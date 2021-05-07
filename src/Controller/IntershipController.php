<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Intership;
use App\Form\IntershipFormType;
use App\Entity\ApplicationField;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class IntershipController extends AbstractController
{

    private $em,$security;
    public function __construct(EntityManagerInterface $entityManager,Security $security)
    {
        $this->em = $entityManager;
        $this->security = $security;
    }

    /**
     * @Route("/intership/{id}",name="viewIntership")
     */
    public function intership(Intership $intership){
        return $this->render('intership/intership.html.twig',[
            'intership'=>$intership
        ]);
    }
    /**
     * @Route("/intership", name="intership")
     */
    public function getList(Request $request): Response
    {
        $date = new DateTime();
        
        if($date->format('m')<=8)
        $ansco = ''.($date->format('Y')-1).'-'.$date->format('Y');
        if($this->security->getUser()->getType()=="Administrateur"){
            $listIntership = $this->em->getRepository(Intership::class)->findAll();
        }
        else{
            
            $listIntership =$this->em->getRepository(Intership::class)->findBy(['ansco'=>$ansco,'applicationField'=>$this->security->getUser()->getApplicationField()]);
         
        }
        return $this->render('intership/list.html.twig', [
            'interships' => $listIntership,
            'date'=>$date
        ]);
    }

    /**
     * @Route("/intership/create", name="createIntership")
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request): Response
    {
        $intership = new Intership();
        $applications = $this->em->getRepository(ApplicationField::class)->findAll();
$date=new Datetime();

        if ($request->getMethod() == 'POST') {
            $appli = $request->get('selectBloc');
            $firstday = new DateTime($request->get('first'));
            $lastday = new DateTime($request->get('end'));
            
if($firstday< $date || $lastday <$date){
    $this->addFlash("danger","Vous avez choisi des dates antérieures à la date actuelle");
}
else{
            if ($firstday > $lastday) {
                $this->addFlash("danger", "Il y a une erreur dans vos dates");
            } else {
                if ($firstday != "" && $lastday != "") {
                    $this->addFlash("success", "Vous avez créé un stage.");
                    $application = $this->em->getRepository(ApplicationField::class)->findOneBy(["id" => $appli]);
                    $intership->setApplicationField($application);
                    if($firstday->format('m')<=9){
                        $firstansco = $firstday->format('Y')-1;
                        $lastansco = $firstday->format('Y');
                        $ansco = "{$firstansco}-{$lastansco}";
                    }
                    else{
                        $firstansco = $firstday->format('Y');
                        $lastansco = $firstday->format('Y')+1;
                        $ansco = "{$firstansco}-{$lastansco}";
                    }
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
                    return $this->redirectToRoute('intership');
                } else $this->addFlash("danger", "Veuillez compléter toutes les données");
            }
        }
    }

        return $this->render('intership/create.html.twig', [

            'applis' => $applications,
            "date"=>$date
        ]);
    }

/**
     * @Route("/intership/update/{id}", name="updateIntership")
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(Intership $intership,Request $request): Response
    {
        $date = new DateTime();
        $applications = $this->em->getRepository(ApplicationField::class)->findAll();


        if ($request->getMethod() == 'POST') {
            $appli = $request->get('selectBloc');
            $firstday = new DateTime($request->get('first'));
            $lastday = new DateTime($request->get('end'));
            

            if ($firstday > $lastday) {
                $this->addFlash("danger", "Il y a une erreur dans vos dates");
            } else {
                if ($firstday != "" && $lastday != "") {
                    $this->addFlash("success", "Vous avez créé un stage.");
                    $application = $this->em->getRepository(ApplicationField::class)->findOneBy(["id" => $appli]);
                    $intership->setApplicationField($application);
                    if($firstday->format('m')<9){
                        $firstansco = $firstday->format('Y')-1;
                        $lastansco = $firstday->format('Y');
                        $ansco = "{$firstansco}-{$lastansco}";
                    }
                    else{
                        $firstansco = $firstday->format('Y');
                        $lastansco = $firstday->format('Y')+1;
                        $ansco = "{$firstansco}-{$lastansco}";
                    }
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
                    $this->em->flush();
                } else $this->addFlash("danger", "Veuillez compléter toutes les données");
            }
        }

        return $this->render('intership/update.html.twig', [

            'applis' => $applications,
            'intership'=>$intership,
            'date'=>$date
        ]);
    }
/**
 * @Route("/intership/delete",name="deleteIntership")
     * @IsGranted("ROLE_ADMIN")
 */
public function delete(Request $request){
$intership = $this->em->getRepository(Intership::class)->findOneby(['id'=>$request->get('intership')]);
$this->em->remove($intership);
$this->em->flush();
}

    /**
     * @Route ("/intership/skill/{id}",name="skillIntership")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listSkill(Intership $intership, Request $request)
    {
        $applicationParent = $intership->getApplicationField();
        while ($applicationParent->getType() != "category") {
            $applicationParent = $applicationParent->getParent();
        }
        $skills = $this->em->getRepository(Skill::class)->findBy(['domain' => $applicationParent], ['skillNumber' => 'ASC']);
        return $this->render('intership/setSkill.html.twig', [
            'skills' => $skills,
            'intership' => $intership
        ]);
    }

/**
 * @Route ("/intership/referent/{id}",name="referentIntership")
     * @IsGranted("ROLE_ADMIN")
 */
public function listReferent(Intership $intership, Request $request){
    $applicationParent = $intership->getApplicationField();
    while ($applicationParent->getType() != "category") {
        $applicationParent = $applicationParent->getParent();
    }
    $referent = $this->em->getRepository(User::class)->findBy(['applicationField' => $applicationParent], ['lastname' => 'ASC']);
    return $this->render('intership/setReferent.html.twig', [
        'referents' => $referent,
        'intership' => $intership
    ]);
}


    /**
     * @Route("/intership/addSkill/{id}/{idIntership}",name="setSkill")
     * @ParamConverter("intership", options={"id" = "idIntership"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function setSkill(Skill $skill, Intership $intership)
    {
        $intership->addSkill($skill);
        $this->em->flush();
        return $this->redirectToRoute('skillIntership', ['id' => $intership->getId()]);
    }


    /**
     * @Route("/intership/deleteSkill/{id}/{idIntership}",name="unsetSkill")
     * @ParamConverter("intership", options={"id" = "idIntership"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function unsetSkill(Skill $skill, Intership $intership, Request $request)
    {
        $intership->removeSkill($skill);
        $this->em->flush();
        return $this->redirectToRoute('skillIntership', ['id' => $intership->getId()]);
    }

    
    /**
     * @Route("/intership/addReferent/{id}/{idIntership}",name="setReferent")
     * @ParamConverter("intership", options={"id" = "idIntership"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function setReferent(User $referent, Intership $intership)
    {
        $intership->addReferent($referent);
        $this->em->flush();
        return $this->redirectToRoute('referentIntership', ['id' => $intership->getId()]);
    }


    /**
     * @Route("/intership/deleteReferent/{id}/{idIntership}",name="unsetReferent")
     * @ParamConverter("intership", options={"id" = "idIntership"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function unsetReferent(User $referent, Intership $intership, Request $request)
    {
        $intership->removeReferent($referent);
        $this->em->flush();
        return $this->redirectToRoute('referentIntership', ['id' => $intership->getId()]);
    }
}
