<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ltc_item_used_history}}".
 *
 * @property int $id
 * @property int $item_id
 * @property string $date_used
 */
class ItemUsedHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ltc_item_used_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'date_used'], 'required'],
            [['item_id'], 'integer'],
            [['date_used'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'date_used' => 'Date Used',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}
