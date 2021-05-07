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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class SubSkillController extends AbstractController
{

    private $em;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }
    /**
     * @Route("/subskill/{id}", name="accueilSubSkill")
     */
    public function list(Skill $skill): Response
    {

        return $this->render('sub_skill/listSubSkill.html.twig', [
            'skill' => $skill
        ]);
    }

    /**
     * @Route("/subskill/{id}/create",name="createSubSkill")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Skill $skill, Request $request): Response
    {
        $subSkill = new SubSkill();

        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($this->em->getRepository(SubSkill::class)->findOneBy(['skill'=>$skill->getId(),'number'=>$subSkill->getNumber()])==null){
            $subSkill->setSkill($skill);
            $this->em->persist($subSkill);
            $this->em->flush();
            $this->addFlash("success", "La sous-compétence a bien été créée.");
                
            return $this->redirectToRoute("accueilSubSkill", [
                'id' => $skill->getId()
            ]);
            }
            else{
                $this->addFlash("danger","Ce numéro de sous-compétence est déjà attribué dans cette compétence");
            }
        }
        return $this->render('sub_skill/createSubSkill.html.twig', [
            'form' => $form->createView(),
            'skill' => $skill
        ]);
    }
    /**
     * @Route("/subskill/{id}/delete", name="deleteSubSkill")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(SubSkill $subSkill)
    {
        $this->em->remove($subSkill);
        $this->em->flush();

        return $this->redirectToRoute("accueilSubSkill", [
            'id' => $subSkill->getSkill()->getId()
        ]);
    }

    /**
     * @Route("/subskill/{id}/update",name="updateSubSkill")
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(SubSkill $subSkill, Request $request)
    {

        $form = $this->createForm(SubSkillFormType::class, $subSkill);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($this->em->getRepository(SubSkill::class)->findOneBy(['skill'=>$subSkill->getSkill()->getId(),'number'=>$subSkill->getNumber()])==null){
           
                $this->addFlash("success", "La sous-compétence a bien été modifiée.");
           
            $this->em->flush();
            return $this->redirectToRoute("accueilSubSkill", [
                'id' => $subSkill->getSkill()->getId()
            ]);
            }
            else{
                $this->addFlash("danger","Ce numéro de sous-compétence est déjà attribué dans ce domaine . La sous-compétence n'est pas modifiée");
            }
        }
        return $this->render('sub_skill/updateSubSkill.html.twig', [
            'form' => $form->createView(),
            'skill' => $subSkill->getSkill()
        ]);
    }
}
