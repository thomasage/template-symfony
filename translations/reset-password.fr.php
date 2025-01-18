<?php

declare(strict_types=1);

return [
    'actions' => [
        'send_password_reset_email' => 'Envoyer un courriel de réinitialisation du mot de passe',
    ],
    'notifications' => [
        'email_sent' => 'Si un compte existe avec cette adresse, un courriel a été envoyé avec un lien pour réinitialiser votre mot de passe.',
        'link_expiration' => 'Ce lien expirera dans %expiration%.',
        'not_received' => 'Si vous ne recevez pas le courriel, vérifier votre dossier "Indésirables" ou <a href="%link%">essayez à nouveau</a>.',
    ],
    'page_title' => 'Réinitialiser votre mot de passe',
];
