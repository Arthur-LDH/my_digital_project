<?php

namespace App\Controller\Front;

use App\Entity\Review;
use App\Entity\Shop;
use App\Form\ShopReviewType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{

    #[Route('/review/new/{id}', name: 'app_new_review')]
    public function index(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {

        $shopRepository = $entityManager->getRepository(Shop::class);
        $reviewRepository = $entityManager->getRepository(Review::class);
        $shop = $shopRepository->findOneById($id);

        $review = $reviewRepository->findOneBy([
            'shop' => $shop,
            'user' => $this->getUser(),
        ]);

        if($review){
            return $this->redirectToRoute('app_edit_review', [
                'id' => $review->getId()
            ]);
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
            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash('success', 'Votre review à bien enregistrée.');
            // Redirect to a success page or show a success message
            return $this->redirectToRoute('app_new_review', [
                'id' => $shop->getId()
            ]);
        }

        return $this->render('front/review/index.html.twig', [
            'reviewForm' => $reviewForm->createView(),
            'shop' => $shop,
            'title' => 'Ecrire une review'
        ]);
    }

    #[Route('/review/edit/{id}', name: 'app_edit_review')]
    public function edit(Review $review, EntityManagerInterface $entityManager, Request $request): Response
    {
        $shop = $review->getShop();

        // Create the review form
        $reviewForm = $this->createForm(ShopReviewType::class, $review);
        $reviewForm->handleRequest($request);

        if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
            // Save the review to the database
            $entityManager->flush();
            $this->addFlash('success', 'Votre review à bien enregistrée.');
            // Redirect to a success page or show a success message
            return $this->redirectToRoute('app_edit_review', [
                'id' => $review->getId()
            ]);
        }

        return $this->render('front/review/index.html.twig', [
            'reviewForm' => $reviewForm->createView(),
            'shop' => $shop,
            'title' => 'Modifier ma review'
        ]);
    }
}
