<?php

namespace Wpcommander\Artisan\Commands\Make;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wpcommander\Artisan\Common;

#[AsCommand(
    name:'make:middleware',
    description:'Create a middleware class',
)]
class Middleware extends Command
{
    protected $rootDir;

    public function __construct( string $rootDir )
    {
        $this->rootDir = $rootDir;
        parent::__construct();
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $middleware = $input->getArgument( 'middleware' );
        $middleware = str_replace( [' ', '/', '\\'], ['', '', ''], $middleware );
        $middleware = ucwords( $middleware );
        $filePath   = $this->rootDir . '/app/Http/Middleware/' . $middleware . '.php';

        if ( is_file( $filePath ) ) {
            $output->writeln( $middleware . ' Middleware Already Exists' );
            return Command::INVALID;
        }

        $namespace = Common::getNamespace( $this->rootDir );
        $content   = str_replace( ['PluginNameSpace', 'MiddlewareName'], [$namespace, $middleware], $this->middlewareFileContent() );
        $file      = fopen( $filePath, "wb" );

        fwrite( $file, $content );
        fclose( $file );
        $output->writeln( $middleware );

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->addArgument( 'middleware', InputArgument::REQUIRED, 'Middleware class name?' );
    }

    private function middlewareFileContent()
    {
        return '<?php

namespace PluginNameSpace\App\Http\Middleware;

use WpCommander\Contracts\Middleware;
use WP_REST_Request;

class MiddlewareName implements Middleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \WP_REST_Request  $wp_rest_request
	 * @return bool
	 */
	public function handle( WP_REST_Request $wp_rest_request )
	{
		return current_user_can( "manage_options" );
	}
}';
    }
}
