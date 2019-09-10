<?php

namespace App\Controller;

use App\Entity\Folder;
use App\Form\FolderType;
use App\Repository\FolderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/folder")
 */
class FolderController extends AbstractController
{

    /**
     * @Route("/upload_tutorials", name="app_folder_upload_multiple", methods={"GET","POST"})
     */
    public function uploadMultiple(Request $request): Response
    {
//        dd($request);
        /** @var Folders $folderEntity */
        $tree = [];
        $tree['text'] = 'New course';
        $tree['state'] = [
            'opened' => true,
            'selected' => true
        ];
        $treeResult = json_encode($tree, true);

        return $this->render('folder/upload_multiple.html.twig', [
            'tree' => $treeResult
        ]);
    }

	/**
	 * @Route("/", name="app_folder_index", methods={"GET"})
	 */
	public function index(FolderRepository $folderRepository): Response
	{
		return $this->render('folder/index.html.twig', [
			'folders' => $folderRepository->findAll(),
		]);
	}

	/**
	 * @Route("/new", name="app_folder_new", methods={"GET","POST"})
	 */
	public function new(Request $request): Response
	{
		$folder = new Folder();
		$form = $this->createForm(FolderType::class, $folder);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($folder);
			$entityManager->flush();

			return $this->redirectToRoute('folder_index');
		}

		return $this->render('folder/new.html.twig', [
			'folder' => $folder,
			'form' => $form->createView(),
		]);
	}



	/**
	 * @Route("/{slug}", name="app_folder_show", methods={"GET"})
	 */
	public function show(Folder $folder, FolderRepository $folderRepository): Response
	{

		$folders = $folderRepository->findByIdWithRelatedEntities($folder);

		$folderEntity = reset($folders);
		/** @var Folder $folderEntity */
		$tree = [];
		$tree['text'] = $folder->getTitle();
		$tree['state'] = [
			'opened' => true,
			'selected' => true
		];
		$t = 0;
		foreach ($folderEntity->getTutorials() as $tutorial) {
			$tree['children'][$t] = [
				'id' => $tutorial->getId(),
				'text' => $tutorial->getTitle(),
				'state' => [
					'opened' => true,
					'selected' => true
				]
			];
			foreach ($tutorial->getVideos() as $video) {
				$tree['children'][$t]['children'][] = [
					'text' => $video->getTitle(),
					'id' => $video->getId(),
				];
			}

			$t++;
		}
		$treeResult = json_encode($tree, true);

		return $this->render('folder/show.html.twig', [
			'tree' => $treeResult,
		]);
	}

	/**
	 * @Route("/{id}/edit", name="folder_edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, Folder $folder): Response
	{
		$form = $this->createForm(FolderType::class, $folder);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('folder_index', [
				'id' => $folder->getId(),
			]);
		}

		return $this->render('folder/edit.html.twig', [
			'folder' => $folder,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="folder_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Folder $folder): Response
	{
		if ($this->isCsrfTokenValid('delete' . $folder->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($folder);
			$entityManager->flush();
		}

		return $this->redirectToRoute('folder_index');
	}

}
