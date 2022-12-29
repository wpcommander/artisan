<?php

namespace Wpcommander\Artisan;

class PluginName
{
    public static function pluginNameValidation( $pluginName )
    {
        if ( empty( $pluginName ) ) {
            $pluginName = (string) stream_get_line( "Please enter plugin name: " );
            $pluginName = static::pluginNameValidation( $pluginName );
        }

        return $pluginName;
    }
}
