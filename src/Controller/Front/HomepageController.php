<?php

namespace App\Controller\Front;

use App\Entity\Search;
use App\Form\SearchRestaurantType;
use App\Repository\ShopRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends AbstractController
{

    private $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $search = new Search();

        $searchForm = $this->createForm(SearchRestaurantType::class, $search);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $shops = $this->shopRepository->findRestaurants($search);
            $request->getSession()->set('shops', $shops);
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
        $shops = $request->getSession()->get('shops');

        if (!$shops) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/result_restaurants.html.twig', [
            'shops' => $shops
        ]);
    }
}
