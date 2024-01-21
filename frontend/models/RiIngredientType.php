<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_ingredient_type}}".
 *
 * @property int $id
 * @property string $title
 *
 * @property RiIngredient[] $riIngredients
 */
class RiIngredientType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_ingredient_type}}';
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
     * Gets query for [[RiIngredients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiIngredients()
    {
        return $this->hasMany(RiIngredient::className(), ['ingredient_type_id' => 'id']);
    }
}
