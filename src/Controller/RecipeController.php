<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Recipe;
use App\Form\RecipeType;

class RecipeController extends AbstractController
{

    #[Route('/recette', name: 'recipe.index')]
    public function index( Request $request, RecipeRepository $repository ): Response
    {
        $recipes = $repository->findAll();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['slug' => '[^/]+', 'id' => '\d+'])]
    public function show( Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {
        $recipe = $repository->find($id);
        if($recipe->getSlug() !== $slug){
            return $this->redirectToRoute('recipe.show', [
                'id' => $recipe->getId(),
                'slug' => $recipe->getSlug()
            ], 301);
        } 

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }
    
    #[Route('/recipe/create', name: 'recipe.create')]
    public function create(Request $request, EntityManager $em): Response
    {
    $recipe = new Recipe();
    
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $this->addFlash('success', 'Recette créée avec succès');
        $em->persist($recipe);
        $em->flush();
        return $this->redirectToRoute('recipe.index');
    }

        return $this->render('recipe/create.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/recipe/edit/{id}', name: 'recipe.edit')]
    public function edit(Request $request, EntityManager $em, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Recette modifiée avec succès');
            $em->flush();
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe
        ]);
    }


}