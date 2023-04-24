<?php

// namespace App\Form;

// use App\Entity\User;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;

// class UserType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('login')
//             ->add('roles')
//             ->add('password')
//             ->add('email')
//             ->add('isVerified')
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => User::class,
//         ]);
//     }
// }

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices'  => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'row_attr' => [
					'class' => 'form-floating'
				],
            ])

            ->add('login', TextType::class, [
				'label' => 'Prénom',
				'attr' => [
					'placeholder' => 'Pseudo',
				],
				'row_attr' => [
					'class' => 'form-floating'
				],
				'required' => true,
			])

            ->add('email', EmailType::class, [
				'label' => 'Adresse email',
				'attr' => [
					'placeholder' => 'Adresse email',
				],
				'row_attr' => [
					'class' => 'form-floating'
				],
				'required' => true,
			])

            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'placeholder' => 'Mot de passe',
                        ],
                    'constraints' => [
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} charactères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de passe',
                    'row_attr' => [
                        'class' => 'form-floating col-6 pe-2'
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'placeholder' => 'Mot de passe',
                        ],
                    'label' => 'Confirmer le mot de passe',
                    'row_attr' => [
                        'class' => 'form-floating col-6 ps-2'
                    ],
                ]
                
			])

			->add('address', AddressType::class, [
                'row_attr' => [
                    'class' => 'd-none',
                ],
                'mapped' => false,
                'data_class' => Address::class,
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event)
                {
                    // get the form
                    $form = $event->getForm();

                    $actual_link = "$_SERVER[REQUEST_URI]";

                    // disable field if it has been populated with a password already
                    if ( $actual_link !== "/user/crud/new"){
                        $form->add('plainPassword', RepeatedType::class, [
                            // instead of being set onto the object directly,
                            // this is read and encoded in the controller
                            'mapped' => false,
                            'type' => PasswordType::class,
                            'required' => false,
                            'first_options' => [
                                'attr' => [
                                    'autocomplete' => 'new-password',
                                    'placeholder' => 'Mot de passe',
                                    ],
                                'constraints' => [
                                    new Length([
                                        'min' => 6,
                                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} charactères',
                                        // max length allowed by Symfony for security reasons
                                        'max' => 4096,
                                    ]),
                                ],
                                'label' => 'Mot de passe',
                                'row_attr' => [
                                    'class' => 'form-floating col-6 pe-2'
                                ],
                            ],
                            'second_options' => [
                                'attr' => [
                                    'autocomplete' => 'new-password',
                                    'placeholder' => 'Mot de passe',
                                    ],
                                'label' => 'Confirmer le mot de passe',
                                'row_attr' => [
                                    'class' => 'form-floating col-6 ps-2'
                                ],
                            ]
                        ]);
                    }
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
