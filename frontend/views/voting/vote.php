<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Candidates;
use frontend\models\VotingRecords;

$this->title = 'Vote for Your Candidate';
$this->registerCssFile('@web/css/vote.css');

// ensure candidates exist
if (Candidates::find()->count() === 0) {
    // Insert default candidates if they do not exist
    // e.g., Willbright Uyole & Winfred Pandula with their respective photos
}

$candidates = Candidates::find()->all();

// fixed photos array for candidates
$fixedPhotos = ['profile_1.png', 'profile_2.png'];
?>

<div class="container mt-5">
  <h1 class="text-center mb-4">Welcome to the Voting Page</h1>
  <p class="text-center fs-5 mb-4">
    Please choose your preferred candidate wisely. You can only vote once.
  </p>

  <?php if (Yii::$app->request->isPost): ?>
    <?php
      $candidateId = Yii::$app->request->post('candidate_id');
      $candidate = Candidates::findOne($candidateId);

      if ($candidate) {
        // Simulate saving vote (this example does not validate the voter)
        $candidate->votes++;
        $candidate->save();

        // Optional: Save the voting record in VotingRecords table
        $rec = new VotingRecords([
          'voter_id_number' => 'guest', // Use guest or leave empty
          'full_name' => 'Guest', // Or collect full name from input if needed
          'voted_at' => date('Y-m-d H:i:s'),
        ]);
        $rec->save();

        echo "<div class='alert alert-success text-center'>Congrats! Your vote has been recorded.</div>";
      } else {
        echo "<div class='alert alert-danger text-center'>Invalid candidate selected.</div>";
      }
    ?>
  <?php endif; ?>

  <div class="row justify-content-center mt-4">
    <?php foreach ($candidates as $index => $candidate): ?>
      <?php
        $photo = isset($fixedPhotos[$index]) ? $fixedPhotos[$index] : 'default.png';
        $photoUrl = Url::to("@web/uploads/{$photo}");
      ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow text-center">
          <img src="<?= $photoUrl ?>"
               onerror="this.src='<?= Url::to('@web/images/default.png') ?>'"
               class="card-img-top"
               style="height:300px;object-fit:cover"
               alt="<?= Html::encode($candidate->name) ?>">

          <div class="card-body">
            <h5><?= Html::encode($candidate->name) ?></h5>

            <?= Html::beginForm(Url::to(['voting/vote']), 'post') ?>
              <?= Html::hiddenInput('candidate_id', $candidate->id) ?>
              <?= Html::submitButton('Vote', ['class'=>'btn btn-primary']) ?>
            <?= Html::endForm() ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
