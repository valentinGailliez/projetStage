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

    private $em, $isNew;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
        $this->isNew = true;
    }
    /**
     * @Route("/sub/skill", name="sub_skill")
     */
    public function getSubSkill(Request $request): Response
    {
        $id = $request->get("id");

        $skill = $this->em->getRepository(Skill::class)->findOneBy(['id' => $id]);

        $subSkills = $this->em->getRepository(SubSkill::class)->findBy(['skill' => $id]);
        if ($subSkills == null) {
            return $this->createSubSkill($request, true);
        }
        return $this->render('sub_skill/listSubSkill.html.twig', [
            'subskills' => $subSkills,
            'skill' => $skill
        ]);
    }
    public function createSubSkill(Request $request): Response
    {
        $id = $request->get("id");
        $subSkills = $this->em->getRepository(SubSkill::class)->findBy(['skill' => $id]);
        if ($subSkills != null) {
            $this->isNew = false;
        }
        $skill = $this->em->getRepository(Skill::class)->findOneBy(['id' => $id]);

        $subSkill = new SubSkill();

        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->checkForm($subSkills, $subSkill)) {
                $subSkill->setSkill($skill);
                $this->em->persist($subSkill);
                $this->em->flush();
                return $this->redirectToRoute("accueilSubSkill", [
                    'id' => $id
                ]);
            }
        }
        return $this->render('sub_skill/createSubSkill.html.twig', [
            'form' => $form->createView(),
            'skill' => $skill,
            'new' => $this->isNew
        ]);
    }

    public function deleteSubSkill(Request $request)
    {
        $id = $request->get("id");
        $subskill = $this->em->getRepository(SubSkill::class)->findOneBy(['id' => $id]);
        $this->em->remove($subskill);
        $this->em->flush();


        return $this->redirectToRoute("accueilSubSkill", [
            'id' => $request->get('id')
        ]);
    }

    public function updateSubSkill(Request $request)
    {
        $id = $request->get("id");

        $listSubSkill = $this->em->getRepository(SubSkill::class)->findBy(['skill' => $id]);

        $subSkill = $this->em->getRepository(SubSkill::class)->findOneBy(['id' => $request->get('idSubSkill')]);
        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->checkForm($listSubSkill, $subSkill)) {
                $this->em->flush();
                return $this->redirectToRoute("accueilSubSkill", [
                    'id' => $id
                ]);
            }
        }
        return $this->render('sub_skill/updateSubSkill.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function checkForm($list, $itemToCheck)
    {
        foreach ($list as $element) {
            if ($element->getNumber() == $itemToCheck->getNumber()) {
                $this->addFlash("danger", "Cette identifiant de sous-compétence est déjà attribué");
                return false;
            }
        }
        return true;
    }
}
