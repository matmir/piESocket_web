
import {jsError} from './../jsError.js';
import {parser} from './../onh/parser/parser.js';
import {parserMultiCMD} from './../onh/parser/parserMultiCMD.js';
import {actionButton} from './../onh/components/buttons/actionButton.js';
import {alarmTable} from './../onh/components/alarm/alarmTable.js';
import {socket} from './socket.js';

// Page loaded
document.addEventListener('DOMContentLoaded', function () {
    
    let alarmStatusTid;
    let statePollingTid;
    
    // Alarm table
    let alarm = new alarmTable();
        
    // Lock buttons
    let lockS1BTN = null;
    let lockS2BTN = null;
    let lockS3BTN = null;
    let lockS4BTN = null;
    
    if (document.getElementById('S1lock')!==null) {
        lockS1BTN = new actionButton('S1lock', parser.CMD.INVERT_BIT, 'S1TriggerLock');
    }
    if (document.getElementById('S2lock')!==null) {
        lockS2BTN = new actionButton('S2lock', parser.CMD.INVERT_BIT, 'S2TriggerLock');
    }
    if (document.getElementById('S3lock')!==null) {
        lockS3BTN = new actionButton('S3lock', parser.CMD.INVERT_BIT, 'S3TriggerLock');
    }
    if (document.getElementById('S4lock')!==null) {
        lockS4BTN = new actionButton('S4lock', parser.CMD.INVERT_BIT, 'S4TriggerLock');
    }
    
    // Sockets
    let socket1 = new socket('S1Trigger', 'S1_img_state', 'S1_img_alarm', 'S1_img_blocked', 'S1_img_trigger');
    let socket2 = new socket('S2Trigger', 'S2_img_state', 'S2_img_alarm', 'S2_img_blocked', 'S2_img_trigger');
    let socket3 = new socket('S3Trigger', 'S3_img_state', 'S3_img_alarm', 'S3_img_blocked', 'S3_img_trigger');
    let socket4 = new socket('S4Trigger', 'S4_img_state', 'S4_img_alarm', 'S4_img_blocked', 'S4_img_trigger');
    
    // Multicommand
    let multiCMD = new parserMultiCMD();
    multiCMD.addCommand(parser.CMD_GET_BITS(['S1Out', 'S1Alarm', 'S1Locked', 'S1Trigger']));
    multiCMD.addCommand(parser.CMD_GET_BITS(['S2Out', 'S2Alarm', 'S2Locked', 'S2Trigger']));
    multiCMD.addCommand(parser.CMD_GET_BITS(['S3Out', 'S3Alarm', 'S3Locked', 'S3Trigger']));
    multiCMD.addCommand(parser.CMD_GET_BITS(['S4Out', 'S4Alarm', 'S4Locked', 'S4Trigger']));
    // Execute multicommand
    function multiExecute() {
        multiCMD.execute().then(
            reply => { multiReply(reply); },
            error => { jsError.add(error); }
        );
    }
    // Parse multicommand reply
    function multiReply(val) {
        let s1 = val[0];
        let s2 = val[1];
        let s3 = val[2];
        let s4 = val[3];
        
        socket1.setState(s1[0], s1[1], s1[2], s1[3]);
        socket2.setState(s2[0], s2[1], s2[2], s2[3]);
        socket3.setState(s3[0], s3[1], s3[2], s3[3]);
        socket4.setState(s4[0], s4[1], s4[2], s4[3]);
        // Setup polling timer
        repollingState();
    }
    
    // Setup polling timer
    function repollingState() {
        statePollingTid = setTimeout(multiExecute, 1000);
    }
    
    // Update alarms function
    function poolingAlarmStatus() {
        alarm.update().then(
            reply => { repoolingAlarms(); },
            error => { jsError.add(error); }
        );
    }
    // Setup alarm polling timer
    function repoolingAlarms() {
        alarmStatusTid = setTimeout(poolingAlarmStatus, 1000);
    }
    
    // First loop
    repoolingAlarms();
    multiExecute();
    
    // Click trigger button
    document.getElementById('S1_img_state').onclick = function() {
        socket1.trigger().catch(
            error => { jsError.add(error); }
        );
    };
    document.getElementById('S2_img_state').onclick = function() {
        socket2.trigger().catch(
            error => { jsError.add(error); }
        );
    };
    document.getElementById('S3_img_state').onclick = function() {
        socket3.trigger().catch(
            error => { jsError.add(error); }
        );
    };
    document.getElementById('S4_img_state').onclick = function() {
        socket4.trigger().catch(
            error => { jsError.add(error); }
        );
    };
});
