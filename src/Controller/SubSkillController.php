<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\SubSkill;
use App\Form\SubSkillFormType;
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

        $skill = $em->getRepository(Skill::class)->findOneBy(['id' => $id]);

        $subSkills = $em->getRepository(SubSkill::class)->findBy(['skill' => $id]);
        if ($subSkills == null) {
            return $this->createSubSkill($request, true);
        }
        return $this->render('sub_skill/listSubSkill.html.twig', [
            'subskills' => $subSkills,
            'skill' => $skill
        ]);
    }
    public function createSubSkill(Request $request, Bool $isNew): Response
    {
        if ($isNew == null) {
            $isNew = true;
        }
        $em = $this->getDoctrine()->getManager();
        $skill = $em->getRepository(Skill::class)->findOneBy(['id' => $request->get('id')]);

        $subSkill = new SubSkill();

        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $subSkill->setSkill($skill);

            $em->persist($subSkill);
            $em->flush();
            return $this->redirectToRoute("accueilSubSkill", [
                'id' => $request->get('id')
            ]);
        }
        return $this->render('sub_skill/createSubSkill.html.twig', [
            'form' => $form->createView(),
            'skill' => $skill,
            'new' => $isNew
        ]);
    }
}
