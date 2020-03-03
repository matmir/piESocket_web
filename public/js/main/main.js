$(document).ready(function(){
        
    var pr = new createParser('/parser/query');
    
    var statePoolingTid;
    
    // Buttons
    var lockS1BTN = new createActionButton(pr, 'S1lock', pr.CMD.INVERT_BIT, 'S1TriggerLock');
    var lockS2BTN = new createActionButton(pr, 'S2lock', pr.CMD.INVERT_BIT, 'S2TriggerLock');
    var lockS3BTN = new createActionButton(pr, 'S3lock', pr.CMD.INVERT_BIT, 'S3TriggerLock');
    var lockS4BTN = new createActionButton(pr, 'S4lock', pr.CMD.INVERT_BIT, 'S4TriggerLock');
    var exitBtn = new createExitButton(pr, 'exitClient');
    
    // Sockets
    var socket1 = new createSocket(pr, 'S1Trigger', 'S1_img_state', 'S1_img_alarm', 'S1_img_blocked', 'S1_img_trigger', 'S1_error');
    var socket2 = new createSocket(pr, 'S2Trigger', 'S2_img_state', 'S2_img_alarm', 'S2_img_blocked', 'S2_img_trigger', 'S2_error');
    var socket3 = new createSocket(pr, 'S3Trigger', 'S3_img_state', 'S3_img_alarm', 'S3_img_blocked', 'S3_img_trigger', 'S3_error');
    var socket4 = new createSocket(pr, 'S4Trigger', 'S4_img_state', 'S4_img_alarm', 'S4_img_blocked', 'S4_img_trigger', 'S4_error');
    
    // Multicommand
    var multiCMD = new createMultiCommand(pr, multiError);
    
    function multiExecute() {
        if (!multiCMD.isExecuting()) {
            
            var C1 = pr.m_GET_BITS(['S1Out', 'S1Alarm', 'S1Locked', 'S1Trigger']);
            var C2 = pr.m_GET_BITS(['S2Out', 'S2Alarm', 'S2Locked', 'S2Trigger']);
            var C3 = pr.m_GET_BITS(['S3Out', 'S3Alarm', 'S3Locked', 'S3Trigger']);
            var C4 = pr.m_GET_BITS(['S4Out', 'S4Alarm', 'S4Locked', 'S4Trigger']);

            multiCMD.addCommand(C1, multiGetC1);
            multiCMD.addCommand(C2, multiGetC2);
            multiCMD.addCommand(C3, multiGetC3);
            multiCMD.addCommand(C4, multiGetC4);
            multiCMD.Execute();
        }
    }
    
    function poolingState() {
        // Update state
        multiExecute();
    }
    
    function repoolingState() {
        statePoolingTid = setTimeout(poolingState, 1000);
    }
    
    function multiError(msg) {
        document.getElementById("mError").innerHTML = msg;
    }
    
    poolingState();
    
    // Multicommand feedback functions
    function multiGetC1(val) {
        // State
        socket1.state(val[0]);
        // Alarm
        socket1.alarm(val[1]);
        // Blocked
        socket1.blocked(val[2], val[3]);
    }
    function multiGetC2(val) {
        // State
        socket2.state(val[0]);
        // Alarm
        socket2.alarm(val[1]);
        // Blocked
        socket2.blocked(val[2], val[3]);
    }
    function multiGetC3(val) {
        // State
        socket3.state(val[0]);
        // Alarm
        socket3.alarm(val[1]);
        // Blocked
        socket3.blocked(val[2], val[3]);
    }
    function multiGetC4(val) {
        // State
        socket4.state(val[0]);
        // Alarm
        socket4.alarm(val[1]);
        // Blocked
        socket4.blocked(val[2], val[3]);
        
        repoolingState();
    }
    
    // Click trigger button
    $("#S1_img_state").click(function(){
        socket1.Trigger();
    });
    $("#S2_img_state").click(function(){
        socket2.Trigger();
    });
    $("#S3_img_state").click(function(){
        socket3.Trigger();
    });
    $("#S4_img_state").click(function(){
        socket4.Trigger();
    });
    
    // Click lock button
    $("#S1lock").click(function(){
        lockS1BTN.Execute();
    });
    $("#S2lock").click(function(){
        lockS2BTN.Execute();
    });
    $("#S3lock").click(function(){
        lockS3BTN.Execute();
    });
    $("#S4lock").click(function(){
        lockS4BTN.Execute();
    });
    $("#exitClient").click(function(){
        exitBtn.Execute();
    });
    
});
