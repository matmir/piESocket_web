<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;

use App\Service\Admin\ChartDataReader;

class ChartController extends AbstractController {
    
    /**
     * @Route("/admin/chart/show/{loggerID}", name="admin_chart_show")
     */
    public function index(int $loggerID = 0) {
        
        // Check values
        if ($loggerID < 0) {
            $loggerID = 0;
        }
        
        return $this->render('admin/chartShow.html.twig', array(
            'loggerID' => $loggerID
        ));
    }
    
    /**
     * @Route("/admin/chart/get", name="admin_chart_get")
     */
    public function data(Request $request, ChartDataReader $dataReader) {
        
        $error = array(
            'state' => false,
            'msg' => 'none',
            'code' => 0
        );
        
        $reply = array();
        
        // Check request
        if ($request->request->get('json') !== null) {
            
            // Get data from POST
            $data = json_decode($request->request->get('json'), true);
            
            try {
                
                // Get chart data
                $reply = $dataReader->getData($data);
                
            } catch (Exception $ex) {
                $error['state'] = true;
                $error['msg'] = $ex->getMessage();
                $error['code'] = $ex->getCode();
            }
            
        }
        
        return $this->json(array(
            'error' => $error,
            'reply' => $reply
        ));
    }
}
