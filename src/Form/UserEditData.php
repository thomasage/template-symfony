<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class UserEditData
{
    #[Email]
    #[NotBlank]
    public string $email;

    /**
     * @var list<string>
     */
    public array $roles;
}
