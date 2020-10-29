
import {jsError} from './../../jsError.js';
import {utils} from './../../utils.js';
import {tagListSearch} from './../tags/tagListSearch.js';

// Page loaded
document.addEventListener('DOMContentLoaded', function () {
    
    let tagSearch = new tagListSearch('tagLogger-datalist');
    
    let loggerIntervalS = document.getElementById('tagsLoggerIntervalS');
    let loggerInterval = document.getElementById('tag_logger_form_ltInterval');
    let loggerTagName = document.getElementById('tag_logger_form_ltTagName');
    
    // Events
    loggerInterval.addEventListener('change', intervalChanged);
    
    loggerTagName.addEventListener('change', tagNameChanged);
    loggerTagName.addEventListener('paste', tagNameChanged);
    loggerTagName.addEventListener('keyup', tagNameChanged);
    
    // Interval value changed
    function intervalChanged(e) {
        if (e.target.value === '5') {
            utils.showTR(loggerIntervalS);
        } else {
            utils.showTR(loggerIntervalS, false);
        }
    };
    
    // Tag name changed
    function tagNameChanged(e) {
        // Update list
        tagSearch.update(e.target.value).catch(
            error => { jsError.add(error); }
        );
    };
});