<?php

namespace Telenok\Core\Model\Workflow;

class Process extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'workflow_process'; 
     
	public function thread()
	{
		return $this->hasMany('\App\Model\Telenok\Workflow\Thread', 'thread_workflow_process');
	}


    public function threadWorkflowProcess()
    {
        return $this->belongsTo('\App\Model\Telenok\Workflow\Process', 'thread_workflow_process');
    }  
}