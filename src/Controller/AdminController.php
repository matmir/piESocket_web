<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;

use App\Form\Admin\ConfigGeneralForm;
use App\Form\Admin\ConfigDriverModbusForm;
use App\Form\Admin\ConfigDriverSHMForm;
use App\Service\Admin\ConfigGeneralMapper;
use App\Service\Admin\ConfigDriverMapper;
use App\Service\Admin\SystemScripts;

class AdminController extends AbstractController {
    
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(SystemScripts $scripts, ConfigGeneralMapper $cfgMapper) {
        
        // Get service status
        $services = $scripts->getServiceStatus();
        
        // Get restart flag
        $restart = $cfgMapper->serverNeedRestart();
        
        return $this->render('admin/index.html.twig', array(
            'services' => $services,
            'restart' => $restart
        ));
    }
    
    /**
     * @Route("/admin/config", name="admin_config_general")
     */
    public function configGeneral(ConfigGeneralMapper $cfgMapper, Request $request) {
        
        $cfg = $cfgMapper->getConfig();
        
        $form = $this->createForm(ConfigGeneralForm::class, $cfg);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $cfg = $form->getData();
            
            // Write data to the DB
            $cfgMapper->setConfig($cfg);
            
        }
        
        return $this->render('admin/config/configGeneral.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/config/driver/{type}", name="admin_config_driver")
     */
    public function configDriver(ConfigDriverMapper $cfgMapper, Request $request, $type = 'def') {
        
        // Check driver selection
        if ($type == 'def') {
            $type = $cfgMapper->getDriverName();
        }
        
        // Select driver form
        if ($type == 'SHM') {
            $cfg = $cfgMapper->getSHMConfig();
            $form = $this->createForm(ConfigDriverSHMForm::class, $cfg);
        } else {
            $cfg = $cfgMapper->getModbusConfig();
            $form = $this->createForm(ConfigDriverModbusForm::class, $cfg);
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $cfg = $form->getData();
            
            // Write data to the DB
            if ($type == 'SHM') {
                $cfgMapper->setSHMConfig($cfg);
            } else {
                $cfgMapper->setModbusConfig($cfg);
            }
            
        }
        
        return $this->render('admin/config/configDriver.html.twig', array(
            'drvType' => $type,
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/logs/show/{component}", name="admin_logs_show")
     */
    public function logs(ConfigGeneralMapper $cfgMapper, $component = 'mainProg') {
        
        $components = array('mainProg', 
            'parser',
            'process',
            'socket',
            'taglogger',
            'alarming',
            'driver',
            'script',
            'scriptOutput');
        
        // Check component parameter
        if (!in_array($component, $components)) {
            $component = 'mainProg';
        }
        
        // Server app path
        $servAppPath = $cfgMapper->getConfig()->getServerAppPath();
        $len = strlen($servAppPath);
        if ($servAppPath[$len-1] == '/') {
            $logsPath = $servAppPath."logs/";
        } else {
            $logsPath = $servAppPath."/logs/";
        }
        
        // Prepare log path
        $logPath = $logsPath.$component."/";
        
        // Log lines
        $log = '';
        
        // Get log files
        $files = array_reverse(glob($logPath."*.log"));
        
        if (!empty($files)) {
            
            foreach ($files as $file) {
                
                $log .= "Log name: ".$file."\n";
                
                // Get file
                $fileArray = array_reverse(file($file));
                $log .= implode($fileArray);
                $log .= "----------------------------------------------\n";
                
            }
            
        }
        
        return $this->render('admin/logs.html.twig', array(
            'component' => $component,
            'log' => $log
        ));
    }
    
    /**
     * @Route("/admin/logs/clear", name="admin_logs_clear")
     */
    public function logsClear(SystemScripts $scripts) {
        
        $scripts->clearLogs();
        
        return $this->redirect($this->generateUrl('admin_logs_show'));
    }
    
    /**
     * @Route("/admin/logs/archive", name="admin_logs_archive")
     */
    public function logsArchive(SystemScripts $scripts) {
        
        $scripts->archiveLogs();
        
        $this->addFlash(
            'log-msg-ok',
            'All logs archived!'
        );
        
        return $this->redirect($this->generateUrl('admin_logs_show'));
    }
}
