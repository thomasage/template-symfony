<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
    }

    public static function class(): string
    {
        return User::class;
    }

    public function admin(): self
    {
        return $this->with([
            'roles' => ['ROLE_ADMIN'],
        ]);
    }

    public function withTotpEnabled(string $secret = 'test'): self
    {
        return $this->afterInstantiate(static function (User $user) use ($secret): void {
            $user->setTwoFactorsAuthenticationTotpSecret($secret);
            $user->enableTwoFactorsAuthenticationTotp();
        });
    }

    /**
     * @return array{
     *     email: string,
     *     password: string,
     *     roles: list<string>,
     * }
     */
    protected function defaults(): array
    {
        return [
            'email' => self::faker()->unique()->email(),
            'password' => self::faker()->password(),
            'roles' => [],
        ];
    }

    protected function initialize(): static
    {
        return $this->afterInstantiate(function (User $user): void {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        });
    }
}
