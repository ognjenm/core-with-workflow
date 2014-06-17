
<?php

    $domAttr = ['disabled' => 'disabled'];

    $title = '';
    $tree_pid = 0;
    $folderType = \Telenok\Core\Model\Object\Type::where('code', 'folder')->first();

    if ($model->exists && $tree_pid = $model->sequence->tree_pid)
    {
        $title = \Telenok\Core\Model\System\Folder::find($tree_pid)->translate('title');
    }
?>

    <div class="form-group">
        {{ Form::label('tree_pid', $field->translate('title'), array('class'=>'control-label')) }}
        <div class="controls">
            @if ($field->icon_class)
            <span class="input-group-addon">
                <i class="{{{$field->icon_class}}}"></i>
            </span>
            @endif
            
            {{ Form::hidden('tree_pid', $tree_pid) }}
            {{ Form::text(uniqid(), $title, $domAttr ) }}
            
            <button onclick="chooseTreePid{{$uniqueId}}(this, '{{ URL::route('cmf.module.objects-lists.wizard.choose', ['id' => $folderType->getKey()]) }}'); return false;" data-toggle="modal" class="btn btn-sm" type="button">
                <i class="fa fa-bullseye"></i>
                {{{ $controller->LL('btn.choose') }}}
            </button>
            <button onclick="editTreePid{{$uniqueId}}(this, '{{ URL::route('cmf.module.objects-lists.wizard.edit', ['id' => ':ID:' ]) }}'); return false;" data-toggle="modal" class="btn btn-sm btn-success" type="button">
                <i class="fa fa-pencil"></i>
                {{{ $controller->LL('btn.edit') }}}
            </button>
            <button onclick="deleteTreePid{{$uniqueId}}(this); return false;" data-toggle="modal" class="btn btn-sm btn-danger" type="button">
                <i class="fa fa-trash-o"></i>
                {{{ $controller->LL('btn.delete') }}}
            </button>

            @if ($field->translate('description'))
            <span title="" data-content="{{{ $field->translate('description') }}}" data-placement="right" data-trigger="hover" data-rel="popover" 
                  class="help-button" data-original-title="{{{\Lang::get('core::default.tooltip.description')}}}">?</span>
            @endif
        </div>
    </div>
 

    <script type="text/javascript">

        function editTreePid{{$uniqueId}}(obj, url) 
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

        function deleteTreePid{{$uniqueId}}(obj) 
        {
            var $block = jQuery(obj).closest('div.form-group');

            jQuery('input[type="text"]', $block).val('');
            jQuery('input[type="hidden"]', $block).val(0);
        }

        function chooseTreePid{{$uniqueId}}(obj, url) 
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