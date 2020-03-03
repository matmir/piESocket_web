<?php

namespace App\Entity\Admin;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Admin\User;
use App\Entity\AppException;

/**
 * Class represents User object for Forms (add/edit)
 * 
 * @author Mateusz Mirosławski
 */
class UserEntity {
    
    /**
     * User identifier
     * 
     * @Assert\PositiveOrZero
     */
    private $id;
    
    /**
     * User name
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=25)
     */
    private $username;
    
    /**
     * Old User password
     * 
     * @Assert\Length(max=200)
     */
    private $oldPassword;
    
    /**
     * User password
     * 
     * @Assert\Length(max=200)
     */
    private $password1;
    
    /**
     * User password
     * 
     * @Assert\Length(max=200)
     */
    private $password2;
    
    /**
     * User email
     * 
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email"
     * )
     * @Assert\Length(max=254)
     */
    private $email;
    
    /**
     * User role
     * 
     * @Assert\NotBlank()
     * @Assert\Length(max=20)
     */
    private $userRole;
    
    /**
     * User active flag
     */
    private $isActive;
    
    public function __construct() {
        
        $this->id = 0;
        $this->username = '';
        $this->oldPassword = '';
        $this->password1 = '';
        $this->password2 = '';
        $this->email = '';
        $this->userRole = '';
    }
    
    public function getid() {
        
        return $this->id;
    }
    
    public function setid($id) {
        
        $this->id = $id;
    }
    
    public function getusername() {
        
        return $this->username;
    }
    
    public function setusername($val) {
        
        $this->username = $val;
    }
    
    public function getpassword1() {
        
        return $this->password1;
    }
    
    public function setpassword1($val) {
        
        $this->password1 = $val;
    }
    
    public function getpassword2() {
        
        return $this->password2;
    }
    
    public function setpassword2($val) {
        
        $this->password2 = $val;
    }
    
    public function getoldPassword() {
        
        return $this->oldPassword;
    }
    
    public function setoldPassword($val) {
        
        $this->oldPassword = $val;
    }
    
    public function getemail() {
        
        return $this->email;
    }
    
    public function setemail($val) {
        
        $this->email = $val;
    }
    
    public function getuserrole() {
        
        return $this->userRole;
    }
    
    public function setuserrole($val) {
        
        $this->userRole = $val;
    }
    
    /**
     * Get User object
     * 
     * @param bool $add New User flag
     * @return User User object
     */
    public function getFullUserObject(bool $add=true): User {
        
        // New User
        $user = new User();
        $user->setId($this->id);
        $user->setUsername($this->username);
        $user->setEmail($this->email);
        $user->setRoles($this->userRole);
        
        if ($add && $this->password1 == '') {
            throw new AppException(
                "Password can not be empty!",
                AppException::USER_PASSWORD_NOT_EQUAL
            );
        }
        
        if ($this->password1 != '') {
            if ($this->password1 == $this->password2) {
                $user->setPassword($this->password1);
            } else {
                throw new AppException(
                    "Given passwords are not equal!",
                    AppException::USER_PASSWORD_NOT_EQUAL
                );
            }
        }
        
        return $user;
    }
    
    /**
     * Initialize from User object
     * 
     * @param User $user User object
     */
    public function initFromUserObject(User $user) {
        
        // Check if User object is valid
        $user->isValid(true);
        
        $this->id = $user->getId();
        $this->username = $user->getUsername();
        $this->oldPassword = '';
        $this->password1 = '';
        $this->password2 = '';
        $this->email = $user->getEmail();
        $this->userRole = $user->getRoles()[0];
    }
}
