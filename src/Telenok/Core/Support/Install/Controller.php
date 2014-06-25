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
		if (gethostbyname(idn_to_ascii($param)))
		{
			$this->domain = $param;
		}
		else
		{
			throw new \Exception('Wrong domain name');	
		}
	}
	
	public function setSuperuserLogin($param = '')
	{
		if (preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $param))
		{
			$this->superuserLogin = $param;
		}
		else
		{
			throw new \Exception('Wrong superuser login');	
		}		
	}
	
	public function setSuperuserPassword($param = '')
	{
		if (mb_strlen($param) > 7)
		{
			$this->superuserPassword = $param;
		}
		else
		{
			throw new \Exception('Wrong superuser password, should at least 8 symbols');	
		}		
	}
	
	public function setLocale($param = '')
	{
		if (preg_match('/^[a-z]{2}$/', $param))
		{
			$this->locale = $param;
		}
		else
		{
			throw new \Exception('Wrong locale');	
		}		
	} 
	
	public function setDbDatabase($param = '')
	{
		if (preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $param))
		{
			$this->dbDatabase = $param;
		}
		else
		{
			throw new \Exception('Wrong database name');	
		}		
	}
	
	public function setDbDriver($param = '')
	{
		if (in_array($param, ['sqlite', 'mysql', 'pgsql', 'sqlsrv']))
		{
			$this->dbDriver = $param;
		}
		else
		{
			throw new \Exception('Wrong database driver');	
		}		
	}
	
	public function setDbHost($param = '')
	{
		if (filter_var($param, FILTER_VALIDATE_IP) && $this->validateDomain($param))
		{
			$this->dbHost = $param;
		}
		else if ($this->dbDriver != 'sqlite') 
		{
			throw new \Exception('Wrong database host');	
		}		
	}
	
	public function setDbUsername($param = '')
	{
		if (mb_strlen($param))
		{
			$this->dbUsername = $param;
		}
		else if ($this->dbDriver != 'sqlite') 
		{
			throw new \Exception('Wrong database username');	
		}		
	}
	
	public function setDbPassword($param = '')
	{
		if (mb_strlen($param))
		{
			$this->dbPassword = $param;
		}
		else if ($this->dbDriver != 'sqlite') 
		{
			throw new \Exception('Wrong database password');	
		}		
	}
	
	public function setDbPrefix($param = '')
	{
		if (mb_strlen($param) && preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $param))
		{
			$this->dbPrefix = $param;
		}
		else if (mb_strlen($param)) 
		{
			throw new \Exception('Wrong database prefix');	
		}		
	}
	
	public function setDomainSecure($param = '')
	{
		$this->domainSecure = $param === true || $param == 'yes' || $param == 'y' ? true : false;
	}

	public function validateDomain($param)
	{
		return gethostbyname(idn_to_ascii($param));
	}


	public function process($param = [])
    {
        $error = []; 

        $domain = trim(\Input::get('domain'));
        $superuser_login = trim(\Input::get('superuser_login'));
        $superuser_password = trim(\Input::get('superuser_password'));
        $locale = trim(\Input::get('locale'));
        $db_driver = trim(\Input::get('db_driver'));
        $db_host = trim(\Input::get('db_host'));
        $db_username = trim(\Input::get('db_username'));
        $db_database = trim(\Input::get('db_database'));
        $db_password = trim(\Input::get('db_password'));
        $db_prefix = trim(\Input::get('db_prefix'));

        try
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
        catch (\Exception $e)
        {
            return ['error' => $error];
        }
            
        return ['success' => 1];
    } 
}
