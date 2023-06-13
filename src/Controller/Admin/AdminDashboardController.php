<?php

namespace App\Controller\Admin;

use App\Repository\RestaurantSearchRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(RestaurantSearchRepository $restaurantSearchRepository, ReviewRepository $reviewRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'totalSearch' => $restaurantSearchRepository->count([]),
            'totalMonth' => $restaurantSearchRepository->findTotalResearchesThisMonth(),
            'mostFrequentCity' => $restaurantSearchRepository->findMostFrequentCities(),
            'mostFrequentCategory' => $restaurantSearchRepository->findMostFrequentCategories(),
            'totalReview' => $reviewRepository->count([])
        ]);
    }
}
