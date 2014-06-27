<?php

namespace Telenok\Core\Model\Object;

class Field extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $ruleList = ['title' => ['required', 'min:1'], 'title_list' => ['required', 'min:1'], 'code' => ['required', 'unique:object_field,code,:id:,id,field_object_type,:field_object_type:', 'regex:/^[A-Za-z][A-Za-z0-9_]*$/']];
	protected $table = 'object_field';
	
	public static function boot()
	{
		parent::boot();

		static::saved(function($model)
		{
			$type = $model->fieldObjectType()->first();
			
			if ($type && $type->class_model)
			{
				static::eraseStatic(\App::build($type->class_model));
			}
		});
	}
	
	public function getFillable()
	{ 
		$class = get_class($this);

		if (!isset(static::$staticListFillable[$class]))
		{
			$parent = parent::getFillable();

			static::$staticListFillable[$class] = [];

			\App::make('telenok.config')->getObjectFieldController()->each(function($item) use ($class)
			{
				static::$staticListFillable[$class] = array_merge(static::$staticListFillable[$class], (array) $item->getSpecialField());
			});

			static::$staticListFillable[$class] = array_merge(static::$staticListFillable[$class], $parent);
		} 

		return static::$staticListFillable[$class];
	} 
	
	public function setCodeAttribute($value)
	{
		$this->attributes['code'] = str_replace(' ', '', strtolower((string) $value));
	}
 
	public function fieldObjectType()
	{
		return $this->belongsTo('\Telenok\Object\Type', 'field_object_type');
	}
 
	public function fieldObjectTab()
	{
		return $this->belongsTo('\Telenok\Object\Tab', 'field_object_tab');
	}
}

?>