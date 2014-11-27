<?php

namespace Telenok\Core\Interfaces\Module;

interface IModule {

    public function getName();
    
    public function getHeader();
    
    public function getHeaderDescription();

    public function setKey($key);

    public function getKey();

    public function setPermissionKey($key = ''); 
    
    public function setRequest(\Illuminate\Http\Request $request = null);

    public function getRequest();

    public function getPermissionKey();

    public function getParent();

    public function getIcon();
	
    public function getActionParam();

    public function getBreadcrumbs();

    public function getPageHeader();
}