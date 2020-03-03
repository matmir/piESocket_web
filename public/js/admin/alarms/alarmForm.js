$(document).ready(function(){
    
    var tagNameSearch = new createTagListSearch('tagName-datalist', '/admin/tags/search');
    var tagFbNotAckSearch = new createTagListSearch('tagFbNotAck-datalist', '/admin/tags/search');
    var tagHWAckSearch = new createTagListSearch('tagHWAck-datalist', '/admin/tags/search');
    var tagTypeSearch = new createTagTypeSearch('/admin/tags/search/1', tagTypeFound);
    
    var alarmTriggerB = $('#alarmTriggerB');
    var alarmTriggerN = $('#alarmTriggerN');
    var alarmTriggerR = $('#alarmTriggerR');
    var alarmTrigger = $('#alarm_form_adTrigger');
    
    // Feedback function (tag type found)
    function tagTypeFound(type) {
        
        var oldval = alarmTrigger.val();
        
        if (type==='Real' || type==='Numeric') {
            if (oldval==='1') {
                alarmTrigger.val('2').trigger('change');
            } else {
                alarmTrigger.val(oldval).trigger('change');
            }
        } else {
            if (oldval!=='1') {
                alarmTrigger.val('1').trigger('change');
            } else {
                alarmTrigger.val(oldval).trigger('change');
            }
        }
        
    }
    
    // Update numeric fields
    function updateNumericFields(enable) {
        
        if (enable===true) {
            if (tagTypeSearch.getType()==='Real') {
                alarmTriggerN.hide();
                alarmTriggerR.show();
            } else if (tagTypeSearch.getType()==='Numeric') {
                alarmTriggerN.show();
                alarmTriggerR.hide();
            } else {
                alarmTriggerN.hide();
                alarmTriggerR.hide();
            }
        } else {
            alarmTriggerN.hide();
            alarmTriggerR.hide();
        }
        
    }
    
    // Search tag type
    tagTypeSearch.search($('#alarm_form_adTagName').val());
    
    alarmTrigger.on('change', function() {
        
        if (this.value === '1') {
            alarmTriggerB.show();
            updateNumericFields(false);
        } else {
            alarmTriggerB.hide();
            updateNumericFields(true);
        }
        
    });
    
    // Change tag name
    $('#alarm_form_adTagName').on('change paste keyup', function() {
        
        // Update list
        tagNameSearch.update(this.value);
        
        // Search tag type
        tagTypeSearch.search(this.value);
        
    });
    
    // Change feedback not ack tag
    $('#alarm_form_adFeedbackNotACK').on('change paste keyup', function() {
        
        // Update list
        tagFbNotAckSearch.update(this.value);
        
    });
    
    // Change HW ack tag
    $('#alarm_form_adHWAck').on('change paste keyup', function() {
        
        // Update list
        tagHWAckSearch.update(this.value);
        
    });
    
});