<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "calibrator".
 *
 * @property int $id
 * @property string $name
 *
 * @property Cpdbcalc[] $cpdbcalcs
 * @property Testresult[] $testresults
 */
class Calibrator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calibrator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
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
        return $this->hasMany(Cpdbcalc::className(), ['calibrator_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestresults()
    {
        return $this->hasMany(Testresult::className(), ['calibrator_id' => 'id']);
    }
}
