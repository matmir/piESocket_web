<?php

namespace App\Tests\Entity;

use App\Entity\Paginator;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for Paginator class
 *
 * @author Mateusz Mirosławski
 */
class PaginatorTest extends TestCase
{
    /**
     * Test Default constructor
     */
    public function testDefaultConstructor()
    {
        $totalRows = 11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        
        $this->assertEquals(1, $pg->getCurrentPage());
        $this->assertEquals(2, $pg->getRowsPerPage());
        $this->assertEquals(6, $pg->getTotalPages());
        $this->assertEquals(3, $pg->getViewCount());
        $this->assertEquals('LIMIT 2 OFFSET 0', $pg->getSqlQuery());
        $this->assertEquals(array(1,2,3), $pg->getViewPages());
    }
    
    public function testDefaultConstructorWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Total rows variable need to be numeric');
        
        $totalRows = 'eleven';
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
    }
    
    public function testDefaultConstructorWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Total rows can not be less than 0');
        
        $totalRows = -11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
    }
    
    public function testDefaultConstructorWrong3()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Rows per page variable need to be numeric');
        
        $totalRows = 11;
        $rowsPerPage = 'two';
        
        $pg = new Paginator($totalRows, $rowsPerPage);
    }
    
    public function testDefaultConstructorWrong4()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Rows per page can not be less than 1');
        
        $totalRows = 11;
        $rowsPerPage = 0;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
    }
    
    /**
     * Test setCurrentPage method
     */
    public function testSetCurrentPage1()
    {
        $totalRows = 11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(2);
        
        $this->assertEquals(2, $pg->getCurrentPage());
    }
    
    public function testSetCurrentPage2()
    {
        $totalRows = 11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(50);
        
        $this->assertEquals($pg->getTotalPages(), $pg->getCurrentPage());
    }
    
    public function testSetCurrentPageWrong1()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Page variable need to be numeric');
        
        $totalRows = 11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage('two');
    }
    
    public function testSetCurrentPageWrong2()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Page can not be less than 1');
        
        $totalRows = 11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(0);
    }
    
    /**
     * Test setViewCount method
     */
    public function testSetViewCount()
    {
        $totalRows = 11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(4);
        
        $this->assertEquals(4, $pg->getViewCount());
    }
    
    public function testSetViewCountWrong()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\Exception::class);
        $this->expectExceptionMessage('Page count can not be less than 1');
        
        $totalRows = 11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(0);
    }
    
    /**
     * Test getSqlQuery method
     */
    public function testGetSqlQuery1()
    {
        $totalRows = 11;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(3);
        
        $this->assertEquals('LIMIT 2 OFFSET 4', $pg->getSqlQuery());
    }
    
    public function testGetSqlQuery2()
    {
        $totalRows = 0;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        
        $this->assertEquals('LIMIT 2 OFFSET 0', $pg->getSqlQuery());
    }
    
    /**
     * Test getViewPages method
     *
     * left border check (1,2,3)
     */
    public function testGetViewPages1()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(1);
        
        $this->assertEquals(array(1,2,3), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (1,2,3)
     */
    public function testGetViewPages2()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(2);
        
        $this->assertEquals(array(1,2,3), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (2,3,4)
     */
    public function testGetViewPages3()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(3);
        
        $this->assertEquals(array(2,3,4), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (4,5,6)
     */
    public function testGetViewPages4()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(5);
        
        $this->assertEquals(array(4,5,6), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (8,9,10)
     */
    public function testGetViewPages5()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(9);
        
        $this->assertEquals(array(8,9,10), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * right border check (9,10,11)
     */
    public function testGetViewPages6()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setCurrentPage(11);
        
        $this->assertEquals(array(9,10,11), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * left border check (1,2)
     */
    public function testGetViewPages7()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(2);
        $pg->setCurrentPage(1);
        
        $this->assertEquals(array(1,2), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (1,2)
     */
    public function testGetViewPages8()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(2);
        $pg->setCurrentPage(2);
        
        $this->assertEquals(array(1,2), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (5,6)
     */
    public function testGetViewPages9()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(2);
        $pg->setCurrentPage(6);
        
        $this->assertEquals(array(5,6), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * right border check (10,11)
     */
    public function testGetViewPages10()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(2);
        $pg->setCurrentPage(11);
        
        $this->assertEquals(array(10,11), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * left border check (1,2,3,4)
     */
    public function testGetViewPages11()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(4);
        $pg->setCurrentPage(1);
        
        $this->assertEquals(array(1,2,3,4), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (1,2,3,4)
     */
    public function testGetViewPages12()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(4);
        $pg->setCurrentPage(3);
        
        $this->assertEquals(array(1,2,3,4), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (2,3,4,5)
     */
    public function testGetViewPages13()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(4);
        $pg->setCurrentPage(4);
        
        $this->assertEquals(array(2,3,4,5), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (8,9,10,11)
     */
    public function testGetViewPages14()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(4);
        $pg->setCurrentPage(10);
        
        $this->assertEquals(array(8,9,10,11), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * right border check (8,9,10,11)
     */
    public function testGetViewPages15()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(4);
        $pg->setCurrentPage(11);
        
        $this->assertEquals(array(8,9,10,11), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * left border check (all)
     */
    public function testGetViewPages16()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(20);
        $pg->setCurrentPage(1);
        
        $this->assertEquals(array(1,2,3,4,5,6,7,8,9,10,11), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * middle border check (all)
     */
    public function testGetViewPages17()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(20);
        $pg->setCurrentPage(5);
        
        $this->assertEquals(array(1,2,3,4,5,6,7,8,9,10,11), $pg->getViewPages());
    }
    
    /**
     * Test getViewPages method
     *
     * right border check (all)
     */
    public function testGetViewPages18()
    {
        $totalRows = 21;
        $rowsPerPage = 2;
        
        $pg = new Paginator($totalRows, $rowsPerPage);
        $pg->setViewCount(20);
        $pg->setCurrentPage(11);
        
        $this->assertEquals(array(1,2,3,4,5,6,7,8,9,10,11), $pg->getViewPages());
    }
}
