<?php

declare(strict_types=1);

namespace App\Tests\Form\Settings;

use App\Form\Settings\TwoFactorsAuthenticationConfirmationData;
use App\Form\Settings\TwoFactorsAuthenticationConfirmationType;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Form\Test\TypeTestCase;

#[CoversClass(TwoFactorsAuthenticationConfirmationType::class)]
final class TwoFactorsAuthenticationConfirmationTypeTest extends TypeTestCase
{
    public function testShouldSubmitValidData(): void
    {
        $data = new TwoFactorsAuthenticationConfirmationData();

        $form = $this->factory->create(TwoFactorsAuthenticationConfirmationType::class, $data);
        $form->submit([
            'code' => '000000',
        ]);

        self::assertTrue($form->isSynchronized());
        self::assertSame('000000', $data->code);
    }
}
