<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Form;

use App\Service\Admin\TagsMapper;
use App\Service\Admin\TagLoggerMapper;
use App\Entity\Paginator;
use App\Form\Admin\TagLoggerForm;
use App\Entity\Admin\TagLoggerEntity;
use App\Entity\AppException;

class TagLoggerController extends AbstractController {
    
    /**
     * Check tag logger list router parameters
     * 
     * @param int $page Page number
     * @param int $perPage Rows per page
     * @param int $area Tag area (0 - all, 1 - input, 2 - output, 3 - memory)
     * @param int $sort Tag logger sorting (0 - ID, 1 - tag name, 2 - interval, 3 - last update, 4 - enabled flag)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     */
    private function checkParams(int &$page, int &$perPage, int &$area, int &$sort, int &$sortDESC) {
        
        // Check page params
        if ($page <= 0) {
            $page = 1;
        }
        if ($perPage <10) {
            $perPage = 10;
        }
        
        // Check area (0 - all, 1 - input, 2 - output, 3 - memory)
        if ($area < 0 || $area > 3) {
            $area = 0;
        }
        
        // Check sort (0 - ID, 1 - tag name, 2 - start address, 3 - tag type)
        if ($sort < 0 || $sort > 3) {
            $sort = 0;
        }
        
        // Check sort direction (0 - ASC, 1 - DESC)
        if ($sortDESC < 0 || $sortDESC > 1) {
            $sortDESC = 0;
        }
    }
    
    /**
     * @Route("/admin/logger/list/{page}/{perPage}/{area}/{sort}/{sortDESC}", name="admin_logger_list")
     */
    public function index(TagLoggerMapper $tagLoggerMapper, Request $request, $page=1, $perPage=20, $area=0, $sort=0, $sortDESC=0) {
        
        // Check parameters
        $this->checkParams($page, $perPage, $area, $sort, $sortDESC);
        
        // Get number of all tags
        $tagLogCnt = $tagLoggerMapper->getLoggersCount($area);
                
        // Paginator
        $paginator = new Paginator($tagLogCnt, $perPage);
        $paginator->setCurrentPage($page);
        
        // Get all tags
        $tagLogers = $tagLoggerMapper->getLoggers($area, $sort, $sortDESC, $paginator);
        
        // Store current url in session variable
        $this->get('session')->set('tagLoggersListURL', $request->getUri());
        
        return $this->render('admin/tagsLogger/tagsLogger.html.twig', array(
            'loggers' => $tagLogers,
            'paginator' => $paginator
        ));
    }
    
    /**
     * Parse Tag logger exception
     * 
     * @param $errorObj Error object
     * @param Form $form Form object
     */
    private function parseTagLoggerError($errorObj, Form $form) {
        
        $code = $errorObj->getCode();
        
        if ($errorObj instanceof AppException) {
            
            if ($code == AppException::LOGGER_TAG_EXIST || $code == AppException::TAG_NOT_EXIST) {
                
                // Add error
                $form->get('ltTagName')->addError(new FormError($errorObj->getMessage()));
                
            } else {
                
                // Unknown error
                $form->get('ltTagName')->addError(new FormError('Unknown exception!'));
                
            }
            
        }
    }
    
    /**
     * @Route("/admin/logger/add", name="admin_logger_add")
     */
    public function add(TagLoggerMapper $tagLoggerMapper, TagsMapper $tagsMapper, Request $request) {
                
        $tagLoggerE = new TagLoggerEntity();
        
        $form = $this->createForm(TagLoggerForm::class, $tagLoggerE);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Get Form data
            $tagLoggerE = $form->getData();
            
            try {
                
                // Get tag object
                $tag = $tagsMapper->getTagByName($tagLoggerE->getltTagName());
                
                // Get real Tag logger object
                $tagLogger = $tagLoggerE->getFullLoggerObject($tag);
                
                // Add to the DB
                $tagLoggerMapper->addLogger($tagLogger);
                
                $this->addFlash(
                    'tag-msg-ok',
                    'New Tag logger was saved! '
                );
                
                // Get last Tags list url
                $lastUrl = $this->get('session')->get('tagLoggersListURL', $this->generateUrl('admin_logger_list'));

                return $this->redirect($lastUrl);
                
            } catch (AppException $ex) {
                
                $this->parseTagLoggerError($ex, $form);
                
            }
        }
        
        return $this->render('admin/tagsLogger/tagsLoggerAdd.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/logger/edit/{loggerID}", name="admin_logger_edit")
     */
    public function edit($loggerID, TagLoggerMapper $tagLoggerMapper, TagsMapper $tagsMapper, Request $request) {
        
        // Get logger from DB
        $tagLogger = $tagLoggerMapper->getLogger($loggerID);
        
        $tagLoggerE = new TagLoggerEntity();
        $tagLoggerE->initFromLoggerObject($tagLogger);
        
        $form = $this->createForm(TagLoggerForm::class, $tagLoggerE);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Get Form data
            $tagLoggerE = $form->getData();
            
            try {
                
                // Get tag object
                $tag = $tagsMapper->getTagByName($tagLoggerE->getltTagName());
                
                // Get real Tag logger object
                $tagLogger = $tagLoggerE->getFullLoggerObject($tag);
                
                // Write to the DB
                $tagLoggerMapper->editLogger($tagLogger);
                
                $this->addFlash(
                    'tag-msg-ok',
                    'New Tag logger was saved!'
                );
                
                // Get last Tags list url
                $lastUrl = $this->get('session')->get('tagLoggersListURL', $this->generateUrl('admin_logger_list'));

                return $this->redirect($lastUrl);
                
            } catch (AppException $ex) {
                
                $this->parseTagLoggerError($ex, $form);
                
            }
            
        }
        
        return $this->render('admin/tagsLogger/tagsLoggerEdit.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/logger/delete/{loggerID}", name="admin_logger_delete")
     */
    public function delete($loggerID, TagLoggerMapper $tagLoggerMapper) {
        
        // Delete logger
        $tagLoggerMapper->deleteLogger($loggerID);

        $this->addFlash(
            'tag-msg-ok',
            'Tag logger was deleted!'
        );
        
        // Get last Tags list url
        $lastUrl = $this->get('session')->get('tagLoggersListURL', $this->generateUrl('admin_logger_list'));

        return $this->redirect($lastUrl);
    }
    
    /**
     * @Route("/admin/logger/enable/{loggerID}/{en}", name="admin_logger_enable")
     */
    public function enable($loggerID, $en, TagLoggerMapper $tagLoggerMapper) {
        
        if ($en < 0 || $en > 1) {
            $en = 0;
        }
        
        // Enable logger
        $tagLoggerMapper->enableLogger($loggerID, $en);
        
        // Get last Tags list url
        $lastUrl = $this->get('session')->get('tagLoggersListURL', $this->generateUrl('admin_logger_list'));

        return $this->redirect($lastUrl);
    }
}
