<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Entity\Classe;
use App\Form\BoatType;
use App\Form\ClasseType;
use App\Repository\BoatRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ClasseController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/classe', name: 'classe.index')]
    public function show(ClasseRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $classes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
        5
        );

        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('classe/edit/{id}', name: 'classe.edit', methods: ['GET', 'POST'])]
    public function new(int $id,
                        ClasseRepository $repository,
                        Request $request,
                        EntityManagerInterface $manager): Response
    {

        $classe = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $classe = $form->getData();
            $manager->persist($classe);
            $manager->flush();
            $this->addFlash('message', 'La classe a bien été modifiée !');

        }

        return $this->render('classe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/classe/add/', name: 'classe.add', methods: ['GET', 'POST'])]
    public function add(EntityManagerInterface $manager,
                         Request $request): Response{

        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $classe = $form->getData();
            $manager->persist($classe);
            $manager->flush();
            $this->addFlash('message', 'La classe a bien été ajoutée !');

        }

        return $this->render('classe/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[IsGranted('ROLE_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
    #[Route('classe/delete/{id}', name: 'classe.delete', methods: ['GET', 'POST'])]
    public function delete(ClasseRepository $repository,
                           int $id,
                           EntityManagerInterface $manager){
        $classe = $repository->findOneBy(['id' => $id]);
        $manager->remove($classe);
        $manager->flush();
        $this->addFlash('message', 'La classe a bien été supprimée !');
        
        return $this->redirectToRoute('classe.index');

    }

    #[IsGranted('ROLE_USER')]
    #[Route('classe/show/{id}', name: 'classe.show', methods: ['GET'])]
    public function showOne(ClasseRepository $repository,
                            int $id): Response
    {
        $classe = $repository->findOneBy(['id' => $id]);
        $boats = $classe->getBoats();
        // show all boats in this classe (id)
        $foundClasse = $repository->findBy(['wording' => $id]);

        return $this->render('classe/show.html.twig', [
            'classe' => $classe,
            'boats' => $boats,
            'foundClasse' => $foundClasse
        ]);
    }
}
