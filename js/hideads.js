var customclass = clientcfmonitor.customclass
var disablead = clientcfmonitor.disablead;

jQuery.noConflict();
jQuery(document).ready(function(){
// check if ad is disabled by excessive clicking or hidden by admin option
            jQuery("." + customclass).remove(); 
            });