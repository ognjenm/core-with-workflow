<?php

namespace Telenok\Core\Interfaces\Module;

abstract class Controller extends \Illuminate\Routing\Controller {

    protected $key = '';
    protected $parent = '';
    protected $group = '';
    protected $package = '';
    protected $icon = 'fa fa-desktop'; 
    protected $moduleModel; 

    public function __construct()
    {
        if (!\App::runningInConsole())
        {
            $this->beforeFilter('auth');
            $this->beforeFilter(function()
            {
                if (!\Auth::can('read', 'module.' . $this->getKey()))
                {
                    return \Redirect::route('error.access-denied');
                }
            });
        }
    }

    public function getName()
    {
        return $this->LL('name');
    }
    
    public function getHeader()
    {
        return $this->LL('header.title');
    }    
    
    public function getHeaderDescription()
    {
        return $this->LL('header.description');
    }    

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
        
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }  

    public function getPackage()
    {
        if ($this->package) return $this->package;
        
        $list = explode('\\', __NAMESPACE__);
        
        return strtolower(array_get($list, 1));
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getGroup()
    {
        return $this->group;
    }  
    
    public function setModuleModel($model)
    {
        $this->moduleModel = $model;
        
        return $this;
    }
    
    public function getModuleModel()
    {
        return $this->moduleModel;
    }

    public function children()
    {
        $list = \App::make('telenok.config')->getModule();
        $key = $this->getKey();

        return $list->filter(function($item) use ($key) {
            return $key == $item->getParent();
        });
    }

    public function parent()
    {
        if (!$this->getParent()) return false;
        
        return \App::make('telenok.config')->getModule()->get($this->getParent());
    }

    public function isParentAndSingle()
    {
        $list = \App::make('telenok.config')->getModule()->toArray();
        $key = $this->getKey();

        $arr = array_filter($list, function($item) use ($key) {
            return $item->getParent() == $key;
        });

        return !$this->getParent() && empty($arr);
    }  

    public function getRouterActionParam($param = [])
    {
		return \URL::route("cmf.module.{$this->getKey()}.action.param", $param);
    }  
	
    public function getActionParam()
    {
        return json_encode(array(
            'presentationBlockKey' => $this->getPresentation(),
            'presentationBlockContent' => $this->getPresentationContent(),
            'key' => $this->getKey(),
            'contentUrl' => \URL::route("cmf.module.{$this->getKey()}"),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(), 
        ));
    }

    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        
        if ($this->getParent()) $breadcrumbs[] = $this->parent()->getName();
        
        $breadcrumbs[] = $this->getName();
        
        return $breadcrumbs;
    }

    public function getPageHeader()
    {
        return array($this->getHeader(), $this->getHeaderDescription());
    }

    public function LL($key = '', $param = [])
    {
        $key_ = "{$this->getPackage()}::module/{$this->getKey()}.$key";
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