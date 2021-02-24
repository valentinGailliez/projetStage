<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\SubSkill;
use App\Form\SubSkillFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubSkillController extends AbstractController
{

    private $em;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }
    /**
     * @Route("/subskill/{id}", name="accueilSubSkill")
     */
    public function list(Skill $skill): Response
    {

        return $this->render('sub_skill/listSubSkill.html.twig', [
            'skill' => $skill
        ]);
    }

    /**
     * @Route("/subskill/{id}/create",name="createSubSkill")
     */
    public function create(Skill $skill, Request $request): Response
    {
        $subSkill = new SubSkill();

        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $subSkill->setSkill($skill);
            $this->em->persist($subSkill);
            $this->em->flush();
            return $this->redirectToRoute("accueilSubSkill", [
                'id' => $skill->getId()
            ]);
        }
        return $this->render('sub_skill/createSubSkill.html.twig', [
            'form' => $form->createView(),
            'skill' => $skill
        ]);
    }
    /**
     * @Route("/subskill/{id}/delete", name="deleteSubSkill")
     */
    public function delete(SubSkill $subSkill)
    {
        $this->em->remove($subSkill);
        $this->em->flush();

        return $this->redirectToRoute("accueilSubSkill", [
            'id' => $subSkill->getSkill()->getId()
        ]);
    }

    /**
     * @Route("/subskill/{id}/update",name="updateSubSkill")
     */
    public function update(SubSkill $subSkill, Request $request)
    {

        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $this->em->flush();
            return $this->redirectToRoute("accueilSubSkill", [
                'id' => $subSkill->getSkill()->getId()
            ]);
        }
        return $this->render('sub_skill/updateSubSkill.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
