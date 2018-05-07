<?php

namespace app\controllers;

use Yii;
use app\models\Cpdborder;
use app\models\Item;
use app\models\CpdborderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
// use phpoffice\PHPExcel;
use \PHPExcel;

/**
 * SiteController implements the CRUD actions for Cpdborder model.
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    // public function behaviors()
    // {
    //     return [
    //         'verbs' => [
    //             'class' => VerbFilter::className(),
    //             'actions' => [
    //                 'delete' => ['POST'],
    //             ],
    //         ],
    //     ];
    // }

    public function actionDownload($id)
    {
        $cpdborder = Cpdborder::findOne($id);
        $map = [];
        $itemname = Item::find()
            ->select("name,id")
            ->indexBy('id')
            ->asArray()
            ->all();
        
        $itemidarr = [];
        $itempos = [];
        foreach ($cpdborder->testresults as $testresult) {
            $itemid = $testresult->item->id;
            if (!in_array($itemid, $itemidarr)) {
                $pos = count($itemidarr);
                $itemidarr[$pos] = $itemid;
                $itempos[$itemid] = $pos;
            }
            if ($testresult->calibrator_id == 1) { //CAL2   
                $map[$itempos[$itemid]][] = $testresult->result; 
            }
        }
        // $kbmap = [];
        // $kbmap0 = [];
        $k0map =[];
        $k1map =[];
        $b0map =[];
        $b1map =[];
        foreach ($cpdborder->cpdbkbs as $cpdbkb) {
            $itemid = $cpdbkb->item_id;
            // $kbmap[$itempos[$itemid]] = "y = ". $cpdbkb->kvalue1 ." x + " . $cpdbkb->kvalue1; 
            // $kbmap0[$itempos[$itemid]] = "y = ". $cpdbkb->kvalue1 ." x + " . $cpdbkb->kvalue1; 
            $k0map[$itempos[$itemid]] = $cpdbkb->kvalue0;
            $k1map[$itempos[$itemid]] = $cpdbkb->kvalue1;
            $b0map[$itempos[$itemid]] = $cpdbkb->bvalue0;
            $b1map[$itempos[$itemid]] = $cpdbkb->bvalue1;
        }
        // var_dump($kbmap);
        // exit();

        $row = 0;
        $col = count($map);
        foreach ($map as $value) {
            $row = max($row, count($value));
        }
        echo "panel: " . $cpdborder->panel->showname . "<br>";
        echo "lot: " . $cpdborder->panellot . "<br>";
        echo "machine: " . $cpdborder->machineid . "<br>";

        echo "<table border=1>";

        echo "<tr>";
        for ($j=0; $j < $col ; $j++) { 
            echo "<td>". $itemname[$itemidarr[$j]]['name'] ."</td>";
        }
        echo "</tr>";

        for ($i=0; $i < $row ; $i++) { 
            echo "<tr>";
            for ($j=0; $j < $col ; $j++) { 
                if (isset($map[$j][$i]) && is_numeric($map[$j][$i])) {
                    $result = round($map[$j][$i],2);
                } else {
                    $result = "/";
                }
               echo "<td>". $result ."</td>";
            }
            echo "</tr>";
        }

        echo "<tr>";
        for ($j=0; $j < $col ; $j++) { 
            echo "<td>". "0: y = ". $k0map[$j] ." x + " . $b0map[$j] ."</td>";
        }
        echo "</tr>";

        echo "<tr>";
        for ($j=0; $j < $col ; $j++) { 
            echo "<td>". "1: y = ". $k1map[$j] ." x + " . $b1map[$j] ."</td>";
        }
        echo "</tr>";

        for ($i=0; $i < $row ; $i++) { 
            echo "<tr>";
            for ($j=0; $j < $col ; $j++) { 
                if (isset($map[$j][$i]) && is_numeric($map[$j][$i])) {
                    if (empty($k0map[$j])) {
                        $result = 0;
                    } else {
                        $result = ($map[$j][$i] - $b0map[$j]) * $k1map[$j] / $k0map[$j] + $b1map[$j];
                    }
                    // $result = $map[$j][$i];
                    $result = round($result, 2);
                } else {
                    $result = "/";
                }
               echo "<td>". $result."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
/*    public function actionDownload($id)
    {
        // $searchModel = new CpdborderSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // return $this->render('index', [
        //     'searchModel' => $searchModel,
        //     'dataProvider' => $dataProvider,
        // ]);
        // echo "string".$id;
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                             ->setLastModifiedBy("Maarten Balliauw")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");

        $cpdborder = Cpdborder::findOne($id);
        // var_dump($cpdborder->panel->showname);
        $i = 1;
        foreach ($cpdborder->testresults as $testresult) {
            // var_dump($testresult->result);
            // var_dump($testresult->item->name);
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'. $i, $testresult->item->name)
                        ->setCellValue('B'. $i, $testresult->result);

            $i++;
        }
// Add some data
// Miscellaneous glyphs, UTF-8
// $objPHPExcel->setActiveSheetIndex(0)
//             ->setCellValue('A4', 'Miscellaneous glyphs')
//             ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$path = Yii::getAlias('@webroot');
$path .= "/output/";
$path .= '1.xlsx';
$objWriter->save($path);
// $objWriter->save('1.xlsxs');
exit;


    }
*/
    /**
     * Lists all Cpdborder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CpdborderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cpdborder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cpdborder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cpdborder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cpdborder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cpdborder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cpdborder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cpdborder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cpdborder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
