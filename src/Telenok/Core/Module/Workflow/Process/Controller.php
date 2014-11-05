<?php

namespace Telenok\Core\Module\Workflow\Process;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller {

    protected $key = 'workflow-process';
    protected $parent = 'workflow';
    protected $typeList = 'workflow_process';

    protected $presentation = 'tree-tab-object';

    protected $presentationFormFieldListView = 'core::module.workflow-process.form-field-list';
    protected $diagramStensilSet = 'core::module.workflow-process.stensilset'; 
    protected $diagramBody = 'core::module.workflow-process.diagram'; 

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
				$resourceId = array_get($shape, 'resourceId', false);
				$property = array_get($properties, $resourceId, false);
				
				$e = $elements->get($id);
				
				if ($e instanceof \Telenok\Core\Interfaces\Workflow\Point)
				{
					$r = $e->getStartEventObject($id, $resourceId, $property, $process);

					if ($r !== false)
					{
						$startEventObject[] = $r;
					}
				}
			}
		}

		return $startEventObject;
	}
	
    public function preProcess($model, $type, $input)
    {
        $process = json_decode($input->get('process', "[]"), true);

		$input->put('process', $process);
		 
        //$this->validate($process);

		$eventObject = $this->getStartEventObject($process);

		$input->put('event_object', $eventObject);

        return $this;
    }

    public function applyDiagram()
    { 
		$clear = \Input::get('clear', false);
		$clearOnly = \Input::get('clearOnly', false);
		$diagramData = json_decode(\Input::get('diagram', ''), true);
		$sessionDiagramId = \Input::get('sessionDiagramId');

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
        $id = \Input::get('diagramId');
        
		$model = \Telenok\Workflow\Process::find($id);
		
        return \View::make($this->diagramBody, [
                'controller' => $this,
                'model' => $model,
                'stencilData' => ($model ? $model->process->get('diagram', []) : false),
                'uniqueId' => str_random(), 
				'sessionDiagramId' => \Input::get('sessionDiagramId'),
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
        
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function getElements()
    {
        return \App::make('telenok.config')->getWorkflowElement();
    }
    
}

?>