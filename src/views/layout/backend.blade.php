<!doctype html>
<html> 
    <head>
    @section('head')
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <base href="/" />

        {{ Html::style('packages/telenok/core/css/jquery-ui.css') }}
        {{ Html::style('packages/telenok/core/css/jquery.gritter.css') }}
        {{ Html::style('packages/telenok/core/js/bootstrap/css/bootstrap.min.css') }}
        {{ Html::style('packages/telenok/core/js/bootstrap/css/font-awesome.css') }}
        <!--[if lt IE 7]>
        {{ Html::style('packages/telenok/core/js/bootstrap/css/font-awesome-ie7.min.css') }}
        <![endif]-->
        {{ Html::style('packages/telenok/core/js/bootstrap/css/ace-fonts.css') }}
        {{ Html::style('packages/telenok/core/js/bootstrap/css/ace.css') }}
        {{ Html::style('packages/telenok/core/js/bootstrap/css/ace-skins.min.css') }}
        {{ Html::style('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.css') }}

        {{ Html::style('packages/telenok/core/js/dropzone/dropzone.css') }}
		
        {{ Html::style('packages/telenok/core/css/style.css') }}
        
        {{-- Html::style('packages/telenok/core/js/jquery.datatables/jquery.datatables.css') --}}

        {{ Html::script('packages/telenok/core/js/jquery.js') }}
        {{ Html::script('packages/telenok/core/js/jquery-ui.js') }}
        {{ Html::script('packages/telenok/core/js/jquery.gritter.js') }}
        {{ Html::script('packages/telenok/core/js/jquery.punch.js') }}
        {{ Html::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.js') }}
        {{ Html::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.tabletool.js') }}
        {{ Html::script('packages/telenok/core/js/jquery.datatables/jquery.datatables.bootstrap.js') }}
        {{ Html::script('packages/telenok/core/js/jquery.jstree/jstree.js') }}
        
        {{ Html::style('packages/telenok/core/js/jquery.chosen/chosen.css') }}
        {{ Html::script('packages/telenok/core/js/jquery.chosen/chosen.js') }}

        <script type="text/javascript">
            if("ontouchend" in document) document.write("<script src='packages/telenok/core/js/bootstrap/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
        </script>

        {{ Html::script('packages/telenok/core/js/fuelux/fuelux.wizard.min.js') }}
        {{ Html::script('packages/telenok/core/js/bootstrap/js/bootstrap.min.js') }}
        {{ Html::script('packages/telenok/core/js/bootstrap/js/ace-extra.js') }}
        {{ Html::script('packages/telenok/core/js/bootstrap/js/ace-elements.js') }}
        {{ Html::script('packages/telenok/core/js/bootstrap/js/ace.js') }}
        {{ Html::script('packages/telenok/core/js/bootstrap/lib/moment.js') }}
        {{ Html::script('packages/telenok/core/js/bootstrap/lib/datetimepicker/datetimepicker.js') }}

        {{-- Html::script('packages/telenok/core/js/md5.js') --}}
        {{ Html::script('packages/telenok/core/js/dropzone/dropzone.js') }}
        {{ Html::script('packages/telenok/core/js/script.js') }}
    @show
    </head>

    
    @yield('body')
</html>