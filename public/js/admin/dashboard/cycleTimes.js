/**
 * Create cycle time object
 * 
 * @param {type} ctId           Cycle time identifier
 * @returns {createCycleTime}
 */
function createCycleTime(ctId) {
    
    var cycleTimeId = ctId;

    this.setValue = function(ctVals) {
        
        var vals = ctVals;

        document.getElementById(cycleTimeId+"CycleMin").innerHTML = vals.min;
        document.getElementById(cycleTimeId+"CycleMax").innerHTML = vals.max;
        document.getElementById(cycleTimeId+"CycleCurrent").innerHTML = vals.current;
    };
    
    this.setValueM = function(ctVals) {
                
        for (var key in ctVals) {
            document.getElementById(cycleTimeId+key+"_CycleMin").innerHTML = ctVals[key].min;
            document.getElementById(cycleTimeId+key+"_CycleMax").innerHTML = ctVals[key].max;
            document.getElementById(cycleTimeId+key+"_CycleCurrent").innerHTML = ctVals[key].current;
        }
    };
    
    this.clear = function() {
        document.getElementById(cycleTimeId+"CycleMin").innerHTML = 0;
        document.getElementById(cycleTimeId+"CycleMax").innerHTML = 0;
        document.getElementById(cycleTimeId+"CycleCurrent").innerHTML = 0;
    };
    
    this.clearM = function() {

        $("td[id^='"+cycleTimeId+"']").text("0");
    };
}

/**
 * Get cycle times
 * 
 * @param {type} parser             Parser object
 * @param {type} ctUpdater          Cycle time Process Updater object
 * @param {type} ctPolling          Cycle time Driver polling object
 * @param {type} ctLogger           Cycle time Tag logger object
 * @param {type} ctLoggerWriter     Cycle time Tag logger writer object
 * @param {type} ctAlarming         Cycle time Alarming object
 * @param {type} ctScript           Cycle time Script system object
 * @returns {undefined}
 */
function cycleTimes(parser, ctUpdater, ctPolling, ctLogger, ctLoggerWriter, ctAlarming, ctScript) {

    // Get cycle times
    parser.CMD_GET_THREAD_CYCLE_TIME(function(data, status){
                
        if (status === "success" && data.error.state === false) {
            
            ctUpdater.setValueM(data.reply.values.Updater);
            ctPolling.setValueM(data.reply.values.Polling);
            ctLogger.setValue(data.reply.values.Logger);
            ctLoggerWriter.setValue(data.reply.values.LoggerWriter);
            ctAlarming.setValue(data.reply.values.Alarming);
            ctScript.setValue(data.reply.values.Script);
        } else {
            ctUpdater.clearM();
            ctPolling.clearM();
            ctLogger.clear();
            ctLoggerWriter.clear();
            ctAlarming.clear();
            ctScript.clear();
        }
        
    });
}