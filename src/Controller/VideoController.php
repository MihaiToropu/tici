<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
	/**
	 * @Route("/", name="app_homepage")
	 */
	public function homepage(VideoRepository $repository)
	{
		$videos = $repository->findAll();

		return $this->render('video/homepage.html.twig', [
			'videos' => $videos
		]);
	}

	//use for path {{ patg('app_video_show', {slug: 'text'})}}

	/**
	 * @Route("/tici/videos/{slug}", name="app_video_show")
	 */
	public function show($slug, EntityManagerInterface $entityManager)
	{
		$repository = $entityManager->getRepository(Video::class);
		/** @var @var Video $video */
		$video = $repository->findOneBy(['slug' => $slug]);

		return $this->render('video/show.html.twig', [
			'video' => $video
		]);
	}

	/**
	 * @Route("/tici/video/{id}", name="app_video_get")
	 */
	public function getVideo($id, EntityManagerInterface $entityManager)
	{
		$repository = $entityManager->getRepository(Video::class);
		/** @var @var Video $video */
		$video = $repository->findVideoById($id);
		//dump(json_encode($video, true));
		//dd($video);
		return new JsonResponse([
			'content' => json_encode($video, true),
		]);

	}

}
