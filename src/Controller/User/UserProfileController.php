<?php

namespace App\Controller\User;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\ChangePasswordType;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class UserProfileController extends AbstractController
{

    private $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    #[Route('/', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('front/user/index.html.twig', [
        ]);
    }

    #[Route('/password', name: 'app_account_password')]
    public function password(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        assert($user instanceof \App\Entity\User);
        //Removes the errors on unexisting methods for the entity User(), can appears on some IDEs.
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
				$userPasswordHasher->hashPassword(
					$user,
					$form->get('password')->getData()
                )
			);
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_account');
        }

        return $this->render('front/user/password.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/address', name: 'app_account_address')]
    public function address()
    {
        return $this->render('front/user/address.html.twig', [
        ]);
    }

    #[Route('/address/create', name: 'app_account_add_address')]
    public function add_address(Request $request)
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setIdUser($this->getUser());
            $this->addressRepository->save($address, true);

            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('front/user/address_add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/address/edit/{id}', name: 'app_account_edit_address')]
    public function edit_address(int $id, Request $request)
    {
        $address = $this->addressRepository->findOneBy(['id' => $id]);
        
        if(!$address || $address->getIdUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_address');
        }
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $address->setIdUser($this->getUser());
            $this->addressRepository->save($address, true);

            return $this->redirectToRoute('app_account_address');
        }

        return $this->render('front/user/address_edit.html.twig', [
            'form' => $form,
            'address' => $address
        ]);
    }

    #[Route('/address/remove/{id}', name: 'app_account_remove_address')]
    public function remove_address(int $id)
    {
        $address = $this->addressRepository->findOneBy(['id' => $id]);
        
        if(!$address || $address->getIdUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_address');
        }
        $this->addressRepository->remove($address, true);

        return $this->redirectToRoute('app_account_address');
    }
}
