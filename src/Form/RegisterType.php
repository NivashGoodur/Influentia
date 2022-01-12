<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [

                    new NotBlank([
                        'message' => 'Merci de renseigner un email'
                    ]),
                    new Length([
                        'min' => 2, 
                        'minMessage' => 'L\'email doit contenir au moins {{ limit }} caractères',   
                        'max' => 100,   
                        'maxMessage' => 'L\'email doit contenir au maximum {{ limit }} caractères'  
                    ]),
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe']
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Nom',
                    ]),
                    new Length([
                        'min' => 2, 
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',   
                        'max' => 100,   
                        'maxMessage' => 'Le nom doit contenir au maximum {{ limit }} caractères',
                    ]),
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un prénom' 
                    ]),
                    new Length([
                        'min' => 2, 
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères', 
                        'max' => 100,   
                        'maxMessage' => 'Le prénom doit contenir au maximum {{ limit }} caractères' 
                    ]),
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
