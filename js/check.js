/* get the vars from CDATA clientcfmonitor */
var clientIP= clientcfmonitor.client_ip;
var maxclickcount = clientcfmonitor.clickcount;
var bannedperiod = clientcfmonitor.bannedperiod;
/*var preurl = clientcfmonitor.preurl;*/
var nonceUpdate = AjaxUpdateClicks.nonce;
var nonceCount = AjaxCheckClicks.nonce;
var preurl = AjaxUpdateClicks.ajaxurl + "?action=ajax-updateclicks&nonce=" + nonceUpdate;
var counturl = AjaxCheckClicks.ajaxurl + "?action=ajax-checkclicks&nonce=" + nonceCount;
var firstclickdata = clientcfmonitor.firstclickdate;
var firstclickdate = firstclickdata[0][1]-1;
var updatedVisitCount = parseInt(clientcfmonitor.updatedVisitCount);
//var currentURL = window.location.pathname;
var currentURL = clientcfmonitor.currentURL;

var jq = null;

var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
var clickdate = new Date(firstclickdata[0][0],firstclickdate,firstclickdata[0][2],firstclickdata[1][0],firstclickdata[1][1],firstclickdata[1][2]);
var currDate = new Date();
var secondDate = clickdate.setTime(clickdate.getTime() + (bannedperiod * 24 * 60 * 60 * 1000));
var endDate = new Date(secondDate);
var customclass = clientcfmonitor.customclass;
var firstclick  = clientcfmonitor.firstclick;
var disablead = clientcfmonitor.disablead;

jq = jQuery.noConflict();

var wrapperclass = "." + customclass;



jq.cfmonitor = {
        cfmonitor_options: {
            maxClicks            : 2, // Maximum clicks per client
            countDown            : 5, // Seconds to check if client can still see the ads
            defaultElements      : wrapperclass, // guard ads in the container of given elements.
            dataMethod           :  'GET', // GET data
            checkDataUrl         : false, // data URL for checking client. Example: 'adsguard.php?action=check'.
            clickDataUrl         : false, // data URL for adding client. Example: 'adsguard.php?action=click'
            additionalData       : {}, // Additional data object
            blockOnError         : true, // Turn on - off error warning
            blockTitle           : 'Critical Error', // Critical Error title.
            thanksTitle          : false, // Thank you title.
            thanksMsg            : false, // Thank you message.
            thanksTimer          : 3 // Seconds to remove after displaying thank you message
        },
        elements: [],
        iframes: []
    };

var cfmonitor_options = jq.extend(jq.cfmonitor.cfmonitor_options, cfmonitor_options);
var isOverIFrame = false;
        var object       = this;


function cfmonitorProcess(event)
{

    var clientdata = {"clientIP": clientIP, "visitcount": updatedVisitCount};
    if (clientdata.clientIP) {
        updatedVisitCount = ++clientdata.visitcount;
        //alert ("Its clientIP" + updatedVisitCount);

    } else {
        updatedVisitCount = (updatedVisitCount) ? (updatedVisitCount + 1) : 1;
        //alert (updatedVisitCount);

    }


    if (updatedVisitCount >= maxclickcount)
    {
        //alert(updatedVisitCount + "blocked");
        jq = jQuery.noConflict();
        // check if ad is disabled by excessive clicken or hidden by admin option
        if (disablead === 'true') {
            jQuery("." + customclass).remove();
            //event.preventDefault();
        } else {
            jQuery("." + customclass).remove();
            //event.preventDefault();
        }
    }

}
/* update clicks */
function ajax_post()
{
    var postData = {
                    count: updatedVisitCount,
                    clickurl: currentURL        
                    }
    
    jq.ajax({
        type: "post",
        dataType: "json",
        url: preurl,
        //async : "false",
        //cache : "false",
        /*data: {count: updatedVisitCount,
               clickurl: currentURL
        },*/
        data: postData,
        success: function(response) {

            //alert("updatedVisitCount" + response.success);
            cfmonitorProcess();
            //countajaxclicks();
            if (response.success)
            {
                return response.success;
            }
            else
            {
                return 0;
            }
        }
    });
}

/* check */

function checkit()
{
    // check if first click is enabled
    if (updatedVisitCount === 0 && firstclick === 'true') {
        //alert (updatedVisitCount + "firstclick true");
        jQuery("." + customclass).remove();
        if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
    }

    if (updatedVisitCount >= maxclickcount)
    {
        //reloadpage();
        //alert (updatedVisitCount + "blocked");
        jq = jQuery.noConflict();
        // check if ad is disabled or hidden
        if (disablead === 'true') {
            jQuery("." + customclass).remove();
            /* preventDefault must be checked first because IE does not support it.*/
            if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
        } else {
            jQuery("." + customclass).remove();
            if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
        }
        //alert (clientIP + 'Count' + updatedVisitCount);
    }
}

/* count clicks */
function countajaxclicks()
{
    jq.ajax({
        type: "POST",
        dataType: "json",
        cache: false,
        //url: "http://127.0.0.1/dev/wp-content/plugins/cfmonitor/checkclicks.php", only for testing
        url: counturl,
        success: function(data) {
            updatedVisitCount = data.clicks;
            //alert (updatedVisitCount);
            checkit();
            return data.clicks;
        }
    });
}

// SAVE IFRAMES TO GLOBAL IFRAMES ARRAY
        function saveIframes (iframes) {
            iframes = jq(iframes).get();
            
            // MULTI
            if(iframes.length > 1){
                jq.each(iframes, function(i, val){
                    jq.cfmonitor.iframes.push(val);
                });
            }
            // SINGLE
            else jq.cfmonitor.iframes.push(iframes);
        }

 // FIND IFRAMES
        function findIframes () {
            jq.each(jq.cfmonitor.elements,function (index, element) {
                // IFRAME(S) FOUND
                if (jq(element).find('iframe').length > 0) {
                    //alert ("iframe found1");
                    
                    var frames = jq(element).find('iframe');
                    frames = frames.get();
                    saveIframes(frames);
                } 
            });
            
            iframeAction(jq.cfmonitor.iframes);
            
        }
        
        // FIND IFRAMES
        /*function findIframes () {
            $.each($.adsguard.elements,function (index, element) {
                // IFRAME(S) FOUND
                if ($(element).find('iframe').length > 0) {
                    frames = $(element).find('iframe');
                    frames = frames.get();
                    saveIframes(frames);
                } 
            });
            
            iframeAction($.adsguard.iframes);
        }*/
        
        // IFRAME ACTION
        function iframeAction () {
            jq.each(jq.cfmonitor.iframes, function(index,element) {
                frameID = jq(element).attr('id') || false;
                if (frameID) initiateIframe(frameID);
                //alert (frameID);
            });
        }
        
        // INIT IFRAME
        function initiateIframe(elementID) {
            var element = document.getElementById(elementID);
            // MOUSE IN && OUT
            if (element) {
                element.onmouseover = processMouseOver;
                element.onmouseout = processMouseOut;
                //console.log("mouse on out");
            }
            // CLICKS
            if (typeof window.attachEvent !== 'undefined') {
                top.attachEvent('onblur', processIFrameClick);
            }
            else if (typeof window.addEventListener !== 'undefined') {
                top.addEventListener('blur', processIFrameClick, false);
            }
        }
        
        // IN IFRAME
        function processMouseOut() {
            isOverIFrame = false;
            top.focus();
        }
        
        // OUTSIDE OF IFRAME
        function processMouseOver() {
            isOverIFrame = true;
        }
        
        // IFRAME CLICKS
        function processIFrameClick() {
            // ADDA CLICK
            if(isOverIFrame) {
                //addClick();
                ajax_post();
                //console.log("Go");
                top.focus();
            }
        }

// SAVE ELEMENTS TO GLOBAL ELEMENTS ARRAY
        function saveElements (elements) {
            elements = jq(elements).get();
            // MULTI
            if(elements.length > 1){
                jq.each(elements, function(i, val){
                    jq.cfmonitor.elements.push(val);
                });
            }
            // SINGLE
            else jq.cfmonitor.elements.push(elements);
        }

// UPDATE ELEMENTS
        function updateElements() {
            jq.cfmonitor.elements = [];
            //console.log("UpdateElements");
            //alert ("update elements");
            // DEFAULT ITEMS
            if (cfmonitor_options.defaultElements.length > 1) saveElements(cfmonitor_options.defaultElements);
        }

/* Beginn */
jq(document).ready(function($)
{
    // UPDATE ELEMENTS -> VALIDATE SETTINGS -> ADD ACTION
    updateElements();
    //alert (jq.cfmonitor.options['defaultElements']);
    countajaxclicks();
    
    // FIND IFRAMES
    findIframes();

    jq("." + customclass).click(function(event) {
    ajax_post();
    });
});