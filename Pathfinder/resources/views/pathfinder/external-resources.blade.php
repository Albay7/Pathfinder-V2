@extends('pathfinder.layout')

@section('title', 'Learning Resources - Pathfinder')
@section('hide_footer', true)

@section('content')
<style>
    :root {
        --sea-900: #0f2742;
        --sea-800: #153557;
        --sea-700: #1b4b6d;
        --sea-500: #2f7ea2;
        --sea-400: #4a9bbf;
        --sea-300: #9dd1e5;
        --sea-100: #e6f3f8;
        --sun-200: #f7e1a1;
        --stone-100: #f6f7f9;
    }

    html { scroll-behavior: smooth; }

    /* ── Stat chips (hero) ── */
    .lr-stat-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.22);
        color: white;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 500;
        backdrop-filter: blur(4px);
    }

    /* ── Snapshot stat boxes ── */
    .lr-stat-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 14px 18px;
        background: var(--stone-100);
        border-radius: 14px;
        gap: 3px;
        min-width: 90px;
    }

    /* ── YouTube cards ── */
    .lr-yt-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: box-shadow 0.25s, transform 0.25s;
    }
    .lr-yt-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.09); transform: translateY(-1px); }

    /* ── Article cards ── */
    .lr-article-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: box-shadow 0.25s, transform 0.25s;
        display: flex;
        flex-direction: column;
    }
    .lr-article-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.09); transform: translateY(-1px); }
    .lr-article-card:hover h3 { color: var(--sea-500) !important; }
    .lr-article-card .p-5 { display: flex; flex-direction: column; flex: 1; }

    /* ── Job platform cards ── */
    .lr-job-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: box-shadow 0.25s, transform 0.25s;
        display: flex;
        flex-direction: column;
    }
    .lr-job-card:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .lr-job-card:hover .font-semibold { color: var(--sea-500) !important; }

    /* ── Section icon badge ── */
    .lr-section-icon {
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ── Source pill ── */
    .lr-source-pill {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 500;
        background: #f0f9ff;
        color: var(--sea-700);
        border: 1px solid var(--sea-300);
    }
</style>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 1 · HERO                           --}}
{{-- ═══════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="background: linear-gradient(160deg, #1a5276 0%, #154360 25%, #0f2742 55%, #0b1c33 100%);">
    {{-- Subtle dot pattern --}}
    <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 20px 20px;"></div>
    {{-- Glow accent --}}
    <div class="absolute top-0 right-0 w-96 h-96 opacity-20 rounded-full" style="background: radial-gradient(circle, var(--sea-400) 0%, transparent 70%); filter: blur(60px); transform: translate(30%, -30%);"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 opacity-10 rounded-full" style="background: radial-gradient(circle, var(--sea-300) 0%, transparent 70%); filter: blur(50px); transform: translate(-30%, 30%);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

        <div class="text-center text-white max-w-3xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-bold mb-3" style="letter-spacing: -0.02em;">Learning Resources</h1>
            <p class="text-base mb-7" style="color: rgba(215,238,248,0.8); line-height: 1.7;">
                @if(!empty($targetRole))
                    Curated resources for <span class="font-semibold" style="color: #fff;">{{ $targetRole }}</span>
                @else
                    Curated playlists, industry articles, and job platforms to help you close your skill gaps.
                @endif
            </p>

            {{-- Stat chips --}}
            <div class="flex flex-wrap justify-center gap-3">
                <span class="lr-stat-chip">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    YouTube Playlists
                </span>
                <span class="lr-stat-chip">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Industry Articles
                </span>
                <span class="lr-stat-chip">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Job Search
                </span>
                @if(!empty($missingSkills))
                <span class="lr-stat-chip">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ count($missingSkills) }} Skills to Learn
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 3 · LEARNING SNAPSHOT              --}}
{{-- ═══════════════════════════════════════════ --}}
<div id="snapshot" class="py-10" style="background-color: var(--stone-100);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">

            @if(!empty($missingSkills))
            {{-- Has skill gap data --}}
            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                {{-- Left: title + desc --}}
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-900 mb-1">Your Learning Snapshot</h2>
                    <p class="text-sm text-gray-500 mb-4">All content on this page is personalized from your skill gap analysis results.</p>
                    {{-- Skill pills --}}
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_slice($missingSkills, 0, 8) as $skill)
                            <span class="px-3 py-1 rounded-full text-xs font-medium" style="background-color: var(--sea-100); color: var(--sea-700);">{{ $skill }}</span>
                        @endforeach
                        @if(count($missingSkills) > 8)
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">+{{ count($missingSkills) - 8 }} more</span>
                        @endif
                    </div>
                </div>
                {{-- Right: stat boxes --}}
                <div class="flex flex-wrap gap-4">
                    <div class="lr-stat-box">
                        <span class="text-2xl font-bold" style="color: var(--sea-700);">{{ count($missingSkills) }}</span>
                        <span class="text-xs text-gray-500 font-medium">Skills to Learn</span>
                    </div>
                    @if(!empty($targetRole))
                    <div class="lr-stat-box" style="max-width: 130px;">
                        <svg class="h-5 w-5 mb-1" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-xs font-semibold text-gray-800 leading-tight text-center">{{ $targetRole }}</span>
                        <span class="text-xs text-gray-400">Target Role</span>
                    </div>
                    @endif
                    @if(!empty($targetCategory))
                    <div class="lr-stat-box">
                        <svg class="h-5 w-5 mb-1" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span class="text-xs font-semibold text-gray-800 leading-tight text-center">{{ $targetCategory }}</span>
                        <span class="text-xs text-gray-400">Category</span>
                    </div>
                    @endif
                </div>
            </div>

            @else
            {{-- No skill gap data yet --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-1">Your Learning Snapshot</h2>
                    <p class="text-sm text-gray-500">Run a skill gap analysis to personalize all content on this page.</p>
                </div>
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl flex-shrink-0" style="background-color: #fef9e7; border: 1px solid #f7e1a1;">
                    <svg class="h-5 w-5 flex-shrink-0" style="color: #b45309;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="mr-2">
                        <p class="text-sm font-medium" style="color: #92400e;">Run a skill gap analysis</p>
                        <p class="text-xs text-gray-500">to personalize all results on this page</p>
                    </div>
                    <a href="{{ route('pathfinder.skill-gap') }}" class="text-xs font-semibold px-3 py-2 rounded-lg text-white flex-shrink-0" style="background-color: var(--sea-500);">Start →</a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 4 · YOUTUBE PLAYLISTS              --}}
{{-- ═══════════════════════════════════════════ --}}
<div id="youtube" class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-start gap-3">
                <div class="lr-section-icon" style="background-color: #fee2e2;">
                    <svg class="h-5 w-5" style="color: #dc2626;" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">YouTube Playlists by Skill</h2>
                    <p class="text-sm text-gray-500">Each playlist is matched to a missing skill and filtered by your target role.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400 flex-shrink-0">
                <span class="inline-block w-2 h-2 rounded-full" style="background-color: var(--sea-500);"></span>
                Updated daily via YouTube API
            </div>
        </div>

        @if(!empty($youtubeRecommendations))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($youtubeRecommendations as $group)
            <div class="lr-yt-card">
                {{-- Red accent bar --}}
                <div class="h-1.5" style="background: linear-gradient(to right, #dc2626, #ef4444);"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-900">{{ $group['skill'] }}</h3>
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium" style="background-color: #fee2e2; color: #dc2626;">
                            {{ count($group['items']) }} {{ count($group['items']) === 1 ? 'Playlist' : 'Playlists' }}
                        </span>
                    </div>
                    <div class="space-y-3">
                        @foreach($group['items'] as $item)
                        <div class="flex items-start gap-3 p-3 rounded-xl" style="background-color: #fafafa; border: 1px solid #f3f4f6;">
                            {{-- Play icon --}}
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center mt-0.5" style="background-color: #fee2e2;">
                                <svg class="h-4 w-4" style="color: #dc2626;" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ $item['url'] }}" target="_blank" class="font-medium text-gray-900 hover:underline text-sm leading-snug block" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item['label'] }}</a>
                                @if(!empty($item['description']))
                                <p class="text-xs text-gray-500 mt-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $item['description'] }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    @if(!empty($item['channel']))
                                    <span class="flex items-center gap-1 text-xs text-gray-400">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ $item['channel'] }}
                                    </span>
                                    @endif
                                    @if(!empty($item['videoCount']))
                                    <span class="flex items-center gap-1 text-xs text-gray-400">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                                        {{ $item['videoCount'] }} videos
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        {{-- Empty state --}}
        <div class="rounded-2xl py-14 px-8 text-center" style="border: 2px dashed #e5e7eb;">
            <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #fee2e2;">
                <svg class="h-7 w-7" style="color: #dc2626;" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No playlists yet</h3>
            <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Run a skill gap analysis so we can generate personalized YouTube playlists matched to your missing skills.</p>
            <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center gap-2 px-6 py-3 text-white font-medium rounded-xl text-sm" style="background-color: var(--sea-500);">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Start Skill Gap Analysis
            </a>
        </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 5 · ARTICLES & NEWS (RSS FEEDS)    --}}
{{-- ═══════════════════════════════════════════ --}}
<div id="articles" class="py-12 bg-white" style="border-top: 1px solid #e5e7eb;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-start gap-3">
                <div class="lr-section-icon" style="background-color: var(--sea-100);">
                    <svg class="h-5 w-5" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Articles & Industry News</h2>
                    <p class="text-sm text-gray-500">
                        @if(!empty($targetRole))
                            Stay current with articles relevant to <span class="font-medium text-gray-700">{{ $targetRole }}</span> and your career path.
                        @else
                            Latest articles from top industry publications to keep your skills sharp.
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-400 flex-shrink-0">
                <svg class="h-3.5 w-3.5" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Powered by NewsAPI
            </div>
        </div>

        @if(!empty($articles))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($articles as $article)
                <a href="{{ $article['link'] ?? '#' }}" target="_blank" class="lr-article-card group block">
                    {{-- Accent bar --}}
                    <div class="h-1" style="background: linear-gradient(to right, var(--sea-500), var(--sea-300));"></div>
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="lr-source-pill" style="background: var(--sea-100); color: var(--sea-500); border-color: var(--sea-300);">
                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                {{ $article['source'] ?? 'Article' }}
                            </span>
                            @if(!empty($article['date']))
                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($article['date'])->diffForHumans() }}</span>
                            @endif
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2 leading-snug transition-colors" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $article['title'] ?? 'Untitled' }}</h3>
                        @if(!empty($article['description']))
                        <p class="text-xs text-gray-500 leading-relaxed mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ strip_tags($article['description']) }}</p>
                        @endif
                        <div class="flex items-center gap-1 mt-auto text-xs font-medium" style="color: var(--sea-500);">
                            Read article
                            <svg class="h-3 w-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

        @else
        {{-- Empty state --}}
        <div class="rounded-2xl py-14 px-8 text-center" style="border: 2px dashed #e5e7eb;">
            <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: var(--sea-100);">
                <svg class="h-7 w-7" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No articles available</h3>
            <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Run a skill gap analysis to see articles tailored to your target role, or check back later.</p>
        </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 6 · JOB SEARCH PLATFORMS           --}}
{{-- ═══════════════════════════════════════════ --}}
<div id="jobs" class="py-12" style="background-color: var(--stone-100);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-start gap-3">
                <div class="lr-section-icon" style="background-color: var(--sea-100);">
                    <svg class="h-5 w-5" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Job Search Platforms</h2>
                    <p class="text-sm text-gray-500">
                        @if(!empty($targetRole))
                            Click any platform to search for <span class="font-medium text-gray-700">"{{ $targetRole }}"</span> jobs in the Philippines.
                        @else
                            Browse top Philippine job platforms to find your next opportunity.
                        @endif
                    </p>
                </div>
            </div>
            @if(!empty($targetRole))
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold flex-shrink-0" style="background-color: var(--sea-100); color: var(--sea-700); border: 1px solid var(--sea-300);">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Auto-searching: {{ $targetRole }}
            </span>
            @endif
        </div>

        @php
            $encodedRole = urlencode($targetRole ?? '');
            $jobPlatforms = [
                [
                    'name' => 'Indeed Philippines',
                    'description' => 'Search millions of jobs in the Philippines updated daily from thousands of local and international companies.',
                    'base_url' => 'https://ph.indeed.com',
                    'search_url' => $encodedRole ? "https://ph.indeed.com/jobs?q={$encodedRole}" : 'https://ph.indeed.com',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
                ],
                [
                    'name' => 'LinkedIn Jobs',
                    'description' => 'Leverage your professional network to discover job opportunities in the Philippines.',
                    'base_url' => 'https://www.linkedin.com/jobs',
                    'search_url' => $encodedRole ? "https://www.linkedin.com/jobs/search/?keywords={$encodedRole}&location=Philippines" : 'https://www.linkedin.com/jobs/search/?location=Philippines',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2zM4 6a2 2 0 100-4 2 2 0 000 4z"/>',
                ],
                [
                    'name' => 'JobStreet',
                    'description' => 'The Philippines\' leading job portal connecting Filipino talent with top local and multinational employers.',
                    'base_url' => 'https://www.jobstreet.com.ph',
                    'search_url' => $encodedRole ? "https://www.jobstreet.com.ph/jobs?q={$encodedRole}" : 'https://www.jobstreet.com.ph',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'name' => 'Kalibrr',
                    'description' => 'A popular Philippine job platform matching skilled professionals with top companies across the country.',
                    'base_url' => 'https://www.kalibrr.com',
                    'search_url' => $encodedRole ? "https://www.kalibrr.com/job-board/te/{$encodedRole}/co/Philippines" : 'https://www.kalibrr.com/job-board/co/Philippines',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
                ],
                [
                    'name' => 'OnlineJobs.ph',
                    'description' => 'The largest Philippine remote work marketplace connecting Filipino professionals with global employers.',
                    'base_url' => 'https://www.onlinejobs.ph',
                    'search_url' => $encodedRole ? "https://www.onlinejobs.ph/jobseekers/search?jobkeyword={$encodedRole}" : 'https://www.onlinejobs.ph',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                ],
                [
                    'name' => 'Glassdoor',
                    'description' => 'Find jobs in the Philippines with salary insights, company reviews, and interview tips from employees.',
                    'base_url' => 'https://www.glassdoor.com',
                    'search_url' => $encodedRole ? "https://www.glassdoor.com/Job/philippines-{$encodedRole}-jobs-SRCH_IL.0,11_IN204_KO12," . (12 + strlen($targetRole ?? '')) . ".htm" : 'https://www.glassdoor.com/Job/philippines-jobs-SRCH_IL.0,11_IN204.htm',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($jobPlatforms as $platform)
            <a href="{{ $platform['search_url'] }}" target="_blank" class="lr-job-card group block">
                {{-- Color accent bar --}}
                <div class="h-1.5" style="background-color: var(--sea-500);"></div>
                <div class="p-5 flex flex-col flex-1">
                    {{-- Icon badge --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background-color: var(--sea-100);">
                        <svg class="h-5 w-5" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $platform['icon'] !!}</svg>
                    </div>
                    <div class="font-semibold text-gray-900 mb-1.5 text-sm transition-colors">{{ $platform['name'] }}</div>
                    <p class="text-xs text-gray-500 flex-1 leading-relaxed mb-4">{{ $platform['description'] }}</p>

                    @if(!empty($targetRole))
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg mb-3 text-xs" style="background-color: var(--sea-100); border: 1px solid var(--sea-300);">
                        <svg class="h-3.5 w-3.5 flex-shrink-0" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="truncate" style="color: var(--sea-700);">{{ $targetRole }}</span>
                    </div>
                    @endif

                    <div class="inline-flex items-center gap-1.5 text-xs font-semibold mt-auto" style="color: var(--sea-500);">
                        {{ !empty($targetRole) ? 'Search jobs' : 'Visit platform' }}
                        <svg class="h-3.5 w-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- SECTION 7 · CTA BANNER (shown if no data)  --}}
{{-- ═══════════════════════════════════════════ --}}
@if(empty($missingSkills))
<div class="py-12" style="background: linear-gradient(135deg, #fef9e7 0%, #fef3c7 100%); border-top: 1px solid #fde68a;">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-5" style="background-color: #f7e1a1;">
            <svg class="h-7 w-7" style="color: #b45309;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <h2 class="text-xl font-semibold text-gray-900 mb-3">Unlock Your Personalized Content</h2>
        <p class="text-sm text-gray-600 mb-7 max-w-lg mx-auto">Run a skill gap analysis to get matched YouTube playlists, curated articles, and job search results tailored specifically to your target role.</p>
        <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-white font-semibold rounded-xl text-sm" style="background-color: var(--sea-700);">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Start Skill Gap Analysis
        </a>
    </div>
</div>
@endif

@endsection
