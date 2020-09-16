<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Form;
use App\Service\Admin\UserMapper;
use App\Entity\Admin\UserEntity;
use App\Entity\Paginator;
use App\Entity\AppException;
use App\Form\Admin\UserForm;

class UserController extends AbstractController
{
    /**
     * Check user list router parameters
     *
     * @param int $page Page number
     * @param int $perPage Rows per page
     * @param int $sort User sorting (0 - ID, 1 - user name, 2 - email, 3 - active flag)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     */
    private function checkParams(int &$page, int &$perPage, int &$sort, int &$sortDESC)
    {
        // Check page params
        if ($page <= 0) {
            $page = 1;
        }
        if ($perPage < 10) {
            $perPage = 10;
        }
        
        // Check sort (0 - ID, 1 - user name, 2 - email, 3 - active flag)
        if ($sort < 0 || $sort > 3) {
            $sort = 0;
        }
        
        // Check sort direction (0 - ASC, 1 - DESC)
        if ($sortDESC < 0 || $sortDESC > 1) {
            $sortDESC = 0;
        }
    }
    
    /**
     * @Route("/admin/user/list/{page}/{perPage}/{sort}/{sortDESC}", name="admin_user_list")
     */
    public function index(UserMapper $userMapper, Request $request, $page = 1, $perPage = 20, $sort = 0, $sortDESC = 0)
    {
        // Check parameters
        $this->checkParams($page, $perPage, $sort, $sortDESC);
        
        // Get number of all users
        $userCnt = $userMapper->getUsersCount();
        
        // Paginator
        $paginator = new Paginator($userCnt, $perPage);
        $paginator->setCurrentPage($page);
        
        // Get all users
        $users = $userMapper->getUsers($sort, $sortDESC, $paginator);
        
        // Store current url in session variable
        $this->get('session')->set('usersListURL', $request->getUri());
        
        return $this->render('admin/user/usersList.html.twig', array(
            'users' => $users,
            'paginator' => $paginator
        ));
    }
    
    /**
     * Parse User exception
     *
     * @param $errorObj Error object
     * @param Form $form Form object
     */
    private function parseUserError($errorObj, Form $form)
    {
        $code = $errorObj->getCode();
        
        if ($errorObj instanceof AppException) {
            switch ($code) {
                case AppException::USER_ADDRESS_EXIST:
                    // Add error
                    $form->get('email')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::USER_NAME_EXIST:
                    // Add error
                    $form->get('username')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::USER_PASSWORD_NOT_EQUAL:
                    // Add error
                    $form->get('password1')->addError(new FormError($errorObj->getMessage()));
                    break;
                case AppException::USER_OLD_PASSWORD_WRONG:
                    // Add error
                    $form->get('oldPassword')->addError(new FormError($errorObj->getMessage()));
                    break;
                default:
                    $form->get('username')->addError(new FormError('Unknown exception!'));
            }
        }
    }
    
    /**
     * @Route("/admin/user/add", name="admin_user_add")
     */
    public function add(UserMapper $userMapper, Request $request)
    {
        $userE = new UserEntity();
        
        $form = $this->createForm(UserForm::class, $userE);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Form data
            $userE = $form->getData();
            
            try {
                // Get real Tag object
                $user = $userE->getFullUserObject();
                
                // Add to the DB
                $userMapper->addUser($user);
                
                $this->addFlash(
                    'usr-msg-ok',
                    'New User was saved!'
                );
                
                // Get last Tags list url
                $lastUrl = $this->get('session')->get('userListURL', $this->generateUrl('admin_user_list'));

                return $this->redirect($lastUrl);
            } catch (AppException $ex) {
                $this->parseUserError($ex, $form);
            }
        }
        
        return $this->render('admin/user/userAdd.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/user/edit/{userID}", name="admin_user_edit")
     */
    public function edit($userID, UserMapper $userMapper, Request $request)
    {
        // Get user data from DB
        $oldUser = $userMapper->getUser($userID);
        
        $userE = new UserEntity();
        $userE->initFromUserObject($oldUser);
        
        $form = $this->createForm(UserForm::class, $userE);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get Form data
            $newUserE = $form->getData();
            
            try {
                // Get real User object
                $newUser = $newUserE->getFullUserObject(false);
                
                // Save User
                $userMapper->editUser($newUser, $oldUser, $newUserE->getoldPassword());
                
                $this->addFlash(
                    'usr-msg-ok',
                    'User was saved!'
                );
                
                // Get last Tags list url
                $lastUrl = $this->get('session')->get('userListURL', $this->generateUrl('admin_user_list'));

                return $this->redirect($lastUrl);
            } catch (AppException $ex) {
                $this->parseUserError($ex, $form);
            }
        }
        
        return $this->render('admin/user/userEdit.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/admin/user/delete/{userID}", name="admin_user_delete")
     */
    public function delete($userID, UserMapper $userMapper)
    {
        // Delete user
        $userMapper->deleteUser($userID);

        $this->addFlash(
            'usr-msg-ok',
            'User was deleted!'
        );
        
        // Get last Script list url
        $lastUrl = $this->get('session')->get('userListURL', $this->generateUrl('admin_user_list'));

        return $this->redirect($lastUrl);
    }
    
    /**
     * @Route("/admin/user/enable/{userID}/{en}", name="admin_user_enable")
     */
    public function enable($userID, $en, UserMapper $userMapper)
    {
        if ($en < 0 || $en > 1) {
            $en = 0;
        }
        
        // Enable user
        $userMapper->enableUser($userID, $en);
        
        // Get last Scrit list url
        $lastUrl = $this->get('session')->get('userListURL', $this->generateUrl('admin_user_list'));

        return $this->redirect($lastUrl);
    }
}
