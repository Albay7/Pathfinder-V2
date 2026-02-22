@extends('pathfinder.layout')

@section('title', 'MBTI Personality Assessment - Pathfinder')

@section('content')
<!-- Header Section -->
<div style="background: linear-gradient(to bottom right, #13264D, #5AA7C6);">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4">
                Discover Your Personality Type
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl text-white/90 max-w-3xl mx-auto mb-8">
                Understand yourself better. Unlock career insights tailored to your unique personality.
            </p>
            <p class="text-base sm:text-lg text-white/80">
                Takes about <strong>10-15 minutes</strong> • <strong>Free</strong> • <strong>Instant Results</strong>
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-gray-50 py-16 sm:py-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- What is MBTI Section -->
        <div class="mb-20">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 text-center mb-4">What is MBTI?</h2>
            <p class="text-center text-gray-700 text-lg max-w-3xl mx-auto mb-12">
                Myers-Briggs Type Indicator (MBTI) is a widely-used personality framework that helps you understand your natural preferences across four key dimensions.
            </p>

            <!-- Four Dimensions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <!-- Dimension 1 -->
                <div class="bg-white rounded-lg shadow-md p-8 border-l-4" style="border-color: #5AA7C6;">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full text-white text-xl font-bold" style="background-color: #5AA7C6;">E</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Extraversion vs. Introversion</h3>
                            <p class="text-gray-700">How do you direct your energy? Do you thrive in social settings, or do you recharge through quiet reflection?</p>
                        </div>
                    </div>
                </div>

                <!-- Dimension 2 -->
                <div class="bg-white rounded-lg shadow-md p-8 border-l-4" style="border-color: #13264D;">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full text-white text-xl font-bold" style="background-color: #13264D;">N</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Sensing vs. Intuition</h3>
                            <p class="text-gray-700">How do you take in information? Are you detail-oriented and practical, or do you focus on patterns and possibilities?</p>
                        </div>
                    </div>
                </div>

                <!-- Dimension 3 -->
                <div class="bg-white rounded-lg shadow-md p-8 border-l-4" style="border-color: #4B5563;">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full text-white text-xl font-bold" style="background-color: #4B5563;">T</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Thinking vs. Feeling</h3>
                            <p class="text-gray-700">How do you make decisions? Do you rely on logic and objective analysis, or on values and how decisions affect people?</p>
                        </div>
                    </div>
                </div>

                <!-- Dimension 4 -->
                <div class="bg-white rounded-lg shadow-md p-8 border-l-4" style="border-color: #2D5A7B;">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full text-white text-xl font-bold" style="background-color: #2D5A7B;">J</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Judging vs. Perceiving</h3>
                            <p class="text-gray-700">How do you approach structure? Do you prefer planning and closure, or flexibility and keeping your options open?</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 16 Personality Types Section -->
        <div class="mb-20">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 text-center mb-4">The 16 Personality Types</h2>
            <p class="text-center text-gray-700 text-lg max-w-2xl mx-auto mb-16">
                Your unique combination of these four preferences creates one of 16 distinct personality types. Each type has its own strengths, challenges, and ideal career paths.
            </p>

            <!-- Types Grid - 2 Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @php
                    $types = [
                        ['code' => 'INTJ', 'name' => 'The Architect', 'color' => '#5AA7C6', 'description' => 'Strategic visionaries who blend long-range vision with disciplined execution. INTJs excel at developing comprehensive systems and implementing transformative ideas. They thrive in environments requiring independent thinking, strategic planning, and the ability to see patterns others miss.'],
                        ['code' => 'INTP', 'name' => 'The Logician', 'color' => '#5AA7C6', 'description' => 'Innovative problem-solvers driven by an endless curiosity about how things work. INTPs love diving deep into complex theoretical concepts and developing novel solutions. They bring original thinking and analytical rigor to fields ranging from technology to pure research.'],
                        ['code' => 'ENTJ', 'name' => 'The Commander', 'color' => '#5AA7C6', 'description' => 'Natural leaders with a strategic mindset and commanding presence. ENTJs excel at organizing people and resources toward ambitious goals. Their directness, decisiveness, and ability to inspire teams make them effective executives, strategists, and visionary leaders.'],
                        ['code' => 'ENTP', 'name' => 'The Debater', 'color' => '#5AA7C6', 'description' => 'Skilled debaters and enterprising innovators who thrive on intellectual challenges. ENTPs love exploring new ideas, challenging assumptions, and finding unconventional solutions. They bring adaptability, strategic thinking, and persuasive communication to dynamic environments.'],
                        ['code' => 'INFJ', 'name' => 'The Advocate', 'color' => '#2D5A7B', 'description' => 'Thoughtful idealists driven by a deep desire to help others and make a positive impact. INFJs combine intuitive insight with genuine empathy, making them natural counselors and mentors. They excel at understanding people\'s potential and inspiring meaningful personal growth.'],
                        ['code' => 'INFP', 'name' => 'The Mediator', 'color' => '#2D5A7B', 'description' => 'Creative idealists who follow their authentic values with quiet determination. INFPs bring depth, authenticity, and compassion to their work and relationships. They excel in roles allowing personal meaning-making and helping others align with their own values.'],
                        ['code' => 'ENFJ', 'name' => 'The Protagonist', 'color' => '#2D5A7B', 'description' => 'Charismatic leaders who inspire others through genuine care and compelling vision. ENFJs combine emotional intelligence with natural persuasiveness to motivate teams toward shared goals. They thrive in environments where they can develop people and drive meaningful change.'],
                        ['code' => 'ENFP', 'name' => 'The Campaigner', 'color' => '#2D5A7B', 'description' => 'Enthusiastic catalysts who bring energy, creativity, and social passion to any endeavor. ENFPs excel at connecting with people, exploring diverse perspectives, and generating innovative ideas. They thrive in dynamic roles requiring adaptability, emotional connection, and creative problem-solving.'],
                        ['code' => 'ISTJ', 'name' => 'The Logistician', 'color' => '#4B5563', 'description' => 'Dependable organizers who take pride in executing plans with precision and integrity. ISTJs create order and efficiency through systematic thinking and unwavering commitment to responsibility. They excel in roles requiring careful planning, detailed execution, and reliable follow-through.'],
                        ['code' => 'ISFJ', 'name' => 'The Defender', 'color' => '#4B5563', 'description' => 'Loyal protectors who create harmony through quiet dedication and genuine care for others\' wellbeing. ISFJs notice details others miss and work tirelessly behind the scenes to support their communities. They excel in roles combining practical service with meaningful human connection.'],
                        ['code' => 'ESTJ', 'name' => 'The Executive', 'color' => '#4B5563', 'description' => 'Efficient administrators who command respect through competence, decisiveness, and clear standards. ESTJs excel at establishing structure, coordinating teams, and ensuring results. Their directness and organizational skill make them effective managers and reliable leaders in complex environments.'],
                        ['code' => 'ESFJ', 'name' => 'The Consul', 'color' => '#4B5563', 'description' => 'Warmhearted organizers who bring people together and create supportive environments. ESFJs combine practical efficiency with genuine interest in others\' happiness and wellbeing. They excel in roles emphasizing collaboration, community service, and creating positive team dynamics.'],
                        ['code' => 'ISTP', 'name' => 'The Virtuoso', 'color' => '#13264D', 'description' => 'Pragmatic problem-solvers with a hands-on approach to understanding how things work. ISTPs combine logical analysis with practical troubleshooting ability, excelling in technical fields. They bring cool objectivity and mechanical insight to complex technical challenges.'],
                        ['code' => 'ISFP', 'name' => 'The Adventurer', 'color' => '#13264D', 'description' => 'Artistic explorers who bring aesthetic sensitivity and authentic self-expression to their pursuits. ISFPs notice beauty in the present moment and create meaningful experiences through their creativity. They thrive in roles allowing personal freedom, artistic expression, and hands-on engagement.'],
                        ['code' => 'ESTP', 'name' => 'The Entrepreneur', 'color' => '#13264D', 'description' => 'Dynamic risk-takers who seize opportunities and navigate challenges with resourceful adaptability. ESTPs bring energy, pragmatism, and social confidence to high-stakes environments. They excel in roles requiring quick decision-making, negotiation, and the ability to think on their feet.'],
                        ['code' => 'ESFP', 'name' => 'The Entertainer', 'color' => '#13264D', 'description' => 'Spontaneous performers who bring enthusiasm, charm, and joy to every situation. ESFPs excel at connecting with others emotionally and creating memorable experiences. They thrive in dynamic, people-focused roles where their energy, optimism, and social skill shine.'],
                    ];
                @endphp

                @foreach($types as $type)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow border border-gray-100 p-8">
                        <!-- Header with Code Badge and Name -->
                        <div class="flex items-start gap-4 mb-5">
                            <div class="flex-shrink-0">
                                <div class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-white font-bold text-sm" style="background-color: {{ $type['color'] }};">
                                    {{ $type['code'] }}
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 pt-1">{{ $type['name'] }}</h3>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-700 leading-relaxed text-base">{{ $type['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- What You'll Discover Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">What You'll Discover</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <div class="mb-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full text-white font-bold" style="background-color: #5AA7C6;">
                            1
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Your Personality Type</h3>
                    <p class="text-gray-700 text-sm">A detailed profile explaining your four-letter type and what it means for how you think and behave.</p>
                </div>
                <div>
                    <div class="mb-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full text-white font-bold" style="background-color: #2D5A7B;">
                            2
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Compatible Careers</h3>
                    <p class="text-gray-700 text-sm">Job recommendations that align with your personality strengths and natural work style.</p>
                </div>
                <div>
                    <div class="mb-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full text-white font-bold" style="background-color: #4B5563;">
                            3
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Skill Insights</h3>
                    <p class="text-gray-700 text-sm">Guidance on skills to develop based on your personality type and career goals.</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
            <div class="inline-block">
                <a href="{{ route('pathfinder.mbti-questionnaire') }}"
                   class="inline-flex items-center justify-center px-8 py-4 rounded-lg font-semibold text-white text-lg transition-transform hover:scale-105"
                   style="background: linear-gradient(135deg, #5AA7C6, #13264D);">
                    Let's Get Started
                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>

        </div>
    </div>
</div>

@endsection
