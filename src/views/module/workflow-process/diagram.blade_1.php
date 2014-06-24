<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:bpmnplus="http://b3mn.org/stencilset/bpmnplus#"
      xmlns:bpel="http://b3mn.org/stencilset/bpel#"
      xmlns:bpel4chor="http://b3mn.org/stencilset/bpel4chor#"
      xmlns:b3mn="http://b3mn.org/2007/b3mn"
      xmlns:ext="http://b3mn.org/2007/ext"
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:atom="http://b3mn.org/2007/atom+xhtml">
    <head profile="http://purl.org/NET/erdf/profile">
        <title>Oryx-Editor - Oryx</title>
        
        <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />
        <link rel="schema.dcTerms" href="http://purl.org/dc/terms/" />
        <link rel="schema.b3mn" href="http://b3mn.org" />
        <link rel="schema.oryx" href="http://oryx-editor.org/" />
        <link rel="schema.raziel" href="http://raziel.org/" />


        {{ HTML::style('packages/telenok/core/js/oryx/lib/ext-2.0.2/resources/css/ext-all.css') }}
        {{ HTML::style('packages/telenok/core/js/oryx/lib/ext-2.0.2/resources/css/xtheme-gray.css') }}
        {{ HTML::style('packages/telenok/core/js/oryx/css/theme_norm.css') }}


        {{ HTML::script('packages/telenok/core/js/oryx/lib/prototype.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/path_parser.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/ext-2.0.2/adapter/ext/ext-base.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/ext-2.0.2/ext-all.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/ext-2.0.2/color-field.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/i18n/translation_en.js')}}
        
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/utils.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/kickstart.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/erdfparser.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/datamanager.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/clazz.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/config.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/oryx.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/SVG/editpathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/SVG/minmaxpathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/SVG/pointspathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/SVG/svgmarker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/SVG/svgshape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/SVG/label.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/Math/math.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/StencilSet/stencil.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/StencilSet/property.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/StencilSet/propertyitem.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/StencilSet/complexpropertyitem.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/StencilSet/rules.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/StencilSet/stencilset.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/StencilSet/stencilsets.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/command.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/bounds.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/uiobject.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/abstractshape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/canvas.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/main.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/svgDrag.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/shape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/Controls/control.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/Controls/docker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/Controls/magnet.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/node.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/edge.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/abstractPlugin.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/Core/abstractLayouter.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/lib/oryx/utils.js') }}

        
        <script type="text/javascript">
            if(!ORYX) var ORYX = {};
            if(!ORYX.CONFIG) ORYX.CONFIG = {};
            
            ORYX.PATH = '{{\Config::get('app.url')}}/packages/telenok/core/js/oryx/';
            ORYX.CONFIG.ROOT_PATH = '{{\Config::get('app.url')}}/packages/telenok/core/js/oryx/';
            ORYX.CONFIG.WEB_URL = "{{\Config::get('app.url')}}/";
            ORYX.CONFIG.SSEXTS = [];
            ORYX.CONFIG.SSET = "{{ URL::route("cmf.module.workflow-process.diagram.stensilset") }}";
            ORYX.CONFIG.SERVER_HANDLER_ROOT = "";
            ORYX.CONFIG.PROFILE_PATH = ORYX.CONFIG.ROOT_PATH + "profiles/";
            ORYX.CONFIG.PLUGINS_CONFIG = ORYX.CONFIG.PROFILE_PATH + "telenok.xml";
            ORYX.CONFIG.SS_EXTENSIONS_FOLDER = ORYX.CONFIG.ROOT_PATH + "stencilsets/extensions/";
            ORYX.CONFIG.SS_EXTENSIONS_CONFIG = ORYX.CONFIG.ROOT_PATH + "stencilsets/extensions/extensions.json";
            Ext.BLANK_IMAGE_URL = ORYX.PATH + 'lib/ext-2.0.2/resources/images/default/s.gif';

            var init = function() { 
                ORYX.Log.debug("Creating Editor instance.");
                // Hack for WebKit to set the SVGElement-Classes
                ORYX.Editor.setMissingClasses();
                // If someone wants to create the editor instance himself
                oryxEditor = new ORYX.Editor({
                    id: 'oryx-{{uniqid()}}',
                    fullscreen: true,
                    stencilset: {
                        url: "{{ URL::route("cmf.module.workflow-process.diagram.stensilset") }}"
                    },//"{{\Config::get('app.url')}}/packages/telenok/core/js/oryx/stencilsets/bpmn2.0/bpmn2.0.json"
                    ssextensions:[]
                });
                
                if (false && importJSONFromTop)
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
    <body style="overflow:hidden;">
    </body>
</html>
