<?php

namespace Telenok\Core\Module\Workflow\Process;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller 
{
    protected $key = 'workflow-process';
    protected $parent = 'workflow';
    protected $typeList = 'workflow_process';

    protected $presentationFormModelView = 'core::module.workflow-process.form'; 
    protected $diagramStensilSet = 'core::module.workflow-process.stensilset'; 
    protected $diagramBody = 'core::module.workflow-process.diagram'; 

    public function preProcess($model, $type, $input)
    {
        $processConfig = (array)$input->get('process');
        
        if (!empty($processConfig))
        {
            \Telenok\Core\Workflow\Process::validate($processConfig);
        }
        
        return $this;
    }

    public function diagramShow($id = 0)
    { 
        $model = \Telenok\Core\Model\Workflow\Process::find($id);
                
        return \View::make($this->diagramBody, [
                'controller' => $this,
                'model' => $model,
                'uniqueId' => uniqid(),
            ])->render();
    }

    public function diagramStencilSet()
    {            
      /*
        return \View::make($this->diagramStensilSet, [
                'controller' => $this,  
                'uniqueId' => uniqid(),
            ])->render();
        */
        $data = [
            'title' => $this->LL('diagram.title'),
            "namespace" => "http://b3mn.org/stencilset/telenok#",
            'description' => $this->LL('diagram.description'),
            'stencils' => [
                [
                    "type" => "node",
                    "id" => "TelenokDiagram",
                    "title" => "Business Diagram",
                    "groups" => ["Diagram"],
                    "description" => "A Process Diagramm", 
                    "view" => "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?><svg    xmlns=\"http://www.w3.org/2000/svg\"    xmlns:svg=\"http://www.w3.org/2000/svg\"    xmlns:oryx=\"http://www.b3mn.org/oryx\"    xmlns:xlink=\"http://www.w3.org/1999/xlink\"    width=\"800\"    height=\"600\"    version=\"1.0\">   <defs></defs>   <g pointer-events=\"fill\" >     <polygon stroke=\"black\" fill=\"black\" stroke-width=\"1\" points=\"0,0 0,590 9,599 799,599 799,9 790,0\" stroke-linecap=\"butt\" stroke-linejoin=\"miter\" stroke-miterlimit=\"10\" />     <rect id=\"diagramcanvas\" oryx:resize=\"vertical horizontal\" x=\"0\" y=\"0\" width=\"790\" height=\"590\" stroke=\"black\" stroke-width=\"2\" fill=\"white\" />      <text font-size=\"22\" id=\"diagramtext\" x=\"400\" y=\"25\" oryx:align=\"top center\" stroke=\"black\"></text>   </g> </svg> ",
                    'icon' => \Config::get('app.url')."/packages/telenok/core/js/oryx/scripts/stencilsets/telenok/icons/diagram.png",
                    "mayBeRoot" => true,
                    "hide" => true,
                    "roles" => ["canContainArtifacts"]
                ]
            ],
            'rules' => [
                'containmentRules' => [
                    [
                        "role" => "TelenokDiagram",
                        "contains" => ["edge", "point", "activity"]
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
            ],
            "propertyPackages" => []
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