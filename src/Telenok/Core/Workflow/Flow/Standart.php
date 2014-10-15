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
			$this->stencilConnectionRules = 
						[
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
                        'view' => '<svg
												xmlns="http://www.w3.org/2000/svg"
												xmlns:oryx="http://www.b3mn.org/oryx"
												xmlns:svg="http://www.w3.org/2000/svg"
												version="1.2"
												oryx:edge="edge">
											<defs>
												<marker id="start" refX="1" refY="5" markerUnits="userSpaceOnUse" markerWidth="17" markerHeight="11" orient="auto">
													<path id="conditional" d="M 0 5 L 8 0 L 16 5 L 8 10 L 0 5" fill="white" stroke="black" stroke-width="1" />
													<path id="default" d="M 5 0 L 11 10" fill="white" stroke="black" stroke-width="1" />
												</marker>
												<marker id="end" refX="15" refY="6" markerUnits="userSpaceOnUse" markerWidth="15" markerHeight="12" orient="auto">
													<path d="M 0 1 L 15 6 L 0 11z" fill="black" stroke="black" stroke-linejoin="round" stroke-width="2" />
												</marker>
											</defs>
											<g id="edge">
												<path d="M10 50 L210 50" stroke="black" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" marker-start="url(#start)" marker-end="url(#end)" />
												<text id="title" x="0" y="0" oryx:edgePosition="midtop"/>
											</g>
										</svg>',
						'icon' => \Config::get('app.url') . "/packages/telenok/core/js/oryx/stencilset/telenok/icons/flow/" . $this->getKey() . ".png",
                        'roles' => ["controlflow"],
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
                            ]/*,
							[
								"id" => "ConditionType",
								"type" => "Choice",
								"title" => "ConditionType",
								"title_de" => "Bedingungstyp",
								"value" => "None",
								"description" => "Determine the typ of the flow object.",
								"description_de" => "Legt den Typ des Flussobjekts fest.",
								"readonly" => false,
								"optional" => false,
								"items" =>  [
									[
										"id" => "none",
										"title" => "Standard",
										"title_de" => "Standard",
										"value" => "None"
									],
									[
										"id" => "expression",
										"title" => "Conditional Flow",
										"title_de" => "Bedingter Fluss",
										"value" => "Expression",
										"icon"  =>  "connector/list/type.expression.png",
										"refToView" => ""
									],
									[
										"id" => "default",
										"title" => "Default Flow",
										"title_de" => "Standardfluss",
										"value" => "Default",
										"icon"  =>  "connector/list/type.default.png",
										"refToView" => "default"
									]
								]
							],
							[
								"id" => "conditionexpression",
								"type" => "String",
								"title" => "Condition Expression",
								"title_de" => "Bedingungsausdruck",
								"value" => "",
								"description" => "",
								"readonly" => false,
								"optional" => true,
								"length" => "",
								"refToView" => "condition",
								"wrapLines" => true
							],
							[
								"id" => "isimmediate",
								"type" => "Boolean",
								"title" => "isImmediate",
								"title_de" => "Sofortige Ausführung",
								"value" => "",
								"description" => "An optional Boolean value specifying whether Activities or Choreography Activities not in the model containing the Sequence Flow can occur between the elements connected by the Sequence Flow. If the value is true, they MAY NOT occur. If the value is false, they MAY occur. Also see the isClosed attribute on Process, Choreography, and Collaboration.",
								"readonly" => false,
								"optional" => false
							],
							[
								"id" => "showdiamondmarker",
								"type" => "Boolean",
								"title" => "is conditional flow",
								"title_de" => "ist bedingter Fluss",
								"value" => false,
								"description" => "System intern variable to set the Diamond invisible, if sourceShape is a gateway and ConditionType is set to Expression",
								"readonly" => true,
								"optional" => false,
								"visible" => false,
								"refToView" => "conditional"
							]*/
						]
                    ];
        }
        
        return $this->stencilConfig;
    }

}

?>