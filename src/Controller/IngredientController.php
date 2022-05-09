<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientFormType;
use App\Repository\IngredientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IngredientController extends AbstractController
{
    #[Route('/ingredients', name: 'ingredient_list')]
    /**
     * @param IngredientRepository $ingredientRepository
     * @return Response
     */
    public function index(IngredientRepository $ingredientRepository): Response
    {
        $data = $ingredientRepository->findAll();
        return $this->render('ingredients/index.html.twig', [
            'ingredient_list' => $data,
        ]);
    }


    #[Route('/ingredient/create', name: 'create_ingredient')]
    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    public function create(ManagerRegistry $doctrine,Request $request): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientFormType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($ingredient);
            $em->flush();

            $this->addFlash('notice','Ingredient added successfully!');

            return $this->redirectToRoute('ingredient_list');
        }

    return $this->render('ingredients/create.html.twig', [
            'form' => $form->createView(),
    ]);
    }

    #[Route('/ingredient/update/{id}', name: 'update_ingredient')]
    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(ManagerRegistry $doctrine,Request $request, $id): Response
    {
        $ingredient = $doctrine->getRepository(Ingredient::class)->find($id);
        $form = $this->createForm(IngredientFormType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($ingredient);
            $em->flush();

            $this->addFlash('notice','Update successfully!');

            return $this->redirectToRoute('ingredient_list');
        }

        return $this->render('ingredients/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ingredient/delete/{id}', name: 'delete_ingredient')]
    /**
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return Response
     */
    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        $data = $doctrine->getRepository(Ingredient::class)->find($id);
        $em = $doctrine->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice','Delete successfully!');

        return $this->redirectToRoute('ingredient_list');
    }


}
