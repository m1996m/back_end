<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Form\AgendaType;
use App\Repository\AgendaRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Repository\UserRepository;

/**
 * @Route("/agenda")
 */
class AgendaController extends AbstractController
{
    /**
     * @Route("/", name="agenda_index", methods={"GET"})
     */
    public function index(AgendaRepository $agendaRepository): Response
    {
        $defaultcontext=[
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object,$format,$context){
                return 'Symfony 5';
            }
        ];      
        return $this->json($agendaRepository->findAll(),200,[],$defaultcontext);
    }

    /**
     * @Route("/new", name="agenda_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $agenda = new Agenda();
        $request=$request->getContent();
        $form=json_decode($request,true);
        $user=$userRepository->find($form['user']);
        $agenda->setNom($form['nom']);
        $agenda->setDate($form['date']);
        $agenda->setUser($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($agenda);
        $entityManager->flush();
        return $this->json('ok',200);
    }

    /**
     * @Route("/agenda/{id}", name="agenda_show", methods={"GET"})
     */
    public function show(Agenda $agenda): Response
    {
        $defaultcontext=[
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object,$format,$context){
                return 'Symfony 5';
            }
        ];      
        return $this->json($agenda,200,[],$defaultcontext);
    }

    /**
     * @Route("/user/{id}/edit", name="agenda_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Agenda $agenda,UserRepository $userRepository): Response
    {
        $defaultcontext=[
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER=>function($object,$format,$context){
                return 'Symfony 5';
            }
        ];  
        $request=$request->getContent();
        $form=json_decode($request,true);
        $user=$userRepository->find($form['user']);
        $agenda->setNom($form['nom']);
        $agenda->setDate($form['date']);
        $agenda->setUser($user);
        $this->getDoctrine()->getManager()->flush();
        return $this->json($agenda,200,[],$defaultcontext);
    }

    /**
     * @Route("/supp/delete/{id}", name="agenda_delete", methods={"POST"})
     */
    public function delete(Request $request, Agenda $agenda): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($agenda);
        $entityManager->flush();
        return $this->json("ok",200);
    }
}
