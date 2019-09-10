<?php
/**
 * Created by PhpStorm.
 * User: torop
 * Date: 09-Jun-19
 * Time: 1:42 PM
 */

namespace App\Form\TypeExtensions;


use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method iterable getExtendedTypes()
 */
class TextareaSizeExtension implements FormTypeExtensionInterface
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	}

	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['attr']['rows'] = $options['rows'];
	}

	public function finishView(FormView $view, FormInterface $form, array $options)
	{
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'rows' => 15
		]);
	}

	public function getExtendedType()
	{
		return TextareaType::class;
	}

	public function __call($name, $arguments)
	{
	}
}