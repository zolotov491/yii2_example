<?php

namespace app\form;

use Yii;
use yii\base\Model;

/**
 * Description of ChatSubscriptionForm
 *
 * @author apple
 */
class ChatSubscriptionForm extends Model
{
    public $uid;
    public $for;
    public $month;
    
    /**
     * @inheritdoc
     */
    public function rules() {
        return[
            [['uid', 'for', 'month'], 'required'],
            ['month', 'integer', 'min' => 1, 'max' => Yii::$app->params['maxMonth']],
            [['uid', 'for'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
       return [
            'month' => Yii::t('app', 'Month to pay'),
       ];
    }
    
    /**
     * Get month array
     * @return array
     */
    public function getMonthData()
    {
        $marray = [];
        for($i = 1; $i <= Yii::$app->params['maxMonth']; $i++)
        {
            $marray[$i] = $i;
        }
        return $marray;
    }
}
