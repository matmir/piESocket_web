
// Parser class
export class parser {
    
    // Command numbers
    static CMD = {
        GET_BIT: 10,
        SET_BIT: 11,
        RESET_BIT: 12,
        INVERT_BIT: 13,
        
        GET_BITS: 20,
        SET_BITS: 21,

        GET_BYTE: 30,
        WRITE_BYTE: 31,

        GET_WORD: 32,
        WRITE_WORD: 33,

        GET_DWORD: 34,
        WRITE_DWORD: 35,

        GET_INT: 36,
        WRITE_INT: 37,

        GET_REAL: 38,
        WRITE_REAL: 39,

        MULTI_CMD: 50,

        ACK_ALARM: 90,
        
        GET_THREAD_CYCLE_TIME: 500,

        EXIT_APP: 600
    };
    
    /**
     * Parser constructor
     * 
     * @param {string} scriptName Parser script path
     */
    constructor(scriptName) {
        
        this._parser = scriptName;
        this._method = "post";
        this._headers = {'Content-Type': 'application/json'};
    }
    
    /**
     * Get GET_BIT command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_GET_BIT(tag) {

 	return {
	    "cmd": this.CMD.GET_BIT,
	    "tag": tag
 	};
    };
    
    /**
     * Get Bit from device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async GET_BIT(tag) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_GET_BIT(tag))
        });
    };
    
    /**
     * Get SET_BIT command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_SET_BIT(tag) {

 	return {
	    "cmd": this.CMD.SET_BIT,
	    "tag": tag
 	};
    };
    
    /**
     * Set Bit in device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async SET_BIT(tag) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_SET_BIT(tag))
        });
    };
    
    /**
     * Get RESET_BIT command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_RESET_BIT(tag) {

 	return {
	    "cmd": this.CMD.RESET_BIT,
	    "tag": tag
 	};
    };
    
    /**
     * Reset Bit in device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async RESET_BIT(tag) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_RESET_BIT(tag))
        });
    };
    
    /**
     * Get INVERT_BIT command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_INVERT_BIT(tag) {

 	return {
	    "cmd": this.CMD.INVERT_BIT,
	    "tag": tag
 	};
    };
    
    /**
     * Invert Bit in device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async INVERT_BIT(tag) {

	return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_INVERT_BIT(tag))
        });
    };
    
    /**
     * Get GET_BITS command object
     * 
     * @param {array} tags Tag names
     * @returns {object}
     */
    static CMD_GET_BITS(tags) {

 	return {
	    "cmd": this.CMD.GET_BITS,
	    "tags": tags
 	};
    };
    
    /**
     * Get Bits from device
     * 
     * @param {array} tags Tag names
     * @returns {promise}
     */
    async GET_BITS(tags) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_GET_BITS(tags))
        });
    };
    
    /**
     * Get SET_BITS command object
     * 
     * @param {array} tags Tag names
     * @returns {object}
     */
    static CMD_SET_BITS(tags) {

 	return {
	    "cmd": this.CMD.SET_BITS,
	    "tags": tags
 	};
    };
    
    /**
     * Set Bits in device
     * 
     * @param {array} tags Tag names
     * @returns {promise}
     */
    async SET_BITS(tags) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_SET_BITS(tags))
        });
    };
    
    /**
     * Get GET_BYTE command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_GET_BYTE(tag) {

 	return {
	    "cmd": this.CMD.GET_BYTE,
	    "tag": tag
 	};
    };
    
    /**
     * Get Byte from device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async GET_BYTE(tag) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_GET_BYTE(tag))
        });
    };
    
    /**
     * Get WRITE_BYTE command object
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {object}
     */
    static CMD_WRITE_BYTE(tag, value) {

 	return {
	    "cmd": this.CMD.WRITE_BYTE,
	    "tag": tag,
	    "value": value
 	};
    };
    
    /**
     * Write Byte in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {promise}
     */
    async WRITE_BYTE(tag, value) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_WRITE_BYTE(tag, value))
        });
    };
    
    /**
     * Get GET_WORD command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_GET_WORD(tag) {

 	return {
	    "cmd": this.CMD.GET_WORD,
	    "tag": tag
 	};
    };
    
    /**
     * Get Word from device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async GET_WORD(tag) {
        
        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_GET_WORD(tag))
        });
    };
    
    /**
     * Get WRITE_WORD command object
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {object}
     */
    static CMD_WRITE_WORD(tag, value) {

 	return {
	    "cmd": this.CMD.WRITE_WORD,
	    "tag": tag,
	    "value": value
 	};
    };
    
    /**
     * Write Word in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {promise}
     */
    async WRITE_WORD(tag, value) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_WRITE_WORD(tag, value))
        });
    };
    
    /**
     * Get GET_DWORD command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_GET_DWORD(tag) {

 	return {
	    "cmd": this.CMD.GET_DWORD,
	    "tag": tag
 	};
    };
    
    /**
     * Get DWord from device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async GET_DWORD(tag) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_GET_DWORD(tag))
        });
    };
    
    /**
     * Get WRITE_DWORD command object
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {object}
     */
    static CMD_WRITE_DWORD(tag, value) {

 	return {
	    "cmd": this.CMD.WRITE_DWORD,
	    "tag": tag,
	    "value": value
 	};
    };
    
    /**
     * Write DWord in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {promise}
     */
    async WRITE_DWORD(tag, value) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_WRITE_DWORD(tag, value))
        });
    };
    
    /**
     * Get GET_INT command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_GET_INT(tag) {

 	return {
	    "cmd": this.CMD.GET_INT,
	    "tag": tag
 	};
    };
    
    /**
     * Get INT from device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async GET_INT(tag) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_GET_INT(tag))
        });
    };
    
    /**
     * Get WRITE_INT command object
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {object}
     */
    static CMD_WRITE_INT(tag, value) {

 	return {
	    "cmd": this.CMD.WRITE_INT,
	    "tag": tag,
	    "value": value
 	};
    };
    
    /**
     * Write INT in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {promise}
     */
    async WRITE_INT(tag, value) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_WRITE_INT(tag, value))
        });
    };
    
    /**
     * Get GET_REAL command object
     * 
     * @param {string} tag Tag name
     * @returns {object}
     */
    static CMD_GET_REAL(tag) {

 	return {
	    "cmd": this.CMD.GET_REAL,
	    "tag": tag
 	};
    };
    
    /**
     * Get REAL from device
     * 
     * @param {string} tag Tag name
     * @returns {promise}
     */
    async GET_REAL(tag) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_GET_REAL(tag))
        });
    };
    
    /**
     * Get WRITE_REAL command object
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {object}
     */
    static CMD_WRITE_REAL(tag, value) {

 	return {
	    "cmd": this.CMD.WRITE_REAL,
	    "tag": tag,
	    "value": value
 	};
    };
    
    /**
     * Write REAL in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {promise}
     */
    async WRITE_REAL(tag, value) {

        return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(parser.CMD_WRITE_REAL(tag, value))
        });
    };
    
    /**
     * Run MULTICMD in device
     * 
     * @param {object} commands Commands object
     * @returns {promise}
     */
    async CMD_MULTI(commands) {

	let myCMD = {
	    "cmd": parser.CMD.MULTI_CMD,
	    "value": commands
 	};

	return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(myCMD)
        });
    };
    
    /**
     * Run ACK_ALARM in device
     * 
     * @param {number} alarm_id Alarm identifier
     * @returns {promise}
     */
    async ACK_ALARM(alarm_id) {

	var myCMD = {
	    "cmd": parser.CMD.ACK_ALARM,
	    "alarm_id": alarm_id
 	};

 	return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(myCMD)
        });
    };
    
    /**
     * Get thread cycle times from system
     * 
     * @returns {promise}
     */
    async GET_THREAD_CYCLE_TIME() {

	var myCMD = {
	    "cmd": parser.CMD.GET_THREAD_CYCLE_TIME
 	};

 	return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(myCMD)
        });
    };
    
    /**
     * Exit service application
     * 
     * @returns {promise}
     */
    async EXIT_APP() {

	var myCMD = {
	    "cmd": parser.CMD.EXIT_APP
 	};

 	return fetch(this._parser, {
                        method: this._method,
                        headers: this._headers,
                        body: JSON.stringify(myCMD)
        });
    };
}
