<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $mailer = $framework->mailer();

    $mailer->dsn('%env(MAILER_DSN)%');
};
