<?php

namespace Wpcommander\Artisan\Commands\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wpcommander\Artisan\Common;

class ServiceProvider extends Command
{
    protected static $defaultName = 'make:provider';

    protected static $defaultDescription = 'Create a new service provider class';

    protected $rootDir;

    public function __construct( string $rootDir )
    {
        $this->rootDir = $rootDir;
        parent::__construct();
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $serviceProvider = $input->getArgument( 'serviceProvider' );
        $serviceProvider = (string) str_replace( [' ', '/', '\\'], ['', '', ''], $serviceProvider );
        $serviceProvider = ucwords( $serviceProvider );
        $filePath        = $this->rootDir . '/app/Providers/' . $serviceProvider . '.php';

        if ( is_file( $filePath ) ) {
            $io = new SymfonyStyle( $input, $output );
            $io->getErrorStyle()->warning( $serviceProvider . ' ServiceProvider Class Already Exists' );
            return Command::INVALID;
        }

        $namespace = Common::getNamespace( $this->rootDir );
        $content   = (string) str_replace( ['PluginNameSpace', 'ServiceProviderName'], [$namespace, $serviceProvider], $this->middlewareFileContent() );
        $file      = fopen( $filePath, "wb" );

        fwrite( $file, $content );
        fclose( $file );
        $output->writeln( '<info>' . $serviceProvider . ' ServiceProvider Created Successfully!</info>' );

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->addArgument( 'serviceProvider', InputArgument::REQUIRED, 'ServiceProvider class name?' );
    }

    private function middlewareFileContent()
    {
        return '<?php

namespace PluginNameSpace\App\Providers;

use WpCommander\Contracts\ServiceProvider;

class ServiceProviderName extends ServiceProvider
{
    public function boot()
    {
        //
    }
}';
    }
}
