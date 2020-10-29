
// Tag list search
export class tagListSearch {
    
    /**
     * Tag list constructor
     * 
     * @param {type} lName
     * @param {type} searchScriptAddress
     */
    constructor(lName, searchScriptAddress = '/admin/tags/search') {
        
        // List name
        this._dataList = document.getElementById(lName);
        
        // Search script address
        this._searchScript = searchScriptAddress;
    }
    
    /**
     * Parse reply data
     * 
     * @param {object} data Data object with reply
     */
    _parseReply(data) {
        
        if (data.error.state === false) {
            
            // Clear list
            this._dataList.innerHTML = '';
            
            // Add new list options
            for (let i=0; i<data.reply.length; ++i) {
                
                // Create <option> element
                let option = document.createElement('option');
                // Assign value
                option.value = data.reply[i];
                // Add to the list
                this._dataList.appendChild(option);
            }
        } else {
            throw new Error(data.error.msg);
        }
    }
    
    /**
     * Update tag list
     * 
     * @param {string} tagName Tag name
     * @returns {Promise}
     */
    async update(tagName) {
        
        try {
            
            let tag = {
                "tagName": tagName.trim()
            };
            
            if (tag.tagName !=='') {
                // Get tags
                let res = await fetch(this._searchScript, {
                                    method: 'post',
                                    headers: {'Content-Type': 'application/json'},
                                    body: JSON.stringify(tag)
                });
                let data = await res.json();

                this._parseReply(data);
            }

            return Promise.resolve(true);
            
        } catch (err) {
            return Promise.reject(err);
        }
    }
}
