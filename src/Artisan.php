<?php

namespace Wpcommander\Artisan;

use Symfony\Component\Console\Application;
use Wpcommander\Artisan\Commands\Setup;

class Artisan
{
    public static function exec( $rootDir )
    {
        $application = new Application();

        foreach ( self::commands() as $command ) {
            $application->add( new $command( $rootDir ) );
        }

        $application->run();
    }

    public static function commands()
    {
        return [
            Setup::class
        ];
    }
}
