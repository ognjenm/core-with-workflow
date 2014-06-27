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

		if ($this->confirm('Do you want to configure app.php [yes/no]: ', false))
		{
			$this->inputDomain();
			$this->inputDomainSecure(); 
			$this->inputLocale();

			if ($this->confirm('Do you want to replace app.php [yes/no]: ', false))
			{
				try
				{
					$this->processingController->processConfigAppFile();

					$this->info('Done. Thank you.');
				}
				catch (\Exception $ex)
				{
					$this->error('Sorry, an error occured.');
					$this->error($ex->getMessage());
				}
			}
		}

		if ($this->confirm('Do you want to configure database.php [yes/no]: ', false))
		{
			$this->inputDbDriver();
			$this->inputDbHost();
			$this->inputDbUsername();
			$this->inputDbPassword();
			$this->inputDbDatabase();
			$this->inputDbPrefix();

			if ($this->confirm('Do you want to replace database.php files [yes/no]: ', false))
			{
				try
				{
					$this->processingController->processConfigDatabaseFile();

					$this->info('Done. Thank you.');
				}
				catch (\Exception $ex)
				{
					$this->error('Sorry, an error occured.');
					$this->error($ex->getMessage());
				}
			}
		}

		if (!\Schema::hasTable('setting'))
		{
			\Schema::create('setting', function(\Illuminate\Database\Schema\Blueprint $table)
			{
				$table->increments('id');
				$table->timestamps();
				$table->softDeletes();

				$table->text('title')->nullable();
				$table->string('code')->nullable()->default(null)->unique('code');
				$table->mediumText('value');
				$table->integer('active')->unsigned()->nullable()->default(null);
				$table->timestamp('start_at');
				$table->timestamp('end_at');
				$table->integer('created_by_user')->unsigned()->nullable()->default(null);
				$table->integer('updated_by_user')->unsigned()->nullable()->default(null);
				$table->integer('deleted_by_user')->unsigned()->nullable()->default(null);
			});
		}

		$this->processingController->postMigrateProcess();

		$this->info('Please, run command "php artisan telenok:migrate" to finish installation. Thank you.');
	}

	public function inputDomain()
	{
		while(true)
		{
			$name = $this->ask('What is site domain or IP, eg, mysite.com or 192.168.0.1: ');

			$this->info('Wait, please...');

			try
			{
				$this->processingController->setDomain($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}

	public function inputDomainSecure()
	{
		$this->processingController->setDomainSecure($this->confirm('Is domain secure (aka site uses https) [yes/no]: '));
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
 
	public function inputLocale()
	{
		while(true)
		{
			$name = $this->ask('What is locale, eg, en: ');
			
			try
			{
				$this->processingController->setLocale($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}
 
	public function inputDbDriver()
	{
		while(true)
		{
			$name = $this->ask('What is database driver, eg, mysql: ');
			
			try
			{
				$this->processingController->setDbDriver($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}
 
	public function inputDbHost()
	{
		while(true)
		{
			$name = $this->ask('What is database host, eg, 127.0.0.1 or mysql.mysite.com: ');
			
			$this->info('Wait, please...');

			try
			{
				$this->processingController->setDbHost($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}
 
	public function inputDbUsername()
	{
		while(true)
		{
			$name = $this->ask('What is database username: ');
					
			try
			{
				$this->processingController->setDbUsername($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}
 
	public function inputDbPassword()
	{
		while(true)
		{
			$name = $this->ask('What is database user\'s password: ');
			
			try
			{
				$this->processingController->setDbPassword($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}
 
	public function inputDbDatabase()
	{
		while(true)
		{
			$name = $this->ask('What is database name: ');
			
			try
			{
				$this->processingController->setDbDatabase($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}
	
	public function inputDbPrefix()
	{
		while(true)
		{
			$name = $this->ask('What is database prefix [empty default]: ');
			
			try
			{
				$this->processingController->setDbPrefix($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error($e->getMessage() . ' Please, retry.');
			}
		}
	}
}
