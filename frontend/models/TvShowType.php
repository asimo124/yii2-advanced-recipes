<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%tv_show_type}}".
 *
 * @property int $id
 * @property string $title
 *
 * @property TvShow[] $tvShows
 */
class TvShowType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tv_show_type}}';
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
     * Gets query for [[TvShows]].
     *
     * @return \yii\db\ActiveQuery
     *
    public function getTvShows()
    {
        return $this->hasMany(TvShow::className(), ['show_type_id' => 'id']);
    }
    //*/
}
