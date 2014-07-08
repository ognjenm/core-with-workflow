<?php

namespace Telenok\Core\Filter\Acl\Resource\ObjectType;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'object-type'; 

    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
        $spr = new \Telenok\Security\SubjectPermissionResource();
        $aclResource = new \Telenok\Security\Resource();
        
        $queryCommon->leftJoin($aclResource->getTable() . ' as acl_resource_filter_object_type_direct', function($join) use ($aclResource)
        {
            $join->on(\DB::raw('concat("object_type.", otype.code)'), '=', 'acl_resource_filter_object_type_direct.code');
            $join->on('acl_resource_filter_object_type_direct.' . $aclResource->getDeletedAtColumn(), ' is ', \DB::raw('null'));
            $join->on('acl_resource_filter_object_type_direct.active', '=', \DB::raw('1'));
        });
        
        //for direct right on \Telenok\Security\Resource
        $queryCommon->leftJoin($spr->getTable() . ' as spr_resource_filter_object_type_direct', function($join) use ($spr, $subject, $permission)
        {
            $join->on('acl_resource_filter_object_type_direct.id', '=', 'spr_resource_filter_object_type_direct.acl_resource_object_sequence'); 
            $join->on('spr_resource_filter_object_type_direct.acl_subject_object_sequence', '=', \DB::raw($subject->getKey()));
            $join->on('spr_resource_filter_object_type_direct.acl_permission_object_sequence', '=', \DB::raw($permission->getKey()));
            $join->on('spr_resource_filter_object_type_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw('null'));
            $join->on('spr_resource_filter_object_type_direct.active', '=', \DB::raw('1'));
        });
        
        $queryWhere->OrWhereNotNull('spr_resource_filter_object_type_direct.id');
        
        // for logined user's right on resource
        if ($subject instanceof \Telenok\Core\Model\User\User)
        {
            $userGroupRole = \Telenok\User\User::with([
                'group' => function($query) { $query->whereActive(1); }, 
                'group.role' => function($query) { $query->whereActive(1); }])
            ->whereId($subject->getKey())
            ->active()
            ->get();
                
            $roles = [0];
                
            $userGroupRole->each(function($user) use (&$roles){
                $user->group->each(function($group) use (&$roles) {
                    $group->role->each(function($role) use (&$roles) {
                        $roles[] = $role->getKey();
                    });
                });
            });
            
            $queryCommon->leftJoin($spr->getTable() . ' as spr_resource_filter_object_type_user', function($join) use ($spr, $roles, $permission)
            {
                $join->on('acl_resource_filter_object_type_direct.id', '=', 'spr_resource_filter_object_type_user.acl_resource_object_sequence'); 
                $join->on('spr_resource_filter_object_type_user.acl_subject_object_sequence', ' in ', \DB::raw('(' . implode(',', $roles) . ')')); 
                $join->on('spr_resource_filter_object_type_user.acl_permission_object_sequence', '=', \DB::raw($permission->getKey()));
                $join->on('spr_resource_filter_object_type_user.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw('null'));
                $join->on('spr_resource_filter_object_type_user.active', '=', \DB::raw('1'));
            });

            $queryWhere->OrWhereNotNull('spr_resource_filter_object_type_user.id');
        }
    }
}

?>