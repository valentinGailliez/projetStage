<?php

namespace App\Controller;

use App\Entity\ApplicationField;
use App\Entity\Intership;
use App\Form\IntershipFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function getList(): Response
    {
        $listIntership = $this->em->getRepository(Intership::class)->findAll();
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
        $form = $this->createForm(IntershipFormType::class, $intership);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em->persist($intership);
            $this->em->flush();
        }
        return $this->render('intership/create.html.twig', [
            'form' => $form->createView(),
            'applis' => $applications
        ]);
    }
}
