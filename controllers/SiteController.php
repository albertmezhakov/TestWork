<?php

namespace app\controllers;

use app\helpers\OAuth2;
use app\models\ReminderForm;
use app\models\Reminders;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;


class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionIndex()
    {
        $access_token = Yii::$app->request->cookies->getValue('access_token');
        if (!empty($access_token)) {
            OAuth2::joinGuild();
            $user = json_decode(OAuth2::apiRequest(Yii::$app->params['OAuth2']['API_URL']));
            $model = new ReminderForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $db = new Reminders();
                $db->user_id = $user->id;
                $db->content = $model->content;
                $db->unix_time = mktime(explode(':', explode(' ', $model->date)[1])[0],
                    explode(':', explode(' ', $model->date)[1])[1],
                    0,
                    explode('.', explode(' ', $model->date)[0])[1],
                    explode('.', explode(' ', $model->date)[0])[0],
                    explode('.', explode(' ', $model->date)[0])[2]);
                $db->save();
            }
            $model->date = '';
            $model->content = '';
            $query = Reminders::find()->where(['user_id' => $user->id]);
            $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 10]);
            $db = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

            return $this->render('index_login', ['username' => $user->username,
                'db' => $db,
                'pages' => $pages,
                'model' => $model]);
        }
        return $this->render('index');
    }

    public function actionLogout()
    {
        $access_token = Yii::$app->request->cookies->getValue('access_token');
        if (!empty($access_token)) {
            $params = [
                'token' => $access_token,
                'token_type_hint' => 'access_token',
                'client_id' => Yii::$app->params['OAuth2']['CLIENT_ID'],
                'client_secret' => Yii::$app->params['OAuth2']['CLIENT_SECRET'],
            ];
            OAuth2::logout(Yii::$app->params['OAuth2']['REVOKE_URL'], $params);
            $cookies = Yii::$app->response->cookies;
            $cookies->remove('access_token');
            unset($cookies['access_token']);
        }
        return $this->goHome();
    }

    public function actionLogin()
    {
        $params = [
            'client_id' => Yii::$app->params['OAuth2']['CLIENT_ID'],
            'redirect_uri' => Yii::$app->params['OAuth2']['REDIRECT_URI'],
            'response_type' => 'code',
            'scope' => 'identify guilds guilds.join'
        ];
        return $this->redirect(Yii::$app->params['OAuth2']['AUTH_URL'] . '?' . http_build_query($params));
    }

    public function actionCallback($code)
    {
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => Yii::$app->params['OAuth2']['CLIENT_ID'],
            'client_secret' => Yii::$app->params['OAuth2']['CLIENT_SECRET'],
            'redirect_uri' => Yii::$app->params['OAuth2']['REDIRECT_URI'],
            'code' => $code
        ];

        $token = json_decode(OAuth2::apiRequest(Yii::$app->params['OAuth2']['TOKEN_URL'], $params))->access_token;
        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'access_token',
            'value' => $token,
        ]));
        return $this->goHome();
    }

}