<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ProfileData
{
    #[Email]
    #[NotBlank]
    public string $email;
}
