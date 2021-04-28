<?php

namespace restapi\models;

use yii\behaviors\TimestampBehavior;

class sport extends \common\models\sport
{
    public function rules()
    {
        return [
            ['search','safe'],
            ['name','string','max'=>250],

        ];
    }
    public  function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::class,
                'value'=>function(){
                    return (new \DateTime())->format('Y-m-d H:i');
                }

            ]
        ];
    }
}