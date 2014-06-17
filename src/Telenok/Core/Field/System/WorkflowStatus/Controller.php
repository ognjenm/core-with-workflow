<?php

namespace Telenok\Core\Field\System\WorkflowStatus;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Field\MorphManyToMany\Controller {

	protected $key = 'workflow-status';

	public function postProcess($model, $type, $input)
	{
		$model->morph_many_to_many_has = \Telenok\Core\Model\Object\Type::where('code', 'workflow_status')->first()->getKey();
		$model->save();

		try
		{
			$type = $model->fieldObjectType()->first();
			$table = $type->code;

			if (!\Schema::hasColumn($table, 'workflow_status_restrict') && !\Schema::hasColumn($table, "`workflow_status_restrict`"))
			{
				\Schema::table($table, function(Blueprint $table)
				{
					$table->string('workflow_status_restrict');
				});
			}

			if (!\Schema::hasColumn($table, 'workflow_status_comment') && !\Schema::hasColumn($table, "`workflow_status_comment`"))
			{
				\Schema::table($table, function(Blueprint $table)
				{
					$table->text('workflow_status_comment');
				});
			}
		}
		catch (\Exception $e)
		{
			throw $e;
		}

		return parent::postProcess($model, $type, $input);
	}

}

?>