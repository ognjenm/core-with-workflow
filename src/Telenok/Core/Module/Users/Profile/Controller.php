<?php

namespace Telenok\Core\Module\Users\Profile; 

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller {
    
    protected $key = 'users-profile';
    protected $parent = 'users';
    protected $presentation = 'tree-tab-users-profile';
    protected $presentationContentView = 'core::module.users-profile.content';

    public function getTreeContent()
    {
        return false;
    }

    public function getContent()
    {
        return array(
            'tabKey' => $this->getTabKey(),
            'tabLabel' => $this->LL('tab.name'),
            'tabContent' => \View::make($this->getPresentationContentView(), array(
                'controller' => $this, 
                'uniqueId' => uniqid(),
            ))->render()
        );
    }

    public function topMenuMain()
    {
        $collection = \Illuminate\Support\Collection::make([]);
        
        $collection->put('key', 'user-name');
        $collection->put('parent', false);
        $collection->put('order', 100000);
        $collection->put('li', '<li class="light-blue user-profile">');
        $collection->put('content', '<a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
                <img class="nav-user-photo" src="packages/telenok/core/image/anonym.png" alt="Anonym">
                <span id="user_info">
                     ' . $this->LL('welcome', ['username' => \Auth::user()->username]) . '
                </span>
                <i class="fa fa-caret-down"></i>
            </a>');

        return $collection;
    }

    public function topMenuLogout()
    {         
        $collection = \Illuminate\Support\Collection::make([]);
        
        $collection->put('parent', 'user-name');
        $collection->put('key', 'log-off');
        $collection->put('order', 100000);
        $collection->put('devider_before', false);
        $collection->put('devider_after', false);
        $collection->put('content', '<a href="#" onclick="jQuery.ajax(\'' . \URL::route('cmf.logout') . '\').done(function() { window.location = window.location; } ); return false;"><i class="fa fa-power-off"></i> ' . $this->LL('btn.logout') . '</a>');

        return $collection;
    }
}

?>