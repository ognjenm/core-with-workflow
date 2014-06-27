<?php

namespace Telenok\Core\Model\Workflow;

class EventResource extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'workflow_event_resource';

	public function resource()
	{
		return $this->belongsToMany('\Telenok\Security\Resource', 'pivot_relation_m2m_resource_workflow_event_resource', 'resource_workflow_event_resource', 'resource')->withTimestamps();
	}

	public function eventWorkflowEvent()
	{
		return $this->belongsTo('\Telenok\Workflow\Event', 'event_workflow_event');
	}

	public function eventResourceWorkflowProcess()
	{
		return $this->belongsTo('\Telenok\Workflow\Process', 'event_resource_workflow_process');
	}

}

?>