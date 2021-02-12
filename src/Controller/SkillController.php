<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\SubSkill;
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
        $new = false;
        $listSkill = $this->em->getRepository(Skill::class)->findAll();
        if ($listSkill == null) {
            $new == true;
        }

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
            'form' => $form->createView(),
            'new' => $new
        ]);
    }

    public function updateSkill(Request $request): Response
    {

        $skill = $this->em->getRepository(Skill::class)->findOneBy(['id' => $request->get('id')]);

        $form = $this->createForm(SkillFormType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($skill);
            $this->em->flush();
            $this->addFlash("success", "La compétence a bien été créée.");
            return $this->redirectToRoute("accueilSkill");
        }
        return $this->render('skill/updateSkill.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    public function deleteSkill(Request $request): Response
    {
        $subskill = $this->em->getRepository(SubSkill::class)->findOneBy(['skill' => $request->get('id')]);
        $skill =  $this->em->getRepository(Skill::class)->findOneBy(['id' => $request->get('id')]);
        $this->em->remove($subskill);
        $this->em->remove($skill);
        $this->em->flush();
        return $this->redirectToRoute('accueilSkill');
    }
}