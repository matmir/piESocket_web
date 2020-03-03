/**
 * Create service object
 * 
 * @param {type} sName          Service identifier name
 * @param {type} sVirtual       Virtual service flag
 * @returns {createService}
 */
function createService(sName, sVirtual=false) {
    
    var serviceName = sName;
    var state = 'inactive';
    var started = false;
    var virtual = sVirtual;

    this.setState = function(st) {
        state = st;
        if (state === "active") {
            started = true;
        } else {
            started = false;
        }

        if (!virtual) {
            document.getElementById(serviceName).innerHTML = state;

            if (state === "active") {
                document.getElementById(serviceName).className = "badge badge-success";
            } else if (state === "inactive") {
                document.getElementById(serviceName).className = "badge badge-danger";
            } else if (state === "failed") {
                document.getElementById(serviceName).className = "badge badge-dark";
            }
        }
    };
    
    this.setStarted = function(st) {
        started = st;
    };
    
    this.isActive = function() {
        return started;
    };

    this.getURLflag = function() {
        var ret = 0;

        if (started===false) {
            ret = 1;
        } else {
            ret = 0;
        }

        return ret;
    };
}

function createRestartBadge(rbName) {
    
    var rbName = rbName;
    
    this.setState = function(state) {
        if (state===true) {
            document.getElementById(rbName).style.display = "inline-block";
        } else {
            document.getElementById(rbName).style.display = "none";
        }
    };
}

/**
 * Create srvice button object
 * 
 * @param {type} bName          Button identifier name
 * @returns {createServiceBtn}
 */
function createServiceBtn(bName) {
    
    var btnName = bName;
    var disableFlag = false;

    this.updateDisable = function() {
        document.getElementById(btnName).disabled = disableFlag;
    };

    this.setDisable = function() {
        disableFlag = true;
    };
    
    this.resetDisable = function() {
        disableFlag = false;
    };

    this.setState = function(state, text) {
        if (state===true) {
            document.getElementById(btnName).innerHTML = text;
            document.getElementById(btnName).className = "btn btn-success";
        } else {
            document.getElementById(btnName).innerHTML = text;
            document.getElementById(btnName).className = "btn btn-danger";
        }
    };
}

/**
 * Get service status
 * 
 * @param {type} sONH           Service openNetworkHMI object
 * @param {type} srONH       Restart badge object
 * @param {type} sApache        Service Apache object
 * @param {type} sMySql         Service MySQL object
 * @param {type} sAutoload      Service Autoloading object (virtual)
 * @param {type} btnAutoload    Button Autoload object
 * @param {type} btnClient      Button Client object
 * @param {type} feedbackFunc   Feedback function - request OK
 * @returns {undefined}
 */
function serviceStatus(sONH, srONH, sApache, sMySql, sAutoload, btnAutoload, btnClient, feedbackFunc) {

    $.get("/services/status", function(data, status){

        if (status === "success" && data.error.state === false) {

            sONH.setState(data.services.openNetworkHMI);
            srONH.setState(data.restart);
            sApache.setState(data.services.Apache2);
            sMySql.setState(data.services.MySQL);
            sAutoload.setStarted(data.services.Autoload);

        }

        if (sAutoload.isActive()===true) {
            btnAutoload.setState(true, "Enabled");
        } else {
            btnAutoload.setState(false, "Disabled");
        }

        if (sONH.isActive()===true) {
            btnClient.setState(false, "Kill service");
        } else {
            btnClient.setState(true, "Start service");
        }

        btnAutoload.updateDisable();
        btnClient.updateDisable();
        
        feedbackFunc();

    });

}

/**
 * Change autoloading state
 * 
 * @param {type} button     Button object
 * @param {type} service    Service object
 * @returns {undefined}
 */
function changeAutoloading(button, service) {

    // Disable change button
    button.setDisable();
    button.updateDisable();

    // Change autoload state
    $.get("/services/autoload/"+service.getURLflag(), function(data, status){

        if (status === "success" && data.error.state === false) {

            // Enable change button
            button.resetDisable();

        }

    });

}

/**
 * Start openNetworkHMI service
 * 
 * @param {type} button     Button object
 * @param {type} service    Service object
 * @returns {undefined}
 */
function startONH(button, service) {

    // Disable change button
    button.setDisable();
    button.updateDisable();

    // Start/Stop client
    $.get("/services/onh/"+service.getURLflag(), function(data, status){

        if (status === "success" && data.error.state === false) {

            // Enable change button
            button.resetDisable();

        }

    });

}
