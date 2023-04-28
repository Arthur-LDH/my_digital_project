<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tsClasses = 'peer block min-h-12 w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0';

        $builder
        // ->add('oldPassword', PasswordType::class,[
        //     'mapped' => false,
        //     'required' => true,
        //     'invalid_message' => 'Le mot de passe n\'est pas valide.',
        //     'attr' => [
        //         'class' => $tsClasses,
        //         'placeholder' => 'Mot de passe actuel',
        //         ],
        //     'label' => 'Mot de passe actuel',
        // ])
        ->add('password', RepeatedType::class, [
            'mapped' => false,
            'type' => PasswordType::class,
            'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques.',
            'required' => true,
            'first_options' => [
                'attr' => [
                    'placeholder' => 'Nouveau mot de passe',
                    'class' => $tsClasses,
                    ],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères',
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Nouveau mot de passe',
            ],
            'second_options' => [
                'attr' => [
                    'class' => $tsClasses,
                    'placeholder' => 'Mot de passe',
                    ],
                'label' => 'Confirmer le mot de passe',
            ]
            
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
