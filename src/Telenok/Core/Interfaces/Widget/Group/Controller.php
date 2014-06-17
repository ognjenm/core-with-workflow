<?php

namespace Telenok\Core\Interfaces\Widget\Group;

abstract class Controller {
    
    protected $key = '';
    protected $icon = 'fa fa-desktop'; 
    protected $package; 
    protected $widgetGroupModel;

    public function getName()
    {
        return $this->LL('name');
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setWidgetGroupModel($model)
    {
        $this->widgetGroupModel = $model;

        return $this;
    }

    public function getWidgetGroupModel()
    {
        return $this->widgetGroupModel;
    }

    public function getPackage()
    {
        if ($this->package) return $this->package;
        
        $list = explode('\\', __NAMESPACE__);
        
        return strtolower(array_get($list, 1));
    }

    public function LL($key='', $param = [])
    {
        $key_ = "{$this->getPackage()}::widget-group/{$this->getKey()}.$key";
        $key_default_ = "{$this->getPackage()}::default.$key";
        
        $word = \Lang::get($key_, $param);
        
        // not found in current wordspace
        if ($key_ === $word)
        {
            $word = \Lang::get($key_default_, $param);
            
            // not found in default wordspace
            if ($key_default_ === $word)
            {
                return $key_;
            }
        } 
        
        return $word;
    } 
}

?>