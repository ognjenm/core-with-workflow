<script type="text/javascript"> 
    
    jQuery('#model-ajax-{{$uniqueId}}').on('submit', function(e) {
        e.preventDefault();

        var $el = jQuery(this);

        jQuery.ajax({
            url: $el.attr('action'),
            type: $el.attr('method'),
            data: $el.serialize(),
            dataType: 'json',
            cache: false,
            statusCode: {
                200: function(data) {
                    
                    var $tab = $el.closest('div.tab-pane');
                    jQuery('ul.error', $tab).remove();
                    jQuery('div.alert-success', $tab).remove();
                    
                    if (data.error.length) {
                        $tab.prepend(jQuery('<ul class="error" style="background: red;"></ul>').prepend(function(){return jQuery.each(data.error, function(i,v){ return '<li>'+v+'</li>'; })}))
                    }
                    else {
                        $tab.html(data.content); 
                    }
                },
                500: function() {
                    
                },
                404: function() {
                    
                }
            },
            complete: function() {
                
            }
        });
    });
</script>

@if ($success)
<div class="alert alert-block alert-success">
    <button data-dismiss="alert" class="close" type="button">
        <i class="fa fa-times"></i>
    </button>
    <p>
        <strong>
            <i class="fa fa-check"></i>
            Сохранено!
        </strong>
        Данные успешно сохранены.
    </p>
</div>
@endif

 

{{ Form::model($model, array('route' => $route, 'files' => true, 'id'=>"model-ajax-$uniqueId", 'class'=>'form-horizontal')) }}
    {{ Form::hidden('id') }}

    <div class="form-group">
        {{ Form::label('title', $controller->LL('entity.title'), array('class'=>'control-label')) }}
        <div class="controls">
            {{ Form::text('title') }}
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label('code', $controller->LL('entity.code'), array('class'=>'control-label')) }}
        <div class="controls">
            {{ Form::text('code') }}
        </div>
    </div>
    
    <div class="form-group">
        {{ Form::label('class', $controller->LL('entity.class'), array('class'=>'control-label')) }}
        <div class="controls">
            {{ Form::text('class') }}
        </div>
    </div>
    
    <script type="text/javascript"> 
        function editObjectTypeField{{$uniqueId}}(url)
        {
            jQuery.ajax({
                url: url,
                method: 'get',
                dataType: 'json'
            }).done(function(data) {
                
                jQuery('div.modal-body', '#modal-objects-type-field-{{$uniqueId}}').html(data.content);
    
                jQuery('#modal-objects-type-field-{{$uniqueId}}').modal('show');
            });
        }
    </script>
    
    
    

    <div class="widget-box">
        <div class="widget-header">
            <h4>
                <i class="fa fa-list-ol"></i>
                Fields
            </h4>
        </div>
        <div class="widget-body">
            <div class="widget-main">

                @foreach($model->field()->get() as $field)
                <div class="form-group">
                    <div class="row input-group"> 
                        {{ Form::text('', $field->getAttribute('title'), array('disabled' => 'disabled')) }}
                        <span class="btn btn-sm btn-success" data-toggle="modal" onclick="editObjectTypeField{{$uniqueId}}('{{URL::route('cmf.module.objects-field.edit', array('id' => $field->getKey())) }}');">
                            <i class="fa fa-pencil"></i> Редактировать
                        </span>
                        <span class="btn btn-sm btn-danger">
                            <i class="fa fa-trash-o"></i> Удалить
                        </span>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>    

    <div class='form-actions'>

        <button type="submit" class="btn btn-info" >
            <i class="fa fa-floppy-o"></i>
            {{{ $controller->LL('btn.save') }}}
        </button>
        @if ($model->exists)
        <button type="button" class="btn" data-toggle="modal" onclick="editObjectTypeField{{$uniqueId}}('{{ URL::route('cmf.module.objects-field.create', array('id' => $model->getKey())) }}');">
            <i class="fa fa-plus"></i>
            Add new field
        </button>
        @endif

    </div>
    
{{ Form::close() }} 




<div id="modal-objects-type-field-{{$uniqueId}}" class="modal fade" style="width: 800px; margin-left: -400px;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header table-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Редактирование поля</h3>
			</div>
			<div class="modal-body" style="max-height: 400px; overflow-y: auto; padding: 15px; position: relative;"></div>
			<div class="modal-footer">
				<a class="btn btn-info" onclick="jQuery('.modal-body > form', jQuery(this).closest('.modal')).submit();">Save Changes</a>
				<a class="btn" data-dismiss="modal">Close</a>
			</div>
		</div>
	</div>
</div>

 