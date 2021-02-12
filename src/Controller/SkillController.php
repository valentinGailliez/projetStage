<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SkillController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function index(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Skill::class);
        $listSkill = $repository->findAll();
        if ($listSkill == null) {
            return $this->createSkill($request, true);
        } else {
            return $this->getListSkill($listSkill);
        }
    }




    public function getListSkill($listSkill): Response
    {
        return $this->render('skill/listSkill.html.twig', [
            'skills' => $listSkill
        ]);
    }

    public function createSkill(Request $request): Response
    {


        $skill = new Skill();
        $form = $this->createForm(SkillFormType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($skill);
            $this->em->flush();
            $this->addFlash("success", "La compétence a bien été créée.");
            return $this->redirectToRoute("accueilSkill");
        }
        return $this->render('skill/createSkill.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
