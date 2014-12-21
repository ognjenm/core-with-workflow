<?php

    $jsUnique = str_random();

?>

<div class="modal-dialog">
	<div class="modal-content">

		<div class="modal-header table-header">
			<button data-dismiss="modal" class="close" type="button">×</button>
			<h4>{{ $controller->LL('wizard.file.header') }}</h4>
		</div>
		<div class="modal-body"> 
asdasdsad
		</div>
		<div class="modal-footer">
			<a class="btn btn-info" onclick="
					var $modal = jQuery(this).closest('.modal');
						$modal.modal('hide');">{{ $controller->LL('btn.choose') }}</a>
			<a class="btn" data-dismiss="modal">{{ $controller->LL('btn.close') }}</a>
		</div>
	</div>
</div>