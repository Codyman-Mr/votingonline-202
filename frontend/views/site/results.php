<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Candidates[] $candidates */

$this->title = 'Election Results';
$this->registerCss("
    .site-results {
        background: linear-gradient(to right, #e0eafc, #cfdef3);
        padding: 50px;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .site-results h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: 48px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    .site-results p.lead {
        font-family: 'Arial', sans-serif;
        font-size: 20px;
        color: #34495e;
        margin-bottom: 40px;
        font-weight: 300;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .modern-table thead {
        background-color: #2980b9;
        color: white;
        text-transform: uppercase;
    }

    .modern-table th, .modern-table td {
        padding: 20px;
        text-align: center;
        font-size: 18px;
        font-family: 'Roboto', sans-serif;
    }

    .modern-table tbody tr {
        transition: background-color 0.3s ease;
    }

    .modern-table tbody tr:nth-child(even) {
        background-color: #f7f9fc;
    }

    .modern-table tbody tr:hover {
        background-color: #d0e9ff;
    }

    .modern-table img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #3498db;
    }

    .modern-table th {
        font-weight: 600;
    }

    .modern-table td {
        font-weight: 400;
    }

    .site-results .modern-table {
        margin-top: 40px;
    }

    /* Add animation for table rows */
    .modern-table tbody tr {
        opacity: 0;
        animation: fadeIn 0.5s forwards;
    }

    .modern-table tbody tr:nth-child(even) {
        animation-delay: 0.3s;
    }

    .modern-table tbody tr:nth-child(odd) {
        animation-delay: 0.6s;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }
");
?>

<?php
$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', ['position' => \yii\web\View::POS_HEAD]);
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
                    // Hardcode picha za wagombea wawili tu
                    $photos = ['profile_1.png', 'profile_2.png'];
                    $photoFile = isset($photos[$index]) ? $photos[$index] : 'default.png';
                ?>
                <tr data-id="<?= $candidate->id ?>">
                    <td>
                        <img src="<?= \yii\helpers\Url::to("@web/uploads/{$photoFile}") ?>" 
                             onerror="this.src='<?= \yii\helpers\Url::to('@web/images/default.png') ?>'" 
                             alt="<?= Html::encode($candidate->name) ?>">
                    </td>
                    <td><?= Html::encode($candidate->name) ?></td>
                    <td class="vote-count"><strong><?= Html::encode($candidate->votes) ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$updateUrl = \yii\helpers\Url::to(['/site/result']); // Hakikisha URL ni sahihi
$script = <<<JS
function updateResults() {
    $.getJSON('$updateUrl', function(data) {
        if (data.success && data.results) {
            data.results.forEach(function(item) {
                var row = $('tr[data-id="' + item.id + '"]');
                if (row.length > 0) {
                    row.find('.vote-count strong').text(item.votes);
                }
            });
        }
    }).fail(function() {
        console.log("Error fetching live results.");
    });
}

setInterval(updateResults, 5000); // update kila sekunde 5
JS;

$this->registerJs($script);
?>
