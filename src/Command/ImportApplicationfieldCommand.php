<?php

namespace App\Command;

use App\Entity\ApplicationField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportApplicationfieldCommand extends Command
{
    //defaultName est le nom donné à la commande
    protected static $defaultName = 'app:import-applicationfield';
    private $kernel, $em;
    public function __construct(KernelInterface $kernel, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->kernel = $kernel;
        $this->em = $em;
    }
    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command');
        //   ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //io permet la lecture et l'écriture d'un fichier
        $io = new SymfonyStyle($input, $output);
        //       $arg1 = $input->getArgument('arg1');

        //permet de définir l'emplacement du fichier à lire
        $webPath = $this->kernel->getProjectDir() . '\public\json\application_field.json';
        //lit les données écrits dans le fichier
        $data = file_get_contents($webPath);
        //décripte les données au format JSon
        $applicationFields = json_decode($data);
        $applicationFieldsClone = json_decode($data);
        $applicationFieldsCloneParent = json_decode($data);

        //Crée une liste d'applicationField de type ApplicationField pour pouvoir ajouter à la db
        foreach ($applicationFields as $appli) {
            $applicationfield = new ApplicationField();
            $applicationfield->setCode($appli->code);
            $applicationfield->setName($appli->name);
            if ($appli->all_in == null) $applicationfield->setAllIn("");
            else $applicationfield->setAllIn($appli->all_in);
            $applicationfield->setIsActive($appli->is_active);
            $applicationfield->setType($appli->type);
            $this->em->persist($applicationfield);
        }
        $this->em->flush();

        $i = 0;
        $applicationFieldsDataBase = $this->em->getRepository(ApplicationField::class)->findAll();
        $parent = new ApplicationField();
        
        //cette boucle va déterminer la donnée parent
        foreach ($applicationFields as $appli) {
            foreach ($applicationFieldsClone as $app) {
                if ($app->id == $appli->parent_id && $appli->parent_id != null) {
                    $applications = $this->em->getRepository(ApplicationField::class)->findBy(["code" => $app->code, "name" => $app->name, "allIn" => $app->all_in]);
                    if (count($applications) > 1) {
                        foreach ($applicationFieldsCloneParent as $appParent) {
                            if ($appParent->id == $app->parent_id && $app->parent_id != null) {
                                $parent = $this->em->getRepository(ApplicationField::class)->findOneBy(["code" => $appParent->code, "name" => $appParent->name, "allIn" => $appParent->all_in]);
                            }
                            foreach ($applications as $application) {
                                if ($application->getParent() != null) {
                                    if ($application->getParent()->getCode() == $parent->getCode()) {

                                        $applicationFieldsDataBase[$i]->setParent($application);
                                    }
                                }
                            }
                        }
                    } else {
                        $application = $applications[0];
                        $applicationFieldsDataBase[$i]->setParent($application);
                    }
                }
            }
            $i++;
            $this->em->flush();
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        return Command::SUCCESS;
    }
}
