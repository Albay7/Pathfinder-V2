@extends('pathfinder.layout')

@section('title', 'CV Analysis Results - Pathfinder')

@section('content')
<style>
:root {
    --pf-navy: #13264D;
    --pf-sky:  #5AA7C6;
    --pf-sky-light: #e8f4f9;
    --pf-sky-mid:   #c2e0ef;
}

/* ── Hero ─────────────────────────────────────── */
.cd-hero {
    background: linear-gradient(135deg, var(--pf-navy) 0%, #1e3f7a 50%, var(--pf-sky) 100%);
    padding: 3.5rem 0 3rem;
    position: relative;
    overflow: hidden;
}
.cd-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.cd-hero-inner { position: relative; z-index: 1; max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }
.cd-breadcrumb { font-size: 0.8rem; color: rgba(255,255,255,0.65); margin-bottom: 1rem; }
.cd-breadcrumb a { color: rgba(255,255,255,0.75); text-decoration: none; }
.cd-breadcrumb a:hover { color: #fff; }
.cd-breadcrumb span { margin: 0 0.4rem; }
.cd-hero h1 { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 800; color: #fff; margin: 0 0 0.6rem; }
.cd-hero .tagline { font-size: 1.05rem; color: rgba(255,255,255,0.80); max-width: 540px; margin: 0; }

/* ── Stats strip ─────────────────────────────── */
.cd-stats {
    display: flex; flex-wrap: wrap; gap: 0;
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 12px; margin-top: 2rem; overflow: hidden;
}
.cd-stat { flex: 1 1 160px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; border-right: 1px solid rgba(255,255,255,0.12); }
.cd-stat:last-child { border-right: none; }
.cd-stat-icon { width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.15); display: grid; place-items: center; flex-shrink: 0; }
.cd-stat-icon svg { width: 18px; height: 18px; color: #fff; }
.cd-stat-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: .06em; color: rgba(255,255,255,0.55); }
.cd-stat-val { font-size: 1rem; font-weight: 700; color: #fff; }

/* ── Layout ──────────────────────────────────── */
.cd-body { background: #f3f6f9; padding: 2.5rem 0 3rem; }
.cd-body-inner { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; display: grid; grid-template-columns: 1fr 340px; gap: 1.75rem; }
@media(max-width:900px){ .cd-body-inner{ grid-template-columns:1fr; } }

/* ── Cards ───────────────────────────────────── */
.cd-card { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(19,38,77,0.07); overflow: hidden; margin-bottom: 1.5rem; border: 1px solid #edf2f7; }
.cd-card:last-child { margin-bottom: 0; }
.cd-card-head { padding: 1.1rem 1.4rem; border-bottom: 1px solid #edf2f7; display: flex; align-items: center; gap: 0.6rem; }
.cd-card-head svg { width: 20px; height: 20px; color: var(--pf-sky); flex-shrink: 0; }
.cd-card-head h2 { font-size: 1rem; font-weight: 700; color: var(--pf-navy); margin: 0; }
.cd-card-body { padding: 1.4rem; }

/* ── Content ─────────────────────────────── */
.cd-desc { font-size: 0.95rem; line-height: 1.75; color: #4a5568; margin: 0; }
.cd-responsibilities { list-style: none; padding: 0; margin: 0; }
.cd-responsibilities li { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f0f4f8; font-size: 0.92rem; color: #374151; }
.cd-responsibilities li:last-child { border-bottom: none; }
.cd-resp-num { width: 24px; height: 24px; border-radius: 6px; background: var(--pf-navy); color: #fff; font-size: 0.72rem; font-weight: 700; display: grid; place-items: center; flex-shrink: 0; margin-top: 1px; }

/* ── Sidebar widgets ─────────────────────────── */
.cd-widget { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(19,38,77,0.07); overflow: hidden; margin-bottom: 1.25rem; border: 1px solid #edf2f7; }
.cd-widget-head { padding: 0.9rem 1.2rem; background: var(--pf-navy); display: flex; align-items: center; gap: 0.55rem; }
.cd-widget-head svg { width: 18px; height: 18px; color: var(--pf-sky); }
.cd-widget-head h3 { font-size: 0.9rem; font-weight: 700; color: #fff; margin: 0; }
.cd-widget-body { padding: 1.2rem; }

/* ── MBTI / Dimension Grid ──────────────────── */
.dim-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; }
.dim-item { padding: 1.1rem; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; }

/* ── Degree / Certs ────────────────────────── */
.match-badge { display: inline-flex; align-items: center; px: 0.6rem; py: 0.2rem; border-radius: 6px; background: var(--pf-sky-light); color: var(--pf-navy); font-size: 0.75rem; font-weight: 700; }
.degree-card { background: var(--pf-navy); color: #fff; padding: 1.25rem; border-radius: 12px; position: relative; overflow: hidden; }
.degree-card h4 { font-size: 0.7rem; text-transform: uppercase; color: var(--pf-sky); margin: 0 0 0.4rem; letter-spacing: .05em; }
.degree-card p { font-size: 1rem; font-weight: 700; margin: 0; line-height: 1.3; }
.degree-card svg { position: absolute; right: -15px; bottom: -15px; width: 80px; height: 80px; opacity: 0.08; }

.cd-btn { display: block; width: 100%; padding: 0.75rem; border-radius: 8px; text-align: center; font-size: 0.88rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: all .2s; margin-bottom: 0.75rem; }
.cd-btn-primary { background: var(--pf-navy); color: #fff; }
.cd-btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
.cd-btn-secondary { background: var(--pf-sky); color: #fff; }
.cd-btn-secondary:hover { opacity: 0.9; transform: translateY(-1px); }

.other-matches { list-style: none; padding: 0; margin: 0; }
.other-match-item { padding: 0.75rem; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; }
.other-match-item:last-child { border-bottom: none; }
</style>

@php
    $topMatchData = null;
    if($topMatch) {
        $topMatchData = \App\Http\Controllers\PathfinderController::getCareerData($topMatch['job_title']);
    }
    $description = $topMatchData['description'] ?? ($topMatch['description'] ?? '');
    $responsibilities = $topMatchData['responsibilities'] ?? [];
    $salary = $topMatchData['salary_range'] ?? 'Competitive';
    $education = $topMatchData['education_requirements'] ?? 'Degree required';
    $outlook = $topMatchData['job_outlook'] ?? 'Growth';
    $degree = $topMatchData['recommended_degree'] ?? null;
    $certs = $topMatchData['certificates'] ?? [];
@endphp

{{-- ═══════════════════ HERO ═══════════════════ --}}
<div class="cd-hero">
    <div class="cd-hero-inner">
        <nav class="cd-breadcrumb">
            <a href="{{ route('pathfinder.index') }}">Pathfinder</a>
            <span>›</span>
            <a href="{{ route('pathfinder.cv-upload') }}">CV Upload</a>
            <span>›</span>
            Analysis Result
        </nav>

        <h1>CV Analysis Complete!</h1>
        <p class="tagline">We've matched your skills against dozens of career profiles to find your perfect professional fit.</p>

        <div class="cd-stats">
            <div class="cd-stat">
                <div class="cd-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="cd-stat-label">Skills Detected</div>
                    <div class="cd-stat-val">{{ $analysisSummary['total_skills_found'] }}</div>
                </div>
            </div>
            <div class="cd-stat">
                <div class="cd-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div>
                    <div class="cd-stat-label">Career Matches</div>
                    <div class="cd-stat-val">{{ $analysisSummary['total_job_matches'] }}</div>
                </div>
            </div>
            <div class="cd-stat">
                <div class="cd-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <div class="cd-stat-label">Best Match Score</div>
                    <div class="cd-stat-val">{{ $topMatch['similarity_score'] }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════ BODY ═══════════════════ --}}
<div class="cd-body">
    <div class="cd-body-inner">

        {{-- ─── LEFT COLUMN ─── --}}
        <div>
            @if($topMatch)
            {{-- Best Match Overview --}}
            <div class="cd-card">
                <div class="cd-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path></svg>
                    <h2>Your Best Career Match</h2>
                </div>
                <div class="cd-card-body">
                    <h3 style="font-size: 2.2rem; font-weight: 800; color: var(--pf-navy); margin: 0 0 0.5rem;">{{ $topMatch['job_title'] }}</h3>
                    <div style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 1.5rem;">
                        <span style="font-size: 0.75rem font-weight: 700; color: var(--pf-sky); text-transform: uppercase; letter-spacing: .05em;">{{ $topMatch['category'] }}</span>
                        <div class="match-badge">{{ $topMatch['similarity_score'] }}% COMPATIBILITY</div>
                    </div>
                    
                    <p class="cd-desc" style="font-size: 1.1rem; italic; margin-bottom: 0;">"{{ $description }}"</p>
                </div>
            </div>

            {{-- Quick Insights Grid (Stats replacement) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #f0fdf4; display: grid; place-items: center;">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Salary Range</div>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #334155;">{{ $salary }}</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #eff6ff; display: grid; place-items: center;">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Min Education</div>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #334155;">{{ $education }}</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #fff7ed; display: grid; place-items: center;">
                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Job Outlook</div>
                        <div style="font-size: 0.95rem; font-weight: 800; color: #334155;">{{ $outlook }}</div>
                    </div>
                </div>
            </div>

            {{-- Responsibilities --}}
            @if(!empty($responsibilities))
            <div class="cd-card">
                <div class="cd-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <h2>Key Responsibilities</h2>
                </div>
                <div class="cd-card-body">
                    <ul class="cd-responsibilities">
                        @foreach($responsibilities as $i => $resp)
                        <li>
                            <span class="cd-resp-num">{{ $i + 1 }}</span>
                            <span>{{ $resp }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Extracted Skills --}}
            @if(!empty($analysisSummary['top_skills']))
            <div class="cd-card">
                <div class="cd-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <h2>Skills Detected in Your CV</h2>
                </div>
                <div class="cd-card-body">
                    <div style="display: flex; flex-wrap: wrap; gap: 0.6rem;">
                        @foreach($analysisSummary['top_skills'] as $skill)
                            <span style="padding: 0.35rem 0.85rem; border-radius: 999px; background: #f1f5f9; color: var(--pf-navy); font-size: 0.8rem; font-weight: 600; border: 1px solid #e2e8f0;">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Why This Job? --}}
            @if($topMatch && !empty($topMatch['matching_dimensions']))
            <div class="cd-card">
                <div class="cd-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    <h2>Why {{ $topMatch['job_title'] }}?</h2>
                </div>
                <div class="cd-card-body">
                    <div class="dim-grid">
                        @foreach($topMatch['matching_dimensions'] as $dimension)
                            <div class="dim-item">
                                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--pf-navy); margin: 0 0 0.4rem;">{{ $dimension['dimension'] }}</h4>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 0.8rem; color: #64748b;">Match Strength:</span>
                                    <span style="font-size: 0.9rem; font-weight: 800; color: var(--pf-sky);">{{ round($dimension['user_score'] * 100) }}%</span>
                                </div>
                                <div style="height: 4px; background: #e2e8f0; border-radius: 2px; margin-top: 0.5rem; overflow: hidden;">
                                    <div style="height: 100%; width: {{ round($dimension['user_score'] * 100) }}%; background: var(--pf-sky);"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div><!-- /left col -->

        {{-- ─── RIGHT COLUMN ─── --}}
        <div>
            @if($degree)
            <div class="cd-widget" style="background: transparent; border: none; box-shadow: none;">
                <div class="degree-card">
                    <h4>Recommended Degree Path</h4>
                    <p>{{ $degree }}</p>
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path d="M12 14v6" /></svg>
                </div>
            </div>
            @endif

            @if(!empty($certs))
            <div class="cd-widget">
                <div class="cd-widget-head">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <h3>Certifications</h3>
                </div>
                <div class="cd-widget-body">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach($certs as $cert)
                        <li style="padding: 0.6rem 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.6rem;">
                            <svg class="h-4 w-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            <span style="font-size: 0.85rem; font-weight: 600; color: #475569;">{{ $cert }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Other Matches Widget --}}
            @if(count($allMatches) > 1)
            <div class="cd-widget">
                <div class="cd-widget-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <h3>Alternative Careers</h3>
                </div>
                <div class="cd-widget-body" style="padding: 0;">
                    <ul class="other-matches">
                        @foreach(array_slice($allMatches, 1, 3) as $match)
                        <li class="other-match-item">
                            <div>
                                <div style="font-size: 0.88rem; font-weight: 700; color: var(--pf-navy);">{{ $match['job_title'] }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">{{ $match['category'] }}</div>
                            </div>
                            <div style="font-size: 1rem; font-weight: 800; color: var(--pf-sky);">{{ $match['similarity_score'] }}%</div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Navigation Actions --}}
            <div style="margin-top: 1.5rem;">
                <a href="{{ route('pathfinder.skill-gap') }}" class="cd-btn cd-btn-secondary">Analyze Skill Gap</a>
                <a href="{{ route('pathfinder.career-path') }}" class="cd-btn cd-btn-secondary">Visualize Career Path</a>
                <a href="{{ route('pathfinder.cv-upload') }}" class="cd-btn cd-btn-outline" style="background: white; border: 1px solid #cbd5e1; color: #64748b;">Upload Another CV</a>
            </div>

        </div><!-- /right col -->
    </div><!-- /inner -->
</div><!-- /body -->

@endsection
