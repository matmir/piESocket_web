$(document).ready(function(){
        
    updateMode();
    updateSlaveID();
        
    // Change mode
    $('#driver_modbus_form_mode').on('change', function() {
        
        if (this.value === '1') {
            showTCP();
        } else {
            showRTU();
        }
        
        updateSlaveID();
    });
    
    function updateMode() {
        
        if ($('#driver_modbus_form_mode').val() === '1') {
            showTCP();
        } else {
            showRTU();
        }
    }
    
    // Change slaveID usage
    $('#driver_modbus_form_TCP_use_slaveID').on('change', function() {
                
        updateSlaveID();
    });
    
    function updateSlaveID() {
        
        if ($('#driver_modbus_form_mode').val() === '1') {
            if ($('#driver_modbus_form_TCP_use_slaveID').val() === '1') {
                $('#slaveID').show();
            } else {
                $('#slaveID').hide();
            }
        }
    }
    
    function showTCP() {
        
        $('#TCP_addr').show();
        $('#TCP_port').show();
        $('#TCP_use_slaveID').show();
        
        $('#slaveID').hide();
        $('#RTU_port').hide();
        $('#RTU_baud').hide();
        $('#RTU_parity').hide();
        $('#RTU_dataBit').hide();
        $('#RTU_stopBit').hide();
    }
    
    function showRTU() {
        
        $('#TCP_addr').hide();
        $('#TCP_port').hide();
        $('#TCP_use_slaveID').hide();
        
        $('#slaveID').show();
        $('#RTU_port').show();
        $('#RTU_baud').show();
        $('#RTU_parity').show();
        $('#RTU_dataBit').show();
        $('#RTU_stopBit').show();
    }
    
});