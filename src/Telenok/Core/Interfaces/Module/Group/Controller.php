<?php

namespace Telenok\Core\Interfaces\Module\Group;

abstract class Controller {
    
    use \Telenok\Core\Support\PackageLoad;
    
    protected $key = '';
    protected $icon = 'fa fa-desktop'; 
    protected $btn = 'btn-info'; 
    protected $moduleGroupModel; 
    protected $package; 
    protected $languageDirectory = 'module-group';

    public function getName()
    {
        return $this->LL('name');
    }

    public function getButton()
    {
        return $this->btn;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getKey()
    {
        return $this->key;
    }
    
    public function setModuleGroupModel($model)
    {
        $this->moduleGroupModel = $model;
        
        return $this;
    }
    
    public function getModuleGroupModel()
    {
        return $this->moduleGroupModel;
    }
}

?>