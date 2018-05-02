<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $name
 *
 * @property Cpdbcalc[] $cpdbcalcs
 * @property Cpdbkb[] $cpdbkbs
 * @property Cpdborder[] $cpdborders
 * @property Testresult[] $testresults
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 15],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpdbcalcs()
    {
        return $this->hasMany(Cpdbcalc::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpdbkbs()
    {
        return $this->hasMany(Cpdbkb::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpdborders()
    {
        return $this->hasMany(Cpdborder::className(), ['id' => 'cpdborder_id'])->viaTable('cpdbkb', ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestresults()
    {
        return $this->hasMany(Testresult::className(), ['item_id' => 'id']);
    }
}
