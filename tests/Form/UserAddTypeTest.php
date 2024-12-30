<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Form\UserAddData;
use App\Form\UserAddType;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Form\Test\TypeTestCase;

#[CoversClass(UserAddType::class)]
final class UserAddTypeTest extends TypeTestCase
{
    public function testShouldSubmitValidData(): void
    {
        $data = new UserAddData();

        $form = $this->factory->create(UserAddType::class, $data);
        $form->submit(['email' => 'test@example.com']);

        self::assertTrue($form->isSynchronized());
        self::assertSame('test@example.com', $data->email);
    }
}
