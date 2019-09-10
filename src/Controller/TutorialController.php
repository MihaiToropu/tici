<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Entity\Tutorial;
use App\Entity\User;
use App\Form\TutorialType;
use App\Repository\CategoryRepository;
use App\Repository\CompanyRepository;
use App\Repository\FolderRepository;
use App\Repository\TutorialRepository;
use App\Repository\UserRepository;
use function Couchbase\defaultDecoder;
use Knp\Component\Pager\PaginatorInterface;
use function PHPSTORM_META\type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tutorial")
 */
class TutorialController extends AbstractController
{
	/**
	 * @Route("/", name="tutorial_index", methods={"GET"})
	 */
	public function index(TutorialRepository $tutorialRepository): Response
	{
		return $this->render('tutorial/index.html.twig', [
			'tutorials' => $tutorialRepository->findAll(),
		]);
	}


	/**
	 * @Route("/add/to/fallow", name="watching_tutorial", methods={"GET","POST"})
	 */
	public function followUnfollow(
		Request $request,
		TutorialRepository $tutorialRepository,
		UserRepository $userRepository
	): Response {
		$entityManager = $this->getDoctrine()->getManager();
		/** @var User $user */
		$user = new User();
		/** @var Tutorial $tutorial */
		$tutorial = new Tutorial();

		$user = $this->getUser();
		$tutorialId = (int)$request->request->get('tutorialId');
		$tutorial = $tutorialRepository->find($tutorialId);
		$user->addWatching($tutorial);

		$tutorial->addUser($user);

		$entityManager->persist($user);
		$entityManager->persist($tutorial);
		$entityManager->flush();

		return $this->render('ask_anything/success.html.twig');
	}

	/**
	 * @Route("/to/unfallow", name="unwatch_tutorial", methods={"GET","POST"})
	 */
	public function unfollow(
		Request $request,
		TutorialRepository $tutorialRepository,
		UserRepository $userRepository
	): Response {
		$entityManager = $this->getDoctrine()->getManager();
		/** @var User $user */
		$user = new User();
		/** @var Tutorial $tutorial */
		$tutorial = new Tutorial();

		$user = $this->getUser();
		$tutorialId = (int)$request->request->get('tutorialId');
		$tutorial = $tutorialRepository->find($tutorialId);
		$user->removeWatching($tutorial);

		$tutorial->removeUser($user);

		$entityManager->persist($user);
		$entityManager->persist($tutorial);
		$entityManager->flush();

		return $this->render('ask_anything/success.html.twig');
	}

	/**
	 * @Route("/new", name="tutorial_new", methods={"GET","POST"})
	 */
	public function new(
		Request $request
	): Response {
		$tutorial = new Tutorial();
		$form = $this->createForm(TutorialType::class, $tutorial);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($tutorial);
			$entityManager->flush();

			return $this->redirectToRoute('tutorial_index');
		}

		return $this->render('tutorial/new.html.twig', [
			'tutorial' => $tutorial,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{slug}", name="app_tutorial_show", methods={"GET"})
	 */
	public function show(Tutorial $tutorial): Response
	{
		return $this->render('tutorial/show.html.twig', [
			'tutorial' => $tutorial,
		]);
	}

	/**
	 * @Route("/{id}/edit", name="tutorial_edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, Tutorial $tutorial): Response
	{
		$form = $this->createForm(TutorialType::class, $tutorial);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('tutorial_index', [
				'id' => $tutorial->getId(),
			]);
		}

		return $this->render('tutorial/edit.html.twig', [
			'tutorial' => $tutorial,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="tutorial_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Tutorial $tutorial): Response
	{
		if ($this->isCsrfTokenValid('delete' . $tutorial->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($tutorial);
			$entityManager->flush();
		}

		return $this->redirectToRoute('tutorial_index');
	}


	/**
	 * @Route("/user/following", name="tutorials_following", methods={"GET"})
	 */
	public function followedTutorials(
		TutorialRepository $tutorialRepository,
		CompanyRepository $companyRepository,
		FolderRepository $folderRepository,
		PaginatorInterface $paginator,
		CategoryRepository $categoryRepository,
		Request $request
	) {
		/** @var User $user */
		$user = new User();
		$user = $this->getUser();
		$tutorials = $user->getWatching()->toArray();
		$pagination = $paginator->paginate(
			$tutorials,
			$request->query->getInt('page', 1),
			5
		);
		return $this->render('tutorial/followedTutorials.html.twig', [
			'tutorials' => $pagination,
			'categories' => $categoryRepository,
		]);
	}

	/**
	 * @Route("/user/company-courses", name="company_tutorials", methods={"GET"})
	 */
	public function company(
		TutorialRepository $tutorialRepository,
		CompanyRepository $companyRepository,
		FolderRepository $folderRepository
	) {
		/** @var User $user */
		$user = new User();
		$user = $this->getUser();
		$tutorials = $user->getWatching()->toArray();

		if ($user->getHasCompany()) {
			$companyId = $user->getCompany()->getId();
			/** @var Folder $courses */
			$courses = $folderRepository->findAllByCompanyId($companyId);
		}

		return $this->render('tutorial/companyTutorials.html.twig', [
			'tutorials' => $tutorials,
			'folders' => $courses,
		]);
	}

	/**
	 * @Route("/all/tutorials", name="app_tutorials_index")
	 */
	public function searchTutorials(
		TutorialRepository $tutorialRepository,
		Request $request,
		PaginatorInterface $paginator,
		CategoryRepository $categoryRepository
	) {
		$searchTutorials = $request->query->get('searchTutorials');
		if ($searchTutorials == null) {
			$queryBuilder = $tutorialRepository->findAll();
			//dd($queryBuilder);
		} else {
			$queryBuilder = $tutorialRepository->searchTutorialsQueryBuilder($searchTutorials);
		}


		$pagination = $paginator->paginate(
			$queryBuilder,
			$request->query->getInt('page', 1),
			5
		);

		return $this->render('tutorial/listTutorials.html.twig', [
			'tutorials' => $pagination,
			'categories' => $categoryRepository->findAll(),

		]);
	}

	/**
	 * @Route("/category/tutorials", name="app_tutorials_category")
	 */
	public function byCategory(
		TutorialRepository $tutorialRepository,
		Request $request,
		PaginatorInterface $paginator,
		CategoryRepository $categoryRepository
	) {
		dump($request->request->get('categoryId'));
		if ($request->request->get('categoryId')) {
			$categoryId = (int) $request->request->get('categoryId');
			$category = $categoryRepository->findOneBy(['id' => $categoryId]);
			$queryBuilder = $tutorialRepository->searchTutorialsByCategory($categoryId);
			dd($queryBuilder);
			//$queryBuilder = $category->getTutorials();

			dd($queryBuilder);
		} else {
			$queryBuilder = $tutorialRepository->findAll();
			dd('ny utra');
		}


		$pagination = $paginator->paginate(
			$queryBuilder,
			$request->query->getInt('page', 1),
			5
		);


		return $this->render('tutorial/listTutorials.html.twig', [
			'tutorials' => $pagination,
			'categories' => $categoryRepository->findAll(),
		]);
	}
}
