<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%ri_difficulty_level}}".
 *
 * @property int $id
 * @property string $title
 */
class RiDifficultyLevel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ri_difficulty_level}}';
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
}
