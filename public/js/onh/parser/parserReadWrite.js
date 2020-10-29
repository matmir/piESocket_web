
import {parser} from './parser.js';

// Parser ReadWrite class
export class parserReadWrite {
    
    /**
     * Parser constructor
     * 
     * @param {string} scriptName Parser script path
     */
    constructor(scriptName = '/parser/query') {
        
        this._parser = new parser(scriptName);
    }
    
    /**
     * Parse reply data
     * 
     * @param {object} data Data object with reply
     * @param {bool} multiValues Multivalues reply flag
     * @returns {number}
     */
    _parseReply(data, multiValues=false) {
        
        let returnValue = 0;
        
        if (data.error.state === false) {
            
            if (data.reply.length === 0) {
                throw new Error("Server empty response");
            }
            
            if (!multiValues) {
                returnValue = data.reply.value;
            } else {
                returnValue = data.reply.values;
            }
        } else {
            throw new Error(data.error.msg);
        }
        
        return returnValue;
    };
    
    /**
     * Get Bit from device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async getBit(tag) {
        
        try {
            let res = await this._parser.GET_BIT(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Set Bit in device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async setBit(tag) {
        
        try {
            let res = await this._parser.SET_BIT(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Reset Bit in device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async resetBit(tag) {
        
        try {
            let res = await this._parser.RESET_BIT(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Invert Bit in device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async invertBit(tag) {
        
        try {
            let res = await this._parser.INVERT_BIT(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Get Bits from device
     * 
     * @param {array} tags Tag names
     * @returns {Promise}
     */
    async getBits(tags) {
        
        try {
            let res = await this._parser.GET_BITS(tags);

            let data = await res.json();

            let value = this._parseReply(data, true);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Set Bits in device
     * 
     * @param {array} tags Tag names
     * @returns {Promise}
     */
    async setBits(tags) {
        
        try {
            let res = await this._parser.SET_BITS(tags);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Get Byte from device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async getByte(tag) {
        
        try {
            let res = await this._parser.GET_BYTE(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Write Byte in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {Promise}
     */
    async writeByte(tag, value) {
        
        try {
            let res = await this._parser.WRITE_BYTE(tag, value);

            let data = await res.json();

            let v = this._parseReply(data);

            return Promise.resolve(v);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Get WORD from device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async getWord(tag) {
        
        try {
            let res = await this._parser.GET_WORD(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Write WORD in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {Promise}
     */
    async writeWord(tag, value) {
        
        try {
            let res = await this._parser.WRITE_WORD(tag, value);

            let data = await res.json();

            let v = this._parseReply(data);

            return Promise.resolve(v);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Get DWORD from device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async getDWord(tag) {
        
        try {
            let res = await this._parser.GET_DWORD(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Write DWORD in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {Promise}
     */
    async writeDWord(tag, value) {
        
        try {
            let res = await this._parser.WRITE_DWORD(tag, value);

            let data = await res.json();

            let v = this._parseReply(data);

            return Promise.resolve(v);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Get INT from device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async getInt(tag) {
        
        try {
            let res = await this._parser.GET_INT(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Write INT in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {Promise}
     */
    async writeInt(tag, value) {
        
        try {
            let res = await this._parser.WRITE_INT(tag, value);

            let data = await res.json();

            let v = this._parseReply(data);

            return Promise.resolve(v);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Get REAL from device
     * 
     * @param {string} tag Tag name
     * @returns {Promise}
     */
    async getReal(tag) {
        
        try {
            let res = await this._parser.GET_REAL(tag);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Write REAL in device
     * 
     * @param {string} tag Tag name
     * @param {number} value Tag value
     * @returns {Promise}
     */
    async writeReal(tag, value) {
        
        try {
            let res = await this._parser.WRITE_REAL(tag, value);

            let data = await res.json();

            let v = this._parseReply(data);

            return Promise.resolve(v);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Ack alarm
     * 
     * @param {number} alarm_id Alarm identifier
     * @returns {Promise}
     */
    async ackAlarm(alarm_id) {
        
        try {
            let res = await this._parser.ACK_ALARM(alarm_id);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Get system thread cycle times
     * 
     * @returns {Promise}
     */
    async getCycleTimes() {
        
        try {
            let res = await this._parser.GET_THREAD_CYCLE_TIME();

            let data = await res.json();

            let value = this._parseReply(data, true);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
    
    /**
     * Exit service application
     * 
     * @returns {Promise}
     */
    async exitService() {
        
        try {
            let res = await this._parser.EXIT_APP();

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
}
