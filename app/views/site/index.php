<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CpdborderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cpdborders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cpdborder-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'datetime',
            // 'id',
            'panel.showname',
            'panellot',
            // 'machineid',

            // ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {download}',
                'buttons' => [
                        'download'=>function ($url, $model, $key) {
                            return  Html::a('download', $url);
                            },
                        ]
            ],
        ],
    ]); ?>
</div>
