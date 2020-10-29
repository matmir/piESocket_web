
// Javascript error object
export class jsError {
    
    /**
     * Add error message
     * 
     * @param {string} err Error message
     */
    static add(err) {
        let errDiv = document.getElementById('jsError');
        errDiv.style = "display: block";
        errDiv.innerHTML += err + "<br />";
    }
}
