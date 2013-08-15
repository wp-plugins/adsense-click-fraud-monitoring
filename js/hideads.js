var customclass = clientcfmonitor.customclass
var disablead = clientcfmonitor.disablead;

jQuery.noConflict();
jQuery(document).ready(function(){
// check if ad is disabled by excessive clicking or hidden by admin option
            if (disablead === 'true'){
            jQuery("." + customclass).remove();
            if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
            }else{
            jQuery("." + customclass).remove(); 
            if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
            }       
});