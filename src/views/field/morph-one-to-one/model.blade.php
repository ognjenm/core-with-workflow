<?php

    $method = camel_case($field->code);
    $linkedField = $field->morph_one_to_one_has ? 'morph_one_to_one_has' : 'morph_one_to_one_belong_to';
    $jsUnique = str_random();

	$domAttr = ['disabled' => 'disabled', 'class' => 'col-xs-5 col-sm-5'];

	$title = '';
	$id = 0; 

	if ($model->exists && $result = $model->$method)
	{
		$title = $result->translate('title');
		$id = $result->id;
	}
	
?>

    <div class="form-group">
        {{ Form::label("{$field->code}", $field->translate('title'), ['class' => 'col-sm-3 control-label no-padding-right']) }}
        <div class="col-sm-9"> 
            {{ Form::hidden("{$field->code}", $id) }}
            {{ Form::text(str_random(), ($id ? "[{$id}] " : "") . $title, $domAttr ) }}
            
            
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="chooseMorphO2MBelongTo{{$uniqueId}}(this, '{{ URL::route($controller->getRouteWizardChoose(), ['id' => $field->morph_one_to_one_has ? $field->{$linkedField} : $field->morph_one_to_one_belong_to_type_list->toArray()]) }}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-bullseye"></i>
                {{{ $controller->LL('btn.choose') }}}
            </button>
            <button onclick="editMorphO2MBelongTo{{$uniqueId}}(this, '{{ URL::route($controller->getRouteWizardEdit(), ['id' => ':ID:', 'saveBtn' => 1]) }}'); return false;" data-toggle="modal" class="btn btn-sm btn-success" type="button">
                <i class="fa fa-pencil"></i>
                {{{ $controller->LL('btn.edit') }}}
            </button>
            <button onclick="deleteMorphO2MBelongTo{{$uniqueId}}(this); return false;" data-toggle="modal" class="btn btn-sm btn-danger" type="button">
                <i class="fa fa-trash-o"></i>
                {{{ $controller->LL('btn.delete') }}}
            </button>
            @endif
        </div>
    </div>

    <script type="text/javascript">

        function editMorphO2MBelongTo{{$uniqueId}}(obj, url) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            var id = jQuery('input[type="hidden"]', $block).val();
            
            if (id == 0) return false;
            
            url = url.replace(':ID:', id);

            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
				
                if (!jQuery('#modal-{{$uniqueId}}').size())
                {
                    jQuery('body').append('<div id="modal-{{$uniqueId}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                }

				var $modal = jQuery('#modal-{{$uniqueId}}');
				
                $modal.data('model-data', function(data)
                {  
                    jQuery('input[type="text"]', $block).val(data.title);
                    jQuery('input[type="hidden"]', $block).val(data.id);

                });
						
				$modal.html(data.tabContent);
				
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }

        function deleteMorphO2MBelongTo{{$uniqueId}}(obj) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery('input[type="text"]', $block).val('');
            jQuery('input[type="hidden"]', $block).val(0);
        }

        function chooseMorphO2MBelongTo{{$uniqueId}}(obj, url) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
				
                if (!jQuery('#modal-{{$uniqueId}}').size())
                {
                    jQuery('body').append('<div id="modal-{{$uniqueId}}" class="modal fade" role="dialog" aria-labelledby="label"></div>');
                }

				var $modal = jQuery('#modal-{{$uniqueId}}');

                $modal.data('model-data', function(data)
                {
                    jQuery('input[type="text"]', $block).val(data.title);
                    jQuery('input[type="hidden"]', $block).val(data.id);

                });
						
				$modal.html(data.tabContent);
						
				$modal.modal('show').on('hidden', function() 
				{
                    jQuery(this).html(""); 
                });
            });
        }
        
    </script>