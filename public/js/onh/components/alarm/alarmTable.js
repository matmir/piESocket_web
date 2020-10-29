
import {alarmAckButton} from './../buttons/alarmAckButton.js';

// Alarm table class
export class alarmTable {
    
    /**
     * Alarm table constructor
     * 
     * @param {bool} autohide Alarm component autohide flag
     * @param {object} ackFeedbackOK Ack button feedback function
     * @param {object} ackFeedbackNOK Ack button feedback error function
     * @param {string} containerId Alarm container itentifier
     * @param {string} tableId Alarm table itentifier
     * @param {string} ackButtonId Alarm ack button identifier
     * @param {string} statusScript Alarm status script path
     */
    constructor(
            autohide = true,
            ackFeedbackOK = null,
            ackFeedbackNOK = null,
            containerId = 'alarmStatus',
            tableId = 'alarmBody',
            ackButtonId = 'ackAlarm',
            statusScript = '/alarm/status'
    ) {
        
        // Alarm container
        this._container = document.getElementById(containerId);
        
        // Alarm table
        this._table = document.getElementById(tableId);
        
        // Ack button
        this._ackBtn = new alarmAckButton(ackButtonId, ackFeedbackOK, ackFeedbackNOK);
        
        // Alarm status script
        this._status = statusScript;
        
        // Autohide option
        this._autohide = autohide;
    }
    
    /**
     * Set alarms in the table
     * 
     * @param {array} alarms Array with alarms
     */
    _setAlarms(alarms) {
        
        let htmlData = '';

        for (let i = 0; i < alarms.length; i++) {

            htmlData += "<tr>";
            htmlData += "<th scope=\"row\">" + alarms[i].priority + "</th>";
            htmlData += "<td>" + alarms[i].msg + "</td>";

            if (alarms[i].active) {
                htmlData += "<td>Yes</td>";
            } else {
                htmlData += "<td>No</td>";
            }

            htmlData += "<td>" + alarms[i].onTimestamp + "</td>";
            htmlData += "<td>" + alarms[i].offTimestamp + "</td>";
            htmlData += "</tr>";
        }

        // Add alarms to the table
        this._table.innerHTML = htmlData;
    }
    
    /**
     * Update alarm table
     * 
     * @returns {Promise}
     */
    async update() {
        
        try {
            // Get alarm status
            let res = await fetch(this._status, { method: 'get' });
            let data = await res.json();

            if (data.error.state === false) {
                // Show/hide table
                if (this._autohide === true) {
                    
                    if (data.reply.length === 0) {
                        this._container.style = "display: none";
                    } else {
                        this._container.style = "display: block";
                    }
                } else {
                    this._container.style = "display: block";
                }

                // Update table
                this._setAlarms(data.reply);
            } else {
                throw new Error(data.error.msg);
            }
            
            return Promise.resolve(true);

        } catch (err) {
            return Promise.reject(err);
        }
    }
}
