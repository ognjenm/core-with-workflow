<?php

namespace Telenok\Core\Command\Install;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Controller extends Command {
 
	protected $name = 'telenok:install'; 
	protected $description = 'Install Telenok CMS with console';
	protected $processingController;
	
	public function setProcessingController($param = null)
	{
		$this->processingController = $param;
	}

	public function getProcessingController()
	{
		return $this->processingController;
	}

	public function fire()
	{
		$this->setProcessingController(new \Telenok\Core\Support\Install\Controller());

		$this->info('Configure Telenok CMS');

		if ($this->confirm('Do you want to configure app.php and database.php [yes/no]: ', false))
		{
			$this->inputDomain();
			$this->inputDomainSecure();
			$this->inputSuperuserLogin();
			$this->inputSuperuserPassword();
			$this->inputLocale();
			$this->inputDbDriver();
			$this->inputDbHost();
			$this->inputDbUsername();
			$this->inputDbPassword();
			$this->inputDbDatabase();
			$this->inputDbPrefix();
			
			if ($this->confirm('Do you want to replace app.php and database.php files [yes/no]: ', false))
			{
				try
				{
					$this->processingController->processConfigFile();
					
					$this->info('Done. Thank you.');
				}
				catch (\Exception $ex)
				{
					$this->error('Sorry, an error occured.');
					$this->error($ex->getMessage());
				}
			}
		}
	}
 
	public function inputDomain()
	{
		while($name = $this->ask('What are site\'s domain or IP, eg, mysite.com or 192.168.0.1: '))
		{
			$this->info('Wait, please...');

			try
			{
				$this->processingController->setDomain($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
 
	public function inputDomainSecure()
	{
		$this->processingController->setDomainSecure($this->confirm('Is domain secure (aka site uses https) [yes/no]: '));
	}

	public function inputSuperuserLogin()
	{
		while($name = $this->ask('What is login for superuser in backend: '))
		{

			try
			{
				$this->processingController->setSuperuserLogin($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
 
	public function inputSuperuserPassword()
	{
		while($name = $this->secret('What is password for superuser in backend: '))
		{ 
			try
			{
				$this->processingController->setSuperuserPassword($name); 
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
			
			$confirmPassword = $this->secret('Please, type password again to confirm it: ');
			
			if ($name === $confirmPassword)
			{
				break;
			}
			else
			{
				$this->error('Wrong confirmed password. Try again, please.');
			}
		}
	}
 
	public function inputLocale()
	{
		while($name = $this->ask('What is locale, eg, en: '))
		{
			try
			{
				$this->processingController->setLocale($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
 
	public function inputDbDriver()
	{
		while($name = $this->ask('What is database driver, eg, mysql: '))
		{
			try
			{
				$this->processingController->setDbDriver($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
 
	public function inputDbHost()
	{
		while($name = $this->ask('What is database host, eg, 127.0.0.1 or mysql.mysite.com: '))
		{
			$this->info('Wait, please...');

			try
			{
				$this->processingController->setDbHost($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
 
	public function inputDbUsername()
	{
		while($name = $this->ask('What is database username: '))
		{
			try
			{
				$this->processingController->setDbUsername($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
 
	public function inputDbPassword()
	{
		while($name = $this->ask('What is database user\'s password: '))
		{
			try
			{
				$this->processingController->setDbPassword($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
 
	public function inputDbDatabase()
	{
		while($name = $this->ask('What is database name: '))
		{
			try
			{
				$this->processingController->setDbDatabase($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
	
	public function inputDbPrefix()
	{
		while($name = $this->ask('What is database prefix [empty default]: '))
		{
			try
			{
				$this->processingController->setDbPrefix($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
				continue;
			}
		}
	}
}
