<?php

namespace Wpcommander\Artisan\Commands\Make;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wpcommander\Artisan\Common;

class Controller extends Command
{
    protected static $defaultName = 'make:controller';

    protected static $defaultDescription = 'Create a new controller class';

    protected $rootDir;

    public function __construct( string $rootDir )
    {
        $this->rootDir = $rootDir;
        parent::__construct();
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $controller = $input->getArgument( 'controller' );
        $controller = (string) str_replace( [' ', '/', '\\'], ['', '', ''], $controller );
        $controller = ucwords( $controller );
        $filePath   = $this->rootDir . '/app/Http/Controllers/' . $controller . '.php';

        if ( is_file( $filePath ) ) {
            $io = new SymfonyStyle( $input, $output );
            $io->getErrorStyle()->warning( $controller . ' Controller Class Already Exists' );
            return Command::INVALID;
        }

        $namespace = Common::getNamespace( $this->rootDir );
        $content   = (string) str_replace( ['PluginNameSpace', 'ControllerName'], [$namespace, $controller], $this->middlewareFileContent() );
        $file      = fopen( $filePath, "wb" );

        fwrite( $file, $content );
        fclose( $file );
        $output->writeln( '<info>' . $controller . ' Controller Created Successfully!</info>' );

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->addArgument( 'controller', InputArgument::REQUIRED, 'Controller class name?' );
    }

    private function middlewareFileContent()
    {
        return '<?php

namespace PluginNameSpace\App\Http\Controllers;

class ControllerName extends Controller
{
    public function index()
    {
        //
    }
}';
    }
}
