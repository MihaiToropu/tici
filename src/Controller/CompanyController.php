<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company")
 */
class CompanyController extends AbstractController
{
	/**
	 * @Route("/", name="company_index", methods={"GET"})
	 */
	public function index(CompanyRepository $companyRepository): Response
	{
		return $this->render('company/index.html.twig', [
			'companies' => $companyRepository->findAll(),
		]);
	}

	/**
	 * @Route("/new", name="company_new")
	 */
	public function new(Request $request): Response
	{
		$entityManager = $this->getDoctrine()->getManager();

		$user = $this->getUser();
		$company = new Company();
		//dd($request->request->get('name'));
		if ($request->request->get('name')) {
			$company->setName($request->request->get('name'));
			$company->setCreatedAt(new \DateTime('now'));
			$company->setUpdatedAt(new \DateTime('now'));

			$entityManager->persist($company);
			$entityManager->flush();

			$user->setCompany($company);
			$user->setHasCompany(1);
			$entityManager->flush();
		}

		return $this->render('company/new.html.twig', [
			'company' => $company,
		]);
	}

	/**
	 * @Route("/{id}", name="company_show", methods={"GET"})
	 */
	public function show(Company $company): Response
	{
		return $this->render('company/show.html.twig', [
			'company' => $company,
		]);
	}

    /**
     * @Route("/{id}/edit", name="company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_edit', [
                'id' => $company->getId(),
            ]);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

	/**
	 * @Route("/{id}", name="company_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Company $company): Response
	{
		if ($this->isCsrfTokenValid('delete' . $company->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($company);
			$entityManager->flush();
		}

		return $this->redirectToRoute('company_index');
	}

	/**
	 * @Route("/list/employes", name="company_employes", methods={"GET","POST"})
	 */
	public function listUsers(CompanyRepository $companyRepository, Request $request, UserRepository $userRepository): Response
	{
		/** @var User $user */
		$user = $this->getUser();
		$companyId = $user->getCompany()->getId();
		/** @var Company $company */
		$company = $companyRepository->findBy(['id' => $companyId]);
		$company = reset($company);
		$users = $userRepository->findAllByCompanyId($companyId);
		//$users = $company->getUser();

		return $this->render('company/listUsers.html.twig', [
			'Users' => $users,
		]);
	}

	/**
	 * @Route("/list/employes/hasCompany", name="employe_hasCompany", methods={"POST"})
	 */
	public function company(CompanyRepository $companyRepository, Request $request, UserRepository $userRepository): Response
	{
		$userId = (int)$request->request->get('userId');
		/** @var User $user */
		$user = $userRepository->findOneBy(['id'=> $userId]);
		$user->setHasCompany(true);
		$this->getDoctrine()->getManager()->flush();

		$user = $this->getUser();
		$companyId = $user->getCompany()->getId();
		/** @var Company $company */
		$company = $companyRepository->findBy(['id' => $companyId]);
		$company = reset($company);
		$users = $userRepository->findAllByCompanyId($companyId);
		//$users = $company->getUser();

		return $this->render('company/listUsers.html.twig', [
			'Users' => $users,
		]);
	}

	/**
	 * @Route("/list/employes/hasNoCompany", name="employe_hasNoCompany", methods={"POST"})
	 */
	public function companyRefuse(CompanyRepository $companyRepository, Request $request, UserRepository $userRepository): Response
	{
		$userId = (int)$request->request->get('userId');
		/** @var User $user */
		$user = $userRepository->findOneBy(['id'=> $userId]);
		$user->setHasCompany(false);
		$this->getDoctrine()->getManager()->flush();

		$user = $this->getUser();
		$companyId = $user->getCompany()->getId();
		/** @var Company $company */
		$company = $companyRepository->findBy(['id' => $companyId]);
		$company = reset($company);
		$users = $userRepository->findAllByCompanyId($companyId);
		//$users = $company->getUser();

		return $this->render('company/listUsers.html.twig', [
			'Users' => $users,
		]);
	}
}
