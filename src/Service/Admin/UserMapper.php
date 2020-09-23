<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\Admin\User;
use App\Entity\Paginator;
use App\Entity\AppException;

/**
 * Class to read/write Users
 *
 * @author Mateusz Mirosławski
 */
class UserMapper
{
    private $dbConn;
    
    private $encoder;
    
    private $authChecker;
    
    public function __construct(
        Connection $connection,
        UserPasswordEncoderInterface $enc,
        AuthorizationCheckerInterface $aci
    ) {
        $this->dbConn = $connection;
        
        $this->encoder = $enc;
        
        $this->authChecker = $aci;
    }
    
    /**
     * Get Users
     *
     * @param int $sort User sorting (0 - ID, 1 - user name, 2 - email, 3 - active flag)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     * @param Paginator $paginator Paginator object
     * @return array Array with Users
     */
    public function getUsers(int $sort = 0, int $sortDESC = 0, Paginator $paginator = null): array
    {
        // Basic query
        $sql = 'SELECT * FROM app_users u';
        
        // Order direction
        $oDirection = ($sortDESC == 1) ? ('DESC') : ('ASC');
        
        // Order
        switch ($sort) {
            case 0:
                $sql .= ' ORDER BY u.id ' . $oDirection;
                break;
            case 1:
                $sql .= ' ORDER BY u.username ' . $oDirection;
                break;
            case 2:
                $sql .= ' ORDER BY u.email ' . $oDirection;
                break;
            case 3:
                $sql .= ' ORDER BY u.isActive ' . $oDirection;
                break;
            default:
                $sql .= ' ORDER BY u.id ' . $oDirection;
        }
        
        // Check paginator
        if (!is_null($paginator)) {
            $sql .= " " . $paginator->getSqlQuery();
        }
        
        // End query
        $sql .= ';';
        
        $statement = $this->dbConn->prepare($sql);
                
        $statement->execute();
        $items = $statement->fetchAll();
        
        $ret = array();
        
        foreach ($items as $item) {
            // New user
            $user = new User();
            $user->setId($item['id']);
            $user->setUsername($item['username']);
            $user->setPassword($item['password']);
            $user->setEmail($item['email']);
            $user->setRoles($item['userRole']);
            $user->setActive((($item['isActive'] == 1) ? (true) : (false)));
            
            // Add to the array
            array_push($ret, $user);
        }
        
        return $ret;
    }
    
    /**
     * Get number of all users in DB
     *
     * @return numeric Number of users in DB
     * @throws Exception
     */
    public function getUsersCount()
    {
        // Base query
        $sql = "SELECT count(*) AS 'cnt' FROM app_users;";
        
        $statement = $this->dbConn->prepare($sql);
                
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
        
        $item = $items[0];
        
        return $item['cnt'];
    }
    
    /**
     * Get User data
     *
     * @param int $userId User identifier
     * @return User User object
     * @throws Exception
     */
    public function getUser(int $userId): User
    {
        // Check user identifier
        User::checkId($userId);
        
        $statement = $this->dbConn->prepare('SELECT * FROM app_users u WHERE u.id = ?;');
        $statement->bindValue(1, $userId, ParameterType::INTEGER);
        $statement->execute();
        
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("User with identifier " . $userId . " does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New user
        $user = new User();
        $user->setId($item['id']);
        $user->setUsername($item['username']);
        $user->setPassword($item['password']);
        $user->setEmail($item['email']);
        $user->setRoles($item['userRole']);
        $user->setActive((($item['isActive'] == 1) ? (true) : (false)));
        
        return $user;
    }
    
    /**
     * Get User data
     *
     * @param string $userNm User name
     * @return User User object
     * @throws Exception
     */
    public function getUserByName(string $userNm): User
    {
        // Check user identifier
        User::checkName($userNm);
        
        $statement = $this->dbConn->prepare('SELECT * FROM app_users u WHERE u.username = ?;');
        $statement->bindValue(1, $userNm, ParameterType::STRING);
        $statement->execute();
        
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new AppException(
                "User with name " . $userNm . " does not exist!",
                AppException::USER_NOT_EXIST
            );
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        $item = $items[0];
        
        // New user
        $user = new User();
        $user->setId($item['id']);
        $user->setUsername($item['username']);
        $user->setPassword($item['password']);
        $user->setEmail($item['email']);
        $user->setRoles($item['userRole']);
        $user->setActive((($item['isActive'] == 1) ? (true) : (false)));
        
        return $user;
    }
    
    /**
     * Check if given User has unique email
     *
     * @param User $user User object
     * @return boolean True if address exist in DB
     * @throws Exception
     */
    private function isEmailAddressExist(User $user)
    {
        $ret = false;
        
        $sql = "SELECT count(*) AS 'cnt' FROM app_users u WHERE u.email = ?;";
                
        $statement = $this->dbConn->prepare($sql);
        
        $statement->bindValue(1, $user->getEmail(), ParameterType::STRING);
        
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items) || count($items) != 1) {
            throw new Exception("Error during executing count query!");
        }
        
        $item = $items[0];
        
        if ($item['cnt'] != 0) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Add User to the DB
     *
     * @param User $newUser User to add
     */
    public function addUser(User $newUser)
    {
        $newUser->isValid();
        
        // Check User address
        if ($this->isEmailAddressExist($newUser)) {
            throw new AppException(
                "User " . $newUser->getUsername() . " address: " . $newUser->getEmail() . " exist in DB!",
                AppException::USER_ADDRESS_EXIST
            );
        }
        
        // Encode password
        $encoded = $this->encoder->encodePassword($newUser, $newUser->getPassword());
        
        // Query
        $q = 'INSERT INTO app_users (username, password, email, userRole) VALUES(?, ?, ?, ?);';
        $stmt = $this->dbConn->prepare($q);
        
        $stmt->bindValue(1, $newUser->getUsername(), ParameterType::STRING);
        $stmt->bindValue(2, $encoded, ParameterType::STRING);
        $stmt->bindValue(3, $newUser->getEmail(), ParameterType::STRING);
        $stmt->bindValue(4, $newUser->getRoles()[0], ParameterType::STRING);
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            throw new AppException(
                "User " . $newUser->getusername() . " exist in DB!",
                AppException::USER_NAME_EXIST
            );
        }
    }
    
    /**
     * Check if user changed email
     *
     * @param User $newUser New User object
     * @param User $oldUser Old User object
     * @return bool
     */
    private function isAddressChanged(User $newUser, User $oldUser): bool
    {
        $ret = false;
        
        if ($newUser->getEmail() != $oldUser->getEmail()) {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Verify user old passwords
     *
     * @param User $oldUser Old user object
     * @param string $oldPass Old user password
     * @return bool True if old password is valid
     */
    private function verifyOldPassword(User $oldUser, $oldPass): bool
    {
        // Check password
        $valid = $this->encoder->isPasswordValid($oldUser, $oldPass);
        
        return $valid;
    }
    
    /**
     * Check if user changed password
     *
     * @param User $newUser New user object
     * @return bool True if user changed password
     */
    private function userChangedPassword(User $newUser): bool
    {
        $ret = false;
        
        if ($newUser->getPassword() != '') {
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Edit User
     *
     * @param User $newUser New User object
     * @param User $oldUser Old User object
     * @param string $oldPass Old user password
     */
    public function editUser(User $newUser, User $oldUser, $oldPass)
    {
        // Check old user data
        $oldUser->isValid(true);
        
        // Check if user changed password
        $passwordChange = $this->userChangedPassword($newUser);
        if ($passwordChange) {
            $newUser->isValid(true);
        } else {
            $newUser->isValid(true, false);
        }
        
        // Check User address
        if ($this->isAddressChanged($newUser, $oldUser) && $this->isEmailAddressExist($newUser)) {
            throw new AppException(
                "User " . $newUser->getUsername() . " address: " . $newUser->getEmail() . " exist in DB!",
                AppException::USER_ADDRESS_EXIST
            );
        }
        
        // Base query
        $q = '';
        
        // Check old password only when not admin
        if (false === $this->authChecker->isGranted('ROLE_ADMIN')) {
            if (!$this->verifyOldPassword($oldUser, $oldPass)) {
                throw new AppException(
                    "Old password wrong!",
                    AppException::USER_OLD_PASSWORD_WRONG
                );
            }
        }
        
        // Check if user changed password
        if ($passwordChange) {
            // Prepare query with password
            $q = 'UPDATE app_users SET username = ?, password = ?, email = ?, userRole = ? WHERE id = ?;';
        } else {
            // Prepare query without password
            $q = 'UPDATE app_users SET username = ?, email = ?, userRole = ? WHERE id = ?;';
        }
        
        $stmt = $this->dbConn->prepare($q);
        
        if ($passwordChange) {
            // Encode new password
            $encoded = $this->encoder->encodePassword($newUser, $newUser->getPassword());
            
            $stmt->bindValue(1, $newUser->getUsername(), ParameterType::STRING);
            $stmt->bindValue(2, $encoded, ParameterType::STRING);
            $stmt->bindValue(3, $newUser->getEmail(), ParameterType::STRING);
            $stmt->bindValue(4, $newUser->getRoles()[0], ParameterType::STRING);
            $stmt->bindValue(5, $newUser->getId(), ParameterType::INTEGER);
        } else {
            $stmt->bindValue(1, $newUser->getUsername(), ParameterType::STRING);
            $stmt->bindValue(2, $newUser->getEmail(), ParameterType::STRING);
            $stmt->bindValue(3, $newUser->getRoles()[0], ParameterType::STRING);
            $stmt->bindValue(4, $newUser->getId(), ParameterType::INTEGER);
        }
        
        try {
            if (!$stmt->execute()) {
                throw new Exception("Error during execute sql add query!");
            }
        } catch (UniqueConstraintViolationException $ex) {
            throw new AppException(
                "User " . $newUser->getusername() . " exist in DB!",
                AppException::USER_NAME_EXIST
            );
        }
    }
    
    /**
     * Delete User
     *
     * @param int $userId User identifier
     */
    public function deleteUser(int $userId)
    {
        // Check User identifier
        User::checkId($userId);
        
        $statement = $this->dbConn->prepare('DELETE FROM app_users WHERE id = ?;');
        $statement->bindValue(1, $userId, ParameterType::INTEGER);
        
        if (!$statement->execute()) {
            throw new Exception("Error during execute delete query!");
        }
    }
    
    /**
     * Enable User
     *
     * @param int $userId User identifier
     * @param bool $en Enable flag
     */
    public function enableUser(int $userId, bool $en = true)
    {
        // Check user identifier
        User::checkId($userId);
        
        $stmt = $this->dbConn->prepare('UPDATE app_users SET isActive = ? WHERE id = ?;');
        
        $stmt->bindValue(1, (($en) ? (1) : (0)), ParameterType::INTEGER);
        $stmt->bindValue(2, $userId, ParameterType::INTEGER);
        
        if (!$stmt->execute()) {
            throw new Exception("Error during execute sql update query!");
        }
    }
}
