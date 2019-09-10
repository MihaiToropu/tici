<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserTutorialFixtures extends BaseFixture implements DependentFixtureInterface
{

	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(20, 'main_user_tutorials', function () use ($manager){
			/** @var User $user */
			$user = $this->getRandomReference('main_users');
			$tutorials = $this->getRandomReferences('main_tutorials', $this->faker->numberBetween(1,5));

			foreach ($tutorials as $tutorial) {
				$user->addWatching($tutorial);
			}

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
			UserFixture::class,
			TutorialFixture::class,
		];
	}
}
