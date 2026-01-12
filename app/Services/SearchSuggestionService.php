<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Book;

class SearchSuggestionService
{
    public static function suggest(string $searchTerm): ?string
    {
        $normalizedSearch = mb_strtolower(trim($searchTerm));

        if ($normalizedSearch === '') {
            return null;
        }

        $candidates = collect();

        // Authors
        Author::select('name', 'name_en')->get()->each(function ($author) use ($candidates) {
            if ($author->name) {
                $candidates->push($author->name);
            }
            if ($author->name_en) {
                $candidates->push($author->name_en);
            }
        });

        // Book titles
        Book::select('title')->get()->each(function ($book) use ($candidates) {
            if ($book->title) {
                $candidates->push($book->title);
            }
        });

        $buckets = [
            90 => [],
            80 => [],
            70 => [],
        ];

        foreach ($candidates as $candidate) {
            similar_text(
                $normalizedSearch,
                mb_strtolower($candidate),
                $percent
            );

            foreach ($buckets as $threshold => $_) {
                if ($percent >= $threshold) {
                    $buckets[$threshold][$candidate] = $percent;
                    break;
                }
            }
        }

        foreach ([90, 80, 70] as $tier) {
            if (!empty($buckets[$tier])) {
                arsort($buckets[$tier]);
                return array_key_first($buckets[$tier]);
            }
        }

        return null;
    }
}
