<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Vote for Your Candidate';

$candidates = [
    (object)[
        'id' => 1,
        'name' => 'Willbright Uyole',
        'photo' => 'profile2.png',
        'votes' => 0,
    ],
    (object)[
        'id' => 2,
        'name' => 'Cheastina Sanga',
        'photo' => 'chea.jpg',
        'votes' => 0,
    ],
];

// Process vote if POST
if (Yii::$app->request->isPost) {
    $candidateId = Yii::$app->request->post('candidate_id');
    foreach ($candidates as $candidate) {
        if ($candidate->id == $candidateId) {
            $candidate->votes++;
            echo "<div class='alert alert-success text-center'>Thank you for voting for <b>" . Html::encode($candidate->name) . "</b>.</div>";
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
                    <img src="<?= Url::to('@web/uploads/' . $candidate->photo) ?>"
                         onerror="this.src='<?= Url::to('@web/images/default.png') ?>'"
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
