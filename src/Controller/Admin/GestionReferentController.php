<?php

namespace App\Controller\Admin;

use App\Entity\Etudiant;
use App\Entity\InternalUser;
use App\Entity\Utilisateur;
use App\Form\AddingInternalUserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\AjoutEtudiantFormType;

class GestionReferentController extends AbstractController
{
    /**
     * @Route("/ajout/etudiant", name="ajout_etudiant")
     */
    public function ajout(Request $request): Response
    {
        $nomUtilisateur = $request->get('Utilisateur');

        $internalUser = new InternalUser();
        $role = $request->get('role');
        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);

        $form = $this->createForm(AddingInternalUserFormType::class, $internalUser);

        $utilisateurtrouve = null;
        $utilisateur = $repository->findOneBy([
            'roleUtilisateur' => $role,
            'nomUtilisateur' => $nomUtilisateur,
        ]);
        if ($utilisateur != null) {
            $utilisateurtrouve = new Utilisateur(
                $utilisateur->getNom(),
                $utilisateur->getPrenom(),
                $utilisateur->getEmail(),
                $utilisateur->getTelephone(),
                $utilisateur->getRole(),
                $utilisateur->getChargeMds(),
                $utilisateur->getMatricule(),
                $utilisateur->getMotDePasse()
            );
        }
        $form->handleRequest($request);

        $utilisateurtrouve->setId($utilisateur->getId());
        if ($form->isSubmitted()) {
            $this->addStudentToDataBase($internalUser, $repository);
        }
        return $this->render('admin/gestion_referent/ajoutReferent.html.twig', [
            'controller_name' => 'GestionEtudiantController',
            'utilisateur' => $utilisateurtrouve,
            'form' => $form->createView()
        ]);
    }

    public function import(Request $request)
    {
        $nomUtilisateur = $request->get('Utilisateur');

        $role = $request->get('role');
        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);


        $utilisateurtrouve = null;
        $utilisateur = $repository->findOneBy([
            'roleUtilisateur' => $role,
            'nomUtilisateur' => $nomUtilisateur,
        ]);
        if ($utilisateur != null) {
            $utilisateurtrouve = new Utilisateur(
                $utilisateur->getNom(),
                $utilisateur->getPrenom(),
                $utilisateur->getEmail(),
                $utilisateur->getTelephone(),
                $utilisateur->getRole(),
                $utilisateur->getChargeMds(),
                $utilisateur->getMatricule(),
                $utilisateur->getMotDePasse()
            );
        }
        $utilisateurtrouve->setId($utilisateur->getId());


        return $this->render('admin/gestion_etudiant/importEtudiant.html.twig', [
            'controller_name' => 'GestionEtudiantController',
            'utilisateur' => $utilisateurtrouve,

        ]);
    }

    public function ListeReferent(Request $request): Response
    {
        $nomUtilisateur = $request->get('Utilisateur');

        $role = $request->get('role');
        $repository = $this->getDoctrine()->getRepository(Utilisateur::class);


        $utilisateurtrouve = null;
        $utilisateur = $repository->findOneBy([
            'roleUtilisateur' => $role,
            'nomUtilisateur' => $nomUtilisateur,
        ]);
        if ($utilisateur != null) {
            $utilisateurtrouve = new Utilisateur(
                $utilisateur->getNom(),
                $utilisateur->getPrenom(),
                $utilisateur->getEmail(),
                $utilisateur->getTelephone(),
                $utilisateur->getRole(),
                $utilisateur->getChargeMds(),
                $utilisateur->getMatricule(),
                $utilisateur->getMotDePasse()
            );
        }

        $etudiants = $repository->findBy([
            'roleUtilisateur' => 'REFERENT'
        ]);
        $utilisateurtrouve->setId($utilisateur->getId());


        return $this->render('admin/gestion_referent/listeReferent.html.twig', [
            'controller_name' => 'GestionEtudiantController',
            'utilisateur' => $utilisateurtrouve,
            'etudiants' => $etudiants

        ]);
    }

    private function addStudentToDataBase($student, $repository)
    {

        $utilisateurcree = new Utilisateur(
            $student->getNom(),
            $student->getPrenom(),
            $student->getNom() . $student->getPrenom()[0] . "@helha.be",
            $student->getTelephone(),
            Utilisateur::$ListeRole[5],
            null,
            null,
            $student->getMotdepasse()
        );

        $studentList = $repository->findAll();


        if ($this->containsStudent($utilisateurcree, $studentList)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateurcree);
            $em->flush();
        }
    }

    private function containsStudent($student, $students): bool
    {
        foreach ($students as $studentInList) {
            $normalStudent = $studentInList;
            $normalStudent->setId(null);
            if ($normalStudent == $student) {
                return false;
            }
        }
        return true;
    }
}
