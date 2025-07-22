<?php
use yii\helpers\Html;

$this->title = 'Election Results';

$photos = ['profile_1.png', 'profile_2.png'];
?>

<div class="site-results">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="lead">Live voting results for each candidate.</p>

    <table class="modern-table">
        <thead>
            <tr>
                <th>Candidate Photo</th>
                <th>Candidate Name</th>
                <th>Votes</th>
            </tr>
        </thead>
        <tbody id="results-body">
            <?php foreach ($candidates as $index => $candidate): ?>
                <?php 
                    // Use profile_1.png for first candidate, profile_2.png for second
                    $photoFile = isset($photos[$index]) ? $photos[$index] : 'default.png'; 
                    $photoUrl = \yii\helpers\Url::to("@web/uploads/{$photoFile}");
                ?>
                <tr data-id="<?= $candidate->id ?>">
                    <td>
                        <img src="<?= $photoUrl ?>" 
                             onerror="this.onerror=null;this.src='<?= \yii\helpers\Url::to('@web/images/default.png') ?>'" 
                             alt="<?= Html::encode($candidate->name) ?>" />
                    </td>
                    <td><?= Html::encode($candidate->name) ?></td>
                    <td class="vote-count"><strong><?= Html::encode($candidate->votes) ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
