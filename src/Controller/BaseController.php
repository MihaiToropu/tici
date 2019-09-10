<?php
/**
 * Created by PhpStorm.
 * User: torop
 * Date: 19-May-19
 * Time: 11:43 PM
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
	protected function getUser(): ?User
	{
		return parent::getUser();
	}
}