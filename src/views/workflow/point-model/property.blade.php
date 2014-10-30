 

<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>Setting After save point</h4>
		</div>
		
		<form action="#" onsubmit="return false;">
		
			{{Form::hidden('sessionDiagramId', $sessionDiagramId)}}
			{{Form::hidden('stencilId', $stencilId)}}
			
			<div class="modal-body" style="max-height: 400px; overflow-y: auto; padding: 15px; position: relative;">
				<div class="widget-main">

					<select name="stencil[el][]" multiple="multiple">
						<option value="1" selected="selected">Type</option>
						<option value="2" selected="selected">Field</option>
					</select>

					<input type="text" name="stencil[title]" value="{{{array_get($property, 'title.value')}}}" />
					<input type="text" name="stencil[bgcolor]" value="{{{array_get($property, 'bgcolor.value')}}}" />
					<input type="text" name="stencil[bordercolor]" value="{{{array_get($property, 'bordercolor.value')}}}" />

				</div>
			</div>

			<div class="modal-footer">

				<div class="center no-margin">

					<button class="btn btn-success" onclick="
						var $modal = jQuery(this).closest('.modal');
						var $form = jQuery(this).closest('form');
						$modal.data('model-data')($form);
						return false;">
						<i class="fa fa-bullseye"></i>
						{{{ $controller->LL('btn.apply') }}}
					</button>

					<button class="btn btn-success">
						<i class="fa fa-bullseye"></i>
						{{{ $controller->LL('btn.close') }}}
					</button>

				</div>

			</div>

		</form>

	</div>
</div>
