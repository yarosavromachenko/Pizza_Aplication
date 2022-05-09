<?php

namespace App\Controller;

use App\Entity\Pizza;
use App\Form\PizzaFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PizzaController extends AbstractController
{

    #[Route('/', name: 'home')]
    /**
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        $data = $doctrine->getRepository(Pizza::class)->findAll();

        return $this->render('pizza/index.html.twig', [
            'pizza_list' => $data,
        ]);
    }


    #[Route('/create', name: 'create')]
    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {

        $pizza = new Pizza();

        $form = $this->createForm(PizzaFormType::class, $pizza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($pizza);
            $em->flush();

            $this->addFlash('notice', 'Pizza added successfully!');

            return $this->redirectToRoute('home');
        }

        return $this->render('pizza/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $pizza = $doctrine->getRepository(Pizza::class)->find($id);
        $form = $this->createForm(PizzaFormType::class, $pizza);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($pizza);
            $em->flush();

            $this->addFlash('notice', 'Update successfully!');

            return $this->redirectToRoute('home');
        }

        return $this->render('pizza/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    /**
     * @param ManagerRegistry $doctrine
     * @param $id
     * @return RedirectResponse
     */
    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        $data = $doctrine->getRepository(Pizza::class)->find($id);
        $em = $doctrine->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice', 'Delete successfully!');

        return $this->redirectToRoute('home');
    }


    public function totalPrice($data){

    }

}
