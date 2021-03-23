<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Cotation;
use App\Entity\SubSkill;
use App\Entity\Intership;
use App\Entity\Evaluation;
use App\Form\IntershipFormType;
use App\Entity\SubSkillCotation;
use App\Form\SubmitTypeFormType;
use mikehaertl\wkhtmlto\Pdf as PDF;
use Knp\Snappy\Pdf as Snappy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        $students = [];
        $evaluations = $this->em->getRepository(Evaluation::class)->findAll();

        foreach ($intership->getApplicationField()->getUsers() as $student) {
            $added = 0;
            foreach ($evaluations as $evaluation) {
                foreach ($evaluation->getCotation() as $cotation) {
                    if ($student == $cotation->getUser() && $cotation->getIntership() == $intership && $evaluation->getDateCreation() != null) {
                        $added = 1;
                    }
                }
            }
            if ($added == 0) {
                array_push($students, $student);
            }
        }
        return $this->render('evaluation/index.html.twig', [

            'students' => $students,
            'intership' => $intership
        ]);
    }


    /**
     * @Route("/evaluation/{id}/evaluer/{idIntership}",name="listEvaluation")
     * @ParamConverter("intership", options={"id" = "idIntership"})
     */
    public function listSkills(User $student, Intership $intership, Request $request): Response
    {
        $cotations = [];
        $cotations = $this->em->getRepository(Cotation::class)->findBy(['intership' => $intership, 'user' => $student]);
        if ($cotations == null) {
            $listSkill = $intership->getSkills();
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
                array_push($cotations, $cotation);
                $this->em->persist($cotation);
            }
            $this->em->flush();
        }
        return $this->render('evaluation/createEvaluation.html.twig', [


            'student' => $student,
            'cotations' => $cotations,
        ]);
    }

    /**
     * @Route("/evaluation/cloture/{id}{idIntership}",name="endEvaluation")
     *  @ParamConverter("intership", options={"id" = "idIntership"})
     */
    public function endEvaluation(User $student, Intership $intership, Request $request, Snappy $snappy)
    {
        $form = $this->createForm(SubmitTypeFormType::class);
        $form->handleRequest($request);
        $cotations = $this->em->getRepository(Cotation::class)->findBy(['user' => $student->getId(), 'intership' => $intership->getId()]);
        if ($cotations == null) {
            $this->addFlash('danger', "Aucune évaluation n'est créée");

            return $this->redirectToRoute('viewEvaluation', ['id' => $intership->getId()]);
        }
        $nbCotationError = 0;
        foreach ($cotations as $cotation) {
            foreach ($cotation->getsubSkillcotation() as $subcotation) {
                if ($subcotation->getCotation() == 0) {
                    $nbCotationError++;
                }
            }
        }
        if ($nbCotationError != 0) {
            $this->addFlash('danger',  $nbCotationError . " sous-compétence(s) n'est/ne sont pas évaluée(s)");
            return $this->redirectToRoute('listEvaluation', ['id' => $student->getId(), 'idIntership' => $intership->getId()]);
        }

        $evaluationArchive = new Evaluation();
        $cotations = $this->em->getRepository(Cotation::class)->findBy(['user' => $student->getId(), 'intership' => $intership->getId()]);
        $evaluationArchive->setState("Modification");
        $evaluations = $this->em->getRepository(Evaluation::class)->findAll();

        foreach ($evaluations as $evaluation) {
            foreach ($evaluation->getCotation() as $cotationEvaluated) {
                if ($cotationEvaluated->getUser() == $student && $cotationEvaluated->getIntership() == $intership) {
                    if ($form->isSubmitted()) {
                        $html = $this->renderView('evaluation/evaluationStudent.html.twig', [
                            'student' => $student,
                            'cotations' => $cotations,
                            'evaluation' => $evaluation,
                        ]);
                        $snappy->setOption('header-left', 'Nom et prénom de l\'étudiant : ' . $student->getLastName() . ' ' . $student->getFirstName() . "\r\n" . "Réalisé à la HELHa");
                        $snappy->setOption('header-line', true);
                        $snappy->setOption('footer-center', 'Encodé par Mr/Mme Enseignant Enseignant');
                        $snappy->setOption('footer-line', true);
                        return new PdfResponse(
                            $snappy->getOutputFromHtml($html),
                            'evaluation_' . $student->getFirstName() . '_' . $student->getLastName() . '.pdf'
                        );
                    }
                    return $this->render('evaluation/generatePDF.html.twig', [
                        'student' => $student,
                        'cotations' => $cotations,
                        'evaluation' => $evaluation,
                        'form' => $form->createView()
                    ]);
                }
            }
        }

        foreach ($cotations as $cotation) {
            $evaluationArchive->addCotation($cotation);
        }

        $this->em->persist($evaluationArchive);
        $this->em->flush();

        return $this->render('evaluation/generatePDF.html.twig', [
            'student' => $student,
            'cotations' => $cotations,
            'evaluation' => $evaluationArchive,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("evaluation",name="evaluationSubSkill")
     */
    public function setCotation(Request $request)
    {
        $subCotation = $this->em->getRepository(SubSkillCotation::class)->findOneBy(['id' => $request->get('subSkillCotation')]);
        $subCotation->setCotation($request->get('cotation'));
        $this->em->flush();
        return new Response("test");
    }

    /**
     * @Route("evaluationComments",name="evaluationComments")
     */
    public function setComments(Request $request)
    {
        $cotation = $this->em->getRepository(Cotation::class)->findOneBy(['id' => $request->get('cotation')]);
        $cotation->setComments($request->get('comments'));
        $this->em->flush();
        return new Response("test");
    }


    /**
     * @Route("evaluationArchiveComments",name="evaluationGlobalComments")
     */
    public function setGlobalComments(Request $request)
    {
        $evaluation = $this->em->getRepository(Evaluation::class)->findOneBy(['id' => $request->get('evaluation')]);
        $evaluation->setComments($request->get('comments'));
        $this->em->flush();
        return new Response("test");
    }

    /**
     * @Route("evaluationArchiveSubject",name="evaluationGlobalSubjects")
     */
    public function setSubject(Request $request)
    {
        $evaluation = $this->em->getRepository(Evaluation::class)->findOneBy(['id' => $request->get('evaluation')]);
        $evaluation->setSubject($request->get('subject'));
        $this->em->flush();
        return new Response("test");
    }
}
