<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

final class UserAddData
{
    #[Email]
    #[NotBlank]
    public string $email;

    #[NotBlank]
    #[PasswordStrength(minScore: PasswordStrength::STRENGTH_WEAK)]
    public string $password;
}
