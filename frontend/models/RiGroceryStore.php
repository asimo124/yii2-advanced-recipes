<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_grocery_store}}".
 *
 * @property int $id
 * @property string $title
 *
 * @property RiIngredientPriceHistory[] $riIngredientPriceHistories
 */
class RiGroceryStore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_grocery_store}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
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
        ];
    }

    /**
     * Gets query for [[RiIngredientPriceHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiIngredientPriceHistories()
    {
        return $this->hasMany(RiIngredientPriceHistory::className(), ['grocery_store_id' => 'id']);
    }
}
