$(document).ready(function(){
    
    var tagNameSearch = new createTagListSearch('tagName-datalist', '/admin/tags/search');
    var tagFbRunSearch = new createTagListSearch('tagFbRun-datalist', '/admin/tags/search');
    
    // Change tag name
    $('#script_item_form_scTagName').on('change paste keyup', function() {
        
        // Update list
        tagNameSearch.update(this.value);
        
    });
    
    // Change feedback run tag
    $('#script_item_form_scFeedbackRun').on('change paste keyup', function() {
        
        // Update list
        tagFbRunSearch.update(this.value);
        
    });
    
});