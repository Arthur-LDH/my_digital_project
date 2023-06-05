<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\CallbackTransformer;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tsClasses = 'peer block min-h-12 w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0';

        $builder
            ->add('name', TextType::class, [
                'attr' => [
                'class' => $tsClasses,
                ],
                'required' => false
            ])
            ->add('house_number', TextType::class, [
                'row_attr' => [
                'class' => '',
                ]
            ])
            ->add('street', TextType::class, [
                'row_attr' => [
                'class' => '',
                ]
            ])
            ->add('postal_code', TextType::class, [
                'row_attr' => [
                'class' => '',
                ]
            ])
            ->add('city', TextType::class, [
                'row_attr' => [
                'class' => '',
                ]
            ])
            ->add('coordinates', TextType::class, [
                'row_attr' => [
                'class' => '',
                ]
            ])
        ;
		$builder->get("coordinates")->addModelTransformer(new CallbackTransformer(
		// Transform the Point to a string
			function (?Point $point) {
				if(is_null($point)) return "0 0";
			
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
            'data_class' => Address::class,
        ]);
    }
}
