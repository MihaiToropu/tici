<?php

namespace App\DataFixtures;

use App\Entity\TokenApi;
use App\Entity\User;
use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixture extends BaseFixture implements DependentFixtureInterface
{
	private $iter = 1;
	private $adminIter = 1;
	private $metaIter = 1;

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(20, 'main_users', function($i) use ($manager){
			$user = new User();
			$user->setEmail(sprintf('userdot%d@mail.com', $i));
			$user->setFirstName($this->faker->firstName);
			$user->setHasCompany(false);
			$user->agreeTerms();
			/*if ($this->faker->boolean) {
				$user->setFacebookUsername($this->faker->userName);
			}*/
			$user->setCreatedAt($this->faker->dateTimeBetween('-3 years', '-1 years'));
			$user->setPassword($this->passwordEncoder->encodePassword(
				$user,
				'engage'
			));

			$tokenApi = new TokenApi($user);
			$tokenApi1 = new TokenApi($user);
			$manager->persist($tokenApi);
			$manager->persist($tokenApi1);

			/** @var Profile $profile */
			$profile = $this->getReference("profile".$this->iter);
			$user->setUserProfile($profile);

			++$this->iter;

			return $user;
		});

		$this->createMany(2, 'main_admin_users', function($i) {
			$user = new User();
			$user->setEmail(sprintf('admin%d@mail.com', $i));
			$user->setFirstName($this->faker->firstName);
			$user->setRoles(['ROLE_ADMIN']);
			$user->agreeTerms();
			$user->setHasCompany(false);
			$user->setPassword($this->passwordEncoder->encodePassword(
				$user,
				'engage'
			));
			$user->setCreatedAt($this->faker->dateTimeBetween('-3 years', '-1 years'));

			/** @var Profile $profile */
			$profile = $this->getReference("adminProfile".$this->adminIter);
			$user->setUserProfile($profile);

			++$this->adminIter;

			return $user;
		});

		$this->createMany(4, 'main_meta_users', function($i) {
			$user = new User();
			$user->setEmail(sprintf('meta%d@mail.com', $i));
			$user->setFirstName($this->faker->firstName);
			$user->setRoles(['ROLE_META']);
			$user->agreeTerms();
			$user->setHasCompany(false);
			$user->setPassword($this->passwordEncoder->encodePassword(
				$user,
				'engage'
			));
			$user->setCreatedAt($this->faker->dateTimeBetween('-3 years', '-1 years'));

			/** @var Profile $profile */
			$profile = $this->getReference("metaProfile".$this->metaIter);
			$user->setUserProfile($profile);

			++$this->metaIter;

			return $user;
		});

		$manager->flush();
	}

	/**
	 * This method must return an array of fixtures classes
	 * on which the implementing class depends on
	 *
	 * @return array
	 */
	public function getDependencies()
	{
		return [
			ProfileFixtures::class,
		];
	}
}
