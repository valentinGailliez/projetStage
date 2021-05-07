<?php

namespace App\Controller;

use Office365\SharePoint\File;
use Office365\SharePoint\ClientContext;
use Office365\Runtime\Auth\UserCredentials;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
private $kernel;

public function __construct(KernelInterface $kernel)
{
    $this->kernel = $kernel;
}

  
    /**
     * @Route("/error",name="ErrorPage")
     */
public function errorPage(){
    return $this->render('home/error.html.twig');
}



}
