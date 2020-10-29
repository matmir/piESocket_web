
import {parser} from './../../parser/parser.js';
import {parserReadWrite} from './../../parser/parserReadWrite.js';
import {button} from './button.js';

// Action button class
export class actionButton extends button {
    
    /**
     * Action button constructor
     * 
     * @param {string} bId Button identifier
     * @param {object} bCommand Button command
     * @param {string} tagName Tag name
     * @param {object} returnOK Button feedback return function
     * @param {object} returnNOK Button feedback error function
     * @param {object} onClick Button onClick function
     * @param {string} normalClass Button normal style class
     * @param {string} errorClass Button error style class
     */
    constructor(
            bId,
            bCommand,
            tagName,
            returnOK = null,
            returnNOK = null,
            onClick = null,
            normalClass = "btn btn-success",
            errorClass = "btn btn-danger")
    {
        
        // Call base constructor
        super(bId, normalClass, errorClass);
        
        // Create parser
        this._parser = new parserReadWrite();
        
        this._cmd = bCommand;
        this._tag = tagName;
        this._tagValue = 0;
        
        // Feedback functions
        this._returnOK = returnOK;
        this._returnNOK = returnNOK;
        
        // Attach click function
        if (onClick === null) {
            this._button.onclick = this.click;
        } else {
            this._button.onclick = onClick;
        }
    }
    
    /**
     * Button internal onClick function
     */
    click = () => {
        (async () => {
            
            try {
                // Execute button function
                let res = await this.execute();
                
                // Check feedback function
                if (this._returnOK !== null) {
                    // Call external function
                    this._returnOK(res);
                }
                
            } catch (err) {
                // Check error function
                if (this._returnNOK === null) {
                    // Set internal button error
                    this.setError(err);
                } else {
                    // Call external function
                    this._returnNOK(err);
                }
            }
            
        })();
    }
    
    /**
     * Set Tag value
     * 
     * @param {number} value
     */
    setTagValue(value) {
        
        this._tagValue = value;
    }
    
    /**
     * Button execute function
     * @returns {Promise}
     */
    async execute() {

        try {
            // Disable button
            this.disable();

            let response = 0;

            switch (this._cmd) {
                case parser.CMD.GET_BIT: response = await this._parser.getBit(this._tag); break;
                case parser.CMD.SET_BIT: response = await this._parser.setBit(this._tag); break;
                case parser.CMD.RESET_BIT: response = await this._parser.resetBit(this._tag); break;
                case parser.CMD.INVERT_BIT: response = await this._parser.invertBit(this._tag); break;
                case parser.CMD.GET_BITS: response = await this._parser.getBits(this._tag); break;
                case parser.CMD.SET_BITS: response = await this._parser.setBits(this._tag); break;
                case parser.CMD.GET_BYTE: response = await this._parser.getByte(this._tag); break;
                case parser.CMD.WRITE_BYTE: response = await this._parser.writeByte(this._tag, this._tagValue); break;
                case parser.CMD.GET_WORD: response = await this._parser.getWord(this._tag); break;
                case parser.CMD.WRITE_WORD: response = await this._parser.writeWord(this._tag, this._tagValue); break;
                case parser.CMD.GET_DWORD: response = await this._parser.getDWord(this._tag); break;
                case parser.CMD.WRITE_DWORD: response = await this._parser.writeDWord(this._tag, this._tagValue); break;
                case parser.CMD.GET_INT: response = await this._parser.getInt(this._tag); break;
                case parser.CMD.WRITE_INT: response = await this._parser.writeInt(this._tag, this._tagValue); break;
                case parser.CMD.GET_REAL: response = await this._parser.getReal(this._tag); break;
                case parser.CMD.WRITE_REAL: response = await this._parser.writeReal(this._tag, this._tagValue); break;
            }
            
            // Enable button
            this.enable();

            return Promise.resolve(response);
            
        } catch (err) {
            return Promise.reject(err);
        }
    }
}
