$(document).ready(function(){
    
    var tagSearch = new createTagListSearch('tagLogger-datalist', '/admin/tags/search');
    
    // Change interval value
    $('#tag_logger_form_ltInterval').on('change', function() {
        
        if (this.value === '5') {
            $('#tagsLoggerIntervalS').show();
        } else {
            $('#tagsLoggerIntervalS').hide();
        }
        
    });
    
    // Change tag name
    $('#tag_logger_form_ltTagName').on('change paste keyup', function() {
        
        // Update list
        tagSearch.update(this.value);
        
    });
    
});