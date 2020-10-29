
import {button} from './../../onh/components/buttons/button.js';

// Two state button class
export class twoStateButton extends button {
    
    /**
     * Two state button constructor
     * 
     * @param {string} bId Button identifier
     * @param {string} onTxt Button on text
     * @param {string} offTxt Button off text
     * @param {string} scriptPath On/off script path
     * @param {bool} enableAfterState Enable button after set state
     * @param {string} onClass Button on style class
     * @param {string} offClass Button off style class
     */
    constructor(
            bId,
            onTxt,
            offTxt,
            scriptPath,
            enableAfterState = true,
            onClass = "btn btn-success",
            offClass = "btn btn-danger")
    {
        // Call base constructor
        super(bId, onClass, offClass);
        
        // State flag
        this._state = false;
        
        // Button texts
        this._onTxt = onTxt;
        this._offTxt = offTxt;
        
        // Enable after set state flag
        this._enAfterState = enableAfterState;
        
        // On click function
        this._button.onclick = this.click;
        
        // Script path
        this._scriptPath = scriptPath;
        
        // Button lock flag
        this._lock = false;
    }
    
    /**
     * Button internal onClick function
     */
    click = () => {
        (async () => {
            
            try {
                this._lock = true;
                
                // Disable button
                this.disable();
                
                // Trigger flag
                let flag = (this._state===true) ? (0) : (1);
                
                // Change state
                let res = await fetch(this._scriptPath+flag, { method: 'get' });
                let data = await res.json();

                if (data.error.state === false) {
                    
                    if (this._enAfterState === false) {
                        // Enable button
                        this.enable();
                    }

                } else {
                    throw new Error(data.error.msg);
                }
                
                this._lock = false;
                
            } catch (err) {
                // Set internal button error
                this.setError(err);
            }
        })();
    }
    
    /**
     * Set state
     * 
     * @param {bool} st State flag
     */
    setState(st) {
        
        if (this._lock === false) {
            this._state = st;
            this._updateBtn();
        }
    }
    
    /**
     * Get lock flag
     * 
     * @returns {Boolean}
     */
    isLocked() {
        return this._lock;
    }
    
    /**
     * Update button
     */
    _updateBtn() {
        // Update autoload button
        if (this._state===true) {
            this._button.className = this._errorClass;
            this.setText(this._offTxt);
        } else {
            this._button.className = this._normalClass;
            this.setText(this._onTxt);
        }
        // Enable button
        if (this._enAfterState === true) {
            // Enable button
            this.enable();
        }
    }
}
