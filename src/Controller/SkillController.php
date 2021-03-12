<?php

namespace App\Controller;

use App\Entity\ApplicationField;
use App\Entity\Skill;
use App\Entity\SubSkill;
use App\Form\SkillFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class SkillController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/skill",name="accueilSkill")
     */
    public function list(): Response
    {
        $skills = $this->em->getRepository(Skill::class)->findAll();
        return $this->render('skill/listSkill.html.twig', [
            'skills' => $skills
        ]);
    }

    /**
     * @Route("/skill/create",name="createSkill")
     */
    public function create(Request $request): Response
    {
        $skill = new Skill();
        $domains = $this->em->getRepository(ApplicationField::class)->findBy(["type" => "category"]);
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
            'domains' => $domains
        ]);
    }
    /**
     * @Route("/skill/update/{id}",name="updateSkill")
     */
    public function update(Skill $skill, Request $request): Response
    {
        $form = $this->createForm(SkillFormType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->em->flush();
            $this->addFlash("success", "La compétence a bien été modifiée.");
            return $this->redirectToRoute("accueilSkill");
        }
        return $this->render('skill/updateSkill.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/skill/delete/{id}",name="deleteSkill")
     */
    public function delete(Skill $skill): Response
    {
        $this->em->remove($skill);
        $this->em->flush();
        $this->addFlash("success", "La compétence a bien été supprimée");
        return $this->redirectToRoute('accueilSkill');
    }
}
