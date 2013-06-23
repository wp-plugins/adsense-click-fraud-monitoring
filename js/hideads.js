var customclass = clientcfmonitor.customclass
var disablead = clientcfmonitor.disablead;

jQuery.noConflict();
jQuery(document).ready(function(){
// check if ad is disabled or hidden
            if (disablead === 'true'){
            jQuery("." + customclass).remove();
            event.preventDefault();
            }else{
            jQuery("." + customclass).remove(); 
            event.preventDefault();
            }
           
});