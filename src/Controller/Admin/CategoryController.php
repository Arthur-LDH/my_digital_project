<?php

namespace App\Controller\Admin;

use App\Entity\FoodCategory;
use App\Entity\UserFilter;
use App\Form\UserFilterType;
use App\Form\CategoryType;
use App\Repository\FoodCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/category/crud')]
class CategoryCrudController extends AbstractController
{
    private $categoryRepository;
    private $entityManager;

    public function __construct(FoodCategoryRepository $categoryRepository, EntityManagerInterface $entityManager){
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_admin_category_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        //Filter form for the index
        $search = new UserFilter();
        $form = $this->createForm(UserFilterType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->categoryRepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 20
        );

        // $users = $this->$categoryRepository->findAllVisibleQuery($search);

        return $this->render('/admin/category_crud/index.html.twig', [
            'users' => $properties,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $category = new FoodCategory();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryRepository->save($category, true);

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_crud/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_show', methods: ['GET'])]
    public function show(FoodCategory $category): Response
    {
        return $this->render('admin/category_crud/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FoodCategory $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryRepository->save($category, true);

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_crud/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, FoodCategory $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
