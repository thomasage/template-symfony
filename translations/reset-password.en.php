<?php

declare(strict_types=1);

return [
    'actions' => [
        'send_password_reset_email' => 'Send a password reset email',
    ],
    'notifications' => [
        'email_sent' => 'If an account matching your email exists, then an email was just sent that contains a link that you can use to reset your password.',
        'link_expiration' => 'This link will expire in %expiration%.',
        'not_received' => 'If you don\'t receive an email please check your spam folder or <a href="%link%">try again</a>.',
    ],
    'page_title' => 'Reset your password',
];
