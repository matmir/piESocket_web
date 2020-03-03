<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Service\Admin\SystemSocket;
use App\Service\Admin\ConfigGeneralMapper;
use App\Service\Admin\Parser\ParserAccessRights;
use App\Service\Admin\Parser\ParserQuery;
use App\Service\Admin\Parser\ParserResponse;
use Symfony\Component\Config\Definition\Exception\Exception;

class ParserController extends AbstractController {
    
    /**
     * @Route("/parser/query", name="parser_query")
     */
    public function parserQuery(Request $request, ConfigGeneralMapper $cfg, ParserQuery $pQuery, ParserResponse $pResponse, ParserAccessRights $pAccess) {
        
        $error = array(
            'state' => false,
            'msg' => 'none',
            'code' => 0
        );
        
        $reply = array();
        
        // Get data from POST
        if ($request->request->get('json') !== null) {
            
            $data = json_decode($request->request->get('json'), true);
        
            try {
                
                // Update ack rights in parser
                $pQuery->setAckRights($cfg->getAckAccessRole());

                // Prepare query
                $qStr = $pQuery->query($data);
                
                // Check access rights
                $pAccess->check($pQuery->getAccessRights());
                
                // Prepare socket
                $socket = new SystemSocket($cfg->getSystemSocketPort());

                // Send query
                $sResponse = $socket->send($qStr);

                // Prepare reply
                $reply = $pResponse->response($sResponse);

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
