<?php

namespace App\Controller;

use App\Entity\ApplicationField;
use App\Entity\Skill;
use App\Entity\SubSkill;
use App\Form\SkillFormType;

use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class SkillController extends AbstractController
{
    private $em,$security;
    public function __construct( Security $security,EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        $this->security = $security;
    }

    /**
     * @Route("/skill",name="accueilSkill")
     */
    public function list(): Response
    {
        if($this->security->getUser()->getApplicationField() == null){

            $skills = $this->em->getRepository(Skill::class)->findAll();
        }
        else{
            $applicationParent = $this->security->getUser()->getApplicationField();
            while ($applicationParent->getType() != "category") {
                $applicationParent = $applicationParent->getParent();
            }
            $skills = $this->em->getRepository(Skill::class)->findBy(['domain'=>$applicationParent->getId()]);
        }
        return $this->render('skill/listSkill.html.twig', [
            'skills' => $skills
        ]);
    }

    /**
     * @Route("/skill/create",name="createSkill")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request): Response
    {
        $skill = new Skill();
        $domains = $this->em->getRepository(ApplicationField::class)->findBy(["type" => "category"]);
        //creation de formulaire
        $form = $this->createForm(SkillFormType::class, $skill);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $skills = $this->em->getRepository(Skill::class)->findOneBy(["domain"=>$skill->getDomain()->getId(),"skillNumber"=>$skill->getSkillNumber()]);
            if($skills == null){
                $this->em->persist($skill);
                $this->em->flush();
                $this->addFlash("success", "La compétence a bien été créée.");
                return $this->redirectToRoute("accueilSkill");
            }
            else{
                $this->addFlash("danger","Ce numéro de compétence est déjà attribué dans ce domaine");
            }
        }
        return $this->render('skill/createSkill.html.twig', [
            'form' => $form->createView(),
            'domains' => $domains
        ]);
    }
    /**
     * @Route("/skill/update/{id}",name="updateSkill")
     *  @IsGranted("ROLE_ADMIN")
     */
    public function update(Skill $skill, Request $request): Response
    {
        $form = $this->createForm(SkillFormType::class, $skill);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $skills = $this->em->getRepository(Skill::class)->findOneBy(["domain"=>$skill->getDomain()->getId(),"skillNumber"=>$skill->getSkillNumber()]);
            if($skills == null){

            $this->em->flush();
            $this->addFlash("success", "La compétence a bien été modifiée.");
            return $this->redirectToRoute("accueilSkill");
        }
        else{
            $this->addFlash("danger","Ce numéro de compétence est déjà attribué dans ce domaine . La compétence n'est pas modifiée");
        }
        }
        return $this->render('skill/updateSkill.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/skill/delete/{id}",name="deleteSkill")
     *  @IsGranted("ROLE_ADMIN")
     */
    public function delete(Skill $skill): Response
    {
        $this->em->remove($skill);
        $this->em->flush();
        $this->addFlash("success", "La compétence a bien été supprimée");
        return $this->redirectToRoute('accueilSkill');
    }
}
