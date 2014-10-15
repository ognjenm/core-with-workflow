<?php

namespace Telenok\Core\Workflow\Point\Start;

class AfterSave extends \Telenok\Core\Interfaces\Workflow\Point {
 
    protected $minIn = 0;
    protected $minOut = 1;
 
    protected $maxIn = 0;
    protected $maxOut = 1;
    
    protected $total = 1;
    
    protected $key = 'point-start-after-save';
    protected $propertyView = 'core::workflow.point-start-after-save.property';
    protected $routerPropertyContent = 'cmf.workflow.point-start-after-save.property';

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
														<stop id="fill_el" offset="100%" stop-color="#ffffff" stop-opacity="1"/>
													</radialGradient>
												</defs>

												<circle 
													id="bg_frame" 
													cx="16" 
													cy="16" 
													r="15" 
													stroke="black" 
													fill="url(#background) white" 
													stroke-width="1" />

												<circle 
													id="frame2_non_interrupting" 
													cx="16" 
													cy="16" 
													r="12" 
													stroke="black" 
													fill="none" 
													stroke-width="1" />

												<path
												   d="M 6.75,13 L6.75,19 L13,19 L13,25.75 L19,25.75 L19,19 L25.75,19 L25.75,13 L19,13 L19,6.75 L13,6.75 L13,13z"
												   id="path9"
												   style="fill:none;stroke:#000000;stroke-width:1" />

												<text font-size="11" 
													id="title" 
													x="16" y="33" 
													oryx:align="top center" 
													stroke="black"></text>
											</g>
										</svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/pointstart/" . $this->getKey() . ".png",
                        'defaultAlign' => "south",
                        'roles' => ["sequence_start", "point"],
						'propertyPackages' => ["bgColor"],
                        'properties' => [
                            [
                                "id" => "name",
                                "type" => "String",
                                "title" => $this->LL('property.title.title'),
                                "value" => $this->LL('property.title.value'),
                                "description" => "",
                                "readonly" => false,
                                "optional" => false,
                                "popular" => true,
                                "refToView" => "title",
                                "length" => "",
                                "wrapLines" => true
                            ],
                            [
                                "id" => "reference",
                                "type" => "script",
                                "title" => "Refence",
                                "value" => "",
                                "description" => "",
                                "popular" => true,
                                "readonly" => false,
                                "optional" => false,
                                "script" => "property.telenok.eventlist",
                            ],
                        ],
                    ];
        }
        
        return $this->stencilConfig;
    }

    
}

?>