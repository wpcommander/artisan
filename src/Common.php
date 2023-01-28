<?php

namespace WpCommander\Artisan;

class Common
{
    public static function getReplaceContent( $path, $data )
    {
        $content = file_get_contents( $path );
        return str_replace(
            [
                $data['pluginNamespace'],
                $data['pluginName'],
                $data['apiNamespace'],
                $data['pluginFileName']
            ],
            [
                $data['pluginNewNamespace'],
                $data['pluginNewName'],
                $data['apiNewNamespace'],
                $data['pluginNewFileName']
            ],
            $content
        );
    }

    public static function updatePluginInfo( $path, $data )
    {
        file_put_contents( $path, static::getReplaceContent( $path, $data ) );
    }

    public static function getDirContents( $dir, $data )
    {
        $files = scandir( $dir );
        foreach ( $files as $key => $value ) {
            $path = realpath( $dir . DIRECTORY_SEPARATOR . $value );
            if ( !is_dir( $path ) ) {
                static::updatePluginInfo( $path, $data );
            } else if ( $value != "." && $value != ".." ) {
                static::getDirContents( $path, $data );
            }
        }
    }

    public static function getNamespace( $rootDir )
    {
        $getComposerJsonContent = json_decode( file_get_contents( $rootDir . '/composer.json' ), true );

        foreach ( $getComposerJsonContent['autoload']['psr-4'] as $key => $item ) {
            $folders = explode( '/', $item );
            if ( isset( $folders[0] ) ) {
                if ( 'app' == $folders[0] ) {
                    $classes = explode( '\\', $key );
                    if ( isset( $classes[0] ) ) {
                        return $classes[0];
                    }
                }
            }
        }
        return '';
    }

    public static function getWpCommanderFrameworkVersion( $rootDir )
    {
        $getComposerJsonContent = json_decode( file_get_contents( $rootDir . '/composer.json' ), true );
        return $getComposerJsonContent['require']['wpcommander/framework'];
    }
}
