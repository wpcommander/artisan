<?php

namespace Wpcommander;

class PluginName
{
    public static function pluginNameValidation( $pluginName )
    {
        if ( empty( $pluginName ) ) {
            $pluginName = (string) readline( "Please enter plugin name: " );
            $pluginName = static::pluginNameValidation( $pluginName );
        }

        return $pluginName;
    }
}
