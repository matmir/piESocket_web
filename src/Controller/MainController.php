<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class MainController extends AbstractController {
    
    /**
     * @Route("/", name="main_index")
     */
    public function index() {
        
        $scfg = Yaml::parseFile($this->getParameter('kernel.project_dir').'/config/sockets.yaml');
                
        return $this->render('main/index.html.twig', array(
            'scfg' => $scfg
        ));
    }
}
