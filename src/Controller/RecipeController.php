<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recipe.index', methods:['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {   
        $recipes = $paginator->paginate(
            // $repository->findAll(), /* query NOT result */
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit perpage*/
        );
        // dd($recipes);
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

     /**
     * Store d'un recipe
     */

     #[Route('/recette/creation', 'recipe.new', methods: ['GET', 'POST'])]
     #[IsGranted('ROLE_USER')]
     public function new (
            Request $request,
            EntityManagerInterface $manager
        ): Response
     {   
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe); /* Integration du formulaire dans le controller */
 
        /* http fondetion pour recuperer les donnees envoye */
        $form->handleRequest($request); 
         /* soumission du formulaire */
          if($form->isSubmitted() && $form->isValid()) {
            //  dd($form->getData());
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());
 
            $manager->persist($recipe);
            $manager->flush();
            // dd($recipe);

            $this->addFlash(
                'success',
                'Votre recette a été crée avec succès'
            );

            return $this->redirectToRoute('recipe.index');
         } 
 
         return $this->render('pages/recipe/new.html.twig', [
             'form' => $form->createView()
         ]);
     }

    /**
     * Edition de recipe
     */

    #[Route('recette/edition/{id}', 'recipe.edit', methods:['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER') and user === recipe.getUser()")]
    public function edit(
        Recipe $recipe, Request $request,
        EntityManagerInterface $manager
    ): Response
    {
        // dd($recipe);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre reccette a été modifiée avec succès'
            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Delete function
     */
    #[Route('/recette/suppression/{id}', 'recipe.delete', methods:['GET'])]
    public function delete(
        Recipe $recipe,
        EntityManagerInterface $manager
    ): Response
    {
        if (!$recipe) {
            $this->addFlash(
                'warning',
                'La recette n\' a pas été trouvé'
            );
            return $this->redirectToRoute('recipe.index');
        }

        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre rectte a été supprimé avec succès'
        );
        return $this->redirectToRoute('recipe.index');
    }
     
}
