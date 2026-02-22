@extends('pathfinder.layout')

@section('title', 'External Learning Resources - Pathfinder')

@section('content')
<style>
    :root {
        --sea-900: #0f2742;
        --sea-700: #1b4b6d;
        --sea-500: #2f7ea2;
        --sea-300: #9dd1e5;
        --sun-200: #f7e1a1;
        --stone-100: #f6f7f9;
    }
</style>

<!-- Header Section -->
<div class="relative" style="background: radial-gradient(1200px 400px at 20% 0%, #2f7ea2 0%, #1b4b6d 45%, #0f2742 100%);">
    <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.08) 25%, transparent 25%), linear-gradient(225deg, rgba(255,255,255,0.08) 25%, transparent 25%), linear-gradient(45deg, rgba(255,255,255,0.08) 25%, transparent 25%), linear-gradient(315deg, rgba(255,255,255,0.08) 25%, transparent 25%); background-position: 12px 0, 12px 0, 0 0, 0 0; background-size: 24px 24px; background-repeat: repeat;"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" style="font-family: 'Poppins', 'Segoe UI', sans-serif;">
        <div class="text-center text-white">
            <p class="uppercase tracking-[0.3em] text-xs mb-3" style="color: var(--sea-300);">Learning Resources</p>
            <h1 class="text-4xl md:text-5xl font-semibold mb-3">Build a Focused Learning Path</h1>
            <p class="text-lg md:text-xl max-w-3xl mx-auto" style="color: #d7eef8;">
                @if(!empty($targetRole))
                    Learning resources for <span class="font-semibold">{{ $targetRole }}</span>
                    @if(!empty($targetCategory))
                        <span class="inline-flex items-center px-3 py-1 ml-2 rounded-full text-xs" style="background-color: rgba(247, 225, 161, 0.2); color: var(--sun-200);">
                            {{ $targetCategory }}
                        </span>
                    @endif
                @else
                    Curated playlists and feeds to help you close your skill gaps.
                @endif
            </p>
        </div>
    </div>
</div>

<!-- Summary -->
<div class="py-10" style="background-color: var(--stone-100);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Your Learning Snapshot</h2>
                    <p class="text-gray-600">Playlists are built directly from your missing skills to keep results precise.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if(!empty($missingSkills))
                        @foreach(array_slice($missingSkills, 0, 8) as $skill)
                            <span class="px-3 py-1 rounded-full text-xs font-medium" style="background-color: #e6f3f8; color: var(--sea-700);">
                                {{ $skill }}
                            </span>
                        @endforeach
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-medium" style="background-color: #fef3c7; color: #92400e;">
                            Run a skill gap analysis to personalize results.
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- YouTube Playlists (Middle Section) -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900">YouTube Playlists by Skill</h2>
                <p class="text-gray-600">Each playlist is matched to a missing skill and filtered by your target role.</p>
            </div>
            <div class="hidden md:flex items-center gap-2 text-sm text-gray-500">
                <span class="inline-flex items-center w-2 h-2 rounded-full" style="background-color: var(--sea-500);"></span>
                Updated daily via YouTube API
            </div>
        </div>

        @if(!empty($youtubeRecommendations))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($youtubeRecommendations as $group)
                    <div class="rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $group['skill'] }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full" style="background-color: #e6f3f8; color: var(--sea-700);">Playlists</span>
                        </div>
                        <div class="space-y-4">
                            @foreach($group['items'] as $item)
                                <div class="p-4 rounded-xl border border-gray-100" style="background-color: #fbfdff;">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <a href="{{ $item['url'] }}" target="_blank" class="font-semibold text-gray-900 hover:underline">
                                                {{ $item['label'] }}
                                            </a>
                                            <p class="text-sm text-gray-600 mt-1">{{ $item['description'] }}</p>
                                        </div>
                                        <div class="text-right text-xs text-gray-500">
                                            @if(!empty($item['channel']))
                                                <div>{{ $item['channel'] }}</div>
                                            @endif
                                            @if(!empty($item['videoCount']))
                                                <div>{{ $item['videoCount'] }} videos</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No playlists yet</h3>
                <p class="text-gray-600 mb-6">Run a skill gap analysis so we can generate playlists for your missing skills.</p>
                <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-lg" style="background-color: var(--sea-500);">
                    Start Skill Gap Analysis
                </a>
            </div>
        @endif
    </div>
</div>

<!-- RSS Feeds -->
<div class="py-16" style="background-color: var(--stone-100);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h2 class="text-3xl font-semibold text-gray-900">Live RSS Feeds</h2>
            <p class="text-gray-600">Fresh articles from trusted sources, updated and cached automatically.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($rssFeeds as $groupName => $feeds)
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $groupName === 'technical' ? 'Technical Skills' : 'Soft Skills' }}
                        </h3>
                        <span class="text-xs px-2 py-1 rounded-full" style="background-color: #e6f3f8; color: var(--sea-700);">Live</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($feeds as $feedName => $items)
                            <div class="border border-gray-100 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $feedName }}</h4>
                                    @if(!empty($rssSources[$groupName][$feedName]))
                                        <a href="{{ $rssSources[$groupName][$feedName][0]['url'] }}" target="_blank" class="text-xs font-medium" style="color: var(--sea-700);">Subscribe</a>
                                    @endif
                                </div>
                                <ul class="space-y-2">
                                    @foreach($items as $item)
                                        <li class="text-sm text-gray-700">
                                            <a href="{{ $item['url'] }}" target="_blank" class="hover:underline">{{ $item['title'] }}</a>
                                            @if(!empty($item['publishedAt']))
                                                <span class="text-xs text-gray-500 ml-2">{{ $item['publishedAt'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-200 p-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Feeds unavailable</h3>
                    <p class="text-gray-600">We could not fetch feeds right now. Please try again later.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- External Learning Platforms -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-semibold text-gray-900">Recommended Learning Platforms</h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium" style="color: var(--sea-700);">Back to dashboard</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($platforms as $platform)
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <div class="text-lg font-semibold text-gray-900 mb-2">{{ $platform['name'] }}</div>
                    <p class="text-sm text-gray-600 mb-4">{{ $platform['description'] }}</p>
                    <a href="{{ $platform['url'] }}" target="_blank" class="inline-flex items-center text-sm font-medium" style="color: var(--sea-700);">
                        Visit platform
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection