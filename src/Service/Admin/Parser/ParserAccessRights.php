<?php

namespace App\Service\Admin\Parser;

use Symfony\Component\Security\Core\Security;
use App\Service\Admin\TagsMapper;
use Symfony\Component\Config\Definition\Exception\Exception;
use App\Entity\AppException;

/**
 * Parser access rights checker - Class for checking access rights to query
 *
 * @author Mateusz Mirosławski
 */
class ParserAccessRights
{
    private Security $security;
    
    private TagsMapper $tagsMapper;
    
    /**
     * Default constructor
     */
    public function __construct(Security $sec, TagsMapper $tagsMapper)
    {
        $this->security = $sec;
        $this->tagsMapper = $tagsMapper;
    }
    
    /**
     * Check special function rights
     *
     * @param array $accessRightItem Array with access right to one query
     * @return bool true if special function occurred
     * @throws AppException
     */
    private function checkSpecialFunction(array $accessRightItem): bool
    {
        $ret = false;
        
        // Check array keys
        if (array_key_exists('specialFunc', $accessRightItem)) {
            if (!array_key_exists('role', $accessRightItem)) {
                throw new Exception('Access right array missing role field');
            }
            
            // Check 'specialFunc' value
            if (trim($accessRightItem['specialFunc']) == false) {
                throw new Exception('Access right array wrong specialFunc value');
            }
            
            // Admin can do everything
            if (!$this->security->isGranted('ROLE_ADMIN')) {
                // Check GUEST access
                if ($accessRightItem['role'] != 'ROLE_GUEST') {
                    // Check USER access
                    if (!$this->security->isGranted($accessRightItem['role'])) {
                        throw new AppException('Calling ' . $accessRightItem['specialFunc'] .
                                            ' is forbidden', AppException::SPECIAL_FUNCTION_DENIED);
                    }
                }
            }
            
            $ret = true;
        }
        
        return $ret;
    }
    
    /**
     * Check tag access rights
     *
     * @param array $accessRightItem Array with access right to one query
     * @throws AppException
     */
    private function checkTagAccess(array $accessRightItem)
    {
        // Check array keys
        if (!array_key_exists('tagName', $accessRightItem)) {
            throw new Exception('Tag access array missing tagName field');
        }
        if (!array_key_exists('read', $accessRightItem)) {
            throw new Exception('Tag access array missing read flag');
        }

        // Get tag data
        $tag = $this->tagsMapper->getTagByName($accessRightItem['tagName']);

        // Admin can read and write
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            // Query wants read tag
            if ($accessRightItem['read']) {
                // Check GUEST access
                if ($tag->getReadAccess() != 'ROLE_GUEST') {
                    // Check USER access
                    if (!$this->security->isGranted($tag->getReadAccess())) {
                        throw new AppException('Reading from ' . $tag->getName() .
                                            ' is forbidden', AppException::TAG_READ_ACCESS_DENIED);
                    }
                }
            } else { // Query wants write tag
                // Check ALL access
                if ($tag->getWriteAccess() != 'ROLE_GUEST') {
                    // Check USER access
                    if (!$this->security->isGranted($tag->getWriteAccess())) {
                        throw new AppException('Writting to ' . $tag->getName() .
                                            ' is forbidden', AppException::TAG_WRITE_ACCESS_DENIED);
                    }
                }
            }
        }
    }
    
    /**
     * Check access rights
     *
     * @param array $accessRightsArray Array with access rights to query
     * @throws AppException
     */
    public function check(array $accessRightsArray)
    {
        // Check array
        for ($i = 0; $i < count($accessRightsArray); ++$i) {
            // Check special function
            if (!$this->checkSpecialFunction($accessRightsArray[$i])) {
                // Check Tag access
                $this->checkTagAccess($accessRightsArray[$i]);
            }
        }
    }
}
