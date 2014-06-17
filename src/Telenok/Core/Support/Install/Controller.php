<?php

namespace Telenok\Core\Support\Install;

class Controller extends \Illuminate\Routing\Controller {
    
    public function getContent()
    {
        return \View::make('core::install.installer', array('name' => 'Taylor'));
    }
    
    public function process()
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
            if (!$domain || !$this->validateDomain($domain))
            {
               $error['domain'] = 1;
            }

            if (!$superuser_login || !preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $superuser_login))
            {
               $error['superuser_login'] = 1;
            }

            if (!$superuser_password || strlen($superuser_password) < 8)
            {
               $error['superuser_password'] = 1;
            }

            if (!$db_database || !preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $db_database))
            {
               $error['db_database'] = 1;
            }

            if ($db_driver!='sqlite' && (!$db_host || (!filter_var($db_host, FILTER_VALIDATE_IP) && !$this->validateDomain($db_host))))
            {
               $error['db_host'] = 1;
            }

            if ($db_prefix && !preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $db_prefix))
            {
               $error['db_prefix'] = 1;
            }

            if (!empty($error))
            {
                throw new \Exception();
            } 
            
            $reflector = new \ReflectionClass('\Telenok\Core\CoreServiceProvider');
            $file = $reflector->getFileName();
            
            $content = \File::get($file);

            $pattern = '/(DONOTDELETETHISCOMMENT\s*)(return;)/i';
            $replacement = '${1}return;';
            
            $res = preg_replace($pattern, $replacement, $content);
            
            \File::put($file, $res);
            
            $param = array(
                'domain' => $domain,
                'locale' => $locale,
                'random' => str_random(),
                'db_driver' => $db_driver,
                'db_database' => $db_database,
                'db_host' => $db_host,
                'db_username' => $db_username,
                'db_password' => $db_password,
                'db_prefix' => $db_prefix,
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
            
            \Artisan::call('asset:publish');
            \Artisan::call('optimize');
            \Artisan::call('migrate');
        }
        catch (\Exception $e)
        {
            return ['error' => $error];
        }
            
        return ['success' => 1];
    }

    protected function validateDomain($domain)
    { 
        return gethostbyname(idn_to_ascii($domain));
    }
}
