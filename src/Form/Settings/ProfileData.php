<?php

declare(strict_types=1);

namespace App\Form\Settings;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ProfileData
{
    #[Email]
    #[NotBlank]
    public string $email;
}
