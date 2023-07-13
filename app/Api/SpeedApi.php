<?php

namespace App\Api;



use GuzzleHttp\Client;

class SpeedApi
{

    public $url;

    public $client;
    public $username;
    public $password;
    public $authorization;


    public function __construct()
    {
        $this->client = new Client();
        $this->username = 'وب سرویس';
        $this->password = '159753';
        $this->url = 'http://93.118.112.245:8081';

        $this->authorization = 'Bearer '.$this->GetToken();


    }


    public function GetToken()
    {



        $response = $this->client->request('POST',$this->url .'/Serv/token/GetServiceToken?username=وب سرویس&password=159753' ,[

            "headers" => [
                'Content-Type' => 'application/json',

            ],
            "http_errors" => false,
        ]);


        return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ;

    }

    public  function SpeedGet($suburl,$method,$data)
    {
        $token = $this->authorization;




        if ($data == null){
            $response = $this->client->request($method,$this->url . $suburl,[
                "headers" => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $token
                ],
                "http_errors" => false,
            ]);


        }
        else{

            $response = $this->client->request($method,$this->url . $suburl,[
                'json' => $data,
                "headers" => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->authorization
                ],
                "http_errors" => false,
            ]);
        }




        $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()), true );
dd($result);


        if ($result['error'] != null){


            return response()->json([
                'message' => $result['error'],
            ]);
        }



        return $result['data'];



    }


}
