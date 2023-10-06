<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Form\BoatType;
use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BoatController extends AbstractController
{
    #[IsGranted("ROLE_USER")]
    #[Route('/boat', name: 'boat.index')]
    public function show(BoatRepository $repository,
                         PaginatorInterface $paginator,
                         Request $request): Response
    {

        $boats = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('boat/index.html.twig', [
            'boats' => $boats,
        ]);
    }

    #[Route('boat/edit/{id}', name: 'boat.edit', methods: ['GET', 'POST'])]
    public function new(int $id,
                        BoatRepository $repository,
                        Request $request,
                        EntityManagerInterface $manager): Response
    {

        $boat = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(BoatType::class, $boat);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $boat = $form->getData();
            $manager->persist($boat);
            $manager->flush();
            $this->addFlash('message', 'La référence du navire a bien été modifiée !');

        }

        return $this->render('boat/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('boat/add', name: 'boat.add', methods: ['GET', 'POST'])]
    public function add(Request $request,
                        EntityManagerInterface $manager)
    {
        $boat = new Boat();
        $form = $this->createForm(BoatType::class, $boat);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $boat = $form->getData();
            $manager->persist($boat);
            $manager->flush();
            $this->addFlash('message', 'La référence du navire a bien été ajoutée !');

        }

        return $this->render('boat/add.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('boat/show/{id}', name: 'boat.show', methods: ['GET'])]
    public function showOne(BoatRepository $repository,
                            int $id): Response
    {
        $boat = $repository->findOneBy(['id' => $id]);

        return $this->render('boat/show.html.twig', [
            'boat' => $boat
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('boat/delete/{id}', name: 'boat.delete', methods: ['GET'])]
    public function delete(BoatRepository $repository,
                           int $id,
                           EntityManagerInterface $manager){
        $boat = $repository->findOneBy(['id' => $id]);
        $manager->remove($boat);
        $manager->flush();

        $this->addFlash('message', 'La référence du navire a bien été supprimée !');

        return $this->redirectToRoute('boat.index');
    }
}
