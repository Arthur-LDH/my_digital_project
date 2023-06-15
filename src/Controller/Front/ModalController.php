<?php

namespace App\Controller\Front;

use App\Repository\RestaurantSearchRepository;
use App\Repository\ReviewRepository;
use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ModalController extends AbstractController
{

    private $restaurantSearchRepository;
    private $shopRepository;
    private $reviewRepository;

    public function __construct(RestaurantSearchRepository $restaurantSearchRepository, ShopRepository $shopRepository, ReviewRepository $reviewRepository)
    {
        $this->restaurantSearchRepository = $restaurantSearchRepository;
        $this->shopRepository = $shopRepository;
        $this->reviewRepository = $reviewRepository;
    }

    #[Route('modal/reviews/{id}', name: 'app_modal_reviews')]
    public function modalReviews(int $id)
    {
        $shop = $this->shopRepository->findOneById($id);

        $reviews = $this->reviewRepository->findByShop($shop);

        return $this->render('components/reviews/_reviews.html.twig', [
            'reviews' => $reviews,
            'shop' => $shop
        ]);
    }

    #[Route('modal/selection/{id}', name: 'app_modal_selection')]
    public function modalSelection(int $id)
    {
        $search = $this->restaurantSearchRepository->findOneById($id);

        return $this->render('components/reviews/_selection_list.html.twig', [
            'selection' => $search
        ]);
    }
}