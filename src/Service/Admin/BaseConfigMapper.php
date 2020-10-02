<?php

namespace App\Service\Admin;

use Doctrine\DBAL\Driver\Connection;

/**
 * Base abstract class to read/write configuration of the system
 *
 * @author Mateusz Mirosławski
 */
abstract class BaseConfigMapper
{
    protected Connection $dbConn;
    
    public function __construct(Connection $connection)
    {
        $this->dbConn = $connection;
    }
    
    /**
     * Set server restart flag
     */
    protected function setServerRestartFlag()
    {
        $stmt = $this->dbConn->prepare("UPDATE configuration SET cValue = '1' WHERE cName = 'serverRestart';");
        $stmt->execute();
    }
    
    /**
     * Check if server application need to be restarted
     *
     * @return bool True if server application need to be restarted
     * @throws Exception
     */
    public function serverNeedRestart(): bool
    {
        $statement = $this->dbConn->prepare("SELECT * FROM configuration WHERE cName = 'serverRestart';");
        $statement->execute();
        $items = $statement->fetchAll();
        
        if (empty($items)) {
            throw new Exception("Configuration serverRestart does not exist!");
        }
        if (count($items) != 1) {
            throw new Exception("Query return more than one element!");
        }
        
        return $items[0]['cValue'];
    }
}
