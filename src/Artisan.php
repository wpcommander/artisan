<?php

namespace Wpcommander\Artisan;

class Artisan
{
    public static function exec( $rootDir )
    {
        $pluginNewName = (string) readline( "Enter plugin name: " );
        $pluginNewName = PluginName::pluginNameValidation( $pluginNewName );

        $pluginNewNameSpace = (string) readline( "Enter plugin namespace: " );
        $pluginNewNameSpace = PluginNamespace::pluginNameSpaceValidation( $pluginNewNameSpace );

        $folders = ['app', 'bootstrap', 'config', 'enqueues', 'routes'];
        $data    = [
            'pluginName'         => 'PluginName',
            'pluginNewName'      => $pluginNewName,
            'pluginNamespace'    => 'PluginNameSpace',
            'pluginNewNamespace' => $pluginNewNameSpace,
            'apiNamespace'       => 'plugin-api-namespace',
            'apiNewNamespace'    => 'myplugin'
        ];

        foreach ( $folders as $folder ) {
            $dir = $rootDir . '\\' . $folder;
            Common::getDirContents( $dir, $data );
        }

        $dir     = $rootDir . '\composer.json';
        $content = Common::getReplaceContent( $dir, $data );
        $content = str_replace('"post-create-project-cmd" : "php artisan"', '', $content);
        file_put_contents($dir, $content);

        $content      = Common::getReplaceContent( $rootDir . '\wpcommander.php', $data );
        $rootFileName = str_replace( ' ', '-', strtolower( $pluginNewName ) ) . '.php';
        $file         = fopen( $rootFileName, "wb" );

        fwrite( $file, $content );
        fclose( $file );

        exec( 'composer remove --dev wpcommander/artisan' );
        exec( 'composer dump-autoload' );

        unlink( $rootDir . '\wpcommander.php' );
        unlink( $rootDir . '\artisan' );
    }
}
