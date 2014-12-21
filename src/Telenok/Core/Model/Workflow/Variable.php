<?php

namespace Telenok\Core\Model\Workflow;

class Variable extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'workflow_process_variable'; 
 
    public function variableWorkflowProcess()
    {
        return $this->belongsTo('\App\Model\Telenok\Workflow\Process', 'variable_workflow_process');
    }  
}