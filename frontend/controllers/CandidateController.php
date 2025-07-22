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

        if ($model->load(Yii::$app->request->post())) {
            // Pata uploaded file
            $model->photoFile = UploadedFile::getInstance($model, 'photoFile');

            if ($model->photoFile && $model->validate()) {
                $uploadDir = Yii::getAlias('@frontend/web/uploads/');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $photoName = uniqid() . '.' . $model->photoFile->extension;
                $photoPath = $uploadDir . $photoName;

                if ($model->photoFile->saveAs($photoPath)) {
                    $model->photo = $photoName;  // store filename to DB
                    if ($model->save(false)) {   // save model without validation again
                        Yii::$app->session->setFlash('success', 'Candidate created successfully!');
                        return $this->redirect(['index']);
                    }
                }
            }
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
            $model->photoFile = UploadedFile::getInstance($model, 'photoFile');

            if ($model->photoFile && $model->validate()) {
                $uploadDir = Yii::getAlias('@frontend/web/uploads/');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $photoName = uniqid() . '.' . $model->photoFile->extension;
                $photoPath = $uploadDir . $photoName;

                if ($model->photoFile->saveAs($photoPath)) {
                    $model->photo = $photoName;
                }
            }
            
            if ($model->save(false)) {
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
