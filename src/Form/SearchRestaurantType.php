<?php

namespace App\Form;

use App\Entity\FoodCategory;
use App\Entity\Search;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchRestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', TextType::class, [
                'row_attr' => [
                'class' => 'hidden',
                ]
            ])
            ->add('postal_code', TextType::class, [
                'row_attr' => [
                'class' => 'hidden',
                ]
            ])
            ->add('city', TextType::class, [
                'row_attr' => [
                'class' => 'hidden',
                ]
            ])
            ->add('coordinates', null, [
                'row_attr' => [
                    'class' => 'd-none hidden'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => FoodCategory::class,
                'autocomplete' => true,
                'multiple' => true,
            ]);
        $builder->get("coordinates")->addModelTransformer(new CallbackTransformer(
            // Transform the Point to a string
            function (?Point $point) {
                if (is_null($point)) return "0 0";

                // e.g "-74.07867091 4.66455174"
                return "{$point->getX()} {$point->getY()}";
            },
            // Transform the string from the form back to a Point type
            function (string $coordinates) {
                $coordinates = explode(" ", $coordinates);

                return new Point($coordinates[0], $coordinates[1], null);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
        ]);
    }
}
