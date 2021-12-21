<?php
/*
 * Copyright (c) 2021.
 * TestWork
 * Author: BerhtAdal
 */

namespace app\helpers;

use Yii;

class OAuth2
{
    public static function apiRequest($url, $post = false, $headers = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if ($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

        $headers[] = 'Accept: application/json';

        if (!empty(Yii::$app->request->cookies->getValue('access_token')))
            $headers[] = 'Authorization: Bearer ' . Yii::$app->request->cookies->getValue('access_token');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        return curl_exec($ch);
    }

    public static function logout($url, $data = array())
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
            CURLOPT_POSTFIELDS => http_build_query($data),
        ));
        return curl_exec($ch);
    }

    public static function joinGuild()
    {
        $user_id = json_decode(static::apiRequest(Yii::$app->params['OAuth2']['API_URL']))->id;
        $ch = curl_init(sprintf(Yii::$app->params['OAuth2']['GUILD_URL'], Yii::$app->params['OAuth2']['GUILD_ID'], $user_id));
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_POSTFIELDS => json_encode(array("access_token" => Yii::$app->request->cookies->getValue('access_token'))),
            CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Authorization: Bot ' . Yii::$app->params['OAuth2']['BOT_TOKEN'])
        ));
        curl_exec($ch);
    }

}
