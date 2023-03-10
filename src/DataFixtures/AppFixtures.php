<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{   

    /**
     * Faker generator variable de generateur
     */
    // private UserPasswordHasherInterface $hasher;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    
    public function load(ObjectManager $manager): void
    {    
        // $product = new Product();
        // $manager->persist($product);

        // Users
        $users = [];
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user->setFullName($this->faker->name())
                 ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                 ->setEmail($this->faker->email())
                 ->setRoles(['ROLE_USER'])
                 ->setPlainPassword('password');

                //  ->setPassword($this->hasher->hashPassword(
                //     $user,
                //     'password'
                //  ));
            
            // $hashPassword = $this->hasher->hashPassword(
            //     $user,
            //     'password'
            // );
            

            $users[]= $user;
            // $user->setPassword($hashPassword);

            $manager->persist($user);
        }

        //Ingredient
        $ingredients = [];
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            // $ingredient->setName('Ingredient' .$i)
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100))
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        //Recipes
        for ($j=0; $j < 25 ; $j++) { 
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
                ->setNBPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                // ->setDescription('lorem ispun' .$i)
                ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
                ->setIsPublic(mt_rand(0, 1) == 1 ? true : false);
            
            for ($k=0; $k < mt_rand(5, 15); $k++) { 
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            } 
            $manager->persist($recipe);
        }
        
        $manager->flush();
    }
}
