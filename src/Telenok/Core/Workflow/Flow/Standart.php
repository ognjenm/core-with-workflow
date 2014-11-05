<?php

namespace Telenok\Core\Workflow\Flow;

class Standart extends \Telenok\Core\Interfaces\Workflow\Edge {

	protected $minIn = 1;
	protected $minOut = 0;
	protected $maxIn = 2000000000;
	protected $maxOut = 0;
	protected $total = 1;
	protected $key = 'standart-flow';

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
									'to' => ['sequence_end', 'activity']
								],
								[
									'from' => 'activity',
									'to' => ['sequence_end', 'activity']
								],
								[
									'from' => 'activity',
									'to' => ['activity']
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
				'view' => '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
							<svg
									xmlns="http://www.w3.org/2000/svg"
									xmlns:oryx="http://www.b3mn.org/oryx"
									version="1.0"
									oryx:edge="edge" >
								<defs>
								<marker id="start" oryx:optional="yes" oryx:enabled="yes" refX="5" refY="5" markerUnits="userSpaceOnUse" markerWidth="10" markerHeight="10" orient="auto">
								<!-- <path d="M 10 10 L 0 5 L 10 0" fill="none" stroke="black" /> -->
								<circle cx="5" cy="5" r="5" fill="white" stroke="black" />
								</marker>

								<marker id="end" refX="10" refY="5" markerUnits="userSpaceOnUse" markerWidth="10" markerHeight="10" orient="auto">
								<path d="M 0 0 L 10 5 L 0 10 L 0 0" fill="white" stroke="black" />
								</marker>
								</defs>
								<g id="edge">
								<path d="M10 50 L210 50" stroke="black" fill="none" stroke-width="2" stroke-dasharray="3, 4" marker-start="url(#start)" marker-end="url(#end)" />
								<text id="text_name" x="0" y="0" oryx:edgePosition="midTop"/>
								</g>
							</svg>',
				'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/flow/" . $this->getKey() . ".png",
				'roles' => ["controlflow"],
				'properties' => [
					[
						"id" => "title",
						"type" => "string",
                        "value" => $this->LL('title'),
						"refToView" => "title",
					]
				]
			];
		}

		return $this->stencilConfig;
	}

}

?>