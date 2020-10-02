<?php

namespace App\Entity;

use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Simple Paginator class
 *
 * @author Mateusz Mirosławski
 */
class Paginator
{
    /**
     * Total number of rows in DB
     */
    private int $totalRows;
    
    /**
     * Number of rows per page
     */
    private int $rowsPerPage;
    
    /**
     * Current page
     */
    private int $currentPage;
    
    /**
     * Total number of pages
     */
    private int $totalPages;
    
    /**
     * Number of button with pages to display
     */
    private int $viewCount;
    
    /**
     * Default constructor
     *
     * @param int $totalRows Total number of rows
     * @param int $rowsPerPage Number of rows per page
     * @throws Exception Wrong parameters
     */
    public function __construct(int $totalRows, int $rowsPerPage)
    {
        if ($totalRows < 0) {
            throw new Exception('Total rows can not be less than 0');
        }
        if ($rowsPerPage < 1) {
            throw new Exception('Rows per page can not be less than 1');
        }
        
        $this->totalRows = $totalRows;
        $this->rowsPerPage = $rowsPerPage;
        
        // Calculate total number of pages
        $this->totalPages = ceil($this->totalRows / $this->rowsPerPage);
        
        $this->currentPage = 1;
        
        $this->viewCount = 3;
    }
    
    /**
     * Get current page
     *
     * @return int Current page
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }
    
    /**
     * Set current page number
     *
     * @param int $page Page number
     * @throws Exception Wrong parameters
     */
    public function setCurrentPage(int $page)
    {
        if ($page <= 0) {
            throw new Exception('Page can not be less than 1');
        }
        
        if ($page > $this->totalPages) {
            $page = $this->totalPages;
        }
        
        $this->currentPage = $page;
    }
    
    /**
     * Get Page buttons count
     *
     * @return int Page buttons count
     */
    public function getViewCount(): int
    {
        return $this->viewCount;
    }
    
    /**
     * Set Page button count
     *
     * @param int $vCount Page buttons count
     */
    public function setViewCount(int $vCount)
    {
        if ($vCount <= 0) {
            throw new Exception('Page count can not be less than 1');
        }
        
        $this->viewCount = $vCount;
    }
    
    /**
     * Get total number of pages
     *
     * @return int Total number of pages
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
    
    /**
     * Get rows per page
     *
     * @return int Rows per page
     */
    public function getRowsPerPage(): int
    {
        return $this->rowsPerPage;
    }
    
    /**
     * Get sql query contains LIMIT and OFFSET values
     *
     * @return string Query contains LIMIT and OFFSET values
     */
    public function getSqlQuery(): string
    {
        // Prepare limit
        $limitQuery = "LIMIT " . $this->rowsPerPage;
        
        if ($this->totalPages == 0) {
            $offset = 0;
        } else {
            $offset = ($this->currentPage - 1) * $this->rowsPerPage;
        }
        
        $offsetQuery = "OFFSET " . $offset;
        
        return $limitQuery . " " . $offsetQuery;
    }
    
    /**
     * Get page numbers to show
     *
     * @return array Page numbers to show
     */
    public function getViewPages(): array
    {
        $ret = array();
        
        $middle = floor($this->viewCount / 2);
        
        // Check pages to print
        $ptp = ($this->totalPages < $this->viewCount) ? ($this->totalPages) : ($this->viewCount);
        
        // Low limit
        if ($this->currentPage <= $middle) {
            for ($i = 1; $i <= $ptp; ++$i) {
                array_push($ret, $i);
            }
        } elseif ($this->currentPage > $middle && $this->currentPage <= ($this->totalPages - $middle)) {
            // Middle
            for ($i = 0; $i < $ptp; ++$i) {
                array_push($ret, $i + ($this->currentPage - $middle));
            }
        } elseif ($this->currentPage > $middle && $this->currentPage > ($this->totalPages - $middle)) {
            // Max limit
            for ($i = $this->totalPages - $ptp; $i < $this->totalPages; ++$i) {
                array_push($ret, $i + 1);
            }
        }
        
        return $ret;
    }
}
