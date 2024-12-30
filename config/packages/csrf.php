<?php

declare(strict_types=1);

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework): void {
    $framework
        ->form()
        ->csrfProtection()
        ->tokenId('submit');
    $framework
        ->csrfProtection()
        ->statelessTokenIds([
            'submit',
            'authenticate',
            'logout',
        ]);
};
