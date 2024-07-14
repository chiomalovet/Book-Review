@extends('layouts.app')


@section('content')
    <h1 class="mb-10 2xl">BOOKS</h1>

    <form action="{{route('book.index')}}" method="get" class="flex items-center mb-5 gap-3">
        <input type="text" value="{{request('title')}}" placeholder="Search by Title" 
        name="title" class="input h-10">
  <input type="hidden" name="filter" value="{{request('filter')}}">
        <button type="submit" class="btn h-10">Search</button>
        <a href="{{route('book.index')}}" class="btn h-10">Clear</a>
    </form>

<div class="filter-container flex mb-5">
@php
$filters=[
    '' => "latest",
    'popular_last_month' =>"Popular Last Month",
    'popular_last_6months' =>"Popular Last 6 Months",
    'highest_rated_last_month' =>"Highest Rated Last Month",
    'highest_rated_last_6months' =>"Highest Rated Last 6 Months"
]
@endphp

@foreach ($filters as $key=> $label)
<a href="{{ route('book.index', [... request()->query(), 'filter'=> $key]) }}" class="{{ request('filter') === $key ||
 (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
    {{$label}}
</a>
@endforeach
</div>

    <ul>
        @forelse ($books as $book)
        <li class="mb-4">
            <div class="book-item">
              <div
                class="flex flex-wrap items-center justify-between">
                <div class="w-full flex-grow sm:w-auto">
                  <a href="{{route('book.show', $book)}}" class="book-title">{{$book->title}}</a>
                  <span class="book-author">By {{$book->author}}</span>
                </div>
                <div>
                  <div class="book-rating">
                    <x-star-rating :rating="$book->review_avg_rating"/>
                  </div>
                   <div class="book-review-count"> 
                    out of {{$book->review_count}} {{Str::plural('review', $book->review_count)}}
                  </div> 
                </div>
              </div>
            </div>
          </li> 
        @empty
        <li class="mb-4">
            <div class="empty-book-item">
              <p class="empty-text">No books found</p>
              <a href="{{route('book.index')}}" class="reset-link">Reset criteria</a>
            </div>
          </li>
        @endforelse
    </ul>
@endsection