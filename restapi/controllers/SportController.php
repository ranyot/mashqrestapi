<?php

namespace restapi\controllers;

use restapi\models\sport;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\Response;

class SportController extends ActiveController
{
    public $modelClass = 'restapi\models\sport';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
            ],
        ];
        $behaviors['formats'] = [

            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
//        $behaviors['authenticator'] = [
//            'class' => HttpBasicAuth::className(),
//        ];

        return $behaviors;
    }


    public function actions()
    {
        $actions=parent::actions();
        unset($actions['create']);
        return $actions;
    }
    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => sport::find()
        ]);
        return $dataProvider;
    }

}
