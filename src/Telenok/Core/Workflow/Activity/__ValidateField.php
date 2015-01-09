<?php

namespace Telenok\Core\Workflow\Activity;

class ValidateField extends \Telenok\Core\Interfaces\Workflow\Activity {
    
    protected $minIn = 1;
    protected $minOut = 1;
 
    protected $maxIn = 1;
    protected $maxOut = 1;
    
    protected $key = 'action-validate-field';

    protected $stencilConfig = [
            'type' => 'node',
            'id' => 'action-validate-field',
            'title' => 'Validate Field',
            'groups' => ["Activities"],
            'description' => 'Validate field for any documents',
            "view" => "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
                            <svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:svg=\"http://www.w3.org/2000/svg\" xmlns:oryx=\"http://www.b3mn.org/oryx\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" width=\"102\" height=\"82\" version=\"1.0\">
                                <defs></defs>
                                <oryx:magnets>
                                    <oryx:magnet oryx:cx=\"1\" oryx:cy=\"40\" oryx:anchors=\"left\" />
                                    <oryx:magnet oryx:cx=\"50\" oryx:cy=\"79\" oryx:anchors=\"bottom\" />
                                    <oryx:magnet oryx:cx=\"99\" oryx:cy=\"40\" oryx:anchors=\"right\" />
                                    <oryx:magnet oryx:cx=\"50\" oryx:cy=\"1\" oryx:anchors=\"top\" />
                                    <oryx:magnet oryx:cx=\"50\" oryx:cy=\"40\" oryx:default=\"yes\" />
                                </oryx:magnets>
                                <g pointer-events=\"fill\" oryx:minimumSize=\"50 40\" oryx:maximumSize=\"200 160\" >
                                    <rect id=\"taskrect\" oryx:resize=\"vertical horizontal\" x=\"0\" y=\"0\" width=\"100\" height=\"80\" rx=\"10\" ry=\"10\" stroke=\"black\" stroke-width=\"1\" fill=\"white\" />
                                    <text font-size=\"14\" id=\"title\" x=\"50\" y=\"40\" oryx:align=\"middle center\" oryx:fittoelem=\"taskrect\" stroke=\"black\"></text>
                                </g>
                            </svg>",
            'icon' => "/packages/telenok/core/js/oryx/stencilsets/bpmn2.0/icons/activity/task.png",
            'roles' => ["edge", "activity"],
            'properties' => [
                [
                    "id" => "name",
                    "type" => "String",
                    "title" => "Name",
                    "value" => "Validate Field",
                    "description" => "",
                    "readonly" => false,
                    "optional" => true,
                    "refToView" => "title",
                    "length" => "",
                    "wrapLines" => true
                ],
                [
                    "id" => "qualifications",
                    "type" =>  "complex",
                    "title" =>  "qualifications",
                    "title_de" =>  "Qualifikationen",
                    "description" =>  "The qualifications this profile comprises",
                    "description_de" =>  "Die Qualifikationen die dieses Profil umfasst",
                    "tooltip" => "",
                    "readonly" =>  false,
                    "popular" =>  true,
                    "optional" =>  true,
                    "wrapLines" =>  false,
                    "value" =>  "{}",
                    "complexItems" =>  [
                        [
                            "id" => "qualification",
                            "title" => "Qualification",
                            "type" => "script",
                            "script" => "property.hrepk.qualification",
                            "value" => "",
                            "optional" => false
                        ],
                        [
                            "id" => "level",
                            "title" => "Level",
                            "type" => "choice",
                            "value" => "",
                            "optional" => false,
                            "items"  =>  [
                                [
                                    "id" => "qualification_level_1",
                                    "title" => "1",
                                    "value" => "1"
                                ],
                                [
                                    "id" => "qualification_level_2",
                                    "title" => "2",
                                    "value" => "2"
                                ]
                            ]
                        ]
                    ]
                ]
            ],
    ];

    public function process($log = [])
    {
        //\Log::info('Business Process: Event:'.$this->getProcess()->getEvent()->getEventCode().'. Process action with code "'.$this->key.'"');
        
        //$paramElement = $process->getParam();
        
        return $this;
    }
}

