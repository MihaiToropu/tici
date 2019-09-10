<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AskAnythingController extends AbstractController
{
	/**
	 * @Route("/ask/anything", name="new_question", methods={"GET","POST"})
	 */
	public function new(Request $request, \Swift_Mailer $mailer)
	{
		/** @var Question $question */
		$question = new Question();
		$form = $this->createForm(QuestionType::class, $question);


		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($question);
			$entityManager->flush();
			$formData = $request->request->get('question');
			$message = (new \Swift_Message($formData['title']. ' ' . $formData['email']))
				->setFrom('memoriestocata@gmail.com')
				->setTo('toropumihai@gmail.com')
				->setBody(
					$formData['content'],
					'text/plain'
				);
			$mailer->send($message);


			return $this->redirectToRoute('new_question');
		}

		return $this->render('ask_anything/index.html.twig', [
			'question' => $question,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @param QuestionRepository $questionRepository
	 * @Route("contact/list", name="contact_list", methods={"GET"})
	 * @return null|Response
	 */
	public function index(QuestionRepository $questionRepository): ?Response
	{
		return $this->render('ask_anything/list.html.twig', [
			'questions' => $questionRepository->findAll(),
		]);
	}
}
