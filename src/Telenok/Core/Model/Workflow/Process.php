<?php

namespace Telenok\Core\Model\Workflow;

class Process extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'workflow_process'; 
     
	public function thread()
	{
		return $this->hasMany('\App\Model\Telenok\Workflow\Thread', 'thread_workflow_process');
	}
     
	public function variable()
	{
		return $this->hasMany('\App\Model\Telenok\Workflow\Variable', 'variable_workflow_process');
	}
     
	public function parameter()
	{
		return $this->hasMany('\App\Model\Telenok\Workflow\Parameter', 'parameter_workflow_process');
	}
}