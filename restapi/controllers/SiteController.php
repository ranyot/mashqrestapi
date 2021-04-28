<?php


namespace restapi\controllers;


use restapi\models\LoginForm;
use yii\bootstrap\Modal;
use yii\rest\Controller;

class SiteController extends Controller
{
    public function actionLogin()
    {
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post(),'') && ($token=$model->login())){
            return $token;
        }else{
            return $model   ;
        }
    }
    public array $controllerMap = [
                                    'account' => 'app\controllers\UserController',
                                    'article' => [
                                    'class' => 'app\controllers\PostController',
                                    'pageTitle' => 'something new',
                                    ],
                                    ];

}