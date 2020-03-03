
function createMultiCommand(pr, errorFunction) {
    
    // Parser object
    this.parser = pr;
    
    // Commands
    var cmds = [];
    
    // Feedback functions
    var fbs = [];
    
    // Executing flag
    var exec = false;
    
    this.isExecuting = function() {
        return exec;
    };
    
    this.addCommand = function(cmd, feedBackFunction) {
        
        // Add command
        cmds.push(cmd);
        
        // Add feedback function
        fbs.push(feedBackFunction);
        
    };
    
    // Execute button function
    this.Execute = function() {
        
        if (!exec) {
            
            exec = true;
        
            this.parser.CMD_MULTI(cmds, function(data, status){

                // Command success
                if (status === "success" && data.error.state === false) {

                    var i=0;
                    
                    for (i=0; i<cmds.length; i=i+1) {

                        // Check command
                        if (cmds[i].cmd === data.reply.value[i].cmd) {
                            
                            // Call feedback function
                            if (cmds[i].cmd === pr.CMD.GET_BITS) {
                                fbs[i](data.reply.value[i].values);
                            } else {
                                fbs[i](data.reply.value[i].value);
                            }
                            
                        }

                    }
                    
                    exec = false;
                    
                    cmds = [];
                    fbs = [];

                } else {
                    if (data.error.state === true) {
                        errorFunction(data.error.msg);
                    } else {
                        errorFunction('Unknown error');
                    }
                }

            });
            
        }
        
    };
    
}
