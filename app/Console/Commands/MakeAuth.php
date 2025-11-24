<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Application Auth';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->publishStubDirectory('stubs/auth/controllers', app_path('Http/Controllers/Auth'));
        $this->publishStubDirectory('stubs/auth/requests', app_path('Http/Requests/Auth'));
        $this->publishRoutes('stubs/auth/routes/auth.stub', base_path('routes/auth.php'));

        return Command::SUCCESS;
    }

    /**
     * Publish a stub directory to the target directory.
     *
     * @param string $stubDir
     * @param string $targetDir
     * @return void
     */
    private function publishStubDirectory($stubDir, $targetDir)
    {
        $stubDir = base_path($stubDir);

        foreach ($this->files->allFiles($stubDir) as $file) {
            $targetPath = sprintf('%s/%s.php', $targetDir, $file->getFilenameWithoutExtension());
            $this->files->ensureDirectoryExists($targetDir);

            $contents = $this->files->get($file->getRealPath());
            $this->files->put($targetPath, $contents);

            $this->info("Created: {$targetPath}");
        }
    }

    /**
     * Publish a stub route to the target route.
     *
     * @param string $stubPath
     * @param string $targetPath
     * @return void
     */
    private function publishRoutes($stubPath, $targetPath)
    {
        $contents = $this->files->get(base_path($stubPath));
        $this->files->put($targetPath, $contents);
    }
}
