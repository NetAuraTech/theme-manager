<?php

namespace Netauratech\ThemeManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class MinifyTheme implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $path;

    /**
     * Create a new job instance.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $folder = "$this->path/css";

        if(env('APP_ENV') !== 'dev') {
            $nodePath = env('NODE_PATH');
            $commands = [
                "export PATH=$nodePath:\$PATH && npx postcss $folder/*.css -u autoprefixer cssnano -r --no-map"
            ];
        } else {
            $commands = [
                "npx postcss $folder/*.css -u autoprefixer cssnano -r --no-map"
            ];
        }

        foreach ($commands as $command) {
            $process = Process::fromShellCommandline($command);
            $process->setWorkingDirectory(base_path());

            $process->run(function ($type, $buffer) {
                echo $type === Process::ERR ? "❌ $buffer" : "✅ $buffer";
            });

            if (!$process->isSuccessful()) {
                throw new \RuntimeException("Error when compressing theme :" . $process->getErrorOutput());
            }
        }
    }
}
