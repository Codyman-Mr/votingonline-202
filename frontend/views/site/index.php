<?php

/** @var yii\web\View $this */
use yii\helpers\Url;

$this->title = 'Online Voting System';

// CSS mpya kwa white background & layout nzuri
$this->registerCss(<<<CSS
    body {
        background-color: #ffffff;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .hero {
        background: linear-gradient(90deg, #f9f9f9 0%, #e6f0ff 100%);
        padding: 80px 20px;
        text-align: center;
    }

    .hero h1 {
        font-size: 3.5rem;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .hero p {
        font-size: 1.3rem;
        margin-bottom: 30px;
    }

    .btn-custom {
        background-color: #007bff;
        color: #fff;
        padding: 14px 30px;
        border: none;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: bold;
        transition: background 0.3s;
        text-transform: uppercase;
    }

    .btn-custom:hover {
        background-color: #0056b3;
    }

    .section {
        padding: 60px 20px;
        text-align: center;
    }

    .section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #222;
    }

    .section p {
        font-size: 1.1rem;
        max-width: 800px;
        margin: 0 auto 30px auto;
        color: #555;
    }

    .feature-box {
        padding: 30px;
        background: #f7f9fc;
        margin: 20px auto;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        max-width: 900px;
    }

    .footer {
        background-color: #f1f1f1;
        padding: 30px;
        text-align: center;
        font-size: 0.9rem;
        color: #666;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2.2rem;
        }

        .hero p {
            font-size: 1rem;
        }
    }
CSS);
?>

<div class="hero">
    <h1>Welcome to the Online Voting System</h1>
    <p>Simple. Secure. Smart voting from anywhere.</p>
     <a href="<?= Url::to(['site/dashboard']) ?>" class="btn btn-custom">Click Here to visit your Dashboard</a>
</div>

<div class="section"git remote add origin git@github.com:Codyman-Mr/voting-application.git
>
    <h2>Why Choose Us?</h2>
    <p>Our system provides a modern, transparent, and accessible voting process that empowers everyone to participate in decision-making from anywhere in the world.</p>

    <div class="feature-box">
        <h3>✅ Secure & Private</h3>
        <p>All votes are encrypted and stored safely in our backend. No one can tamper with your vote.</p>
    </div>

    <div class="feature-box">
        <h3>🌍 Vote Anywhere</h3>
        <p>Whether you’re at home or abroad, you can still cast your vote securely.</p>
    </div>

    <div class="feature-box">
        <h3>📊 Transparent Process</h3>
        <p>Real-time results and open audit logs ensure full trust in the election process.</p>
    </div>
</div>

<div class="section">
    <h2>How It Works</h2>
    <p>In three simple steps, you are ready to vote:</p>

    <div class="feature-box">
        <h3>1. Create an Account</h3>
        <p>Register on our platform using your email and ID verification.</p>
        
    </div>

    <div class="feature-box">
        <h3>2. Login to Your Dashboard</h3>
        <p>Access upcoming elections and voting history from your secure portal.</p>
        
    </div>

    <div class="feature-box">
        <h3>3. Cast Your Vote</h3>
        <p>Choose your candidate or decision, then confirm your secure vote.</p>
          <a href="<?= Url::to(['candidate/index', 'section' => 'manage-elections']) ?>">👤</a>
    </div>
</div>

<div class="footer">
    &copy; <?= date('Y') ?> Online Voting System. All rights reserved.
</div>
