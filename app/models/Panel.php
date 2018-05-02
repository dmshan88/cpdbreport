<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "panel".
 *
 * @property int $id
 * @property string $name
 * @property string $showname
 * @property string $type
 *
 * @property Cpdborder[] $cpdborders
 */
class Panel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'panel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'showname', 'type'], 'required'],
            [['id'], 'integer'],
            [['type'], 'string'],
            [['name', 'showname'], 'string', 'max' => 45],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'showname' => 'Showname',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpdborders()
    {
        return $this->hasMany(Cpdborder::className(), ['panel_id' => 'id']);
    }
}
