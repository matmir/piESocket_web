
// Tag type search
export class tagTypeSearch {
    
    /**
     * Tag type search constructor
     * 
     * @param {type} searchScriptAddress
     */
    constructor(searchScriptAddress = '/admin/tags/search/1') {
                
        // Search script address
        this._searchScript = searchScriptAddress;
        
        // Tag type
        this._type = '';
    }
    
    /**
     * Parse reply data
     * 
     * @param {object} data Data object with reply
     */
    _parseReply(data) {
        
        if (data.error.state === false) {
            
            // Check reply
            if (data.reply.length===1) {
                if (data.reply[0]!=='') {
                    this._type = data.reply[0];
                }
            }
        } else {
            throw new Error(data.error.msg);
        }
    }
    
    /**
     * Get Tag type
     * 
     * @returns {String}
     */
    getType() {
        return this._type;
    }
    
    /**
     * Search Tag type
     * 
     * @param {string} tagName Tag name
     * @returns {Promise}
     */
    async search(tagName) {
        
        try {
            
            let tag = {
                "tagName": tagName.trim()
            };
            
            if (tag.tagName !=='') {
                
                this._type = '';
                
                // Get tags
                let res = await fetch(this._searchScript, {
                                    method: 'post',
                                    headers: {'Content-Type': 'application/json'},
                                    body: JSON.stringify(tag)
                });
                let data = await res.json();

                this._parseReply(data);
            }

            return Promise.resolve(this._type);
            
        } catch (err) {
            return Promise.reject(err);
        }
    }
}
