<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whatsapp extends Model
{
    use HasFactory;

   

    public static function sendImage($session, $phone, $nomeImagen, $detalhes)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "image" => array(
                    "url" => $nomeImagen // public_path('uploads/' . $nomeImagen)
                ),
                "caption" => $detalhes
            ),
            "delay" => 3
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        //  file_put_contents(Utils::createCode() . ".txt", $response);

        curl_close($curl);
    }
    public static function sendMessagem($session, $phone, $texto)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "number": "' . $phone . '",
                                        "message": {
                                            "text": "' . $texto . '"
                                        },
                                        "delay": 3
                                    }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    }

    public static function sendMessagewithOption($session, $phone, $text, $options)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "text" => $text,
                "options" => $options,
            ),
            "delay" => 3
        );


        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public static function sendAudio($session, $phone)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "number": "' . $phone . '",
            "message": {
                "audio": {
                    "url" : "http://localhost:3333/static/audio/2F49EE65082AB66116EBFC03DC26C44D.ogg?sessionId=JOSE_1&messageId=2F49EE65082AB66116EBFC03DC26C44D"
                }
            },
            "delay": 0
        }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
