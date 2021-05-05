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
     * @Route("/up", name="up")
     */
    public function index(): Response
    {


        try {
            $userName = "gestion-stage@helha.be";
            $password = "H€lh@!Ext21";
            $credentials = new UserCredentials($userName, $password);
            $ctx = (new ClientContext("https://helha.sharepoint.com/sites/gestion-stage"))->withCredentials($credentials);
            $filename = $this->kernel->getProjectDir()."\public\image\LOGO_HELHa_talents.jpg";
            $targetLibraryTitle = "Documents";
            $targetList = $ctx->getWeb()->getLists()->getByTitle($targetLibraryTitle);

                $uploadFile = $targetList->getRootFolder()->uploadFile(basename($filename),file_get_contents($filename));
                $ctx->executeQuery();
                print "File {$uploadFile->getServerRelativeUrl()} has been uploaded\r\n";

        }
        catch (Exception $e) {
            echo 'Error: ',  $e->getMessage(), "\n";
        }

        die();

    }

    /**
     * @return BinaryFileResponse
     * @Route("/down", name="down")
     */
    public function getFile()
    {
        $userName = "gestion-stage@helha.be";
        $password = "H€lh@!Ext21";

        $credentials = new UserCredentials($userName, $password);
        $ctx = (new ClientContext("https://helha.sharepoint.com/sites/gestion-stage"))->withCredentials($credentials);

        $sourceFileUrl = '/sites/gestion-stage/Documents%20partages/LOGO_HELHa_talents.jpg';
        $fileContent = \Office365\SharePoint\File::openBinary($ctx, $sourceFileUrl);
        $fileName = join(DIRECTORY_SEPARATOR,[sys_get_temp_dir(),"test.jpg"]);
        file_put_contents($fileName,$fileContent);

        if (!file_exists($fileName))
            throw $this->createNotFoundException();

        $response = new BinaryFileResponse($fileName);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,"test.jpg");

        return $response;


        die();
    }




}
