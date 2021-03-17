<?php


namespace App\Services;


use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TimelineService
{

    private static function formatEvent(string $title, string $timestamp): object {
        $parsed = Carbon::parse($timestamp);
        return (object)[
            'title' => $title,
            'date' => $timestamp,
            'formatted' => (object)[
                'date' => $parsed->translatedFormat('j F, Y'),
                'time' => $parsed->format('H:i'),
                'diff' => $parsed->diffForHumans(),
            ],
        ];
    }

    final public static function fromUser(User $user): Collection
    {
        $events = collect([]);
        $events->add(self::formatEvent(__('user signed up'), $user->created_at));
        return $events->sortByDesc('date');
    }

    final public static function fromCompany(Company $company): Collection
    {
        $events = collect([]);
        $events->add(self::formatEvent(__('company was created'), $company->created_at));
        return $events->sortByDesc('date');
    }
}