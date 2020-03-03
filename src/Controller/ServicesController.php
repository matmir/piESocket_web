<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Admin\SystemScripts;
use App\Service\Admin\ConfigGeneralMapper;
use App\Entity\AppException;

class ServicesController extends AbstractController {
    
    /**
     * Check if logged user is Admin
     * 
     * @param type $err Error array
     * @return bool True if admin
     */
    private function isAdmin(&$err): bool {
        
        $ret = false;
        
        if (!$this->isGranted('ROLE_ADMIN')) {
            $err = array(
                'state' => true,
                'msg' => 'user not logged in',
                'code' => 0
            );
        } else {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * @Route("/services/status", name="services_status")
     */
    public function servicesStatus(SystemScripts $scripts, ConfigGeneralMapper $cfgMapper) {
        
        $error = array(
            'state' => false,
            'msg' => 'none',
            'code' => 0
        );
        
        $services = array();
        
        $restart = false;
                    
        try {

            // Get service status
            $services = $scripts->getServiceStatus();
            
            // Server restart flag
            $restart = $cfgMapper->serverNeedRestart();

        } catch (AppException $ex) {

            $error['state'] = true;
            $error['msg'] = $ex->getMessage();
            $error['code'] = $ex->getCode();

        }
        
        return $this->json(array(
            'error' => $error,
            'services' => $services,
            'restart' => $restart
        ));
    }
    
    /**
     * @Route("/services/autoload/{flag}", name="services_autoload")
     */
    public function servicesAutoload(SystemScripts $scripts, $flag=0) {
        
        // Check parameters
        if ($flag > 1 || $flag <0) {
            $flag = 0;
        }
        
        $error = array(
            'state' => false,
            'msg' => 'none',
            'code' => 0
        );
        
        $cmdExecState = false;
        
        // Check if logged user is Admin
        if ($this->isAdmin($error)) {
            
            try {
            
                // Enable flag
                $en = ($flag==1)?(true):(false);
                
                // Set autoload
                $cmdExecState = $scripts->setServicesAutoload($en);

            } catch (AppException $ex) {

                $error['state'] = true;
                $error['msg'] = $ex->getMessage();
                $error['code'] = $ex->getCode();

            }
            
        }
        
        return $this->json(array(
            'error' => $error,
            'cmdExecState' => $cmdExecState
        ));
    }
    
    /**
     * @Route("/services/onh/{flag}", name="services_onh")
     */
    public function servicesOnhStart(SystemScripts $scripts, $flag=0) {
        
        // Check parameters
        if ($flag > 1 || $flag <0) {
            $flag = 0;
        }
        
        $error = array(
            'state' => false,
            'msg' => 'none',
            'code' => 0
        );
        
        $cmdExecState = false;
        
        // Check if logged user is Admin
        if ($this->isAdmin($error)) {
            
            try {
            
                // Start flag
                $start = ($flag==1)?(true):(false);
                
                // Start/Stop client
                $cmdExecState = $scripts->startONH($start);

            } catch (AppException $ex) {

                $error['state'] = true;
                $error['msg'] = $ex->getMessage();
                $error['code'] = $ex->getCode();

            }
            
        }
        
        return $this->json(array(
            'error' => $error,
            'cmdExecState' => $cmdExecState
        ));
    }
}
