<?php
namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('admin/category', name: 'admin.category.')]
class CategoryController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository)
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' =>  $categoryRepository->findAll()
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $category = new Category();
       $form = $this->createForm(CategoryType::class, $category);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $em->persist($category);
             $em->flush();
             $this->addFlash('success', 'la category a été ajouté avec succès');
             return $this->redirectToRoute('admin.category.index');
         }
        return $this->render('admin/category/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'la category a été modifié avec succès');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/edit.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/delete/{id}', name: 'delete', methods: ['GET', 'POST'])]
    public function delete(Category $category, EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'la category a été supprimé avec succès');
        return $this->redirectToRoute('admin.category.index');
    }
}