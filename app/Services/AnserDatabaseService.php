<?php

namespace App\Services;

use App\Models\AnserDatabase;

class AnserDatabaseService
{
    public function getUnanswered()
    {
       $data = AnserDatabase::where('is_answered', 0)->get();

        return $data->toArray();
    }

    public function getAll()
    {
        return AnserDatabase::all();
    }

    public function findById(int $id)
    {
        return AnserDatabase::find($id);
    }

    public function markAsAnswered(int $id): bool
    {
        return AnserDatabase::where('id', $id)
            ->update([
                'is_answered' => 1
            ]);
    }

    public function resetAll(): bool
    {
        return AnserDatabase::query()
            ->update([
                'is_answered' => 0
            ]);
    }


    public function countUnanswered(): int
    {
        return AnserDatabase::where('is_answered', 0)
            ->count();
    }

    public function getRandomQuestion()
    {
        return AnserDatabase::where('is_answered', 0)
            ->inRandomOrder()
            ->first();
    }

    public function getRandomQuestions(int $limit = 10)
    {
        return AnserDatabase::where('is_answered', 0)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}