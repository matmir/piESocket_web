
function createButton(bName, errIntegr=true) {
    
    // Button name
    var buttonName = bName;
    
    // Error flag
    var error = false;
    
    // Error Message
    var errorMsg = '';
    
    // Error integration flag
    var errorIntegrated = errIntegr;
    
    // Disable flag
    var disableFlag = false;
    
    // Return value
    var returnValue;
    
    var multiValues = false;
    
    this.hide = function() {
        document.getElementById(buttonName).style.display = "none";
    };
    
    this.show = function() {
        document.getElementById(buttonName).style.display = "inline-block";
    };
    
    this.getReturnValue = function() {
        return returnValue;
    };
    
    this.setReturnMultiValues = function() {
        multiValues = true;
    };
    
    this.disableBtn = function() {
        disableFlag = true;
        document.getElementById(buttonName).disabled = disableFlag;
    };
    
    this.enableBtn = function() {
        disableFlag = false;
        document.getElementById(buttonName).disabled = disableFlag;
    };
    
    this.isError = function() {
        return error;
    };
    
    this.getErrorMSG = function() {
        return errorMsg;
    };
    
    this.updateButtonState = function() {
        // Check error state
        if (this.isError()) {
            if (errorIntegrated) {
                document.getElementById(buttonName).className = "btn btn-danger";
                document.getElementById(buttonName).title = errorMsg;
            }
        } else {
            this.enableBtn();
        }
        
    };
    
    this.setError = function(errMsg) {
        error = true;
        errorMsg = errMsg;
        
        this.updateButtonState();
    };
    
    this.parseReply = function(data, status) {
        
        if (status === "success" && data.error.state === false) {
            error = false;
            errorMsg = '';
            
            if (!multiValues) {
                returnValue = data.reply.value;
            } else {
                returnValue = data.reply.values;
            }
        } else {
            error = true;
            if (data.error.state === true) {
                errorMsg = data.error.msg;
            } else {
                errorMsg = 'Unknown error';
            }
        }
        
        this.updateButtonState();
        
    };
}

function createActionButton(pr, bName, command, tagNM, returnFunction=null, errIntegrated=true) {
    
    // Parser object
    this.parser = pr;
    
    // Button object
    var button = new createButton(bName, errIntegrated);
    
    // Command attached to the button
    var cmd = command;
    
    // Tag name
    var tag = tagNM;
    
    // Tag value
    var tagValue = 0;
    
    // Return function from parser
    function parse_FB(data, status) {
                
        button.parseReply(data, status);
        
        if (!button.isError()) {
            if (returnFunction!==null) {
                returnFunction(button.getReturnValue());
            }
        }
    };
    
    this.setTagValue = function(val) {
        tagValue = val;
    };
    
    // Execute button function
    this.Execute = function() {
        
        button.disableBtn();
                        
        switch (cmd) {
            case this.parser.CMD.GET_BIT: this.parser.CMD_GET_BIT(tag, parse_FB); break;
            case this.parser.CMD.SET_BIT: this.parser.CMD_SET_BIT(tag, parse_FB); break;
            case this.parser.CMD.RESET_BIT: this.parser.CMD_RESET_BIT(tag, parse_FB); break;
            case this.parser.CMD.INVERT_BIT: this.parser.CMD_INVERT_BIT(tag, parse_FB); break;
            case this.parser.CMD.GET_BITS: {
                button.setReturnMultiValues();
                this.parser.CMD_GET_BITS(tag, parse_FB);
            }; break;
            case this.parser.CMD.SET_BITS: this.parser.CMD_SET_BITS(tag, parse_FB); break;
            case this.parser.CMD.GET_BYTE: this.parser.CMD_GET_BYTE(tag, parse_FB); break;
            case this.parser.CMD.WRITE_BYTE: this.parser.CMD_WRITE_BYTE(tag, tagValue, parse_FB); break;
            case this.parser.CMD.GET_WORD: this.parser.CMD_GET_WORD(tag, parse_FB); break;
            case this.parser.CMD.WRITE_WORD: this.parser.CMD_WRITE_WORD(tag, tagValue, parse_FB); break;
            case this.parser.CMD.GET_DWORD: this.parser.CMD_GET_DWORD(tag, parse_FB); break;
            case this.parser.CMD.WRITE_DWORD: this.parser.CMD_WRITE_DWORD(tag, tagValue, parse_FB); break;
            case this.parser.CMD.GET_INT: this.parser.CMD_GET_INT(tag, parse_FB); break;
            case this.parser.CMD.WRITE_INT: this.parser.CMD_WRITE_INT(tag, tagValue, parse_FB); break;
            case this.parser.CMD.GET_REAL: this.parser.CMD_GET_REAL(tag, parse_FB); break;
            case this.parser.CMD.WRITE_REAL: this.parser.CMD_WRITE_REAL(tag, tagValue, parse_FB); break;
        }
        
    };
    
}

function createExitButton(pr, bName, returnFunction=null, errIntegrated=true) {
    
    // Parser object
    this.parser = pr;
    
    // Button object
    var button = new createButton(bName, errIntegrated);
    
    this.hide = function() {
        button.hide();
    };
    
    this.show = function() {
        button.show();
    };
    
    // Return function from parser
    function parse_FB(data, status) {
                
        button.parseReply(data, status);
        
        if (!button.isError()) {
            if (returnFunction!==null) {
                returnFunction(button.getReturnValue());
            }
        }
    };
    
    // Execute button function
    this.Execute = function() {
        
        button.disableBtn();
                        
        this.parser.CMD_EXIT_APP(parse_FB);
    };
}

function createAlarmAckButton(pr, bName, returnFunction=null, errIntegrated=true) {
    
    // Parser object
    this.parser = pr;
    
    // Button object
    var button = new createButton(bName, errIntegrated);
    
    // Return function from parser
    function parse_FB(data, status) {
                
        button.parseReply(data, status);
        
        if (!button.isError()) {
            if (returnFunction!==null) {
                returnFunction(button.getReturnValue());
            }
        }
    };
    
    // Execute button function
    this.ack = function(alarm_id=0) {
        
        button.disableBtn();
                        
        this.parser.CMD_ACK_ALARM(alarm_id, parse_FB);
    };
}
