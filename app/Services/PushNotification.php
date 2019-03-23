<?php
namespace App\Services;

use App\Http\Controllers\LINENotifyController;
use App\LINE_Notify_User;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use SoapBox\Formatter\Formatter;

use Carbon\Carbon;


class PushNotification
{
    public static function sendMsg($access_token, $msg)
    {
        $client = new GuzzleHttpClient();
        try {
            $response = $client->request('POST', 'https://notify-api.line.me/api/notify', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'form_params' => [
                    'message' => $msg,
                ],
                'timeout' => 10,
            ]);
        } catch (GuzzleHttpTransferException $e) {
            $status = $e->getCode();
            if ($status == 400) {
                throw new \Exception('400 - Unauthorized request');
            } elseif ($status == 401) {
                throw new \Exception('401 -  Invalid access token');
            } elseif ($status == 500) {
                throw new \Exception('500 - Failure due to server error');
            } else {
                throw new \Exception('Processed over time or stopped');
            }
        }
        return $response;
    }

    public function getStatus($access_token)
    {
        $client = new GuzzleHttpClient();
        try {
            $response = $client->request('GET', 'https://notify-api.line.me/api/status', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'timeout' => 10,
            ]);
            $response = $response->getBody()->getContents();
            $formatter = Formatter::make($response, Formatter::JSON);
            $json = $formatter->toArray();
            return $json;
        } catch (GuzzleHttpTransferException $e) {
            return $e;
        }
    }

    public function setPushList(){

    }

    public function setPushTmp(){

    }
}
