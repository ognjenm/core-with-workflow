							@if (!in_array($field->code, ['value']))

								{{ \App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) }}

							@elseif ($field->code=='value')

								<?php

								$w = \App::make('telenok.config')->getSetting()->get(strtolower($model->code));

								?>

								@if ($w)

									{{ $w->getFormSettingContent($field, $model, $uniqueId) }}

								@else

									{{ \App::make('telenok.config')->getObjectFieldController()->get($field->key)->getFormModelContent($controller, $model, $field, $uniqueId) }}

								@endif

							@endif
