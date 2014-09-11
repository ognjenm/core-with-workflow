<?php

namespace Telenok\Core\Model\User;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \Telenok\Core\Interfaces\Eloquent\Object\Model implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $ruleList = ['title' => ['required', 'min:1'], 'email' => ['unique:user,email,:id:,id'], 'usernick' => ['unique:user,usernick,:id:,id']];
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
		else if (!$this->exists && !$value)
		{
			$this->attributes['password'] = \Hash::make(str_random());
		}
	}

	public function setUsernickAttribute($value)
	{
		$this->attributes['usernick'] = trim($value) ? $value : $this->getAttribute('username');
	}

	public function createdBy()
	{
		return $this->hasMany('\Telenok\Object\Sequence', 'created_by_user');
	}

	public function updatedBy()
	{
		return $this->hasMany('\Telenok\Object\Sequence', 'updated_by_user');
	}

	public function deletedBy()
	{
		return $this->hasMany('\Telenok\Object\Sequence', 'deleted_by_user');
	}

	public function lockedBy()
	{
		return $this->hasMany('\Telenok\Object\Sequence', 'locked_by_user');
	}

	public function group()
	{
		return $this->belongsToMany('\Telenok\User\Group', 'pivot_relation_m2m_group_user', 'group_user', 'group')->withTimestamps();
	}


}
?>