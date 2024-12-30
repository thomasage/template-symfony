<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'fields.email',
                'required' => true,
            ])
            ->add('twoFactorsAuthentication', CheckboxType::class, [
                'label' => 'fields.2fa',
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'roles.admin' => 'ROLE_ADMIN',
                ],
                'label' => 'fields.roles',
                'multiple' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserEditData::class,
        ]);
    }
}
