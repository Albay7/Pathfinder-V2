@extends('pathfinder.layout')

@section('title', 'Your MBTI Results - Pathfinder')

@section('content')
@if(session('info'))
<div class="bg-blue-50 border-l-4 border-blue-400 px-6 py-4 text-blue-800 text-sm font-medium flex items-center gap-3">
    <svg class="h-5 w-5 flex-shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('info') }}
</div>
@endif
<style>
:root {
    --pf-navy: #13264D;
    --pf-sky:  #5AA7C6;
    --pf-sky-light: #e8f4f9;
    --pf-sky-mid:   #c2e0ef;
    --pf-accent-green: #10B981;
    --pf-accent-purple: #8B5CF6;
}

/* ── Hero ─────────────────────────────────────── */
.mbti-hero {
    background: linear-gradient(135deg, var(--pf-navy) 0%, var(--pf-sky) 100%);
    padding: 5rem 0 4rem;
    position: relative;
    overflow: hidden;
    text-align: center;
}
.mbti-hero-content { position: relative; z-index: 1; max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; }
.mbti-hero h1 { font-size: clamp(2.5rem, 6vw, 4rem); font-weight: 850; color: #fff; line-height: 1.1; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
.mbti-hero .type-subtitle { font-size: 1.25rem; font-weight: 600; color: rgba(255,255,255,0.85); margin-bottom: 3.5rem; text-transform: uppercase; letter-spacing: 0.15em; }

/* ── Stats strip ─────────────────────────────── */
.mbti-stats {
    display: flex; flex-wrap: wrap; justify-content: center;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 16px; margin-top: 1.5rem; overflow: hidden;
    backdrop-filter: blur(8px);
}
.mbti-stat { flex: 0 1 240px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; border-right: 1px solid rgba(255,255,255,0.1); text-align: left; }
.mbti-stat:last-child { border-right: none; }
.mbti-stat-icon { width: 42px; height: 42px; border-radius: 10px; background: rgba(255,255,255,0.12); display: grid; place-items: center; flex-shrink: 0; }
.mbti-stat-icon svg { width: 22px; height: 22px; color: #fff; }
.mbti-stat-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,0.6); margin-bottom: 0.1rem; }
.mbti-stat-val { font-size: 1rem; font-weight: 700; color: #fff; }

/* ── Layout ──────────────────────────────────── */
.mbti-body { background: #f8fafc; padding: 3rem 0 4rem; }
.mbti-container { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem; }
@media(max-width:1024px){ .mbti-container { grid-template-columns: 1fr; } }

/* ── Content Cards ───────────────────────────── */
.mbti-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 20px rgba(19,38,77,0.04); border: 1px solid #f1f5f9; overflow: hidden; margin-bottom: 2rem; }
.mbti-card-head { padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.8rem; }
.mbti-card-head h2 { font-size: 1.25rem; font-weight: 800; color: var(--pf-navy); margin: 0; }
.mbti-card-head .icon-bg { width: 36px; height: 36px; border-radius: 8px; background: var(--pf-sky-light); color: var(--pf-sky); display: grid; place-items: center; }
.mbti-card-body { padding: 2rem; }

/* ── Typography and Text ──────────────────────── */
.mbti-prose { font-size: 1.05rem; line-height: 1.8; color: #334155; }
.mbti-italic-box { background: #f1f5f9; border-left: 4px solid var(--pf-navy); padding: 1.5rem; border-radius: 0 12px 12px 0; font-style: italic; color: #1e293b; }

/* ── Sidebar Widgets ─────────────────────────── */
.mbti-widget { background: #fff; border-radius: 20px; box-shadow: 0 4px 15px rgba(19,38,77,0.03); border: 1px solid #f1f5f9; overflow: hidden; margin-bottom: 1.5rem; }
.mbti-widget-head { padding: 1.25rem 1.5rem; background: var(--pf-navy); display: flex; align-items: center; gap: 0.75rem; }
.mbti-widget-head h3 { font-size: 1rem; font-weight: 700; color: #fff; margin:0; }
.mbti-widget-head svg { width: 1.2rem; height: 1.2rem; color: var(--pf-sky); }
.mbti-widget-body { padding: 1.5rem; }

/* ── Trait Bars ───────────────────────────────── */
.trait-row { margin-bottom: 1.5rem; }
.trait-row:last-child { margin-bottom: 0; }
.trait-labels { display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #475569; margin-bottom: 0.5rem; }
.trait-bar-wrap { height: 12px; background: #e2e8f0; border-radius: 999px; display: flex; overflow: hidden; border: 1px solid #cbd5e1; }
.trait-fill { height: 100%; transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
.trait-pcts { display: flex; justify-content: space-between; font-size: 10px; font-weight: 800; color: #64748b; margin-top: 0.3rem; }

/* ── Strengths/Growth Grid ───────────────────── */
.insight-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
@media(max-width:640px){ .insight-grid { grid-template-columns: 1fr; } }
.insight-card { padding: 2rem; border-radius: 20px; border: 1px solid #f1f5f9; transition: transform 0.2s; position: relative; overflow: hidden; }
.insight-card:hover { transform: translateY(-4px); }
.insight-card.strength { background: #f0f9ff; border-color: #e0f2fe; }
.insight-card.growth { background: #f8fafc; border-color: #f1f5f9; }
.insight-title { font-size: 1.15rem; font-weight: 850; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; letter-spacing: -0.01em; }
.insight-title svg { width: 32px; height: 32px; }
.insight-list { list-style: none; padding: 0; margin: 0; }
.insight-list li { font-size: 1rem; color: #334155; margin-bottom: 0.85rem; display: flex; align-items: flex-start; gap: 0.75rem; font-weight: 500; }
.insight-list li svg { width: 1.25rem; height: 1.25rem; flex-shrink: 0; margin-top: 3px; }

/* ── Career Recommendations ───────────────────── */
.career-section-dark { background: #0f172a; padding: 6rem 0; border-top: 1px solid #1e293b; }
.career-list-container { background: #1e293b; border-radius: 32px; border: 1px solid #334155; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
.career-item { display: flex; align-items: center; gap: 1.5rem; padding: 2rem; border-bottom: 1px solid #334155; transition: all 0.3s; }
.career-item:last-child { border-bottom: none; }
.career-item:hover { background: #24344d; }
.career-rank-pill { padding: 0.35rem 0.85rem; background: var(--pf-sky); color: var(--pf-navy); font-size: 0.75rem; font-weight: 850; border-radius: 999px; text-transform: uppercase; }
.career-info { flex: 1; }
.career-info h3 { font-size: 1.25rem; font-weight: 850; color: #fff; margin-bottom: 0.25rem; }
.career-info p { color: #cbd5e1; font-size: 0.85rem; line-height: 1.6; max-width: 600px; }
.career-action { flex-shrink: 0; }

/* ── Buttons ──────────────────────────────────── */
.mbti-btn-retake {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700;
    font-size: 0.9rem; text-decoration: none; border: 2px solid var(--pf-navy);
    color: var(--pf-navy); background: transparent; transition: all 0.2s;
}
/* ── Professional Note ────────────────────────── */
.mbti-note-box { background: rgba(255, 255, 255, 0.03); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.mbti-note-title { color: #60a5fa !important; font-size: 11px; font-weight: 900; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 0.25rem; display: block; }
.mbti-note-text { color: #f8fafc !important; font-size: 0.8rem; line-height: 1.6; font-weight: 500; }

.mbti-btn-retake:hover { background: var(--pf-navy); color: #fff; }
</style>

{{-- ═══════════════════ MBTI HERO ═══════════════════ --}}
<div class="mbti-hero">
    <div class="mbti-hero-content">
        <div class="type-subtitle">YOUR MBTI ASSESSMENT RESULT</div>
        <h1>{{ $personalityType ? $personalityType->name : $mbtiType }}</h1>
        <div class="text-white/60 font-bold tracking-[0.4em] text-2xl mt-4">{{ $mbtiType }}</div>

        <div class="mt-12">
            <a href="{{ route('pathfinder.mbti.retake') }}" 
               class="inline-flex items-center gap-2 px-8 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold border border-white/20 transition-all backdrop-blur-sm"
               onclick="return confirm('Are you sure you want to retake?')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Retake Assessment
            </a>
        </div>
    </div>
</div>

{{-- ═══════════════════ MBTI BODY ═══════════════════ --}}
<div class="mbti-body">
    <div class="mbti-container">
        {{-- Left Column: Narrative & Insights --}}
        <div>
            {{-- About the Type Card --}}
            <div class="mbti-card">
                <div class="mbti-card-head">
                    <div class="icon-bg">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h2>Psychological Deep Dive</h2>
                </div>
                <div class="mbti-card-body">
                    <div class="mbti-prose mb-8">
                        {{ $personalityType ? $personalityType->description : $mbtiDescription }}
                    </div>
                    @if($personalityType)
                    <div class="mbti-italic-box">
                        <span class="font-bold block mb-1 text-xs uppercase tracking-wider text-slate-500">Core Philosophy</span>
                        "{{ $personalityType->workplace_habits }}"
                    </div>
                    @endif
                </div>
            </div>


            {{-- Re-integrated Career Recommendations --}}
            <div class="mt-4">
                <!-- Consolidated List (Integrated Theme) -->
                <div class="career-list-container">
                    <!-- Integrated Note Section -->
                    <div class="px-8 py-6 mbti-note-box flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-500/20">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="mbti-note-title">Professional Note:</span>
                            <p class="mbti-note-text">
                                These roles are strategically mapped to your cognitive architecture and decision-making style to ensure long-term professional fulfillment. Match is based on how your personality handles abstract patterns and long-term planning.
                            </p>
                        </div>
                    </div>

                    @foreach($careerRecommendations as $index => $career)
                        @php $careerName = is_array($career) ? $career['name'] : $career; @endphp
                        <div class="career-item">
                            <div class="flex-shrink-0">
                                <span class="career-rank-pill">#{{ $index + 1 }}</span>
                            </div>
                            
                            <div class="career-info">
                                <h3 class="!text-lg">{{ $careerName }}</h3>
                                <p class="!text-sm line-clamp-2">
                                    {{ is_array($career) ? $career['description'] : "High-level strategic planning and objective logic inherent in the {$mbtiType} type." }}
                                </p>
                            </div>

                            <div class="career-action">
                                <a href="{{ route('pathfinder.career.details', ['career' => urlencode($careerName)]) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-lg text-xs font-bold border border-white/10 transition-all">
                                    Roadmap
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right Column: Sidebars --}}
        <div>
            {{-- Trait Profile Widget --}}
            <div class="mbti-widget">
                <div class="mbti-widget-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <h3>Trait Profile</h3>
                </div>
                <div class="mbti-widget-body">
                    @php
                        $traits = [
                            ['label' => ['Extraversion', 'Introversion'], 'scores' => [$mbtiScores['E_I']['E'], $mbtiScores['E_I']['I']], 'colors' => ['#6366f1', '#1e293b']],
                            ['label' => ['Sensing', 'Intuitive'], 'scores' => [$mbtiScores['S_N']['S'], $mbtiScores['S_N']['N']], 'colors' => ['#14b8a6', '#3b82f6']],
                            ['label' => ['Thinking', 'Feeling'], 'scores' => [$mbtiScores['T_F']['T'], $mbtiScores['T_F']['F']], 'colors' => ['#f59e0b', '#ec4899']],
                            ['label' => ['Judging', 'Prospecting'], 'scores' => [$mbtiScores['J_P']['J'], $mbtiScores['J_P']['P']], 'colors' => ['#8b5cf6', '#06b6d4']],
                        ];
                    @endphp
                    @foreach($traits as $trait)
                        <div class="trait-row">
                            <div class="trait-labels">
                                <span>{{ $trait['label'][0] }}</span>
                                <span>{{ $trait['label'][1] }}</span>
                            </div>
                            <div class="trait-bar-wrap">
                                <div class="trait-fill" style="width: {{ $trait['scores'][0] }}%; background: {{ $trait['colors'][0] }};"></div>
                                <div class="trait-fill" style="width: {{ $trait['scores'][1] }}%; background: {{ $trait['colors'][1] }};"></div>
                            </div>
                            <div class="trait-pcts">
                                <span>{{ $trait['scores'][0] }}%</span>
                                <span>{{ $trait['scores'][1] }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Learning Style Widget --}}
            <div class="mbti-widget">
                <div class="mbti-widget-head" style="background: #1e293b;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <h3>Learning Signature</h3>
                </div>
                <div class="mbti-widget-body">
                    <div class="font-bold text-slate-800 text-sm mb-2">{{ $learningStyle['style'] }}</div>
                    <p class="text-[0.85rem] text-slate-500 leading-relaxed mb-4">{{ $learningStyle['description'] }}</p>
                    <div class="space-y-2">
                        @foreach(array_slice($learningStyle['recommendations'], 0, 3) as $rec)
                            <div class="flex items-center gap-2 text-[0.7rem] font-bold text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                {{ $rec }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Signature Strengths Widget --}}
            @if($personalityType)
            <div class="mbti-widget">
                <div class="mbti-widget-head" style="background: #f0f9ff; border-bottom: 1px solid #e0f2fe;">
                    <svg class="text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 style="color: #1e3a8a !important;">Signature Strengths</h3>
                </div>
                <div class="mbti-widget-body">
                    <ul class="insight-list">
                        @foreach(explode(',', $personalityType->strengths) as $strength)
                            <li class="!text-sm" style="color: #1e3a8a !important; font-weight: 600;">
                                <svg class="text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                {{ trim($strength) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Growth Opportunities Widget --}}
            <div class="mbti-widget">
                <div class="mbti-widget-head" style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                    <svg class="text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <h3 style="color: #0f172a !important;">Growth Opportunities</h3>
                </div>
                <div class="mbti-widget-body">
                    <ul class="insight-list">
                        @foreach(explode(',', $personalityType->weaknesses) as $weakness)
                            <li class="!text-sm" style="color: #334155 !important; font-weight: 600;">
                                <svg class="text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                {{ trim($weakness) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>



<!-- Personalized Course Recommendations -->
@if(Auth::check() && count($courseRecommendations) > 0)
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Upskilling for your {{ $mbtiType }} Type</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                These specialized tracks are selected based on your natural learning style and the cognitive skills required for your top matches.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($courseRecommendations as $course)
                <div class="mbti-card hover:border-blue-400 transition-all duration-300">
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                {{ $course['compatibility_score'] }}% Match
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $course['provider'] }}</span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 mb-4 line-clamp-2">{{ $course['title'] }}</h3>
                        
                        @if($course['compatibility_explanation'])
                        <div class="bg-slate-50 border-l-4 border-slate-200 p-4 mb-6">
                            <p class="text-[0.75rem] text-slate-600 leading-relaxed italic">
                                "{{ $course['compatibility_explanation'] }}"
                            </p>
                        </div>
                        @endif

                        <a href="{{ $course['url'] }}" target="_blank" 
                           class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                            Enroll in Path
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Personalized Job Recommendations -->
@if(Auth::check() && count($jobRecommendations) > 0)
<div class="py-20 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Active Opportunities</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                We've identified real-world roles currently available that match your {{ $mbtiType }} temperament and professional strengths.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($jobRecommendations as $job)
                <div class="mbti-card hover:shadow-xl transition-all duration-300">
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $job['title'] }}</h3>
                                <div class="flex items-center gap-2 text-sm text-slate-500 font-medium">
                                    <span>{{ $job['company'] }}</span>
                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                    <span>{{ $job['location'] }}</span>
                                </div>
                            </div>
                            <div class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                {{ $job['compatibility_score'] }}% Fit
                            </div>
                        </div>

                        @if($job['salary_range'])
                        <div class="mb-6 inline-flex items-center gap-2 px-3 py-1 bg-green-50 text-green-700 rounded-lg text-xs font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $job['salary_range'] }}
                        </div>
                        @endif

                        @if($job['compatibility_explanation'])
                        <div class="bg-slate-50 border-l-4 border-slate-200 p-4 mb-8">
                            <p class="text-sm text-slate-600 leading-relaxed italic">
                                "{{ $job['compatibility_explanation'] }}"
                            </p>
                        </div>
                        @endif

                        <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                            <a href="{{ $job['url'] }}" target="_blank" 
                               class="flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                Application Portal
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif


@endsection
