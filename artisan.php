<?php

use Wpcommander\Common;
use Wpcommander\PluginName;
use Wpcommander\PluginNamespace;

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

$dir = $rootDir . '\composer.json';
Common::updatePluginInfo( $dir, $data );

$content      = Common::getReplaceContent( $rootDir . '\plugin.php', $data );
$rootFileName = str_replace( ' ', '-', strtolower( $pluginNewName ) ) . '.php';
$file         = fopen( $rootFileName, "wb" );

fwrite( $file, $content );
fclose( $file );
unlink( $rootDir . '\plugin.php' );
