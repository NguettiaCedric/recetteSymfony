<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Webmozart\Assert\Assert as AssertAssert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2', 
                    'maxlength'=> '50',
                ],

                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],

                'constraints' =>  [
                    new  Assert\Length(['min' => 2,'max' =>50]),
                    new Assert\NotBlank()
                ],
            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1440
                ],
                'required'=> false,
                'label' => 'Temps (en minutes)',

                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('nbPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 50
                ],

                'label' => 'Nombre de personnes',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],

                'constraints' =>  [
                    new Assert\Positive(),
                    new Assert\LessThan(51)

                ],
                'constraints' =>  [
                    new  Assert\Positive(),
                    new Assert\LessThan(1441)
                ],
            ])
            ->add('difficulty' ,RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5
                ],
                'required' => false,
                'label' => 'Difficulté (en minutes)',

                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' =>  [
                    new  Assert\Positive(),
                    new Assert\LessThan(5)
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Description',

                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' =>  [
                    new  Assert\NotBlank()
                ],
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],

                'constraints' =>  [
                    new Assert\Positive(),
                    new Assert\LessThan(1001)

                ],
                'constraints' =>  [
                    new  Assert\Positive(),
                    new Assert\LessThan(1441)
                ],
            ])
            ->add('isFavorite', CheckboxType::class, [
                'label' => 'Favoris ?',
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
                'label_attr' => [
                    'class' => 'form-ckeck-label'
                ],
                

                'constraints' =>  [
                    new Assert\NotNull(),
                ],
            ] )
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $res) {
                    return $res->createQueryBuilder('i')
                            ->orderby('i.name', 'DESC');
                },
                'label' => 'Les ingredients',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            // ->add('submit', SubmitType::class)

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Créer une recette'
            ])
        ;   
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
