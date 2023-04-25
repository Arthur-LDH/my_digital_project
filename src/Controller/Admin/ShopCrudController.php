<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Shop;
use App\Entity\Filter;
use App\Form\ShopFilterType;
use App\Form\ShopType;
use App\Repository\AddressRepository;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/shop/crud')]
class ShopCrudController extends AbstractController
{
    private $shopRepository;
    private $entityManager;

    public function __construct(ShopRepository $shopRepository, EntityManagerInterface $entityManager){
        $this->shopRepository = $shopRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_admin_shop_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        //Filter form for the index
        $search = new Filter();
        $form = $this->createForm(ShopFilterType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->shopRepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 20
        );

        return $this->render('//admin/shop_crud/index.html.twig', [
            'shops' => $properties,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_shop_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $shop = new Shop();
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->get('address')->getData();
            $this->entityManager->persist($address);
            $shop->setAddress($address);
            $this->entityManager->persist($shop);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('/admin/shop_crud/new.html.twig', [
            'shop' => $shop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_shop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shop $shop, Address $address, AddressRepository $addressRepository): Response
    {
        $form = $this->createForm(ShopType::class, $shop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $shop->getAddress();
            $addressData = $form->get('address')->getData();
            $address->setHouseNumber($addressData->getHouseNumber());
            $address->setStreet($addressData->getStreet());
            $address->setPostalCode($addressData->getPostalCode());
            $address->setCity($addressData->getCity());
            $address->setCoordinates($addressData->getCoordinates());
            $addressRepository->save($address, true);
            $this->shopRepository->save($shop, true);

            return $this->redirectToRoute('app_admin_shop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('/admin/shop_crud/edit.html.twig', [
            'shop' => $shop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_shop_delete', methods: ['POST'])]
    public function delete(Request $request, Shop $shop): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shop->getId(), $request->request->get('_token'))) {
            $this->shopRepository->remove($shop, true);
        }

        return $this->redirectToRoute('app_admin_shop_index', [], Response::HTTP_SEE_OTHER);
    }
}
