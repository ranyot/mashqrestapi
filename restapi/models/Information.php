<?php


namespace restapi\models;


use yii\data\ActiveDataProvider;
use yii\web\HttpException;

class Information extends \common\models\Information
{
    public function fields()
    {
        return [
            'id',
            'name',
            'sport_id',
            'phone1',
            'phone2',
            'rate',
            'map',
            'status',
            'open',
            'close',
        ];

    }
    public static function getReference(){
        return self::find()
            ->select(['id','sport_id','name','phone1','phone2','rate','map','open','close'])
            ->where(['status'=> self::STATUS_ACTIVE,])
            ->asArray()
            ->all();
    }
    public static function getSort($massiv)
    {
        $massiv2= sort($massiv);
        return $massiv2;//endi ishlating
    }
    public static function customErrorMsg($error_code,$message = null, $code = 0, \Exception $previous = null,$extra_content=NULL)
    {
        $httpException = new HttpException($error_code,$message,$code,$previous);
        \Yii::$app->response->statusCode = $error_code;
        $custom_err = array(
            'name'=> $httpException->getName(),
            'message' => $message,
        );
        return $custom_err;
    }
}