<?php

namespace Telenok\Core\Workflow\Activity;

class FormElementHide extends \Telenok\Core\Interfaces\Workflow\Point {
 
    protected $minIn = 1;
    protected $minOut = 0;
 
    protected $maxIn = 2000000000;
    protected $maxOut = 0;
   
    protected $total = 1;

    protected $key = 'form-element-hide';
    protected $propertyView = 'core::workflow.form-element-hide.property';
    protected $routerPropertyContent = 'cmf.workflow.form-element-hide.property';

    protected $stencilCardinalityRules = [
            [
                'role' => 'activity',
                'minimumOccurrence' => 0,
                'maximumOccurrence' => 10000,
                'outgoingEdges' => [
                    [
                        'role' => 'controlflow',
                        'maximum' => 1
                    ]
                ],
                'incomingEdges' => [
                    [
                        'role' => 'controlflow',
                        'maximum' => 1
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
						"view" => '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
										<svg
										   xmlns="http://www.w3.org/2000/svg"
										   xmlns:svg="http://www.w3.org/2000/svg"
										   xmlns:oryx="http://www.b3mn.org/oryx"
										   xmlns:xlink="http://www.w3.org/1999/xlink"

										   width="102"
										   height="82"
										   version="1.0">
										  <defs></defs>
										  <oryx:magnets>
											<oryx:magnet oryx:cx="1" oryx:cy="20" oryx:anchors="left" />
											<oryx:magnet oryx:cx="1" oryx:cy="40" oryx:anchors="left" />
											<oryx:magnet oryx:cx="1" oryx:cy="60" oryx:anchors="left" />

											<oryx:magnet oryx:cx="25" oryx:cy="79" oryx:anchors="bottom" />
											<oryx:magnet oryx:cx="50" oryx:cy="79" oryx:anchors="bottom" />
											<oryx:magnet oryx:cx="75" oryx:cy="79" oryx:anchors="bottom" />

											<oryx:magnet oryx:cx="99" oryx:cy="20" oryx:anchors="right" />
											<oryx:magnet oryx:cx="99" oryx:cy="40" oryx:anchors="right" />
											<oryx:magnet oryx:cx="99" oryx:cy="60" oryx:anchors="right" />

											<oryx:magnet oryx:cx="25" oryx:cy="1" oryx:anchors="top" />
											<oryx:magnet oryx:cx="50" oryx:cy="1" oryx:anchors="top" />
											<oryx:magnet oryx:cx="75" oryx:cy="1" oryx:anchors="top" />

											<oryx:magnet oryx:cx="50" oryx:cy="40" oryx:default="yes" />
										  </oryx:magnets>
										  <g pointer-events="fill" oryx:minimumSize="50 40">
											<defs>
												<radialGradient id="background" cx="10%" cy="10%" r="100%" fx="10%" fy="10%">
													<stop offset="0%" stop-color="#ffffff" stop-opacity="1"/>
													<stop id="fill_el" offset="100%" stop-color="#ffffcc" stop-opacity="1"/>
												</radialGradient>
											</defs>

											<rect id="text_frame" oryx:anchors="bottom top right left" x="1" y="1" width="94" height="79" rx="10" ry="10" stroke="none" stroke-width="0" fill="none" />
											<rect id="callActivity" oryx:resize="vertical horizontal" oryx:anchors="bottom top right left" x="0" y="0" width="100" height="80" rx="10" ry="10" stroke="black" stroke-width="4" fill="none" />
											<rect id="bg_frame" oryx:resize="vertical horizontal" x="0" y="0" width="100" height="80" rx="10" ry="10" stroke="black" stroke-width="1" fill="url(#background) #ffffcc" />
												<text 
													font-size="12" 
													id="title" 
													x="50" 
													y="40" 
													oryx:align="middle center"
													oryx:fittoelem="text_frame"
													stroke="black">
												</text>


											<g id="none"></g>

										  </g>
										</svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/pointend/" . $this->getKey() . ".png",
						'defaultAlign' => "south",
						'roles' => ["activity"],
						'propertyPackages' => ["bgcolor", "bordercolor"],
                        'properties' => [
                            [
                                "id" => "title",
                                "type" => "string",
                                "value" => $this->LL('property.title.value'),
                                "refToView" => "title",
                            ],
                        ],

                    ];
        }
        
        return $this->stencilConfig;
    }

}

?>