<?php

namespace Telenok\Core\Workflow\Point\Start;

class Form extends \Telenok\Core\Interfaces\Workflow\Point {
 
    protected $minIn = 0;
    protected $minOut = 1;
 
    protected $maxIn = 0;
    protected $maxOut = 1;
    
    protected $total = 1;
    
    protected $key = 'point-form';
    protected $propertyView = 'core::workflow.point-form.property';
    protected $routerPropertyContent = 'cmf.workflow.point-form.property';

	public function isEventForMe(\Telenok\Core\Workflow\Event $event)
    {
		$eventList = $this->getInput()->get('event_list', []);

        return in_array($event->getEventCode(), $eventList);
    }

    public function getPropertyValue($data = [])
    {
        $stencilData = $this->getStencilData($data);
        
		$commonProperty = parent::getPropertyValue($data); 
        
        $commonProperty->put('event_list', $stencilData->get('event_list', []));
        
        return $commonProperty;
	}
    
    public function getStartEventObject($id, $resourceId, $property, $process)
    {
        return ['workflow.update.before' => [1128,2,3]];
    }
	
    protected $stencilCardinalityRules = [
        [
            'role' => 'sequence_start',
            'minimumOccurrence' => 1,
            'maximumOccurrence' => 1,
            'incomingEdges' => 
            [
                [
                    'role' => 'controlflow',
                    'maximum' => 0
                ]
            ]
        ]
    ];
	
    public function getStencilConfig()
    {
        if (empty($this->stencilConfig))
        {
            $this->stencilConfig = [
                        'type' => 'node',
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
                                       width="40"
                                       height="40"
                                       version="1.0">
                                      <defs></defs>
                                      <oryx:magnets>
                                        <oryx:magnet oryx:cx="16" oryx:cy="16" oryx:default="yes" />
                                      </oryx:magnets>
                                      <g pointer-events="fill">

                                        <defs>
                                            <radialGradient id="background" cx="10%" cy="10%" r="100%" fx="10%" fy="10%">
                                                <stop offset="0%" stop-color="#ffffff" stop-opacity="1"/>
                                                <stop id="fill_el_1" offset="100%" stop-color="#ffffff" stop-opacity="1"/>
                                            </radialGradient>
                                        </defs>

                                        <circle 
                                            id="border_el_1" 
                                            cx="16" 
                                            cy="16" 
                                            r="15" 
                                            stroke="black" 
                                            fill="url(#background) white" 
                                            stroke-width="1" />

                                        <circle 
                                            id="border_el_2" 
                                            cx="16" 
                                            cy="16" 
                                            r="12" 
                                            stroke="black" 
                                            fill="none" 
                                            stroke-width="1" />

                                        <rect id="border_el_3"  x="8" y="8" width="16" height="16" stroke="black" stroke-width="1" fill="none" />
                                        
                                        <path id="border_el_4"
                                            d="M 10 10 L 22 10
                                                M 10 14 L 22 14
                                                M 10 18 L 22 18
                                                M 10 22 L 22 22" fill="none" stroke="black" />
                                        <text font-size="11" 
                                            id="title" 
                                            x="16" y="33" 
                                            oryx:align="top center" 
                                            stroke="black"
                                        ></text>
                                      </g>
                                    </svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/pointstart/" . $this->getKey() . ".png",
                        'defaultAlign' => "south",
                        'roles' => ["sequence_start", "point"],
						'propertyPackages' => ["bgcolor", "bordercolor"],
                        'properties' => [
                            [
                                "id" => "title",
                                "type" => "string",
                                "value" => $this->LL('title'),
                                "refToView" => "title",
                            ],
                        ],
                    ];
        }
        
        return $this->stencilConfig;
    }

    
}

?>