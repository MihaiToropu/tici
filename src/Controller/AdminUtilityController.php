<?php
/**
 * Created by PhpStorm.
 * User: torop
 * Date: 09-Jun-19
 * Time: 10:54 AM
 */

namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminUtilityController extends AbstractController
{
	/**
	 * @Route("/admin/api/users", methods="GET", name="app_admin_api_users")
	 */
	public function getUserApi(UserRepository $userRepository, Request $request)
	{
		$users = $userRepository->findAllUsersEmailMatching($request->query->get('query'));

		return $this->json([
			'users' => $users
		], 200, [], ['groups' => ['user_group_email']]);
	}
}