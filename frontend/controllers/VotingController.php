<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\RegisteredVoters;
use frontend\models\VotingRecords;
use frontend\models\Candidates; // ← Hii hapa ndo ilikosekana
use yii\web\NotFoundHttpException;

class VotingController extends Controller
{
    // Weka muda wa mwisho wa kujiandikisha hapa
    public $registrationDeadline = '2027-12-31 23:59:59';  // Deadline mpaka mwisho 2027 kama ulivyotaka

    // Kujiandikisha
    public function actionRegister()
    {
        $model = new RegisteredVoters();

        // Badilisha hapa: tumia property badala ya DB query
        $deadline = $this->registrationDeadline;

        // Check kama deadline iko sawa (haina null)
        if (!$deadline) {
            Yii::$app->session->setFlash('error', 'Registration deadline is not set.');
            return $this->render('register', ['model' => $model]);
        }

        // Taarifa deadline na timezone Tanzania
        $deadlineDateTime = new \DateTime($deadline, new \DateTimeZone('Africa/Dar_es_Salaam'));
        $currentDateTime = new \DateTime('now', new \DateTimeZone('Africa/Dar_es_Salaam'));

        // Debugging output (optional)
        Yii::info("Current Time: " . $currentDateTime->format('Y-m-d H:i:s'), __METHOD__);
        Yii::info("Registration Deadline: " . $deadlineDateTime->format('Y-m-d H:i:s'), __METHOD__);

        if ($model->load(Yii::$app->request->post())) {
            if ($currentDateTime > $deadlineDateTime) {
                Yii::$app->session->setFlash('error', 'The registration deadline has passed.');
            } else {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'You have successfully registered.');
                    return $this->redirect(['site/dashboard']);
                } else {
                    Yii::$app->session->setFlash('error', 'There was an issue with the registration process.');
                }
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }


    // Kupiga kura
    public function actionVote()
    {
        $session = Yii::$app->session;

        // Hakikisha session iko hai
        if (!$session->isActive) {
            $session->open();
        }

        // 1. Angalia kama 'voter_id' ipo kwenye session
        $voterId = $session->get('voter_id', null);

        if ($voterId === null) {
            // Hakuna taarifa za mpiga kura kwenye session, rudisha kwa ukurasa wa verification
            Yii::$app->session->setFlash('error', 'You must verify your details first.');
            return $this->redirect(['site/details']);
        }

        // 2. Tafuta mpiga kura kutoka database
        $voter = RegisteredVoters::findOne($voterId);

        // 3. Kama mpiga kura hajapatikana au tayari amepiga kura
        if (!$voter || $voter->has_voted) {
            Yii::$app->session->setFlash('error', 'Either your details are not verified, or you have already voted.');
            return $this->redirect(['site/details']);
        }

        // 4. Pakua wagombea wote kutoka DB
        $candidates = Candidates::find()->all();

        // 5. Kagua kama form imewasilishwa
        if (Yii::$app->request->isPost) {
            $candidateId = Yii::$app->request->post('candidate_id');
            $candidate = Candidates::findOne($candidateId);

            if ($candidate) {
                // Ongeza kura kwa mgombea
                $candidate->votes++;
                $candidate->save();

                // Weka kwamba mpiga kura ameshapiga kura
                $voter->has_voted = 1;
                $voter->save();

                // Hifadhi rekodi ya kura na jina la mgombea
                $record = new VotingRecords([
                    'voter_id_number' => $voter->voter_id_number,
                    'full_name' => $voter->full_name,
                    'candidate_name' => $candidate->name, // ← Ongeza jina la mgombea
                    'voted_at' => date('Y-m-d H:i:s'),
                ]);
                $record->save();

                // Tuma salamu za pongezi
                Yii::$app->session->setFlash('success', 'Thank you for voting!');
                return $this->redirect(['site/leo']);
            } else {
                Yii::$app->session->setFlash('error', 'Invalid candidate selected.');
            }
        }

        // 6. Onyesha ukurasa wa kura na wagombea wote
        return $this->render('vote', [
            'candidates' => $candidates,
        ]);
    }
}
