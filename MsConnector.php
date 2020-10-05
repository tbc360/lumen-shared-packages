<?php

namespace TbcConnect\MsConnector;

use GuzzleHttp\Client;

class MsConnector
{

  
    public static function httpRequest( array $params )
    {

        if (!isset($params['route'])) return 'Route not found';

        $method  = !isset($params['method'])  ? 'POST'  : $params['method'];
        $data    = !isset($params['data'])    ? []      : $params['data'];
        $headers = !isset($params['headers']) ? []      : $params['headers'];

        try {

            $client  = new Client();
            $url     = isset($params['app']) ? env(strtoupper($params['app']) . '_API_URL') . '/' . env(strtoupper($params['app']) . '_VERSION') . '/' . $params['route'] : $params['route'];
            
            $request = $client->request($method, $url, [
                'form_params' => $data, 
                'headers'     => $headers
            ]);

            $result                = [];
            $result['status_code'] = $request->getStatusCode();
            $result['headers']     = $request->getHeader('content-type');
            $result['body']        = $request->getBody();

            return $result;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $result                = [];
            $result['status_code'] = $e->getCode();
            $result['headers']     = $e->getResponse()->getHeader('content-type');
            $result['body']        = $e->getResponse()->getBody(true);
            // აქ შეგვიძლია ლოგის შენახვა.
            return $result;
        }

    }

}
