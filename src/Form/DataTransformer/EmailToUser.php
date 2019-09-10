<?php
/**
 * Created by PhpStorm.
 * User: torop
 * Date: 09-Jun-19
 * Time: 2:11 AM
 */

namespace App\Form\DataTransformer;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToUser implements DataTransformerInterface
{

	/**
	 * @var UserRepository
	 */
	private $userRepository;
	/**
	 * @var callable
	 */
	private $finderCallback;

	public function __construct(UserRepository $userRepository, callable $finderCallback)
	{
		$this->userRepository = $userRepository;
		$this->finderCallback = $finderCallback;
	}

	public function transform($value)
	{
		if (null === $value) {
			return '';
		}

		if (!$value instanceof User) {
			throw new \LogicException('You need User object for this process');
		}

		return $value->getEmail();
	}

	public function reverseTransform($value)
	{
		if (!$value) {
			return;
		}

		$callback = $this->finderCallback;
		$user = $callback($this->userRepository, $value);

		if (!$user) {
			throw new TransformationFailedException(sprintf(
				'There is not a user with %s email',
				$value
			));
		}

		return $user;
	}
}