<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Cotation;
use App\Entity\SubSkill;
use App\Entity\Intership;
use App\Entity\Evaluation;
use App\Entity\SubSkillCotation;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class EvaluationController extends AbstractController
{

    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }


    /**
     * @Route("/evaluation/intership",name="viewEvaluationIntership")
     */
    public function getListIntership(): Response
    {
        $listIntership = $this->em->getRepository(Intership::class)->findAll();
        return $this->render('evaluation/listIntership.html.twig', [
            'interships' => $listIntership
        ]);
    }
    /**
     * @Route("/evaluation/intership/{id}", name="viewEvaluation")
     */
    public function listStudent(Intership $intership): Response
    {

        return $this->render('evaluation/index.html.twig', [

            'students' => $intership->getApplicationField()->getUsers(),
            'intership' => $intership
        ]);
    }


    /**
     * @Route("/evaluation/{id}/evaluer/{idIntership}",name="listEvaluation")
     * @ParamConverter("intership", options={"id" = "idIntership"})
     */
    public function listEvaluation(User $student, Intership $intership, Request $request): Response
    {
        $cotations = [];
        $cotations = $this->em->getRepository(Cotation::class)->findBy(['intership' => $intership, 'user' => $student]);
        if ($cotations == null) {
            $listSkill =  $this->em->getRepository(Skill::class)->findBy(array(), ['skillNumber' => 'ASC']);




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

            foreach ($listSkill as $skill) {
                $cotation = new Cotation();
                $cotation->setUser($student);
                $cotation->setSkill($skill);
                $cotation->setIntership($intership);
                foreach ($skill->getSubSkills() as $subskill) {
                    $subCotation = new SubSkillCotation();
                    $subCotation->setSubSkill($subskill);
                    $subCotation->setCotation(0);
                    $cotation->addSubskillcotation($subCotation);
                }
                $cotations->add($cotation);
                $this->em->persist($cotation);
            }
            $this->em->flush();
        }

        return $this->render('evaluation/createEvaluation.html.twig', [


            'student' => $student,
            'cotations' => $cotations
        ]);
    }

    /**
     * @Route("evaluation",name="evaluationTest")
     */
    public function test(Request $request)
    {
        $subCotation = $this->em->getRepository(SubSkillCotation::class)->findOneBy(['id' => $request->get('subSkillCotation')]);
        $subCotation->setCotation($request->get('cotation'));
        $this->em->flush();
        return new Response("test");
    }
}
