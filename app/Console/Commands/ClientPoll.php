<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ClientPoll extends Command
{
    protected $signature = 'client:poll {client_id} {--server=}';
    protected $description = 'Poll the server for download requests, then upload the requested file.';

    public function handle()
    {
        $clientId = $this->argument('client_id');
        $server = $this->option('server') ?: 'http://127.0.0.1:8000/api';

        $this->info("Polling {$server} for tasks as {$clientId}...");

        $resp = Http::timeout(30)->get("{$server}/tasks/{$clientId}");
        if (!$resp->ok()) {
            $this->error("Server returned status ".$resp->status());
            return;
        }

        $data = $resp->json();
        if (!isset($data['action']) || $data['action'] !== 'send_file') {
            $this->line("No tasks for {$clientId}.");
            return;
        }

        $taskId = $data['task_id'];
        $filePath = storage_path('app/data/sample.txt');

        if (!file_exists($filePath)) {
            $this->error("Local file not found at {$filePath}");
            return;
        }

        $this->info("Uploading file for task {$taskId}...");

        $upload = Http::attach('file', file_get_contents($filePath), 'sample.txt')
            ->post("{$server}/upload", ['client_id' => $clientId, 'task_id' => $taskId]);

        if ($upload->ok()) {
            $this->info("File uploaded successfully!");
        } else {
            $this->error("Upload failed: ".$upload->status());
        }
    }
}
