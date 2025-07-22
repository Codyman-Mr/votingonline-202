<?php
use yii\helpers\Html;
$this->title = 'About Us';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }
        header {
            background: #34495e;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 40px 0;
        }
        h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        h3 {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .about-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 40px;
        }
        .about-text {
            flex: 1;
            min-width: 300px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .about-text ul {
            list-style-type: none;
            padding-left: 0;
        }
        .about-text ul li {
            margin: 10px 0;
            font-size: 18px;
        }
        .about-img {
            flex: 1;
            min-width: 300px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .about-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        footer {
            background: #34495e;
            color: #fff;
            text-align: center;
            padding: 20px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1>About Our Voting System</h1>
    <p>Empowering the Future of Online Voting</p>
</header>

<div class="container">
    <div class="about-content">
        <div class="about-text">
            <h3>Our Mission</h3>
            <p>We aim to revolutionize the voting process by making elections more secure, transparent, and accessible for everyone around the world.</p>

            <h3>What We Offer</h3>
            <ul>
                <li>Real-time voting updates</li>
                <li>High-level security and encryption</li>
                <li>Seamless user experience across devices</li>
                <li>Scalable for all types of elections</li>
            </ul>

            <h3>Why Choose Us?</h3>
            <p>Our platform provides a highly secure and efficient voting environment that ensures every vote counts. By leveraging modern technology, we can ensure a safe, fast, and easy voting experience.</p>
        </div>

        
    </div>

    <h3>Future Goals</h3>
    <p>We are continually improving our system to include more features such as multilingual support, mobile app integration, and international elections.</p>
</div>

<footer>
    <p>&copy; 2025 Voting Platform | All Rights Reserved</p>
</footer>

</body>
</html>
