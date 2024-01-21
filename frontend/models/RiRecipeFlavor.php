<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_recipe_flavor}}".
 *
 * @property int $id
 * @property int $recipe_id
 * @property int $flavor_id
 *
 * @property RiFlavor $flavor
 * @property RiRecipe $recipe
 */
class RiRecipeFlavor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_recipe_flavor}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recipe_id', 'flavor_id'], 'required'],
            [['recipe_id', 'flavor_id'], 'integer'],
            [['flavor_id'], 'exist', 'skipOnError' => true, 'targetClass' => RiFlavor::className(), 'targetAttribute' => ['flavor_id' => 'id']],
            [['recipe_id'], 'exist', 'skipOnError' => true, 'targetClass' => RiRecipe::className(), 'targetAttribute' => ['recipe_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipe_id' => 'Recipe ID',
            'flavor_id' => 'Flavor ID',
        ];
    }

    /**
     * Gets query for [[Flavor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlavor()
    {
        return $this->hasOne(RiFlavor::className(), ['id' => 'flavor_id']);
    }

    /**
     * Gets query for [[Recipe]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecipe()
    {
        return $this->hasOne(RiRecipe::className(), ['id' => 'recipe_id']);
    }
}
