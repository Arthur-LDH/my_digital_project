<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\CallbackTransformer;
use CrEOF\Spatial\PHP\Types\Geometry\Point;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('house_number')
            ->add('street')
            ->add('postal_code')
            ->add('city')
            ->add('coordinates')
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
