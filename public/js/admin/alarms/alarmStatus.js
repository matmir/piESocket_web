$(document).ready(function(){
    
    var alarmParser = new createParser('/parser/query');
    var alarmStatusTid;
    
    var alarmStatus = new createAlarmStatus(alarmParser);
    
    function poolingAlarmStatus() {

        // Update alarm status
        alarmStatus.updateAlarmStatus(repoolingAlarms);

    }
    
    function repoolingAlarms() {
        
        alarmStatusTid = setTimeout(poolingAlarmStatus, 1000);
        
    }
    
    poolingAlarmStatus();
    
    $("#ackAlarm").click(function(){
        alarmStatus.ackAlarm();
    });
});