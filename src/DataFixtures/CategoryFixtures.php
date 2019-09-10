<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends BaseFixture
{

	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(10, 'main_categories', function () use ($manager){
			$category = new Category();
			$category->setName($this->faker->realText(30));
			$category->setCreatedAt($this->faker->dateTimeBetween('-2 years', '-1 years'));

			return $category;
		});

		$manager->flush();
	}
}
