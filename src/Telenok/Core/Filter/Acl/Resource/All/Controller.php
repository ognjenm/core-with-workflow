<?php

namespace Telenok\Core\Filter\Acl\Resource\All;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'all'; 

    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
        $spr = new \Telenok\Security\SubjectPermissionResource();
        $aclResource = new \Telenok\Security\Resource();
        
        $queryCommon->leftJoin($aclResource->getTable() . ' as acl_resource_filter_all_direct', function($join) use ($aclResource)
        {
            $join->on(\DB::raw('concat("object_type.", otype.code, ".' . $this->getKey() . '")'), '=', 'acl_resource_filter_all_direct.code');
            $join->on('acl_resource_filter_all_direct.' . $aclResource->getDeletedAtColumn(), ' is ', \DB::raw('null'));
            $join->on('acl_resource_filter_all_direct.active', '=', \DB::raw('1'));
        });
        
        //for direct right on \Telenok\Security\Resource
        $queryCommon->leftJoin($spr->getTable() . ' as spr_resource_filter_all_direct', function($join) use ($spr, $subject, $permission)
        {
            $join->on('acl_resource_filter_all_direct.id', '=', 'spr_resource_filter_all_direct.acl_resource_object_sequence'); 
            $join->on('spr_resource_filter_all_direct.acl_subject_object_sequence', '=', \DB::raw($subject->getKey()));
            $join->on('spr_resource_filter_all_direct.acl_permission_object_sequence', '=', \DB::raw($permission->getKey()));
            $join->on('spr_resource_filter_all_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw('null'));
            $join->on('spr_resource_filter_all_direct.active', '=', \DB::raw('1'));
        });
        
        $queryWhere->OrWhereNotNull('spr_resource_filter_all_direct.id');
        
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
            
            $queryCommon->leftJoin($spr->getTable() . ' as spr_resource_filter_all_user', function($join) use ($spr, $roles, $permission)
            {
                $join->on('acl_resource_filter_all_direct.id', '=', 'spr_resource_filter_all_user.acl_resource_object_sequence'); 
                $join->on('spr_resource_filter_all_user.acl_subject_object_sequence', ' in ', \DB::raw('(' . implode(',', $roles) . ')')); 
                $join->on('spr_resource_filter_all_user.acl_permission_object_sequence', '=', \DB::raw($permission->getKey()));
                $join->on('spr_resource_filter_all_user.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw('null'));
                $join->on('spr_resource_filter_all_user.active', '=', \DB::raw('1'));
            });

            $queryWhere->OrWhereNotNull('spr_resource_filter_all_user.id');
        }
    }
}

?>