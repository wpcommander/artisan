<?php

namespace Wpcommander\Artisan;

class PluginName
{
    public static function pluginNameValidation( $pluginName )
    {
        if ( empty( $pluginName ) ) {
            $pluginName =  Artisan::run( "Please enter plugin name: " );
            $pluginName = static::pluginNameValidation( $pluginName );
        }

        return $pluginName;
    }
}
