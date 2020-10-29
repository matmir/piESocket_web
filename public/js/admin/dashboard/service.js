
// Service class
export class service {
    
    /**
     * Service constructor
     * 
     * @param {string} serviceId Service identifier
     */
    constructor(serviceId) {
        
        // Service
        this._service = document.getElementById(serviceId);
        
        // Service state
        this._state = 'inactive';
    }
    
    /**
     * Set service state
     * 
     * @param {string} st Service state
     */
    setState(st) {
        
        this._state = st;
        
        this._service.innerHTML = this._state;
        
        if (this._state === "active") {
            this._service.className = "badge badge-success";
        } else if (this._state === "inactive") {
            this._service.className = "badge badge-danger";
        } else if (this._state === "failed") {
            this._service.className = "badge badge-dark";
        }
    }
    
    /**
     * Check if service is active
     * 
     * @returns {bool}
     */
    isActive() {
        let ret = false;
        
        if (this._state === "active") {
            ret = true;
        }
        
        return ret;
    }
}
