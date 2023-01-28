<?php

namespace WpCommander\Artisan;

use Symfony\Component\Console\Application;
use WpCommander\Artisan\Commands\App\Setup;
use WpCommander\Artisan\Commands\Make\Controller;
use WpCommander\Artisan\Commands\Make\Middleware;
use WpCommander\Artisan\Commands\Make\ServiceProvider;

class Artisan
{
    public static function exec( $rootDir )
    {
        $application = new Application();

        $application->setName( 'WpCommander <info>' . Common::getWpCommanderFrameworkVersion( $rootDir ) . '</info>' );

        foreach ( self::commands() as $command ) {
            $application->add( new $command( $rootDir ) );
        }

        $application->run();
    }

    public static function commands()
    {
        return [
            Setup::class,
            Middleware::class,
            Controller::class,
            ServiceProvider::class
        ];
    }
}
