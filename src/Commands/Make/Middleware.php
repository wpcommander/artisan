<?php

namespace WpCommander\Artisan\Commands\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WpCommander\Artisan\Common;

class Middleware extends Command
{
    protected static $defaultName = 'make:middleware';

    protected static $defaultDescription = 'Create a middleware class';

    protected $rootDir;

    public function __construct( string $rootDir )
    {
        $this->rootDir = $rootDir;
        parent::__construct();
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $middleware = $input->getArgument( 'middleware' );
        $middleware = (string) str_replace( [' ', '/', '\\'], ['', '', ''], $middleware );
        $middleware = ucwords( $middleware );
        $filePath   = $this->rootDir . '/app/Http/Middleware/' . $middleware . '.php';

        if ( is_file( $filePath ) ) {
            $io = new SymfonyStyle( $input, $output );
            $io->getErrorStyle()->warning( $middleware . ' Middleware Class Already Exists' );
            return Command::INVALID;
        }

        $namespace = Common::getNamespace( $this->rootDir );
        $content   = (string) str_replace( ['PluginNameSpace', 'MiddlewareName'], [$namespace, $middleware], $this->middlewareFileContent() );
        $file      = fopen( $filePath, "wb" );

        fwrite( $file, $content );
        fclose( $file );
        $output->writeln( '<info>' . $middleware . ' Middleware Created Successfully!</info>' );

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->addArgument( 'middleware', InputArgument::REQUIRED, 'Middleware class name?' );
    }

    private function middlewareFileContent()
    {
        $namespace = explode('\\', __NAMESPACE__)[0];

        return '<?php

namespace ' . $namespace . '\App\Http\Middleware;

use ' . $namespace . '\WpCommander\Contracts\Middleware;
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
