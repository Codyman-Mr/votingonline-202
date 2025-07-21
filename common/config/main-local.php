<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DB_DSN') ?: 'pgsql:host=dpg-d1v7hcjuibrs73958tug-a.oregon-postgres.render.com;port=5432;dbname=votingonline',
            'username' => getenv('DB_USERNAME') ?: 'votingonline_user',
            'password' => getenv('DB_PASSWORD') ?: 'fGjXQAlVJ8HenncVjDIKQex1Zpr3Q0YF',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
            // Usitumie transport kwa sasa kama hutumi barua pepe halisi
        ],
    ],
];
