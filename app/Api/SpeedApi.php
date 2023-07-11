<?php

namespace App\Api;



use GuzzleHttp\Client;

class SpeedApi
{

    public $url;

    public $client;
    public $authorization;

    public function __construct()
    {
        $this->client = new Client();
        $this->url = 'http://93.118.112.245:8081';
        $this->authorization = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1bmlxdWVfbmFtZSI6Ilx1MDY0OFx1MDYyOCBcdTA2MzNcdTA2MzFcdTA2NDhcdTA2Y2NcdTA2MzMiLCJVc2VySUQiOiI1IiwibmJmIjoxNjg4ODkxOTQ2LCJleHAiOjE2ODg5NzgzNDYsImlhdCI6MTY4ODg5MTk0NiwiaXNzIjoiU1JWLUZTIiwiYXVkIjoiU1JWLUZTIiwiY21wbmEiOiJhUnBhX20xMDE6MDk6MDYifQ.ZvNAj-pLZsuu0JwJYaeTGTr976UitWjTlIBigQICEIQ';


    }

    public  function SpeedGet($suburl,$method,$data)
    {


        if ($data == null){
            $response = $this->client->request($method,$this->url . $suburl,[
                "headers" => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->authorization
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




        $result = json_decode($response->getBody()->getContents(), true);
        dd(str_replace('"','`',$response->getBody()->getContents()));


        if ($result['error'] != null){


            return response()->json([
                'message' => $result['error'],
            ]);
        }



        return $result['data'];



    }


}
