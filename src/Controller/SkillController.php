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
    private $em, $new, $listSkill, $skill;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->listSkill = $this->em->getRepository(Skill::class)->findAll();
        $this->new = false;
        $this->skill = new Skill();
    }

    public function getListSkill(Request $request): Response
    {
        if ($this->isEmptyList($this->listSkill)) {
            return $this->createSkill($request);
        } else {
            return $this->render('skill/listSkill.html.twig', [
                'skills' => $this->listSkill
            ]);
        }
    }


    public function createSkill(Request $request): Response
    {
        if ($this->listSkill == null) {
            $this->new == true;
        }


        $form = $this->createForms($this->skill, $request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->checkForm($this->skill)) {
                $this->em->persist($this->skill);
                $this->em->flush();
                $this->addFlash("success", "La compétence a bien été créée.");
                return $this->redirectToRoute("accueilSkill");
            }
        }
        return $this->render('skill/createSkill.html.twig', [
            'form' => $form->createView(),
            'new' => $this->new
        ]);
    }

    public function updateSkill(Request $request): Response
    {
        $id = $request->get('id');
        $skill = $this->em->getRepository(Skill::class)->findOneBy(['id' => $id]);

        $form = $this->createForms($skill, $request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->checkForm($skill)) {
                $this->em->flush();
                $this->addFlash("success", "La compétence a bien été modifiée.");
                return $this->redirectToRoute("accueilSkill");
            }
        }
        return $this->render('skill/updateSkill.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    public function deleteSkill(Request $request): Response
    {
        $id =  $request->get('id');
        $skill =  $this->em->getRepository(Skill::class)->findOneBy(['id' => $id]);
        $subskills = $this->em->getRepository(SubSkill::class)->findBy(['skill' => $skill->getId()]);

        if ($this->isEmptyList($subskills)) $this->em->remove($subskills);
        $this->em->remove($skill);
        $this->em->flush();
        $this->addFlash("success", "La compétence a bien été supprimée");
        return $this->redirectToRoute('accueilSkill');
    }


    private function checkForm($skill)
    {
        foreach ($this->listSkill as $elementSkill) {
            if ($elementSkill->getSkillNumber() == $skill->getSkillNumber() && $elementSkill->getSection() == $skill->getSection() && !($elementSkill->getId() == $skill->getId())) {

                $this->addFlash("danger", "Cette identifiant de compétence est déjà attribué pour cette section");
                return false;
            }
        }
        return true;
    }

    private function isEmptyList($list)
    {
        return $list == null;
    }

    private function createForms($item, $request)
    {
        $form = $this->createForm(SkillFormType::class, $item);

        $form->handleRequest($request);
        return $form;
    }
}
