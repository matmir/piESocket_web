$(document).ready(function(){
    
    var minStatusTid;
    
    function createMinService(sName, rName) {
        
        var serviceName = sName;
        var sRestartName = rName;
        var state = 'inactive';
        var restart = false;
        
        this.setState = function(st, rs) {
            state = st;
            restart = rs;
            
            if (state === "active") {
                document.getElementById(serviceName).className = "badge badge-success";
            } else if (state === "inactive") {
                document.getElementById(serviceName).className = "badge badge-danger";
            } else if (state === "failed") {
                document.getElementById(serviceName).className = "badge badge-dark";
            }
            
            if (restart === true) {
                document.getElementById(sRestartName).style.display = "inline-block";
            } else {
                document.getElementById(sRestartName).style.display = "none";
            }
            
        };
    }
    
    var minServiceONH = new createMinService("minONHStatus", "minONHRestart");
    
    function poolingMinServiceStatus() {

        $.get("/services/status", function(data, status){
            
            if (status === "success" && data.error.state === false) {
                
                minServiceONH.setState(data.services.openNetworkHMI, data.restart);
                
            }

        });

        minStatusTid = setTimeout(poolingMinServiceStatus, 1000);

    }
    
    // Get status
    poolingMinServiceStatus();
});