<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_protein}}".
 *
 * @property int $id
 * @property string $title
 * @property float|null $cheap_price
 * @property float|null $price
 */
class RiProtein extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_protein}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['cheap_price', 'price'], 'number'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'cheap_price' => 'Cheap Price',
            'price' => 'Price',
        ];
    }
}
