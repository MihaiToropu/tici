<?php

namespace App\DataFixtures;

use App\Entity\Tutorial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TutorialFixture extends BaseFixture implements DependentFixtureInterface
{

	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(50, 'main_tutorials', function () use ($manager){
			$tutorial = new Tutorial();
			$tutorial->setTitle('Tutorial ' . $this->faker->numberBetween(1, 200));
			$tutorial->setImagePath('favicon_tici');
			$tutorial->setDescription($this->faker->realText(200));
			$tutorial->setCreatedAt($this->faker->dateTimeBetween('-2 years', '-1 years'));

			$videos = $this->getRandomReferences('main_videos', $this->faker->numberBetween(1, 20));
			$categories = $this->getRandomReferences('main_categories', $this->faker->numberBetween(1,3));

			foreach ($videos as $video) {
				$tutorial->addVideo($video);
			}

			foreach ($categories as $category) {
				$tutorial->addCategory($category);
			}


			return $tutorial;
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
			VideoFixtures::class,
			CategoryFixtures::class,
		];
	}
}
