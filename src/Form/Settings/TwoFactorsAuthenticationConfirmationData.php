<?php

declare(strict_types=1);

namespace App\Form\Settings;

use Symfony\Component\Validator\Constraints\NotBlank;

final class TwoFactorsAuthenticationConfirmationData
{
    #[NotBlank]
    public string $code;
}
