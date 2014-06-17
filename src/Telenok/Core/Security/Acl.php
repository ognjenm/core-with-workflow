<?php

namespace Telenok\Core\Security;

class Acl
{
    protected $subject;

    public function __construct(\Illuminate\Database\Eloquent\Model $subject = null)
    {
        $this->subject = $subject;
    }

    /* 
     * Set resource as internal variable for manipulating
     * 
     * \Telenok\Core\Security\Acl::resource(200)
     * \Telenok\Core\Security\Acl::resource('control_panel')
     * \Telenok\Core\Security\Acl::resource(\Telenok\Core\Model\User\User $user)
     * 
     */
    public static function resource($id = null)
    {
        if ($id instanceof \Illuminate\Database\Eloquent\Model)
        {
            $resource = $id;
        }
        else if (is_scalar($id))
        {
            $resource = \Telenok\Core\Model\Security\Resource::where('id', (int)$id)->orWhere('code', $id)->active()->first();
        }

        return new static($resource);
    }

    /* 
     * Set subject as internal variable for manipulating
     * 
     * \Telenok\Core\Security\Acl::subject(200)
     * \Telenok\Core\Security\Acl::subject('user_unauthorized')
     * \Telenok\Core\Security\Acl::subject(\Telenok\Core\Model\User\User $user)
     * 
     */
    public static function subject($id = null)
    {
        if ($id instanceof \Illuminate\Database\Eloquent\Model)
        {
            $subject = $id;
        }
        else if (intval($id))
        {
            $subject = \Telenok\Core\Model\Object\Sequence::findOrFail($id);
        }
        else if (is_scalar($id))
        {
            $subject = \Telenok\Core\Model\Security\Resource::where('code', $id)->active()->first();
        }

        return new static($subject);
    }

    /* 
     * Set user as internal variable for manipulating
     * 
     * \Telenok\Core\Security\Acl::user() - for logged user
     * \Telenok\Core\Security\Acl::user(2)
     * \Telenok\Core\Security\Acl::user(\Telenok\Core\Model\User\User $user)
     * 
     */
    public static function user($id = null)
    {
        $user = null;
        
        if ($id === null)
        {
            $user = \Auth::user();
        }
        else if ($id instanceof \Telenok\Core\Model\User\User)
        {
            $user = $id;
        }
        else if (is_scalar($id))
        {
            $user = \Telenok\Core\Model\User\User::findOrFail($id);
        }

        return new static($user);
    }

    /* 
     * Set role as internal variable for manipulating
     * 
     * \Telenok\Core\Security\Acl::role('administrator')
     * \Telenok\Core\Security\Acl::role(2)
     * \Telenok\Core\Security\Acl::role(\Telenok\Core\Model\Security\Role $role)
     * 
     */
    public static function role($id = null)
    {
	if ($id instanceof \Telenok\Core\Model\Security\Role)
        {
            $role = $id;
        }
        else if (is_scalar($id))
        {
            $role = \Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', (int)$id)->firstOrFail();
        }

        return new static($role);
    }
    
    /* 
     * Set group as internal variable for manipulating
     * 
     * \Telenok\Core\Security\Acl::group('administrator')
     * \Telenok\Core\Security\Acl::group(2)
     * \Telenok\Core\Security\Acl::group(\Telenok\Core\Model\User\Group $group)
     * 
     */
    public static function group($id = null)
    {
	if ($id instanceof \Telenok\Core\Model\User\Group)
        {
            $group = $id;
        }
        else if (is_scalar($id))
        {
            $group = \Telenok\Core\Model\User\Group::where('code', $id)->orWhere('id', (int)$id)->firstOrFail();
        }

        return new static($group);
    }

    /* 
     * Add role 
     * 
     * \Telenok\Core\Security\Acl::addRole(['en' => 'News writers'], 'news_writers')
     * \Telenok\Core\Security\Acl::addRole('News writers', 'news_writers')
     * 
     */
    public static function addRole($title = [], $code = null)
    {
        if (!$code)
        {
            throw new \Exception('Code should be set');
        }

        $role = (new \Telenok\Core\Module\Objects\Lists\Controller())->save([
            'title' => $title,
            'code' => $code,
            'active' => 1,
        ], 'role');

        return new static($role);
    }

    /* 
     * Delete role
     * 
     * \Telenok\Core\Security\Acl::deleteRole(2)
     * \Telenok\Core\Security\Acl::deleteRole(\Telenok\Core\Model\Security\Role $role)
     * 
     */
    public static function deleteRole($id = null)
    {
        $role = null;
        
        if ($id instanceof \Telenok\Core\Model\Security\Role)
        {
            $role = $id;
        }
        else if (is_scalar($id))
        {
            $role = \Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', (int)$id)->firstOrFail(); 
        }

        if ($role)
        {
            (new \Telenok\Core\Module\Objects\Lists\Controller())->delete($role->getKey());
        }

        return new static();
    }

    /* 
     * Add resource 
     * 
     * \Telenok\Core\Security\Acl::addResource(['en' => 'File'], 'file')
     * \Telenok\Core\Security\Acl::addResource('File', 'file')
     * 
     */
    public static function addResource($title = [], $code = null)
    {
        if (!$code)
        {
            throw new \Exception('Code should be set');
        }
        
        (new \Telenok\Core\Module\Objects\Lists\Controller())->save([
            'title' => $title,
            'code' => $code,
            'active' => 1,
        ], 'resource');

        return new static();
    }
    
    /* 
     * Delete resource
     * 
     * \Telenok\Core\Security\Acl::deleteResource(2)
     * \Telenok\Core\Security\Acl::deleteResource(\Telenok\Core\Model\Security\Resource $resource)
     * 
     */
    public static function deleteResource($id = null)
    {
        $resource = null;
        
        if ($id instanceof \Telenok\Core\Model\Security\Resource)
        {
            $resource = $id;
        }
        else if (is_scalar($id))
        {
            $resource = \Telenok\Core\Model\Security\Resource::where('code', $id)->orWhere('id', (int)$id)->firstOrFail(); 
        }

        if ($resource)
        {
            (new \Telenok\Core\Module\Objects\Lists\Controller())->delete($resource->getKey());
        }
        
        return new static();
    }

    /* 
     * Add permission 
     * 
     * \Telenok\Core\Security\Acl::addPermission(['en' => 'Search'], 'search')
     * \Telenok\Core\Security\Acl::addPermission('Search', 'search')
     * 
     */
    public static function addPermission($title = [], $code = null)
    {
        if (!$code)
        {
            throw new \Exception('Code should be set');
        }
        
        (new \Telenok\Core\Module\Objects\Lists\Controller())->save([
            'title' => $title,
            'code' => $code,
            'active' => 1,
        ], 'permission');

        return new static();
    } 

    /* 
     * Delete permission
     * 
     * \Telenok\Core\Security\Acl::deletePermission()
     * \Telenok\Core\Security\Acl::deletePermission(2)
     * \Telenok\Core\Security\Acl::deletePermission(\Telenok\Core\Model\Security\Permission $permission)
     * 
     */
    public static function deletePermission($id = null)
    {
        $permission = null;
        
        if ($id instanceof \Telenok\Core\Model\Security\Permission)
        {
            $permission = $id;
        }
        else if (is_scalar($id))
        {
            $permission = \Telenok\Core\Model\Security\Permission::where('code', $id)->orWhere('id', (int)$id)->firstOrFail(); 
        }

        if ($permission)
        {
            (new \Telenok\Core\Module\Objects\Lists\Controller())->delete($permission->getKey());
        }
        
        return new static();
    }

    /* 
     * Set permission to subject
     * 
     * \Telenok\Core\Security\Acl::role/subject/user(who)->setPermission(what.can, over.resource)
     * 
     * \Telenok\Core\Security\Acl::role(316)->setPermission('read', 'control_panel')
     * \Telenok\Core\Security\Acl::user(339)->setPermission('read', 'news')
     * \Telenok\Core\Security\Acl::role(800)->setPermission(233, 1901)
     * \Telenok\Core\Security\Acl::subject(\Process $process)->setPermission(\Telenok\Core\Model\Security\Permission $permission, \Telenok\Core\Model\Security\Resource $resource)
     * 
     */
    public function setPermission($permissionCode = null, $resourceCode = null)
    {
        if (!$this->subject)
        {
            return $this;
        }
        
        if ($permissionCode instanceof \Telenok\Core\Model\Security\Permission)
        {
            $permission = $permissionCode;
        }
        else if (is_scalar($permissionCode))
        {
            $permission = \Telenok\Core\Model\Security\Permission::where('code', $permissionCode)->orWhere('id', (int)$permissionCode)->first();
        }

        if ($resourceCode instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model)
        {
            $resource = $resourceCode;
        }
        else if (is_scalar($resourceCode))
        {
            $resource = \Telenok\Core\Model\Security\Resource::where('code', $resourceCode)->orWhere('id', (int)$resourceCode)->first();
        }
        
        if (!$permission) 
        {
            throw new \Exception('Can\'t find permission');
        }
        
        if (!$resource) 
        {
            throw new \Exception('Can\'t find resource');
        }

        \DB::transaction(function() use ($permission, $resource)
        {
            try
            {
                $opr = \Telenok\Core\Model\Security\SubjectPermissionResource::where('acl_permission_permission', $permission->getKey())
                        ->where('acl_subject_object_sequence', $this->subject->getKey())
                        ->where('acl_resource_object_sequence', $resource->getKey())
                        ->active()
                        ->firstOrFail();
            }
            catch (\Exception $e)
            {
                if ($this->subject instanceof \Telenok\Core\Module\Objects\Sequence)
                {
                    $titleTypeSubject = $this->subject->sequencesObjectType()->translate('title');
                }
                else
                {
                    $titleTypeSubject = $this->subject->type()->translate('title');
                }
                
                if ($resource instanceof \Telenok\Core\Module\Objects\Sequence)
                {
                    $titleTypeResource = $this->subject->sequencesObjectType()->translate('title');
                }
                else
                {
                    $titleTypeResource = $this->subject->type()->translate('title');
                }

                $opr = (new \Telenok\Core\Module\Objects\Lists\Controller())->save([
                    'title' => '[' . $titleTypeSubject . '] ' . $this->subject->translate('title') . ' - ' . $permission->translate('title') . ' - ' . '[' . $titleTypeResource . '] ' . $resource->translate('title'),
                    'code' => $permission->code . '_' . $resource->code . '_' . $resource->getKey() . '_subject_' . $this->subject->getKey(),
                    'active' => 1,
                ], 'subject_permission_resource');

                $permission->aclPermission()->save($opr);
                
                if ($resource instanceof \Telenok\Core\Model\Object\Sequence)
                {
                    $resource->aclResource()->save($opr);
                }
                else
                {
                    $resource->sequence->aclResource()->save($opr);
                }
                
                if ($this->subject instanceof \Telenok\Core\Model\Object\Sequence)
                {
                    $this->subject->aclSubject()->save($opr);
                }
                else
                {
                    $this->subject->sequence->aclSubject()->save($opr);
                }
            }
        });

        return $this;
    }

    /* 
     * Remove permission from resource 
     * 
     * \Telenok\Core\Security\Acl::resource(120)->unsetPermission('read')
     * \Telenok\Core\Security\Acl::role(120)->unsetPermission(null, \User $subject)
     * \Telenok\Core\Security\Acl::user($admin)->unsetPermission(\Telenok\Core\Model\Security\Permission $permission, \News $resource)
     * 
     */
    public function unsetPermission($permissionCode = null, $subjectId = null)
    {
        if (!$this->subject) 
        {
            return $this;
        }
        
        $permission = null;
        $subject = null;

        $resource = $this->subject;

        if ($permissionCode instanceof \Telenok\Core\Model\Security\Permission)
        {
            $permission = $permissionCode;
        }
        else if (is_scalar($permissionCode))
        {
            $permission = \Telenok\Core\Model\Security\Permission::where('code', $permissionCode)->orWhere('id', (int)$permissionCode)->first();
        }

        if ($subjectId instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model)
        {
            $subject = $subjectId;
        }
        else if (is_scalar($subjectId))
        {
            $subject = \Telenok\Core\Model\Security\Resource::where('code', $subjectId)->orWhere('id', (int)$subjectId)->first();
        }
        
        $query = \Telenok\Core\Model\Security\SubjectPermissionResource::where('acl_resource_object_sequence', $resource->getKey()); 

        if ($permission)
        {
            $query->where('acl_permission_permission', $permission->getKey()); 
        }

        if ($subject)
        {
            $query->where('acl_subject_object_sequence', $subject->getKey());
        }

        $ids =  $query->lists('id');
        
        $controller = new \Telenok\Core\Module\Objects\Lists\Controller();

        foreach($ids as $id)
        {
            $controller->delete($id, true);
        }
        
        return $this;
    }
    
    /* 
     * Add group to user
     * 
     * \Telenok\Core\Security\Acl::user(2)->setGroup('administrator')
     * \Telenok\Core\Security\Acl::user($user)->setGroup(2)
     * \Telenok\Core\Security\Acl::user($user)->setGroup(\Telenok\Core\Model\User\Group $group)
     * 
     */
    public function setGroup($id = null)
    {
        if (!$this->subject instanceof \Telenok\Core\Model\User\User)
        {
            throw new \Exception('Subject should be instance of \Telenok\Core\Model\User\User');
        }
        
        if ($id instanceof \Telenok\Core\Model\User\Group)
        {
            $group = $id;
        }
        else if (is_scalar($id))
        {
            try
            {
                $group = \Telenok\Core\Model\User\Group::where('code', $id)->orWhere('id', (int)$id)->firstOrFail();
            }
            catch (\Exception $e)
            {
                throw new \Exception('Can\'t find group via code "' . $id . '"');
            }
        }

        $this->subject->group()->save($group);

        return $this;
    }

    /* 
     * Remove group from user
     * 
     * \Telenok\Core\Security\Acl::user(2)->unsetGroup(2)
     * \Telenok\Core\Security\Acl::user(2)->unsetGroup('super_administrator')
     * \Telenok\Core\Security\Acl::user(2)->unsetGroup(\Telenok\Core\Model\User\Group $group)
     * 
     */
    public function unsetGroup($id = null)
    {
        if (!$this->subject instanceof \Telenok\Core\Model\User\User)
        {
            throw new \Exception('Subject should be instance of \Telenok\Core\Model\User\User');
        }

        if ($id instanceof \Telenok\Core\Model\User\Group)
        {
            $group = $id;
        }
        else if (is_scalar($id))
        {
            try
            {
                $group = \Telenok\Core\Model\User\Group::where('code', $id)->orWhere('id', (int)$id)->firstOrFail();
            }
            catch (\Exception $e)
            {
                throw new \Exception('Can\'t find group via code "' . $id . '"');
            }
        }

        $this->subject->group()->detach($group); 

        return $this;
    }
    
    /* 
     * Add role to group
     * 
     * \Telenok\Core\Security\Acl::group($admin)->setRole('super_administrator')
     * \Telenok\Core\Security\Acl::group($admin)->setRole(2)
     * \Telenok\Core\Security\Acl::group($admin)->setRole(\Telenok\Core\Model\Security\Role $role)
     * 
     */
    public function setRole($id = null)
    {
        if (!$this->subject instanceof \Telenok\Core\Model\User\Group)
        {
            throw new \Exception('Subject should be instance of \Telenok\Core\Model\User\Group');
        }

        if ($id instanceof \Telenok\Core\Model\Security\Role)
        {
            $role = $id;
        }
        else if (is_scalar($id))
        {
            try
            {
                $role = \Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', (int)$id)->firstOrFail();
            }
            catch (\Exception $e)
            {
                throw new \Exception('Can\'t find role via code "' . $id . '"');
            }
        }

        $this->subject->role()->save($role);

        return $this;
    }

    /* 
     * Remove role from group
     * 
     * \Telenok\Core\Security\Acl::group($admin)->unsetRole() - unset all roles from group
     * \Telenok\Core\Security\Acl::group($admin)->unsetRole(2)
     * \Telenok\Core\Security\Acl::group($admin)->unsetRole('super_administrator')
     * 
     */
    public function unsetRole($id = null)
    {
        if (!$this->subject instanceof \Telenok\Core\Model\User\Group)
        {
            throw new \Exception('Subject should be instance of \Telenok\Core\Model\User\Group');
        }

        if ($id === null)
        {
            $this->subject->role()->detach();
        }
        else if (is_scalar($id))
        {
            try
            {
                $role = \Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', (int)$id)->firstOrFail();
            }
            catch (\Exception $e)
            {
                throw new \Exception('Can\'t find role via code "' . $id . '"');
            }

            $this->subject->role()->detach($role);
        }

        return $this;
    }

    /* 
     * Validate subject's permission
     * 
     * \Telenok\Core\Security\Acl::group($admin)->can(\Telenok\Core\Model\Security\Permission->code eg: 'write', \Telenok\Core\Model\Security\Resource->code 'log')
     * \Telenok\Core\Security\Acl::user(103)->can(222, \News $news)
     * \Telenok\Core\Security\Acl::subject(103)->can(\Telenok\Core\Model\Security\Permission $read, \User $user)
     * \Telenok\Core\Security\Acl::subject(103)->can(\Telenok\Core\Model\Security\Permission $read, ['object_type.language'])
     * \Telenok\Core\Security\Acl::subject(103)->can(\Telenok\Core\Model\Security\Permission $read, ['object_type.language.%'])
     * 
     */
    public function can($permissionCode = null, $resourceCode = null)
    {
        if (!\Config::get('app.acl.enabled')) 
        {
            return true;
        }

        $permission = [];
        $resource = [];

        if (!$this->subject || !$this->subject->active) 
        {
            return false;
        }
        else if ($this->subject instanceof \Telenok\Core\Model\User\User && $this->subject->active && $this->hasRole('super_administrator'))
        {
            return true;
        }

        if ($resourceCode instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model && $resourceCode->active)
        {
            $resource = $resourceCode;
        }
        else if (intval($resourceCode))
        {
            $resource = \Telenok\Core\Model\Object\Sequence::where('id', $resourceCode)->active()->first();
        }
        else if (is_scalar($resourceCode))
        {
            $resource = \Telenok\Core\Model\Security\Resource::where(function($query) use ($resourceCode) 
            {
                if (str_contains($resourceCode, '%'))
                {
                    $query->where('code', 'like', $resourceCode);
                }
                else
                {
                    $query->where('code', $resourceCode);
                }
                
            })->orWhere('id', (int)$resourceCode)->active()->get();
        }
        
        if ($permissionCode instanceof \Telenok\Core\Model\Security\Permission && $permissionCode->active)
        {
            $permission = $permissionCode;
        }
        else if (is_scalar($permissionCode))
        {
            $permission = \Telenok\Core\Model\Security\Permission::where('code', $permissionCode)->orWhere('id', $permissionCode)->active()->first();
        }
        
        $permission = $permission instanceof \Illuminate\Database\Eloquent\Collection ? $permission : \Illuminate\Database\Eloquent\Collection::make($permission);
        $resource = $resource instanceof \Illuminate\Database\Eloquent\Collection ? $resource : \Illuminate\Database\Eloquent\Collection::make($resource);

        if (!$permission->count() || !$resource->count())
        {
            return false;
        }  
        
        if ($this->subject instanceof \Telenok\Core\Model\User\User)
        {
            $group = new \Telenok\Core\Model\User\Group();
            $role = new \Telenok\Core\Model\Security\Role();
            $user = new \Telenok\Core\Model\User\User();

            $query = $this->subject->select('role.id')->join('pivot_relation_m2m_group_user', function($join)
            {
                $join->on($this->subject->getTable() . '.id', '=', 'pivot_relation_m2m_group_user.group_user');
                $join->on($this->subject->getTable() . '.id', '=', \DB::raw($this->subject->getKey()));
            });

            $query->join($group->getTable() . ' as group', function($join) use ($group)
            {
                $join->on('pivot_relation_m2m_group_user.group', '=', 'group.id');
                $join->on('group.active', '=', \DB::raw('1'));
                $join->on('group.' . $group->getDeletedAtColumn(), ' is ', \DB::raw('null'));
            });

            $query->join('pivot_relation_m2m_role_group', function($join) 
            {
                $join->on('group.id', '=', 'pivot_relation_m2m_role_group.role_group');
            });

            $query->join($role->getTable() . ' as role', function($join) use ($role)
            {
                $join->on('pivot_relation_m2m_role_group.role', '=', 'role.id');
                $join->on('role.active', '=', \DB::raw('1'));
                $join->on('role.' . $role->getDeletedAtColumn(), ' is ', \DB::raw('null'));
            });

            $roles = $query->get();
            
            if ($roles->count())
            {
                $sprs = \Telenok\Core\Model\Security\SubjectPermissionResource::whereIn('acl_subject_object_sequence', $roles->modelKeys())
                        ->whereIn('acl_permission_permission', $permission->modelKeys())
                        ->whereIn('acl_resource_object_sequence', $resource->modelKeys())
                        ->active()->take(1)->get();
                
                if ($sprs->count())
                {
                    return true;
                }
            } 
        }

        // for direct right on resource (we can set rigth for anything on anything)
        $result = \Telenok\Core\Model\Security\SubjectPermissionResource::where('acl_subject_object_sequence', $this->subject->getKey())
                    ->whereIn('acl_resource_object_sequence', $resource->modelKeys())
                    ->whereIn('acl_permission_permission', $permission->modelKeys())
                    ->active()->take(1)->get();
        
        return $result->count() > 0;
    }

    /* 
     * Validate subject's permission
     * 
     * \Telenok\Core\Security\Acl::group($admin)->cannot(\Telenok\Core\Model\Security\Permission->code eg: 'write', \Telenok\Core\Model\Security\Resource->code 'log')
     * \Telenok\Core\Security\Acl::user(103)->cannot(222, \News $news)
     * \Telenok\Core\Security\Acl::subject(103)->cannot(\Telenok\Core\Model\Security\Permission $read, \User $user)
     * \Telenok\Core\Security\Acl::subject(103)->cannot(\Telenok\Core\Model\Security\Permission $read, ['object_type.language.%'])
     * 
     */
    public function cannot($permissionCode = null, $resourceCode = null)
    {
        return !$this->can($permissionCode, $resourceCode);
    }
    
    /* 
     * Validate user's role
     * 
     * \Telenok\Core\Security\Acl::user(103)->hasRole('superadmin')
     * \Telenok\Core\Security\Acl::user($user)->hasRole(1)
     * \Telenok\Core\Security\Acl::user(103)->hasRole(\Telenok\Core\Model\Security\Role $role)
     * 
     */
    public function hasRole($id = null)
    {
        if (!\Config::get('app.acl.enabled')) 
        {
            return true;
        }

        if (!$this->subject || !$this->subject->active || !$this->subject instanceof \Telenok\Core\Model\User\User) 
        {
            return false;
        }

        if ($id instanceof \Telenok\Core\Model\Security\Role)
        {
            $role = $id;
        }
        else if (is_scalar($id))
        {
            $role = \Telenok\Core\Model\Security\Role::where('code', $id)->orWhere('id', (int)$id)->active()->first();
        }

        if (!$role)
        {
            return false;
        }

        $opr = $this->subject->with([
            'group' => function($query) { $query->where('active', 1); },
            'group.role' => function($query) use ($role) { $query->where('role.id', $role->getKey())->where('role.active', 1); }
        ])
        ->whereId($this->subject->getKey())->active()->get();
        
        foreach($opr as $user)
        { 
            foreach($user->group as $group)
            { 
                foreach($group->role as $role)
                {
                    return true;
                }
            }
        }

        return false;
    }
}

?>