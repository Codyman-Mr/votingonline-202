<?php

namespace frontend\controllers;

use frontend\models\Candidates;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class CandidateController extends Controller
{
    public function actionIndex()
    {
        $candidates = Candidates::find()->all();
        return $this->render('index', ['candidates' => $candidates]);
    }

    public function actionCreate()
    {
        $model = new Candidates();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Candidate created successfully!');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = Candidates::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Candidate not found.');
        }

        if ($model->load(Yii::$app->request->post())) {
            $photoFile = UploadedFile::getInstance($model, 'photo');

            if ($photoFile) {
                $uploadDir = Yii::getAlias('@frontend/web/uploads/');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $photoName = uniqid() . '.' . $photoFile->extension;
                $photoPath = $uploadDir . $photoName;

                $photoFile->saveAs($photoPath);

                $model->photo = $photoName;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Candidate updated successfully!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update candidate.');
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = Candidates::findOne($id);
        if ($model) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }
}
