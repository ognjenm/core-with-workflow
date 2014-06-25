<?php

namespace Telenok\Core\Support\Install;

class Controller {
    
	protected $domain = '';
	protected $domainSecure = false;
	protected $superuserLogin = '';
	protected $superuserPassword = '';
	protected $locale = '';
	protected $dbDriver = '';
	protected $dbHost = '';
	protected $dbUsername = '';
	protected $dbPassword = '';
	protected $dbDatabase = '';
	protected $dbPrefix = '';

	public function setDomain($param = '')
	{
		if ($this->validateDomainOrIp($param))
		{
			$this->domain = $param;
		}
		else
		{
			throw new \Exception('Wrong domain or domain doesnt link to IP or wrong IP, may be server not running ?');	
		}

		return $this;
	}
	
	public function setSuperuserLogin($param = '')
	{
		if (preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $param))
		{
			$this->superuserLogin = $param;
		}
		else
		{
			throw new \Exception('Wrong superuser login.');	
		}		
		
		return $this;
	}
	
	public function setSuperuserPassword($param = '')
	{
		if (mb_strlen($param) > 7)
		{
			$this->superuserPassword = $param;
		}
		else
		{
			throw new \Exception('Wrong superuser password, it should be at least 8 symbols.');	
		}		
		
		return $this;
	}
	
	public function setLocale($param = '')
	{
		if (preg_match('/^[a-z]{2}$/', $param))
		{
			$this->locale = $param;
		}
		else
		{
			throw new \Exception('Wrong locale. It should contain two symbols like "en" or "ru"');	
		}		
		
		return $this;
	} 
	
	public function setDbDatabase($param = '')
	{
		if (preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $param))
		{
			$this->dbDatabase = $param;
		}
		else
		{
			throw new \Exception('Wrong database name.');	
		}		
		
		return $this;
	}
	
	public function setDbDriver($param = '')
	{
		if (in_array($param, ['sqlite', 'mysql', 'pgsql', 'sqlsrv']))
		{
			$this->dbDriver = $param;
		}
		else
		{
			throw new \Exception('Wrong database driver. Choose one of sqlite, mysql, pgsql, sqlsrv.');	
		}		
		
		return $this;
	}
	
	public function setDbHost($param = '')
	{
		if ($this->validateDomainOrIp($param))
		{
			$this->dbHost = $param;
		}
		else if ($this->dbDriver != 'sqlite') 
		{
			throw new \Exception('Wrong domain or domain doesnt link to IP or wrong IP, may be server not running ?');	
		}		
		
		return $this;
	}
	
	public function setDbUsername($param = '')
	{
		if (mb_strlen($param))
		{
			$this->dbUsername = $param;
		}
		else if ($this->dbDriver != 'sqlite') 
		{
			throw new \Exception('Wrong database username.');	
		}		
		
		return $this;
	}
	
	public function setDbPassword($param = '')
	{
		if (mb_strlen($param))
		{
			$this->dbPassword = $param;
		}
		else if ($this->dbDriver != 'sqlite') 
		{
			throw new \Exception('Wrong database password.');	
		}		
		
		return $this;
	}
	
	public function setDbPrefix($param = '')
	{
		if (mb_strlen($param) && preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $param))
		{
			$this->dbPrefix = $param;
		}
		else if (mb_strlen($param)) 
		{
			throw new \Exception('Wrong database prefix.');	
		}		
		
		return $this;
	}
	
	public function setDomainSecure($param = '')
	{
		$this->domainSecure = $param === true || $param == 'yes' || $param == 'y' ? true : false;
		
		return $this;
	}

	public function validateDomainOrIp($param)
	{
		return (filter_var($param, FILTER_VALIDATE_IP) || gethostbyname(idn_to_ascii($param)));
	}

	public function processConfigFile()
    {
		$reflector = new \ReflectionClass('\Telenok\Core\CoreServiceProvider');
		$file = $reflector->getFileName();

		$content = \File::get($file);

		$pattern = '/(DONOTDELETETHISCOMMENT\s*)(return;)/i';
		$replacement = '${1}return;';

		$res = preg_replace($pattern, $replacement, $content);

		\File::put($file, $res);

		$param = array(
			'domain' => $this->domain,
			'domainSecure' => $this->domainSecure ? 's' : '',
			'locale' => $this->locale,
			'random' => str_random(),
			'dbDriver' => $this->dbDriver,
			'dbDatabase' => $this->dbDatabase,
			'dbHost' => $this->dbHost,
			'dbUsername' => $this->dbUsername,
			'dbPassword' => $this->dbPassword,
			'dbPrefix' => $this->dbPrefix,
		);

		$stub = \File::get(__DIR__.'/stubs/database.stub');

		foreach($param as $k => $v)
		{
			$stub = str_replace('{{'.$k.'}}', $v, $stub);
		}

		\File::put(app_path() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php', $stub);

		try
		{
			if (\Schema::hasTable('deletemeplease'))
			{
				\Schema::drop('deletemeplease');
			}

			\Schema::create('deletemeplease', function($table)
			{
				$table->increments('id');
			});

			\Schema::drop('deletemeplease');
		}
		catch (\Exception $e)
		{
			$error['db_username'] = 1;

			throw new \Exception();
		}

		\File::put(app_path() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'database.php', $stub);

		$stub = \File::get(__DIR__.'/stubs/app.stub');

		foreach($param as $k => $v)
		{
			$stub = str_replace('{{'.$k.'}}', $v, $stub);
		}

		\File::put(app_path() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php', $stub);

		$contentNew = \File::get($file);

		$resNew = preg_replace($pattern, $replacement, $contentNew);

		\File::put($file, $resNew);
    } 
}
