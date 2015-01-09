<?php namespace Telenok\Core\Workflow\Gateway;

class Complex extends \Telenok\Core\Interfaces\Workflow\Gateway {

    protected $key = 'gateway-complex';

    protected $stencilCardinalityRules = [
            [
                'role' => 'gateway',
                'minimumOccurrence' => 0,
                'maximumOccurrence' => 10000,
                'outgoingEdges' => [
                    [
                        'role' => 'controlflow',
                        'maximum' => 10000
                    ]
                ],
                'incomingEdges' => [
                    [
                        'role' => 'controlflow',
                        'maximum' => 10000
                    ]
                ]
            ]
    ];

    public function getPropertyValue($data = [])
    {
        $stencilData = $this->getStencilData($data);

		$commonProperty = parent::getPropertyValue($data); 

        $commonProperty->put('type', $stencilData->get('type', 'parallel'));

        return $commonProperty;
	}

	/*
	 * Allow go out special flows returned by user's function
	 */
    public function getProcessedLinkOut()
    {
		if ( ($classMethod = explode('@', $this->getInput()->get('class_method'), 2)) && count($classMethod) == 2 )
		{
			list($class, $method) = $classMethod;
			
			return (new $class)->$method($this);
		}
		else
		{
			return $this->getLinkOut();
		}
	}

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
                                           xmlns:oryx="http://www.b3mn.org/oryx"
                                           xmlns:svg="http://www.w3.org/2000/svg"
                                           xmlns="http://www.w3.org/2000/svg"
                                           version="1.0"
                                           width="40"
                                           height="40">
                                          <oryx:magnets>
                                            <oryx:magnet
                                               oryx:default="yes"
                                               oryx:cy="16"
                                               oryx:cx="16" />
                                          </oryx:magnets>
                                          <g>
                                            <path
                                               d="M -4.5,16 L 16,-4.5 L 35.5,16 L 16,35.5z"
                                               id="frame"
                                               fill="#ffffff"
                                               style="stroke:#000000;stroke-width:1" />
                                            <path
                                               d="M 6.25,16 L 25.75,16 M 16,6.25 L 16,25.75 M 8.85,8.85 L 23.15,23.15 M 8.85,23.15 L 23.15,8.85"
                                               style="fill:#ffffff;stroke:#000000;stroke-width:3.9" />

                                            <text id="text_name" x="26" y="26" oryx:align="left top"/>

                                          </g>
                                        </svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/gateway/" . $this->getKey() . ".png",
						'defaultAlign' => "east",
						'roles' => ["gateway"],
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