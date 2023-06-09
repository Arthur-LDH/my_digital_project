<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Entity\Address;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
	private EmailVerifier $emailVerifier;

	public function __construct(EmailVerifier $emailVerifier)
	{
		$this->emailVerifier = $emailVerifier;
	}

	#[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
	public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
	{
		if ($this->getUser()) {
			return $this->redirectToRoute('user_profile');
		}

		$user = new User();
		$address = new Address();

		$form = $this->createForm(RegistrationFormType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$address = $form->get('address')->getData();
			$address->setIdUser($user);
			$entityManager->persist($address);
			// encode the plain password
			$user->setPassword(
				$userPasswordHasher->hashPassword(
					$user,
					$form->get('plainPassword')->getData()
				)
			);
			$entityManager->persist($user);
			$entityManager->flush();

			// generate a signed url and email it to the user
			// $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
			// 	(new TemplatedEmail())
			// 		->from(new Address('contact@mon-domaine.fr', 'Contact'))
			// 		->to($user->getEmail())
			// 		->subject('Veuillez confirmer votre adresse email')
			// 		->htmlTemplate('authentification/confirmation_email.html.twig')
			// );
			// do anything else you need here, like send an email

			// return $userAuthenticator->authenticateUser(
			// 	$user,
			// 	$request
			// );
			$this->addFlash('success', 'Votre compte a bien été créé');

			return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
		}

		$error = $authenticationUtils->getLastAuthenticationError();
		if ($error) {
			$this->addFlash('error', 'Votre formulaire d\'inscription n\'est pas valide');
		}

		return $this->render('registration/register.html.twig', [
			'registrationForm' => $form->createView(),
			'error' => $error,
		]);
	}

	// #[Route('/verify/email', name: 'app_verify_email')]
	// public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
	// {
	//     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

	//     // validate email confirmation link, sets User::isVerified=true and persists
	//     try {
	//         $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
	//     } catch (VerifyEmailExceptionInterface $exception) {
	//         $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

	//         return $this->redirectToRoute('app_register');
	//     }

	//     // @TODO Change the redirect on success and handle or remove the flash message in your templates
	//     $this->addFlash('success', 'Your email address has been verified.');

	//     return $this->redirectToRoute('app_register');
	// }
}
