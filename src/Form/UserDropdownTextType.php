<?php
/**
 * Created by PhpStorm.
 * User: torop
 * Date: 09-Jun-19
 * Time: 1:55 AM
 */

namespace App\Form;


use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use App\Form\DataTransformer\EmailToUser;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UserDropdownTextType extends AbstractType
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;
	/**
	 * @var RouterInterface
	 */
	private $router;

	public function __construct(UserRepository $userRepository, RouterInterface $router)
	{
		$this->userRepository = $userRepository;
		$this->router = $router;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addModelTransformer(new EmailToUser(
			$this->userRepository,
			$options['finder_callback']
		));
	}

	public function getParent()
	{
		return TextType::class;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'invalid_message' => 'Wrong email address',
			'finder_callback' => function (UserRepository $userRepository, string $email) {
				return $userRepository->findOneBy(['email' => $email]);
			},
			/*'attr' => [
				'class' => 'angolia-email-autocomplete',
				'data-autocomplete-url' => $this->router->generate('app_admin_api_users')
			]*/
		]);
	}

	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$attr = $view->vars['attr'];
		$class = isset($attr['class']) ? $attr['class'].' ' : '';
		$class .= 'angolia-email-autocomplete';

		$attr['class'] = $class;
		$attr['data-autocomplete-url'] = $this->router->generate('app_admin_api_users');
		$view->vars['attr'] = $attr;
	}


}