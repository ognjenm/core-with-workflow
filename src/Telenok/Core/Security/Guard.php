<?php

namespace Telenok\Core\Security;

class Guard extends \Illuminate\Auth\Guard {

    public function check()
    { 
        return parent::check() && $this->user()->active;
    }
    
    /*
     * \Auth::cannot(\Telenok\Core\Model\Security\Permission->code eg: 'write', \Telenok\Core\Model\Security\Resource->code 'log')
     * \Auth::cannot(222, \News $news)
     * \Auth::cannot(\Telenok\Core\Model\Security\Permission $read, \User $user)
     * \Auth::cannot(\Telenok\Core\Model\Security\Permission $read, ['object_type.language.%'])
    */
    public function cannot($permissionCode = null, $resourceCode = null)
    {
        return !$this->can($permissionCode, $resourceCode);
    }
    
    /*
     * \Auth::can(\Telenok\Core\Model\Security\Permission->code eg: 'write', \Telenok\Core\Model\Security\Resource->code 'log')
     * \Auth::can(222, \News $news)
     * \Auth::can(\Telenok\Core\Model\Security\Permission $read, \User $user)
     * \Auth::can(\Telenok\Core\Model\Security\Permission $read, ['object_type.language.%'])
    */
    public function can($permissionCode = null, $resourceCode = null)
    {
        if ($this->check()) 
        {
            if (\Telenok\Core\Security\Acl::user()->can($permissionCode, $resourceCode))
            {
                return true;
            }
            else if (\Telenok\Core\Security\Acl::subject('user_authorized')->can($permissionCode, $resourceCode))
            {
                return true;
            }
        }
        else 
        {
            return \Telenok\Core\Security\Acl::subject('user_unauthorized')->can($permissionCode, $resourceCode);
        }

        return false;
    }
    
    public function hasRole($id = null)
    { 
        if ($this->check())
        {
            return \Telenok\Core\Security\Acl::user()->hasRole($id);
        }

        return false;
    }
}

?>