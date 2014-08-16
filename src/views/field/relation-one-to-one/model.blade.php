
<?php

	$domAttr = ['disabled' => 'disabled', 'class' => 'col-xs-5 col-sm-5'];
    $method = camel_case($field->code);
    $linkedField = $field->relation_one_to_one_has ? 'relation_one_to_one_has' : 'relation_one_to_one_belong_to';

    $title = '';
    $id = 0;

    if ($model->exists && $result = $model->$method()->first())
    {
        $title = $result->translate('title');
        $id = $result->id;
    }
	
	$disabledCreateLinkedType = false;
	$disabledReadLinkedType = false;

	$linkedType = $controller->getLinkedModelType($field);
	
	if (!\Auth::can('create', 'object_type.' . $linkedType->code))
	{
		$disabledCreateLinkedType = true;
	}
	
	if (!\Auth::can('read', 'object_type.' . $linkedType->code))
	{
		$disabledReadLinkedType = true;
	}

	
?>

    <div class="form-group">
        {{ Form::label($field->code, $field->translate('title'), ['class'=>'col-sm-3 control-label no-padding-right']) }}
        <div class="col-sm-9"> 
            @if ($field->icon_class)
            <span class="input-group-addon">
                <i class="{{{$field->icon_class}}}"></i>
            </span>
            @endif
            
            {{ Form::hidden($field->code, $id) }}
            {{ Form::text(str_random(), $title, $domAttr ) }}
            
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledReadLinkedType
				)
            <button onclick="chooseO2O{{$uniqueId}}(this, '{{ URL::route($controller->getRouteWizardChoose(), ['id' => $field->{$linkedField}]) }}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-bullseye"></i>
                {{{ $controller->LL('btn.choose') }}}
            </button>
            @endif
			
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledCreateLinkedType
				)
            <button onclick="createO2O{{$uniqueId}}(this, '{{ URL::route($controller->getRouteWizardCreate(), [ 'id' => $field->{$linkedField}, 'saveBtn' => 1, 'chooseBtn' => 1]) }}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-plus"></i>
                {{{ $controller->LL('btn.create') }}}
            </button>
            @endif
			
			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate)) && !$disabledReadLinkedType
				)
            <button onclick="editO2O{{$uniqueId}}(this, '{{ URL::route($controller->getRouteWizardEdit(), ['id' => ':ID:', 'saveBtn' => 1, 'chooseBtn' => 1]) }}'); return false;" data-toggle="modal" class="btn btn-sm btn-success" type="button">
                <i class="fa fa-pencil"></i>
                {{{ $controller->LL('btn.edit') }}}
            </button>
            @endif

			@if ( 
					((!$model->exists && $field->allow_create && $permissionCreate) 
						|| 
					($model->exists && $field->allow_update && $permissionUpdate))
				)
            <button onclick="deleteO2O{{$uniqueId}}(this); return false;" data-toggle="modal" class="btn btn-sm btn-danger" type="button">
                <i class="fa fa-trash-o"></i>
                {{{ $controller->LL('btn.delete') }}}
            </button>
            @endif

            @if ($field->translate('description'))
            <span title="" data-content="{{{ $field->translate('description') }}}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{{\Lang::get('core::default.tooltip.description')}}}">?</span>
            @endif
        </div>
    </div>
 

    <script type="text/javascript">
        
        function createO2O{{$uniqueId}}(obj, url) 
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

        function editO2O{{$uniqueId}}(obj, url) 
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

        function deleteO2O{{$uniqueId}}(obj) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery('input[type="text"]', $block).val('');
            jQuery('input[type="hidden"]', $block).val(0);
        }

        function chooseO2O{{$uniqueId}}(obj, url) 
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