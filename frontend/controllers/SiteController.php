<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UploadedFile; // 
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\user;
use frontend\models\Candidates;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
   public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::class,
            'only' => ['logout', 'signup', 'view-results'],  // Specify only the actions you want to allow
            'rules' => [
                [
                    'actions' => ['signup'],
                    'allow' => true,
                    'roles' => ['?'],  // Allow guests (non-logged-in users) to signup
                ],
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@'],  // Allow logged-in users to logout
                ],
                [
                    'actions' => ['view-results'],  // Admins can view results
                    'allow' => true,
                    'roles' => ['@'],  // Allow logged-in users to view results
                ],
                [
                    'actions' => ['edit-vote', 'edit-results'],  // Implicitly deny by not allowing these actions
                    'allow' => false,  // Deny access to editing votes/results for logged-in users
                    'roles' => ['@'],  // Restrict access for logged-in users
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::class,
            'actions' => [
                'logout' => ['post'],  // Limit logout to POST requests only
            ],
        ],
    ];
}


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
public function actionResult()
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $electionId = Yii::$app->request->get('id');  // Pata ID ya uchaguzi kutoka kwa ombi

    if (!$electionId) {
        return [
            'success' => false,
            'message' => 'Election ID is required.',
        ];
    }

    $candidates = Candidates::find()->where(['election_id' => $electionId])->all();  // Pata wagombea

    if (empty($candidates)) {
        return [
            'success' => false,
            'message' => 'No candidates found for the given election ID.',
        ];
    }

    $results = [];
    foreach ($candidates as $candidate) {
        $results[] = [
            'id' => $candidate->id,  // Haki za mgombea
            'name' => $candidate->name,
            'votes' => $candidate->votes,
        ];
    }

    return [
        'success' => true,
        'results' => $results,  // Rudisha matokeo ya uchaguzi
    ];
}

    // SiteController.php
    public function actionSetLanguage()
{
    if (Yii::$app->request->isPost) {
        $lang = Yii::$app->request->post('lang');
        if (in_array($lang, ['en', 'sw'])) {
            Yii::$app->language = $lang;  // Set language
            Yii::$app->session->set('language', $lang);  // Store the language in session
        }
    }
    return $this->goBack();  // Redirect back to the previous page
}
// Hii ni kazi ya kuboresha matokeo ya uchaguzi bila kulazimika kufreshi ukurasa

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
{
    if (!Yii::$app->user->isGuest) {
        return $this->redirect(['site/dashboard']); // Mtu akiwa tayari amelogin, apelekwe dashboard
    }

    $model = new LoginForm();
    if ($model->load(Yii::$app->request->post()) && $model->login()) {
        return $this->redirect(['site/dashboard']); // Aki-login kwa mafanikio, apelekwe dashboard
    }

    $model->password = '';

    return $this->render('login', [
        'model' => $model,
    ]);
}


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }
public function actionGetElectionResults()
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $electionId = Yii::$app->request->get('id');

    if (!$electionId) {
        return [
            'success' => false,
            'message' => 'Election ID is required.',
        ];
    }

    $candidates = Candidates::find()
        ->where(['election_id' => $electionId])
        ->all();

    if (empty($candidates)) {
        return [
            'success' => false,
            'message' => 'No candidates found for the given election ID.',
        ];
    }

    $results = [];
    foreach ($candidates as $candidate) {
        $results[] = [
            'id' => $candidate->id,
            'name' => $candidate->name,
            'votes' => $candidate->votes,
        ];
    }

    return [
        'success' => true,
        'results' => $results,
    ];
}

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }public function actionCongrats()
{
    return $this->render('congrats');
}
 public function actionLeo()
{
    return $this->render('leo');
}

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

public function actionDashboard()
{
    // Check if user is not logged in
    if (Yii::$app->user->isGuest) {
        return $this->redirect(['site/login']);
    }

    /** @var \common\models\User $model */
    $model = Yii::$app->user->identity;

    // Sanidi attribute ya upload (hii ni virtual property)
    $model->profile_picture_file = null;

    if (Yii::$app->request->isPost) {
        $model->load(Yii::$app->request->post());

        // pata picha uliyopakia
        $image = UploadedFile::getInstance($model, 'profile_picture_file');
        if ($image) {
            // Unda jina la picha na hifadhi
            $imageName = 'profile_' . $model->id . '.' . $image->extension;
            $uploadPath = Yii::getAlias('@frontend/web/uploads/') . $imageName;

            if ($image->saveAs($uploadPath)) {
                $model->profile_picture = $imageName;
                $model->save(false); // tunatumia false ili tusihitaji validation tena
                Yii::$app->session->setFlash('success', 'Picha imepakiwa kikamilifu!');
            }
        }
    }

    return $this->render('dashboard', [
        'model' => $model,
    ]);
}


public function actionResults()
{
    $candidates = Candidates::find()->orderBy(['votes' => SORT_DESC])->all();
    return $this->render('results', ['candidates' => $candidates]);
}


public function actionDetails()
{
    $message = '';
    $fullname = '';
    $voter_id = '';

    // Check if form is submitted via POST
    if (Yii::$app->request->isPost) {
        // Get full name and voter ID from the form submission
        $fullname = Yii::$app->request->post('full_name');
        $voter_id = Yii::$app->request->post('voter_id');

        // Search for the voter in the database using the full name and voter ID
        $query = (new \yii\db\Query())
            ->from('registered_voters')
            ->where(['full_name' => $fullname]);

        if (!empty($voter_id)) {
            $query->andWhere(['voter_id_number' => $voter_id]);
        }

        $voter = $query->one();

        // If voter exists, store their ID in session and redirect to voting page
        if ($voter) {
            // Store voter details in session
            $session = Yii::$app->session;
            $session->set('voter_id', $voter['id']); // Store voter ID (primary key)
            $session->set('voter_fullname', $voter['full_name']); // Optionally store full name

            // Success message and redirect to vote page
            Yii::$app->session->setFlash('success', 'Your details have been verified!');

            // Redirect to the voting page
            return $this->redirect(['voting/vote']);
        } else {
            // If voter is not found, show error message
            $message = " 📓 🖊️.";
        }
    }

    return $this->render('details', [
        'message' => $message,
        'fullname' => $fullname,
        'voter_id' => $voter_id,
    ]);
}



    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
