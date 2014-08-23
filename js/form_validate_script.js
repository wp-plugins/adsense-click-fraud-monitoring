jQuery.noConflict();

jQuery(document).ready(function() {

        
	jQuery("#cfmonitor-conf").validate();
        /* no ads */
        jQuery('#cfmonitor_noads').click(function() {
        if (jQuery('#cfmonitor_noads').attr('checked')) {
            jQuery('#cfmonitor_noads').val('true');
        } else {
            jQuery('#cfmonitor_noads').val('false');
        } 
        });
        /* Block first click */
        jQuery('#cfmonitor_blockfirst').click(function() {
        if (jQuery('#cfmonitor_blockfirst').attr('checked')) {
            jQuery('#cfmonitor_blockfirst').val('true');
        } else {
            jQuery('#cfmonitor_blockfirst').val('false');
        } 
        });
         /* Open advanced settings */

        jQuery("#click-cfadvanced").click(function() {
         jQuery("#div-cfadvanced").fadeIn("fast");   
        });
        
        jQuery("#clickgetmyip").click(function(){
            var currentVal = jQuery("#cfmonitor_myip").val();
            if (!currentVal)
                currentVal = '';
            
            if (currentVal.indexOf(cfclientip) >= 0)
                return;

            currentVal += (currentVal.length > 0 ? ', ' : '') + cfclientip;

            jQuery("#cfmonitor_myip").val(currentVal);
        });

});