<?php

namespace App\Form;

use App\Entity\User;
//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('prenom',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('email',TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('password',PasswordType::class, array('attr' => array('class' => 'form-control')))
            ->add('tel',NumberType::class, array('attr' => array('class' => 'form-control'),'html5'=> true))
            ->add('role', ChoiceType::class, [
                'choices'  => [
                    'Normal User' => 'ROLE_USER',
                    'Coach' => 'ROLE_COACH',
                    'Owner' => 'ROLE_OWNER',
                ],
                'expanded' => false, // Use a dropdown (set to true for radio buttons)
                'multiple' => false, // Single selection
                'attr' => array('class' => 'form-control'), // Bootstrap styling
                'label' => 'User Role', // Field label
            ])
            ->add('Register',SubmitType::class, array('attr' => array('class' => 'btn btn-primary')))


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
