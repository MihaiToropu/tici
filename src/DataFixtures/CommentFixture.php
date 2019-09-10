<?php

namespace App\DataFixtures;

use App\Entity\Video;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
	private $commentIter = 1;
	private $replyIter = 1;
    protected function loadData(ObjectManager $manager)
    {
    	$this->createMany(100, 'main_comments', function ($count) use ($manager) {
    		$comment = new Comment();
    		$comment->setContent(
    			$this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true)
			);

    		$comment->setUserName($this->faker->name);
    		$comment->setIsDeleted($this->faker->boolean(5));
    		$comment->setCreatedAt($this->faker->dateTimeBetween('-2 months', 'now'));

    		$comment->setVideo($this->getRandomReference('main_videos'));
    		$comment->setUser($this->getRandomReference('main_users'));

    		$this->addReference("comment".$this->commentIter, $comment);
    		++$this->commentIter;

    		return $comment;
		});

    	$manager->flush();

		$this->createMany(200, 'main_reply', function ($count) use ($manager) {
			$comment = new Comment();
			$comment->setContent(
				$this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true)
			);

			$comment->setUserName($this->faker->name);
			$comment->setIsDeleted($this->faker->boolean(5));
			$comment->setCreatedAt($this->faker->dateTimeBetween('-2 months', 'now'));

			$comment->setVideo($this->getRandomReference('main_videos'));
			$comment->setUser($this->getRandomReference('main_users'));
			/** @var Comment $parentId */
			$parentId = ($this->getReference("comment".$this->faker->numberBetween(1, $this->commentIter-1)));
			$comment->setParentId($parentId->getId());


			return $comment;
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
			VideoFixtures::class
		];
	}
}
