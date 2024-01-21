<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_home_inventory}}".
 *
 * @property int $id
 * @property int $ingredient_id
 *
 * @property RiIngredient $ingredient
 */
class RiHomeInventory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_home_inventory}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ingredient_id'], 'required'],
            [['ingredient_id'], 'integer'],
            [['ingredient_id'], 'exist', 'skipOnError' => true, 'targetClass' => RiIngredient::className(), 'targetAttribute' => ['ingredient_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ingredient_id' => 'Ingredient ID',
        ];
    }

    /**
     * Gets query for [[Ingredient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngredient()
    {
        return $this->hasOne(RiIngredient::className(), ['id' => 'ingredient_id']);
    }
}
