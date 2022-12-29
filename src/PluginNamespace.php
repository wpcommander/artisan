<?php

namespace Wpcommander\Artisan;

class PluginNamespace
{
    public static function pluginNameSpaceValidation( $pluginNameSpace )
    {
        if ( empty( $pluginNameSpace ) ) {
            $pluginNameSpace = (string) stream_get_line( "Please enter plugin namespace: " );
            $pluginNameSpace = static::pluginNameSpaceValidation( $pluginNameSpace );
        }

        $first_character = intval( substr( $pluginNameSpace, 0, 1 ) );

        if ( 0 !== $first_character ) {
            $pluginNameSpace = (string) stream_get_line( "Please enter a valid namespace: " );
            $pluginNameSpace = static::pluginNameSpaceValidation( $pluginNameSpace );
        }

        return $pluginNameSpace;
    }
}
