<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Form;
use App\Service\Admin\ScriptItemMapper;
use App\Service\Admin\ConfigGeneralMapper;
use App\Service\Admin\SystemScripts;
use App\Form\Admin\ScriptItemForm;
use App\Entity\Admin\ScriptItem;
use App\Entity\Paginator;
use App\Entity\AppException;

class ScriptItemController extends AbstractController
{
    /**
     * Check script list router parameters
     *
     * @param int $page Page number
     * @param int $perPage Rows per page
     * @param int $area Tag area (0 - all, 1 - input, 2 - output, 3 - memory)
     * @param int $sort Tag sorting (0 - ID, 1 - tag name, 2 - start address, 3 - tag type)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     */
    private function checkParams(int &$page, int &$perPage, int &$area, int &$sort, int &$sortDESC)
    {
        // Check page params
        if ($page <= 0) {
            $page = 1;
        }
        if ($perPage < 10) {
            $perPage = 10;
        }
        
        // Check area (0 - all, 1 - input, 2 - output, 3 - memory)
        if ($area < 0 || $area > 3) {
            $area = 0;
        }
        
        // Check sort (0 - ID, 1 - tag name, 2 - script name, 3 - run flag, 4 - lock flag, 5 - enabled flag)
        if ($sort < 0 || $sort > 5) {
            $sort = 0;
        }
        
        // Check sort direction (0 - ASC, 1 - DESC)
        if ($sortDESC < 0 || $sortDESC > 1) {
            $sortDESC = 0;
        }
    }
    
    /**
     * @Route("/admin/script/list/{page}/{perPage}/{area}/{sort}/{sortDESC}", name="admin_script_list")
     */
    public function index(
        ScriptItemMapper $scriptMapper,
        Request $request,
        $page = 1,
        $perPage = 20,
        $area = 0,
        $sort = 0,
        $sortDESC = 0
    ) {
        // Check parameters
        $this->checkParams($page, $perPage, $area, $sort, $sortDESC);
        
        // Get number of all scripts
        $scriptCnt = $scriptMapper->getScriptsCount($area);
        
        // Paginator
        $paginator = new Paginator($scriptCnt, $perPage);
        $paginator->setCurrentPage($page);
        
        // Get all scripts
        $scripts = $scriptMapper->getScripts($area, $sort, $sortDESC, $paginator);
        
        // Store current url in session variable
        $request->getSession()->set('ScriptListURL', $request->getUri());
        
        return $this->render('admin/script/scriptItem.html.twig', array(
            'scripts' => $scripts,
            'paginator' => $paginator
        ));
    }
    
    /**
     * Parse Script item exception
     *
     * @param $errorObj Error object
     * @param Form $form Form object
     */
    private function parseScriptItemError($errorObj, Form $form)
    {
        $code = $errorObj->getCode();
        
        if ($errorObj instanceof AppException) {
            if ($code == AppException::SCRIPT_TAG_EXIST || $code == AppException::TAG_NOT_EXIST) {
                // Add error
                $form->get('scTagName')->addError(new FormError($errorObj->getMessage()));
            } elseif ($code == AppException::SCRIPT_FILE_EXIST || $code == AppException::SCRIPT_FILE_NOT_EXIST) {
                // Add error
                $form->get('scName')->addError(new FormError($errorObj->getMessage()));
            } else {
                // Unknown error
                $form->get('scTagName')->addError(new FormError('Unknown exception!'));
            }
        }
    }
    
    /**
     * @Route("/admin/script/add", name="admin_script_add")
     */
    public function add(
        ScriptItemMapper $scriptMapper,
        Request $request,
        ConfigGeneralMapper $cfg
    ) {
        $script = new ScriptItem();
        
        $form = $this->createForm(ScriptItemForm::class, $script);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Form data
            $script = $form->getData();
            
            try {
                // Check if file exist on disk
                if (!file_exists(SystemScripts::buildScriptPath($cfg->getUserScriptsPath(), $script->getName()))) {
                    throw new AppException("Script: " . $script->getName() .
                            " does not exist on disk!", AppException::SCRIPT_FILE_NOT_EXIST);
                }
                
                // Add to the DB
                $scriptMapper->addScript($script);
                
                $this->addFlash(
                    'script-msg-ok',
                    'New Script was saved!'
                );
                
                // Get last Script list url
                $lastUrl = $request->getSession()->get('ScriptListURL', $this->generateUrl('admin_script_list'));

                return $this->redirect($lastUrl);
            } catch (AppException $ex) {
                $this->parseScriptItemError($ex, $form);
            }
        }
        
        return $this->render('admin/script/scriptItemAdd.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/script/edit/{scriptID}", name="admin_script_edit")
     */
    public function edit(
        $scriptID,
        ScriptItemMapper $scriptMapper,
        Request $request,
        ConfigGeneralMapper $cfg
    ) {
        // Get script from DB
        $script = $scriptMapper->getScript($scriptID);
        
        $form = $this->createForm(ScriptItemForm::class, $script);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Form data
            $scriptN = $form->getData();
            
            try {
                // Check if file exist on disk
                if (!file_exists(SystemScripts::buildScriptPath($cfg->getUserScriptsPath(), $scriptN->getName()))) {
                    throw new AppException("Script: " . $scriptN->getName() .
                            " does not exist on disk!", AppException::SCRIPT_FILE_NOT_EXIST);
                }
                
                // Add to the DB
                $scriptMapper->editScript($scriptN);
                
                $this->addFlash(
                    'script-msg-ok',
                    'New Script was saved!'
                );
                
                // Get last Script list url
                $lastUrl = $request->getSession()->get('ScriptListURL', $this->generateUrl('admin_script_list'));

                return $this->redirect($lastUrl);
            } catch (AppException $ex) {
                $this->parseScriptItemError($ex, $form);
            }
        }
        
        return $this->render('admin/script/scriptItemEdit.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/script/delete/{scriptID}", name="admin_script_delete")
     */
    public function delete($scriptID, ScriptItemMapper $scriptMapper, Request $request)
    {
        // Delete script
        $scriptMapper->deleteScript($scriptID);

        $this->addFlash(
            'script-msg-ok',
            'Script was deleted!'
        );
        
        // Get last Script list url
        $lastUrl = $request->getSession()->get('ScriptListURL', $this->generateUrl('admin_script_list'));

        return $this->redirect($lastUrl);
    }
    
    /**
     * @Route("/admin/script/enable/{scriptID}/{en}", name="admin_script_enable")
     */
    public function enable($scriptID, $en, ScriptItemMapper $scriptMapper, Request $request)
    {
        if ($en < 0 || $en > 1) {
            $en = 0;
        }
        
        // Enable script
        $scriptMapper->enableScript($scriptID, $en);
        
        // Get last Scrit list url
        $lastUrl = $request->getSession()->get('ScriptListURL', $this->generateUrl('admin_script_list'));

        return $this->redirect($lastUrl);
    }
}
