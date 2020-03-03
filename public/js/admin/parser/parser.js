/**
 * Create parser object
 * 
 * @param string pName
 * @returns Parser object
 */
function createParser(pName) {
    
    // PHP script name
    this.parserName = pName;
    
    // Command numbers
    this.CMD = {
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
    
    this.CMD_IS_REPLY_ERROR = function(data) {

        if (data.error === undefined)
            return false;
        else
            return true;

    };
    
    this.CMD_GET_ERROR_CODE = function(data) {

	if (this.CMD_IS_REPLY_ERROR(data)) {
            return data.code;
	} else {
            return 0;
	}

    };
    
    this.m_GET_BIT = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.GET_BIT,
	    "tag": tag
 	};

 	return myCMD;
    };
    
    this.CMD_GET_BIT = function(tag, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_GET_BIT(tag))}, callbackFunction);

    };
    
    this.m_SET_BIT = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.SET_BIT,
	    "tag": tag
 	};

 	return myCMD;
    };
    
    this.CMD_SET_BIT = function(tag, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_SET_BIT(tag))}, callbackFunction);

    };
    
    this.m_RESET_BIT = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.RESET_BIT,
	    "tag": tag
 	};

 	return myCMD;
    };
    
    this.CMD_RESET_BIT = function(tag, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_RESET_BIT(tag))}, callbackFunction);

    };
    
    this.m_INVERT_BIT = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.INVERT_BIT,
	    "tag": tag
 	};

 	return myCMD;

    };
    
    this.CMD_INVERT_BIT = function(tag, callbackFunction) {

	$.post(this.parserName, { "json" : JSON.stringify(this.m_INVERT_BIT(tag))}, callbackFunction);

    };
    
    this.m_GET_BITS = function(tags) {

	var myCMD = {
	    "cmd": this.CMD.GET_BITS,
	    "tags": tags
 	};

 	return myCMD;

    };
    
    this.CMD_GET_BITS = function(tags, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_GET_BITS(tags))}, callbackFunction);

    };
    
    this.m_SET_BITS = function(tags) {

	var myCMD = {
	    "cmd": this.CMD.SET_BITS,
	    "tags": tags
 	};

 	return myCMD;

    };
    
    this.CMD_SET_BITS = function(tags, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_SET_BITS(tags))}, callbackFunction);

    };
    
    this.m_GET_BYTE = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.GET_BYTE,
	    "tag": tag
 	};

 	return myCMD;

    };
    
    this.CMD_GET_BYTE = function(tag, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_GET_BYTE(tag))}, callbackFunction);

    };
    
    this.m_WRITE_BYTE = function(tag, value) {

	var myCMD = {
	    "cmd": this.CMD.WRITE_BYTE,
	    "tag": tag,
	    "value": value
 	};

 	return myCMD;

    };
    
    this.CMD_WRITE_BYTE = function(tag, value, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_WRITE_BYTE(tag, value))}, callbackFunction);

    };
    
    this.m_GET_WORD = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.GET_WORD,
	    "tag": tag
 	};

 	return myCMD;

    };
    
    this.CMD_GET_WORD = function(tag, callbackFunction) {

	$.post(this.parserName, { "json" : JSON.stringify(this.m_GET_WORD(tag))}, callbackFunction);

    };
    
    this.m_WRITE_WORD = function(tag, value) {

	var myCMD = {
	    "cmd": this.CMD.WRITE_WORD,
	    "tag": tag,
	    "value": value
 	};

 	return myCMD;

    };
    
    this.CMD_WRITE_WORD = function(tag, value, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_WRITE_WORD(tag, value))}, callbackFunction);

    };
    
    this.m_GET_DWORD = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.GET_DWORD,
	    "tag": tag
 	};

 	return myCMD;

    };
    
    this.CMD_GET_DWORD = function(tag, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_GET_DWORD(tag))}, callbackFunction);

    };
    
    this.m_WRITE_DWORD = function(tag, value) {

	var myCMD = {
	    "cmd": this.CMD.WRITE_DWORD,
	    "tag": tag,
	    "value": value
 	};

 	return myCMD;

    };
    
    this.CMD_WRITE_DWORD = function(tag, value, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_WRITE_DWORD(tag, value))}, callbackFunction);

    };
    
    this.m_GET_INT = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.GET_INT,
	    "tag": tag
 	};

 	return myCMD;

    };
    
    this.CMD_GET_INT = function(tag, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_GET_INT(tag))}, callbackFunction);

    };
    
    this.m_WRITE_INT = function(tag, value) {

	var myCMD = {
	    "cmd": this.CMD.WRITE_INT,
	    "tag": tag,
	    "value": value
 	};

 	return myCMD;

    };
    
    this.CMD_WRITE_INT = function(tag, value, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_WRITE_INT(tag, value))}, callbackFunction);

    };
    
    this.m_GET_REAL = function(tag) {

	var myCMD = {
	    "cmd": this.CMD.GET_REAL,
	    "tag": tag
 	};

 	return myCMD;

    };
    
    this.CMD_GET_REAL = function(tag, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_GET_REAL(tag))}, callbackFunction);

    };
    
    this.m_WRITE_REAL = function(tag, value) {

	var myCMD = {
	    "cmd": this.CMD.WRITE_REAL,
	    "tag": tag,
	    "value": value
 	};

 	return myCMD;

    };
    
    this.CMD_WRITE_REAL = function(tag, value, callbackFunction) {

   	$.post(this.parserName, { "json" : JSON.stringify(this.m_WRITE_REAL(tag, value))}, callbackFunction);

    };
    
    this.CMD_MULTI = function(commands, callbackFunction) {

	var myCMD = {
	    "cmd": this.CMD.MULTI_CMD,
	    "value": commands
 	};

	$.post(this.parserName, { "json" : JSON.stringify(myCMD)}, callbackFunction);

    };
    
    this.CMD_ACK_ALARM = function(alarm_id, callbackFunction) {

	var myCMD = {
	    "cmd": this.CMD.ACK_ALARM,
	    "alarm_id": alarm_id
 	};

 	$.post(this.parserName, { "json" : JSON.stringify(myCMD)}, callbackFunction);

    };
    
    this.CMD_GET_THREAD_CYCLE_TIME = function(callbackFunction) {

	var myCMD = {
	    "cmd": this.CMD.GET_THREAD_CYCLE_TIME
 	};

 	$.post(this.parserName, { "json" : JSON.stringify(myCMD)}, callbackFunction);

    };
    
    this.CMD_EXIT_APP = function(callbackFunction) {

	var myCMD = {
	    "cmd": this.CMD.EXIT_APP
 	};

 	$.post(this.parserName, { "json" : JSON.stringify(myCMD)}, callbackFunction);

    };

    this.testFunc = function() {
        alert(this.CMD.EXIT_APP);
    };
}
