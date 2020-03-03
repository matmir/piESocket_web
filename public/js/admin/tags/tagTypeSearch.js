/**
 * Create tag type search object
 * 
 * @param {type} searchScriptAddress    Search script address
 * @param {type} fbFunc                 Feedback function (when type was found)
 * @returns {createTagTypeSearch}
 */
function createTagTypeSearch(searchScriptAddress, fbFunc) {
        
    // Search script address
    var searchScript = searchScriptAddress;
    
    // Lock flag
    var lock = false;
    
    // Tag type
    var type = '';
    
    this.getType = function() {
        return type;
    };
    
    function parseReply(data, status) {
        
        if (status === "success" && data.error.state === false) {
            
            // Unlock script
            lock = false;
            
            // Check reply
            if (data.reply.length===1) {
                if (data.reply[0]!=='') {
                    type = data.reply[0];
                    // Call feedback function
                    fbFunc(data.reply[0]);
                }
            }
            
        } else {
            if (data.error.state === true) {
                console.log(data.error.msg);
            } else {
                console.log('Unknown error');
            }
        }
        
    };
    
    this.search = function(tagName) {
        
        var tag = {
        "tagName": tagName
    };
        
        if (!lock && tag.tagName !=='') {
            lock = true;
            type = '';
            $.post(searchScript, { "json" : JSON.stringify(tag)}, parseReply);
        }
        
    };
}
