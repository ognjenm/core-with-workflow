<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>{{{ \Telenok\Core\Model\Object\Type::where('code', $model->getTable())->first()->translate('title_list') }}}</h3>
		</div>
		<div class="modal-body" style="max-height: 400px; overflow-y: auto; padding: 15px; position: relative;">
			<div class="widget-main">
				<table class="table table-striped table-bordered table-hover" id="table-{{$gridId}}" role="grid"></table>
			</div>
		</div>

		<script type="text/javascript">

			var aoColumns = []; 
			@foreach($fields as $key => $field)
				@if ($key==0)
					aoColumns.push({ "mData": "choose", "sTitle": "{{{ $controller->LL('btn.choose') }}}", "bSortable": false });
				@endif
				aoColumns.push({ "mData": "{{ $field->code }}", "sTitle": "{{{ $field->translate('title_list') }}}"});
			@endforeach

			jQuery('#table-{{$gridId}}').dataTable({
				"multipleSelection": true,
				"aoColumns": [],
				"bAutoWidth": true,
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource" : '{{ URL::route("cmf.module.{$controller->getKey()}.wizard.list", ["id" => $type->getKey()]) }}',
				"bDeferRender": '',
				"bJQueryUI": false,
				"sDom": "<'row'<'col-md-6'T><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				aoColumns : aoColumns,
				"oTableTools": {"aButtons": []}
			});
		</script>
	</div>
</div>