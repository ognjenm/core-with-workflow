<?php

namespace Telenok\Core\Model\Object;

class Tab extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:object_tab,code,:id:,id,tab_object_type,:tab_object_type:', 'regex:/^[A-Za-z][A-Za-z0-9_.-]*$/']];
	protected $table = 'object_tab';

	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = str_replace(' ', '', strtolower((string) $value));
	}
 
	public function tabObjectType()
	{
		return $this->belongsTo('\Telenok\Core\Model\Object\Type', 'tab_object_type');
	}
	
	public function field()
	{
		return $this->hasMany('\Telenok\Core\Model\Object\Field', 'field_object_tab');
	}

	
}

?>