@extends('pathfinder.layout')

@section('title', 'My Learning Journey - Pathfinder')
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
        --stone-100: #f6f7f9;
    }
    .lj-stat-card { background: white; border-radius: 14px; border: 1px solid #e5e7eb; padding: 18px 20px; transition: box-shadow 0.2s; }
    .lj-stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
    .lj-section-card { background: white; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden; }
    .lj-resource-item { background: var(--stone-100); border-radius: 12px; padding: 16px; border: 1px solid #f3f4f6; transition: box-shadow 0.2s, transform 0.2s; }
    .lj-resource-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); transform: translateY(-1px); }
    .lj-skill-card { background: white; border-radius: 14px; border: 1px solid #e5e7eb; padding: 18px; transition: box-shadow 0.2s; }
    .lj-skill-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
    .lj-type-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 999px; font-size: 0.68rem; font-weight: 600; }
    .lj-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; font-size: 0.8rem; font-weight: 600; transition: all 0.2s; cursor: pointer; border: none; }
    .lj-btn-primary { background: var(--sea-500); color: white; }
    .lj-btn-primary:hover { background: var(--sea-700); }
    .lj-btn-success { background: #059669; color: white; }
    .lj-btn-success:hover { background: #047857; }
    .lj-btn-outline { background: white; color: var(--sea-700); border: 1px solid var(--sea-300); }
    .lj-btn-outline:hover { background: var(--sea-100); }
    .lj-btn-danger { background: white; color: #dc2626; border: 1px solid #fecaca; }
    .lj-btn-danger:hover { background: #fef2f2; }
    .lj-skill-card { cursor: pointer; }
    .lj-skill-card.active { border-color: var(--sea-500); box-shadow: 0 0 0 3px var(--sea-100); }
    .lj-resource-item.filtered-out { display: none !important; }
    .lj-filter-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; font-size: 0.78rem; font-weight: 500; background: var(--sea-100); color: var(--sea-700); border: 1px solid var(--sea-300); }
    .lj-filter-badge button { background: none; border: none; cursor: pointer; color: var(--sea-500); font-weight: 700; font-size: 0.85rem; padding: 0 2px; line-height: 1; }
    .lj-filter-badge button:hover { color: var(--sea-900); }
</style>

{{-- ═══════════════════════════════════════════ --}}
{{-- HERO                                        --}}
{{-- ═══════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="background: linear-gradient(160deg, #1a5276 0%, #154360 25%, #0f2742 55%, #0b1c33 100%);">
    <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 20px 20px;"></div>
    <div class="absolute top-0 right-0 w-96 h-96 opacity-20 rounded-full" style="background: radial-gradient(circle, var(--sea-400) 0%, transparent 70%); filter: blur(60px); transform: translate(30%, -30%);"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="text-center text-white max-w-3xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-bold mb-3" style="letter-spacing: -0.02em;">My Learning Journey</h1>
            <p class="text-base mb-5" style="color: rgba(215,238,248,0.8); line-height: 1.7;">
                Track your tutorials and saved resources from your personalized learning path.
            </p>
            @if(!empty($targetRole))
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); color: white;">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Target: {{ $targetRole }}
            </span>
            @endif
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- STATS ROW                                   --}}
{{-- ═══════════════════════════════════════════ --}}
<div class="py-8" style="background-color: var(--stone-100);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="lj-stat-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #dbeafe;">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">In Progress</p>
                    </div>
                </div>
            </div>
            <div class="lj-stat-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #d1fae5;">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">Completed</p>
                    </div>
                </div>
            </div>
            <div class="lj-stat-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #fef3c7;">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['bookmarked'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">Bookmarked</p>
                    </div>
                </div>
            </div>
            <div class="lj-stat-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--sea-100);">
                        <svg class="h-5 w-5" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['saved_resources'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">Saved Resources</p>
                    </div>
                </div>
            </div>
            <div class="lj-stat-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #ede9fe;">
                        <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['skills_tracked'] }}</p>
                        <p class="text-xs text-gray-500 font-medium">Skills Tracked</p>
                    </div>
                </div>
            </div>
            <div class="lj-stat-card">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #fce7f3;">
                        <svg class="h-5 w-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['total_time'] }}m</p>
                        <p class="text-xs text-gray-500 font-medium">Total Time</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- QUICK LINKS                                 --}}
{{-- ═══════════════════════════════════════════ --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-medium text-gray-400 uppercase tracking-wider mr-1">Quick Links</span>
            <a href="{{ route('pathfinder.external-resources') }}" class="lj-btn lj-btn-primary">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Browse Learning Resources
            </a>
            <a href="{{ route('pathfinder.skill-gap') }}" class="lj-btn lj-btn-outline">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Run Skill Gap Analysis
            </a>
        </div>
    </div>
</div>

<div class="py-10 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SKILL PROGRESS CARDS                        --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if(!empty($missingSkills) && !empty($skillProgress))
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: #ede9fe;">
                    <svg class="h-4.5 w-4.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Skill Progress</h2>
                    <p class="text-xs text-gray-500">Track resources saved per missing skill from your analysis.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($skillProgress as $skill => $progress)
                <div class="lj-skill-card" data-skill="{{ $skill }}" onclick="filterBySkill('{{ addslashes($skill) }}')">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $skill }}</h3>
                        @if($progress['total'] > 0)
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background: {{ $progress['completed'] === $progress['total'] ? '#d1fae5' : 'var(--sea-100)' }}; color: {{ $progress['completed'] === $progress['total'] ? '#059669' : 'var(--sea-700)' }};">
                            {{ $progress['completed'] }}/{{ $progress['total'] }}
                        </span>
                        @endif
                    </div>
                    @if($progress['total'] > 0)
                    <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                        <div class="h-2 rounded-full transition-all" style="width: {{ $progress['total'] > 0 ? round(($progress['completed'] / $progress['total']) * 100) : 0 }}%; background: {{ $progress['completed'] === $progress['total'] ? '#059669' : 'var(--sea-500)' }};"></div>
                    </div>
                    <p class="text-xs text-gray-400">{{ $progress['completed'] }} of {{ $progress['total'] }} resources completed</p>
                    @else
                    <p class="text-xs text-gray-400">No resources saved yet for this skill.</p>
                    <a href="{{ route('pathfinder.external-resources') }}" class="text-xs font-medium mt-2 inline-block" style="color: var(--sea-500);" onclick="event.stopPropagation();">Find resources &rarr;</a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Skill filter badge (shown when filtering) --}}
        <div id="lj-filter-bar" class="hidden">
            <span class="lj-filter-badge">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filtering by: <strong id="lj-filter-skill"></strong>
                <button onclick="clearSkillFilter()" title="Clear filter">&times;</button>
            </span>
        </div>

        {{-- ═══════════════════════════════════════════ --}}
        {{-- CONTINUE LEARNING (tutorials + resources)   --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if($inProgress->count() > 0 || $inProgressResources->count() > 0)
        <div class="lj-section-card">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: #dbeafe;">
                        <svg class="h-4.5 w-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Continue Learning</h2>
                        <p class="text-xs text-gray-500">Tutorials and resources you're currently working on.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- In-progress tutorials --}}
                    @foreach($inProgress as $progress)
                    <div class="lj-resource-item">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="lj-type-badge" style="background: #dbeafe; color: #1d4ed8;">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                Tutorial
                            </span>
                            @if($progress->tutorial && $progress->tutorial->level)
                            <span class="text-xs text-gray-400">{{ ucfirst($progress->tutorial->level) }}</span>
                            @endif
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1.5">{{ $progress->tutorial->title ?? 'Untitled' }}</h4>
                        <p class="text-xs text-gray-500 mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $progress->tutorial->description ?? '' }}</p>
                        {{-- Progress bar --}}
                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Progress</span>
                                <span>{{ $progress->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full" style="width: {{ $progress->progress_percentage }}%; background: var(--sea-500);"></div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ $progress->tutorial->url ?? '#' }}" target="_blank" class="lj-btn lj-btn-primary flex-1 justify-center">Continue</a>
                            <form action="{{ route('tutorials.complete') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tutorial_id" value="{{ $progress->tutorial->id ?? '' }}">
                                <button type="submit" class="lj-btn lj-btn-success">Done</button>
                            </form>
                        </div>
                    </div>
                    @endforeach

                    {{-- In-progress resources --}}
                    @foreach($inProgressResources as $resource)
                    <div class="lj-resource-item" data-skill="{{ $resource->skill ?? '' }}">
                        <div class="flex items-center gap-2 mb-3">
                            @if($resource->resource_type === 'youtube_playlist')
                            <span class="lj-type-badge" style="background: #ccfbf1; color: #0d9488;">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                YouTube
                            </span>
                            @else
                            <span class="lj-type-badge" style="background: var(--sea-100); color: var(--sea-700);">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                Article
                            </span>
                            @endif
                            @if($resource->skill)
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background: var(--sea-100); color: var(--sea-700);">{{ $resource->skill }}</span>
                            @endif
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1.5" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $resource->title }}</h4>
                        @if($resource->source)
                        <p class="text-xs text-gray-400 mb-3">{{ $resource->source }}</p>
                        @endif
                        <div class="flex gap-2">
                            <a href="{{ $resource->url }}" target="_blank" class="lj-btn lj-btn-primary flex-1 justify-center">Open</a>
                            <button class="lj-btn lj-btn-success" onclick="completeResource('{{ $resource->url }}', this)">Done</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- SAVED RESOURCES                             --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if($savedResources->count() > 0)
        <div class="lj-section-card">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: var(--sea-100);">
                        <svg class="h-4.5 w-4.5" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Saved Resources</h2>
                        <p class="text-xs text-gray-500">Resources you've saved from the Learning Resources page.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($savedResources as $resource)
                    <div class="lj-resource-item" id="resource-{{ $resource->id }}" data-skill="{{ $resource->skill ?? '' }}">
                        <div class="flex items-center gap-2 mb-3">
                            @if($resource->resource_type === 'youtube_playlist')
                            <span class="lj-type-badge" style="background: #ccfbf1; color: #0d9488;">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                YouTube
                            </span>
                            @elseif($resource->resource_type === 'article')
                            <span class="lj-type-badge" style="background: var(--sea-100); color: var(--sea-700);">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                Article
                            </span>
                            @else
                            <span class="lj-type-badge" style="background: #fef3c7; color: #92400e;">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Job Platform
                            </span>
                            @endif
                            @if($resource->skill)
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background: var(--sea-100); color: var(--sea-700);">{{ $resource->skill }}</span>
                            @endif
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $resource->title }}</h4>
                        @if($resource->source)
                        <p class="text-xs text-gray-400 mb-1">{{ $resource->source }}</p>
                        @endif
                        @if($resource->description)
                        <p class="text-xs text-gray-500 mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $resource->description }}</p>
                        @else
                        <div class="mb-3"></div>
                        @endif
                        <div class="flex gap-2">
                            <button class="lj-btn lj-btn-primary flex-1 justify-center" onclick="startResource('{{ $resource->url }}', this)">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Start
                            </button>
                            <button class="lj-btn lj-btn-danger" onclick="removeResource('{{ $resource->url }}', {{ $resource->id }})" title="Remove">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- BOOKMARKED TUTORIALS                        --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if($bookmarked->count() > 0)
        <div class="lj-section-card">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: #fef3c7;">
                        <svg class="h-4.5 w-4.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Bookmarked Tutorials</h2>
                        <p class="text-xs text-gray-500">Tutorials you've saved for later.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($bookmarked as $progress)
                    <div class="lj-resource-item">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="lj-type-badge" style="background: #dbeafe; color: #1d4ed8;">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                Tutorial
                            </span>
                            @if($progress->tutorial && $progress->tutorial->level)
                            <span class="text-xs text-gray-400">{{ ucfirst($progress->tutorial->level) }}</span>
                            @endif
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1.5">{{ $progress->tutorial->title ?? 'Untitled' }}</h4>
                        <p class="text-xs text-gray-500 mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $progress->tutorial->description ?? '' }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-400 mb-3">
                            <span>{{ $progress->tutorial->formatted_duration ?? '' }}</span>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('tutorials.start') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="tutorial_id" value="{{ $progress->tutorial->id ?? '' }}">
                                <button type="submit" class="lj-btn lj-btn-primary w-full justify-center">Start Learning</button>
                            </form>
                            <form action="{{ route('tutorials.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="tutorial_id" value="{{ $progress->tutorial->id ?? '' }}">
                                <button type="submit" class="lj-btn lj-btn-danger" title="Remove">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- COMPLETED (tutorials + resources)           --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if($completed->count() > 0 || $completedResources->count() > 0)
        <div class="lj-section-card">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: #d1fae5;">
                        <svg class="h-4.5 w-4.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Completed</h2>
                        <p class="text-xs text-gray-500">Tutorials and resources you've finished.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Completed tutorials --}}
                    @foreach($completed as $progress)
                    <div class="lj-resource-item" style="border-left: 3px solid #059669;">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="lj-type-badge" style="background: #d1fae5; color: #059669;">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Tutorial
                            </span>
                            @if($progress->completed_at)
                            <span class="text-xs text-gray-400">{{ $progress->completed_at->format('M j, Y') }}</span>
                            @endif
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1.5">{{ $progress->tutorial->title ?? 'Untitled' }}</h4>
                        @if($progress->user_rating)
                        <div class="flex items-center gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="h-3.5 w-3.5 {{ $i <= $progress->user_rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        @endif
                        <a href="{{ $progress->tutorial->url ?? '#' }}" target="_blank" class="text-xs font-medium inline-flex items-center gap-1" style="color: var(--sea-500);">
                            Review
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    @endforeach

                    {{-- Completed resources --}}
                    @foreach($completedResources as $resource)
                    <div class="lj-resource-item" data-skill="{{ $resource->skill ?? '' }}" style="border-left: 3px solid #059669;">
                        <div class="flex items-center gap-2 mb-3">
                            @if($resource->resource_type === 'youtube_playlist')
                            <span class="lj-type-badge" style="background: #d1fae5; color: #059669;">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                YouTube
                            </span>
                            @else
                            <span class="lj-type-badge" style="background: #d1fae5; color: #059669;">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Article
                            </span>
                            @endif
                            @if($resource->completed_at)
                            <span class="text-xs text-gray-400">{{ $resource->completed_at->format('M j, Y') }}</span>
                            @endif
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-1.5" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $resource->title }}</h4>
                        @if($resource->skill)
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium mb-2 inline-block" style="background: var(--sea-100); color: var(--sea-700);">{{ $resource->skill }}</span>
                        @endif
                        <a href="{{ $resource->url }}" target="_blank" class="text-xs font-medium inline-flex items-center gap-1 mt-1" style="color: var(--sea-500);">
                            Review
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{-- EMPTY STATE                                 --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if($inProgress->count() === 0 && $bookmarked->count() === 0 && $completed->count() === 0 && $savedResources->count() === 0 && $inProgressResources->count() === 0 && $completedResources->count() === 0)
        <div class="lj-section-card">
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5" style="background: var(--sea-100);">
                    <svg class="h-8 w-8" style="color: var(--sea-500);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Your learning journey starts here</h3>
                <p class="text-sm text-gray-500 mb-7 max-w-md mx-auto">Browse learning resources to find YouTube playlists and articles, then save them to track your progress right here.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('pathfinder.external-resources') }}" class="lj-btn lj-btn-primary justify-center px-6 py-3">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Browse Learning Resources
                    </a>
                    <a href="{{ route('pathfinder.skill-gap') }}" class="lj-btn lj-btn-outline justify-center px-6 py-3">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Run Skill Gap Analysis
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ═══════════════════════════════════════════ --}}
{{-- JAVASCRIPT FOR RESOURCE ACTIONS             --}}
{{-- ═══════════════════════════════════════════ --}}
<script>
    const csrf = '{{ csrf_token() }}';

    function startResource(url, btn) {
        fetch('/resources/start', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ url: url }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { location.reload(); }
        })
        .catch(() => {});
    }

    function completeResource(url, btn) {
        fetch('/resources/complete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ url: url }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) { location.reload(); }
        })
        .catch(() => {});
    }

    function removeResource(url, id) {
        fetch('/resources/unsave', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ url: url }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById('resource-' + id);
                if (el) { el.style.transition = 'opacity 0.3s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }
            }
        })
        .catch(() => {});
    }

    // Skill filtering
    let activeSkillFilter = null;

    function filterBySkill(skill) {
        const filterBar = document.getElementById('lj-filter-bar');
        const filterSkillEl = document.getElementById('lj-filter-skill');
        const skillCards = document.querySelectorAll('.lj-skill-card');
        const resourceItems = document.querySelectorAll('.lj-resource-item');

        // Toggle off if same skill clicked again
        if (activeSkillFilter === skill) {
            clearSkillFilter();
            return;
        }

        activeSkillFilter = skill;

        // Highlight active skill card
        skillCards.forEach(card => {
            card.classList.toggle('active', card.getAttribute('data-skill') === skill);
        });

        // Filter resource items
        resourceItems.forEach(item => {
            const itemSkill = item.getAttribute('data-skill');
            // Show items that match the skill, or items with no skill (tutorials)
            if (!itemSkill || itemSkill === skill) {
                item.classList.remove('filtered-out');
            } else {
                item.classList.add('filtered-out');
            }
        });

        // Show filter badge
        filterSkillEl.textContent = skill;
        filterBar.classList.remove('hidden');

        // Scroll to first visible resource section
        const firstSection = document.querySelector('.lj-section-card');
        if (firstSection) {
            firstSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function clearSkillFilter() {
        activeSkillFilter = null;
        const filterBar = document.getElementById('lj-filter-bar');
        const skillCards = document.querySelectorAll('.lj-skill-card');
        const resourceItems = document.querySelectorAll('.lj-resource-item');

        // Remove active state from all skill cards
        skillCards.forEach(card => card.classList.remove('active'));

        // Show all resource items
        resourceItems.forEach(item => item.classList.remove('filtered-out'));

        // Hide filter badge
        filterBar.classList.add('hidden');
    }
</script>
@endsection
