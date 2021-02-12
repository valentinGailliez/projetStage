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
        $listSubSkill = $this->em->getRepository(SubSkill::class)->findBy(['skill' => $request->get('id')]);
        if ($listSubSkill == null) {
            $isNew = true;
        } else {
            $isNew = false;
        }
        $skill = $this->em->getRepository(Skill::class)->findOneBy(['id' => $request->get('id')]);

        $subSkill = new SubSkill();

        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);
        $validator = true;
        if ($form->isSubmitted() && $form->isValid()) {
            $subSkill->setSkill($skill);
            foreach ($listSubSkill as $elementSubSkill) {
                if ($elementSubSkill->getNumber() == $subSkill->getNumber()) {
                    $validator = false;
                    $this->addFlash("danger", "Cette identifiant de sous-compétence est déjà attribué");
                }
            }
            if ($validator == true) {
                $this->em->persist($subSkill);
                $this->em->flush();
                return $this->redirectToRoute("accueilSubSkill", [
                    'id' => $request->get('id')
                ]);
            }
        }
        return $this->render('sub_skill/createSubSkill.html.twig', [
            'form' => $form->createView(),
            'skill' => $skill,
            'new' => $isNew
        ]);
    }

    public function deleteSubSkill(Request $request)
    {
        $subskill = $this->em->getRepository(SubSkill::class)->findOneBy(['id' => $request->get('idSubSkill')]);
        $this->em->remove($subskill);
        $this->em->flush();


        return $this->redirectToRoute("accueilSubSkill", [
            'id' => $request->get('id')

        ]);
    }

    public function updateSubSkill(Request $request)
    {
        $listSubSkill = $this->em->getRepository(SubSkill::class)->findBy(['skill' => $request->get('id')]);

        $subSkill = $this->em->getRepository(SubSkill::class)->findOneBy(['id' => $request->get('idSubSkill')]);
        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);
        $validator = true;
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($listSubSkill as $elementSubSkill) {
                if ($elementSubSkill->getNumber() == $subSkill->getNumber() && !($elementSubSkill->getId() == $subSkill->getId())) {
                    $validator = false;
                    $this->addFlash("danger", "Cette identifiant de sous-compétence est déjà attribué");
                }
            }
            if ($validator == true) {
                $this->em->flush();
                return $this->redirectToRoute("accueilSubSkill", [
                    'id' => $request->get('id')
                ]);
            }
        }
        return $this->render('sub_skill/updateSubSkill.html.twig', [
            'form' => $form->createView(),


        ]);
    }
}
