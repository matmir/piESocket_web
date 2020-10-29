
// Javascript utils
export class utils {
    
    /**
     * Show/Hide TR element
     * 
     * @param {object} element TR element
     * @param {bool} flag Show/Hide flag
     */
    static showTR(element, flag=true) {
        
        if (flag === false) {
            element.style.display = "none";
        } else {
            element.style.display = "";
        }
    }
}
