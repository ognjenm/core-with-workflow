@extends('core::layout.model')


@section('form')
	
	@parent
  
	@section('formBtn')
    <div class='form-actions center no-margin'>
		
		@if ( (isset($canCreate) && $canCreate) || (isset($canUpdate) && $canUpdate) )
        <button type="submit" class="btn btn-success" onclick="jQuery(this).closest('form').data('btn-clicked', 'save');">
            <i class="fa fa-floppy-o"></i>
            {{{ $controller->LL('btn.save') }}}
        </button>
        <button type="submit" class="btn btn-info" onclick="jQuery(this).closest('form').data('btn-clicked', 'save.close');">
            <i class="fa fa-floppy-o"></i>
            {{{ $controller->LL('btn.save.close') }}}
        </button>
		@endif
		
		@if (isset($canDelete) && $canDelete)
        <button type="submit" class="btn btn-danger" onclick="jQuery(this).closest('form').data('btn-clicked', 'delete.close');">
            <i class="fa fa-floppy-o"></i>
            {{{ $controller->LL('btn.delete') }}}
        </button>
        @endif
		
		<button type="submit" class="btn" onclick="jQuery(this).closest('form').data('btn-clicked', 'close');">
            <i class="fa fa-floppy-o"></i>
            {{{ $controller->LL('btn.close') }}}
        </button>
		
    </div>
	@overwrite
     
@stop
 