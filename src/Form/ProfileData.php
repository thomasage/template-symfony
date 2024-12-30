<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

final class ProfileData
{
    #[Email]
    #[NotBlank]
    public string $email;

    #[Choice(choices: [false, true])]
    #[NotNull]
    public bool $twoFactorsAuthentication;
}
