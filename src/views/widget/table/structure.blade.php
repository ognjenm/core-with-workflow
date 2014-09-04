<div class="form-group">
	{{ Form::label("structure[col]", $controller->LL('title.col'), array('class' => 'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
		{{ Form::text("structure[col]", array_get($model->structure->all(), 'col')) }}
	</div>
</div>

<div class="form-group">
	{{ Form::label("structure[row]", $controller->LL('title.row'), array('class' => 'col-sm-3 control-label no-padding-right')) }}
	<div class="col-sm-9">
		{{ Form::text("structure[row]", array_get($model->structure->all(), 'row')) }}
	</div>
</div>