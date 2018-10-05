<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "messenger".
 *
 * @property int $id
 * @property string $title
 * @property string $link
 */
class Messenger extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messenger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'title' => 'Название',
            'link' => 'Ссылка',
        ];
    }
}
