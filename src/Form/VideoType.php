<?php
/**
 * Created by PhpStorm.
 * User: torop
 * Date: 07-Jun-19
 * Time: 12:30 PM
 */

namespace App\Form;

use App\Entity\User;
use App\Entity\Video;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


class VideoType extends AbstractType
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	public function __construct(UserRepository $userRepository)
	{

		$this->userRepository = $userRepository;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$article = $options['data'] ?? null;
		$isEdit = $article && $article->getId();

		$builder
			->add('title', TextType::class, [
				'help' => 'Here goes your video title'
			])
			->add('videoContent', null, [
				'rows' => 10
				//'attr' => ['rows' => 10]
			])
			->add('author', UserDropdownTextType::class, [
				'disabled' => $isEdit
			])
		;
		/*EntityType::class, array(
			'class' => User::class,
			'choice_label' => 'firstName',
			'placeholder' => 'choose an author',
			'choices' => $this->userRepository
				->findAllFirstNamesInOrder()*/
		if ($options['published_at']) {
			$builder->add('publishedAt', null, [
				'widget' => 'single_text'
			]);
		}

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Video::class,
			'published_at' => false,
		]);
	}
}