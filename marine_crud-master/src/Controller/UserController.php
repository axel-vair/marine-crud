<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    #[Route('/user/edit/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(UserRepository $repository,
                         int $id,
                         EntityManagerInterface $manager,
                         Request $request,
                        ): Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }

        if($this->getUser()->getId() !== $id){
            return $this->redirectToRoute('app_home');
        }

        $user = $repository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('message', 'Les informations ont bien été modifiées !');
        }

        return $this->render('user/edit.html.twig', [
            '$user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
