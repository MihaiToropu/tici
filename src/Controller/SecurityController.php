<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\Model\RegistrationFormModel;
use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
		if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
			return new RedirectResponse('/');
		}
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error
        ]);
    }

	/**
	 * @Route("/register", name="app_register")
	 */
	public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $loginFormAuthenticator, GuardAuthenticatorHandler $guardAuthenticatorHandler)
	{
		if ($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')) {
			return new RedirectResponse('/');
		}

		$form = $this->createForm(RegistrationType::class);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			/** @var RegistrationFormModel $userModel */
			$userModel = $form->getData();

			$user = new User();
			$user->setEmail($userModel->email);
			$user->setPassword($passwordEncoder->encodePassword(
				$user,
				$userModel->plainPassword
			));
			$user->setFirstName('Ted');
			$user->setHasCompany(false);
			if ($userModel->agreeTerms === true) {
				$user->agreeTerms();
			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
				$user,
				$request,
				$loginFormAuthenticator,
				'main'
			);
		}

		return $this->render('security/register.html.twig', [
			'registration' => $form->createView()
		]);
	}

	/**
	 * @Route("/logout", name="app_logout")
	 */
	public function logout()
	{
		throw new \Exception('Logut');
	}
}
