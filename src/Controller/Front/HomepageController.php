<?php

namespace App\Controller\Front;

use App\Entity\RestaurantSearch;
use App\Entity\Review;
use App\Entity\Search;
use App\Form\SearchRestaurantType;
use App\Repository\RestaurantSearchRepository;
use App\Form\ShopReviewType;
use App\Repository\ShopRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class HomepageController extends AbstractController
{

    private $shopRepository;
    private $entityManager;

    public function __construct(ShopRepository $shopRepository, EntityManagerInterface $entityManager)
    {
        $this->shopRepository = $shopRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, RestaurantSearchRepository $restaurantSearchRepository): Response
    {
        $search = new Search();

        $searchForm = $this->createForm(SearchRestaurantType::class, $search);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $shops = $this->shopRepository->findRestaurants($search);
            shuffle($shops);
            $request->getSession()->set('shops', $shops);

            $restaurantSearch = new RestaurantSearch();
            $restaurantSearch->setUserAddress($search->getStreet())
                ->setUserCp($search->getPostalCode())
                ->setUserCity($search->getCity())
                ->setUserCoordinates($search->getCoordinates())
                ->setResults($shops)
                ->setCreatedAt(new DateTimeImmutable());
            if ($search->getCategory()) {
                foreach ($search->getCategory() as $category) {
                    $restaurantSearch->addCategory($category);
                }
            }
            if ($this->getUser()) {
                $restaurantSearch->setUser($this->getUser());
            }
            $restaurantSearchRepository->save($restaurantSearch, true);
            return $this->redirectToRoute('app_results', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/homepage.html.twig', [
            'controller' => 'HomepageController',
            'searchForm' => $searchForm,
        ]);
    }




    #[Route('/results', name: 'app_results')]
    public function restaurantSuggestion(Request $request): Response
    {
        $session = $request->getSession()->get('shops');
        $shops = [];

        foreach ($session as $shop) {
            $shops[] = $this->shopRepository->findOneById($shop->getId());
        }

        if (!$shops) {
            return $this->redirectToRoute('app_home');
        }

        // Create the review form
        $review = new Review();
        $reviewForm = $this->createForm(ShopReviewType::class, $review);
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            $review->setShop($shop); // Set the shop for the review
            $review->setUser($this->getUser()); // Set the user for the review
            $review->setCreatedAt(new DateTimeImmutable()); // Set the creation date

            // Save the review to the database
            $this->entityManager->persist($shop);
            $this->entityManager->persist($review);
            $this->entityManager->flush();

            // Redirect to a success page or show a success message
        }

        // Pass the form view to the template
        return $this->render('front/result_restaurants.html.twig', [
            'shops' => $shops,
            'reviewForm' => $reviewForm->createView(),
        ]);
    }
}
