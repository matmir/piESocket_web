<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Form;
use App\Form\Admin\ConfigGeneralForm;
use App\Form\Admin\DriverModbusForm;
use App\Form\Admin\DriverSHMForm;
use App\Service\Admin\ConfigGeneralMapper;
use App\Service\Admin\DriverConnectionMapper;
use App\Service\Admin\SystemScripts;
use App\Entity\Admin\DriverModbus;
use App\Entity\Admin\DriverSHM;
use App\Entity\Admin\DriverType;
use App\Entity\Admin\DriverConnection;
use App\Entity\AppException;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(SystemScripts $scripts, ConfigGeneralMapper $cfgMapper, DriverConnectionMapper $connMapper)
    {
        // Get service status
        $services = $scripts->getServiceStatus();
        
        // Get restart flag
        $restart = $cfgMapper->serverNeedRestart();
        
        // Get connections
        $connections = $connMapper->getConnections(true);
        
        return $this->render('admin/index.html.twig', array(
            'services' => $services,
            'restart' => $restart,
            'connections' => $connections
        ));
    }
    
    /**
     * @Route("/admin/config", name="admin_config_general")
     */
    public function configGeneral(ConfigGeneralMapper $cfgMapper, Request $request)
    {
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
     * @Route("/admin/config/driver", name="admin_config_driver")
     */
    public function configDriver(DriverConnectionMapper $connMapper)
    {
        // Get connections
        $connList = $connMapper->getConnections();
        
        return $this->render('admin/config/configDriver.html.twig', array(
            'connections' => $connList
        ));
    }
    
    /**
     * Parse Driver exception
     *
     * @param $errorObj Error object
     * @param Form $form Form object
     */
    private function parseDriverError($errorObj, Form $form)
    {
        $code = $errorObj->getCode();
        
        if ($errorObj instanceof AppException) {
            switch ($code) {
                case AppException::SHM_EXIST:
                    // Add error
                    $form->get('segmentName')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::DRIVER_EXIST:
                    // Add error
                    $form->get('connName')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::MODBUS_ADDRESS_EXIST:
                    // Add error
                    $form->get('TCP_addr')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::DRIVER_LIMIT:
                    // Add error
                    $form->get('connName')->addError(new FormError($errorObj->getMessage()));
                    break;
                default:
                    $form->get('connName')->addError(new FormError('Unknown exception!'));
            }
        }
    }
    
    /**
     * @Route("/admin/config/driver/add/{type}", name="admin_config_driver_add")
     */
    public function configDriverAdd($type, DriverConnectionMapper $connMapper, Request $request)
    {
        // Check driver type
        if ($type < 0 || $type > 1) {
            $type = 0;
        }
                
        $conn = new DriverConnection();
                
        // Select driver form
        if ($type == DriverType::SHM) {
            $conn->setType(DriverType::SHM);
            $conn->setShmConfig(new DriverSHM());
            $form = $this->createForm(DriverSHMForm::class, $conn);
        } else {
            $conn->setType(DriverType::MODBUS);
            $conn->setModbusConfig(new DriverModbus());
            $form = $this->createForm(DriverModbusForm::class, $conn);
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Form data
            $conn = $form->getData();
                        
            try {
                // Add to the DB
                $connMapper->addConnection($conn);
                
                $this->addFlash(
                    'driver-msg-ok',
                    'New connection was saved!'
                );
                
                return $this->redirect($this->generateUrl('admin_config_driver'));
            } catch (AppException $ex) {
                $this->parseDriverError($ex, $form);
            }
        }
        
        return $this->render('admin/config/configDriverAdd.html.twig', array(
            'drvType' => $type,
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/config/driver/edit/{connId}", name="admin_config_driver_edit")
     */
    public function configDriverEdit($connId, DriverConnectionMapper $connMapper, Request $request)
    {
        // Get connection object
        $conn = $connMapper->getConnection($connId);
        
        $type = $conn->getType();
        
        // Select driver form
        if ($conn->getType() == DriverType::SHM) {
            $form = $this->createForm(DriverSHMForm::class, $conn);
        } else {
            $form = $this->createForm(DriverModbusForm::class, $conn);
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Form data
            $connN = $form->getData();
            
            try {
                // Add to the DB
                $connMapper->editConnection($connN);
                
                $this->addFlash(
                    'driver-msg-ok',
                    'Connection was saved!'
                );
                
                return $this->redirect($this->generateUrl('admin_config_driver'));
            } catch (AppException $ex) {
                $this->parseDriverError($ex, $form);
            }
        }
        
        return $this->render('admin/config/configDriverEdit.html.twig', array(
            'drvType' => $type,
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/config/driver/enable/{connId}/{en}", name="admin_config_driver_enable")
     */
    public function enable($connId, $en, DriverConnectionMapper $connMapper)
    {
        if ($en < 0 || $en > 1) {
            $en = 0;
        }
        
        // Enable connection
        $connMapper->enableConnection($connId, $en);

        return $this->redirect($this->generateUrl('admin_config_driver'));
    }
    
    /**
     * Parse delete connection exception
     *
     * @param numeric $errorCode Error code
     */
    private function parseDeleteConnError($errorCode)
    {
        switch ($errorCode) {
            case AppException::DRIVER_USED:
                // Add error
                $this->addFlash(
                    'driver-msg-error',
                    'Connection is used inside the system - can not be deleted!'
                );
                break;
            default:
                $this->addFlash(
                    'driver-msg-error',
                    'Unknown error during delete!'
                );
        }
    }
    
    /**
     * @Route("/admin/config/driver/delete/{connId}", name="admin_config_driver_delete")
     */
    public function delete($connId, DriverConnectionMapper $connMapper)
    {
        try {
            // Delete connection
            $connMapper->deleteConnection($connId);
            
            $this->addFlash(
                'driver-msg-ok',
                'Connection was deleted!'
            );
        } catch (AppException $ex) {
            $this->parseDeleteConnError($ex->getCode());
        }
        
        return $this->redirect($this->generateUrl('admin_config_driver'));
    }
    
    
    /**
     * @Route("/admin/logs/show/{component}", name="admin_logs_show")
     */
    public function logs(ConfigGeneralMapper $cfgMapper, $component = 'mainProg')
    {
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
        if ($servAppPath[$len - 1] == '/') {
            $logsPath = $servAppPath . "logs/";
        } else {
            $logsPath = $servAppPath . "/logs/";
        }
        
        // Prepare log path
        $logPath = $logsPath . $component . "/";
        
        // Log lines
        $log = '';
        
        // Get log files
        $files = array_reverse(glob($logPath . "*.log"));
        
        if (!empty($files)) {
            foreach ($files as $file) {
                $log .= "Log name: " . $file . "\n";
                
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
    public function logsClear(SystemScripts $scripts)
    {
        $scripts->clearLogs();
        
        return $this->redirect($this->generateUrl('admin_logs_show'));
    }
    
    /**
     * @Route("/admin/logs/archive", name="admin_logs_archive")
     */
    public function logsArchive(SystemScripts $scripts)
    {
        $scripts->archiveLogs();
        
        $this->addFlash(
            'log-msg-ok',
            'All logs archived!'
        );
        
        return $this->redirect($this->generateUrl('admin_logs_show'));
    }
}
