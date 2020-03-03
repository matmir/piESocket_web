/**
 * Create alarm table
 * 
 * @param {type} tName          Alarm table identifier name
 * @returns {createAlarmTable}
 */
function createAlarmTable(tName) {
    this.tableName = tName;

    this.setAlarms = function(al) {

        var arrayLength = al.length;
        var htmlData = '';

        for (var i = 0; i < arrayLength; i++) {

            htmlData += "<tr>";
            htmlData += "<th scope=\"row\">" + al[i].priority + "</th>";
            htmlData += "<td>" + al[i].msg + "</td>";

            if (al[i].active) {
                htmlData += "<td>Yes</td>";
            } else {
                htmlData += "<td>No</td>";
            }

            htmlData += "<td>" + al[i].onTimestamp + "</td>";
            htmlData += "<td>" + al[i].offTimestamp + "</td>";
            htmlData += "</tr>";

        }

        document.getElementById(this.tableName).innerHTML = htmlData;

    };
}

/**
 * Create alarm status
 * 
 * @param {type} pr                         Parser object
 * @param {type} idName                     Div identifier
 * @param {type} autohide                   Autohide flag
 * @param {type} tName                      Alarm tabe identifier
 * @param {type} ackButtonID                Alarm ack button
 * @param {type} ackButtonFeedbackFunc      Alarm ack button feedback function
 * @param {type} ackButtonErrIntegrated     Alarm ack button eror integrated flag
 * @returns {createAlarmStatus}
 */
function createAlarmStatus(pr, idName='alarmStatus', autohide=true, tName='alarmBody', ackButtonID='ackAlarm', ackButtonFeedbackFunc=null, ackButtonErrIntegrated=true) {
    
    var alarmContainer = idName;
    
    var alarmAutohide = autohide;
    
    var tableAlarms = new createAlarmTable(tName);
    
    var ackButton = new createAlarmAckButton(pr, ackButtonID, ackButtonFeedbackFunc, ackButtonErrIntegrated);
    
    /**
     * Get alarm status and update alarm table
     * 
     * @param {type} feedbackFuncOK         Feedback function - request OK
     * @returns {undefined}
     */
    this.updateAlarmStatus = function(feedbackFuncOK) {
        
        $.get("/alarm/status", function(data, status){

            if (status === "success" && data.error.state === false) {

                // Show/hide table
                if (alarmAutohide === true) {
                    if (data.reply.length === 0) {
                        document.getElementById(alarmContainer).style = "display: none";
                    } else {
                        document.getElementById(alarmContainer).style = "display: block";
                    }
                } else {
                    document.getElementById(alarmContainer).style = "display: block";
                }

                tableAlarms.setAlarms(data.reply);

                // Feedback function
                feedbackFuncOK();
            }

        });
    };
    
    /**
     * Acknowledge alarms
     * 
     * @param {type} alarm_id   Alarm identifier
     * @returns {undefined}
     */
    this.ackAlarm = function(alarm_id=0) {
        
        ackButton.ack(alarm_id);
        
    };
    
}
