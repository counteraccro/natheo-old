<?php
/**
 * Service qui execute des commandes systèmes
 * @author Gourdon Aymeric
 * @version 1.0
 * @package App\Service\Admin\System
 */
namespace App\Service\Admin\System;

use App\Service\AppService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class SystemService extends AppService
{

    /**
     * Permet de vider le cache applicatif
     * @return void
     * @throws Exception
     */
    public function clearCacheInterne()
    {
        // On vide le cache applicatif
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'cache:clear',
            '--no-warmup' => true,
        ]);
        $output = new NullOutput();
        $application->run($input, $output);
    }
}