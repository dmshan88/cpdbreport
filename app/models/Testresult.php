<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "testresult".
 *
 * @property int $cpdborder_id
 * @property int $calibrator_id
 * @property int $item_id
 * @property double $result
 *
 * @property Calibrator $calibrator
 * @property Item $item
 * @property Cpdborder $cpdborder
 */
class Testresult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testresult';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cpdborder_id', 'calibrator_id', 'item_id'], 'required'],
            [['cpdborder_id', 'calibrator_id', 'item_id'], 'integer'],
            [['result'], 'number'],
            [['cpdborder_id', 'calibrator_id', 'item_id'], 'unique', 'targetAttribute' => ['cpdborder_id', 'calibrator_id', 'item_id']],
            [['calibrator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Calibrator::className(), 'targetAttribute' => ['calibrator_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['cpdborder_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cpdborder::className(), 'targetAttribute' => ['cpdborder_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cpdborder_id' => 'Cpdborder ID',
            'calibrator_id' => 'Calibrator ID',
            'item_id' => 'Item ID',
            'result' => 'Result',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalibrator()
    {
        return $this->hasOne(Calibrator::className(), ['id' => 'calibrator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCpdborder()
    {
        return $this->hasOne(Cpdborder::className(), ['id' => 'cpdborder_id']);
    }
}
