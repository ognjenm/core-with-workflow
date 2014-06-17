<?php

namespace Telenok\Core\Model\Object;

class Version extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'object_version';
	protected $hasVersioning = false;
	protected $insideProcess = false;

	public $timestamps = false;

	public static function toModel($versionId)
	{
		$data = static::findOrFail($versionId);

		$class = \Telenok\Core\Model\Object\Type::findOrFail($data->object_type_id)->class_model;

		$model = $class::findOrFail($data->object_id);
		$model->setRawAttributes(json_decode($data->object_data, true));

		return $model;
	}

	public static function toRestore($version)
	{
		if (is_integer($version))
		{
			$versionData = static::findOrFail($version);
		}
		else if ($version instanceof \Telenok\Core\Model\Object\Version)
		{
			$versionData = $version;
		}

		try
		{
			$class = \Telenok\Core\Model\Object\Type::findOrFail($versionData->object_type_id)->class_model;
		} 
		catch (\Exception $ex) 
		{
			throw new \Telenok\Core\Interfaces\Exception\ObjectTypeNotFound();
		}

		try 
		{
			$model = $class::findOrFail($versionData->object_id);
		} 
		catch (\Exception $ex) 
		{
			$model = new $class();
		}
		
		$model->setRawAttributes($versionData->object_data->toArray());
		$model->save();

		return $model;
	}

	public static function add(\Illuminate\Database\Eloquent\Model $model = null)
	{
		if (!($model instanceof \Telenok\Core\Model\Object\Sequence) && $model->exists && \Config::get('app.version.enabled'))
		{
			$this_ = new static;

			$this_->setInsideProcess(true);

			$this_->fill([
				'title' => ($model->title instanceof \Illuminate\Support\Collection ? $model->title->toArray() : $model->title),
				'object_id' => $model->getKey(),
				'object_type_id' => $model->type()->getKey(),
				'object_data' => $model->getAttributes(),
			]);

			if ($createdByUser = $model->createdByUser()->first())
			{
				$this_->createdByUser()->associate($createdByUser);
			}

			if ($updatedByUser = $model->updatedByUser()->first())
			{
				$this_->updatedByUser()->associate($updatedByUser);
			}

			$this_->created_at = $model->created_at;
			$this_->updated_at = $model->updated_at;
			$this_->start_at = $model->start_at;
			$this_->end_at = $model->end_at;

			$this_->save();

			$this_->setInsideProcess(false);
		}
	}

	public function save(array $options = [])
	{
		if (!$this->insideProcess)
		{
			throw new \Exception('Please, use \Telenok\Core\Model\Object\Version::add($model) method');
		}

		return parent::save($options);
	}

	protected function setInsideProcess($option)
	{
		$this->insideProcess = $option;

		return $this;
	}

}

?>