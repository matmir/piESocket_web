
import {parser} from './../../parser/parser.js';
import {parserReadWrite} from './../../parser/parserReadWrite.js';
import {button} from './button.js';

// Alarm ack button class
export class alarmAckButton extends button {
    
    /**
     * Alarm ack button constructor
     * 
     * @param {string} bId Button identifier
     * @param {object} returnOK Button feedback return function
     * @param {object} returnNOK Button feedback error function
     * @param {string} normalClass Button normal style class
     * @param {string} errorClass Button error style class
     */
    constructor(
            bId,
            returnOK = null,
            returnNOK = null,
            normalClass = "btn btn-success",
            errorClass = "btn btn-danger")
    {
        
        // Call base constructor
        super(bId, normalClass, errorClass);
        
        // Create parser
        this._parser = new parserReadWrite();
        
        // Ack all alarms
        this._alarmId = 0;
        
        // Feedback functions
        this._returnOK = returnOK;
        this._returnNOK = returnNOK;
        
        // Attach click function
        this._button.onclick = this.click;
    }
    
    /**
     * Button internal onClick function
     */
    click = () => {
        (async () => {
            
            try {
                // Disable button
                this.disable();
                
                // Execute button function
                let res = await this._parser.ackAlarm(this._alarmId);
                
                // Enable button
                this.enable();
                
                // Check feedback function
                if (this._returnOK !== null) {
                    // Call external function
                    this._returnOK(res);
                }
                
            } catch (err) {
                // Check error function
                if (this._returnNOK === null) {
                    // Set internal button error
                    this.setError(err);
                } else {
                    // Call external function
                    this._returnNOK(err);
                }
            }
            
        })();
    }
}
