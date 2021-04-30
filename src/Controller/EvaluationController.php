<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Cotation;
use App\Entity\SubSkill;
use App\Entity\Intership;
use App\Entity\Evaluation;
use Knp\Snappy\Pdf as Snappy;
use App\Form\FileSendFormType;
use App\Entity\GlobalEvaluation;
use App\Entity\SubSkillCotation;
use App\Form\SubmitTypeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class EvaluationController extends AbstractController
{

    private $em,$mailer;
    public function __construct(EntityManagerInterface $entityManager,MailerInterface $mailer)
    {
        $this->em = $entityManager;
        $this->mailer=$mailer;
    }


    /**
     * @Route("/evaluation/intership",name="viewEvaluationIntership")
     * @IsGranted("ROLE_TEACHER")
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
                    if ($student == $cotation->getUser() && $cotation->getIntership() == $intership && $evaluation->getState() == "Fini") {
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
        $evaluations = $this->em->getRepository(Evaluation::class)->findBy(['typeEvaluation'=>'Evaluation']);
        $evaluationArchive = new Evaluation();
        foreach($evaluations as $evaluation){
            if(count($cotations) >=1){
            if($evaluation == $cotations[0]->getEvaluation()){
                
                return $this->render('evaluation/createEvaluation.html.twig', [
                    'evaluation'=>$evaluation
                ]);
            }
        }
        }
        if ($cotations == null) {
            $listSkill = $intership->getSkills();
            
            foreach ($listSkill as $skill) {
                if (sizeof($skill->getSubSkills()) == 0) {
                    array_splice($listSkill, array_search($skill, $listSkill), 1);
                }
            }
            $evaluationArchive->setState("Modification");
            $evaluationArchive->setTypeEvaluation("Evaluation");
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
                array_push($cotations,$cotation);
                $this->em->persist($cotation);
                $this->em->flush();
                
            }
            foreach ($cotations as $cotation) {
               $evaluationArchive->addCotation($cotation);
            }
    
            $this->em->persist($evaluationArchive);
            $this->em->flush();
        }
        return $this->render('evaluation/createEvaluation.html.twig', [
             'evaluation'=>$evaluationArchive
        ]);
    }

    /**
     * @Route("/evaluation/cloture/{id}/{idIntership}",name="endEvaluation")
     *  @ParamConverter("intership", options={"id" = "idIntership"})
     */
    public function endEvaluation(User $student, Intership $intership, Request $request, Snappy $snappy)
    {
        //Création d'un formulaire
        $form = $this->createForm(SubmitTypeFormType::class);
        $form->handleRequest($request);

        //Recherche des cotations en fonction du stage et de l'étudiant
        $cotations = $this->em->getRepository(Cotation::class)->findBy(['user' => $student->getId(), 'intership' => $intership->getId()]);

        /**
         *  Vérifier si il existe des cotations
         *  Si il n'existe pas de cotations, l'utilisateur sera renvoyé sur une autre page
        */
        if ($cotations == null) {
            $this->addFlash('danger', "Aucune évaluation n'est créée");
            return $this->redirectToRoute('viewEvaluation', ['id' => $intership->getId()]);
        }

        /**
         * Nous allons vérifier si toutes les cotations sont correctes
         * CAD vérifier si toutes les sous-compétences ont une côte
         * Les côtes définies sont NA--,NA-, A+ et A++
         */
        $nbCotationError = 0;
        foreach ($cotations as $cotation) {
            foreach ($cotation->getsubSkillcotation() as $subcotation) {
                if ($subcotation->getCotation() == 0) {
                    $nbCotationError++;
                }
            }
        }

        /**
         * Si il y a des erreurs, l'utilisateur sera redirigé vers la page d'évaluation de l'étudiant
         */
        if ($nbCotationError != 0) {
            $this->addFlash('danger',  $nbCotationError . " sous-compétence(s) n'est/ne sont pas évaluée(s)");
            return $this->redirectToRoute('listEvaluation', ['id' => $student->getId(), 'idIntership' => $intership->getId()]);
        }

        /**
         * Recherche les évaluations dans la Base de données
         */
        $evaluations = $this->em->getRepository(Evaluation::class)->findAll();
        foreach ($evaluations as $evaluation) {
            foreach ($evaluation->getCotation() as $cotationEvaluated) {
                if ($cotationEvaluated->getUser() == $student && $cotationEvaluated->getIntership() == $intership) {
                    if($evaluation->getPosition != ''){
                        if($intership->getFirstDay()<$evaluation->getDateCreation() && $intership->getLastDay()>$evaluation->getDateCreation()){
                            /**
                             * Lorsque le formulaire est validé
                             */
                            if ($form->isSubmitted()) {
                                $html = $this->renderView('evaluation/evaluationStudent.html.twig', [
                                    'evaluation' => $evaluation
                                ]);
                                $snappy->setOption('header-left',"Encodé par Mr/Mme Enseignant Enseignant");
                                $snappy->setOption('header-line', true);
                                $snappy->setOption('footer-line', true);
                                $snappy->setOption('footer-center','Page [page] of [topage]');
                                $evaluation->setState("Fini");
                                $listGlobalEvaluation = $this->em->getRepository(GlobalEvaluation::class)->findAll();
                                /**
                                 * Nous allons placer l'évaluation dans une liste d'évaluation qu'on nommera Evaluation-Globale
                                 */
                                if($listGlobalEvaluation == null){
                                    $globalEvaluation = new GlobalEvaluation();
                                    $globalEvaluation->setCreatedDate(new DateTime());
                                    $this->em->persist($globalEvaluation);
                                    $globalEvaluation->addEvaluation($evaluation);
                                }
                                else{
                                    if(sizeof($listGlobalEvaluation)==1){
                                        if($listGlobalEvaluation[0]->getEvaluations()[0]->getCotation()[0]->getIntership()->getAnsco() == $evaluation->getCotation()[0]->getIntership()->getAnsco()
                                            && $listGlobalEvaluation[0]->getEvaluations()[0]->getCotation()[0]->getUser() == $evaluation->getCotation()[0]->getUser()
                                        ){
                                            $listGlobalEvaluation->addEvaluation($evaluation);
                                        }
                                        else{
                                            $globalEvaluation = new GlobalEvaluation();
                                            
                                    $globalEvaluation->setCreatedDate(new DateTime());
                                            $this->em->persist($globalEvaluation);
                                            $globalEvaluation->addEvaluation($evaluation);
                                        }
                                    }
                                    else{
                                        $count = 0;
                                        foreach($listGlobalEvaluation as $globalEvaluation){
                                            if($globalEvaluation->getEvaluations()[0]->getCotation()[0]->getIntership()->getAnsco() == $evaluation->getCotation()[0]->getIntership()->getAnsco()
                                                && $globalEvaluation->getEvaluations()[0]->getCotation()[0]->getUser() == $evaluation->getCotation()[0]->getUser()
                                            ){
                                                $count++;
                                                $globalEvaluation->addEvaluation($evaluation);
                                            }
                                        }
                                        if($count == 0){
                                            $globalEvaluation = new GlobalEvaluation();
                                            
                                    $globalEvaluation->setCreatedDate(new DateTime());
                                            $this->em->persist($globalEvaluation);
                                            $globalEvaluation->addEvaluation($evaluation);
                                        }
                                    }
                                }

                                $this->em->flush();
                                $this->notify($evaluation);
                                return new PdfResponse(
                                    $snappy->getOutputFromHtml($html),
                                    'evaluation_' . $student->getFirstName() . '_' . $student->getLastName() . '.pdf'
                                );
                            }
                            return $this->render('evaluation/generatePDF.html.twig', [
                                'evaluation' => $evaluation,
                                'form' => $form->createView()
                            ]);
                        }
                        $this->addFlash('danger','La date de visite ne coincide pas avec l\'intervalle de stage');
                        return $this->redirectToRoute('viewEvaluation',['id'=>$intership->getId()]); 
                    }
                    else{
                        $this->addFlash('danger','Aucune position détaillée pour l\'évaluation');
                        return $this->redirectToRoute('viewEvaluation',['id'=>$intership->getId()]);
                    } 
                }
            }
        }
    }

    /**
     *  @Route("/evaluation/cloture/{id}",name="viewCloturedEvaluation")
     */
    public function viewFinishedEvaluation(Intership $intership){
        $evaluations = $this->em->getRepository(Evaluation::class)->findBy(["state"=>"Fini"]);
        foreach($evaluations as $evaluation){
            if($evaluation->getCotation()[0]->getIntership() != $intership){
                array_splice($evaluations, array_search($evaluation, $evaluations), 1);
            }
        }
        return $this->render('evaluation/listFinishedEvaluation.html.twig', [
            'evaluations' => $evaluations,
            
        ]);
    }

    /**
     * @Route("/evaluation/email/student/{id}",name="sendMailToStudent")
     */
    public function sendMailToStudent(Evaluation $evaluation){
        $email = (new TemplatedEmail())
            ->from('no-reply@helha.be')
            ->to($evaluation->getCotation()[0]->getUser()->getMail())
            ->subject('Evaluation de '.$evaluation->getCotation()[0]->getUser()->getLastName().' '.$evaluation->getCotation()[0]->getUser()->getFirstName().'')
            ->htmlTemplate('evaluation/mailStudent.html.twig')
            ->context([
            'evaluation' => $evaluation
            ]);

            $this->mailer->send($email);
        
        return $this->redirectToRoute('viewStudentEvaluation',['id'=>$evaluation->getId()]);
    }


    /**
     * @Route("/evaluation/{id}",name="viewStudentEvaluation")
     */
    public function viewStudentEvaluation(Evaluation $evaluation){
        return $this->render('evaluation/evaluateStudent.html.twig',[
            'evaluation'=>$evaluation
        ]);
    }

    /**
     * @Route("/evaluation/etudiant/intership",name="studentEvaluationIntership")
     */
    public function listEvaluationStudent():Response{

        $listIntership = $this->em->getRepository(Intership::class)->findAll();
        return $this->render('evaluation_student/listIntership.html.twig', [
            'interships' => $listIntership
        ]);
    }


    /**
     * @Route("/autoevaluation/{id}",name="autoEvaluation")
     *  
     */
    public function AutoEvaluation(Intership $intership){
        $cotations = [];
        $cotations = $this->em->getRepository(Cotation::class)->findBy(['intership' => $intership]);
        $evaluations = $this->em->getRepository(Evaluation::class)->findBy(['typeEvaluation'=>'Auto-Evaluation']);
        $evaluationArchive = new Evaluation();
        foreach($evaluations as $evaluation){
            if(count($cotations) >=1){
            if($evaluation == $cotations[0]->getEvaluation() && $evaluation->getTypeEvaluation()=='Auto-Evaluation'){
             
                return $this->render('evaluation_student/evaluation.html.twig', [
                    'evaluation'=>$evaluation
                ]);
            }
        }
        }
            $listSkill = $intership->getSkills();
            
            foreach ($listSkill as $skill) {
                if (sizeof($skill->getSubSkills()) == 0) {
                    array_splice($listSkill, array_search($skill, $listSkill), 1);
                } 
            }
            $cotations=[];
            $evaluationArchive->setState("Modification");
            $evaluationArchive->setTypeEvaluation("Auto-Evaluation");
            foreach ($listSkill as $skill) {
                $cotation = new Cotation();
                $cotation->setUser(null);
                $cotation->setSkill($skill);
                $cotation->setIntership($intership);
                foreach ($skill->getSubSkills() as $subskill) {
                    $subCotation = new SubSkillCotation();
                    $subCotation->setSubSkill($subskill);
                    $subCotation->setCotation(0);
                    $cotation->addSubskillcotation($subCotation);
                }
                array_push($cotations,$cotation);
                $this->em->persist($cotation);
                $this->em->flush();
            }
            foreach ($cotations as $cotation) {
               $evaluationArchive->addCotation($cotation);
            }
    
            $this->em->persist($evaluationArchive);
            $this->em->flush();
        return $this->render('evaluation_student/evaluation.html.twig', [
             'evaluation'=>$evaluationArchive
        ]);
    }
    /**
     * @Route("/evaluation/etudiant/{id}",name="sendDocument")
     */
    public function sendDocument(Intership $intership,Request $request){
        $form = $this->createForm(FileSendFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            
            dd($request->files->get('file_send_form'));
        }
        return $this->render('evaluation_student/sendDocument.html.twig',[
            'intership'=>$intership,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/evaluation/consultation/{id}",name="ConsultationEvaluation")
     */
    public function consultationEvaluation(Evaluation $evaluation){
        return $this->render('evaluation/evaluateStudent.html.twig', [
            'evaluation'=>$evaluation
       ]);
    }

    /**
     * @Route("/evaluation/referent/intership",name="viewReferentEvaluation")
     */
    public function viewReferentIntership():Response{
        $listIntership = $this->em->getRepository(Intership::class)->findAll();
        $interships = [];
        foreach($listIntership as $intership){
            if(sizeof($interships) == 0){
                
                array_push($interships,$intership);
            }
            else{
                foreach($interships as $intershipListing){
                    if($intershipListing->getAnsco() != $intership->getAnsco()){
                       
                array_push($interships,$intership);
                    }
                }
            }
        }

        return $this->render('evaluation_referent/listIntership.html.twig',[
            'interships'=>$interships
        ]);
    }


/**
     * @Route("/evaluation/referent/intership/{id}", name="viewReferentStudentEvaluation")
     */
    public function listReferentStudent(Intership $intership): Response
    {
        $listGlobalEvaluations = $this->em->getRepository(GlobalEvaluation::class)->findAll();
        if(sizeof($listGlobalEvaluations)==1){
            if($listGlobalEvaluations[0]->getEvaluations()[0]->getCotation()[0]->getIntership()->getAnsco() != $intership->getAnsco()){
                $listGlobalEvaluations == null;
            }
        }
        foreach ($listGlobalEvaluations as $globalEvaluation) {
            if($globalEvaluation->getEvaluations()[0]->getCotation()[0]->getIntership()->getAnsco() != $intership->getAnsco()
        ){
            array_splice($listGlobalEvaluations,array_search($globalEvaluation,$listGlobalEvaluations),0);
        }
        }
        return $this->render('evaluation_referent/index.html.twig', [

            'listGlobalEvaluation'=>$listGlobalEvaluations
        ]);
    }



    //Les méthodes suivantes ne renvoient pas de page. Elles agissent lors d'une interaction avec la page

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
     * @Route("position",name="setPosition")
     */
    public function setPosition(Request $request){
        $evaluation = $this->em->getRepository(Evaluation::class)->findOneBy(['id'=>$request->get('evaluation')]);
        $evaluation->setPosition($request->get('position'));
        $this->em->flush();
        return new Response("test");
    }
    /**
     * @Route("evaluationNote",name="setNote")
     */
    public function setNote(Request $request){
        $evaluation = $this->em->getRepository(Evaluation::class)->findOneBy(['id' => $request->get('evaluation')]);
        $evaluation->setNote($request->get('comments'));
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

    /**
     * @Route("evaluationArchiveDate",name="evaluationGlobalDate")
     */
    public function setDate(Request $request)
    {
        $evaluation = $this->em->getRepository(Evaluation::class)->findOneBy(['id' => $request->get('evaluation')]);
        $evaluation->setDateCreation(new DateTime($request->get('date')));
        $this->em->flush();
        return new Response("test");
    }

    public function notify($evaluation){
        foreach($evaluation->getCotation()[0]->getIntership()->getReferents() as $referent){
            $email = (new TemplatedEmail())
            ->from('no-reply@helha.be')
            ->to($referent->getMail())
            ->subject('Evaluation de '.$evaluation->getCotation()[0]->getUser()->getLastName().' '.$evaluation->getCotation()[0]->getUser()->getFirstName().'')
            ->htmlTemplate('evaluation/evaluationStudent.html.twig')
            ->context([
            'evaluation' => $evaluation
            ]);

            $this->mailer->send($email);
        }

    }
}
