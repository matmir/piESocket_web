<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Form;
use App\Service\Admin\TagsMapper;
use App\Service\Admin\DriverConnectionMapper;
use App\Form\Admin\TagForm;
use App\Entity\Paginator;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;
use App\Entity\AppException;

class TagController extends AbstractController
{
    /**
     * Check tag list router parameters
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
     * @Route("/admin/tags/list/{page}/{perPage}/{area}/{sort}/{sortDESC}", name="admin_tags_list")
     */
    public function index(
        TagsMapper $tagsMapper,
        Request $request,
        $page = 1,
        $perPage = 20,
        $area = 0,
        $sort = 0,
        $sortDESC = 0
    ) {
        // Check parameters
        $this->checkParams($page, $perPage, $area, $sort, $sortDESC);
        
        // Get number of all tags
        $tagCnt = $tagsMapper->getTagsCount($area);
        
        // Paginator
        $paginator = new Paginator($tagCnt, $perPage);
        $paginator->setCurrentPage($page);
        
        // Get all tags
        $tags = $tagsMapper->getTags($area, $sort, $sortDESC, $paginator);
        
        // Store current url in session variable
        $this->get('session')->set('tagsListURL', $request->getUri());
        
        return $this->render('admin/tags/tags.html.twig', array(
            'tags' => $tags,
            'paginator' => $paginator
        ));
    }
    
    /**
     * Parse Tag exception
     *
     * @param $errorObj Error object
     * @param Form $form Form object
     */
    private function parseTagError($errorObj, Form $form)
    {
        $code = $errorObj->getCode();
        
        if ($errorObj instanceof AppException) {
            switch ($code) {
                case AppException::TAG_ADDRESS_EXIST:
                    // Add error
                    $form->get('tArea')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::TAG_NAME_EXIST:
                    // Add error
                    $form->get('tName')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::TAG_BYTE_ADDRESS_WRONG:
                    // Add error
                    $form->get('tByteAddress')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::TAG_WRONG_AREA:
                    // Add error
                    $form->get('tArea')->addError(new FormError($errorObj->getMessage()));
                    break;
                default:
                    $form->get('tName')->addError(new FormError('Unknown exception!'));
            }
        }
    }
    
    /**
     * @Route("/admin/tags/add", name="admin_tags_add")
     */
    public function add(TagsMapper $tagsMapper, DriverConnectionMapper $connMapper, Request $request)
    {
        $tag = new Tag();
        
        // Get connection names
        $connections = $connMapper->getConnectionsName();
        
        $form = $this->createForm(TagForm::class, $tag, ['connections' => $connections]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Form data
            $tag = $form->getData();
            
            try {
                // Add to the DB
                $tagsMapper->addTag($tag);
                
                $this->addFlash(
                    'tag-msg-ok',
                    'New Tag was saved!'
                );
                
                // Get last Tags list url
                $lastUrl = $this->get('session')->get('tagsListURL', $this->generateUrl('admin_tags_list'));

                return $this->redirect($lastUrl);
            } catch (AppException $ex) {
                $this->parseTagError($ex, $form);
            }
        }
        
        return $this->render('admin/tags/tagsAdd.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/tags/edit/{tagID}", name="admin_tags_edit")
     */
    public function edit($tagID, TagsMapper $tagsMapper, DriverConnectionMapper $connMapper, Request $request)
    {
        // Get Tag data from DB
        $tag = $tagsMapper->getTag($tagID);
        
        // Get connection names
        $connections = $connMapper->getConnectionsName();
                
        $form = $this->createForm(TagForm::class, $tag, ['connections' => $connections]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Form data
            $tagN = $form->getData();
            
            try {
                // Save Tag
                $tagsMapper->editTag($tagN);
                
                $this->addFlash(
                    'tag-msg-ok',
                    'Tag was saved!'
                );
                
                // Get last Tags list url
                $lastUrl = $this->get('session')->get('tagsListURL', $this->generateUrl('admin_tags_list'));

                return $this->redirect($lastUrl);
            } catch (AppException $ex) {
                $this->parseTagError($ex, $form);
            }
        }
        
        return $this->render('admin/tags/tagsEdit.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * Parse delete Tag exception
     *
     * @param numeric $errorCode Error code
     */
    private function parseDeleteTagError($errorCode)
    {
        switch ($errorCode) {
            case AppException::TAG_IS_USED:
                // Add error
                $this->addFlash(
                    'tag-msg-error',
                    'Tag is used inside the system - can not be deleted!'
                );
                break;
            default:
                $this->addFlash(
                    'tag-msg-error',
                    'Unknown error during delete!'
                );
        }
    }
    
    /**
     * @Route("/admin/tags/delete/{tagID}", name="admin_tags_delete")
     */
    public function delete($tagID, TagsMapper $tagsMapper)
    {
        try {
            // Delete tag
            $tagsMapper->deleteTag($tagID);
            
            $this->addFlash(
                'tag-msg-ok',
                'Tag was deleted!'
            );
        } catch (AppException $ex) {
            $this->parseDeleteTagError($ex->getCode());
        }
        
        // Get last Tags list url
        $lastUrl = $this->get('session')->get('tagsListURL', $this->generateUrl('admin_tags_list'));

        return $this->redirect($lastUrl);
    }
    
    /**
     * Check search data
     *
     * @param array $data Search data array
     * @throws AppException
     */
    private function checkSearchData(array $data)
    {
        // Check if array has 'tagName' field
        if (!array_key_exists('tagName', $data)) {
            throw new AppException('Tag search: Missing tagName field in array!');
        }
    }
    
    /**
     * Prepare search reply array
     *
     * @param array $tags Reply array with tags
     * @return array Reply array with tag names
     * @throws AppException
     */
    private function prepareTagNames(array $tags): array
    {
        $ret = array();
        
        for ($i = 0; $i < count($tags); ++$i) {
            if (!($tags[$i] instanceof Tag)) {
                throw new AppException('Tag search: Wrong Tag object in reply array!');
            }
            
            // Add to the array
            array_push($ret, $tags[$i]->getName());
        }
        
        return $ret;
    }
    
    /**
     * Prepare search reply array
     *
     * @param array $tags Reply array with tags
     * @return array Reply array with tag types
     * @throws AppException
     */
    private function prepareTagTypes(array $tags): array
    {
        $ret = array();
        $type = '';
        
        // Get type only when one tag was found
        if (count($tags) == 1) {
            if (!($tags[0] instanceof Tag)) {
                throw new AppException('Tag search: Wrong Tag object in reply array!');
            }
            
            if ($tags[0]->getType() == TagType::BIT) {
                $type = 'Bit';
            } elseif ($tags[0]->getType() == TagType::REAL) {
                $type = 'Real';
            } else {
                $type = 'Numeric';
            }
            
            // Add to the array
            array_push($ret, $type);
        }
        
        return $ret;
    }
    
    /**
     * @Route("/admin/tags/search/{tagType}", name="admin_tags_search")
     */
    public function search(Request $request, TagsMapper $tagsMapper, int $tagType = 0)
    {
        // Check param
        if ($tagType > 1 || $tagType < 0) {
            $tagType = 0;
        }
        
        $error = array(
            'state' => false,
            'msg' => 'none',
            'code' => 0
        );
        
        $reply = array();
        
        // Get data from POST
        if ($request->isMethod('POST')) {
            
            $data = json_decode($request->getContent(), true);
        
            try {
                // Check data
                $this->checkSearchData($data);
                
                // Get tags
                $tags = $tagsMapper->searchTagsByName($data['tagName']);
                
                // Prepare reply
                $reply = ($tagType == 0) ? ($this->prepareTagNames($tags)) : ($this->prepareTagTypes($tags));
            } catch (AppException $ex) {
                $error['state'] = true;
                $error['msg'] = $ex->getMessage();
                $error['code'] = $ex->getCode();
            }
        }
        
        return $this->json(array(
            'error' => $error,
            'reply' => $reply
        ));
    }
}
