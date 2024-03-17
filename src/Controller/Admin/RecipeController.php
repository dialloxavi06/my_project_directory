<?php

namespace App\Controller\Admin;

use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipe;
use App\Form\RecipeType;

#[Route('/admin/recettes', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(RecipeRepository $repository): Response
    {
        $recipes = $repository->findAll();

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    // #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['slug' => '[^/]+', 'id' => '\d+'])]
    // public function show(string $slug, int $id, RecipeRepository $repository): Response
    // {
    //     $recipe = $repository->find($id);
    //     if($recipe->getSlug() !== $slug){
    //         return $this->redirectToRoute('recipe.show', [
    //             'id' => $recipe->getId(),
    //             'slug' => $recipe->getSlug()
    //         ], 301);
    //     } 

    //     return $this->render('recipe/show.html.twig', [
    //         'recipe' => $recipe
            
    //     ]);
      
    // }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'La recette a bien été modifiée');
            $em->persist($recipe);          
            $em->flush();
            return $this->redirectToRoute('admin.recipe.index', [
                'recipe' => $recipe,
                'form' => $form,
            ]);

        }


        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(), 
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Recipe $recipe, EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]

    public function create(Request $request, EntityManagerInterface $em)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
