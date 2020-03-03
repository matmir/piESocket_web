<?php

namespace App\Entity\Admin;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class represents User object
 * 
 * @author Mateusz Mirosławski
 */
class User implements UserInterface {
    
    /**
     * User identifier
     */
    private $id;

    /**
     * User name
     */
    private $username;

    /**
     * User password
     */
    private $password;

    /**
     * User email
     */
    private $email;
    
    /**
     * User role
     */
    private $userRole;

    /**
     * User active flag
     */
    private $isActive;

    public function __construct() {
        
        $this->id = 0;
        $this->username = '';
        $this->password = '';
        $this->email = '';
        $this->userRole = '';
        $this->isActive = true;
    }
    
    /**
     * Get user identifier
     * 
     * @return int
     */
    public function getId(): int {
        
        return $this->id;
    }
    
    /**
     * Set user identifier
     * 
     * @param int $id
     */
    public function setId(int $id) {
        
        $this->checkId($id);
        
        $this->id = $id;
    }
    
    /**
     * Check User identifier
     * 
     * @param int $id User identifier
     * @return bool True if User identifier is valid
     * @throws Exception if User identifier is invalid
     */
    public static function checkId(int $id): bool {
        
        // Check values
        if ($id < 0) {
            throw new Exception("User identifier wrong value");
        }
        
        return true;
    }

    /**
     * Get User name
     * 
     * @return string User name
     */
    public function getUsername() {
        
        return $this->username;
    }
    
    /**
     * Set User name
     * 
     * @param string $nm User name
     */
    public function setUsername(string $nm) {
        
        $this->checkName($nm);
        
        $this->username = $nm;
    }
    
    /**
     * Check User name
     * 
     * @param string $nm User name
     * @return bool True if User name is valid
     * @throws Exception if User name is invalid
     */
    public static function checkName(string $nm): bool {
        
        if (trim($nm) == false) {
            throw new Exception("User name can not be empty");
        }
        
        return true;
    }

    /**
     * Get user password
     * 
     * @return string
     */
    public function getPassword() {
        
        return $this->password;
    }
    
    /**
     * Set user password
     * 
     * @param string $password
     */
    public function setPassword(string $password) {
        
        $this->checkPassword($password);
        
        $this->password = $password;
    }
    
    /**
     * Check User password
     * 
     * @param string $pass User password
     * @return bool True if User password is valid
     * @throws Exception if User password is invalid
     */
    public static function checkPassword(string $pass): bool {
        
        if (trim($pass) == false) {
            throw new Exception("User password can not be empty");
        }
        
        return true;
    }

    /**
     * Get user roles
     * 
     * @return array
     */
    public function getRoles() {
        return array($this->userRole);
    }
    
    /**
     * Set user role
     * 
     * @param string $role
     */
    public function setRoles(string $role) {
        
        $this->checkRole($role);
        
        $this->userRole = $role;
    }
    
    /**
     * Check role name
     * 
     * @param string $nm Role name
     * @return boolean True if Role name is valid
     * @throws Exception if Role name is invalid
     */
    public static function checkRole($nm) {
        
        if (trim($nm) == false) {
            throw new Exception('Role name can not be empty');
        }
        
        if (strpos($nm, 'ROLE_') === false) {
            throw new Exception('Role name is invalid');
        }
        
        return true;
    }
    
    /**
     * Get User active flag
     * 
     * @return bool User active flag
     */
    public function isActive(): bool {
        
        return $this->isActive;
    }
    
    /**
     * Set User active flag
     * 
     * @param bool $active User Active flag value
     */
    public function setActive(bool $active) {
        
        $this->isActive = $active;
    }
    
    /**
     * Get User email
     * 
     * @return string User email
     */
    public function getEmail(): string {
        
        return $this->email;
    }
    
    /**
     * Set User email
     * 
     * @param string $em User email
     */
    public function setEmail(string $em) {
        
        $this->checkEmail($em);
        
        $this->email = $em;
    }
    
    /**
     * Check User email
     * 
     * @param string $mail User email
     * @return bool True if User email is valid
     * @throws Exception if User email is invalid
     */
    public static function checkEmail(string $mail): bool {
        
        if (trim($mail) == false) {
            throw new Exception("User email can not be empty");
        }
        
        return true;
    }
    
    /**
     * Check if User object is valid
     * 
     * @param bool $checkID Flag validating User identifier
     * @return bool True if User is valid
     * @throws Exception Throws when User is invalid
     */
    public function isValid(bool $checkID = false): bool {
        
        // Check identifier
        if ($checkID) {
            $this->checkId($this->id);
        }
        
        $this->checkName($this->username);
        $this->checkPassword($this->password);
        $this->checkEmail($this->email);
        $this->checkRole($this->userRole);
        
        return true;
    }
    
    public function getSalt() {
        
        return null;
    }
    
    public function eraseCredentials() {
    }
}
