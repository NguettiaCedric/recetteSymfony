<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $ingredients = $paginator->paginate(
            // $repository->findAll(), /* Recupère tous les ingredients */
            $repository->findBy(['user' => $this->getUser()]), // Recupère les ingredients en fonction de l utilisateur
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit perpage*/
        );

        // $ingredients = $repository->findAll();
        // dd($ingredients);

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * Store d'un ingredient
     */
    #[Route('/ingredient/nouveau', 'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        /* http fondation pour recuperer les donnees envoye */
        $form->handleRequest($request);
        /* soumission du formulaire */
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            $ingredient = $form->getData();
            $ingredient->setUser($this->getUser()); //Permet de relier un user à un ingredient
            // dd($ingredient);

            $manager->persist($ingredient);
            $manager->flush();
            // dd($ingredient);

            $this->addFlash(
                'success',
                'Votre ingredient a été crée avec succès'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit d'un ingredient
     */
    /* #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(IngredientRepository $repository, int $id) : Response
    {   
        $ingredient = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(IngredientType::class, $ingredient);

        return $this->render('pages/ingredient/edit.html.twig', [
            'form'=> $form->createView() 
        ]);
    } */

    /* ou */
    #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager): Response
    {
        // dd($ingredient);
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            $ingredient = $form->getData();
            // dd($ingredient);

            $manager->persist($ingredient);
            $manager->flush();
            // dd($ingredient);

            $this->addFlash(
                'success',
                'Votre ingredient a été modifiée avec succès'
            );

            return $this->redirectToRoute('ingredient.index');
        }
        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * Delete function
     */

    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
    // public function delete(EntityManagerInterface $manager, Ingredient $ingredient) : Response
    public function delete(ingredient $ingredient, EntityManagerInterface $manager): Response
    {
        if (!$ingredient) {
            $this->addFlash(
                'warning',
                'L\'ingredient n\'a été trouvé'
            );
            return $this->redirectToRoute('ingredient.index');
        }
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingredient a été supprimé avec succès'
        );
        return $this->redirectToRoute('ingredient.index');
    }
}
