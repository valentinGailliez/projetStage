<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/utilisateur",name="userList")
     */
    public function list(): Response
    {
        $listUser =  $this->em->getRepository(User::class)->findBy(array(), ['type' => 'ASC', 'lastname' => 'ASC']);
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'students' => $listUser
        ]);
    }
}
