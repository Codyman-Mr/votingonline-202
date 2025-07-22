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
    .profile-edit-form { margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 5px; border: 1px solid #eee; }
    .form-group { margin-bottom: 15px; }
    .form-control { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
    .btn-save { background-color: #28a745; border-color: #28a745; margin-right: 10px; }
    .btn-cancel { background-color: #6c757d; border-color: #6c757d; }
    .success-message { color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
");

$section = Yii::$app->request->get('section', 'home');
$isEditing = Yii::$app->request->get('edit', false);

// Check if the user is logged in
if (Yii::$app->user->isGuest) {
    // Redirect to login page if not logged in
    return $this->redirect(['site/login']);
}
?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>My Dashboard</h2>
        <a href="<?= Url::to(['site/dashboard', 'section' => 'profile']) ?>">👤 Profile</a>
        <a href="<?= Url::to(['voting/register','section' =>'Register']) ?>">📝Register Now</a>
        <a href="<?= Url::to(['site/details']) ?>" data-method="post">🗳️Cast Vote</a>
<a href="<?= \yii\helpers\Url::to(['layout/index', 'section' => 'manage-elections']) ?>">👤 Manage Elections</a>
        <a href="<?= Url::to(['site/dashboard', 'section' => 'results']) ?>">📊 Results</a>
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

        <?php elseif ($section === 'account'): ?>
            <div class="card">
                <div class="card-header">Account Settings</div>
                <div class="card-body">
                    <p>Change your password, enable 2FA, or manage sessions.</p>
                    <a href="#" class="btn btn-primary">Manage Account</a>
                </div>
            </div>

        <?php elseif ($section === 'messages'): ?>
            <div class="card">
                <div class="card-header">Messages</div>
                <div class="card-body">
                    <p>You have no new messages.</p>
                    <a href="#" class="btn btn-primary">View Inbox</a>
                </div>
            </div>

        <?php elseif ($section === 'results'): ?>
            <div class="card">
                <div class="card-header" style="background-color: #28a745; color: white;">Results</div>
                <div class="card-body">
                    <p><strong>Election Results:</strong> Voting has been precessed</p>
                    <p><strong>Winning Candidate:</strong> welcome</p>
                    <p><strong>Vote Count:</strong> Votes</p>
                     <a href="<?= Url::to(['site/results', 'section' => 'profile']) ?>" class="btn btn-cancel">View Results</a>
                </div>
            </div>

        <?php elseif ($section === 'cast-vote'): ?>
            <div class="card">
                <div class="card-header" style="background-color: #007bff; color: white;">Your Vote Matters</div>
                <div class="card-body">
                    <p><strong>Make Your Voice Heard:</strong> Voting is your chance to have a say in the future!</p>
                    <p><strong>Election Time:</strong> Today at 5:00 PM</p>
                    <p><strong>Location:</strong> Online - Accessible from Anywhere</p>
                    <a href="#" class="btn btn-primary">Cast Vote</a>
                </div>
            </div>
            
            <!-- Display the profile picture if it exists -->
            <div class="profile-picture">
                <?php if ($model->profile_picture): ?>
                    <img src="<?= Yii::getAlias('@frontend/web/uploads/') . $model->profile_picture ?>" alt="Profile Picture" class="img-thumbnail">
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
