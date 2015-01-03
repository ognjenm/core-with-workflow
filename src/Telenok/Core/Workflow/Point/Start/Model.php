<?php

namespace Telenok\Core\Workflow\Point\Start;

class Model extends \Telenok\Core\Interfaces\Workflow\Point {
 
    protected $key = 'point-model';

	public function isEventForMe(\Telenok\Core\Workflow\Event $event)
    {
		$eventList = $this->getInput()->get('event_list', []);

        return in_array($event->getEventCode(), $eventList, true);
    }

    public function getPropertyValue($data = [])
    {
        $stencilData = $this->getStencilData($data);

		$commonProperty = parent::getPropertyValue($data); 

        $commonProperty->put('event_list', $stencilData->get('event_list', []));
        $commonProperty->put('model_type', $stencilData->get('model_type', 0));
        $commonProperty->put('model_list', $stencilData->get('model_list', []));

        return $commonProperty;
	}

    public function getStartEventObject($id, $permanentId, $property, $process)
    {
        return [$permanentId];
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
											<oryx:docker oryx:cx="16" oryx:cy="16" />
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

												<path
												   d="M 6.75,13 L6.75,19 L13,19 L13,25.75 L19,25.75 L19,19 L25.75,19 L25.75,13 L19,13 L19,6.75 L13,6.75 L13,13z"
												   id="border_el_3"
												   style="fill:none;stroke-width:1" stroke="#000000" />

												<text font-size="11" 
													id="title" 
													x="16" y="33" 
													oryx:align="top center" 
													stroke="black"></text>
											</g>
										</svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/pointstart/" . $this->getKey() . ".png",
                        'defaultAlign' => "east",
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