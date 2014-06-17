<?php

namespace Telenok\Core\Model\Workflow;

class Event extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:workflow_event,code,:id:', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];
	protected $table = 'workflow_event';

	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = str_replace(' ', '', strtolower((string) $value));
	}

	public function event()
	{
		return $this->hasMany('\Telenok\Core\Model\Workflow\EventResource', 'event_workflow_event');
	}

}

?>