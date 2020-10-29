
// Restart badge class
export class restartBadge {
    
    /**
     * Restart badge constructor
     * 
     * @param {string} badgeId Restart badge identifier
     */
    constructor(badgeId) {
        
        // Restart badge
        this._badge = document.getElementById(badgeId);
        
        // Restart badge state
        this._state = false;
    }
    
    /**
     * Set badge state
     * 
     * @param {bool} st Badge state
     */
    setState(st) {
        
        this._state = st;
        
        if (this._state===true) {
            this._badge.style.display = "inline-block";
        } else {
            this._badge.style.display = "none";
        }
    }
}
