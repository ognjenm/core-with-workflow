<?php

namespace Telenok\Core\Workflow\Flow;

class ConditionalSequence extends \Telenok\Core\Interfaces\Workflow\Flow {

	protected $key = 'sequence-conditional';
	
    public function canGoNext()
    {
        $paramElement = $this->getInput();

		if ( ($classMethod = explode('@', $paramElement->get('class_method'), 2)) && count($classMethod) == 2 )
		{
			list($class, $method) = $classMethod;
			
			if (!(new $class)->$method($this))
			{
				return false;
			}
		}

        $conditions = $paramElement->get('condition', []);
		
		foreach($conditions as $condition)
		{
			$conditionCollection = \Illuminate\Support\Collection::make($condition);
		
			switch($conditionCollection->get('type'))
			{
				case 'parameter':
					
					if (!$this->processParameter($conditionCollection))
					{
						return false;
					}
					
					break;
					
				case 'variable':
					
					if (!$this->processVariable($conditionCollection))
					{
						return false;
					}
					
					break;
					
				case 'model_field':
					
					if (!$this->processModelField($conditionCollection))
					{
						return false;
					}

					break;
			}
		}
		
        return true;
    }

	public function compareValue($one = null, $two = null, $case = null) 
	{
		switch($case)
		{
			case 'equal':
				return $one == $two;

			case 'not_equal':
				return $one != $two;

			case 'equal_or_less':
				return $one <= $two;

			case 'equal_or_more':
				return $one >= $two;

			case 'less':
				return $one < $two;

			case 'more':
				return $one > $two;
		}
	}
	
    /*
     * @param \Illuminate\Support\Collection
     */
	public function processParameter($condition)
	{
		$parameterName = $condition->get('parameter');
		$parameterValue = $condition->get('value');
		$parameterCase = $condition->get('case');
		
		$one = $this->getThread()->getParameterByCode($parameterName);
		$two = \Telenok\Core\Workflow\TemplateMarker\TemplateMarkerModal::make()->processMarkersString($parameterValue);

		return $this->compareValue($one, $two, $parameterCase);
	}

    /*
     * @param \Illuminate\Support\Collection
     */
	public function processVariable($condition)
	{
	}

    /*
     * @param \Illuminate\Support\Collection
     */
	public function processModelField($condition)
	{
		$parameterFieldCode = $condition->get('model_field');
		$parameterValue = $condition->get('value');
		$parameterCase = $condition->get('case');
		
		$model = $eventResource->get('model');
		
		if (!$model)
		{
			throw new \Exception('Process hasn\'t correct model as event resource');
		}
		
		$one = $model->$parameterFieldCode;
		$two = \Telenok\Core\Workflow\TemplateMarker\TemplateMarkerModal::make()->processMarkersString($parameterValue);
		
		return $this->compareValue($one, $two, $parameterCase);
	}
	
	public function getStencilConnectionRules()
	{
		if (empty($this->stencilConnectionRules))
		{
			$this->stencilConnectionRules = [
						[
							'role' => 'controlflow',
							'connects' => [
								[
									'from' => 'sequence_start',
									'to' => ['sequence_end', 'activity', 'gateway']
								],
								[
									'from' => 'activity',
									'to' => ['sequence_end', 'activity', 'gateway']
								],
								[
									'from' => 'gateway',
									'to' => ['sequence_end', 'activity', 'gateway']
								]
							]
						]
			];
		}

		return $this->stencilConnectionRules;
	}

	public function getStencilConfig()
	{
		if (empty($this->stencilConfig))
		{
			$this->stencilConfig = [
				'type' => 'edge',
				'id' => $this->getKey(),
				'title' => $this->LL('title'),
				'groups' => [$this->LL('title.groups')],
				'description' => $this->LL('description'),
                'urlPropertyContent' => $this->getRouterPropertyContent(),
                'urlStoreProperty' => $this->getRouterStoreProperty(),
				'view' => '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
                                <svg
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:oryx="http://www.b3mn.org/oryx"
                                version="1.0"
                                oryx:edge="edge">
                                <defs>
                                    <marker id="start" refX="0" refY="5" markerUnits="userSpaceOnUse" markerWidth="17" markerHeight="11" orient="auto">
                                        <path d="M 0 5 L 8 0 L 16 5 L 8 10 L 0 5" fill="white" stroke="black" stroke-width="1" />
                                    </marker>
                                    <marker id="end" refX="15" refY="5" markerUnits="userSpaceOnUse" markerWidth="15" markerHeight="10" orient="auto">
                                        <path d="M 0 0 L 15 5 L 0 10 L 0 0" fill="black" stroke="black" stroke-width="1" />
                                    </marker>
                                </defs>
                                <g id="edge">
                                    <path d="M10 50 L210 50" stroke="black" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" marker-start="url(#start)" marker-end="url(#end)" />
                                </g>
                            </svg>',
				'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/flow/" . $this->getKey() . ".png",
				'roles' => ["controlflow"],
				'properties' => []
			];
		}

		return $this->stencilConfig;
	}

    public function getConditionalTemplateView()
    {
        return 'core::workflow.' . $this->getKey() . '.condition-template';
    }

    public function getPropertyValue($data = [])
    {
        $stencilData = $this->getStencilData($data);
        
		$commonProperty = parent::getPropertyValue($data); 
        
        $commonProperty->put('condition', $stencilData->get('condition', []));
        
        return $commonProperty;
	}

    public function getConditionalTemplateContent($model = null)
    {
        if (!$model)
        {
            $processId = $this->getRequest()->get('processId', 0);
            $model = \App\Model\Telenok\Workflow\Process::find($processId);
        }

		return ['tabContent' => view($this->getConditionalTemplateView(), [
				'controller' => $this,
				'uniqueId' => str_random(),
				'processId' => $processId,
                'model' => $model,
				'p' => \Illuminate\Support\Collection::make(),
			])->render()];
    }

	public function getOrder() 
	{       
		return intval($this->getInput()->get('order'));
	}
}