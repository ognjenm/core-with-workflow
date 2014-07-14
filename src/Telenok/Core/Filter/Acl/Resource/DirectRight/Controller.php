<?php

namespace Telenok\Core\Filter\Acl\Resource\DirectRight;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'direct-right'; 

    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$now = \Carbon\Carbon::now();
		$sequence = new \Telenok\Object\Sequence();
		$spr = new \Telenok\Security\SubjectPermissionResource();
		
		// verify direct right of subject via SubjectPermissionResource on resource
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_direct_right', function($join) use ($spr, $sequence, $subject, $permission, $now)
		{
			$join->on($sequence->getTable() . '.id', '=', 'spr_filter_direct_right.acl_resource_object_sequence');
			$join->where('spr_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where('spr_filter_direct_right.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_direct_right.active', '=', 1);
			$join->where('spr_filter_direct_right.start_at', '<=', $now);
			$join->where('spr_filter_direct_right.end_at', '>=', $now);
		}); 

		$queryWhere->OrWhereNotNull('spr_filter_direct_right.id');
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