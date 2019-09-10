<?php
/**
 * Created by PhpStorm.
 * User: torop
 * Date: 09-Jun-19
 * Time: 4:56 PM
 */

namespace App\Form\Model;


use App\Validator\UniqueUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationFormModel
{

	/**
	 *@Assert\NotBlank(message="You should have a mail in 2019")
	 *@Assert\Email(message="mails have @ in them")
	 * @UniqueUser()
	 */
	public $email;

	/**
	 * @Assert\NotBlank(message="You have to introduce a password")
	 * @Assert\Length(min=6, min="Password is to short. You need atleast 6 letters()")
	 */
	public $plainPassword;

	/**
	 * @Assert\IsTrue(message="You have to agree the terms if you want to proceed!")
	 */
	public $agreeTerms;
}