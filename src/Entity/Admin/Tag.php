<?php

namespace App\Entity\Admin;

use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\AppException;
use App\Entity\Admin\TagArea;
use App\Entity\Admin\TagType;
use App\Entity\Admin\User;
use App\Entity\Admin\DriverConnection;

/**
 * Class represents tag
 *
 * @author Mateusz Mirosławski
 */
class Tag
{
    /**
     * Tag identifier
     */
    private int $tid;
    
    /**
     * Driver connection identifier
     */
    private int $connId;
    
    /**
     * Driver connection name
     */
    private string $connName;
    
    /**
     * Tag name
     */
    private string $tName;
    
    /**
     * Tag type
     */
    private int $tType;
    
    /**
     * Tag area
     */
    private int $tArea;
    
    /**
     * Tag byte address
     */
    private int $tByteAddress;
    
    /**
     * Tag bit address
     */
    private int $tBitAddress;
    
    /**
     * Tag read access role name
     */
    private string $tReadAccess;
    
    /**
     * Tag write access role name
     */
    private string $tWriteAccess;
    
    /**
     * Default constructor
     *
     * @param int $id Tag identifier
     * @param int $cid Connection identifier
     * @param string $cName Connection name
     * @param string $tName Tag name
     * @param int $type Tag type
     * @param int $area Tag area
     * @param int $byteAddr Tag byte address
     * @param int $bit Tag bit address
     * @param string $readAccess Read access
     * @param string $writeAccess Write access
     */
    public function __construct(
        int $id = 0,
        int $cid = 0,
        string $cName = '',
        string $tName = '',
        int $type = TagType::BIT,
        int $area = TagArea::INPUT,
        int $byteAddr = 0,
        int $bit = 0,
        string $readAccess = 'ROLE_USER',
        string $writeAccess = 'ROLE_USER'
    ) {
        $this->tid = $id;
        $this->connId = $cid;
        $this->connName = $cName;
        $this->tName = $tName;
        $this->tType = $type;
        $this->tArea = $area;
        $this->tByteAddress = $byteAddr;
        $this->tBitAddress = $bit;
        $this->tReadAccess = $readAccess;
        $this->tWriteAccess = $writeAccess;
    }
    
    /**
     * Get Tag identifier
     *
     * @return int Tag identifier
     */
    public function getId(): int
    {
        return $this->tid;
    }
    
    /**
     * Set Tag identifier
     *
     * @param int $id Tag identifier
     */
    public function setId(int $id)
    {
        $this->checkId($id);
        
        $this->tid = $id;
    }
    
    /**
     * Check Tag identifier
     *
     * @param int $id Tag identifier
     * @return bool True if Tag identifier is valid
     * @throws Exception if Tag identifier is invalid
     */
    public static function checkId(int $id): bool
    {
        // Check values
        if ($id < 0) {
            throw new Exception('Tag identifier wrong value');
        }
        
        return true;
    }
    
    /**
     * Get Driver connection identifier
     *
     * @return int Driver connection identifier
     */
    public function getConnId(): int
    {
        return $this->connId;
    }
    
    /**
     * Set Driver connection identifier
     *
     * @param int $id Driver connection identifier
     */
    public function setConnId(int $id)
    {
        DriverConnection::checkId($id);
        
        $this->connId = $id;
    }
    
    /**
     * Get Driver connection name
     *
     * @return string Driver connection name
     */
    public function getConnName(): string
    {
        return $this->connName;
    }
    
    /**
     * Set Driver connection name
     *
     * @param string $nm Driver connection name
     */
    public function setConnName(string $nm)
    {
        DriverConnection::checkName($nm);
        
        $this->connName = $nm;
    }
    
    /**
     * Get Tag name
     *
     * @return string Tag name
     */
    public function getName(): string
    {
        return $this->tName;
    }
    
    /**
     * Set Tag name
     *
     * @param string $nm Tag name
     */
    public function setName(string $nm)
    {
        $this->checkName($nm);
        
        $this->tName = $nm;
    }
    
    /**
     * Check Tag name
     *
     * @param string $nm Tag name
     * @return bool True if Tag name is valid
     * @throws Exception if Tag name is invalid
     */
    public static function checkName(string $nm): bool
    {
        if (trim($nm) == false) {
            throw new Exception('Tag name can not be empty');
        }
        
        return true;
    }
    
    /**
     * Get Tag type
     *
     * @return int Tag type identifier
     */
    public function getType(): int
    {
        return $this->tType;
    }
    
    /**
     * Set Tag type
     *
     * @param int $ttyp Tag type identifier
     */
    public function setType(int $ttyp)
    {
        TagType::check($ttyp);
        
        $this->tType = $ttyp;
        
        // Correct Bit address for non Bit types
        if ($this->tType != TagType::BIT) {
            $this->tBitAddress = 0;
        }
    }
    
    /**
     * Get Tag area
     *
     * @return int Tag area identifier
     */
    public function getArea(): int
    {
        return $this->tArea;
    }
    
    /**
     * Set Tag area
     *
     * @param int $tarea Tag area identifier
     */
    public function setArea(int $tarea)
    {
        TagArea::check($tarea);
        
        $this->tArea = $tarea;
    }
    
    /**
     * Get Tag byte address
     *
     * @return int Tag byte address
     */
    public function getByteAddress(): int
    {
        return $this->tByteAddress;
    }
    
    /**
     * Set Tag byte address
     *
     * @param int $byteAddr Tag byte address
     */
    public function setByteAddress(int $byteAddr)
    {
        $this->checkByteAddress($byteAddr);
        
        $this->tByteAddress = $byteAddr;
    }
    
    /**
     * Check Tag byte address
     *
     * @param int $byteAddr Tag byte address
     * @return bool True if byte address is valid
     * @throws Exception if byte address is invalid
     */
    private function checkByteAddress(int $byteAddr): bool
    {
        if ($byteAddr < 0) {
            throw new Exception('Tag byte address can not be lower than 0');
        }
        
        return true;
    }
    
    /**
     * Get Tag bit address
     *
     * @return int Tag bit address
     */
    public function getBitAddress(): int
    {
        return $this->tBitAddress;
    }
    
    /**
     * Set Tag bit address
     *
     * @param int $bitAddr Tag bit address
     */
    public function setBitAddress(int $bitAddr)
    {
        $this->checkBitAddress($bitAddr);
        
        if ($this->tType != TagType::BIT) {
            $this->tBitAddress = 0;
        } else {
            $this->tBitAddress = $bitAddr;
        }
    }
    
    /**
     * Check Tag bit address
     *
     * @param int $bitAddr Tag bit address
     * @return bool True if bit address is valid
     * @throws Exception if bit address is invalid
     */
    private function checkBitAddress(int $bitAddr): bool
    {
        if ($bitAddr < 0 || $bitAddr >= 8) {
            throw new Exception('Tag bit address is invalid');
        }
        
        return true;
    }
    
    /**
     * Get read access role name
     *
     * @return string role name
     */
    public function getReadAccess(): string
    {
        return $this->tReadAccess;
    }
    
    /**
     * Set read access role name
     *
     * @param string $nm Role name
     */
    public function setReadAccess(string $nm)
    {
        User::checkRole($nm);
        
        $this->tReadAccess = $nm;
    }
    
    /**
     * Get write access role name
     *
     * @return string role name
     */
    public function getWriteAccess(): string
    {
        return $this->tWriteAccess;
    }
    
    /**
     * Set write access role name
     *
     * @param string $nm Role name
     */
    public function setWriteAccess(string $nm)
    {
        User::checkRole($nm);
        
        $this->tWriteAccess = $nm;
    }
    
    /**
     * Check if Tag has required type
     *
     * @param int $tagType
     * @return bool
     * @throws Exception
     */
    private function checkRequiredType(int $tagType): bool
    {
        TagType::check($tagType);
            
        if ($this->tType != $tagType) {
            throw new AppException(
                "Tag (" . $this->tName . ") type is " . TagType::getName($this->tType) .
                                " but required is " . TagType::getName($tagType),
                AppException::TAG_WRONG_TYPE
            );
        }
        
        return true;
    }
    
    /**
     * Check if Tag object is valid
     *
     * @param bool $checkID Flag validating tag identifier
     * @param bool $checkType Flag validating tag type
     * @param int $tagType Tag type
     * @return bool True if Tag is valid
     * @throws Exception Throws when Tag is invalid
     */
    public function isValid(bool $checkID = false, bool $checkType = false, int $tagType = 0): bool
    {
        // Check identifier
        if ($checkID) {
            $this->checkId($this->tid);
        }
        
        // Check connection id
        DriverConnection::checkId($this->connId);
        
        // Check name
        $this->checkName($this->tName);
        
        // Check type
        TagType::check($this->tType);
        
        // Check given type
        if ($checkType) {
            $this->checkRequiredType($tagType);
        }
        
        // Check area
        TagArea::check($this->tArea);
        
        // Check Byte address
        $this->checkByteAddress($this->tByteAddress);
        
        // Check Bit address
        $this->checkBitAddress($this->tBitAddress);
        
        // Check Roles
        User::checkRole($this->tReadAccess);
        User::checkRole($this->tWriteAccess);
        
        return true;
    }
}
