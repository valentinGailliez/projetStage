<?php

namespace App\Controller;

use DateTime;
use App\Entity\Skill;
use App\Entity\Intership;
use App\Form\IntershipFormType;
use App\Entity\ApplicationField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IntershipController extends AbstractController
{

    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    /**
     * @Route("/intership", name="intership")
     */
    public function getList(Request $request): Response
    {
        $listIntership = $this->em->getRepository(Intership::class)->findAll();


        if ($request->getMethod() == 'POST') {
            return $this->redirectToRoute('skillIntership');
        }
        return $this->render('intership/list.html.twig', [
            'interships' => $listIntership
        ]);
    }

    /**
     * @Route("/intership/create", name="createIntership")
     */
    public function create(Request $request): Response
    {
        $intership = new Intership();
        $applications = $this->em->getRepository(ApplicationField::class)->findAll();


        if ($request->getMethod() == 'POST') {
            $appli = $request->get('selectBloc');
            $firstday = new DateTime($request->get('first'));
            $lastday = new DateTime($request->get('end'));
            $ansco = $request->get('ansco');


            if ($firstday > $lastday) {
                $this->addFlash("danger", "Il y a une erreur dans vos dates");
            } else {
                if ($firstday != "" && $lastday != "" && $ansco != "") {
                    $this->addFlash("success", "Vous avez créé un stage.");
                    $application = $this->em->getRepository(ApplicationField::class)->findOneBy(["id" => $appli]);
                    $intership->setApplicationField($application);
                    $intership->setAnsco($ansco);
                    $intership->setFirstDay($firstday);
                    $intership->setLastDay($lastday);
                    $applicationParent = $application;
                    $codeEcts = "";
                    $codeEcts .= $applicationParent->getCode();
                    while ($applicationParent->getType() != "department") {
                        $applicationParent = $applicationParent->getParent();
                        $codeEcts .= $applicationParent->getCode();
                    }
                    $intership->setEctsCode($codeEcts);
                    $this->em->persist($intership);
                    $this->em->flush();
                } else $this->addFlash("danger", "Veuillez compléter toutes les données");
            }
        }

        return $this->render('intership/create.html.twig', [

            'applis' => $applications
        ]);
    }
    /**
     * @Route ("/intership/skill/{id}",name="skillIntership")
     */
    public function listSkill(Intership $intership, Request $request)
    {
        $applicationParent = $intership->getApplicationField();
        while ($applicationParent->getType() != "category") {
            $applicationParent = $applicationParent->getParent();
        }
        $skills = $this->em->getRepository(Skill::class)->findBy(['domain' => $applicationParent]);
        return $this->render('intership/setSkill.html.twig', [
            'skills' => $skills,
            'intership' => $intership
        ]);
    }


    /**
     * @Route("/intership/addSkill",name="setSkill")
     */
    public function setSkill(Request $request)
    {

        $skill = $this->em->getRepository(Skill::class)->findOneBy(['id' => $request->get('skill')]);
        $intership = $this->em->getRepository(Intership::class)->findOneBy(['id' => $request->get('intership')]);


        $intership->addSkill($skill);
        $this->em->flush();
        return new Response('test');
    }


    /**
     * @Route("/intership/deleteSkill",name="unsetSkill")
     */
    public function unsetSkill(Request $request)
    {

        $skill = $this->em->getRepository(Skill::class)->findOneBy(['id' => $request->get('skill')]);
        $intership = $this->em->getRepository(Intership::class)->findOneBy(['id' => $request->get('intership')]);


        $intership->removeSkill($skill);
        $this->em->flush();
        return new Response('test');
    }
}
