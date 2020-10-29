
import {parser} from './../../parser/parser.js';
import {parserReadWrite} from './../../parser/parserReadWrite.js';
import {button} from './button.js';

// Exit button class
export class exitButton extends button {
    
    /**
     * Exit button constructor
     * 
     * @param {string} bId Button identifier
     * @param {bool} autoEnable Button auto enable after click flag
     * @param {object} returnOK Button feedback return function
     * @param {object} returnNOK Button feedback error function
     * @param {string} normalClass Button normal style class
     * @param {string} errorClass Button error style class
     */
    constructor(
            bId,
            autoEnable = true,
            returnOK = null,
            returnNOK = null,
            normalClass = "btn btn-success",
            errorClass = "btn btn-danger")
    {
        
        // Call base constructor
        super(bId, normalClass, errorClass);
        
        // Create parser
        this._parser = new parserReadWrite();
        
        // Auto enable
        this._autoEnable = autoEnable;
        
        // Feedback functions
        this._returnOK = returnOK;
        this._returnNOK = returnNOK;
        
        // Lock flag
        this._lock = false;
        
        // Attach click function
        this._button.onclick = this.click;
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
            
                // Execute button function
                let res = await this._parser.exitService();
                
                // Enable button
                if (this._autoEnable) {
                    this.enable();
                }
                
                // Check feedback function
                if (this._returnOK !== null) {
                    // Call external function
                    this._returnOK(res);
                }
                
                this._lock = false;
                
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
    
    /**
     * Get lock flag
     * 
     * @returns {Boolean}
     */
    isLocked() {
        return this._lock;
    }
    
    /**
     * Enable button
     */
    enable() {
        if (this._lock === false || this._autoEnable === true) {
            super.enable();
        }
    };
}
