<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountProfileController extends BaseController
{
	/**
	 * @Route("/account/profile", name="app_account_profile")
	 */
	public function index(CompanyRepository $companyRepository)
	{
		$companies = $companyRepository->findAll();
		return $this->render('account_profile/index.html.twig', [
			'companies' => $companies,
		]);
	}

	/**
	 * @Route("/api/account/profile", name="api_account")
	 */
	public function accountProfileApi()
	{
		$user = $this->getUser();

		return $this->json($user, 200, [], [
			'groups' => ['userInfo']
		]);
	}

	/**
	 * @Route("/account/update", name="app_account_profile_update")
	 */
	public function update(CompanyRepository $companyRepository, Request $request)
	{
		$user = $this->getUser();

		if ($request->request->get('firstName')) {
			$user->setFirstName($request->request->get('firstName'));
		}
		if ($request->request->get('company')) {
			$companyId = $request->request->get('company');
			$company = $companyRepository->findBy(['id' => $companyId]);
			$user->setCompany(reset($company));
			$user->setHasCompany(false);
		}

		$this->getDoctrine()->getManager()->flush();

		$companies = $companyRepository->findAll();
		return $this->render('account_profile/index.html.twig', [
			'companies' => $companies,
		]);
	}

	/**
	 * @Route("/account/forgot/password", name="app_forgot_password")
	 */
	public function forgotPassword(Request $request, \Swift_Mailer $mailer, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
	{

		$email = $request->request->get('email');
		if ($userRepository->findBy(['email' => $email])) {
		/** @var User $user */
			$user = $userRepository->findBy(['email' => $email]);
			$password = 'castraveti';
			$user->setPassword($passwordEncoder->encodePassword(
				$user,
				$password));
			$emailContent = 'Your new password is ' . $password;
			$message = (new \Swift_Message('TICI change password'))
				->setFrom('memoriestocata@gmail.com')
				->setTo($email)
				->setBody(
					$emailContent,
					'text/plain'
				);
			$mailer->send($message);
		}

		return $this->render('/account_profile/forgotPassword.html.twig', [

		]);
	}

}
