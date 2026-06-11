<?php

namespace App\Console\Commands;

use App\Models\AnserDatabase as Question;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportQuestions extends Command
{
    protected $signature = 'questions:import';
    protected $description = 'Import questions from json file';

    public function handle()
    {
        $path = public_path('data/data-question-answer.json');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return Command::FAILURE;
        }

        $data = json_decode(file_get_contents($path), true);

        if (!$data) {
            $this->error('Invalid JSON file.');
            return Command::FAILURE;
        }

        try {

            DB::transaction(function () use ($data) {

                foreach ($data as $item) {

                    Question::create([
                        'id'          => (int)$item['serial_no'],
                        'question'    => $item['question'],
                        'answer'      => $item['answer'],
                        'is_answered' => 0,
                    ]);
                }
            });

            $this->info('Import completed successfully.');
            $this->info('Total records: ' . count($data));

            return Command::SUCCESS;

        } catch (\Throwable $e) {

            $this->error('Import failed.');
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}