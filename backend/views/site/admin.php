<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerCss("
    body { background-color: #f8f9fa; }
    .dashboard-container { display: flex; min-height: 100vh; }
    .sidebar { width: 250px; background-color: #343a40; color: white; padding: 20px; position: fixed; height: 100vh; }
    .sidebar h2 { font-size: 1.5rem; margin-bottom: 30px; }
    .sidebar a { color: #ccc; display: block; margin: 15px 0; text-decoration: none; font-size: 1.1rem; }
    .sidebar a:hover { color: #fff; text-decoration: underline; }
    .main-content { flex-grow: 1; margin-left: 250px; padding: 40px; background-color: #fff; }
    .welcome h1 { font-size: 2rem; margin-bottom: 10px; }
    .card { border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 30px; }
    .card-header { background-color: #6f42c1; color: white; font-size: 1.25rem; padding: 15px; border-top-left-radius: 10px; border-top-right-radius: 10px; }
    .card-body { padding: 20px; }
    .btn-primary { background-color: #6f42c1; border-color: #6f42c1; }
    .btn-save { background-color: #28a745; border-color: #28a745; margin-right: 10px; }
    .btn-cancel { background-color: #6c757d; border-color: #6c757d; }
    .success-message { color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
");

$section = Yii::$app->request->get('section', 'home');
$isEditing = Yii::$app->request->get('edit', false);

if (Yii::$app->user->isGuest) {
    return $this->redirect(['site/login']);
}
?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>My Dashboard</h2>
        
        
        <a href="<?= Url::to(['candidate/index', 'section' => 'manage-elections']) ?>">👤 Manage Elections</a>
        <a class="nav-link" href="<?=Url::to(['/site/registered-voters']) ?>">🧾 Registered Voters</a>
         <a class="nav-link" href="<?=Url::to(['/site/voting-records']) ?>">🗳️ Voting Records</a>
        <a href="<?= Url::to(['site/results', 'section' => 'results']) ?>">📊 Election Results</a>
        <a href="<?= Url::to(['site/logout']) ?>" data-method="post">🚪 Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome">
            <h1>Welcome, <?= Html::encode(Yii::$app->user->identity->username) ?> 👋</h1>
            <p>This is your workspace. Use the menu to manage your profile and settings.</p>
        </div>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="success-message">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php if ($section === 'profile'): ?>
            <!-- Profile Section -->
            <div class="card">
                <div class="card-header">Your Profile</div>
                <div class="card-body">
                    <?php if (!$isEditing): ?>
                        <p><strong>Name:</strong> <?= Html::encode(Yii::$app->user->identity->username) ?></p>
                        <p><strong>Email:</strong> <?= Html::encode(Yii::$app->user->identity->email ?? 'Not Set') ?></p>
                        <p><strong>Bio:</strong> <?= Html::encode($model->bio ?? 'No bio provided') ?></p>
                        <p><strong>Avatar:</strong> <?= Html::encode($model->avatar ?? 'Not selected') ?></p>
                        <?php if ($model->profile_picture): ?>
                            <p><strong>Picture:</strong><br>
                                <?= Html::img(Yii::getAlias('@web/uploads/' . $model->profile_picture), ['style' => 'max-width:150px;border-radius:10px;']) ?>
                            </p>
                        <?php endif; ?>
                        <a href="<?= Url::to(['site/dashboard', 'section' => 'profile', 'edit' => true]) ?>" class="btn btn-primary">Edit Profile</a>
                    <?php else: ?>
                        <?php $form = ActiveForm::begin([
                            'options' => ['class' => 'profile-edit-form', 'enctype' => 'multipart/form-data']
                        ]); ?>

                        <div class="form-group">
                            <label>Name</label>
                            <?= $form->field($model, 'username')->textInput(['class' => 'form-control'])->label(false) ?>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <?= $form->field($model, 'email')->textInput(['class' => 'form-control'])->label(false) ?>
                        </div>

                        <div class="form-group">
                            <label>Upload Profile Picture 📸</label>
                            <?= $form->field($model, 'profile_picture_file')->fileInput(['class' => 'form-control'])->label(false) ?>
                        </div>

                        <div class="form-group">
                            <label>Bio / About Me</label>
                            <?= $form->field($model, 'bio')->textarea(['rows' => 3, 'class' => 'form-control'])->label(false) ?>
                        </div>

                        <?= Html::submitButton('Save Preferences', ['class' => 'btn btn-save']) ?>
                        <a href="<?= Url::to(['site/dashboard', 'section' => 'profile']) ?>" class="btn btn-cancel">Cancel</a>

                        <?php ActiveForm::end(); ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($section === 'results'): ?>
            <!-- Results -->
            <div class="card">
                <div class="card-header" style="background-color: #28a745; color: white;">Results</div>
                <div class="card-body">
                    <p><strong>Election Results:</strong> Voting has been processed</p>
                    <p><strong>Winning Candidate:</strong> TBD</p>
                    <p><strong>Vote Count:</strong> XX</p>
                    <a href="<?= Url::to(['site/results']) ?>" class="btn btn-cancel">View Full Results</a>
                </div>
            </div>

        <?php elseif ($section === 'election-schedule'): ?>
            <!-- Schedule -->
            <div class="card">
                <div class="card-header">Election Schedule</div>
                <div class="card-body">
                    <p>The upcoming elections will be held on:</p>
                    <ul>
                        <li><strong>Date:</strong> 15th June 2025</li>
                        <li><strong>Time:</strong> 10:00 AM</li>
                        <li><strong>Location:</strong> Online Voting Portal</li>
                    </ul>
                </div>
            </div>

        <?php elseif ($section === 'setting'): ?>
            <!-- Settings -->
            <div class="card">
                <div class="card-header">System Settings</div>
                <div class="card-body">
                    <p>Configure the voting system, security preferences, and more.</p>
                    <a href="#" class="btn btn-primary">Manage Settings</a>
                </div>
            </div>

        <?php elseif ($section === 'voters'): ?>
            <!-- Voters -->
            <div class="card">
                <div class="card-header">Registered Voters</div>
                <div class="card-body">
                    <p>View, edit, or verify registered voters.</p>
                    <a href="<?= Url::to(['site/voters']) ?>" class="btn btn-primary">View Voters</a>
                </div>
            </div>

        <?php elseif ($section === 'manage-elections'): ?>
            <!-- Manage Elections -->
            <div class="card">
                <div class="card-header">Manage Elections</div>
                <div class="card-body">
                    <p>Control election phases, approve candidates, and open voting.</p>
                    <a href="<?= Url::to(['election/create']) ?>" class="btn btn-primary">Manage Now</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
