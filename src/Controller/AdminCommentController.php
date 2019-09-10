<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\VideoRepository;
use function Couchbase\defaultDecoder;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
	/**
	 * @Route("/admin/comment", name="admin_comment")
	 */
	public function index(CommentRepository $commentRepository, Request $request, PaginatorInterface $paginator)
	{
		$searchComments = $request->query->get('searchComments');

		$queryBuilder = $commentRepository->searchCommentsQueryBuilder($searchComments);


		$pagination = $paginator->paginate(
			$queryBuilder,
			$request->query->getInt('page', 1),
			5
		);

		return $this->render('admin_comment/index.html.twig', [
			'commentsPagination' => $pagination
		]);
	}

	/**
	 * @Route("/admin/list/comments", name="admin_list_comments")
	 */
	public function listComments(CommentRepository $commentRepository, Request $request, PaginatorInterface $paginator)
	{
		$searchComments = $request->query->get('searchComments');

		$queryBuilder = $commentRepository->searchCommentsQueryBuilder($searchComments);


		$pagination = $paginator->paginate(
			$queryBuilder,
			$request->query->getInt('page', 1),
			5
		);

		return $this->render('admin_comment/listComments.html.twig', [
			'commentsPagination' => $pagination
		]);
	}


	/**
	 * @Route("/tici/comment/{id}", name="app_video_comments")
	 */
	public function getVideo(CommentRepository $commentRepository, $id) {
		$commentsWithParent = $commentRepository->findCommentsWithParrentIdByVideoId($id);
		$commentsWoParent = $commentRepository->findCommentsWoParentIdByVideoId($id);

		$replies = [];
		foreach ($commentsWoParent as $commentWoParent) {
			$replies[] = $commentWoParent;
			foreach ($commentsWithParent as $commentWithParent) {
				if ($commentWoParent['id'] == $commentWithParent['parentId']) {
					$replies[] = $commentWithParent;
				}
			}
		}
		$response = [
			'code' => 200,
			'response' => $this->render('admin_comment/index.html.twig', [
				'tree' => $replies,
			])->getContent(),
		];

		return new JsonResponse([
			'response' => json_encode($response, true),
		]);

	}


	/**
	 * @Route("/tici/comment/{id}", name="app_video_comments")
	 */
	/*public function getVideo(CommentRepository $commentRepository, $id, EntityManagerInterface $entityManager,  PaginatorInterface $paginator, Request $request)
	{
		$pagination = $commentRepository->findCommentById($id);

		$response = [
			'code' => 200,
			'response' => $this->render('admin_comment/index.html.twig', ['commentsPagination' => $pagination])->getContent(),
		];

		return new JsonResponse([
			'response' => json_encode($response, true),
		]);

	}*/
}
