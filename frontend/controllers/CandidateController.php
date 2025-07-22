<?php

namespace frontend\controllers;

use frontend\models\Candidates;  // badala ya Candidate

class CandidateController extends Controller
{
    public function actionIndex()
    {
        $candidates = Candidates::find()->all();  // tumia Candidates hapa
        return $this->render('index', ['candidates' => $candidates]);
    }
    // zingine zikabidhi Candidates badala Candidate
}
