/**
 * Create socket object
 * 
 * @param {type} pr Parser object
 * @param {type} trigTag Trigger tag name
 * @param {type} imgStateId State img identifier
 * @param {type} imgAlarmId Alarm img identifier
 * @param {type} imgBlockedId Blocked img identifier
 * @param {type} imgTriggerId Trigger img identifier
 * @param {type} errorId Error element id (div)
 * @returns {createSocket}
 */
function createSocket(pr, trigTag, imgStateId, imgAlarmId, imgBlockedId, imgTriggerId, errorId) {
    
    // Parser object
    var parser = pr;
    
    // Click block flag
    var clickLock = false;
    
    // Trigger tag
    var triggerTag = trigTag;
    
    // State img id
    var imgState = imgStateId;
    
    // Alarm img id
    var imgAlarm = imgAlarmId;
    
    // Blocked img id
    var imgBlocked = imgBlockedId;
    
    var imgTrigger = imgTriggerId;
    
    // Error div identifier
    var errorDivId = errorId;
    
    // State icon
    this.state = function(val) {
        if (clickLock === false) {
            if (val === '0') {
                document.getElementById(imgState).src="img/main/electricSocketOFF.png";
            } else {
                document.getElementById(imgState).src="img/main/electricSocketON.png";
            }
        }
    };
    
    // Alarm icon
    this.alarm = function(val) {
        if (val === '1') {
            document.getElementById(imgAlarm).src="img/main/alarm.png";
        } else {
            document.getElementById(imgAlarm).src="img/main/emptyIcon.png";
        }
    };
    
    // Locked icon
    this.blocked = function(blockedState, triggerState) {
        if (blockedState === '1') {
            document.getElementById(imgBlocked).src="img/main/blocked.png";
            
            // Trigger state
            if (triggerState === '1') {
                document.getElementById(imgTrigger).src="img/main/on.png";
            } else {
                document.getElementById(imgTrigger).src="img/main/off.png";
            }
        } else {
            document.getElementById(imgBlocked).src="img/main/emptyIcon.png";
            document.getElementById(imgTrigger).src="img/main/emptyIcon.png";
        }
    };
    
    // Set error message
    function setError(msg) {
        document.getElementById(errorDivId).innerHTML = msg;
    };
    
    // Return function from parser
    function parse_FB(data, status) {
                
        if (status === "success" && data.error.state === false) {
            clickLock = false;
        } else {
            if (data.error.state === true) {
                setError(data.error.msg);
            } else {
                setError('Unknown error');
            }
        }
    };
    
    // Click function
    this.Trigger = function() {
        
        // Check lock flag
        if (clickLock === false) {
            
            // Lock flag
            clickLock = true;
            
            // Bussy socket
            document.getElementById(imgState).src="img/main/electricSocketBussy.png";
            
            // Invert bit
            parser.CMD_INVERT_BIT(triggerTag, parse_FB);
        }
    };
}