<?php


namespace restapi\controllers;

use restapi\models\Information;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\Response;


class InformationController extends Controller
{

    public $modelClass = 'restapi\models\information';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    function getPagi($page,$array){
        $count = count($array);

        if($count%5==0 || $count<5){
            $lastP=is_float($count/5);
        }else{
            $lastP=is_float($count/5)+1;
        }
        $n=5;
        for($i=0;$i<2;$i++) {
            if ($page == 1) {
                $c = 0;
            } else {
                $c = $n * ($page-1);
            }
            if ($count < $c) {
                return Information::customErrorMsg(400,$page.' no pagination, last pagination '.$lastP,12,NULL,"Extra Content Here");
            }
        }
        for ($i=$c;$i<$c+$n;$i++){
            if($count==$i){
                break;
            }else {
                $answer[] = $array[$i];
            }
        }
        return $answer;
    }

    function bubble_sort($arr) {
        $size = count($arr)-1;
        for ($i=0; $i<$size; $i++) {
            for ($j=0; $j<$size-$i; $j++) {
                $k = $j+1;
                if ($arr[$k] < $arr[$j]) {
                    // Swap elements at indices: $j, $k
                    list($arr[$j], $arr[$k]) = array($arr[$k], $arr[$j]);
                }
            }
        }
        return $arr;
    }
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


        return $behaviors;
    }


    public function actionIndex()
    {
        $dataProvider=new ActiveDataProvider([
            'query'=>Information::find(),
        ]);
        return $dataProvider;

    }
    public function actionCreate()
    {

        $massiv=Information::getReference();
        $params = \Yii::$app->request->get();

        $lat=(float)$params['lat']+(float)$params['lng'];
        $type=$params['type'];
        $page=(float)$params['page'];
        foreach ($massiv as $f) {
           // return $massiv;
            $mapid=explode(',',$f['map']);
            $massiv0[$f['id']]=$mapid[0]+$mapid[1];
        }
        foreach ($massiv0 as $key=>$v){
            if($v-$lat>=0){
                $massiv1[]=$v-$lat;
            }else{
                $massiv1[]=($v-$lat)*(-1);
            }
        }
        $massiv1=$this->bubble_sort($massiv1);
            $counter=0;


                foreach ($massiv1 as $x) {

                    $counter++;
                    foreach ($massiv as $y) {
                        $mapid2 = explode(',', $y['map']);
                        $z = $mapid2[0] + $mapid2[1] - $lat;
                        if ($z >= 0) {
                            $z = $z * 1;
                        } else {
                            $z = $z * -1;
                        }
                        if ($x == $z) {
                            $javob1[] = $y;

                        }
                    }

                }

                // return $javob1;

                foreach ($javob1 as $j) {
                    $fulRate[] = $j['rate']+$j['id'];
                }

                $fulRate = $this->bubble_sort($fulRate);
                foreach ($fulRate as $r) {
                    foreach ($javob1 as $j2) {
                        if ($r === $j2['rate']+$j2['id']) {
                            $javob2[] = $j2;
                        }
                    }
                }
                if($type=='near'){
                    return $this->getPagi($page,$javob1);
                    //return $javob1;
                }else{
                    if($type=='cheap'){
                        return $this->getPagi($page,$javob2);
                       // return $javob2;
                    }else{

                        return Information::customErrorMsg(400,"wrong type",11,NULL,"Extra Content Here");

                    }
                }
    }
}

