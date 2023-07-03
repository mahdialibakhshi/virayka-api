<?php

namespace App\Alopeyk;
use App\Models\AlopeykConfig;

class Alopeyk{

    public static function authenticate(){
        $token=AlopeykConfig::first()->alopeyk_token;
        $token='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEyOTk0MCwiaXNzIjoibG9jYWxob3N0OjEzMzcvZ2VuZXJhdGVfdG9rZW4_bGFuZz1mYSIsImp0aSI6Ilk1SXo3NGptTDBmSk5oQSIsImlhdCI6MTYzNTY3ODc0OCwiZXhwIjoxNjY3MjE0NzQ4fQ.A79rUH9Kz6m-tYL8RtdBkTvdL7SMnnJgjTApDXCMSm8';
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://api.alopeyk.com/api/v2/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer " .$token,
                "X-Requested-With: XMLHttpRequest"
            ],
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:' . $err;
        } else {
            return json_decode($response);
        }
    }

    public static function getAddress(){
        $token=AlopeykConfig::first()->alopeyk_token;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://sandbox-api.alopeyk.com/api/v2/locations?latlng=35.756780%2C51.411255",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer " .
                $token,
                "X-Requested-With: XMLHttpRequest"
            ],
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:' . $err;
        } else {
            echo $response;
        }
    }

    public static function NormalPrice($origin,$destination){
        $token=AlopeykConfig::first()->alopeyk_token;
        $token='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEyOTk0MCwiaXNzIjoibG9jYWxob3N0OjEzMzcvZ2VuZXJhdGVfdG9rZW4_bGFuZz1mYSIsImp0aSI6Ilk1SXo3NGptTDBmSk5oQSIsImlhdCI6MTYzNTY3ODc0OCwiZXhwIjoxNjY3MjE0NzQ4fQ.A79rUH9Kz6m-tYL8RtdBkTvdL7SMnnJgjTApDXCMSm8';
        $curl = curl_init();

        curl_setopt_array($curl, [
                CURLOPT_URL            => "https://api.alopeyk.com/api/v2/orders/price/calc",
                CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING       => "",
  CURLOPT_MAXREDIRS      => 10,
  CURLOPT_TIMEOUT        => 30,
  CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST  => "POST",
  CURLOPT_POSTFIELDS     => json_encode(
            [
                "transport_type" => "motorbike",
                "addresses"      => [
                    $origin,
                    $destination,
                ],
                "has_return" => false,
      "cashed"     => false,
    ]
  ),
  CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json; charset=utf-8",
            "X-Requested-With: XMLHttpRequest"
        ],
]);

$response = curl_exec($curl);
$err      = curl_error($curl);

curl_close($curl);

if ($err) {
    echo 'cURL Error #:' . $err;
} else {
    return json_decode($response);
}
    }
}
