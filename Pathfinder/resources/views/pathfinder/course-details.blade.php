@extends('pathfinder.layout')

@section('title', ($courseDetails['title'] ?? 'Course') . ' — Course Details | Pathfinder')

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
.cd-stat-val { font-size: 0.88rem; font-weight: 700; color: #fff; }

/* ── Layout ──────────────────────────────────── */
.cd-body { background: #f3f6f9; padding: 2.5rem 0 3rem; }
.cd-body-inner { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; display: grid; grid-template-columns: 1fr 340px; gap: 1.75rem; }
@media(max-width:900px){ .cd-body-inner{ grid-template-columns:1fr; } }

/* ── Cards ───────────────────────────────────── */
.cd-card { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(19,38,77,0.07); overflow: hidden; margin-bottom: 1.5rem; }
.cd-card:last-child { margin-bottom: 0; }
.cd-card-head { padding: 1.1rem 1.4rem; border-bottom: 1px solid #edf2f7; display: flex; align-items: center; gap: 0.6rem; }
.cd-card-head svg { width: 20px; height: 20px; color: var(--pf-sky); flex-shrink: 0; }
.cd-card-head h2 { font-size: 1rem; font-weight: 700; color: var(--pf-navy); margin: 0; }
.cd-card-body { padding: 1.4rem; }

/* ── Description ─────────────────────────────── */
.cd-desc { font-size: 0.95rem; line-height: 1.75; color: #4a5568; }

/* ── Responsibilities list ───────────────────── */
.cd-responsibilities { list-style: none; padding: 0; margin: 0; }
.cd-responsibilities li { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.55rem 0; border-bottom: 1px solid #f0f4f8; font-size: 0.92rem; color: #374151; }
.cd-responsibilities li:last-child { border-bottom: none; }
.cd-resp-num { width: 24px; height: 24px; border-radius: 6px; background: var(--pf-navy); color: #fff; font-size: 0.72rem; font-weight: 700; display: grid; place-items: center; flex-shrink: 0; margin-top: 1px; }

/* ── Skills pills ────────────────────────────── */
.cd-pills { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.cd-pill { padding: 0.35rem 0.85rem; border-radius: 999px; background: var(--pf-sky-light); color: var(--pf-navy); font-size: 0.8rem; font-weight: 600; border: 1px solid var(--pf-sky-mid); }

/* ── MBTI card specifics ─────────────────────── */
.mbti-match-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.9rem; border-radius: 999px; background: #d1fae5; color: #065f46; font-size: 0.8rem; font-weight: 700; border: 1px solid #6ee7b7; }
.mbti-type-chip { display: inline-block; padding: 0.25rem 0.7rem; border-radius: 6px; background: var(--pf-navy); color: #fff; font-size: 0.85rem; font-weight: 700; letter-spacing: .04em; }
.mbti-desc { font-size: 0.92rem; line-height: 1.7; color: #374151; margin: 0.9rem 0 0; }
.mbti-cta-link { font-size: 0.82rem; color: var(--pf-sky); font-weight: 600; text-decoration: none; }
.mbti-cta-link:hover { text-decoration: underline; }

/* ── Sidebar widgets ─────────────────────────── */
.cd-widget { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(19,38,77,0.07); overflow: hidden; margin-bottom: 1.25rem; }
.cd-widget:last-child { margin-bottom: 0; }
.cd-widget-head { padding: 0.9rem 1.2rem; background: var(--pf-navy); display: flex; align-items: center; gap: 0.55rem; }
.cd-widget-head svg { width: 18px; height: 18px; color: var(--pf-sky); }
.cd-widget-head h3 { font-size: 0.9rem; font-weight: 700; color: #fff; margin: 0; }
.cd-widget-body { padding: 1.2rem; }

.cd-btn { display: block; width: 100%; padding: 0.65rem; border-radius: 8px; text-align: center; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: none; cursor: pointer; transition: opacity .2s; }
.cd-btn:hover { opacity: 0.88; }
.cd-btn-primary { background: var(--pf-navy); color: #fff; }
.cd-btn-outline { background: transparent; color: var(--pf-navy); border: 2px solid var(--pf-navy); margin-top: 0.55rem; }

/* ── Related careers ─────────────────────────── */
.related-list { list-style: none; padding: 0; margin: 0; }
.related-item a { display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.5rem; border-radius: 8px; text-decoration: none; color: #374151; font-size: 0.88rem; font-weight: 500; transition: background .15s; }
.related-item a:hover { background: var(--pf-sky-light); color: var(--pf-navy); }
.related-item a svg { width: 16px; height: 16px; color: var(--pf-sky); flex-shrink: 0; }
</style>

{{-- ═══════════════════ HERO ═══════════════════ --}}
<div class="cd-hero">
    <div class="cd-hero-inner">
        <nav class="cd-breadcrumb">
            <a href="{{ route('pathfinder.index') }}">Pathfinder</a>
            @php
                $prevUrl = url()->previous();
                $prevText = 'Previous Page';
                $useHistoryBack = false;
                
                if (str_contains($prevUrl, 'questionnaire')) {
                    $prevText = 'Course Assessment Results';
                    $useHistoryBack = true; // Use JS history back for POST request pages
                } elseif (str_contains($prevUrl, 'courses')) {
                    $prevText = 'Courses';
                } elseif (str_contains($prevUrl, 'career/details')) {
                    $prevText = 'Career Details';
                }
            @endphp
            
            @if($prevUrl && $prevUrl !== url()->current() && rtrim($prevUrl, '/') !== rtrim(route('pathfinder.index'), '/'))
                <span>›</span>
                <a href="{{ $useHistoryBack ? 'javascript:history.back()' : $prevUrl }}">{{ $prevText }}</a>
            @endif
            <span>›</span>
            {{ $courseDetails['title'] }}
        </nav>

        <h1>{{ $courseDetails['title'] }}</h1>
        <p class="tagline">{{ $courseDetails['tagline'] ?? 'Take the first step to your career.' }}</p>

        <div class="cd-stats">
            <div class="cd-stat">
                <div class="cd-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <div class="cd-stat-label">Duration</div>
                    <div class="cd-stat-val">{{ $courseDetails['duration'] ?? '4 Years' }}</div>
                </div>
            </div>
            <div class="cd-stat">
                <div class="cd-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <div class="cd-stat-label">Difficulty</div>
                    <div class="cd-stat-val">{{ $courseDetails['difficulty'] ?? 'Moderate' }}</div>
                </div>
            </div>
            <div class="cd-stat">
                <div class="cd-stat-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="cd-stat-label">Estimated Tuition</div>
                    <div class="cd-stat-val">{{ $courseDetails['tuition'] ?? '₱40,000 - ₱100,000 / Sem' }}</div>
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
            {{-- Course Overview --}}
            <div class="cd-card">
                <div class="cd-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <h2>Course Overview</h2>
                </div>
                <div class="cd-card-body">
                    <p class="cd-desc">{{ $courseDetails['description'] ?? '' }}</p>
                </div>
            </div>

            {{-- Curriculum Highlights --}}
            @if(!empty($courseDetails['curriculum_highlights']))
            <div class="cd-card">
                <div class="cd-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <h2>Curriculum Highlights</h2>
                </div>
                <div class="cd-card-body">
                    <ul class="cd-responsibilities">
                        @foreach($courseDetails['curriculum_highlights'] as $i => $highlight)
                        <li>
                            <span class="cd-resp-num">{{ $i + 1 }}</span>
                            <span>{{ $highlight }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif



            {{-- MBTI Compatibility --}}
            <div class="cd-card">
                <div class="cd-card-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <h2>Personality Alignment</h2>
                </div>
                <div class="cd-card-body">
                    @auth
                        @if($userMbtiType)
                            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;margin-bottom:0.9rem;">
                                <span class="mbti-type-chip">{{ $userMbtiType }}</span>
                                @if($mbtiIsMatch)
                                    <span class="mbti-match-badge">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Great Fit
                                    </span>
                                @endif
                            </div>
                            <p class="mbti-desc">
                                @if($mbtiIsMatch)
                                    Based on your {{ $userMbtiType }} personality, this degree aligns perfectly with your natural strengths, learning style, and career preferences. The curriculum and resulting career paths will likely feel engaging and fulfilling.
                                @else
                                    While your {{ $userMbtiType }} personality isn't listed as the most typical match for this specific degree, diverse perspectives are crucial in every field. This course can offer you unique challenges and opportunities to develop new skills.
                                @endif
                            </p>
                            <div style="margin-top:1rem;">
                                <a href="{{ route('pathfinder.mbti-questionnaire') }}" class="mbti-cta-link">Retake MBTI assessment →</a>
                            </div>
                        @else
                            <p style="font-size:0.92rem;color:#6b7280;margin:0 0 1rem;">Take our MBTI assessment to see how your personality aligns with this course.</p>
                            <a href="{{ route('pathfinder.mbti-questionnaire') }}" class="cd-btn cd-btn-primary">Take MBTI Assessment</a>
                        @endif
                    @else
                        <p style="font-size:0.92rem;color:#6b7280;margin:0 0 1rem;">Sign in and take the MBTI assessment to see your personality compatibility with this course.</p>
                        <a href="{{ route('login') }}" class="cd-btn cd-btn-primary">Sign In</a>
                        <a href="{{ route('pathfinder.mbti-questionnaire') }}" class="cd-btn cd-btn-outline">Take MBTI Assessment</a>
                    @endauth
                </div>
            </div>
        </div><!-- /left col -->

        {{-- ─── RIGHT COLUMN (sidebar) ─── --}}
        <div>

            {{-- Career Opportunities Widget --}}
            @if(!empty($courseDetails['career_opportunities']))
            <div class="cd-widget">
                <div class="cd-widget-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2m8 0H8"/></svg>
                    <h3>Career Opportunities</h3>
                </div>
                <div class="cd-widget-body" style="padding:0.6rem 1rem;">
                    <ul class="related-list">
                        @foreach($courseDetails['career_opportunities'] as $careerOp)
                        <li class="related-item" style="padding: 0.6rem 0.5rem; display: flex; align-items: center; gap: 0.7rem; color: #374151; font-size: 0.88rem; font-weight: 500;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px; color: var(--pf-sky); flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $careerOp }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Top Universities Widget --}}
            @if(!empty($courseDetails['top_universities']))
            <div class="cd-widget">
                <div class="cd-widget-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6" /></svg>
                    <h3>Top Universities</h3>
                </div>
                <div class="cd-widget-body" style="padding:0.8rem 1rem;">
                    <ul style="list-style:none; padding:0; margin:0;">
                        @foreach($courseDetails['top_universities'] as $university)
                        <li style="padding: 0.8rem 0; border-bottom: 1px solid #edf2f7;">
                            <div style="font-size:0.9rem; font-weight:700; color:var(--pf-navy); line-height:1.3;">{{ $university['name'] }}</div>
                            <div style="font-size:0.75rem; color:#6b7280; margin-top:0.2rem; display:flex; align-items:center; gap:0.25rem;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $university['location'] }}
                            </div>
                            @if(!empty($university['description']))
                                <div style="font-size:0.8rem; color:#4a5568; margin-top:0.4rem; line-height:1.4;">{{ $university['description'] }}</div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            
            {{-- Skills Gained Widget --}}
            @if(!empty($courseDetails['skills_gained']))
            <div class="cd-widget">
                <div class="cd-widget-head">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <h3>Skills You Will Gain</h3>
                </div>
                <div class="cd-widget-body">
                    <div class="cd-pills" style="gap: 0.6rem;">
                        @foreach($courseDetails['skills_gained'] as $skill)
                        <span class="cd-pill" style="width: 100%; text-align: left; padding: 0.5rem 1rem; border-radius: 8px;">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div><!-- /right col -->
    </div><!-- /inner -->
</div><!-- /body -->

@endsection
