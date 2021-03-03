<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Cotation;
use App\Entity\SubSkill;
use App\Entity\Evaluation;
use App\Form\SkillFormType;
use App\Form\CotationFormType;
use App\Form\EvaluationFormType;
use App\Form\SubmitTypeFormType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @Route("/evaluation/{id}/evaluer",name="listEvaluation")
     */
    public function listEvaluation(User $student, Request $request): Response
    {

        $listSkill =  $this->em->getRepository(Skill::class)->findBy(array(), ['skillNumber' => 'ASC']);
        $cotation = new Cotation();


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


            'student' => $student,
            'skills' => $listSkill
        ]);
    }

    /**
     * @Route("/evaluation/{id}/{index}/{subIndex}",name="createEvaluation")
     */
    public function createEvaluation(User $student, Request $request): Response
    {

        $index = $request->get('index');
        $subIndex = $request->get('subIndex');
        $cotation = new Cotation();
        $listSkill =  $this->em->getRepository(Skill::class)->findBy(array(), ['skillNumber' => 'ASC']);


        $skill = $listSkill[$index];
        if (sizeof($skill->getSubSkills()) == 0) {
            array_splice($listSkill, array_search($skill, $listSkill), 1);
            return $this->redirectToRoute("createEvaluation", ['id' => $student->getId(), 'index' => $index++]);
        } else {
            $listSubSkill =  $this->em->getRepository(SubSkill::class)->findBy(['skill' => $skill->getId()], ['number' => 'ASC']);
            $subSkill = $listSubSkill[$subIndex];
        }

        $cotationfind = $this->em->getRepository(Cotation::class)->findOneBy(['user' => $student->getId(), 'subSkill' => $subSkill->getId()]);
        if ($cotationfind != null)
            $cotation = $cotationfind;



        $form = $this->createForm(CotationFormType::class, $cotation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $cotation->setUser($student);
            $cotation->setSubSkill($subSkill);
            if ($cotationfind == null)
                $this->em->persist($cotation);
            $this->em->flush();

            return $this->redirectToRoute("listEvaluation", [
                'id' => $student->getId()
            ]);
        }



        return $this->render('evaluation/evaluateStudent.html.twig', [

            'subskill' => $subSkill,
            'student' => $student,
            'form' => $form->createView(),
            'skills' => $listSkill
        ]);
    }

    /**
     * @Route("/evaluation/{id}",name="createUserEvaluation")
     */
    public function createUserEvaluation(User $student, Request $request): Response
    {
        $evaluation = new Evaluation();

        $cotations = $this->em->getRepository(Cotation::class)->findBy(['user' => $student->getId()]);

        array_multisort($cotations);
        $form = $this->createForm(EvaluationFormType::class, $evaluation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $dompdf = new Dompdf();
            //  On  ajoute le texte à afficher
            $dompdf->loadHtml('test');
            // On fait générer le pdf  à Dompdf ...
            $dompdf->render();
            //  et on l'affiche dans un   objet Response
            return new Response($dompdf->stream());
        }
        return $this->render('evaluation/generatepdf.html.twig', [
            'form' => $form->createView(),
            'cotations' => $cotations
        ]);
    }
}
