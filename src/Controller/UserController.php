<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/edit{id}/", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $defaultcontext=[
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object,$format,$context){
                return 'Symfony 5';
            }
        ]; 
        $request=$request->getContent();
        $form=json_decode($request,true);
        $user->setEmail($form['email']);
        $user->setNom($form['nom']);
        $user->setTel($form['tel']);
        $this->getDoctrine()->getManager()->flush();
        return $this->json($user,200,[],$defaultcontext);
    }
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $defaultcontext=[
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object,$format,$context){
                return 'Symfony 5';
            }
        ];      
        return $this->json($userRepository->findAll(),200,[],$defaultcontext);
    }

    /**
     * @Route("/{id}/", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        $defaultcontext=[
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object,$format,$context){
                return 'Symfony 5';
            }
        ]; 
        return $this->json($user,200,[],$defaultcontext);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $request=$request->getContent();
        $form=json_decode($request,true);
        $user->setEmail($form['email']);
        $user->setNom($form['nom']);
        $user->setTel($form['tel']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json('ok');
    }




    /**
     * @Route("/supp/delete/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user); 
        $entityManager->flush();
        return $this->redirectToRoute('ok');
    }
}
