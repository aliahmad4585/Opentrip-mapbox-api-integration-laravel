<?php

namespace App\Helpers;

use App\Exceptions\HttpCallException;

class HttpHelper
{
    /**
     * Excute the http calls
     * @param string $type (GET|POST|PUT|DELETE)
     * @param string $url
     * @param string $headers (Optional)
     * @param array $body (Optional)
     * @return mixed
     */
    public function curlExecute(
        string $type,
        string $url,
        array $headers = [],
        array|string $body = null
    ): mixed {

        try {
            $options = array(
                CURLOPT_URL             => $url,
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_ENCODING        => "",
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => $type,
                CURLOPT_POSTFIELDS      => $body,
                CURLOPT_REFERER         => env('APP_URL'),
                CURLOPT_HTTPHEADER      => $headers
            );

            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $curlResponse = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return null;
            } else {
                return $curlResponse;
            }
        } catch (HttpCallException $th) {
            throw new HttpCallException("Error while calling the Opentrip Api");
        }
    }
}
