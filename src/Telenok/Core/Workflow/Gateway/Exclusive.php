<?php namespace Telenok\Core\Workflow\Gateway;

class Exclusive extends \Telenok\Core\Interfaces\Workflow\Gateway {

    protected $key = 'gateway-exclusive';

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
	 * Allow go out only one flow
	 */
    public function getProcessedLinkOut()
    {
		$linkOut = parent::getProcessedLinkOut();

		if (!$linkOut->count())
		{
			throw new \Exception('Element with key "' . $this->getKey() . '" and resource ID "' . $this->getId() . '" hasn\'t any link out (all conditional flows are false ?)');
		}

		return $linkOut->slice(0, 1);
	}

	/*
	 * Allow go out for each link-in sequence flow
	 */
    public function process($log = [])
    {
		$this->setLog($log);
        $this->setNext();

        return $this;
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
                                          <defs
                                             id="defs4" />
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
                                            <g
                                               id="cross">
                                              <path
                                                 d="M 8.75,7.55 L 12.75,7.55 L 23.15,24.45 L 19.25,24.45 z"
                                                 style="fill:#000000;stroke:#000000;stroke-width:1" />
                                              <path
                                                 d="M 8.75,24.45 L 19.25,7.55 L 23.15,7.55 L 12.75,24.45 z"
                                                 style="fill:#000000;stroke:#000000;stroke-width:1" />
                                            </g>

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