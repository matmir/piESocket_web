$(document).ready(function(){
        
    updateMode();
        
    // Change mode
    $('#driver_modbus_form_mode').on('change', function() {
        
        if (this.value === '1') {
            showTCP();
        } else {
            showRTU();
        }
        
    });
    
    function updateMode() {
        
        if ($('#driver_modbus_form_mode').val() === '1') {
            showTCP();
        } else {
            showRTU();
        }
    }
    
    function showTCP() {
        
        $('#TCP_addr').show();
        $('#TCP_port').show();
        
        $('#RTU_port').hide();
        $('#RTU_baud').hide();
        $('#RTU_parity').hide();
        $('#RTU_dataBit').hide();
        $('#RTU_stopBit').hide();
    }
    
    function showRTU() {
        
        $('#TCP_addr').hide();
        $('#TCP_port').hide();
        
        $('#RTU_port').show();
        $('#RTU_baud').show();
        $('#RTU_parity').show();
        $('#RTU_dataBit').show();
        $('#RTU_stopBit').show();
    }
    
});