<?php

namespace App\Controller;

use App\Entity\Cotation;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\SubSkill;
use App\Form\CotationFormType;
use App\Form\SubmitTypeFormType;
use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EvaluationController extends AbstractController
{

    private $em;
    public function __construct(EntityManagerInterface $entityManager, SkillRepository $repository)
    {
        $this->em = $entityManager;
    }


    /**
     * @Route("/evaluation", name="viewEvaluation")
     */
    public function listStudent(): Response
    {
        $listStudent = $this->em->getRepository(User::class)->findBy(['type' => 'Etudiant']);
        return $this->render('evaluation/index.html.twig', [
            'controller_name' => 'EvaluationController',
            'students' => $listStudent
        ]);
    }
    /**
     * @Route("/evaluation/{id}",name="createEvaluation")
     */
    public function createEvaluation(User $student): Response
    {
        $listSkill =  $this->em->getRepository(Skill::class)->findBy(array(), ['skillNumber' => 'ASC']);
        $form = $this->createForm(SubmitTypeFormType::class);


        foreach ($listSkill as $skill) {
            if (sizeof($skill->getSubSkills()) == 0) {
                array_splice($listSkill, array_search($skill, $listSkill), 1);
            } else {
                $listSubSkill =  $this->em->getRepository(SubSkill::class)->findBy(['skill' => $skill->getId()], ['number' => 'ASC']);
                foreach ($listSubSkill as $subSkill) {
                    $skill->removeSubSkill($subSkill);
                    $skill->addSubSkill($subSkill);
                }
            }
        }


        return $this->render('evaluation/createEvaluation.html.twig', [

            'skills' => $listSkill,
            'student' => $student,
            'form' => $form->createView()
        ]);
    }
}
