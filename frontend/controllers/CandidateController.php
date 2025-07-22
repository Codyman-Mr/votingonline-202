<?php

namespace backend\controllers;

use Yii;
use common\models\Candidate;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile; // <-- Add this line to import UploadedFile

class CandidateController extends Controller
{
    public function actionIndex()
    {
        $candidates = Candidate::find()->all();
        return $this->render('index', ['candidates' => $candidates]);
    }

    public function actionCreate()
    {
        $model = new Candidate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Candidate created successfully!');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = Candidate::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Candidate not found.');
        }

        if ($model->load(Yii::$app->request->post())) {
            $photoFile = UploadedFile::getInstance($model, 'photo'); 

            if ($photoFile) {
                $uploadDir = Yii::getAlias('@frontend/web/uploads/');
                
                // Ensure the directory exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
                }

                // Generate a simple unique filename using uniqid() without the path
                $photoName = uniqid() . '.' . $photoFile->extension; // Create a unique file name like "1.png"
                $photoPath = $uploadDir . $photoName; // This will be the full path on the server

                // Save the photo
                $photoFile->saveAs($photoPath);

                // Save only the relative file name (without 'uploads/')
                $model->photo = $photoName; // Save only the file name, not the full path
            }

            // Save model data
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
        $model = Candidate::findOne($id);
        if ($model) {
            $model->delete();
        }

        return $this->redirect(['index']);
    }
}
