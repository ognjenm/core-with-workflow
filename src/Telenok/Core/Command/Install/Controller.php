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
  
		
	}
 
	public function getDomain()
	{
		while($name = $this->ask('What is your name? '))
		{
			try
			{
				$this->processingController->setDomain($name);
				break;
			}
			catch (\Exception $e)
			{
				$this->error('Wrong domain, please, retry');
				continue;
			}
		}
	}


}
