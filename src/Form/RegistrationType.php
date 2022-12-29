<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('fullName' ,TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2', 
                'maxlength'=> '50',
            ],

            'label' => 'Nom / Prenoms',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],

            'constraints' =>  [
                new  Assert\Length(['min' => 2,'max' =>50]),
                new Assert\NotBlank()
            ],
        ])
        
        ->add('pseudo', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2', 
                'maxlength'=> '50',
            ],
            'required' => false,

            'label' => 'Pseudo (facultatif)',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],

            'constraints' =>  [
                new  Assert\Length(['min' => 2,'max' =>50]),
            ],
        ])
        ->add('email' ,EmailType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2', 
                'maxlength'=> '180',
            ],

            'label' => 'adresse email',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],

            'constraints' =>  [
                new  Assert\Length(['min' => 2,'max' =>180]),
                new Assert\Email(),
                new Assert\NotBlank()
            ],
        ])
        ->add('email' ,EmailType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '2', 
                'maxlength'=> '180',
            ],

            'label' => 'adresse email',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],

            'constraints' =>  [
                new  Assert\Length(['min' => 2,'max' =>180]),
                new Assert\Email(),
                new Assert\NotBlank()
            ],
        ])

        // ->add('plainPassword',RepeatedType::class, [
        //     'type' => PasswordType::class,
        //     'first_options' => [
        //         'label' => 'Mot de passe',
        //     ],
        //     'second_options second_options' => [
        //         'label' => 'confirmation du mot de passe'
        //     ],
        //     'invalid_messsage' => 'Les mots de passe de ne correspondent pas.'
        // ])


        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe de ne correspondent pas.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => [
                'attr' => [
                    'class' =>'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'label' => 'Mot de passe'
            ],
            'second_options' => [
                'attr' => [
                    'class' =>'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'label' => 'Confirmer le mot de passe'
            ],
        ])


        
        ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4 mb-4',
            ],
            'label' => 'S\'inscrire'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
