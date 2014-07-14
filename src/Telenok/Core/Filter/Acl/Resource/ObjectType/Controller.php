<?php

namespace Telenok\Core\Filter\Acl\Resource\ObjectType;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'object-type'; 

    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$resourceType = new \Telenok\Security\Resource();
		$now = \Carbon\Carbon::now();
		
		$queryCommon->leftJoin($resourceType->getTable() . ' as resource_type_permission_user_filter_object_type', function($join) use ($now, $resourceType)
		{
			$join->on(\DB::raw('CONCAT("object.", otype.code)'), '=', 'resource_type_permission_user_filter_object_type.code');
			$join->on('resource_type_permission_user_filter_object_type.' . $resourceType->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('resource_type_permission_user_filter_object_type.active', '=', 1);
			$join->where('resource_type_permission_user_filter_object_type.start_at', '<=', $now);
			$join->where('resource_type_permission_user_filter_object_type.end_at', '>=', $now);
		}); 
		
		// verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$role = new \Telenok\Security\Role();
			$group = new \Telenok\User\Group();
			$sequence = new \Telenok\Object\Sequence();
			$spr = new \Telenok\Security\SubjectPermissionResource();
 
			$queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_object_type', function($join) use ($spr, $permission, $now)
			{
				$join->on('resource_type_permission_user_filter_object_type.id', '=', 'spr_permission_user_filter_object_type.acl_resource_object_sequence');
				$join->where('spr_permission_user_filter_object_type.acl_permission_object_sequence', '=', $permission->getKey());
				$join->on('spr_permission_user_filter_object_type.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_permission_user_filter_object_type.active', '=', 1);
				$join->where('spr_permission_user_filter_object_type.start_at', '<=', $now);
				$join->where('spr_permission_user_filter_object_type.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_object_type', function($join) use ($role, $now)
			{
				$join->on('spr_permission_user_filter_object_type.acl_subject_object_sequence', '=', 'role_permission_user_filter_object_type.id');
				$join->on('role_permission_user_filter_object_type.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('role_permission_user_filter_object_type.active', '=', 1);
				$join->where('role_permission_user_filter_object_type.start_at', '<=', $now);
				$join->where('role_permission_user_filter_object_type.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_object_type', function($join)
			{
				$join->on('role_permission_user_filter_object_type.id', '=', 'pivot_relation_m2m_role_group_filter_object_type.role');
			}); 

			$queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_object_type', function($join) use ($group, $now)
			{
				$join->on('pivot_relation_m2m_role_group_filter_object_type.role_group', '=', 'group_permission_user_filter_object_type.id');
				$join->on('group_permission_user_filter_object_type.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('group_permission_user_filter_object_type.active', '=', 1);
				$join->where('group_permission_user_filter_object_type.start_at', '<=', $now);
				$join->where('group_permission_user_filter_object_type.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_object_type', function($join)
			{
				$join->on('group_permission_user_filter_object_type.id', '=', 'pivot_relation_m2m_group_user_filter_object_type.group');
			}); 

			$queryCommon->leftJoin($subject->getTable() . ' as user_filter_object_type_permission_user', function($join) use ($subject, $now)
			{
				$join->on('pivot_relation_m2m_group_user_filter_object_type.group_user', '=', 'user_permission_user_filter_object_type.id');
				$join->on('user_permission_user_filter_object_type.' . $subject->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('user_permission_user_filter_object_type.active', '=', 1);
				$join->where('user_permission_user_filter_object_type.start_at', '<=', $now);
				$join->where('user_permission_user_filter_object_type.end_at', '>=', $now);
			}); 
			  
            $queryWhere->OrWhereNotNull('user_permission_user_filter_object_type.id');
		}
		else
		{
			// verify direct right of subject via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
			$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_object_type_direct', function($join) use ($spr, $subject, $permission, $now)
			{
				$join->on('resource_type_permission_user_filter_object_type.id', '=', 'spr_filter_object_type_direct.acl_resource_object_sequence');
				$join->where('spr_filter_object_type_direct.acl_permission_object_sequence', '=', $permission->getKey());
				$join->where('spr_filter_object_type_direct.acl_subject_object_sequence', '=', $subject->getKey());
				$join->on('spr_filter_object_type_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_filter_object_type_direct.active', '=', 1);
				$join->where('spr_filter_object_type_direct.start_at', '<=', $now);
				$join->where('spr_filter_object_type_direct.end_at', '>=', $now);
			});

            $queryWhere->OrWhereNotNull('spr_filter_object_type_direct.id');
		}
	}

    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
        $spr = new \Telenok\Security\SubjectPermissionResource();
        $aclResource = new \Telenok\Security\Resource();
		$now = \Carbon\Carbon::now();

        $queryCommon->leftJoin($aclResource->getTable() . ' as acl_resource_filter_object_type_direct', function($join) use ($aclResource, $now)
        {
            $join->on(\DB::raw('concat("object_type.", otype.code)'), '=', 'acl_resource_filter_object_type_direct.code');
            $join->on('acl_resource_filter_object_type_direct.' . $aclResource->getDeletedAtColumn(), ' is ', \DB::raw('null'));
            $join->where('acl_resource_filter_object_type_direct.active', '=', 1);
			$join->where('acl_resource_filter_object_type_direct.start_at', '<=', $now);
			$join->where('acl_resource_filter_object_type_direct.end_at', '>=', $now);
        });

        //for direct right on \Telenok\Security\Resource
        $queryCommon->leftJoin($spr->getTable() . ' as spr_resource_filter_object_type_direct', function($join) use ($spr, $subject, $permission, $now)
        {
            $join->on('acl_resource_filter_object_type_direct.id', '=', 'spr_resource_filter_object_type_direct.acl_resource_object_sequence'); 
            $join->where('spr_resource_filter_object_type_direct.acl_subject_object_sequence', '=', \DB::raw($subject->getKey()));
            $join->where('spr_resource_filter_object_type_direct.acl_permission_object_sequence', '=', \DB::raw($permission->getKey()));
            $join->on('spr_resource_filter_object_type_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw('null'));
            $join->where('spr_resource_filter_object_type_direct.active', '=', 1);
			$join->where('spr_resource_filter_object_type_direct.start_at', '<=', $now);
			$join->where('spr_resource_filter_object_type_direct.end_at', '>=', $now);
        });

        $queryWhere->OrWhereNotNull('spr_resource_filter_object_type_direct.id');

        // for logined user's right on resource
        if ($subject instanceof \Telenok\Core\Model\User\User)
        {
            $userGroupRole = \Telenok\User\User::with(
			[
                'group' => function($query) use ($now)
				{
					$query->where('group.active', 1)
						->where('group.start_at', '<=', $now)
						->where('group.end_at', '>=', $now);
				}, 
                'group.role' => function($query) use ($now)
				{
					$query->where('role.active', 1)
					->where('role.start_at', '<=', $now)
					->where('role.end_at', '>=', $now);
				}
			])
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

            $queryCommon->leftJoin($spr->getTable() . ' as spr_resource_filter_object_type_user', function($join) use ($spr, $roles, $permission, $now)
            {
                $join->on('acl_resource_filter_object_type_direct.id', '=', 'spr_resource_filter_object_type_user.acl_resource_object_sequence'); 
                $join->on('spr_resource_filter_object_type_user.acl_subject_object_sequence', ' in ', \DB::raw('(' . implode(',', $roles) . ')')); 
                $join->where('spr_resource_filter_object_type_user.acl_permission_object_sequence', '=', \DB::raw($permission->getKey()));
                $join->on('spr_resource_filter_object_type_user.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw('null'));
                $join->where('spr_resource_filter_object_type_user.active', '=', 1);
				$join->where('spr_resource_filter_object_type_user.start_at', '<=', $now);
				$join->where('spr_resource_filter_object_type_user.end_at', '>=', $now);
            });

            $queryWhere->OrWhereNotNull('spr_resource_filter_object_type_user.id');
        }
    }
}

?>