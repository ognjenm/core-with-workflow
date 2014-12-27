<?php

namespace Telenok\Core\Module\Workflow\Process;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

    protected $key = 'workflow-process';
    protected $parent = 'workflow';
    protected $modelListClass = '\App\Model\Telenok\Workflow\Process';

    protected $presentation = 'tree-tab-object';

    protected $presentationFormFieldListView = 'core::module.workflow-process.form-field-list';
    protected $diagramStensilSetView = 'core::module.workflow-process.stensilset'; 
    protected $diagramView = 'core::module.workflow-process.diagram'; 

    public function getStartEventObject($process)
    {	
		$properties = array_get($process, 'stencil', false);
		$shapes = array_get($process, 'diagram.childShapes', false);

		$startEventObject = [];
		
		if ($shapes)
		{
			$elements = $this->getElements();
			
			foreach($shapes as $shape)
			{
				$id = array_get($shape, 'stencil.id', false);
				$permanentId = array_get($shape, 'permanentId', false);
				$property = array_get($properties, $permanentId, false);

				$e = $elements->get($id);

				if ($e instanceof \Telenok\Core\Interfaces\Workflow\Point)
				{
					$r = $e->getStartEventObject($id, $permanentId, $property, $process);

					if ($r !== false)
					{
                        foreach ($r as $pId)
                        {
                            $startEventObject[] = $pId;
                        }
					}
				}
			}
		}

		return $startEventObject;
	}

    public function applyDiagram()
    { 
		$id = $this->getRequest()->input('id', 0);
		$clear = $this->getRequest()->input('clear', false);
		$clearOnly = $this->getRequest()->input('clearOnly', false);
		$diagramData = json_decode($this->getRequest()->input('diagram', ''), true);
		$sessionDiagramId = $this->getRequest()->input('sessionDiagramId');

		if ($clear || $clearOnly)
		{
			\Session::forget('diagram.' . $sessionDiagramId . '.stenciltemporary');
			
			if ($clearOnly)
			{
				return [];
			}
		}

		if (!$clearOnly)
		{
			$stencilTemporaryData = \Session::get('diagram.' . $sessionDiagramId . '.stenciltemporary', []);
             
			$stencilData = \Session::get('diagram.' . $sessionDiagramId . '.stencil', []);
            
			if (!empty($stencilTemporaryData))
			{
				foreach($stencilTemporaryData as $key => $stencil)
				{
					$stencilData[$key] = $stencil;
				}
			}

            if ($id)
            {
                $process = \App\Model\Telenok\Workflow\Process::find($id);
                
                if ($process && !empty($process->process->get('stencil')))
                {
                    foreach($process->process->get('stencil') as $permanentId => $s)
                    {
                        if (!isset($stencilData[$permanentId]))
                        {
                            $stencilData[$permanentId] = $s;
                        }
                    }
                }
            }
            
			\Session::put('diagram.' . $sessionDiagramId . '.stencil', $stencilData);
			\Session::put('diagram.' . $sessionDiagramId . '.diagram', $diagramData);
		}

		return ['stencil' => $stencilData, 'diagram' => $diagramData];
    }

    public function getAdditionalViewParam()
    {
		$p = parent::getAdditionalViewParam();

		$p['sessionDiagramId'] = str_random();

        return $p;
    }    

    public function diagramShow()
    { 
        $id = $this->getRequest()->input('diagramId');
        
		$model = \App\Model\Telenok\Workflow\Process::find($id);
		
        return view($this->diagramView, [
                'controller' => $this,
                'model' => $model,
                'stencilData' => ($model ? $model->process->get('diagram', []) : false),
                'uniqueId' => str_random(), 
				'sessionDiagramId' => $this->getRequest()->input('sessionDiagramId'),
            ])->render();
    }

    public function diagramStencilSet()
    {            
        $data = [
            'title' => $this->LL('diagram.title'),
            'namespace' => "http://b3mn.org/stencilset/telenok#",
            'description' => $this->LL('diagram.description'),
            'stencils' => [
                [
                    "type" => "node",
                    "id" => "TelenokDiagram",
                    "title" => "Business Diagram",
                    "groups" => ["Diagram"],
                    "description" => "A Process Diagramm", 
                    "view" => '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
									<svg
											xmlns="http://www.w3.org/2000/svg"
											xmlns:svg="http://www.w3.org/2000/svg"
											xmlns:oryx="http://www.b3mn.org/oryx"
											xmlns:xlink="http://www.w3.org/1999/xlink"
											width="800"
											height="600"
											version="1.0">
										<defs></defs>
										<g pointer-events="fill" >
										<polygon stroke="black" fill="black" stroke-width="1" points="0,0 0,590 9,599 799,599 799,9 790,0" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" />
										<rect id="diagramcanvas" oryx:resize="vertical horizontal" x="0" y="0" width="790" height="590" stroke="black" stroke-width="2" fill="white" />
											<text font-size="22" id="diagramtext" x="400" y="25" oryx:align="top center" stroke="black"></text>
										</g>
									</svg>',
                    'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/diagram.png",
                    "mayBeRoot" => true,
                    "hide" => true,
                    "roles" => ["canContainArtifacts"]
                ]
            ],
			'propertyPackages' => [
				[
					"name" => "bgcolor",
					"properties" => [
						[
							"id" => "bgcolor",
							"type" => "Color",
							"value" => "#ffffff",
							"refToView" => ["fill_el_1", "fill_el_2", "fill_el_3", "fill_el_4", "fill_el_5", "fill_el_6", "fill_el_7", "fill_el_8"],
							"fill" => true,
						],
					]
				],
				[
					"name" => "bordercolor",
					"properties" => [
						[
							"id" => "bordercolor",
							"type" => "Color",
							"value" => "#000000",
							"refToView" => ["border_el_1", "border_el_2", "border_el_3", "border_el_4", "border_el_5", "border_el_6", "border_el_7", "border_el_8"],
							"stroke" => true,
						],
					]
				],
			],
            'rules' => [
                'containmentRules' => [
                    [
                        "role" => "TelenokDiagram",
                        "contains" => ["point", "activity"]
                    ]
                ],
                'cardinalityRules' => [],
                'connectionRules' => [],
                'morphingRules' => [
                    [
                        "role" => "activity",
                        "baseMorphs" => [],
                        "preserveBounds" => false
                    ],

                ],
                'layoutRules' => [],
            ]
        ];

        $elements = $this->getElements();

        $connectionRules = [];
        $connectionStencilRules = [];
        $cardinalityStencilRules = [];
        
        foreach ($elements as $key => $element)
        {
            $data['stencils'][] = $element->getStencilConfig();
            
            $connectionRule = $element->getStencilConnectionRules();
            $cardinalityRule = $element->getStencilCardinalityRules();
                    
            if (!empty($cardinalityRule))
            {
                $cardinalityStencilRules = array_merge($cardinalityStencilRules, $element->getStencilCardinalityRules());
            }
            
            if (!empty($connectionRule))
            {
                foreach ($connectionRule as $rule)
                {
                    foreach($rule['connects'] as $connect)
                    {
                        $connectionRules[array_get($rule, 'role')][] = $connect;
                    }
                }
            }
        }

        foreach($connectionRules as $role => $connects)
        {
            $connectionStencilRules[] = ['role' => $role, 'connects' => $connects];
        }
        
        array_set($data, 'rules.connectionRules', $connectionStencilRules);
        array_set($data, 'rules.cardinalityRules', $cardinalityStencilRules);
        
        return $data;
    }

    public function getElements()
    {
        return app('telenok.config')->getWorkflowElement();
    }
	
    public function preProcess($model, $type, $input)
    {
        $process = json_decode($input->get('process', "[]"), true);
 
		$input->put('is_valid', $this->validateBusinessProcessScheme($process));
		$input->put('process', $process);
        
		$eventObject = $this->getStartEventObject($process);

		$input->put('event_object', $eventObject);

        return $this;
    }

    public function validateBusinessProcessScheme($process = [])
    {
        $isValid = true;

        $elements = app('telenok.config')->getWorkflowElement();
        $processCollection = \Illuminate\Support\Collection::make($process);
        
        $stencilData = $processCollection->get('stencil', []);
        $diagramData = array_get($processCollection->all(), 'diagram.childShapes', []);

        $actions = \Illuminate\Support\Collection::make([]);
        
        foreach($diagramData as $action)
        {
            $el = $elements->get($action['stencil']['id']);

            if ($el)
            {
                $actions->put($action['resourceId'], 
                    $el->make()
                        ->setId($action['resourceId'])
                        ->setInput(array_get($stencilData, 'stencil.' . $action['permanentId'], []))
                        ->setLinkOut(\Illuminate\Support\Collection::make(array_get($action, 'outgoing'))->flatten())
                        ->setLinkIn(\Illuminate\Support\Collection::make([]))); 
            }
            else 
            {
                \Session::flash('warning.workflow-make-' . $action['resourceId'], 'Cant validate action with key "' . $action['stencil']['id'] . '"');

                return false;      
            }
        } 

        // accumulate actions In
        foreach($actions->all() as $action)
        {
            foreach($action->getLinkOut()->all() as $o)
            {
                $in = $actions->get($o)->getLinkIn();

                $in->push($o);

                $actions->get($o)->setLinkIn($in);
            }
        }

        foreach($actions->all() as $action)
        {
            try
            {
                $action->validate($this, $actions, $diagramData, $stencilData);
            }
            catch (\Exception $ex)
            {
                \Session::flash('warning.workflow-' . $action->getId(), $ex->getMessage());

                $isValid = false;
            }
        }

        return $isValid; 
    }
 
    public function getScriptModalContent($attr = [], $uniqueId = '')
    {
        $attr = \Illuminate\Support\Collection::make($attr);

        return view('core::module/workflow-process.modal-template-marker', [
            'controller' => $this,
            'fieldId' => $attr->get('fieldId'),
            'buttonId' => $attr->get('buttonId'),
            'uniqueId' => $uniqueId,
        ])->render();
    }
}