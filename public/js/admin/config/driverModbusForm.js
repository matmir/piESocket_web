
import {utils} from './../../utils.js';

// Page loaded
document.addEventListener('DOMContentLoaded', function () {
    
    let slaveId = document.getElementById('slaveID');
    let TCP_addr = document.getElementById('TCP_addr');
    let TCP_port = document.getElementById('TCP_port');
    let TCP_use_slaveID = document.getElementById('TCP_use_slaveID');
    let RTU_port = document.getElementById('RTU_port');
    let RTU_baud = document.getElementById('RTU_baud');
    let RTU_parity = document.getElementById('RTU_parity');
    let RTU_dataBit = document.getElementById('RTU_dataBit');
    let RTU_stopBit = document.getElementById('RTU_stopBit');
    
    let modbusMode = document.getElementById('driver_modbus_form_mode');
    let modbusUseSlaveID = document.getElementById('driver_modbus_form_TCP_use_slaveID');
    
    // Events
    modbusMode.addEventListener('change', modbusModeChanged);
    modbusUseSlaveID.addEventListener('change', modbusUseSlaveIdChanged);
    
    updateMode();
    updateSlaveID();
        
    // Change mode
    function modbusModeChanged(e) {
        if (e.target.value === '1') {
            showTCP();
        } else {
            showRTU();
        }
        
        updateSlaveID();
    };
    
    // Change slaveID usage
    function modbusUseSlaveIdChanged(e) {
        updateSlaveID();
    };
    
    // Update modbus mode
    function updateMode() {
        if (modbusMode.value === '1') {
            showTCP();
        } else {
            showRTU();
        }
    }
    
    // Update slaveId usage
    function updateSlaveID() {
        if (modbusMode.value === '1') {
            if (modbusUseSlaveID.value === '1') {
                utils.showTR(slaveId);
            } else {
                utils.showTR(slaveId, false);
            }
        }
    }
    
    // Show TCP controls
    function showTCP() {
        utils.showTR(TCP_addr);
        utils.showTR(TCP_port);
        utils.showTR(TCP_use_slaveID);
        
        utils.showTR(slaveId, false);
        utils.showTR(RTU_port, false);
        utils.showTR(RTU_baud, false);
        utils.showTR(RTU_parity, false);
        utils.showTR(RTU_dataBit, false);
        utils.showTR(RTU_stopBit, false);
    }
    
    // Show RTU controls
    function showRTU() {
        utils.showTR(TCP_addr, false);
        utils.showTR(TCP_port, false);
        utils.showTR(TCP_use_slaveID, false);
        
        utils.showTR(slaveId);
        utils.showTR(RTU_port);
        utils.showTR(RTU_baud);
        utils.showTR(RTU_parity);
        utils.showTR(RTU_dataBit);
        utils.showTR(RTU_stopBit);
    }
});