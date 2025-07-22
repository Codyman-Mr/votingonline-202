<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Candidate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class CandidateController extends Controller
{
    public function actionIndex()
    {
        $candidates = Candidate::find()->all();
        return $this->render('index', ['candidates' => $candidates]);
    }

    // ... zingine action zako
}
