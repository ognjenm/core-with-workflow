<?php

namespace Telenok\Core\Field\System\Permission;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Controller extends \Telenok\Core\Interfaces\Field\Controller {

	protected $key = 'permission';

	public function getTitleList($id = null)
	{
		$term = trim(\Input::get('term'));
		$return = [];

		$sequenceTable = (new \Telenok\Core\Model\Object\Sequence())->getTable();
		$typeTable = (new \Telenok\Core\Model\Object\Type())->getTable();

		\Telenok\Core\Model\Object\Sequence::addMultilanguage('title_type');

		try
		{
			\Telenok\Core\Model\Object\Sequence::select($sequenceTable . '.id', $sequenceTable . '.title', $typeTable . '.title AS title_type')
					->join($typeTable, function($join) use ($sequenceTable, $typeTable)
					{
						$join->on($sequenceTable . '.sequences_object_type', '=', $typeTable . '.id');
					})
					->where(function ($query) use ($sequenceTable, $typeTable, $term)
					{
						$query->where($sequenceTable . '.title', 'like', "%{$term}%")
						->orWhere($sequenceTable . '.id', $term)
						->orWhere($typeTable . '.title', 'like', "%{$term}%");
					})
					->take(20)->get()->each(function($item) use (&$return)
			{
				$return[] = ['value' => $item->id, 'text' => "[{$item->translate('title_type')}#{$item->id}] " . $item->translate('title')];
			});
		}
		catch (\Exception $e)
		{
			echo $e;
		}

		return $return;
	}

	public function preProcess($model, $type, $input)
	{ 
		$input->put('id', $modelField ? $modelField->getKey() : null);
		$input->put('title', ['en' => 'Permission']);
		$input->put('title_list', ['en' => 'Permission']);
		$input->put('code', 'permission');
		$input->put('active', 1);
		$input->put('multilanguage', 0);
		$input->put('show_in_list', 0);
		$input->put('show_in_form', 1);
		$input->put('allow_search', 1);
		$input->put('allow_delete', 0);
		$input->put('allow_create', 1);
		$input->put('allow_update', 1); 

		return parent::preProcess($model, $type, $input);
	}

	public function getFormModelContent($controller = null, $model = null, $field = null, $uniqueId = null)
	{
		$permissions = \Telenok\Core\Model\Security\Permission::all();

		return \View::make("core::field.{$this->getKey()}.model", array(
					'parentController' => $controller,
					'controller' => $this,
					'model' => $model,
					'field' => $field,
					'uniqueId' => $uniqueId,
					'permissions' => $permissions,
				))->render();
	}

	public function saveModelField($field, $model, $input)
	{ /*
	  $permissionList = array_get($input, 'permission', []);

	  \Telenok\Core\Security\Acl::resource($model)->unsetPermission();

	  foreach($permissionList as $permissionCode => $persmissionIds)
	  {
	  if (!empty($persmissionIds))
	  {
	  foreach($persmissionIds as $id)
	  {
	  \Telenok\Core\Security\Acl::subject($id)->setPermission($permissionCode, $model);
	  }
	  }
	  }
	 */
		return $model;
	}

	public function getListFieldContent($field, $item, $type = null)
	{
		$items = [];
		$rows = \Illuminate\Support\Collection::make(\Telenok\Core\Model\Security\Permission::take(8)->get());

		if ($rows->count())
		{
			foreach ($rows->slice(0, 7, TRUE) as $row)
			{
				$items[] = $row->translate('title');
			}

			return '"' . implode('", "', $items) . '"' . (count($rows) > 7 ? ', ...' : '');
		}
	}

}

?>