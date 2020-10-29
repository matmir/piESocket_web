
import {jsError} from './../../jsError.js';
import {tagListSearch} from './../tags/tagListSearch.js';

// Page loaded
document.addEventListener('DOMContentLoaded', function () {
    
    let tagNameSearch = new tagListSearch('tagName-datalist');
    let tagFbRunSearch = new tagListSearch('tagFbRun-datalist');
    
    let scriptTagName = document.getElementById('script_item_form_scTagName');
    let scriptFbRun = document.getElementById('script_item_form_scFeedbackRun');
    
    // Events
    scriptTagName.addEventListener('change', tagNameChanged);
    scriptTagName.addEventListener('paste', tagNameChanged);
    scriptTagName.addEventListener('keyup', tagNameChanged);
    
    scriptFbRun.addEventListener('change', fbTagChanged);
    scriptFbRun.addEventListener('paste', fbTagChanged);
    scriptFbRun.addEventListener('keyup', fbTagChanged);
    
    // Change tag name
    function tagNameChanged(e) {
        // Update list
        tagNameSearch.update(e.target.value).catch(
            error => { jsError.add(error); }
        );
    };
    
    // Change feedback run tag
    function fbTagChanged(e) {
        // Update list
        tagFbRunSearch.update(e.target.value).catch(
            error => { jsError.add(error); }
        );
    };
});