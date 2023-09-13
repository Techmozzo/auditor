<?php

namespace App\Services;

use GuzzleHttp\Client;

class SignNow
{
    protected $client;

    public function __construct()
    {
        // $this->client = new Client([
        //     // 'base_uri' => config('services.signnow.api_key'),
        //     // 'headers' => [
        //     //     'Authorization' => 'Basic ' . base64_encode(config('services.signnow.api_key') . ':' . config('services.signnow.api_secret')),
        //     // ],
        //     'multipart' => [
        //         [
        //             'name' => 'grant_type',
        //             'contents' => 'password'
        //         ]
        //     ],
        //     'headers' => [
        //         'Accept' => 'application/json, ',
        //         'Authorization' => 'Basic '.config('services.signnow.token'),
        //         'Content-Type' => 'multipart/form-data',
        //     ],
        // ]);
    }

    public function checkConnection()
    {
        $client = new Client();

        $response = $client->request('POST', 'https://api-eval.signnow.com/oauth2/token', [
            'multipart' => [
                [
                    'name' => 'grant_type',
                    'contents' => 'password'
                ]
            ],
            'headers' => [
                'Accept' => 'application/json, ',
                'Authorization' => 'Basic '.config('services.signnow.token'),
                'Content-Type' => 'multipart/form-data',
            ],
            'form_params' => [
                'username' => 'intoajohnson@gmail.com',
                'password' => '$inyaJohnson@13'
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        return $data;
    }

    // Add methods to interact with SignNow API here
}
