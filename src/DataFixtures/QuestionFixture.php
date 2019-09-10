<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionFixture extends BaseFixture
{
	protected function loadData(ObjectManager $manager)
	{
		$this->createMany(20, 'main_questions', function () use ($manager){
			$question = new Question();
			$question->setTitle($this->faker->title);
			$question->setContent($this->faker->realText(300));
			$question->setCreatedAt($this->faker->dateTimeBetween('-1 years', 'now'));
			$question->setEmail($this->faker->email);
			return $question;
		});

		$manager->flush();
	}
}
