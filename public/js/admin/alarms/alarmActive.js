
import {jsError} from './../../jsError.js';
import {alarmTable} from './../../onh/components/alarm/alarmTable.js';

// Page loaded
document.addEventListener('DOMContentLoaded', function () {
        
    let alarm = new alarmTable(false);
    let alarmStatusTid;
    
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
    
    poolingAlarmStatus();
});