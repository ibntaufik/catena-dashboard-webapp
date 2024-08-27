<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ClientException;

class BaseClient
{
    private $accessToken;
    private $options = [];
    
    public function __construct(){
        
    }
    
    public function call($url, $header_params, $request_params, $method = "POST"){
        
        $response = [
            "response"  => [
                "code"      => 400,
                "message"   => "Failed to call endpoint",
            ],
            "data"      => []
        ];
        
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];
            
            if(count($header_params) > 0){
                foreach ($header_params as $key => $value){
                    $headers[$key] = $value;
                }
            }
            
            $options = [
                'headers' => $headers,
                'json' => $request_params,
            ];
            
            $client = new Client();
            if($method == "POST"){
                $payload = $client->post($url, $options);
            } else {
                $payload = $client->get($url, $options);
            }
            
            if(in_array($payload->getStatusCode(), [200,201,204])){
                $response = json_decode($payload->getBody()->getContents(), true);
                if(!empty($response)){
                    return $response;
                }
            } else {
                throw new \Exception("Failed to process request");
            }
        } catch (\Exception | GuzzleException | ClientException $e) {
            Log::info("Line #".$e->getLine().": ".$e->getMessage());
            Log::info($e->getTraceAsString());
        }
        
        return $response;
    }

    public function pushNotificationOneSignal($content){
        try {


            $client = new BaseClient();
            $url = config("constant.one_signal.url");
            $header_params = [
                "Content-Type"  => "application/json; charset=utf-8",
                "Authorization" => "Basic ".config("constant.one_signal.token")
            ];
            $request_params = [
                "app_id"        => config("constant.one_signal.app_id"),
                "headings"      => [
                    "en"        => "Purchase Order",
                ],
                "contents"      => [
                    "en"        => $content,
                ],
                "included_segments" => ["Active Subscriptions"],
            ];
            $client->call($url, $header_params, $request_params);
        } catch(\Exception $e){

        }
    }
}