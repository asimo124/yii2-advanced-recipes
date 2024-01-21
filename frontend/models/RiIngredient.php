<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_ingredient}}".
 *
 * @property int $id
 * @property string $title
 * @property int|null $ingredient_type_id
 * @property float|null $price
 * @property float|null $cheap_price
 * @property int|null $store_section_id
 *
 * @property RiHomeInventory[] $riHomeInventories
 * @property RiIngredientType $ingredientType
 * @property RiIngredientPriceHistory[] $riIngredientPriceHistories
 * @property RiRecipeIngredient[] $riRecipeIngredients
 */
class RiIngredient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_ingredient}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['ingredient_type_id', 'store_section_id'], 'integer'],
            [['price', 'cheap_price'], 'number'],
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
            'ingredient_type_id' => 'Ingredient Type ID',
            'price' => 'Price',
            'cheap_price' => 'Cheap Price',
            'store_section_id' => 'Store Section ID',
        ];
    }

    /**
     * Gets query for [[RiHomeInventories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiHomeInventories()
    {
        return $this->hasMany(RiHomeInventory::className(), ['ingredient_id' => 'id']);
    }

    /**
     * Gets query for [[IngredientType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIngredientType()
    {
        return $this->hasOne(RiIngredientType::className(), ['id' => 'ingredient_type_id']);
    }

    /**
     * Gets query for [[RiIngredientPriceHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiIngredientPriceHistories()
    {
        return $this->hasMany(RiIngredientPriceHistory::className(), ['ingredient_id' => 'id']);
    }

    /**
     * Gets query for [[RiRecipeIngredients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiRecipeIngredients()
    {
        return $this->hasMany(RiRecipeIngredient::className(), ['ingredient_id' => 'id']);
    }
}
