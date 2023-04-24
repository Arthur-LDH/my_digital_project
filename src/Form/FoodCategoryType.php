<?php

namespace App\Form;

use App\Entity\Filter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Rechercher',
                'attr' => [
                    'placeholder' => 'Rechercher', 
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'required' => false,
                'label' => 'Filtrer par role',
                'attr' => [
                    'placeholder' => 'Filtrer par role',
                ],
                'choices'  => [
                    'Utilisateur' => '[]',
                    'Admin' => 'ROLE_ADMIN'
                ],
            ])
            ->add('isVerified', ChoiceType::class, [
                'required' => false,
                'label' => 'Utilisateur vérifié',
                'attr' => [
                    'placeholder' => 'Filtrer par role',
                ],
                'choices'  => [
                    'Vérifié' => '1',
                    'Non vérifié' => '0'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
