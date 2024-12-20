<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',
                TextType::class, 
                array('attr' => array('class' => 'form-control'),
                'label' => 'Email',
                'required' => true
                ))
            ->add('password',
                PasswordType::class, 
                array('attr' => array('class' => 'form-control'),
                'label' => 'Password',
                'required' => true
                ))
            ->add('Submit',
                SubmitType::class, 
                array('attr' => array('class' => 'btn btn-primary'),
                'label' => 'Login'
                ))

        ;
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'user_value'
        ]);
    }
}
