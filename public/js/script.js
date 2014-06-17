
jQuery(function() 
{
    jQuery('a').on('focus', function() 
    {
        this.blur();
    });
    
    jQuery('div.sidebar-shortcuts button.telenok-module-group-content').click();
    
    jQuery('ul.telenok-module-group ul.submenu li a, ul.telenok-module-group li.parent-single a').click(function() 
    {
        jQuery('ul.telenok-module-group li').removeClass('active');
        jQuery(this).parents('ul.telenok-module-group li').addClass('active');
    });
});
  

var telenok = function() 
{
    var presentationList = {};
    var presentationObject = {};
    var moduleList = {};
    var moduleCallback = {};
    
    return {
        setBreadcrumbs: function(param) 
        {
            var $parent = jQuery('div.breadcrumbs ul.breadcrumb');
            jQuery('li:gt(0), .divider', $parent).remove();
            jQuery.each(param, function(i, v){
                $parent.append('<li class="active">' + v + '</li>');
            });
            //jQuery('li:lt(' + param.length + ')', $parent).append('<span class="divider"><i class="icon-angle-right"></i></span>');
        },
        getPresentationDomId: function(presentation) { return 'telenok-' + presentation + '-presentation'; },
        addPresentation: function(presentation, func) { presentationList[presentation] = func; },
        hasPresentation: function(presentation) { if (presentationList[presentation]) return true; else false; },
        callPresentation: function(presentation, param) { return presentationList[presentation](param); },
        addModule: function(moduleKey, url, callback) 
        { 
            if (!moduleList[moduleKey])
            {
                jQuery.ajax({
                    url: url,
                    method: 'get',
                    dataType: 'json'
                }).done(function(data) {
                    moduleList[moduleKey] = data;
                    callback();
                });
            }
            else
            {
                callback();
            }
        },
        getPresentationByKey: function(presentation) { return presentationObject[presentation]; },
        setPresentationObject: function(param) 
        {
            if (telenok.hasPresentation(param.presentation))
            {
                presentationObject[param.presentation] = telenok.callPresentation(param.presentation, param);
            }

        },
        processModuleContent: function(moduleKey) { 

            var param = moduleList[moduleKey];
            var domId = telenok.getPresentationDomId(param.presentation);

            if (!jQuery('.page-content #' + domId).length)
            {
                jQuery('.page-content').append('<div id="' + domId + '" class="telenok-presentation row ui-helper-hidden">' + param.presentationContent + '</div>');
            }

            jQuery('.page-content div.telenok-presentation').hide();
            jQuery('.page-content div#' + domId).show();

            if (telenok.hasPresentation(param.presentation))
            {
                presentationObject[param.presentation] = telenok.callPresentation(param.presentation, param);
            }
        }
    };
}();
