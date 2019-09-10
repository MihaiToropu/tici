<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tag")
 */
class TagController extends AbstractController
{
	/**
	 * @Route("/", name="tag_index")
	 */
	public function index(TagRepository $tagRepository, Request $request, PaginatorInterface $paginator)
	{
		$searchTags = $request->query->get('searchTags');

		$queryBuilder = $tagRepository->searchTagsQueryBuilder($searchTags);

		$pagination = $paginator->paginate(
			$queryBuilder,
			$request->query->getInt('page', 1),
			5
		);

		return $this->render('tag/index.html.twig', [
			'tagsPagination' => $pagination
		]);
	}

	/**
	 * @Route("/new", name="app_tag_new", methods={"GET","POST"})
	 */
	public function newTag(Request $request): ?Response
	{
		$entityManager = $this->getDoctrine()->getManager();

		/** @var Tag $tag */
		$tag = new Tag();

		if ($request->request->get('name')) {
			$tag->setName($request->request->get('name'));
		}

		if ($request->request->get('slug')) {
			$tag->setSlug($request->request->get('slug'));
		}
		$tag->setCreatedAt(new \DateTime());
		$tag->setUpdatedAt(new \DateTime());

		$entityManager->persist($tag);
		$entityManager->flush();

		return $this->render('tag/show.html.twig', [
			'tag' => $tag,
		]);
	}

	/**
	 * @Route("/create", name="app_tag_create", methods={"GET"})
	 */
	public function create(): ?Response
	{
		return $this->render('tag/create.html.twig');
	}


    /**
     * @Route("/{id}", name="tag_show", methods={"GET"})
     */
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tag_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tag_index', [
                'id' => $tag->getId(),
            ]);
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tag_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tag_index');
    }
}
