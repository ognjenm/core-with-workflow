<?php

namespace Telenok\Core\Module\Objects\Lists\Wizard;

class Controller extends \Telenok\Core\Module\Objects\Lists\Controller {

    protected $presentation = 'wizard-model';
    protected $presentationModelView = 'core::module.objects-lists.wizard-model'; 
    protected $presentationListWizardView = 'core::module.objects-lists.wizard-list'; 

    public function getRouterCreate($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.create", $param);
    }

    public function getRouterEdit($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.edit", $param);
    }

    public function getRouterStore($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.store", $param);
    }

    public function getRouterUpdate($param = [])
    {
        return \URL::route("cmf.module.{$this->getKey()}.wizard.update", $param);
    }

    public function getPresentationListWizardView()
    {
        return $this->presentationListWizardView;
    }

	public function typeForm($type)
    {
		return parent::typeForm($type)
				->setPresentationModelView($this->getPresentationModelView())
				->setRouterStore("cmf.module.{$this->getKey()}.wizard.create")
				->setRouterUpdate("cmf.module.{$this->getKey()}.wizard.update");
    }    
	
    public function create($id = null)
    { 
		$this->additionalViewParam = ['presentation' => $this->getPresentation()];
		
        return parent::create($id);
    }

    public function edit($id = null)
    { 
		$this->additionalViewParam = ['presentation' => $this->getPresentation()];
		
        return parent::edit($id);
    } 

    public function store($id = null, $input = [])
    { 
		$this->additionalViewParam = ['presentation' => $this->getPresentation()];
		
        return parent::store($id, $input);
    } 

    public function update($id = null, $input = [])
    { 
		$this->additionalViewParam = ['presentation' => $this->getPresentation()];
		
        return parent::update($id, $input);
    } 
    
    public function choose()
    {
		$typeList = [];
		$id = \Input::get('id', 0);
		
		try
		{
			if (is_array($id))
			{
				$typeList = $id;
				$id = \Telenok\Object\Type::where('code', 'object_sequence')->pluck('id');
			}
			
            $model = $this->modelByType($id);
            $type = $this->getType($id); 
            $fields = $model->getFieldList(); 
        } 
        catch (\Exception $exc) 
        {
            return;
        } 
        
        return array(
            'tabKey' => "{$this->getTabKey()}-{$model->getTable()}",
            'tabLabel' => $type->translate('title'),
            'tabContent' => \View::make($this->getPresentationListWizardView(), array(
                'controller' => $this,  
				'presentation' => $this->getPresentation(),
                'model' => $model,
                'type' => $type,
				'typeList' => $typeList,
                'fields' => $fields,
                'uniqueId' => ($uniqueId = str_random()),
                'gridId' => str_random(),
				'saveBtn' => \Input::get('saveBtn', true), 
				'chooseBtn' => \Input::get('chooseBtn', true), 
                'contentForm' => ( $model->classController() ? $this->typeForm($model)->getFormContent($model, $type, $fields, $uniqueId) : FALSE),
            ))->render()
        );
    }    
	
    public function getFilterQuery($model, $query) {}
	
    public function getWizardList()
    {
        $content = [];
		$typeList = [];

        $iDisplayStart = intval(\Input::get('iDisplayStart', 10));
        $sEcho = \Input::get('sEcho');
		$id = \Input::get('id', 0);

        try
        {
			if (is_array($id))
			{
				$typeList = $id;
				$id = \Telenok\Object\Type::where('code', 'object_sequence')->pluck('id');
			}

            $type = $this->getType($id);
            $model = $this->modelByType($id);  
			$query = $this->getListItem($model);

			if (!empty($typeList))
			{
				$query->join('object_sequence as osequence_wizard_list', function($join) use ($model, $typeList)
				{
					$join->on($model->getTable() . '.id', '=', 'osequence_wizard_list.id');
				}); 

				$query->whereIn('osequence_wizard_list.sequences_object_type', $typeList);
			}

			$items = $query->get();
			
			$config = \App::make('telenok.config')->getObjectFieldController();

            foreach ($items->slice(0, $this->displayLength, true) as $k => $item)
            {
                $put = \Illuminate\Support\Collection::make([]); 

                foreach ($model->getFieldList() as $field)
                { 
					$put->put($field->code, $config->get($field->key)->getListFieldContent($field, $item, $type));
                }

                $put->put('choose', $this->getChooseButton($item, $type, $put));

                $content[] = $put->toArray();
            }
        }
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
        {
            return [
                'gridId' => $this->getGridId(), 
                'sEcho' => $sEcho,
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => []
            ];
        }

        return [
            'gridId' => $this->getGridId($model->getTable()), 
            'sEcho' => $sEcho,
            'iTotalRecords' => ($iDisplayStart + $items->count()),
            'iTotalDisplayRecords' => ($iDisplayStart + $items->count()),
            'aaData' => $content
        ];
    }

    public function getChooseButton($item, $type, $put)
    {
		$uniq = str_random();

        return '
				<script type="text/javascript">
					$(document).on("click", "#btnfield' . $uniq . '", function() {
						var $modal = jQuery(this).closest(".modal"); 
                            $modal.data("model-data")(' . $put->toJson() . '); 
                            return false;
					});
				</script>
				<button id="btnfield' . $uniq . '" type="button" class="btn btn-xs btn-success">'.$this->LL('btn.choose').'</button>

		';
    }
	
	
}