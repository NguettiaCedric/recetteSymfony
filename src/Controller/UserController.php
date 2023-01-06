<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\Migrations\Configuration\EntityManager\ManagerRegistryEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{   
    /**
     * Edition du profile de l'utilsateur
     */
    #[Security("is_granted('ROLE_USER') and user === userConnect")]
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods:['GET', 'POST'])]
    public function index(User $userConnect, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {     
        //Verification de l'utilisateur avant la modification
        // $this->getUser() recupreation de l'utilisateur connecté
        // dd($user);

        
        /* if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }  
        // dd($user, $this->getUser());
        
        //On verifier si l'utilisateur connecté est identique à celui en cour {id}
        if($this->getUser() !== $user){
            return $this->redirectToRoute('recipe.index');
        } */



        $form = $this->createForm(UserType::class, $userConnect);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //On vérifie ici si le mot de passe entré est il identique à celle de la BD
            if ($hasher->isPasswordValid($userConnect, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Les informations de vôtre compte on bien été modifier'
                );
        
                return $this->redirectToRoute('recipe.index');
            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect'
                );
            }

            
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(), 
        ]);
    }


    #[Security("is_granted('ROLE_USER') and user === userConnect")]
    #[Route('/utilisateur/edition-mot-de-passe/{id}', 'user.edit.password', methods:['GET', 'POST'])]
    public function editPassword(User $userConnect, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher)
    {      
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        // dd($form->getData());
        if ($form->isSubmitted() && $form->isValid()){
           
            if ($hasher->isPasswordValid($userConnect, $form->getData()['plainPassword'])) {
                $userConnect->setPassword(
                    // $form->getData()['newPassword']
                    $hasher->hashPassword(
                        $userConnect,
                        $form->getData()['newPassword']
                    )
                );

                // $user->setUpdateAt(new \DateTimeImmutable());
                // $user->setPlainPassword(
                //     $form->getData()['newPassword']
                // );

                $manager->persist($userConnect);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifier avec succès'
                );
        
                return $this->redirectToRoute('recipe.index');
            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
