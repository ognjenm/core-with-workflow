<?php

namespace Telenok\Core\Model\Workflow;

class Thread extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'workflow_thread';
 
    public function threadWorkflowProcess()
    {
        return $this->belongsTo('\App\Model\Telenok\Workflow\Process', 'thread_workflow_process');
    }
}