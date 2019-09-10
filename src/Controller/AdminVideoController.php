<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use function Couchbase\defaultDecoder;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminVideoController extends AbstractController
{
	/**
	 * @Route("/admin/video/new", name="app_admin_video_new")
	 */
	public function new(EntityManagerInterface $entityManager, Request $request)
	{

		$videoForm = $this->createForm(VideoType::class, null, [
			//delete if you dont want published at in new form
			'published_at' => true
		]);

		$videoForm->handleRequest($request);
		if ($videoForm->isSubmitted() && $videoForm->isValid()) {
			/**	@var Video $video*/
			$video = $videoForm->getData();

			$entityManager->persist($video);
			$entityManager->flush();

			$this->addFlash('newVideoMessage', 'Video added to tutorial');

			return $this->redirectToRoute('app_homepage');
		}

		return $this->render('admin_video/new.twig', [
			'videoForm' => $videoForm->createView(),
		]);
	}

	/**
	 * @Route("/admin/video", name="admin_video")
	 * @IsGranted("ROLE_ADMIN_VIDEO")
	 */
	public function index()
	{
		return $this->render('admin_video/index.html.twig', [
			'controller_name' => 'AdminVideoController',
		]);
	}

	/**
	 * @Route("/admin/video/{id}/edit", name="app_admin_video_edit")
	 * @IsGranted("EDIT", subject="video")
	 */
	public function edit(Video $video,Request $request, EntityManagerInterface $entityManager)
	{
		$videoForm = $this->createForm(VideoType::class, $video, [
			'published_at' => true
		]);

		$videoForm->handleRequest($request);
		if ($videoForm->isSubmitted() && $videoForm->isValid()) {

			$entityManager->persist($video);
			$entityManager->flush();

			$this->addFlash('succes', 'Video updated succesfully');

			return $this->redirectToRoute('app_admin_video_edit', [
				'id' => $video->getId(),
			]);
		}

		return $this->render('admin_video/edit.twig', [
			'videoForm' => $videoForm->createView(),
		]);
	}

	/**
	 * @Route("admin/videos", name="app_admin_videos")
	 */
	public function listVideos(VideoRepository $videoRepository)
	{
		$videos = $videoRepository->findAll();

		return $this->render('admin_video/listVideos.twig', [
			'videos' => $videos
		]);
	}
}
