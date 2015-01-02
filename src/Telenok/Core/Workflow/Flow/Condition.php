<?php

namespace Telenok\Core\Workflow\Flow;

class Condition extends \Telenok\Core\Interfaces\Workflow\Edge {

	protected $key = 'condition-flow';

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
                                oryx:edge="edge" >
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
}