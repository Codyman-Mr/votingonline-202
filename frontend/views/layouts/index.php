<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $candidates backend\models\Candidate[] */

$this->title = 'Candidates';
?>
<h1>Candidates</h1>

<p><?= Html::a('Add New Candidate', ['create'], ['class' => 'btn btn-success']) ?></p>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Photo</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($candidates as $candidate): ?>
        <tr>
            <td><?= Html::encode($candidate->id) ?></td>
            <td><?= Html::encode($candidate->name) ?></td>
            <td>
                <?php if (!empty($candidate->photo)): ?>
                    <img src="<?= Yii::getAlias('@web') . '/uploads/' . basename($candidate->photo) ?>" width="80" alt="Candidate Photo">

                <?php else: ?>
                    <em>No Photo</em>
                <?php endif; ?>
            </td>
            <td>
                <?= Html::a('Edit', ['update', 'id' => $candidate->id]) ?> |
                <?= Html::a('Delete', ['delete', 'id' => $candidate->id], [
                    'data-confirm' => 'Are you sure?',
                    'data-method' => 'post',
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
