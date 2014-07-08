<?php

namespace Telenok\Core\Model\User;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \Telenok\Core\Interfaces\Eloquent\Object\Model implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'user';
	protected $hidden = ['password'];
	protected $fillable = ['remember_token'];

	public function setRememberToken($value) 
	{
		$this->{$this->getRememberTokenName()} = $value;
	}

	public function getRememberToken()
	{
		return $this->{$this->getRememberTokenName()};
	}

	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	public function setPasswordAttribute($value)
	{
		if ($value = trim($value))
		{
			$this->attributes['password'] = \Hash::make($value);
		}
	}

	public function setUsernickAttribute($value)
	{
		$this->attributes['usernick'] = trim($value) ? $value : $this->getAttribute('username');
	}
	
	function permission()
	{
		static $list;

		if ($list == null)
		{
			foreach ($this->group() as $group)
			{
				$list = array_merge_recursive($list, $group->permission());
			}
		}

		return $list;
	}

	public function createdBy()
	{
		return $this->hasMany('\Telenok\Object\Sequence', 'created_by_user');
	}

	public function updatedBy()
	{
		return $this->hasMany('\Telenok\Object\Sequence', 'updated_by_user');
	}

	public function authorUserMessage()
	{
		return $this->belongsTo('\Telenok\User\UserMessage', 'author_user_message');
	}

	public function recepientUserMessage()
	{
		return $this->belongsToMany('\Telenok\User\UserMessage', 'pivot_relation_m2m_recepient_user_message', 'recepient', 'recepient_user_message')->withTimestamps();
	}

	public function group()
	{
		return $this->belongsToMany('\Telenok\User\Group', 'pivot_relation_m2m_group_user', 'group_user', 'group')->withTimestamps();
	}


    public function groupGroup()
    {
        return $this->belongsToMany('\Telenok\User\Group', 'pivot_relation_m2m_group_group', 'group', 'group_group')->withTimestamps();
    }


    public function companyEmployeeCompany()
    {
        return $this->belongsTo('\Company', 'company_employee_company');
    }


    public function companyManager()
    {
        return $this->hasMany('\Company', 'company_manager_user');
    }


    public function companyManagerAssistant()
    {
        return $this->hasMany('\Company', 'company_manager_assistant_user');
    }

}
?>