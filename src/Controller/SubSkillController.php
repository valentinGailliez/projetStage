<?php

namespace App\Controller;

use App\Entity\SubSkill;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubSkillController extends AbstractController
{
    /**
     * @Route("/sub/skill", name="sub_skill")
     */
    public function getSubSkill(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get("id");
        //$repository = $this->getDoctrine()->getRepository(SubSkill::class);
        //$subSkills = $repository->findBy(['skill' => $id]);
        $subSkills = $em->getRepository(SubSkill::class)->findBy(['skill' => $id]);
        if ($subSkills == null) {
            return $this->createSubSkill($request);
        }
        return $this->render('test/listSubSkill.html.twig');
    }
    public function createSubSkill(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $subSkill = new SubSkill();

        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($subSkill);
            $em->flush();
            $this->getSubSkill($request);
        }
        return $this->render('test/createSubSkill.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
