/**
 * Create tag list search object
 * 
 * @param {type} lName                  Data list identifier
 * @param {type} searchScriptAddress    Search script address
 * @returns {createTagListSearch}
 */
function createTagListSearch(lName, searchScriptAddress) {
    
    // List name
    var dataList = document.getElementById(lName);
    
    // Search script address
    var searchScript = searchScriptAddress;
    
    // Lock flag
    var lock = false;
            
    function parseReply(data, status) {
        
        if (status === "success" && data.error.state === false) {
            
            // Unlock script
            lock = false;
            
            // Clear list
            dataList.innerHTML = '';
            
            // Add new list options
            for (var i=0; i<data.reply.length; ++i) {
                
                // Create <option> element
                var option = document.createElement('option');
                // Assign value
                option.value = data.reply[i];
                // Add to the list
                dataList.appendChild(option);
                
            }
            
        } else {
            if (data.error.state === true) {
                console.log(data.error.msg);
            } else {
                console.log('Unknown error');
            }
        }
        
    };
    
    this.update = function(tagName) {
        
        var tag = {
        "tagName": tagName
    };
        
        if (!lock && tag.tagName !=='') {
            lock = true;
            $.post(searchScript, { "json" : JSON.stringify(tag)}, parseReply);
        }
        
    };
}
