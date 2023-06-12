<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\FoodCategory;
use App\Entity\Shop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [

            ])
            ->add('description', TextType::class, [
                'required' => false
            ])
            ->add('website', TextType::class, [
                'required' => false
                ])
            ->add('phone', TextType::class, [
                'required' => false
                ])
            ->add('delivery')
            ->add('take_away')
            ->add('avg_price')
            ->add('image', FileType::class, [
                'attr' => [
                    'accept' => '.jpg, .jpeg, .png',
                ],
                'required' => false,
                'mapped' => false,
                'label' => 'Image en .jpg ou .png uniquement (1mo max.)',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger des images au format .jpg ou .png uniqument',
                    ])
                ],
            ])
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
