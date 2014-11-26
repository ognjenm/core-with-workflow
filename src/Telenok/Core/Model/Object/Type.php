<?php

namespace Telenok\Core\Model\Object;

class Type extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'code' => ['required', 'unique:object_type,code,:id:,id', 'regex:/^[a-z][\w]*$/i'], 'title_list' => ['required', 'min:1']];
	protected $table = 'object_type';

	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = strtolower((string) $value);
	}

	public function getTreeableAttribute($value)
	{
		return $value ? TRUE : FALSE;
	}

	public function field()
	{
		return $this->hasMany('\App\Model\Telenok\Object\Field', 'field_object_type');
	}

	public function tab()
	{
		return $this->hasMany('\App\Model\Telenok\Object\Tab', 'tab_object_type');
	}

	public function sequences()
	{
		return $this->hasMany('\App\Model\Telenok\Object\Sequence', 'sequences_object_type');
	}

}

?>