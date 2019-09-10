<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyFixtures extends BaseFixture
{

	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(4, 'main_companies', function () use ($manager) {
			$company = new Company();
			$company->setName('Company'. $this->faker->numberBetween(1,1000));

			return $company;
		});

		$manager->flush();
	}
}
