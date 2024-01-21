<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_flavor}}".
 *
 * @property int $id
 * @property string $title
 *
 * @property RiRecipeFlavor[] $riRecipeFlavors
 */
class RiFlavor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_flavor}}';
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
     * Gets query for [[RiRecipeFlavors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiRecipeFlavors()
    {
        return $this->hasMany(RiRecipeFlavor::className(), ['flavor_id' => 'id']);
    }
}
