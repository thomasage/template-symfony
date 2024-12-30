<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Form\UserEditData;
use App\Form\UserEditType;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Form\Test\TypeTestCase;

#[CoversClass(UserEditType::class)]
final class UserEditTypeTest extends TypeTestCase
{
    public function testShouldSubmitValidData(): void
    {
        $data = new UserEditData();

        $form = $this->factory->create(UserEditType::class, $data);
        $form->submit(['email' => 'test@example.com']);

        self::assertTrue($form->isSynchronized());
        self::assertSame('test@example.com', $data->email);
    }
}
