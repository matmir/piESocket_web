<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Form;

use App\Entity\Paginator;
use App\Entity\AppException;
use App\Service\Admin\AlarmMapper;
use App\Service\Admin\TagsMapper;
use App\Form\Admin\AlarmForm;
use App\Entity\Admin\AlarmEntity;
use App\Entity\Admin\Tag;
use App\Entity\Admin\TagType;

class AlarmController extends AbstractController {
    
    /**
     * Check alarms list router parameters
     * 
     * @param int $page Page number
     * @param int $perPage Rows per page
     * @param int $area Tag area (0 - all, 1 - input, 2 - output, 3 - memory)
     * @param int $sort Alarm sorting (0 - ID, 1 - tag name, 2 - priority, 3 - trigger type,
     *                                 4 - auto ack flag, 5 - active flag, 6 - pending flag, 7 - enable flag)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     */
    private function checkParams(int &$page, int &$perPage, int &$area, int &$sort, int &$sortDESC) {
        
        // Check page params
        if ($page <= 0) {
            $page = 1;
        }
        if ($perPage <10) {
            $perPage = 10;
        }
        
        // Check area (0 - all, 1 - input, 2 - output, 3 - memory)
        if ($area < 0 || $area > 3) {
            $area = 0;
        }
        
        // Check sort (0 - ID, 1 - tag name, 2 - priority, 3 - trigger type,
        //             4 - auto ack flag, 5 - active flag, 6 - pending flag, 7 - enable flag)
        if ($sort < 0 || $sort > 7) {
            $sort = 0;
        }
        
        // Check sort direction (0 - ASC, 1 - DESC)
        if ($sortDESC < 0 || $sortDESC > 1) {
            $sortDESC = 0;
        }
        
    }
    
    /**
     * @Route("/admin/alarm/list/{page}/{perPage}/{area}/{sort}/{sortDESC}", name="admin_alarm_list")
     */
    public function index(AlarmMapper $alarmMapper, Request $request, $page=1, $perPage=20, $area=0, $sort=0, $sortDESC=0) {
        
        // Check parameters
        $this->checkParams($page, $perPage, $area, $sort, $sortDESC);
        
        // Get number of all alarms
        $alarmsCnt = $alarmMapper->getAlarmsCount($area);
        
        // Paginator
        $paginator = new Paginator($alarmsCnt, $perPage);
        $paginator->setCurrentPage($page);
        
        // Get all alarms
        $alarms = $alarmMapper->getAlarms($area, $sort, $sortDESC, $paginator);
        
        // Store current url in session variable
        $this->get('session')->set('AlarmsListURL', $request->getUri());
        
        return $this->render('admin/alarm/alarmList.html.twig', array(
            'alarms' => $alarms,
            'paginator' => $paginator
        ));
    }
    
    /**
     * Parse Alarm exception
     * 
     * @param $errorObj Error object
     * @param Form $form Form object
     */
    private function parseAlarmError($errorObj, Form $form) {
        
        $code = $errorObj->getCode();
        
        if ($errorObj instanceof AppException) {
            
            if ($code == AppException::ALARM_TAG_EXIST || $code == AppException::TAG_NOT_EXIST) {
                
                // Add error
                $form->get('adTagName')->addError(new FormError($errorObj->getMessage()));
                
            } else if ($code == AppException::ALARM_TRIGGER_WRONG_TYPE) {
                
                // Add error
                $form->get('adTrigger')->addError(new FormError($errorObj->getMessage()));
                
            } else {
                
                // Unknown error
                $form->get('adTagName')->addError(new FormError('Unknown exception!'));
                
            }
            
        }
    }
    
    /**
     * Prepare Tag type
     * 
     * @param Tag $tag Tag object
     * @return string Tag type (Bit/Real/Numeric)
     */
    private function prepareTagType(Tag $tag): string {
        
        $type = '';
        
        if ($tag->getType() == TagType::Bit) {
            $type='Bit';
        } else if ($tag->getType() == TagType::REAL) {
            $type='Real';
        } else {
            $type='Numeric';
        }
        
        return $type;
    }
    
    /**
     * @Route("/admin/alarm/add", name="admin_alarm_add")
     */
    public function add(AlarmMapper $alarmMapper, TagsMapper $tagsMapper, Request $request) {
                
        $alarmE = new AlarmEntity();
        
        $form = $this->createForm(AlarmForm::class, $alarmE);
        
        $form->handleRequest($request);
        
        $tagType = 'Bit';

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Get Form data
            $alarmE = $form->getData();
            
            try {
                
                // Get tag object
                $tag = $tagsMapper->getTagByName($alarmE->getadTagName());
                
                // Tag type
                $tagType = $this->prepareTagType($tag);
                
                $tagFB = null;
                
                // Feedback tag
                if (trim($alarmE->getadFeedbackNotACK())!='') {
                    $tagFB = $tagsMapper->getTagByName($alarmE->getadFeedbackNotACK());
                }
                
                $tagHW = null;
                
                // HW tag
                if (trim($alarmE->getadHWAck())!='') {
                    $tagHW = $tagsMapper->getTagByName($alarmE->getadHWAck());
                }
                
                // Get real Alarm object
                $alarm = $alarmE->getFullAlarmObject($tag, $tagFB, $tagHW);
                
                // Add to the DB
                $alarmMapper->addAlarm($alarm);
                
                $this->addFlash(
                    'alarm-msg-ok',
                    'New Alarm was saved!'
                );
                
                // Get last Tags list url
                $lastUrl = $this->get('session')->get('AlarmListURL', $this->generateUrl('admin_alarm_list'));

                return $this->redirect($lastUrl);
                
            } catch (AppException $ex) {
                
                $this->parseAlarmError($ex, $form);
                
            }
            
        }
        
        return $this->render('admin/alarm/alarmAdd.html.twig', array(
            'form' => $form->createView(),
            'tagType' => $tagType
        ));
    }
    
    /**
     * @Route("/admin/alarm/edit/{alarmID}", name="admin_alarm_edit")
     */
    public function edit($alarmID, AlarmMapper $alarmMapper, TagsMapper $tagsMapper, Request $request) {
                
        // Get alarm from DB
        $alarm = $alarmMapper->getAlarm($alarmID);
        
        $alarmE = new AlarmEntity();
        $alarmE->initFromAlarmObject($alarm);
        
        $form = $this->createForm(AlarmForm::class, $alarmE);
        
        $form->handleRequest($request);
        
        $tagType = $this->prepareTagType($alarm->getTag());

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Get Form data
            $alarmE = $form->getData();
            
            try {
                
                // Get tag object
                $tag = $tagsMapper->getTagByName($alarmE->getadTagName());
                
                // Tag type
                $tagType = $this->prepareTagType($tag);
                
                $tagFB = null;
                
                // Feedback tag
                if (trim($alarmE->getadFeedbackNotACK())!='') {
                    $tagFB = $tagsMapper->getTagByName($alarmE->getadFeedbackNotACK());
                }
                
                $tagHW = null;
                //TODO: Add checkbox in form (yes/no)?
                // HW tag
                if (trim($alarmE->getadHWAck())!='') {
                    $tagHW = $tagsMapper->getTagByName($alarmE->getadHWAck());
                }
                
                // Get real Alarm object
                $alarm = $alarmE->getFullAlarmObject($tag, $tagFB, $tagHW);
                
                // Add to the DB
                $alarmMapper->editAlarm($alarm);
                
                $this->addFlash(
                    'alarm-msg-ok',
                    'New Alarm was saved!'
                );
                
                // Get last Tags list url
                $lastUrl = $this->get('session')->get('AlarmListURL', $this->generateUrl('admin_alarm_list'));

                return $this->redirect($lastUrl);
                
            } catch (AppException $ex) {
                
                $this->parseAlarmError($ex, $form);
                
            }
            
        }
        
        return $this->render('admin/alarm/alarmEdit.html.twig', array(
            'form' => $form->createView(),
            'tagType' => $tagType
        ));
    }
    
    /**
     * @Route("/admin/alarm/delete/{alarmID}", name="admin_alarm_delete")
     */
    public function delete($alarmID, AlarmMapper $alarmMapper) {
        
        // Delete alarm
        $alarmMapper->deleteAlarm($alarmID);

        $this->addFlash(
            'alarm-msg-ok',
            'Alarm was deleted!'
        );
        
        // Get last Alarm list url
        $lastUrl = $this->get('session')->get('AlarmListURL', $this->generateUrl('admin_alarm_list'));

        return $this->redirect($lastUrl);
    }
    
    /**
     * @Route("/admin/alarm/enable/{alarmID}/{en}", name="admin_alarm_enable")
     */
    public function enable($alarmID, $en, AlarmMapper $alarmMapper) {
        
        if ($en < 0 || $en > 1) {
            $en = 0;
        }
        
        // Enable alarm
        $alarmMapper->enableAlarm($alarmID, $en);
        
        // Get last Alarm list url
        $lastUrl = $this->get('session')->get('AlarmListURL', $this->generateUrl('admin_alarm_list'));

        return $this->redirect($lastUrl);
    }
    
    /**
     * @Route("/admin/alarm/active/", name="admin_alarm_active")
     */
    public function active(AlarmMapper $alarmMapper) {
        
        // Get pending alarms
        $alarms = $alarmMapper->getPendingAlarms();
        
        return $this->render('admin/alarm/alarmActive.html.twig', array(
            'alarms' => $alarms
        ));
    }
    
    /**
     * @Route("/alarm/status/", name="alarm_status")
     */
    public function status(AlarmMapper $alarmMapper) {
        
        $error = array(
            'state' => false,
            'msg' => 'none',
            'code' => 0
        );
        
        $reply = array();
        
        try {
            
            // Get pending alarms
            $alarms = $alarmMapper->getPendingAlarms();
            
            // Prepare data
            for ($i=0; $i<count($alarms); ++$i) {
                
                $reply[$i] = array(
                    'priority' => $alarms[$i]->getPriority(),
                    'msg' => $alarms[$i]->getMessage(),
                    'active' => $alarms[$i]->isActive(),
                    'onTimestamp' => $alarms[$i]->getOnTimestamp(),
                    'offTimestamp' => ($alarms[$i]->isOffTimestamp())?($alarms[$i]->getOffTimestamp()):('none'),
                );
                
            }
            
        } catch (Exception $ex) {
            $error['state'] = true;
            $error['msg'] = $ex->getMessage();
            $error['code'] = $ex->getCode();
        }
        
        return $this->json(array(
            'error' => $error,
            'reply' => $reply
        ));
    }
    
    /**
     * Check archived alarms router parameters
     * 
     * @param int $page Page number
     * @param int $perPage Rows per page
     * @param int $sort Alarm sorting (0 - ID, 1 - priority, 2 - on time, 3 - off time, 4 - ack time)
     * @param int $sortDESC Sorting direction (0 - ASC, 1 - DESC)
     */
    private function checkArchivedParams(int &$page, int &$perPage, int &$sort, int &$sortDESC) {
        
        // Check page params
        if ($page <= 0) {
            $page = 1;
        }
        if ($perPage <10) {
            $perPage = 10;
        }
        
        // Check sort (0 - ID, 1 - priority, 2 - on time, 3 - off time, 4 - ack time)
        if ($sort < 0 || $sort > 4) {
            $sort = 0;
        }
        
        // Check sort direction (0 - ASC, 1 - DESC)
        if ($sortDESC < 0 || $sortDESC > 1) {
            $sortDESC = 0;
        }
    }
    
    /**
     * @Route("/admin/alarm/archived/{page}/{perPage}/{sort}/{sortDESC}", name="admin_alarm_archived")
     */
    public function archived(AlarmMapper $alarmMapper, Request $request, $page=1, $perPage=20, $sort=0, $sortDESC=1) {
        
        // Check parameters
        $this->checkArchivedParams($page, $perPage, $sort, $sortDESC);
        
        // Get number of all archived alarms
        $alarmsCnt = $alarmMapper->getArchivedAlarmsCount();
        
        // Paginator
        $paginator = new Paginator($alarmsCnt, $perPage);
        $paginator->setCurrentPage($page);
        
        // Get all archived alarms
        $alarms = $alarmMapper->getArchivedAlarms($sort, $sortDESC, $paginator);
        
        // Store current url in session variable
        $this->get('session')->set('ArchivedAlarmsURL', $request->getUri());
        
        return $this->render('admin/alarm/alarmArchived.html.twig', array(
            'alarms' => $alarms,
            'paginator' => $paginator
        ));
    }
    
    /**
     * @Route("/admin/alarm/clearArchived/", name="admin_alarm_archived_clear")
     */
    public function clearArchived(AlarmMapper $alarmMapper) {
        
        // Delete archived alarms
        $alarmMapper->deleteArchivedAlarm();

        $this->addFlash(
            'alarm-archive-msg-ok',
            'Alarm history cleared!'
        );
        
        return $this->redirect($this->generateUrl('admin_alarm_archived'));
    }
}
