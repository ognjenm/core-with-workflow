<?php

    $method = camel_case($field->code);
    $linkedField = $field->morph_one_to_one_has ? 'morph_one_to_one_has' : 'morph_one_to_one_belong_to';
    $jsUnique = uniqid("{$uniqueId}_");
?>

    <?php 
    
        $domAttr = ['disabled' => 'disabled'];

        $title = '';
        $id = 0; 
		
		if ($model->exists && $result = $model->$method()->first())
        {
            $title = $result->translate('title');
            $id = $result->id;
        }
    ?>

    <div class="form-group">
        {{ Form::label("{$field->code}", $field->translate('title'), array('class'=>'control-label')) }}
        <div class="controls"> 
            {{ Form::hidden("{$field->code}", $id) }}
            {{ Form::text(uniqid(), $title, $domAttr ) }}
            
            <button onclick="chooseO2MBelongTo{{$uniqueId}}(this, '{{ URL::route('cmf.module.objects-lists.wizard.choose', ['id' => $field->{$linkedField}]) }}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-bullseye"></i>
                {{{ $controller->LL('btn.choose') }}}
            </button>
            <button onclick="createO2MBelongTo{{$uniqueId}}(this, '{{ URL::route('cmf.module.objects-lists.wizard.create', [ 'id' => $field->{$linkedField} ]) }}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-plus"></i>
                {{{ $controller->LL('btn.create') }}}
            </button>
            <button onclick="editO2MBelongTo{{$uniqueId}}(this, '{{ URL::route('cmf.module.objects-lists.wizard.edit', ['id' => ':ID:' ]) }}'); return false;" data-toggle="modal" class="btn btn-sm btn-success" type="button">
                <i class="fa fa-pencil"></i>
                {{{ $controller->LL('btn.edit') }}}
            </button>
            <button onclick="deleteO2MBelongTo{{$uniqueId}}(this); return false;" data-toggle="modal" class="btn btn-sm btn-danger" type="button">
                <i class="fa fa-trash-o"></i>
                {{{ $controller->LL('btn.delete') }}}
            </button>
            
        </div>
    </div>

    <script type="text/javascript">
        
        function createO2MBelongTo{{$uniqueId}}(obj, url) 
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

                })
						
				$modal.html(data.tabContent);
						
				$modal.modal('show').on('hidden', function() 
                { 
                    jQuery(this).html(""); 
                });
            });
        }

        function editO2MBelongTo{{$uniqueId}}(obj, url) 
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

        function deleteO2MBelongTo{{$uniqueId}}(obj) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery('input[type="text"]', $block).val('');
            jQuery('input[type="hidden"]', $block).val(0);
        }

        function chooseO2MBelongTo{{$uniqueId}}(obj, url) 
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