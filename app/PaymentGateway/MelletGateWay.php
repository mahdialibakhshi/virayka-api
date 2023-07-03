<?php

namespace App\PaymentGateway;


use App\Models\PaymentMethods;
use App\Models\Transaction;
use Illuminate\Http\Request;
use nusoap_client;

class MelletGateWay extends Payment
{
    private $terminalId;
    private $userName;
    private $userPassword;
    private $payDate;
    private $payTime;
    private $additionalData;
    private $orderId;
    private $callBackUrl;
    private $wsaddr;
    private $result;
    private $amount;
    private $charge_wallet;
    public function __construct($amount,$charge_wallet=null)
    {
        $mellat_config=PaymentMethods::where('name','mellat')->first();
        $this->terminalId=$mellat_config->terminalId;
        $this->userName=$mellat_config->userName;
        $this->userPassword=$mellat_config->userPassword;
        $this->payDate=date('Ymd');
        $this->payTime=date('Gis');
        $this->orderId=time();
        $this->additionalData='';
        $this->amount=$amount;
        $this->charge_wallet=$charge_wallet;
        if ($charge_wallet==='yes'){
            $this->callBackUrl=route('home.payment_wallet_verify');
        }else{
            $this->callBackUrl=route('home.mellat_verify');
        }
        $this->wsaddr='https://bpm.shaparak.ir/pgwchannel/startpay.mellat';
    }

    public function peyment($addressId=null)
    {
        $terminalId=$this->terminalId;
        $userName=$this->userName;
        $userPassword=$this->userPassword;
        $payDate=$this->payDate;
        $payTime=$this->payTime;
        $orderId=$this->orderId;
        $additionalData=$this->additionalData;
        $callBackUrl=$this->callBackUrl;
        $amount=$this->amount;
        $payerId=0;
        include_once('nusoap.php') ;
        $client = new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
        $namespace='http://interfaces.core.sw.bps.com/';
        $err = $client->getError();
        if ($err) {
            echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
            return;
        }
        $parameters = array(
            'terminalId' => $terminalId,
            'userName' => $userName,
            'userPassword' => $userPassword,
            'orderId' => $orderId,
            'amount' => $amount*10,
            'localDate' => $payDate,
            'localTime' => $payTime,
            'additionalData' => $additionalData,
            'callBackUrl' => $callBackUrl,
            'payerId' => $payerId,
        );
        $result = $client->call('bpPayRequest', $parameters, $namespace);
        $this->result=$result;
        if ($client->fault) {
            return [0,'اشکال در اتصال به درگاه پرداخت'];
        }
        else {
            $err = $client->getError();
            if ($err) {
              return [0,$err];
            }
            else {
                $res = explode (',',$result);
                $ResCode = $res[0];
                if ($ResCode == "0") {
                    $token=$res[1];
                    if ($this->charge_wallet==null){
                        $createOrder=parent::createOrder($addressId, $token, 'درگاه پرداخت ملت');
                        if (property_exists('error', $createOrder)) {
                            return $createOrder;
                        }
                    }else{
                        Transaction::create([
                            'user_id' => auth()->id(),
                            'amount' => $amount,
                            'token' => $token,
                            'gateway_name' => 'درگاه پرداخت ملت',
                            'description' => 'َشارژ کیف پول',
                        ]);
                    }
                    return [1];
                }
                else {
                    $message=$this->CheckStatus($ResCode);
                    return [0,$message];
                }
            }
        }
    }

    public function verify()
    {
        $terminalId		= $this->terminalId;
        $userName		= $this->userName;
        $userPassword	= $this->userPassword;
        $ResCode 		= (isset($_POST['ResCode']) && $_POST['ResCode'] != "") ? $_POST['ResCode'] : "";
        if ($ResCode == '0')
        {
            include_once('nusoap.php') ;
            $client 				= new nusoap_client('https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl');
            $namespace 				='http://interfaces.core.sw.bps.com/';
            $orderId 				= (isset($_POST['SaleOrderId']) && $_POST['SaleOrderId'] != "") ? $_POST['SaleOrderId'] : "";
            $verifySaleOrderId 		= (isset($_POST['SaleOrderId']) && $_POST['SaleOrderId'] != "") ? $_POST['SaleOrderId'] : "";
            $verifySaleReferenceId 	= (isset($_POST['SaleReferenceId']) && $_POST['SaleReferenceId'] != "") ? $_POST['SaleReferenceId'] : "";

            $parameters = array(
                'terminalId' 		=> $terminalId,
                'userName' 			=> $userName,
                'userPassword' 		=> $userPassword,
                'orderId' 			=> $orderId,
                'saleOrderId' 		=> $verifySaleOrderId,
                'saleReferenceId' 	=> $verifySaleReferenceId
            );

            $result = $client->call('bpVerifyRequest', $parameters, $namespace);
            if($result == 0)
            {
                $result = $client->call('bpSettleRequest', $parameters, $namespace);

                if($result == 0)
                {
                    if ($this->charge_wallet==null){
                        //update order
                        $updateOrder = parent::updateOrder($_POST['RefId'], $verifySaleReferenceId);
                        if ($updateOrder[0]==0) {
                            return $updateOrder;
                        }
                    }
                    $msg='عملیات پرداخت با موفقیت انجام شد, شناسه پیگیری تراکنش :'.$verifySaleReferenceId;
                    return [1,$msg,$verifySaleReferenceId,$_POST['RefId']];
                } else {
                    $client->call('bpReversalRequest', $parameters, $namespace);

                    //-- نمایش خطا
                    $error_msg = (isset($result) && $result != "") ? $result : "خطا در ثبت درخواست واریز وجه";
                    return [0,$error_msg];
                }
            } else {
                $client->call('bpReversalRequest', $parameters, $namespace);
                //-- نمایش خطا
                $error_msg = (isset($result) && $result != "") ? $result : "خطا در عملیات وریفای تراکنش";
                return [0,$error_msg];
            }
        } else {
            //-- نمایش خطا
            $error_msg = (isset($ResCode) && $ResCode != "") ? $this->CheckStatus($ResCode) : "تراکنش ناموفق";
            return [0,$error_msg];
        }
    }

    public function showEndForm(){
        $terminalId=$this->terminalId;
        $userName=$this->userName;
        $userPassword=$this->userPassword;
        $payDate=$this->payDate;
        $payTime=$this->payTime;
        $orderId=$this->orderId;
        $additionalData=$this->additionalData;
        $callBackUrl=$this->callBackUrl;
        $wsaddr = $this->wsaddr;
        $result = $this->result;
        $amount = $this->amount;
        $res = explode (',',$result);
        $payerId=0;
        ?>
        <div style="text-align: center;margin:100px auto;width:400px;height: 150px;background-color: #f0f7ff; font-family:Tahoma, Geneva, sans-serif; border:1px solid #c5e2ff; color:#107df6; font-size:11px; line-height:20px; padding-top:3px; padding:5px 14px; -webkit-border-radius:8px; -moz-border-radius:8px; border-radius:8px; text-align:right">
            <p style="text-align: center"><img src="" /></p>
            <p style="text-align: center">در حال اتصال به درگاه بانک ... لطفا کمی صبر نمایید</p>
        </div>
        <form name="paymentform" id="paymentform" method="post" action="<?php echo $wsaddr; ?>">
            <input type="hidden" name="TerminalId" value="<?php echo $terminalId; ?>">
            <input type="hidden" name="UserName" value="<?php echo $userName; ?>">
            <input type="hidden" name="UserPassword" value="<?php echo $userPassword; ?>">
            <input type="hidden" name="PayDate" id="PayDate" value="<?php echo $payDate; ?>">
            <input type="hidden" name="PayTime" id="PayTime" value="<?php echo $payTime; ?>">
            <input type="hidden" name="PayAmount" id="PayAmount" value="<?php echo $amount; ?>">
            <input type="hidden" name="PayOrderId" id="PayOrderId" value="<?php echo $orderId; ?>">
            <input type="hidden" name="PayAdditionalData" id="PayAdditionalData" value="<?php echo $additionalData; ?>">
            <input type="hidden" name="PayCallBackUrl" id="PayCallBackUrl" value="<?php echo $callBackUrl; ?>">
            <input type="hidden" name="PayPayerId" id="PayPayerId" value="<?php echo $payerId; ?>">
            <input type="hidden" name="RefId" id="RefId" value="<?php echo $res[1]; ?>">
            <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token() ?>">

        </form>
        <br>
        <script type="text/javascript">document.getElementById('paymentform').submit();</script>

        <?php
        dd('ok');
    }

    function CheckStatus($ecode)
    {
        $tmess="شرح خطا:";
        switch ($ecode)
        {
            case 0:
                $tmess="تراکنش با موفقيت انجام شد";
                break;
            case 11:
                $tmess="شماره کارت معتبر نيست";
                break;
            case 12:
                $tmess= "موجودي کافي نيست";
                break;
            case 13:
                $tmess= "رمز دوم شما صحيح نيست";
                break;
            case 14:
                $tmess= "دفعات مجاز ورود رمز بيش از حد است";
                break;
            case 15:
                $tmess= "کارت معتبر نيست";
                break;
            case 16:
                $tmess= "دفعات برداشت وجه بيش از حد مجاز است";
                break;
            case 17:
                $tmess= "کاربر از انجام تراکنش منصرف شده است";
                break;
            case 18:
                $tmess= "تاريخ انقضاي کارت گذشته است";
                break;
            case 19:
                $tmess= "مبلغ برداشت وجه بيش از حد مجاز است";
                break;
            case 111:
                $tmess= "صادر کننده کارت نامعتبر است";
                break;
            case 112:
                $tmess= "خطاي سوييچ صادر کننده کارت";
                break;
            case 113:
                $tmess= "پاسخي از صادر کننده کارت دريافت نشد";
                break;
            case 114:
                $tmess= "دارنده کارت مجاز به انجام اين تراکنش نمي باشد";
                break;
            case 21:
                $tmess= "پذيرنده معتبر نيست";
                break;
            case 23:
                $tmess= "خطاي امنيتي رخ داده است";
                break;
            case 24:
                $tmess= "اطلاعات کاربري پذيرنده معتبر نيست";
                break;
            case 25:
                $tmess= "مبلغ نامعتبر است";
                break;
            case 31:
                $tmess= "پاسخ نامعتبر است";
                break;
            case 32:
                $tmess= "فرمت اطلاعات وارد شده صحيح نيست";
                break;
            case 33:
                $tmess="حساب نامعتبر است";
                break;
            case 34:
                $tmess= "خطاي سيستمي";
                break;
            case 35:
                $tmess= "تاريخ نامعتبر است";
                break;
            case 41:
                $tmess= "شماره درخواست تکراري است";
                break;
            case 42:
                $tmess= "تراکنش Sale يافت نشد";
                break;
            case 43:
                $tmess= "قبلا درخواست Verify داده شده است";
                break;
            case 44:
                $tmess= "درخواست Verify يافت نشد";
                break;
            case 45:
                $tmess= "تراکنش Settle شده است";
                break;
            case 46:
                $tmess= "تراکنش Settle نشده است";
                break;
            case 47:
                $tmess= "تراکنش Settle يافت نشد";
                break;
            case 48:
                $tmess= "تراکنش Reverse شده است";
                break;
            case 49:
                $tmess= "تراکنش Refund يافت نشد";
                break;
            case 412:
                $tmess= "شناسه قبض نادرست است";
                break;
            case 413:
                $tmess= "شناسه پرداخت نادرست است";
                break;
            case 414:
                $tmess= "سازمان صادر کننده قبض معتبر نيست";
                break;
            case 415:
                $tmess= "زمان جلسه کاري به پايان رسيده است";
                break;
            case 416:
                $tmess= "خطا در ثبت اطلاعات";
                break;
            case 417:
                $tmess= "شناسه پرداخت کننده نامعتبر است";
                break;
            case 418:
                $tmess= "اشکال در تعريف اطلاعات مشتري";
                break;
            case 419:
                $tmess= "تعداد دفعات ورود اطلاعات بيش از حد مجاز است";
                break;
            case 421:
                $tmess= "IP معتبر نيست";
                break;
            case 51:
                $tmess= "تراکنش تکراري است";
                break;
            case 54:
                $tmess= "تراکنش مرجع موجود نيست";
                break;
            case 55:
                $tmess= "تراکنش نامعتبر است";
                break;
            case 61:
                $tmess= "خطا در واريز";
                break;
        }
        return $tmess;
    }

}
