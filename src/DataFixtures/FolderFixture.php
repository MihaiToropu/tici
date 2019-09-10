<?php

namespace App\DataFixtures;

use App\Entity\Folder;
use App\Entity\Tutorial;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class FolderFixture extends BaseFixture implements DependentFixtureInterface
{
	private $iter = 1;

    private static $folders = [
    	'JavaScript Tutorial',
		'Mobile Development',
		'C# Tutorials',
		'Web Development',
		'Learn PHP',
		'Node.js in 3 steps',
		'How to FrontEnd',
		'Ruby ON',
		'React for begginers',
		'Database Administration',
		'Java Course',
		'ASP.NET MVC',
	];

	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(8, 'main_folders', function () use ($manager) {
			$folder = new Folder();
			$folder->setTitle('Course ' . $this->faker->numberBetween(1, 99));
			$folder->setCreatedAt($this->faker->dateTimeBetween('-3 years', '-2 years'));
			$folder->setImagePath('folder'.$this->iter);
			++$this->iter;
			$folder->setDescription($this->faker->realText(1200));

			$tutorials = $this->getRandomReferences('main_tutorials', $this->faker->numberBetween(5,10));

			foreach ($tutorials as $tutorial) {
				$folder->addTutorial($tutorial);
			}

			return $folder;
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
			TutorialFixture::class
		];
	}
}
