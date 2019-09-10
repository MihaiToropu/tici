<?php
/**
 * Created by PhpStorm.
 * User: torop
 * Date: 20-May-19
 * Time: 9:42 PM
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Video;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VideoFixtures extends BaseFixture implements DependentFixtureInterface
{
	private static $videoTitles = [
		'Data bases tutorial',
		'Tutorials for learning oop',
		'PHP tutorials for beginers',
	];

	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(200, 'main_videos', function ($count) use ($manager) {
			$video = new Video();
			$video->setTitle('Video ' . $this->faker->numberBetween(1, 999));
			$video->setAuthor($this->getRandomReference('main_users'));
			$video->setPublishedAt($this->faker->dateTimeBetween('-1 year', 'now'));
			$video->setVideoContent($this->faker->realText(2000));
			$video->setVideoPath('disel');
			$video->setImagePath('169');
			$tags = $this->getRandomReferences('main_tags', $this->faker->numberBetween(3, 5));

			foreach ($tags as $tag) {
				$video->addTag($tag);
			}

			return $video;
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
			TagFixture::class,
			UserFixture::class,
		];
	}
}