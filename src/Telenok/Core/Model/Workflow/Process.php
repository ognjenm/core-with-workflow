<?php

namespace Telenok\Core\Model\Workflow;

class Process extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'workflow_process';
/*
	public function getProcessAttribute($value)
	{
		return \Illuminate\Support\Collection::make(json_decode($value ? $value : '[]', true));
	}

	public function setProcessAttribute($value = [])
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

		$this->attributes['process'] = json_encode($value);
	}
*/
	public function eventResource()
	{
		return $this->hasMany('\Telenok\Workflow\EventResource', 'event_resource_workflow_process');
	}

	public function thread()
	{
		return $this->hasMany('\Telenok\Workflow\Thread', 'thread_workflow_process');
	}


    public function threadWorkflowProcess()
    {
        return $this->belongsTo('\Telenok\Workflow\Process', 'thread_workflow_process');
    }


    public function product536671e33518dShopCategoryShopProduct()
    {
        return $this->morphTo('product536671e33518d_shop_category', 'product536671e33518d_shop_category_shop_product_type', 'product536671e33518d_shop_category_shop_product_id');
    }

}
?>