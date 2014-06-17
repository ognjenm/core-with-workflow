<?php

namespace Telenok\Core\Model\Workflow;

class Thread extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'workflow_thread';
	/*
	public function getOriginalProcessAttribute($value)
	{
		return \Illuminate\Support\Collection::make(json_decode($value ? $value : '[]', true));
	}

	public function setOriginalProcessAttribute($value = [])
	{ 
		if ($value instanceof \Illuminate\Support\Collection) 
		{
			$value = $value->toArray();
		}
		else
		{
			$value = $value ? : [];
		} 
 
		// $value can be json string, then convert it to array
		if (is_scalar($value) && ($json = json_decode($value)) !== null)
		{
			$value = $json;
		}

		$this->attributes['original_process'] = json_encode($value);
	}
	*/
	public function threadWorkflowProcess()
	{
		return $this->belongsTo('\Telenok\Core\Model\Workflow\Process', 'thread_workflow_process');
	}

}

?>