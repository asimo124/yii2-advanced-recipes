<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_ingredient_price_history}}".
 *
 * @property int $id
 * @property int $ingredient_id
 * @property float $price
 * @property string $date_purchased
 * @property int|null $grocery_store_id
 *
 * @property RiGroceryStore $groceryStore
 * @property RiIngredient $ingredient
 */
class RiIngredientPriceHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_ingredient_price_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ingredient_id', 'price', 'date_purchased'], 'required'],
            [['id', 'ingredient_id', 'grocery_store_id'], 'integer'],
            [['price'], 'number'],
            [['date_purchased'], 'safe'],
            [['id'], 'unique'],
            [['grocery_store_id'], 'exist', 'skipOnError' => true, 'targetClass' => RiGroceryStore::className(), 'targetAttribute' => ['grocery_store_id' => 'id']],
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
            'price' => 'Price',
            'date_purchased' => 'Date Purchased',
            'grocery_store_id' => 'Grocery Store ID',
        ];
    }

    /**
     * Gets query for [[GroceryStore]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroceryStore()
    {
        return $this->hasOne(RiGroceryStore::className(), ['id' => 'grocery_store_id']);
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
