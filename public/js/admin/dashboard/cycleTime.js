
// Cycle time class
export class cycleTime {
    
    /**
     * Cycle time constructor
     * 
     * @param {string} cycleId Cycle time identifier
     * @param {bool} multi Multi value flag
     */
    constructor(cycleId, multi = false) {
        
        // Cycle time id
        this._cycleId = cycleId;
        
        // Multi value flag
        this._multi = multi;
    }
    
    /**
     * Set cycle time
     * 
     * @param {object} val Cycle time values
     */
    setValue(val) {
        // One value
        if (this._multi === false) {
            document.getElementById(this._cycleId+"CycleMin").innerHTML = val.min;
            document.getElementById(this._cycleId+"CycleMax").innerHTML = val.max;
            document.getElementById(this._cycleId+"CycleCurrent").innerHTML = val.current;
        } else { // Multivalue
            for (let key in val) {
                document.getElementById(this._cycleId+key+"_CycleMin").innerHTML = val[key].min;
                document.getElementById(this._cycleId+key+"_CycleMax").innerHTML = val[key].max;
                document.getElementById(this._cycleId+key+"_CycleCurrent").innerHTML = val[key].current;
            }
        }
    }
    
    /**
     * Clear cycle time
     */
    clear() {
        // One value
        if (this._multi === false) {
            document.getElementById(this._cycleId+"CycleMin").innerHTML = 0;
            document.getElementById(this._cycleId+"CycleMax").innerHTML = 0;
            document.getElementById(this._cycleId+"CycleCurrent").innerHTML = 0;
        } else { // Multivalue
            $("td[id^='"+this._cycleId+"']").text("0");
        }
    }
}
