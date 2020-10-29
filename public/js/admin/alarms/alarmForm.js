
import {jsError} from './../../jsError.js';
import {utils} from './../../utils.js';
import {tagListSearch} from './../tags/tagListSearch.js';
import {tagTypeSearch} from './../tags/tagTypeSearch.js';

// Page loaded
document.addEventListener('DOMContentLoaded', function () {
    
    let tagNameSearch = new tagListSearch('tagName-datalist');
    let tagFbNotAckSearch = new tagListSearch('tagFbNotAck-datalist');
    let tagHWAckSearch = new tagListSearch('tagHWAck-datalist');
    let tagType = new tagTypeSearch();
    
    let alarmTriggerB = document.getElementById('alarmTriggerB');
    let alarmTriggerN = document.getElementById('alarmTriggerN');
    let alarmTriggerR = document.getElementById('alarmTriggerR');
    let alarmTrigger = document.getElementById('alarm_form_adTrigger');
    let alarmTagName = document.getElementById('alarm_form_adTagName');
    let alarmFeedbackNotACK = document.getElementById('alarm_form_adFeedbackNotACK');
    let alarmHWAck = document.getElementById('alarm_form_adHWAck');
    
    // Events
    alarmTrigger.addEventListener('change', alarmTriggerChanged);
    
    alarmTagName.addEventListener('change', tagNameChanged);
    alarmTagName.addEventListener('paste', tagNameChanged);
    alarmTagName.addEventListener('keyup', tagNameChanged);
    
    alarmFeedbackNotACK.addEventListener('change', feedbackNotACKChanged);
    alarmFeedbackNotACK.addEventListener('paste', feedbackNotACKChanged);
    alarmFeedbackNotACK.addEventListener('keyup', feedbackNotACKChanged);
    
    alarmHWAck.addEventListener('change', HWAckChanged);
    alarmHWAck.addEventListener('paste', HWAckChanged);
    alarmHWAck.addEventListener('keyup', HWAckChanged);
    
    // Search tag type
    searchType(alarmTagName.value);
    
    // Search tag type
    function searchType(tagName) {
        
        tagType.search(tagName).then(
            reply => { tagTypeFound(reply); },
            error => { jsError.add(error); }
        );
    }
    
    // Feedback function (tag type found)
    function tagTypeFound(type) {
        
        let oldval = alarmTrigger.value;
        
        if (type==='Real' || type==='Numeric') {
            if (oldval==='1') {
                alarmTrigger.value = '2';
                alarmTrigger.dispatchEvent(new Event('change'));
            } else {
                alarmTrigger.value = oldval;
                alarmTrigger.dispatchEvent(new Event('change'));
            }
        } else {
            if (oldval!=='1') {
                alarmTrigger.value = '1';
                alarmTrigger.dispatchEvent(new Event('change'));
            } else {
                alarmTrigger.value = oldval;
                alarmTrigger.dispatchEvent(new Event('change'));
            }
        }
        
    }
    
    // Update numeric fields
    function updateNumericFields(enable) {
        
        if (enable===true) {
            if (tagType.getType()==='Real') {
                utils.showTR(alarmTriggerN, false);
                utils.showTR(alarmTriggerR);
            } else if (tagType.getType()==='Numeric') {
                utils.showTR(alarmTriggerN);
                utils.showTR(alarmTriggerR, false);
            } else {
                utils.showTR(alarmTriggerN, false);
                utils.showTR(alarmTriggerR, false);
            }
        } else {
            utils.showTR(alarmTriggerN, false);
            utils.showTR(alarmTriggerR, false);
        }
        
    }
    
    // Change alarm trigger
    function alarmTriggerChanged(e) {
        
        if (e.target.value === '1') {
            utils.showTR(alarmTriggerB);
            updateNumericFields(false);
        } else {
            utils.showTR(alarmTriggerB, false);
            updateNumericFields(true);
        }
    };
    
    // Change tag name
    function tagNameChanged(e) {
        // Update list
        tagNameSearch.update(e.target.value).then(
            reply => { searchType(e.target.value); },
            error => { jsError.add(error); }
        );
    };
    
    // Change feedback not ack tag
    function feedbackNotACKChanged(e) {
        // Update list
        tagFbNotAckSearch.update(e.target.value).catch(
            error => { jsError.add(error); }
        );
    };
    
    // Change HW ack tag
    function HWAckChanged(e) {
        // Update list
        tagHWAckSearch.update(e.target.value).catch(
            error => { jsError.add(error); }
        );
    };
});