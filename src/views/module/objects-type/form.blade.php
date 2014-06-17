@extends('core::presentation.tree-tab-object.form')

	@section('scriptForm')
		@parent 
		
        jQuery('input[name=code]', '#model-ajax-{{$uniqueId}}').blur(function() {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');

            var pathModel = jQuery('input[name=namespace_path_class_model]', $form).val();
            var pathForm = jQuery('input[name=namespace_path_class_form]', $form).val();

            namespaceClassModelByPath{{$uniqueId}}(pathModel);

            if (jQuery.trim(pathForm))
            {
                namespaceClassFormByPath{{$uniqueId}}(pathForm);
            }
        });

        function class_model{{$uniqueId}}()
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');

            jQuery.ajax({
                url: '{{ URL::route('cmf.module.file-browser.wizard.list') }}',
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
                jQuery('#modal-{{$uniqueId}}').data('path', function(path){ 
                    namespaceClassModelByPath{{$uniqueId}}(path);
                }).html(data.content).modal('show');
            });
        }

        function namespaceClassModelByPath{{$uniqueId}}(path)
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');
            var code = jQuery('input[name=code]', $form).val();

            jQuery.ajax({
                url: '{{ URL::route('cmf.module.objects-type.get.namespace-model-by-path') }}',
                data: {'path' : path, 'code' : code},
                method: 'get',
                dataType: 'json'
            }).done(function(data) {

                jQuery('input[name=namespace_path_class_model]', $form).val('');
                jQuery('div.alert-danger', $form).remove();

                if (data.error)
                {
                    if (data.error.length) 
                    {
                        $form.closest('div.tab-pane').prepend(jQuery('<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button></div>').prepend(function(){return jQuery.each(data.error, function(i,v){ return '<li>'+v+'</li>'; })}))
                    }
                }
                else
                {
                    var code = jQuery('input[name=code]', $form).val() || '';

                    jQuery('input[name=class_model]', $form).val(data.class);
                    jQuery('input[name=namespace_path_class_model]', $form).val(path);
                }
            });
        } 

        function class_controller{{$uniqueId}}()
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');
        
            jQuery.ajax({
                url: '{{ URL::route('cmf.module.file-browser.wizard.list') }}',
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
                jQuery('#modal-{{$uniqueId}}').data('path', function(path){ 
                    namespaceClassFormByPath{{$uniqueId}}(path);
                }).html(data.content).modal('show');
            });
        }

        function namespaceClassFormByPath{{$uniqueId}}(path)
        {
            var $form = jQuery('#model-ajax-{{$uniqueId}}');
            var code = jQuery('input[name=code]', $form).val();

            jQuery.ajax({
                url: '{{ URL::route('cmf.module.objects-type.get.namespace-form-by-path') }}',
                data: {'path' : path, 'code' : code},
                method: 'get',
                dataType: 'json'
            }).done(function(data) {

                jQuery('input[name=namespace_path_class_form]', $form).val('');
                jQuery('div.alert-danger', $form).remove();

                if (data.error)
                {
                    if (data.error.length) 
                    {
                        $form.closest('div.tab-pane').prepend(jQuery('<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button></div>').prepend(function(){return jQuery.each(data.error, function(i,v){ return '<li>'+v+'</li>'; })}))
                    }
                }
                else
                {
                    var code = jQuery('input[name=code]', $form).val() || '';

                    jQuery('input[name=class_controller]', $form).val(data.class);
                    jQuery('input[name=namespace_path_class_form]', $form).val(path);
                }
            });
        }
 
	@stop

