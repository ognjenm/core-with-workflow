<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:b3mn="http://b3mn.org/2007/b3mn" xmlns:ext="http://b3mn.org/2007/ext" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:atom="http://b3mn.org/2007/atom+xhtml">
  <head profile="http://purl.org/NET/erdf/profile">
    <link rel="icon" href="/designer/favicon.ico"></link>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"></meta>
    <title>Process Designer</title>
    
        {{ HTML::style('packages/telenok/core/js/ext-2.0.2/resources/css/ext-all.css') }}
        {{ HTML::style('packages/telenok/core/js/ext-2.0.2/resources/css/xtheme-gray.css') }}
        {{ HTML::style('packages/telenok/core/js/oryx/css/theme_norm.css') }}
    
        {{ HTML::script('packages/telenok/core/js/prototype-1.5.1.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/path_parser.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/CFInstall.min.js') }}
        {{ HTML::script('packages/telenok/core/js/ext-2.0.2/adapter/ext/ext-base.js') }}
        {{ HTML::script('packages/telenok/core/js/ext-2.0.2/ext-all-debug.js') }}
        {{ HTML::script('packages/telenok/core/js/ext-2.0.2/plugin/color-field.js') }}
        {{ HTML::script('packages/telenok/core/js/ext-2.0.2/plugin/grid.search.js') }}

        <link rel="schema.dc" href="http://purl.org/dc/elements/1.1/"></link>
        <link rel="schema.dcTerms" href="http://purl.org/dc/terms/"></link>
        <link rel="schema.b3mn" href="http://b3mn.org"></link>
        <link rel="schema.oryx" href="http://oryx-editor.org/"></link>
        <link rel="schema.raziel" href="http://raziel.org/"></link>
		
        {{ HTML::script('packages/telenok/core/js/oryx/kickstart.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/config.js') }}  
        {{ HTML::script('packages/telenok/core/js/oryx/oryx.js') }}         
        {{ HTML::script('packages/telenok/core/js/oryx/clazz.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/main.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/utils.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/erdfparser.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/datamanager.js') }} 
        {{ HTML::script('packages/telenok/core/js/oryx/Core/Math/math.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/SVG/editpathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/SVG/minmaxpathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/SVG/pointspathhandler.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/SVG/svgmarker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/SVG/svgshape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/SVG/label.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/stencil.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/property.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/propertyitem.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/complexpropertyitem.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/rules.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/stencilset.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/StencilSet/stencilsets.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/command.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/bounds.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/uiobject.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/abstractshape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/canvas.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/svgDrag.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/shape.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/Controls/control.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/Controls/magnet.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/Controls/docker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/node.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/edge.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/abstractPlugin.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/abstractLayouter.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Core/abstractDragTracker.js') }}
		
        {{ HTML::script('packages/telenok/core/js/oryx/i18n/translation_en.js') }}

        <script type="text/javascript"> 

			var allPlugins = {};
			[
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.AddDocker",
					"properties" : []
				},
				{ 
					"core" : true,
					"name" : "ORYX.Plugins.ShapeMenuPlugin",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.Arrangement",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.RenameShapes",
					"properties" : []
				},    
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.ShapeRepository",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.ContainerLayouter",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.Edit",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.Undo",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.DragTracker.PoolDragTracker",
					"properties" : []
				},    
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.ShapeHighlighting",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.Overlay",
					"properties" : []
				}, 
				{
					"core" : false,
					"name" : "ORYX.Plugins.CanvasResize",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.DragTracker.LaneDragTracker",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.KeysMove",
					"properties" : []
				},  
				{ 
					"core" : true,
					"name" : "ORYX.Plugins.Toolbar",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.SelectStencilSetPerspective",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.DockerCreation",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.SelectionFrame",
					"properties" : []
				},   
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.Grouping",
					"properties" : []
				},   
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.DragDocker",
					"properties" : []
				}, 
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.DragDropResize",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.PropertyWindow",
					"properties" : []
				},
				{ 
					"core" : false,
					"name" : "ORYX.Plugins.Telenok.EventList",
					"properties" : []
				}
			].each(function (p) {
				allPlugins[p.name] = p;
			}.bind(allPlugins));

		  // install the current plugins
		  ORYX.availablePlugins = [];
		  [ 
			  "ORYX.Plugins.Toolbar",
			  "ORYX.Plugins.ShapeMenuPlugin",
			  "ORYX.Plugins.ShapeRepository",
			  "ORYX.Plugins.PropertyWindow",
			  "ORYX.Plugins.CanvasResize",
			  //"ORYX.Plugins.View",
			  "ORYX.Plugins.DragDropResize",
			  "ORYX.Plugins.RenameShapes",
			  "ORYX.Plugins.Undo",
			  "ORYX.Plugins.Arrangement",
			  "ORYX.Plugins.Grouping",
			  "ORYX.Plugins.DragDocker",
			  "ORYX.Plugins.AddDocker",
			  "ORYX.Plugins.SelectionFrame",
			  "ORYX.Plugins.ShapeHighlighting",
			  "ORYX.Plugins.Edit",
			  "ORYX.Plugins.KeysMove",
			  "ORYX.Plugins.ContainerLayouter",
			  "ORYX.Plugins.DragTracker.LaneDragTracker",
			  "ORYX.Plugins.DragTracker.PoolDragTracker",
			  "ORYX.Plugins.DockerCreation",
			  "ORYX.Plugins.Telenok.EventList"
			].each(function(pluginName) 
			{
				p = allPlugins[pluginName];

				if (p) 
				{
					ORYX.availablePlugins.push(p);
				}
				else 
				{
					ORYX.Log.error("missing plugin " + pluginName);
				}
			}.bind(allPlugins));

			checkChromeFrame = function() {
				CFInstall.check({mode: "popup",node: "prompt"});
			};  
		
			function init() 
			{
				ORYX_LOGLEVEL = 1;
				ORYX.PATH = "{{ \Config::get('app.url') }}/packages/telenok/core/js/oryx/";
				Ext.BLANK_IMAGE_URL = "{{ \Config::get('app.url') }}/packages/telenok/core/js/ext-2.0.2/resources/images/default/s.gif";
  
				var editor_parameters = {
					id: "oryx-{{str_random()}}",
					stencilset: {
						url: "{{ URL::route("cmf.module.workflow-process.diagram.stensilset") }}"
					}
				};

				var editor = new ORYX.Editor(editor_parameters);
				ORYX.EDITOR = editor;
			}
		</script>
		
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/toolbar.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/shapemenu.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/shaperepository.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/propertywindow.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/canvasResize.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/view.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/dragdropresize.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/renameShapes.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/undo.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/arrangement.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/grouping.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/dragDocker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/addDocker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/selectionframe.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/shapeHighlighting.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/edit.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/keysMove.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/Layouter/containerLayouter.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/dragTracker/laneDragTracker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/dragTracker/poolDragTracker.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/dockerCreation.js') }}
        {{ HTML::script('packages/telenok/core/js/oryx/Plugins/Telenok/EventList.js') }}
  </head>
  <body style="overflow:hidden;" onLoad="checkChromeFrame()">
    <div id="prompt"></div>
    <div class="processdata" style="display:none"></div>
  </body>

</html>

