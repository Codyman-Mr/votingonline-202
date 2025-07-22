<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $candidates */

$this->title = 'Vote for Your Candidate';
$this->registerCssFile('@web/css/vote.css');

// Hardcode fixed candidates since we want to use only profile_1.png and profile_2.png
$candidates = [
    (object)[
        'id' => 1,
        'name' => 'Willbright Uyole',
        'photo' => 'profile_1.png',
        'votes' => 0, // Or load from DB if you want
    ],
    (object)[
        'id' => 2,
        'name' => 'Winfred Pandula',
        'photo' => 'profile_2.png',
        'votes' => 0, // Or load from DB if you want
    ],
];

// Handle voting post
if (Yii::$app->request->isPost) {
    $candidateId = Yii::$app->request->post('candidate_id');

    // Simulate vote save here - increment votes for demo (replace with DB save)
    foreach ($candidates as $candidate) {
        if ($candidate->id == $candidateId) {
            $candidate->votes++;
            // TODO: Save vote count to DB here
            echo "<div class='alert alert-success text-center'>Congrats! Your vote for <b>" . Html::encode($candidate->name) . "</b> has been recorded.</div>";
            break;
        }
    }
}
?>

<div class="container mt-5">
  <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>
  <p class="text-center fs-5 mb-4">
    Please choose your preferred candidate wisely. You can only vote once.
  </p>

  <div class="row justify-content-center mt-4">
    <?php foreach ($candidates as $candidate): ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow text-center">
          <img src="<?= Url::to("/votingonline/votingonline-202/frontend/web/uploads/{$candidate->photo}") ?>"
               onerror="this.src='<?= Url::to('/votingonline/votingonline-202/frontend/web/images/default.png') ?>'"
               class="card-img-top"
               style="height:300px; object-fit:cover"
               alt="<?= Html::encode($candidate->name) ?>">

          <div class="card-body">
            <h5><?= Html::encode($candidate->name) ?></h5>

            <?= Html::beginForm('', 'post') ?>
              <?= Html::hiddenInput('candidate_id', $candidate->id) ?>
              <?= Html::submitButton('Vote', ['class' => 'btn btn-primary']) ?>
            <?= Html::endForm() ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
