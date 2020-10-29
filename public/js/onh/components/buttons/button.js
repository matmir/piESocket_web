
// Base button class
export class button {
    
    /**
     * Button constructor
     * 
     * @param {string} bId Button identifier
     * @param {string} normalClass Button normal (no error) style class
     * @param {string} errorClass Button error style class
     */
    constructor(bId, normalClass = "btn btn-success", errorClass = "btn btn-danger") {
        
        // Get button
        this._button = document.getElementById(bId);
        
        // Button style
        this._normalClass = normalClass;
        this._errorClass = errorClass;
    }
    
    /**
     * Hide button
     */
    hide() {
        this._button.style.display = "none";
    }
    
    /**
     * Show button
     */
    show() {
        this._button.style.display = "inline-block";
    }
    
    /**
     * Disable button
     */
    disable() {
        this._button.disabled = true;
    };
    
    /**
     * Enable button
     */
    enable() {
        this._button.disabled = false;
    };
    
    /**
     * Set button error message
     * 
     * @param {string} errorMsg Error message
     */
    setError(errorMsg) {
        this._button.className = this._errorClass;
        this._button.title = errorMsg;
    }
    
    /**
     * Clear button error message
     */
    clearError() {
        this._button.className = this._normalClass;
        this._button.title = "";
    }
    
    /**
     * Set button text
     * 
     * @param {string} txt Button text
     */
    setText(txt) {
        this._button.innerHTML = txt;
    }
}
