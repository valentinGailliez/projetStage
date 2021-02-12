<?php

namespace App\Controller;

use DOMDocument;
use App\Entity\Skill;
use App\Entity\SubSkill;
use App\Form\SkillFormType;
use App\Form\SubSkillFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    /**
     * @Route("/test", name="test")
     */
    public function index(Request $request): Response
    {
        $repository = $this->getDoctrine()->getRepository(Skill::class);
        $listSkill = $repository->findAll();
        if ($listSkill == null) {
            return $this->createSkill($request);
        } else {
            return $this->getListSkill($listSkill);
        }
    }




    public function getListSkill($listSkill): Response
    {
        return $this->render('test/listSkill.html.twig', [
            'skills' => $listSkill
        ]);
    }

    public function createSkill(Request $request): Response
    {


        $skill = new Skill();
        $form = $this->createForm(SkillFormType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em->persist($skill);
            $this->em->flush();
            $this->addFlash("success", "La compétence a bien été créée.");
            return $this->redirectToRoute("accueilSkill");
        }
        return $this->render('test/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
