<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Service\Admin\UserMapper;
use App\Entity\Admin\User;

class UserProvider implements UserProviderInterface
{
    
    private $userMapper;
    
    public function __construct(UserMapper $ursMapper)
    {
        $this->userMapper = $ursMapper;
    }
    
    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        
        try {
            $user = $this->userMapper->getUserByName($username);
        } catch (AppException $ex) {
            if ($ex->getCode() == AppException::USER_NOT_EXIST) {
                throw new UsernameNotFoundException();
            } else {
                throw new UnsupportedUserException($ex->getMessage());
            }
        }
        
        return $user;
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }
        
        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
        try {
            $userN = $this->userMapper->getUser($user->getId());
        } catch (AppException $ex) {
            if ($ex->getCode() == AppException::USER_NOT_EXIST) {
                throw new UsernameNotFoundException();
            } else {
                throw new UnsupportedUserException($ex->getMessage());
            }
        }
        
        return $userN;
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}
