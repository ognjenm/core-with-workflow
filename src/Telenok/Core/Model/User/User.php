<?php

namespace Telenok\Core\Model\User;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \Telenok\Core\Interfaces\Eloquent\Object\Model implements UserInterface, RemindableInterface {

	protected $ruleList = ['title' => ['required', 'min:1']];
	protected $table = 'user';
	protected $hidden = ['password'];
	protected $fillable = ['remember_token'];

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->getAttribute('password');
	}

	public function getReminderEmail()
	{
		return $this->getAttribute('email');
	}

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
		$this->attributes['password'] = \Hash::make($value);
	}

	public function setUsernickAttribute($value)
	{
		$this->attributes['usernick'] = trim($value) ? $value : $this->getAttribute('username');
	}
	/*
	public function getConfigurationAttribute($value)
	{
		$value = $value ? : '[]';
		
		return \Illuminate\Support\Collection::make(json_decode($value, TRUE));
	}

	public function setConfigurationAttribute($value)
	{
		if ($value instanceof \Illuminate\Support\Collection) 
		{
			$value = $value->toArray();
		}
		else
		{
			$value = $value ? : [];
		} 

		// $value can be json string, then convert it to array
		if (is_scalar($value) && ($json = json_decode($value)) !== null)
		{
			$value = $json;
		}

		$this->attributes['configuration'] = json_encode($value);
	}
	*/
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
		return $this->hasMany('\Telenok\Core\Model\Object\Sequence', 'created_by_user');
	}

	public function updatedBy()
	{
		return $this->hasMany('\Telenok\Core\Model\Object\Sequence', 'updated_by_user');
	}

	public function authorUserMessage()
	{
		return $this->belongsTo('\Telenok\Core\Model\User\UserMessage', 'author_user_message');
	}

	public function recepientUserMessage()
	{
		return $this->belongsToMany('\Telenok\Core\Model\User\UserMessage', 'pivot_relation_m2m_recepient_user_message', 'recepient', 'recepient_user_message')->withTimestamps();
	}

	public function group()
	{
		return $this->belongsToMany('\Telenok\Core\Model\User\Group', 'pivot_relation_m2m_group_user', 'group_user', 'group')->withTimestamps();
	}


    public function groupGroup()
    {
        return $this->belongsToMany('\Telenok\Core\Model\User\Group', 'pivot_relation_m2m_group_group', 'group', 'group_group')->withTimestamps();
    }

}
?>