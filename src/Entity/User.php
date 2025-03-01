<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface as TwoFactorInterfaceEmail;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfiguration;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfigurationInterface;
use Scheb\TwoFactorBundle\Model\Totp\TwoFactorInterface as TwoFactorInterfaceTotp;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use DateTimeImmutable;
use LogicException;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_UUID', fields: ['uuid'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements PasswordAuthenticatedUserInterface, TwoFactorInterfaceEmail, TwoFactorInterfaceTotp, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private Uuid $uuid;

    #[ORM\Column(length: 180)]
    private string $email;

    /**
     * @var list<string>
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $password;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastLoginAt = null;

    #[ORM\Column]
    private bool $twoFactorsAuthenticationEmailEnabled = false;

    #[ORM\Column]
    private bool $twoFactorsAuthenticationTotpEnabled = false;

    #[ORM\Column(nullable: true)]
    private ?string $twoFactorsAuthenticationEmailCode = null;

    #[ORM\Column(nullable: true)]
    private ?string $twoFactorsAuthenticationTotpSecret = null;

    public function __construct()
    {
        $this->uuid = Uuid::v6();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        \assert('' !== $this->email);

        return $this->email;
    }

    /**
     * @return non-empty-list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_values(array_unique($roles));
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastLoginAt(): ?DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(): self
    {
        $this->lastLoginAt = new DateTimeImmutable();

        return $this;
    }

    public function getEmailAuthCode(): ?string
    {
        if (null === $this->twoFactorsAuthenticationEmailCode) {
            throw new LogicException('The email authentication code was not set');
        }

        return $this->twoFactorsAuthenticationEmailCode;
    }

    public function isTotpAuthenticationEnabled(): bool
    {
        return $this->twoFactorsAuthenticationTotpEnabled && null !== $this->twoFactorsAuthenticationTotpSecret;
    }

    public function getTotpAuthenticationUsername(): string
    {
        return $this->email;
    }

    public function getTotpAuthenticationConfiguration(): ?TotpConfigurationInterface
    {
        if (null === $this->twoFactorsAuthenticationTotpSecret) {
            return null;
        }

        return new TotpConfiguration(
            secret: $this->twoFactorsAuthenticationTotpSecret,
            algorithm: TotpConfiguration::ALGORITHM_SHA1,
            period: 20,
            digits: 6,
        );
    }

    public function hasTwoFactorsAuthentication(): bool
    {
        return $this->twoFactorsAuthenticationEmailEnabled;
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->twoFactorsAuthenticationEmailCode = $authCode;
    }

    public function setTwoFactorsAuthenticationTotpSecret(?string $secret): self
    {
        $this->twoFactorsAuthenticationTotpSecret = $secret;

        return $this;
    }

    public function disableTwoFactorsAuthenticationTotp(): void
    {
        $this->twoFactorsAuthenticationTotpEnabled = false;
        $this->twoFactorsAuthenticationTotpSecret = null;
    }

    public function enableTwoFactorsAuthenticationTotp(): void
    {
        $this->twoFactorsAuthenticationTotpEnabled = true;
    }

    public function enableTwoFactorsAuthenticationEmail(): void
    {
        $this->twoFactorsAuthenticationEmailEnabled = true;
    }
}
