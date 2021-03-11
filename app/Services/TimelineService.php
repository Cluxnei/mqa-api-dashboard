<?php


namespace App\Services;


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

    final public static function fromUser(User $user): Collection {
        $events = collect([]);
        $events->add(self::formatEvent(__('user signed up'), $user->created_at));
        return $events->sortByDesc('date');
    }
}