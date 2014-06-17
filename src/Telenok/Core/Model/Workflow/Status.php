<?php

namespace Telenok\Core\Model\Workflow;

class Status extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:workflow_status,code,:id:', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];
	protected $table = 'workflow_status';

	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = str_replace(' ', '', strtolower((string) $value));
	}

}

?>