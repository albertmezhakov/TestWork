<?php
/*
 * Copyright (c) 2021.
 * TestWork
 * Author: BerhtAdal
 */

namespace app\controllers;


use app\helpers\OAuth2;
use app\models\Reminders;
use Yii;
use yii\web\Controller;

class ApiController extends Controller
{

    public function actionDelete($id)
    {
        $user = json_decode(OAuth2::apiRequest(Yii::$app->params['OAuth2']['API_URL']));
        if ($user->id == Reminders::findOne($id)['user_id']) {
            Reminders::findOne($id)->delete();
            return $this->asJson(["message" => "200: OK", "code" => 200]);
        }
        return $this->asJson(["message" => "401: Unauthorized", "code" => 401]);
    }


}