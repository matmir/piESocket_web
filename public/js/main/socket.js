
import {parserReadWrite} from './../onh/parser/parserReadWrite.js';

// Socket class
export class socket {
    
    /**
     * Socket constructor
     * 
     * @param {type} triggerTag Trigger tag name
     * @param {type} imgStateId State img identifier
     * @param {type} imgAlarmId Alarm img identifier
     * @param {type} imgBlockedId Blocked img identifier
     * @param {type} imgTriggerId Trigger img identifier
     * @returns {undefined}
     */
    constructor(triggerTag, imgStateId, imgAlarmId, imgBlockedId, imgTriggerId) {
        
        // Parser
        this._parser = new parserReadWrite();
        
        // Socket trigger tag
        this._triggerTag = triggerTag;
        
        // Main socket img object
        this._imgState = document.getElementById(imgStateId);
        
        // Alarm img object
        this._imgAlarm = document.getElementById(imgAlarmId);
        
        // Blocked img object
        this._imgBlocked = document.getElementById(imgBlockedId);
        
        // Trigger img object
        this._imgTrig = document.getElementById(imgTriggerId);
        
        // Lock flag
        this._lock = false;
    }
    
    /**
     * Trigger function
     * 
     * @returns {Promise}
     */
    async trigger() {
        
        try {
            this._lock = true;
            
            // Bussy socket
            this._imgState.src="img/main/electricSocketBussy.png";

            // Invert bit
            let response = await this._parser.invertBit(this._triggerTag);
            
            this._lock = false;

            return Promise.resolve(response);
            
        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Set socket state
     * 
     * @param {int} val
     */
    setState(st, alarm, blocked, trigger) {
        // State
        if (this._lock === false) {
            if (st === '0') {
                this._imgState.src="img/main/electricSocketOFF.png";
            } else {
                this._imgState.src="img/main/electricSocketON.png";
            }
        }
        // Alarm
        if (alarm === '1') {
            this._imgAlarm.src="img/main/alarm.png";
        } else {
            this._imgAlarm.src="img/main/emptyIcon.png";
        }
        // Blocked/Trigger
        if (blocked === '1') {
            this._imgBlocked.src="img/main/blocked.png";
            
            // Trigger state
            if (trigger === '1') {
                this._imgTrig.src="img/main/on.png";
            } else {
                this._imgTrig.src="img/main/off.png";
            }
        } else {
            this._imgBlocked.src="img/main/emptyIcon.png";
            this._imgTrig.src="img/main/emptyIcon.png";
        }
    }
}
