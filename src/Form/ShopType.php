<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\FoodCategory;
use App\Entity\Shop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('website')
            ->add('phone')
            ->add('delivery')
            ->add('take_away')
            ->add('avg_price')
            ->add('address', AddressType::class, [
                'row_attr' => [
                    'class' => 'd-none hidden',
                ],
                'data_class' => Address::class,
            ])
            ->add('category', EntityType::class, [
                'class' => FoodCategory::class,
                'autocomplete' => true,
                'multiple' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shop::class,
        ]);
    }
}
