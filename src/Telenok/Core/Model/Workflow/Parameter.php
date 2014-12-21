<?php

namespace Telenok\Core\Model\Workflow;

class Parameter extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'workflow_process_parameter'; 
 
    public function parameterWorkflowProcess()
    {
        return $this->belongsTo('\App\Model\Telenok\Workflow\Process', 'parameter_workflow_process');
    }  
}