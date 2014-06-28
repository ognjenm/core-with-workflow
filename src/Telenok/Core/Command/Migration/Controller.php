<?php

namespace Telenok\Core\Command\Migration;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Controller extends Command {
 
	protected $name = 'telenok:migrate'; 
	protected $description = 'MIgrate and Seed Telenok CMS';
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

		$this->info('Migrate tables in Telenok CMS'); 
		
		if ($this->confirm('Do you want to create and update tables in database [yes/no]: ', false))
		{
			$this->inputSuperuserLogin();
			$this->inputSuperuserEmail();
			$this->inputSuperuserPassword(); 
			
			$this->info('Start create tables and seed datbase. Please, wait. It cat take some minuts.'); 
			
			$this->call('migrate', array('--package' => 'telenok/core'));

			$user = \Telenok\Core\Model\User\User::where('username', 'admin')->first();
			
			$user->storeOrUpdate([
				'username' => $this->processingController->getSuperuserLogin(),
				'email' => $this->processingController->getSuperuserEmail(),
				'password' => $this->processingController->getSuperuserPassword(),
			]);
		}
	}

	public function inputSuperuserLogin()
	{
		while(true)
		{
			$name = $this->ask('What is login for superuser in backend: ');

			try
			{
				$this->processingController->setSuperuserLogin($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}

	public function inputSuperuserEmail()
	{
		while(true)
		{
			$name = $this->ask('What is superuser\'s email: ');

			try
			{
				$this->processingController->setSuperuserEmail($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}

	public function inputSuperuserPassword()
	{
		while(true)
		{ 
			$name = $this->secret('What is password for superuser in backend: ');
			
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
}