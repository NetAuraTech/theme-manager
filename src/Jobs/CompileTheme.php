<?php

namespace Netauratech\ThemeManager\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class CompileTheme implements ShouldQueue
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
        $input = "$this->path/scss";
        $output = "$this->path/css";

        if(env('APP_ENV') !== 'dev') {
            $nodePath = env('NODE_PATH');
            $command = "export PATH=$nodePath:\$PATH && npx sass --no-source-map $input:$output";
        } else {
            $command = "npx sass --no-source-map $input:$output";
        }

        $process = Process::fromShellCommandline($command);
        $process->setWorkingDirectory(base_path());

        $process->run(function ($type, $buffer) {
            echo $type === Process::ERR ? "❌ $buffer" : "✅ $buffer";
        });

        if (!$process->isSuccessful()) {
            throw new \RuntimeException("Error when compiling theme :" . $process->getErrorOutput());
        }
    }
}
