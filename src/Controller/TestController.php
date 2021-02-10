<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\SubSkill;
use App\Form\SkillFormType;
use App\Form\SubSkillFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Skill::class);
        $listSkill = $repository->findAll();
        if ($listSkill == null) {
            return $this->createSkill($request);
        } else {
            return $this->getListSkill($listSkill);
        }
    }




    public function getListSkill($listSkill): Response
    {

        return $this->render('test/listSkill.html.twig', [
            'skills' => $listSkill
        ]);
    }

    public function createSkill(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $skill = new Skill();
        $form = $this->createForm(SkillFormType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($skill);
            $em->flush();
            $this->index($request);
        }
        return $this->render('test/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
