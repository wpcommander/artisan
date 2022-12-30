<?php

namespace Wpcommander\Artisan\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Wpcommander\Artisan\Common;

#[AsCommand(
    name:'app:setup',
    description:'Setup wordpress plugin basic information',
)]
class Setup extends Command
{
    protected $rootDir;

    public function __construct( string $rootDir )
    {
        $this->rootDir = $rootDir;
        parent::__construct();
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $helper = $this->getHelper( 'question' );

        /**
         * Get Plugin Name
         */
        $question   = new Question( 'Enter plugin name: ' );
        $pluginName = $helper->ask( $input, $output, $question );
        $pluginName = $this->pluginNameValidation( $pluginName, $input, $output );

        /**
         * Get Plugin Namespace
         */
        $question        = new Question( 'Enter plugin namespace: ' );
        $pluginNameSpace = $helper->ask( $input, $output, $question );
        $pluginNameSpace = $this->pluginNameSpaceValidation( $pluginNameSpace, $input, $output );

        /**
         * Get Plugin Rest Api Namespace
         */
        $question           = new Question( 'Enter plugin api namespace: ' );
        $pluginApiNameSpace = $helper->ask( $input, $output, $question );
        $pluginApiNameSpace = $this->pluginApiNamespaceValidation( $pluginApiNameSpace, $input, $output );

        $data = [
            'pluginName'         => 'PluginName',
            'pluginNewName'      => $pluginName,
            'pluginNamespace'    => 'PluginNameSpace',
            'pluginNewNamespace' => $pluginNameSpace,
            'apiNamespace'       => 'plugin-api-namespace',
            'apiNewNamespace'    => $pluginApiNameSpace,
            'pluginFileName'     => 'PluginFileName',
            'pluginNewFileName'  => str_replace( ' ', '-', strtolower( $pluginName ) )
        ];

        foreach ( $this->folders() as $folder ) {
            $dir = $this->rootDir . '\\' . $folder;
            Common::getDirContents( $dir, $data );
        }

        foreach ( $this->files() as $file ) {
            Common::updatePluginInfo( $this->rootDir . '\\' . $file, $data );
        }

        $this->updateInformation( $data );

        return Command::SUCCESS;
    }

    public function updateInformation( array $data )
    {
        $content      = Common::getReplaceContent( $this->rootDir . '\wpcommander.php', $data );
        $name         = str_replace( ' ', '-', strtolower( $data['pluginNewFileName'] ) );
        $rootFileName = $name . '.php';
        $file         = fopen( $rootFileName, "wb" );

        fwrite( $file, $content );
        fclose( $file );

        exec( 'composer dump-autoload' );

        unlink( $this->rootDir . '\wpcommander.php' );
    }

    protected function files()
    {
        return [
            'composer.json',
            'Gruntfile.js',
            'package.json',
            'postcss.config.js'
        ];
    }

    protected function configure(): void
    {
        $this->setHelp( 'Setup wordpress plugin basic information' );
    }

    protected function folders()
    {
        return ['app', 'bootstrap', 'config', 'enqueues', 'routes'];
    }

    protected function pluginNameValidation( $pluginName, $input, $output )
    {
        if ( empty( $pluginName ) ) {
            $question   = new Question( "Please enter plugin name: " );
            $helper     = $this->getHelper( 'question' );
            $pluginName = $helper->ask( $input, $output, $question );
            $pluginName = $this->pluginNameValidation( $pluginName, $input, $output );
        }

        return $pluginName;
    }

    protected function pluginApiNamespaceValidation( $pluginApiNamespace, $input, $output )
    {
        if ( empty( $pluginApiNamespace ) ) {
            $question           = new Question( "Please enter plugin api namespace: " );
            $helper             = $this->getHelper( 'question' );
            $pluginApiNamespace = $helper->ask( $input, $output, $question );
            $pluginApiNamespace = $this->pluginApiNamespaceValidation( $pluginApiNamespace, $input, $output );
        }

        return $pluginApiNamespace;
    }

    public function pluginNameSpaceValidation( $pluginNameSpace, $input, $output )
    {
        if ( empty( $pluginNameSpace ) ) {
            $question        = new Question( "Please enter plugin namespace: " );
            $helper          = $this->getHelper( 'question' );
            $pluginNameSpace = $helper->ask( $input, $output, $question );
            $pluginNameSpace = $this->pluginNameSpaceValidation( $pluginNameSpace, $input, $output );
        }

        $first_character = intval( substr( $pluginNameSpace, 0, 1 ) );

        if ( 0 !== $first_character ) {
            $question        = new Question( "Please enter a valid namespace: " );
            $helper          = $this->getHelper( 'question' );
            $pluginNameSpace = $helper->ask( $input, $output, $question );
            $pluginNameSpace = $this->pluginNameSpaceValidation( $pluginNameSpace, $input, $output );
        }

        return $pluginNameSpace;
    }
}
