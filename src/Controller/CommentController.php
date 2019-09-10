<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Video;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
	/**
	 * @Route("/", name="comment_index", methods={"GET"})
	 */
	public function index(CommentRepository $commentRepository): Response
	{
		return $this->render('comment/index.html.twig', [
			'comments' => $commentRepository->findAll(),
		]);
	}

	/**
	 * @Route("/new/comment", name="_comment_new", methods={"GET","POST"})
	 */
	public function new(Request $request): Response
	{
		//dd($request);

		$comment = new Comment();
		$form = $this->createForm(CommentType::class, $comment);
		$form->handleRequest($request);


		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($comment);
			$entityManager->flush();

			return $this->redirectToRoute('comment_index');
		}

		return $this->render('comment/new.html.twig', [
			'comment' => $comment,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="comment_show", methods={"GET"})
	 */
	public function show(Comment $comment): Response
	{
		return $this->render('comment/show.html.twig', [
			'comment' => $comment,
		]);
	}

	/**
	 * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
	 */
	public function edit(Request $request, Comment $comment): Response
	{
		$form = $this->createForm(CommentType::class, $comment);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('comment_index', [
				'id' => $comment->getId(),
			]);
		}

		return $this->render('comment/edit.html.twig', [
			'comment' => $comment,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="comment_delete", methods={"DELETE"})
	 */
	public function delete(Request $request, Comment $comment): Response
	{
		if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($comment);
			$entityManager->flush();
		}

		return $this->redirectToRoute('comment_index');
	}

	/**
	 * @Route("/new", name="comment_new", methods={"GET","POST"})
	 */
	public function newComment(Request $request, VideoRepository $videoRepository, \Swift_Mailer $mailer): ?Response
	{
		$entityManager = $this->getDoctrine()->getManager();

		$comment = new Comment();
		$comment->setUser($this->getUser());
		$intParentId = $request->request->get('parentId');
		if (($request->request->get('parentId'))) {
			$comment->setParentId((int)$intParentId);
		}
		$comment->setContent(($request->request->get('comment')));
		$videoId = (int)$request->request->get('videoId');
		$video = $videoRepository->find($videoId);
		$comment->setCreatedAt(new \DateTime());
		$comment->setVideo($video);
		$comment->setUserName($comment->getUser()->getFirstName());
		$comment->setIsDeleted(true);

		$message = (new \Swift_Message('Someone left a comment!' ))
			->setFrom('memoriestocata@gmail.com')
			->setTo('toropumihai@gmail.com')
			->setBody(
				$comment->getContent(),
				'text/plain'
			);
		$mailer->send($message);

		$entityManager->persist($comment);
		$entityManager->flush();

		return $this->render('comment/index.html.twig', [
			'comments' => $comment,
		]);
	}
}
