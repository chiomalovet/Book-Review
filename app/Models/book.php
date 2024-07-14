<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class book extends Model
{
    use HasFactory;

    public function review ()
    {
        return $this->hasMany(review::class);
    }

    // building a local query 
    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%'. $title .'%' );
    }


    public function scopeWithReviewCount(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount([
            'review' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ]);
    }

    public function scopeWithAverageRating(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withAvg([
            'review' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating');
        
    }
    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {
       return $query->withreviewcount() ->orderBy('review_count','Desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder
    {
        return $query->WithAverageRating()->orderBy('review_avg_rating', 'desc');
    }
 public function scopeMinReview(Builder $query, int $minReview): Builder
 {
    return $query->having('review_count', '>=', $minReview);
 }
    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query->popular(now()->subMonth(), now())
        ->HighestRated(now()->subMonth(), now())
        ->minReview(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder
    {
        return $query->popular(now()->subMonths(6), now())
        ->HighestRated(now()->subMonths(6), now())
        ->minReview(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query->HighestRated(now()->subMonth(), now())
        ->popular(now()->subMonth(), now())
        ->minReview(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder
    {
        return $query->HighestRated(now()->subMonths(6), now())
        ->popular(now()->subMonths(6), now())
        ->minReview(5);
    }

    protected static function booted(){
        static::updated(fn(book $book) => cache()->forget('book:' . $book->id));
        static::deleted(fn(book $book) => cache()->forget('book:' . $book->id));
    }
}

