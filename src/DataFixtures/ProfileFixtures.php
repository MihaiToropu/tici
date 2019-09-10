<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Self_;

class ProfileFixtures extends BaseFixture
{
	private $iter = 1;
	private $adminIter = 1;
	private $metaIter = 1;

	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(20, 'main_profiles', function () use ($manager) {
			$profile = new Profile();
			$profile->setAvatarPath('favicon_tici');
			$this->addReference("profile".$this->iter, $profile);
			++$this->iter;


			return $profile;
		});

		$this->createMany(2, 'main_admin_profiles', function () use ($manager) {
			$profile = new Profile();
			$profile->setAvatarPath('favicon_tici');
			$this->addReference("adminProfile".$this->adminIter, $profile);
			++$this->adminIter;


			return $profile;
		});

		$this->createMany(4, 'main_meta_profiles', function () use ($manager) {
			$profile = new Profile();
			$profile->setAvatarPath('favicon_tici');
			$this->addReference("metaProfile".$this->metaIter, $profile);
			++$this->metaIter;


			return $profile;
		});

		$manager->flush();
	}
}
