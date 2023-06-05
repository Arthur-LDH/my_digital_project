<?php

namespace App\DataFixtures;

use App\Entity\Shop;
use App\DataFixtures\BaseFixtures;
use App\Entity\Address;
use App\Repository\FoodCategoryRepository;
use App\Repository\ShopRepository;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ShopFixtures extends Fixture
{

    private $shopRepository;
    private $categoryRepository;

    public function __construct(ShopRepository $shopRepository, FoodCategoryRepository $categoryRepository){
        $this->shopRepository = $shopRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $address = array();
        $shop = array();
        $categories = $this->categoryRepository->findAll();
        for ($i = 0; $i < 10; $i++) {
                $latitude = rand(476000000, 478000000) / 10000000;   // Génère une latitude entre 47.0000000 et 48.0000000
                $longitude = rand(-2600000, -2800000) / 1000000;    // Génère une longitude
                $address[$i] = new Address;
                $address[$i]->setHouseNumber(rand(1, 50))
                        ->setStreet($faker->streetName)
                        ->setCity("Vannes")
                        ->setPostalCode(56000)
                        ->setCoordinates(new Point($latitude, $longitude, null));
                $manager->persist($address[$i]);
                $shop[$i] = new Shop;
                $shop[$i]->setAddress($address[$i])
                    ->setName($faker->company)
                    ->setPhone("0".rand(297000000, 297999999))
                    ->setWebsite("www.".$shop[$i]->getName().".com")
                    ->setAvgPrice(rand(8,35))
                    ->setDelivery(rand(0,1))
                    ->setTakeAway(rand(0,1));
                
                $categoryIndex = array_rand($categories);
                $randomCategory = $categories[$categoryIndex];
                $shop[$i]->addCategory($randomCategory);
                $manager->persist($shop[$i]);
        }

        $manager->flush();
    }
}
