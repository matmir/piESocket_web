$(document).ready(function(){
    
    // Parser
    var pr = new createParser('/parser/query');
    
    // Services
    var serviceONH = new createService("onhStatus");
    var restartBadge = new createRestartBadge("onhRStatus");
    
    var serviceApache = new createService("apacheStatus");
    var serviceMySQL = new createService("mysqlStatus");
    var serviceAutoload = new createService("autoloading", true);
    
    // Service buttons
    var buttonAutoload = new createServiceBtn("btnChangeAutoload");
    var buttonONH = new createServiceBtn("btnChangeONH");
    var buttonONHExit = new createExitButton(pr, "btnExitONH");
    
    // Cycle times
    var cycleUpdater = new createCycleTime("process");
    var cyclePolling = new createCycleTime("polling");
    var cycleLogger = new createCycleTime("tagLogger");
    var cycleLoggerWriter = new createCycleTime("tagLoggerWriter");
    var cycleAlarming = new createCycleTime("alarming");
    var cycleScript = new createCycleTime("script");
    
    // Pooling timer
    var dashboardTid;
        
    // Dashboard status
    function poolingDashboardStatus() {
        
        // Get services status
        serviceStatus(serviceONH, restartBadge, serviceApache, serviceMySQL, serviceAutoload, buttonAutoload, buttonONH, repoolingDashboardStatus);
        
    }
    
    function repoolingDashboardStatus() {
        
        // Check if client is running
        if (serviceONH.isActive()) {
            
            // Show exit button
            buttonONHExit.show();
            
            // Get cycle times
            cycleTimes(pr, cycleUpdater, cyclePolling, cycleLogger, cycleLoggerWriter, cycleAlarming, cycleScript);
        } else {
            // Hide exit button
            buttonONHExit.hide();
            
            // Clear cycle times
            cycleUpdater.clearM();
            cyclePolling.clearM();
            cycleLogger.clear();
            cycleLoggerWriter.clear();
            cycleAlarming.clear();
            cycleScript.clear();
        }
        
        // Set next tik
        dashboardTid = setTimeout(poolingDashboardStatus, 1000);
        
    }
    
    // Get status
    poolingDashboardStatus();
    
    $("#btnChangeAutoload").click(function(){
        changeAutoloading(buttonAutoload, serviceAutoload);
    });
    
    $("#btnChangeONH").click(function(){
        startONH(buttonONH, serviceONH);
    });
    
    $("#btnExitONH").click(function(){
        buttonONHExit.Execute();
    });
});