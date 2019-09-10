<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class DeleteController extends AbstractController
{
    /**
     * @Route("/delete", name="delete")
	 * @IsGranted("ROLE_ADMIN_COMMENT")
     */
    public function index()
    {
        return $this->render('delete/index.html.twig', [
            'controller_name' => 'DeleteController',
        ]);
    }
}
