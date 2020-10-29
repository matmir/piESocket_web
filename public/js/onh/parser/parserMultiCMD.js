
import {parser} from './parser.js';

// Parser multi command class
export class parserMultiCMD {
    
    /**
     * Parser constructor
     * 
     * @param {string} scriptName Parser script path
     */
    constructor(scriptName = '/parser/query') {
        
        this._parser = new parser(scriptName);
        
        // Commands
        this._cmds = [];
    }
    
    /**
     * Parse reply data
     * 
     * @param {object} data Data object with reply
     * @returns {array}
     */
    _parseReply(data) {
        
        let returnValue = [];
        
        if (data.error.state === false) {
            
            let i=0;
                    
            for (i=0; i<this._cmds.length; i=i+1) {

                // Check command
                if (this._cmds[i].cmd === data.reply.value[i].cmd) {

                    // Get return values
                    if (this._cmds[i].cmd === parser.CMD.GET_BITS) {
                        returnValue.push(data.reply.value[i].values);
                    } else {
                        returnValue.push(data.reply.value[i].value);
                    }
                } else {
                    throw new Error("Invalid command number in reply");
                }
            }
        } else {
            throw new Error(data.error.msg);
        }
        
        return returnValue;
    };
    
    /**
     * Add command to multicommand
     * 
     * @param {object} cmd Command object
     */
    addCommand(cmd) {
        
        // Add command
        this._cmds.push(cmd);
    }
    
    /**
     * Clear all commands
     */
    clearCommands() {
        
        this._cmds = [];
    }
    
    /**
     * Execute multicommand
     * 
     * @returns {Promise}
     */
    async execute() {
        
        try {
            // Check commands
            if (this._cmds.length === 0) {
                throw new Error("No commands attached");
            }
            
            let res = await this._parser.CMD_MULTI(this._cmds);

            let data = await res.json();

            let value = this._parseReply(data);

            return Promise.resolve(value);

        } catch (err) {
            return Promise.reject(err);
        }
    }
}
