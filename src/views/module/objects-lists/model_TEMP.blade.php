@extends('core::layout.model')

@section('form')

{{ Form::model($model, array('url' => $routerParam, 'files' => true, 'id' => "model-ajax-$uniqueId", 'class' => 'form-horizontal')) }}
    
	<div class="error-container"></div>

    {{ $controller->getFormContent($model, $type, $fields, $uniqueId) }}
    
    <div class='form-actions center no-margin'>
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
            <i class="fa fa-floppy-o"></i>
            {{{ $controller->LL('btn.save') }}}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            <i class="fa fa-floppy-o"></i>
            {{{ $controller->LL('btn.save.close') }}}
        </button>
        <button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            <i class="fa fa-floppy-o"></i>
            {{{ $controller->LL('btn.close') }}}
        </button>
    </div>

{{ Form::close() }}

@stop