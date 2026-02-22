@extends('pathfinder.layout')

@section('title', 'Your Career Path - Pathfinder')

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <div class="flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mx-auto mb-6">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Your Career Path
            </h1>
            <p class="text-xl max-w-3xl mx-auto" style="color: #EFF6FF; opacity: 0.9;">
                From <span class="font-semibold">{{ $currentRole }}</span> to <span class="font-semibold">{{ $targetRole }}</span>
            </p>
        </div>
    </div>
</div>

<!-- Career Ladder Visualization -->
<div class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- New Vertical Journey Timeline -->
        @php
            /* Reorder so earliest (junior) level appears first (top). */
            $journeySteps = array_values(array_reverse($pathSteps));
            $total = count($journeySteps);
        @endphp
        <div class="max-w-5xl mx-auto mt-14" id="journey-root">
            <!-- Sticky Progress Badge -->
            <div id="journey-progress-sticky" class="journey-sticky hidden" aria-live="polite">
                <div class="jp-inner">
                    <span class="jp-label">Step <span id="jp-current">1</span> of <span id="jp-total">{{$total}}</span></span>
                    <div class="jp-bar"><span id="jp-bar-fill"></span></div>
                </div>
            </div>
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold tracking-tight mb-4 bg-gradient-to-r from-[#9fafce] to-[#5AA7C6] bg-clip-text text-transparent">Professional Growth Journey</h2>
                <p class="text-gray-600 text-xl leading-relaxed">Your path toward <span class="font-semibold text-[#13264D]">{{ $targetRole }}</span> visualized as milestones. Expand a milestone for details.</p>
            </div>
            <ol class="relative journey-timeline" aria-label="Career progression timeline">
                <span aria-hidden="true" class="journey-axis"></span>
                @foreach($journeySteps as $i => $step)
                    @php
                        $title = $step['title'] ?? ($step['level'] ?? 'Level '.($i+1));
                        $levelLabel = $step['level'] ?? 'Level '.($i+1);
                        $years = $step['duration'] ?? '';
                        $salary = $step['salary_range'] ?? '';
                        $pct = $total>1 ? intval(($i/($total-1))*100) : 100;
                        $skills = $step['skills'] ?? ($step['core_skills'] ?? []);
                        $certs = $step['certifications'] ?? [];
                        $achievements = $step['achievements'] ?? ($step['milestones'] ?? []);
                    @endphp
                    <li class="journey-item group" data-index="{{$i}}">
                        <div class="journey-node-wrapper">
                            <button class="journey-node focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-300" aria-expanded="false" aria-controls="journey-panel-{{$i}}" data-title="{{ e($title) }}">
                                <span class="sr-only">Toggle {{ e($title) }} details</span>
                                <span class="journey-node-core"></span>
                            </button>
                            <div class="journey-connector" aria-hidden="true"></div>
                        </div>
                        <div id="journey-panel-{{$i}}" class="journey-card" role="group" aria-label="{{ e($title) }}">
                            <div class="journey-card-front">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="font-semibold text-lg text-[#13264D] leading-snug tracking-tight">{{ $title }}</h3>
                                </div>
                                <p class="text-[15px] text-gray-600 leading-relaxed">{{ $step['description'] ?? 'Overview not provided.' }}</p>
                                @if (!empty($step['responsibilities']))
                                <div class="mt-4">
                                  <h4 class="text-xs font-semibold uppercase tracking-wide text-[#13264D] mb-2">Key Responsibilities:</h4>
                                  <ul class="text-xs text-gray-600 space-y-1">
                                    @foreach ($step['responsibilities'] as $resp)
                                      <li class="flex items-start gap-2">
                                        <span class="inline-block text-[#4E90B1] font-bold mt-0.5">•</span>
                                        <span>{{ $resp }}</span>
                                      </li>
                                    @endforeach
                                  </ul>
                                </div>
                                @endif
                                <div class="mt-3 flex flex-wrap gap-2 text-[11px] tracking-wide font-medium text-gray-500">
                                    @if($years)<span class="badge-years">{{$years}}</span>@endif
                                    @if($salary)<span class="badge-salary">{{$salary}}</span>@endif
                                    <span class="badge-level">{{$levelLabel}}</span>
                                    @if(is_array($skills))
                                        @foreach(array_slice($skills,0,3) as $skill)
                                            <span class="badge-skill" title="Skill">{{ $skill }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

    </div>
</div>

<!-- Tips and Resources Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Tips for Success
            </h2>
            <p class="text-lg text-gray-600">
                Additional guidance to help you succeed on your career journey
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-blue-900">Stay Consistent</h3>
                </div>
                <p class="text-blue-800">
                    Dedicate regular time to your career development. Even 30 minutes a day can lead to significant progress over time.
                </p>
            </div>

            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-green-900">Network Actively</h3>
                </div>
                <p class="text-green-800">
                    Build relationships with professionals in your target field. Many opportunities come through networking.
                </p>
            </div>

            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-purple-900">Track Progress</h3>
                </div>
                <p class="text-purple-800">
                    Regularly review your progress and adjust your plan as needed. Celebrate small wins along the way.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">
            Ready to Take the Next Step?
        </h2>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('pathfinder.skill-gap') }}" class="inline-flex items-center justify-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Analyze Your Skills
            </a>
            <a href="{{ route('pathfinder.career-guidance') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                Get Course Recommendations
            </a>
            <a href="{{ route('pathfinder.career-path') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Create Another Path
            </a>
        </div>
    </div>
</div>

<script>
// Vertical Journey Timeline Interactions
document.addEventListener('DOMContentLoaded', () => {
    const items = Array.from(document.querySelectorAll('.journey-item'));
    const nodeButtons = items.map(i => i.querySelector('.journey-node'));
    let openIndex = -1;
    const total = items.length;
    // Sticky elements
    const sticky = document.getElementById('journey-progress-sticky');
    const jpCurrent = document.getElementById('jp-current');
    const jpTotal = document.getElementById('jp-total');
    const jpBarFill = document.getElementById('jp-bar-fill');
    if(jpTotal) jpTotal.textContent = total;

    // Persist keys
    // v2 keys (ordering changed)
    const STORAGE_KEY_OPEN = 'pf_journey_v2_open_step';
    const STORAGE_KEY_ACH = 'pf_journey_v2_achievements';
    let achievementState = {};
    try { achievementState = JSON.parse(localStorage.getItem(STORAGE_KEY_ACH) || '{}'); } catch(e){ achievementState={}; }

    function saveAchievements(){
        try { localStorage.setItem(STORAGE_KEY_ACH, JSON.stringify(achievementState)); } catch(e){}
    }
    function loadAchievementCheckboxes(stepIdx){
        const list = items[stepIdx].querySelector('.journey-achievements');
        if(!list) return;
        const saved = achievementState[stepIdx] || [];
        list.querySelectorAll('.ach-cb').forEach((cb,i)=>{
            cb.checked = saved.includes(i);
            cb.addEventListener('change', ()=>{
                const arr = new Set(achievementState[stepIdx] || []);
                if(cb.checked) arr.add(i); else arr.delete(i);
                achievementState[stepIdx] = Array.from(arr);
                saveAchievements();
            });
        });
    }

    function updateNavState(idx){
        items.forEach((item,i)=>{
            const prevBtn = item.querySelector('.journey-nav-prev');
            const nextBtn = item.querySelector('.journey-nav-next');
            if(prevBtn) prevBtn.disabled = i===0;
            if(nextBtn) nextBtn.disabled = i===items.length-1;
            // highlight progress fill
            item.classList.toggle('completed', i < idx);
            item.classList.toggle('active', i === idx);
        });
        // Sticky badge display logic
        if(idx>=0){
                sticky.classList.remove('hidden');
                jpCurrent.textContent = idx+1;
                const pct = total>1 ? ((idx)/(total-1))*100 : 100;
                jpBarFill.style.width = pct+'%';
                document.documentElement.style.setProperty('--journey-progress-pct', pct+'%');
        } else {
                sticky.classList.add('hidden');
                jpBarFill.style.width = '0%';
        }
        // Axis fill animation
        const axisFill = document.querySelector('.journey-axis::after'); // pseudo can't be selected; we use CSS variable instead
        document.documentElement.style.setProperty('--axis-fill-pct', (idx>=0? ( (idx)/(total-1))*100:0)+'%');
    }

    function collapse(idx){
        if(idx < 0) return;
        const item = items[idx];
        const btn = item.querySelector('.journey-node');
        const card = item.querySelector('.journey-card');
        const detail = card.querySelector('.journey-card-detail');
        const front = card.querySelector('.journey-card-front');
        btn.setAttribute('aria-expanded','false');
        card.classList.remove('expanded');
        detail.setAttribute('hidden','');
        front.classList.remove('opacity-0');
        card.style.removeProperty('--expanded-height');
    }

    function expand(idx){
        const item = items[idx];
        const btn = item.querySelector('.journey-node');
        const card = item.querySelector('.journey-card');
        const detail = card.querySelector('.journey-card-detail');
        const front = card.querySelector('.journey-card-front');
        btn.setAttribute('aria-expanded','true');
        detail.removeAttribute('hidden');
        requestAnimationFrame(()=>{
            card.classList.add('expanded');
            front.classList.add('opacity-0');
            const h = detail.scrollHeight + front.scrollHeight + 48; // some padding
            card.style.setProperty('--expanded-height', h+'px');
            loadAchievementCheckboxes(idx);
        });
        // Persist
        try { localStorage.setItem(STORAGE_KEY_OPEN, idx); } catch(e){}
    }

    function open(idx){
        if(openIndex === idx){ collapse(openIndex); openIndex=-1; updateNavState(-1); return; }
        collapse(openIndex); openIndex = idx; expand(idx); updateNavState(idx);
        // auto-scroll into view
        items[idx].scrollIntoView({block:'nearest', behavior:'smooth'});
    }

    nodeButtons.forEach((btn,i)=>{
        btn.addEventListener('click', ()=> open(i));
        btn.addEventListener('keydown', e=>{ if(['Enter',' '].includes(e.key)){ e.preventDefault(); open(i);} });
    });

    items.forEach((item,i)=>{
        item.addEventListener('click', e=>{
            const toggleBtn = e.target.closest('.journey-toggle-btn');
            if(!toggleBtn) return;
            const action = toggleBtn.dataset.action;
            if(action==='expand') open(i); else if(action==='collapse') collapse(i), updateNavState(openIndex), openIndex=-1;
        });
        const prev = item.querySelector('.journey-nav-prev');
        const next = item.querySelector('.journey-nav-next');
        prev && prev.addEventListener('click', e=>{ e.stopPropagation(); if(i>0) open(i-1); });
        next && next.addEventListener('click', e=>{ e.stopPropagation(); if(i<items.length-1) open(i+1); });
    });

        document.addEventListener('keydown', e=>{
        if(e.key==='Escape' && openIndex!==-1){ collapse(openIndex); updateNavState(-1); openIndex=-1; }
        if(['ArrowDown','ArrowRight'].includes(e.key)){
            const next = Math.min(items.length-1, (openIndex===-1?0:openIndex+1));
            open(next); nodeButtons[next].focus();
        }
        if(['ArrowUp','ArrowLeft'].includes(e.key)){
            const prev = Math.max(0, (openIndex===-1?0:openIndex-1));
            open(prev); nodeButtons[prev].focus();
        }
    });

    // Scroll-triggered node highlight
    let scrollHighlightIdx = -1;
    const observer = new IntersectionObserver((entries) => {
        let mostVisible = -1;
        let maxRatio = 0;
        entries.forEach(entry => {
            if(entry.intersectionRatio > maxRatio){
                maxRatio = entry.intersectionRatio;
                mostVisible = items.indexOf(entry.target);
            }
        });
        if(mostVisible !== scrollHighlightIdx && mostVisible >= 0){
            scrollHighlightIdx = mostVisible;
            nodeButtons.forEach((btn, i) => {
                btn.classList.toggle('scroll-active', i === scrollHighlightIdx);
            });
        }
    }, { threshold: [0, 0.25, 0.5, 0.75, 1] });
    
    items.forEach(item => observer.observe(item));

        // Restore last open step
        try {
            const stored = parseInt(localStorage.getItem(STORAGE_KEY_OPEN),10);
            if(!isNaN(stored) && stored>=0 && stored<total){ open(stored); }
        } catch(e){}
});
</script>

<style>
    :root { --brand-primary:#13264D; --brand-accent:#4E90B1; --brand-accent-strong:#5AA7C6; --brand-accent-soft:#C6E4F0; --brand-bg-soft:#F4F8FB; --brand-surface:#FFFFFF; --brand-surface-alt:#EEF4F8; --axis-bg:linear-gradient(180deg,#13264D 0%,#5AA7C6 100%); --axis-fill-pct:0%; }
    .journey-timeline { list-style:none; margin:0; padding:0; position:relative; }
    .journey-axis { position:absolute; left:34px; top:0; bottom:0; width:5px; background:linear-gradient(180deg,rgba(19,38,77,0.15),rgba(90,167,198,0.18)); border-radius:6px; overflow:hidden; }
    .journey-axis:after { content:""; position:absolute; inset:0; background:var(--axis-bg); transform-origin:top; transform:scaleY(calc(var(--axis-fill-pct)/100)); transition:transform .8s cubic-bezier(.16,.8,.24,1); }
    .journey-item { position:relative; display:flex; gap:1.75rem; padding-bottom:3.75rem; }
    .journey-item:last-child { padding-bottom:0; }
    .journey-node-wrapper { position:relative; width:68px; flex:0 0 68px; display:flex; align-items:flex-start; justify-content:center; }
    .journey-node { position:relative; z-index:2; height:38px; width:38px; border-radius:50%; background:linear-gradient(145deg,#FFFFFF 0%,#E9F2F7 100%); border:2px solid #fff; box-shadow:0 4px 14px -4px rgba(0,0,0,.2),0 2px 6px -2px rgba(0,0,0,.12); cursor:pointer; transition:transform .45s cubic-bezier(.16,.8,.24,1), box-shadow .45s, background-color .4s ease; display:flex; align-items:center; justify-content:center; }
    .journey-node-core { display:block; height:12px; width:12px; background:linear-gradient(135deg,var(--brand-primary),var(--brand-accent-strong)); border-radius:50%; box-shadow:0 0 0 5px rgba(19,38,77,0.15),0 0 0 10px rgba(90,167,198,0.12); transition:box-shadow .6s cubic-bezier(.16,.8,.24,1), transform .6s cubic-bezier(.16,.8,.24,1); }
    .journey-item.active .journey-node-core { box-shadow:0 0 0 5px rgba(90,167,198,0.45),0 0 0 10px rgba(19,38,77,0.35); transform:scale(1.28); }
    .journey-item.completed .journey-node-core { box-shadow:0 0 0 5px rgba(78,144,177,0.55),0 0 0 10px rgba(29,62,115,0.38); background:linear-gradient(135deg,#1D3E73,#4E90B1); }
    .journey-node:hover { transform:translateY(-6px) scale(1.12); box-shadow:0 12px 32px -8px rgba(19,38,77,.28),0 8px 20px -6px rgba(90,167,198,.18); background:linear-gradient(145deg,#F0F8FF 0%,#E0EDF7 100%); }
    .journey-node:active { transform:translateY(-3px) scale(1.08); }
    .journey-node:focus-visible { outline:2px solid var(--brand-accent); outline-offset:3px; }
    .journey-node.scroll-active { transform:scale(1.35); box-shadow:0 14px 40px -10px rgba(19,38,77,.35), 0 10px 24px -8px rgba(90,167,198,.25); }
    .journey-connector { position:absolute; top:38px; bottom:-3.75rem; left:50%; width:2px; background:linear-gradient(180deg,rgba(19,38,77,.28),rgba(90,167,198,.30)); transform:translateX(-50%); }
    .journey-item:last-child .journey-connector { display:none; }
    /* Base card now lighter blue gradient */
    .journey-card { position:relative; flex:1; background:linear-gradient(145deg,#FFFFFF 0%,#ECF5FB 85%); border:1px solid #C9DCE7; border-radius:26px; padding:1.75rem 1.75rem 1.35rem; box-shadow:0 4px 16px -6px rgba(25,55,90,0.12),0 2px 6px -4px rgba(25,55,90,0.06); transition:box-shadow .55s, background .7s, border-color .5s, transform .45s cubic-bezier(.16,.8,.24,1); overflow:hidden; }
    .journey-card:hover { transform:translateY(-6px); box-shadow:0 12px 36px -8px rgba(19,38,77,.2),0 8px 24px -6px rgba(90,167,198,.15); background:linear-gradient(145deg,#FFFFFF 0%,#E0EDF8 85%); }
    .journey-card::before { content:""; position:absolute; inset:0; background:radial-gradient(circle at 70% 18%,rgba(90,167,198,0.22),transparent 65%); opacity:0; transition:opacity .9s; pointer-events:none; }
    .journey-item.active .journey-card::before { opacity:1; }
    .journey-card-front { transition:opacity .5s .05s; }
    .journey-card.expanded .journey-card-front { pointer-events:none; }
    .journey-card-detail { animation:fadeSlide .55s cubic-bezier(.16,.8,.24,1); }
    @keyframes fadeSlide { from { opacity:0; transform:translateY(-6px);} to { opacity:1; transform:translateY(0);} }
    .journey-card.expanded { background:linear-gradient(145deg,#FFFFFF 0%,#E6F1F8 90%); box-shadow:0 14px 38px -12px rgba(15,45,80,0.22),0 22px 58px -18px rgba(15,45,80,0.25); }
    .journey-card.expanded .journey-toggle-btn[data-action="expand"] { display:none; }
    .journey-card:not(.expanded) .journey-card-detail { display:none; }
    .journey-progress { position:absolute; top:0; right:0; width:64px; height:64px; display:flex; align-items:center; justify-content:center; opacity:.08; }
    .journey-progress span { position:absolute; inset:0; background:conic-gradient(var(--brand-accent-strong) var(--p), transparent 0); mask:radial-gradient(circle at center, transparent 44%, #000 46%); -webkit-mask:radial-gradient(circle at center, transparent 44%, #000 46%); }
    .journey-item.completed .journey-progress span { background:conic-gradient(#4E90B1 var(--p), transparent 0); }
    .journey-nav-prev, .journey-nav-next { transition:background .25s, color .25s; }
    .journey-nav-next { background:linear-gradient(135deg,#13264D,#21527D); }
    .journey-nav-next:hover { background:linear-gradient(135deg,#183759,#2D648F); }
    .journey-card-detail .detail-inner { padding-top:.25rem; }
    .journey-item.active .journey-card { border-color:#B8D6E5; }
    .journey-item.completed .journey-card { border-color:#B4D4E3; background:linear-gradient(145deg,#FFFFFF 0%,#E2EEF6 92%); }
    .journey-item.completed .journey-card::before { background:radial-gradient(circle at 70% 18%,rgba(78,144,177,0.24),transparent 65%); opacity:1; }
    /* Focus ring accessible */
    .journey-node:focus-visible { outline:none; box-shadow:0 0 0 4px rgba(59,130,246,0.5),0 0 0 6px #fff; }
    /* Reduced motion */
    @media (prefers-reduced-motion: reduce){
        .journey-node, .journey-card, .journey-card-front { transition:none !important; animation:none !important; }
    }
    /* Dark mode - maintain light card design for readability */
    @media (prefers-color-scheme: dark){
        .journey-card {
            background: linear-gradient(145deg, #FFFFFF 0%, #ECF5FB 85%) !important;
            border-color: #C9DCE7 !important;
        }
        .journey-card-front {
            color: #1D3E73 !important;
        }
        .journey-card-front h3 {
            color: #1D3E73 !important;
        }
        .journey-card-front h4 {
            color: #1D3E73 !important;
        }
        .journey-card-front p {
            color: #374151 !important;
        }
        .journey-card-front ul li {
            color: #374151 !important;
        }
        .badge-years, .badge-salary {
            color: #1D3E73 !important;
        }
        .badge-level {
            background: #E8EEF5 !important;
            color: #1D3E73 !important;
        }
        body.dark .journey-axis { opacity:.4; }
    }
    /* Badges */
    .badge-years { @apply inline-flex items-center gap-1 font-semibold text-gray-700; }
    .badge-salary { @apply inline-flex items-center gap-1 font-semibold text-gray-700; }
    .badge-level { @apply inline-flex items-center gap-1 px-2 py-1 rounded-md bg-slate-100 text-slate-600; }
    .badge-skill { @apply inline-flex items-center px-2 py-1 rounded-md bg-sky-50 text-sky-600; font-weight:500; }
    /* Achievements */
    .journey-achievements { list-style:none; padding:0; margin:0; display:grid; gap:.4rem; }
    .journey-achievements li { margin:0; }
    .ach-label { display:flex; align-items:flex-start; gap:.5rem; font-size:.75rem; line-height:1.2; cursor:pointer; }
    .ach-cb { margin-top:2px; accent-color:var(--brand-accent-strong); }
    .ach-cb:checked + .ach-text { text-decoration:line-through; color:#1D3E73; }
    /* Sticky progress */
    .journey-sticky { position:sticky; top:0; z-index:30; margin-bottom:1.25rem; }
    .journey-sticky .jp-inner { background:linear-gradient(90deg,#FFFFFF,#E5EEF5); color:#1D3E73; border-radius:999px; padding:.7rem 1.25rem; display:flex; align-items:center; gap:1.1rem; box-shadow:0 8px 24px -10px rgba(0,0,0,.18); border:1px solid #D1DEE8; }
    .jp-label { font-size:.7rem; letter-spacing:.08em; font-weight:600; text-transform:uppercase; color:#284B6E; }
    .jp-bar { flex:1; position:relative; height:8px; background:linear-gradient(90deg,#D7E4EC,#E6F1F7); border-radius:5px; overflow:hidden; box-shadow:inset 0 0 0 1px #C3D5E0; }
    .jp-bar span { position:absolute; inset:0; width:0%; background:linear-gradient(90deg,#4E90B1,#78B9D3); transition:width .6s cubic-bezier(.16,.8,.24,1); }
    /* Theming variables usage */
    .journey-node-core { background:linear-gradient(135deg,var(--brand-primary),var(--brand-accent-strong)); }
    @media (prefers-color-scheme: dark){
        .journey-sticky .jp-inner { background:linear-gradient(90deg,#0C1729,#13264D); }
        .jp-bar { background:rgba(255,255,255,.15); }
        .jp-bar span { background:linear-gradient(90deg,#5AA7C6,#8DD5E9); }
    }
</style>
@endsection
