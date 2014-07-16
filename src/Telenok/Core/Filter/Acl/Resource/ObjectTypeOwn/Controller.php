<?php

namespace Telenok\Core\Filter\Acl\Resource\ObjectTypeOwn;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'object-type-own';
	
    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$resourceType = new \Telenok\Security\Resource();
		$sequence = new \Telenok\Object\Sequence();
		$spr = new \Telenok\Security\SubjectPermissionResource();
		$now = \Carbon\Carbon::now();

		$queryCommon->leftJoin($resourceType->getTable() . ' as resource_type_permission_user_filter_object_type_own', function($join) use ($now, $resourceType)
		{
			$join->on(\DB::raw('CONCAT("object_type.", otype.code, ".own")'), '=', 'resource_type_permission_user_filter_object_type_own.code');
			$join->on('resource_type_permission_user_filter_object_type_own.' . $resourceType->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('resource_type_permission_user_filter_object_type_own.active', '=', 1);
			$join->where('resource_type_permission_user_filter_object_type_own.start_at', '<=', $now);
			$join->where('resource_type_permission_user_filter_object_type_own.end_at', '>=', $now);
		}); 
		
		// verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$role = new \Telenok\Security\Role();
			$group = new \Telenok\User\Group();
 
			$queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_object_type_own', function($join) use ($spr, $permission, $now)
			{
				$join->on('resource_type_permission_user_filter_object_type_own.id', '=', 'spr_permission_user_filter_object_type_own.acl_resource_object_sequence');
				$join->where('spr_permission_user_filter_object_type_own.acl_permission_object_sequence', '=', $permission->getKey());
				$join->on('spr_permission_user_filter_object_type_own.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('spr_permission_user_filter_object_type_own.start_at', '<=', $now);
				$join->where('spr_permission_user_filter_object_type_own.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_object_type_own', function($join) use ($role, $now)
			{
				$join->on('spr_permission_user_filter_object_type_own.acl_subject_object_sequence', '=', 'role_permission_user_filter_object_type_own.id');
				$join->on('role_permission_user_filter_object_type_own.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('role_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('role_permission_user_filter_object_type_own.start_at', '<=', $now);
				$join->where('role_permission_user_filter_object_type_own.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_object_type_own', function($join)
			{
				$join->on('role_permission_user_filter_object_type_own.id', '=', 'pivot_relation_m2m_role_group_filter_object_type_own.role');
			}); 

			$queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_object_type_own', function($join) use ($group, $now)
			{
				$join->on('pivot_relation_m2m_role_group_filter_object_type_own.role_group', '=', 'group_permission_user_filter_object_type_own.id');
				$join->on('group_permission_user_filter_object_type_own.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('group_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('group_permission_user_filter_object_type_own.start_at', '<=', $now);
				$join->where('group_permission_user_filter_object_type_own.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_object_type_own', function($join)
			{
				$join->on('group_permission_user_filter_object_type_own.id', '=', 'pivot_relation_m2m_group_user_filter_object_type_own.group');
			}); 

			$queryCommon->leftJoin($subject->getTable() . ' as user_permission_user_filter_object_type_own', function($join) use ($subject, $now, $sequence)
			{
				$join->on('pivot_relation_m2m_group_user_filter_object_type_own.group_user', '=', 'user_permission_user_filter_object_type_own.id');
				$join->on('user_permission_user_filter_object_type_own.' . $subject->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('user_permission_user_filter_object_type_own.id', '=', $subject->getKey());
				$join->where('user_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('user_permission_user_filter_object_type_own.start_at', '<=', $now);
				$join->where('user_permission_user_filter_object_type_own.end_at', '>=', $now);
				$join->on('user_permission_user_filter_object_type_own.id', '=', $sequence->getTable() . '.created_by_user');
			}); 

			$queryWhere->OrWhereNotNull('user_permission_user_filter_object_type_own.id');
		}
		
		// verify direct right of subject via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_object_type_own_direct', function($join) use ($spr, $subject, $permission, $now, $sequence)
		{
			$join->on('resource_type_permission_user_filter_object_type_own.id', '=', 'spr_filter_object_type_own_direct.acl_resource_object_sequence');
			$join->where('spr_filter_object_type_own_direct.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where($sequence->getTable() . '.created_by_user', '=', $subject->getKey());
			$join->where('spr_filter_object_type_own_direct.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_object_type_own_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_object_type_own_direct.active', '=', 1);
			$join->where('spr_filter_object_type_own_direct.start_at', '<=', $now);
			$join->where('spr_filter_object_type_own_direct.end_at', '>=', $now);
		});

		$queryWhere->OrWhereNotNull('spr_filter_object_type_own_direct.id');
	}

    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$resourceType = new \Telenok\Security\Resource();
		$sequence = new \Telenok\Object\Sequence();
		$spr = new \Telenok\Security\SubjectPermissionResource();
		$now = \Carbon\Carbon::now();

		$queryCommon->leftJoin($resourceType->getTable() . ' as resource_type_permission_user_filter_object_type_own', function($join) use ($now, $resourceType)
		{
			$join->on(\DB::raw('CONCAT("object_type.", otype.code, ".own")'), '=', 'resource_type_permission_user_filter_object_type_own.code');
			$join->on('resource_type_permission_user_filter_object_type_own.' . $resourceType->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('resource_type_permission_user_filter_object_type_own.active', '=', 1);
			$join->where('resource_type_permission_user_filter_object_type_own.start_at', '<=', $now);
			$join->where('resource_type_permission_user_filter_object_type_own.end_at', '>=', $now);
		}); 

		// verify user's right via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		if ($subject instanceof \Telenok\Core\Model\User\User)
		{
			$role = new \Telenok\Security\Role();
			$group = new \Telenok\User\Group();
 
			$queryCommon->leftJoin($spr->getTable() . ' as spr_permission_user_filter_object_type_own', function($join) use ($spr, $permission, $now)
			{
				$join->on('resource_type_permission_user_filter_object_type_own.id', '=', 'spr_permission_user_filter_object_type_own.acl_resource_object_sequence');
				$join->where('spr_permission_user_filter_object_type_own.acl_permission_object_sequence', '=', $permission->getKey());
				$join->on('spr_permission_user_filter_object_type_own.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('spr_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('spr_permission_user_filter_object_type_own.start_at', '<=', $now);
				$join->where('spr_permission_user_filter_object_type_own.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin($role->getTable() . ' as role_permission_user_filter_object_type_own', function($join) use ($role, $now)
			{
				$join->on('spr_permission_user_filter_object_type_own.acl_subject_object_sequence', '=', 'role_permission_user_filter_object_type_own.id');
				$join->on('role_permission_user_filter_object_type_own.' . $role->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('role_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('role_permission_user_filter_object_type_own.start_at', '<=', $now);
				$join->where('role_permission_user_filter_object_type_own.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_role_group as pivot_relation_m2m_role_group_filter_object_type_own', function($join)
			{
				$join->on('role_permission_user_filter_object_type_own.id', '=', 'pivot_relation_m2m_role_group_filter_object_type_own.role');
			}); 

			$queryCommon->leftJoin($group->getTable() . ' as group_permission_user_filter_object_type_own', function($join) use ($group, $now)
			{
				$join->on('pivot_relation_m2m_role_group_filter_object_type_own.role_group', '=', 'group_permission_user_filter_object_type_own.id');
				$join->on('group_permission_user_filter_object_type_own.' . $group->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('group_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('group_permission_user_filter_object_type_own.start_at', '<=', $now);
				$join->where('group_permission_user_filter_object_type_own.end_at', '>=', $now);
			}); 

			$queryCommon->leftJoin('pivot_relation_m2m_group_user as pivot_relation_m2m_group_user_filter_object_type_own', function($join)
			{
				$join->on('group_permission_user_filter_object_type_own.id', '=', 'pivot_relation_m2m_group_user_filter_object_type_own.group');
			}); 

			$queryCommon->leftJoin($subject->getTable() . ' as user_permission_user_filter_object_type_own', function($join) use ($subject, $now)
			{
				$join->on('pivot_relation_m2m_group_user_filter_object_type_own.group_user', '=', 'user_permission_user_filter_object_type_own.id');
				$join->on('user_permission_user_filter_object_type_own.' . $subject->getDeletedAtColumn(), ' is ', \DB::raw("null"));
				$join->where('user_permission_user_filter_object_type_own.active', '=', 1);
				$join->where('user_permission_user_filter_object_type_own.start_at', '<=', $now);
				$join->where('user_permission_user_filter_object_type_own.end_at', '>=', $now);
				$join->on('osequence.created_by_user', '=', 'user_permission_user_filter_object_type_own.id');
				$join->where('user_permission_user_filter_object_type_own.id', '=', $subject->getKey());
			}); 

			$queryWhere->OrWhereNotNull('user_permission_user_filter_object_type_own.id');
		}

		// verify direct right of subject via SubjectPermissionResource on resource with code like "object.some_object_type_code" eg "object.object_type"
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_object_type_own_direct', function($join) use ($spr, $subject, $permission, $now, $sequence)
		{
			$join->on('resource_type_permission_user_filter_object_type_own.id', '=', 'spr_filter_object_type_own_direct.acl_resource_object_sequence');
			$join->where('spr_filter_object_type_own_direct.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where('spr_filter_object_type_own_direct.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_object_type_own_direct.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_object_type_own_direct.active', '=', 1);
			$join->where('spr_filter_object_type_own_direct.start_at', '<=', $now);
			$join->where('spr_filter_object_type_own_direct.end_at', '>=', $now);
			$join->where('osequence.created_by_user', '=', $subject->getKey());
		});

		$queryWhere->OrWhereNotNull('spr_filter_object_type_own_direct.id');
    }

}

?>