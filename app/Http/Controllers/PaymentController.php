<?php

namespace App\Http\Controllers;
use HTTP_Request2;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Session;

use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
// use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Uuid;

class PaymentController extends Controller
{

    public function create_payment(){
        return view('payment');
    }

    public function authenticate(){

        $client = new Client();
        $url = 'https://sandbox.api.payme.hsbc.com.hk/oauth2/token';

        $authenticationData  = [
            'client_id' =>  env('PAYME_CLIENT_ID'),
            'client_secret' => env('PAYME_CLIENT_SECRET'),
            // Add other authentication data as required
        ];

        $headers = array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
                'api-version' => '0.12', // Replace with the correct version
        );

        try {
            $response = $client->request('POST', $url, [
                'form_params' => $authenticationData ,
                'headers' => $headers, // Add custom headers here
                // Add any other options you need here
            ]);

    
            $statusCode = $response->getStatusCode();

            $stream = $response->getBody();
            $content = $stream->getContents();
            echo $content;
        } catch (\Exception $e) {
            // Handle errors and exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index(Request $request){

        $client = new Client();
        $url = 'https://sandbox.api.payme.hsbc.com.hk/payments/paymentrequests';

        $headers = [
            'Api-Version' => '0.12',  
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer ".$request->access_token,
            'Accept-Language' =>  'en-US',
            'Trace-Id' =>$request->uuid,
            'Digest' => $request->digest,    
            'Request-Date-Time' =>$request->dateTime,
            'signature' => $request->signature,
        ];

        $jsonData = '{
                    "totalAmount": 2.81,
                    "currencyCode": "HKD",
                    "effectiveDuration":600,
                    "notificationUri":"http://127.0.0.1:8000/return",
                    "appSuccessCallback":"http://127.0.0.1:8000/success",
                        "appFailCallback":"http://127.0.0.1:8000/failure",
                    "merchantData": {
                        "orderId": "ID12345678",
                        "orderDescription": "Description displayed to customer",
                        "additionalData": "Arbitrary additional data - logged but not displayed",
                        "shoppingCart": [
                            {
                                "category1": "General categorization",
                                "category2": "More specific categorization",
                                "category3": "Highly specific categorization",
                                "quantity": 1,
                                "price": 1,
                                "name": "Item 1",
                                "sku": "SKU987654321",
                                "currencyCode": "HKD"
                            },
                            {
                                "category1": "General categorization",
                                "category2": "More specific categorization",
                                "category3": "Highly specific categorization",
                                "quantity": 2,
                                "price": 1,
                                "name": "Item 2",
                                "sku": "SKU678951234",
                                "currencyCode": "HKD"
                            }
                        ]
                    }
                }';

                //  $message_body =   json_encode($jsonData);

        try {
            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'body' => $jsonData
                // Add any other options you need here
            ]);

            $statusCode = $response->getStatusCode();
            // $data =  json_decode($response->getBody(), true);
            $getContents = $response->getBody()->getContents();
            echo "1";
            echo "<pre>";
            // Handle the API response data here
             echo $getContents;
            // return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle request exceptions, e.g., when the API responds with an error
            // $errorResponse = $e->getResponse()->getBody()->getContents();
            $errorResponse = $e->getMessage();
            echo "2";
            echo $errorResponse;
            // return response()->json(['error' => $errorResponse], 400);
        }
        
        catch (\Exception $e) {
            // Handle errors and exceptions
            // return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            echo "3";
            echo $e->getMessage();
        }

    }
    
    public function success(){
        return view('success');
    }
    public function failure(){
        return view('failure');
    }

    public function generate_uuid(){
        $uuid = Str::uuid();
        echo $uuid;
    }

    public function request_date_time(){
        $datetime = Carbon::now();
        $formattedDate = $datetime->format(\DateTime::RFC3339);
        $formattedDate = $datetime->format(Carbon::RFC3339);
        echo $formattedDate."<br>";
        echo Str::uuid();
    }

    public function test_image(){
        return view('imageTest');
    }

    public function return(){
        return view('return');
    }

    public function paycode(){
      
        $data = array(
            'link' =>  "https://mobsandboxprod.paymebiz.hsbc.com.hk/ce4793e4-ba71-4d27-8dc8-3772921af734?appSuccessCallback=http://127.0.0.1:8000/success",

            'logo' => "https://shopfront.paymebiz.hsbc.com.hk/onboarding/df45d63700123b08cdbd5e7cf7c4264eb40abdf1dced3b2464e5a74d3d42a8f8/businessLogo_300x300.png"
        );

        return view('imageTest', $data);
    }

    public function get_payment_request_status($id){
        
        $client = new Client();
        $url = 'https://sandbox.api.payme.hsbc.com.hk/payments/paymentrequests/'.$id;

        $header = [
            'Api-Version' => '0.12',  
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer ".$accessToken,
            'Accept-Language' =>    'en-US',
            'Trace-Id' => '66f5c072-f7aa-49e9-b808-46fc30d013f1',
            'Digest' => 'SHA-256=FjsYVqxSdwbUVO0PgabaxBdhkHQVb4fim2oFOLu44tY=',    
            'Request-Date-Time' =>  '2023-10-30T12:31:30.963Z',
            'signature' => 'keyId="f4ef6321-a725-4c8e-a142-86dcfbe124f2",algorithm="hmac-sha256",headers="(request-target) Api-Version Request-Date-Time Trace-Id Authorization Digest",signature="3uZWnMgkhOJqGU29fEG0PrvkcKPnxgmGKmKhyuPxImk="',
            // 'X-HSBC-Merchant-Id'=> '50e1de21-e0cd-41a4-b384-736e38b3c08d',
        ];
    }


    // public function create_token(){

    //     $request = new Http_Request2('https://sandbox.api.payme.hsbc.com.hk/oauth2/token');
    //     $url = $request->getUrl();

    //     $headers = array(
    //         'Content-Type' => 'application/x-www-form-urlencoded',
    //         'Accept' => 'application/json',
    //         'api-version' => '0.12', // Replace with the correct version
    //     );
        
    //     $request->setHeader($headers);
    //     $parameters = array(
    //         // Request parameters
    //         'client_id' =>  env('PAYME_CLIENT_ID'),
    //         'client_secret' => env('PAYME_CLIENT_SECRET'),
    //     );
            
    //     $url->setQueryVariables($parameters);
    //     $request->setMethod(HTTP_Request2::METHOD_POST);
    //     $requestBody = http_build_query($parameters);

    //     // Request body
    //     $request->setBody($requestBody);
    //     try
    //     {
    //         $response = $request->send();
    //         $statusCode = $response->getStatus();
    //         $body = $response->getBody();

    //         // return array(
    //         //     'status_code' => $statusCode,
    //         //     'body' => $body,
    //         // );

    //         return $body;

    //         // return response()->json([
    //         //     'status_code' => $statusCode,
    //         //     'body' => $body,
    //         // ]);

    //     }
    //     catch (HttpException $ex)
    //     {
    //         return response()->json(['error' => $ex->getMessage()], 500);
    //     }
    // }

    // public function index(){    

    //     // generating access token from create token api
    //     // $jsonString = $this->create_token();
    //     // $data = json_decode($jsonString);
    //     // $accessToken = $data->accessToken;
    //     // $expiresOn = $data->expiresOn;
    //     // $tokenType = $data->tokenType;

    //     $accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6IjlHbW55RlBraGMzaE91UjIybXZTdmduTG83WSIsImtpZCI6IjlHbW55RlBraGMzaE91UjIybXZTdmduTG83WSJ9.eyJhdWQiOiJmOWZkYzI2Mi1kYzU5LTQyMTAtOWNkMS1kODhhNzMyMDk1MmUiLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8xNjMwMGY5MC1hMzk5LTRmOGUtYjhjMy1jNTc4NDZiNmEyNDcvIiwiaWF0IjoxNjk4OTk2NzYzLCJuYmYiOjE2OTg5OTY3NjMsImV4cCI6MTY5OTAwMDY2MywiYWlvIjoiRTJGZ1lGaG92MEtvYXNHVmh5ODBEdjl5U1dkK0NRQT0iLCJhcHBpZCI6ImIwNWZlZmQ1LWVkNDAtNDg2ZC1hMDQ2LTA5MjdjYzVkNmEyNCIsImFwcGlkYWNyIjoiMSIsImlkcCI6Imh0dHBzOi8vc3RzLndpbmRvd3MubmV0LzE2MzAwZjkwLWEzOTktNGY4ZS1iOGMzLWM1Nzg0NmI2YTI0Ny8iLCJvaWQiOiI5MTUwM2I0NS0xYzFkLTQxZTUtYTNmNS1mM2YzYTY5MDBkZGMiLCJyaCI6IjAuQVZRQWtBOHdGcG1qamstNHc4VjRScmFpUjJMQ19mbFozQkJDbk5IWWluTWdsUzVVQUFBLiIsInJvbGVzIjpbInJlZnVuZCIsInBheW1lbnRSZXF1ZXN0IiwiZGV2ZWxvcGVyIl0sInN1YiI6IjkxNTAzYjQ1LTFjMWQtNDFlNS1hM2Y1LWYzZjNhNjkwMGRkYyIsInRpZCI6IjE2MzAwZjkwLWEzOTktNGY4ZS1iOGMzLWM1Nzg0NmI2YTI0NyIsInV0aSI6ImxsMWFVcmJncmtDQ2tRbkdvR2xGQUEiLCJ2ZXIiOiIxLjAifQ.wFsMW0wmtJulKxFW-KpqVirEkU3AGudTXbk1cy0yqI8PGc8-Q98NvbZu0LJGzNOYthtujk94wvRDcTFM984kVd7EHWzMRNg1i1NOUKiwGaDyQzsVlFwfBDm7rSabpEDWHPrII9swmHgARGqU-h2EpL18DYDfmeNuRgRMgZGqrgKHo9Bn7nM_BC3cScO3IpPXfdwwPa2DqfGUdX0JFGFBXVX-xSbA6QmzeDec5o70rjuiJpDnGYtr6ku1I6QxTUG7GDWqWzfXt5NwPtdtiUXbu86WOT25X0xmg4-cS5u9Om54qhOZeHq7vCVG7402lydXRoxIWdz450wSwNSS54wyIw';

    //     $topSecret = env('PAYME_CLIENT_SECRET');
    //     $algo = array('algorithm' => 'HS256');
    //     // $decoded = JWT::decode($accessToken, new Key($topSecret, 'HS256'));  
      
    //     // print_r($decoded);
    //     $request = new Http_Request2('https://sandbox.api.payme.hsbc.com.hk/payments/paymentrequests');
    //     $url = $request->getUrl();
   
    //     $headers = [
    //                 'Api-Version' => '0.12',  
    //                 'Accept' => 'application/json',
    //                 'Content-Type' => 'application/json',
    //                 'Authorization' => "Bearer ".$accessToken,
    //                 'Accept-Language' =>    'en-US',
    //                 'Trace-Id' => '66f5c072-f7aa-49e9-b808-46fc30d013f1',
    //                 'Digest' => 'SHA-256=FjsYVqxSdwbUVO0PgabaxBdhkHQVb4fim2oFOLu44tY=',    
    //                 'Request-Date-Time' =>  '2023-11-03T07:42:00Z',
    //                 'signature' => 'keyId="f4ef6321-a725-4c8e-a142-86dcfbe124f2",algorithm="hmac-sha256",headers="(request-target) Api-Version Request-Date-Time Trace-Id Authorization Digest",signature="3uZWnMgkhOJqGU29fEG0PrvkcKPnxgmGKmKhyuPxImk="',
    //                 'X-HSBC-Merchant-Id'=> '50e1de21-e0cd-41a4-b384-736e38b3c08d',
    //             ];

    //     // $jsonData = '{
    //     //             "totalAmount": 2.81,
    //     //             "currencyCode": "HKD",
    //     //             "effectiveDuration":600,
    //     //             "notificationUri":"http://127.0.0.1:8000/return",
    //     //             "appSuccessCallback":"http://127.0.0.1:8000/success",
    //     //                 "appFailCallback":"http://127.0.0.1:8000/failure",
    //     //             "merchantData": {
    //     //                 "orderId": "ID12345678",
    //     //                 "orderDescription": "Description displayed to customer",
    //     //                 "additionalData": "Arbitrary additional data - logged but not displayed",
    //     //                 "shoppingCart": [
    //     //                     {
    //     //                         "category1": "General categorization",
    //     //                         "category2": "More specific categorization",
    //     //                         "category3": "Highly specific categorization",
    //     //                         "quantity": 1,
    //     //                         "price": 1,
    //     //                         "name": "Item 1",
    //     //                         "sku": "SKU987654321",
    //     //                         "currencyCode": "HKD"
    //     //                     },
    //     //                     {
    //     //                         "category1": "General categorization",
    //     //                         "category2": "More specific categorization",
    //     //                         "category3": "Highly specific categorization",
    //     //                         "quantity": 2,
    //     //                         "price": 1,
    //     //                         "name": "Item 2",
    //     //                         "sku": "SKU678951234",
    //     //                         "currencyCode": "HKD"
    //     //                     }
    //     //                 ]
    //     //             }
    //     //         }';

    //     $request->setHeader($headers);
        
    //     $parameters = array(
    //         "totalAmount" => 3.81,
    //         "currencyCode" => "HKD"
    //     );
        
    //     $url->setQueryVariables($parameters);
    //     $requestBody = http_build_query($parameters);

    //     // Request body
    //     $request->setBody($requestBody);
        
    //     $request->setMethod(HTTP_Request2::METHOD_POST);
        
    //     try
    //     {
    //         $response = $request->send();
    //         echo $response->getBody();
    //     }
    //     catch (HttpException $ex)
    //     {
    //         echo $ex;
    //     }
    // }
   

    // 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

}

