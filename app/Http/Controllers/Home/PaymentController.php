<?php

namespace App\Http\Controllers\Home;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\PaymentMethods;
use App\Models\Product;
use App\Models\ProductAttrVariation;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Notifications\userChargeWallet;
use App\PaymentGateway\MelletGateWay;
use App\PaymentGateway\Pay;
use App\Models\Transaction;
use App\PaymentGateway\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\PaymentGateway\Zarinpal;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;
use Pasargad\Pasargad;


class PaymentController extends Controller
{
    private $wallet_amount;

    public function payment(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            alert()->error('ابتدا بایستی وارد سایت شوید')->autoclose(3000);
            return redirect()->route('index');
        }
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'payment_method' => 'required',
        ]);

        if ($validator->fails()) {
            alert()->error('انتخاب آدرس تحویل سفارش الزامی می باشد', 'دقت کنید')->persistent('ok');
            return redirect()->back();
        }

        $checkCart = $this->checkCart();
        if (array_key_exists('error', $checkCart)) {
            alert()->error($checkCart['error'], 'دقت کنید')->persistent('ok');
            return redirect()->route('home.cart');
        }
        $check_coupon = $this->check_coupon();
        if (array_key_exists('error', $check_coupon)) {
            alert()->error($check_coupon['error'], 'دقت کنید');
            return redirect()->route('home.index');
        }
        //پرداخت از کیف پول
        $use_wallet = 0;
        if (session()->exists('use_wallet')) {
            $use_wallet = session()->get('use_wallet');
        }
        if (summery_cart()['payment'] == 0 and $use_wallet != 0) {
            $payment = new Payment();
            $order = $payment->createOrder($request->address_id, summery_cart()['payment'], 'پرداخت از کیف پول');
            $result = $payment->updateOrder('پرداخت از کیف پول', 'پرداخت از کیف پول', $order->id);
            if ($result[0] == 1 or $result[0] == 2) {
                alert()->success('خرید شما با موفقیت انجام شد', 'باتشکر')->persistent('ok');
                return redirect()->route('home.index');
            }
            if ($result[0] == 0) {
                alert()->error($result[1], 'دقت کنید')->persistent('ok');
                return redirect()->route('home.checkout.preview');
            }
        }
        if ($request->payment_method == 'pay') {
            session()->put('payment_type', 1);
            $payGateway = new Pay();
            $payGatewayResult = $payGateway->send(summery_cart()['payment'], $request->address_id);
            if (array_key_exists('error', $payGatewayResult)) {
                alert()->error($payGatewayResult['error'], 'دقت کنید')->persistent('ok');
                return redirect()->route('home.checkout.preview');
            } else {
                return redirect()->to($payGatewayResult['success']);
            }
        }
        if ($request->payment_method == 'zarinpal') {
            session()->put('payment_type', 1);
            $zarinpalGateway = new Zarinpal();
            $zarinpalGatewayResult = $zarinpalGateway->send(summery_cart()['payment'], 'خرید از طریق درگاه زرین پال', $request->address_id);
            if (array_key_exists('error', $zarinpalGatewayResult)) {
                alert()->error($zarinpalGatewayResult['error'], 'دقت کنید')->persistent('ok');
                return redirect()->route('home.checkout.preview');
            } else {
                return redirect()->to($zarinpalGatewayResult['success']);
            }
        }
        if ($request->payment_method == 'cash') {
            session()->put('payment_type', 2);
            $payment = new Payment();
            $order = $payment->createOrder($request->address_id, summery_cart()['payment'], 'cash', 'cash');
            $payment->updateOrder('cash', 'cash', $order->id);
            alert()->success('خرید شما با موفقیت انجام شد', 'باتشکر')->autoclose('3000');
            return redirect()->route('home.index');
        }
        if ($request->payment_method == 'pasargad') {
            try {
                session()->put('payment_type', 1);
                $amount = summery_cart()['payment'];
                // Tip! Initialize this property in your payment service __constructor() method!
                $pasargad = new Pasargad(
                    "https://sorinhamrah.ir//payment-pasargad_verify",
                    "core/private.xml");
                //create order
                $payment = new Payment();
                $order = $payment->createOrder($request->address_id, null, 'درگاه پرداخت پاسارگاد');
                //Set Amount
                $pasargad->setAmount($amount * 10);
                // Set Invoice Number (it MUST BE UNIQUE)
                // Set Unique Invoice Number that you want to check the result
                $pasargad->setInvoiceNumber($order->id);
                // set Invoice Date of your Invoice
                $date = $order->created_at;
                $pasargad->setInvoiceDate(Carbon::parse($date)->format('Y/m/d H:i:s'));
                $redirectUrl = $pasargad->redirect();
                $token = $pasargad->getToken();
                // and redirect user to payment gateway:
                return redirect()->to($redirectUrl);
            } catch (\Exception $ex) {
                DB::rollBack();
                var_dump($ex->getMessage());
                die();
            }

            // Set Transaction refrence id received in
            $pasargad->setTransactionReferenceId("636843820118990203");

            // Set Unique Invoice Number that you want to check the result
            $pasargad->setInvoiceNumber(4029);

            // set Invoice Date of your Invoice
            $pasargad->setInvoiceDate("2021/08/08 11:54:03");

            // check Transaction result
            var_dump($pasargad->checkTransaction());
        }
        if ($request->payment_method == 'mellat') {
            session()->put('payment_type', 1);
            $amount = summery_cart()['payment'];
            $mellat_Gateway = new MelletGateWay($amount);
            $result = $mellat_Gateway->peyment($request->address_id);
            if ($result[0] === 1) {
                $mellat_Gateway->showEndForm();
            } else {
                alert()->error($result[1], 'دقت کنید')->persistent('ok');
                return redirect()->route('home.checkout.preview');
            }
        }

        alert()->error('درگاه پرداخت انتخابی اشتباه میباشد', 'دقت کنید');
        return redirect()->route('home.checkout');
    }

    public function paymentVerify(Request $request, $gatewayName)
    {
        if ($gatewayName == 'pay') {
            $payGateway = new Pay();
            $payGatewayResult = $payGateway->verify($request->token, $request->status);

            if (array_key_exists('error', $payGatewayResult)) {
                alert()->error($payGatewayResult['error'], 'دقت کنید')->persistent('حله');
                return redirect()->route('home.checkout.preview');
            } else {
                alert()->success($payGatewayResult['success'], 'با تشکر');
                return redirect()->route('home.index');
            }
        }

        if ($gatewayName == 'zarinpal') {
            $amounts = summery_cart()['payment'];
            $zarinpalGateway = new Zarinpal();
            $zarinpalGatewayResult = $zarinpalGateway->verify($request->Authority, $amounts);

            if (array_key_exists('error', $zarinpalGatewayResult)) {
                alert()->error($zarinpalGatewayResult['error'], 'دقت کنید')->persistent('حله');
                return redirect()->route('home.checkout.preview');
            } else {
                alert()->success($zarinpalGatewayResult['success'], 'با تشکر');
                return redirect()->route('home.index');
            }
        }
        alert()->error('مسیر بازگشت از درگاه پرداخت اشتباه می باشد', 'دقت کنید');
        return redirect()->route('home.orders.checkout');
    }

    public function pasargad_paymentVerify(Request $request)
    {
        $Tref = $request->tref;
        $IN = $request->iN;
        $ID = $request->id;
        $pasargad = new Pasargad(
            "https://itehran.shop/payment-pasargad_verify",
            "core/private.xml");
        // Set Transaction refrence id received in
        $pasargad->setTransactionReferenceId($Tref);

        // Set Unique Invoice Number that you want to check the result
        $pasargad->setInvoiceNumber($IN);

        // set Invoice Date of your Invoice
        $pasargad->setInvoiceDate($ID);

        // check Transaction result
        $result = $pasargad->checkTransaction();
        if (isset($result[0]) and $result[0] === 'error') {
            alert()->warning($result[1])->persistent('ok');
            return redirect()->route('home.checkout.preview');
        }

        if ($result['IsSuccess']) {
            // Set Transaction refrence id received in
            $pasargad->setAmount($result['Amount']);

            // Set Unique Invoice Number that you want to check the result
            $pasargad->setInvoiceNumber($result['InvoiceNumber']);

            // set Invoice Date of your Invoice
            $pasargad->setInvoiceDate($result['InvoiceDate']);

            // verify payment:
            $verify = $pasargad->verifyPayment();
            if ($verify['IsSuccess']) {
                $payment = new Payment();
                $order_id = $result['InvoiceNumber'];
                $TransactionReferenceID = $result['TransactionReferenceID'];
                //update order
                $updateOrder = $payment->updateOrder(null, $TransactionReferenceID, $order_id);
                if ($updateOrder[0] == 0) {
                    return $updateOrder;
                }
                $msg = ' پرداخت با موفقیت انجام شد.شماره تراکنش : ' . $TransactionReferenceID;
                alert()->success($msg, 'با تشکر')->persistent('ok');
                return redirect()->route('home.index');
            }
        } else {
            alert()->warning('پرداخت با خطا مواجه شد')->persistent('ok');
            return redirect()->route('home.checkout.preview');
        }
    }

    public function mellat_paymentVerify()
    {
        $amount = summery_cart()['payment'];
        $mellat_Gateway = new MelletGateWay($amount);
        $response = $mellat_Gateway->verify();
        if ($response[0] == 0) {
            alert()->error($response[1], 'خطا')->persistent('ok');
            return redirect()->route('home.checkout.preview');
        }
        if ($response[0] == 1) {
            alert()->success($response[1], 'با تشکر')->persistent('ok');
            return redirect()->route('home.index');
        }
    }

    public function check_coupon()
    {
        if (session()->has('coupon')) {
            $checkCoupon = checkCoupon(session()->get('coupon.code'));
            if (array_key_exists('error', $checkCoupon)) {
                return $checkCoupon;
            }
        }
        return ['success' => 'success'];
    }

    //charge wallet by user
    public function charge_wallet(Request $request)
    {
        $amount = str_replace(',', '', $request->amount);
        $this->wallet_amount = $amount;
        if ($request->payment_method == 'mellat') {
            //Pay via mellat GateWay
            $mellat_Gateway = new MelletGateWay($amount, 'yes');
            $mellat_Gateway->peyment();
            $result = $mellat_Gateway->peyment();
            if ($result[0] === 1) {
                $mellat_Gateway->showEndForm();
            } else {
                alert()->error($result[1], 'دقت کنید')->persistent('ok');
                return redirect()->route('profile.wallet.index');
            }
        }
        if ($request->payment_method == 'pasargad') {
            //Pay via Pasargad GateWay
            try {
                $pasargad = new Pasargad(
                    "https://itehran.shop/payment_pasargad_wallet_verify",
                    "core/private.xml");
                //Set Amount
                $pasargad->setAmount($amount * 10);
                // Set Invoice Number (it MUST BE UNIQUE)
                // Set Unique Invoice Number that you want to check the result
                $time = time();
                $pasargad->setInvoiceNumber($time);
                $pasargad->setInvoiceDate(Carbon::now()->format('Y/m/d H:i:s'));
                $redirectUrl = $pasargad->redirect();
                $token = $pasargad->getToken();
                Transaction::create([
                    'user_id' => auth()->id(),
                    'amount' => $amount,
                    'token' => $time,
                    'gateway_name' => 'درگاه پرداخت پاسارگاد',
                    'description' => 'َشارژ کیف پول از طریق درگاه پاسارگاد',
                ]);
                // and redirect user to payment gateway:
                return redirect()->to($redirectUrl);
            } catch (\Exception $ex) {
                DB::rollBack();
                var_dump($ex->getMessage());
                die();
            }
        }
    }

    public function payment_wallet_verify()
    {
        $wallet_amount = $this->wallet_amount;
        $mellat_gateWay = new MelletGateWay($wallet_amount, 'yes');
        $result = $mellat_gateWay->verify();
        if ($result[0] == 1) {
            //update transaction status
            $verifySaleReferenceId = $result[2];
            $ref_id = $result[3];
            $transaction = Transaction::where('token', $ref_id)->firstOrFail();
            $transaction->update([
                'status' => 1,
                'ref_id' => $verifySaleReferenceId
            ]);
            $this->charge_wallet_success($transaction->amount, auth()->id());
            $msg = $result[1];
            alert()->success($msg, 'با تشکر')->persistent('ok');
            return redirect()->route('home.profile.wallet.index');
        } else {
            $msg = $result[1];
            alert()->error($msg, 'دقت کنید')->persistent('ok');
            return redirect()->route('home.profile.wallet.index');
        }
    }

    public function payment_pasargad_wallet_verify(Request $request)
    {

        $Tref = $request->tref;
        $IN = $request->iN;
        $ID = $request->id;
        $pasargad = new Pasargad(
            "https://sorinhamrah.ir/payment_pasargad_wallet_verify",
            "core/private.xml");
        // Set Transaction refrence id received in
        $pasargad->setTransactionReferenceId($Tref);

        // Set Unique Invoice Number that you want to check the result
        $pasargad->setInvoiceNumber($IN);

        // set Invoice Date of your Invoice
        $pasargad->setInvoiceDate($ID);

        // check Transaction result
        $result = $pasargad->checkTransaction();
        if ($result[0] === 'error') {
            alert()->warning($result[1])->persistent('ok');
            return redirect()->route('home.profile.wallet.index');
        }

        if ($result['IsSuccess']) {
            // Set Transaction refrence id received in
            $pasargad->setAmount($result['Amount']);

            // Set Unique Invoice Number that you want to check the result
            $pasargad->setInvoiceNumber($result['InvoiceNumber']);

            // set Invoice Date of your Invoice
            $pasargad->setInvoiceDate($result['InvoiceDate']);

            // verify payment:
            $verify = $pasargad->verifyPayment();
            if ($verify['IsSuccess']) {
                $TransactionReferenceID = $result['TransactionReferenceID'];
                //update transaction status
                $transaction = Transaction::where('token', $result['InvoiceNumber'])->firstOrFail();
                $transaction->update([
                    'status' => 1,
                    'ref_id' => $TransactionReferenceID
                ]);
                $this->charge_wallet_success($transaction->amount, auth()->id());
                $msg = 'کیف پول شما با موفقیت شارژ شد. شماره پیگیری : '.$TransactionReferenceID;
                alert()->success($msg, 'با تشکر')->persistent('ok');
                return redirect()->route('home.profile.wallet.index');
            }
        } else {
            alert()->warning('پرداخت با خطا مواجه شد')->persistent('ok');
            return redirect()->route('home.profile.wallet.index');
        }
    }

    public function sendRequest($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null)
    {
        return $this->curl_post('https://pay.ir/pg/send', [
            'api' => $api,
            'amount' => $amount,
            'redirect' => $redirect,
            'mobile' => $mobile,
            'factorNumber' => $factorNumber,
            'description' => $description,
        ]);
    }

    public function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    public function verifyRequest($api, $token)
    {
        return $this->curl_post('https://pay.ir/pg/verify', [
            'api' => $api,
            'token' => $token,
        ]);
    }

    public function charge_wallet_success($amount, $user_id)
    {
        //user wallet
        $wallet = Wallet::where('user_id', $user_id)->first();
        $previous_amount = $wallet->amount;
        $new_amount = $previous_amount + $amount;
        $wallet->update([
            'amount' => $new_amount,
        ]);
        //create wallet history
        WalletHistory::create([
            'user_id' => $user_id,
            'amount' => $amount,
            'type' => 5,
            'increase_type' => 1,
            'previous_amount' => $previous_amount,
        ]);
        //send sms
        $user = auth()->user();
        $user->notify(new userChargeWallet($amount, $new_amount));
    }
}
