<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Model\RegistrationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('plainPassword', PasswordType::class, array(
            	/*'mapped' => false,
				'constraints' => [
					new Length([
						'minMessage' => 'Password is to short. You need atleast 6 letters()',
						'min' => 6
					]),
					new NotBlank([
						'message' => 'You need a password'
					])
				]*/
			))
			->add('agreeTerms', CheckboxType::class,[
				/*'mapped' => false,
				'constraints' => [
					new IsTrue([
						'message' => 'You have to agree the terms if you want to proceed!'
					])
				]*/
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RegistrationFormModel::class,
        ]);
    }
}
