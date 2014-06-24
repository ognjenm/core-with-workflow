<!DOCTYPE html>
<html 
    xmlns="http://www.w3.org/1999/xhtml" 
    xmlns:b3mn="http://b3mn.org/2007/b3mn" 
    xmlns:ext="http://b3mn.org/2007/ext" 
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" 
    xmlns:atom="http://b3mn.org/2007/atom+xhtml">
    <head profile="http://purl.org/NET/erdf/profile">

        <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />
        <link rel="schema.dcTerms" href="http://purl.org/dc/terms/ " />
        <link rel="schema.b3mn" href="http://b3mn.org" />
        <link rel="schema.oryx" href="http://oryx-editor.org/" />
        <link rel="schema.raziel" href="http://raziel.org/" />

        <title>Oryx</title>

        <base href="{{ \Config::get('app.url') }}" />

        {{ HTML::style('packages/telenok/core/js/ext-2.0.2/resources/css/ext-all.css') }}
        {{ HTML::style('packages/telenok/core/js/ext-2.0.2/resources/css/xtheme-gray.css') }}
        {{ HTML::style('packages/telenok/core/js/oryx/css/theme_norm.css') }}

        {{ HTML::script('packages/telenok/core/js/prototype-1.5.1.js') }}
        {{ HTML::script('packages/telenok/core/js/ext-2.0.2/adapter/ext/ext-base.js') }}
        {{ HTML::script('packages/telenok/core/js/ext-2.0.2/ext-all.js') }}
        {{ HTML::script('packages/telenok/core/js/ext-2.0.2/color-field.js') }}
        {{ HTML::script('packages/telenok/core/js/ext-2.0.2/plugin/grid.search.js') }}

        {{ HTML::script('packages/telenok/core/js/oryx/scripts/i18n/translation_en.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/path_parser.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/utils.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/config.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/kickstart.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/erdfparser.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/datamanager.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/clazz.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/SVG/editpathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/SVG/minmaxpathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/SVG/pointspathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/SVG/svgmarker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/SVG/svgshape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/SVG/label.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/Math/math.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/StencilSet/stencil.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/StencilSet/property.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/StencilSet/propertyitem.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/StencilSet/rules.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/StencilSet/stencilset.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/StencilSet/stencilsets.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/bounds.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/command.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/uiobject.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/abstractshape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/canvas.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/main.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/svgDrag.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/shape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/Controls/control.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/Controls/docker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/Controls/magnet.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/node.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/edge.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/abstractPlugin.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/Core/abstractLayouter.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/scripts/oryx.js') }}
        {{ HTML::script('packages/telenok/core/js/jquery.js') }}
        
        <script type='text/javascript'>
            
            jQuery.noConflict();
            
            var ORYX_LOGLEVEL = 0;
            var oryxEditor = null;

            ORYX.CONFIG.ROOT_PATH = "{{ \Config::get('app.url') }}";
            ORYX.PATH = ORYX.CONFIG.ROOT_PATH + "/packages/telenok/core/js/oryx/";
            ORYX.CONFIG.WEB_URL = "http://oryx-project.org";

            ORYX.CONFIG.PLUGINS_FOLDER = "scripts/Plugins/";
            ORYX.CONFIG.PLUGINS_CONFIG = ORYX.PATH + "scripts/Plugins/plugins.xml";

            ORYX.CONFIG.SS_EXTENSIONS_FOLDER = ORYX.PATH + "scripts/stencilsets/extensions/";
            ORYX.CONFIG.SS_EXTENSIONS_CONFIG = ORYX.PATH + "scripts/stencilsets/extensions/extensions.json";
            
            Ext.BLANK_IMAGE_URL = ORYX.CONFIG.ROOT_PATH + "/packages/telenok/core/js/ext-2.0.2/resources/images/default/s.gif";

            function init() 
            { 
                ORYX.Editor.setMissingClasses();
                oryxEditor = new ORYX.Editor({
                    id: 'oryx-{{uniqid()}}',
                    fullscreen: false,
                    stencilset: {
                        url: "{{ URL::route("cmf.module.workflow-process.diagram.stensilset") }}"
                    }
                });
                
                if (typeof importJSONFromTop !== 'undefined')
                {
                    //oryxEditor.importJSON(importJSONFromTop());
                }
                else 
                {
                @if ($model && $model->process)
                    //oryxEditor.importJSON('{{$model->process}}');
                @endif
                }
            };
            
        </script>
    </head>
    <body style="overflow:hidden;"></body>
</html>