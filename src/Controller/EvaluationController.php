<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Skill;
use App\Entity\SubSkill;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EvaluationController extends AbstractController
{

    private $em, $listSkill, $listStudent;
    public function __construct(EntityManagerInterface $entityManager, SkillRepository $repository)
    {
        $this->em = $entityManager;
        $this->listStudent = $this->em->getRepository(User::class)->findBy(['type' => 'Etudiant']);
        $this->listSkill =  $this->em->getRepository(Skill::class)->findBy(array(), array('skillNumber' => 'ASC'));
    }


    /**
     * @Route("/evaluation", name="evaluation")
     */
    public function index(): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'controller_name' => 'EvaluationController',
            'students' => $this->listStudent
        ]);
    }

    public function createEvaluation(Request $request): Response
    {
        $student = $this->em->getRepository(User::class)->findOneBy(['id' => $request->get('idUser')]);

        foreach ($this->listSkill as $skill) {
            if (sizeof($skill->getSubSkills()) == 0) {
                array_splice($this->listSkill, array_search($skill, $this->listSkill), 1);
            } else {
                $listSubSkill =  $this->em->getRepository(SubSkill::class)->findBy(['skill' => $skill->getId()], array('number' => 'ASC'));
                foreach ($listSubSkill as $subSkill) {
                    $skill->removeSubSkill($subSkill);
                    $skill->addSubSkill($subSkill);
                }
            }
        }

        return $this->render('evaluation/createEvaluation.html.twig', [
            'student' => $student,
            'skills' => $this->listSkill
        ]);
    }
}
