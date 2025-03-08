<?php

declare(strict_types=1);

namespace App\Tests\Form\Settings;

use App\Form\Settings\ProfileData;
use App\Form\Settings\ProfileType;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Form\Test\TypeTestCase;

#[CoversClass(ProfileType::class)]
final class ProfileTypeTest extends TypeTestCase
{
    public function testShouldSubmitValidData(): void
    {
        $data = new ProfileData();

        $form = $this->factory->create(ProfileType::class, $data);
        $form->submit([
            'email' => 'test@example.com',
        ]);

        self::assertTrue($form->isSynchronized());
        self::assertSame('test@example.com', $data->email);
    }
}
