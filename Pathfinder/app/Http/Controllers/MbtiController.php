<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MbtiTestSession;
use App\Models\MbtiPersonalityType;
use App\Models\UserProgress;
use App\Models\Course;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class MbtiController extends Controller
{
    /**
     * Display the MBTI intro landing page
     *
     * @return \Illuminate\View\View
     */
    public function showIntro()
    {
        // Redirect to results if user already has a result
        if (Auth::check() && Auth::user()->mbti_type) {
            return redirect()->route('pathfinder.mbti.results')
                ->with('info', 'You have already completed the MBTI assessment. Your saved results are shown below.');
        }

        if (!Auth::check() && session('mbti_type')) {
            return redirect()->route('pathfinder.mbti.results')
                ->with('info', 'Your previous MBTI result is shown below.');
        }

        return view('pathfinder.mbti-intro');
    }

    /**
     * Clear the current MBTI result and redirect to the questionnaire for a retake
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function retakeAssessment()
    {
        if (Auth::check()) {
            // Clear MBTI data from user profile so they can retake
            Auth::user()->update([
                'mbti_type'        => null,
                'mbti_scores'      => null,
                'mbti_description' => null,
            ]);
        }

        // Always clear session data (for guests or any lingering session)
        session()->forget(['mbti_type', 'mbti_scores', 'mbti_description', 'personality_type']);

        return redirect()->route('pathfinder.mbti-questionnaire')
            ->with('info', 'Starting a fresh MBTI assessment. Good luck!');
    }

    /**
     * Display the MBTI questionnaire form
     *
     * @return \Illuminate\View\View
     */
    public function showQuestionnaire()
    {
        return view('pathfinder.mbti-questionnaire');
    }

    /**
     * Get the next question for adaptive assessment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNextQuestion(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|exists:mbti_test_sessions,id',
                'current_responses' => 'array',
                'rl_state' => 'array'
            ]);

            $sessionId = $request->input('session_id');
            $currentResponses = $request->input('current_responses', []);
            $rlState = $request->input('rl_state', []);

            // Get the test session
            $testSession = MbtiTestSession::findOrFail($sessionId);

            // Load MBTI questions (you might want to create a separate service for this)
            $allQuestions = $this->getMbtiQuestions();

            // Simple adaptive logic - select next question
            $questionsAsked = count($currentResponses);

            // Select next question based on current responses
            $nextQuestionIndex = $this->selectNextQuestion($currentResponses, $allQuestions);

            if ($nextQuestionIndex === null) {
                return response()->json([
                    'success' => true,
                    'should_stop' => true,
                    'confidence' => 1.0,
                    'questions_asked' => $questionsAsked
                ]);
            }

            $nextQuestion = $allQuestions[$nextQuestionIndex];

            return response()->json([
                'success' => true,
                'should_stop' => false,
                'question' => $nextQuestion,
                'question_index' => $nextQuestionIndex,
                'progress' => min(100, ($questionsAsked / 20) * 100)
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get next question: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to get next question',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Record a response for adaptive assessment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordResponse(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|exists:mbti_test_sessions,id',
                'question_index' => 'required|integer',
                'response' => 'required|integer|min:1|max:7'
            ]);

            $sessionId = $request->input('session_id');
            $questionIndex = $request->input('question_index');
            $response = $request->input('response');

            // Get the test session
            $testSession = MbtiTestSession::findOrFail($sessionId);

            // Update responses
            $responses = $testSession->responses ?? [];
            $responses["q{$questionIndex}"] = $response;

            // Update questions asked
            $questionsAsked = $testSession->questions_asked ?? [];
            if (!in_array($questionIndex, $questionsAsked)) {
                $questionsAsked[] = $questionIndex;
            }

            $testSession->update([
                'responses' => $responses,
                'questions_asked' => $questionsAsked
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Response recorded successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to record response: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to record response',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete the adaptive assessment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeAssessment(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|exists:mbti_test_sessions,id',
                'final_responses' => 'required|array',
                'rl_predictions' => 'array',
                'confidence' => 'numeric|min:0|max:1'
            ]);

            $sessionId = $request->input('session_id');
            $finalResponses = $request->input('final_responses');
            $rlPredictions = $request->input('rl_predictions', []);
            $confidence = $request->input('confidence', 0.5);

            // Get the test session
            $testSession = MbtiTestSession::findOrFail($sessionId);

            // Calculate MBTI type from responses
            $mbtiType = $this->calculateMbtiTypeFromResponses($finalResponses);

            // Calculate efficiency (questions saved)
            $totalQuestions = 60;
            $questionsAsked = count($testSession->questions_asked ?? []);
            $efficiency = ($totalQuestions - $questionsAsked) / $totalQuestions;

            // Update test session
            $testSession->update([
                'responses' => $finalResponses,
                'rl_predictions' => $rlPredictions,
                'final_result' => $mbtiType,
                'confidence' => $confidence,
                'efficiency' => $efficiency,
                'completed' => true,
                'completed_at' => now()
            ]);

            // Update user's MBTI type if authenticated
            if (auth()->check()) {
                auth()->user()->update(['mbti_type' => $mbtiType['type']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Assessment completed successfully',
                'mbti_type' => $mbtiType,
                'efficiency' => $efficiency,
                'confidence' => $confidence,
                'questions_asked' => $questionsAsked,
                'redirect_url' => route('pathfinder.mbti.results')
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to complete assessment: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete assessment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get session data for adaptive assessment
     *
     * @param  int  $sessionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSessionData($sessionId)
    {
        try {
            $testSession = MbtiTestSession::findOrFail($sessionId);

            return response()->json([
                'success' => true,
                'session' => [
                    'id' => $testSession->id,
                    'responses' => $testSession->responses ?? [],
                    'questions_asked' => $testSession->questions_asked ?? [],
                    'rl_predictions' => $testSession->rl_predictions ?? [],
                    'confidence' => $testSession->confidence,
                    'efficiency' => $testSession->efficiency,
                    'completed' => $testSession->completed
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get session data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Session not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get MBTI questions
     *
     * @return array
     */
    private function getMbtiQuestions()
    {
        // This should ideally be stored in a database or config file
        return [
            1 => "I am outgoing and sociable.",
            2 => "I prefer to work alone rather than in a group.",
            3 => "I enjoy being the center of attention.",
            4 => "I find it easy to stay relaxed and focused.",
            5 => "I talk to a lot of different people at parties.",
            6 => "I keep in the background.",
            7 => "I start conversations.",
            8 => "I have little to say.",
            9 => "I feel comfortable around people.",
            10 => "I avoid crowds.",
            11 => "I make friends easily.",
            12 => "I find it difficult to approach others.",
            13 => "I am skilled in handling social situations.",
            14 => "I would describe my experiences as somewhat dull.",
            15 => "I take charge.",
            16 => "I focus on details.",
            17 => "I see the big picture.",
            18 => "I am practical.",
            19 => "I have a vivid imagination.",
            20 => "I prefer to stick with things that I know.",
            21 => "I like to think about complex problems.",
            22 => "I follow directions.",
            23 => "I come up with bold plans.",
            24 => "I stick to conventional approaches.",
            25 => "I like to get lost in thought.",
            26 => "I rarely look for a deeper meaning in things.",
            27 => "I can handle a lot of information.",
            28 => "I like to concentrate on one thing at a time.",
            29 => "I have excellent ideas.",
            30 => "I do not like art.",
            31 => "I make decisions based on facts.",
            32 => "I consider people's feelings when making decisions.",
            33 => "I believe that too much tax money goes to support artists.",
            34 => "I believe laws should be flexible.",
            35 => "I think that all will be well.",
            36 => "I worry about things.",
            37 => "I base my decisions on logic.",
            38 => "I sympathize with others' feelings.",
            39 => "I remain calm under pressure.",
            40 => "I get stressed out easily.",
            41 => "I rarely get irritated.",
            42 => "I get upset easily.",
            43 => "I keep my emotions under control.",
            44 => "I have frequent mood swings.",
            45 => "I am not easily bothered by things.",
            46 => "I like to have everything under control.",
            47 => "I prefer to leave my options open.",
            48 => "I stick to my plans.",
            49 => "I change my plans at the last minute.",
            50 => "I like to organize events.",
            51 => "I prefer spontaneous activities.",
            52 => "I complete tasks successfully.",
            53 => "I often forget to put things back in their proper place.",
            54 => "I like to tidy up.",
            55 => "I leave my belongings around.",
            56 => "I follow a schedule.",
            57 => "I act without thinking.",
            58 => "I get things done quickly.",
            59 => "I postpone decisions.",
            60 => "I work hard."
        ];
    }

    /**
     * Select next question based on current responses
     *
     * @param  array  $currentResponses
     * @param  array  $allQuestions
     * @return int|null
     */
    private function selectNextQuestion($currentResponses, $allQuestions)
    {
        $askedQuestions = array_keys($currentResponses);

        // Simple strategy: ask questions from dimensions with least certainty
        $dimensionCounts = [
            'EI' => 0, // Questions 1-15
            'SN' => 0, // Questions 16-30
            'TF' => 0, // Questions 31-45
            'JP' => 0  // Questions 46-60
        ];

        foreach ($askedQuestions as $questionKey) {
            $questionNum = (int) str_replace('q', '', $questionKey);
            if ($questionNum <= 15) $dimensionCounts['EI']++;
            elseif ($questionNum <= 30) $dimensionCounts['SN']++;
            elseif ($questionNum <= 45) $dimensionCounts['TF']++;
            else $dimensionCounts['JP']++;
        }

        // Find dimension with least questions asked
        $minDimension = array_keys($dimensionCounts, min($dimensionCounts))[0];

        // Select a random question from that dimension
        $dimensionRanges = [
            'EI' => [1, 15],
            'SN' => [16, 30],
            'TF' => [31, 45],
            'JP' => [46, 60]
        ];

        $range = $dimensionRanges[$minDimension];
        $availableQuestions = [];

        for ($i = $range[0]; $i <= $range[1]; $i++) {
            if (!in_array("q{$i}", $askedQuestions)) {
                $availableQuestions[] = $i;
            }
        }

        return empty($availableQuestions) ? null : $availableQuestions[array_rand($availableQuestions)];
    }

    /**
     * Calculate confidence based on current responses
     *
     * @param  array  $responses
     * @return float
     */
    private function calculateConfidence($responses)
    {
        if (empty($responses)) return 0.0;

        // Simple confidence calculation based on response consistency
        $dimensionScores = $this->calculateDimensionScores($responses);

        $confidences = [];
        foreach ($dimensionScores as $dimension => $scores) {
            $total = $scores['positive'] + $scores['negative'];
            if ($total > 0) {
                $ratio = max($scores['positive'], $scores['negative']) / $total;
                $confidences[] = $ratio;
            }
        }

        return empty($confidences) ? 0.0 : array_sum($confidences) / count($confidences);
    }

    /**
     * Calculate dimension scores from responses
     *
     * @param  array  $responses
     * @return array
     */
    private function calculateDimensionScores($responses)
    {
        $scores = [
            'EI' => ['positive' => 0, 'negative' => 0],
            'SN' => ['positive' => 0, 'negative' => 0],
            'TF' => ['positive' => 0, 'negative' => 0],
            'JP' => ['positive' => 0, 'negative' => 0]
        ];

        foreach ($responses as $questionKey => $response) {
            $questionNum = (int) str_replace('q', '', $questionKey);
            $score = (int) $response;

            if ($questionNum <= 15) {
                // E/I dimension
                if (in_array($questionNum, [2, 4, 6, 8, 10, 12, 14])) {
                    $scores['EI']['negative'] += $score;
                } else {
                    $scores['EI']['positive'] += $score;
                }
            } elseif ($questionNum <= 30) {
                // S/N dimension
                if (in_array($questionNum, [17, 19, 21, 23, 25, 27, 29])) {
                    $scores['SN']['negative'] += $score;
                } else {
                    $scores['SN']['positive'] += $score;
                }
            } elseif ($questionNum <= 45) {
                // T/F dimension
                if (in_array($questionNum, [32, 34, 36, 38, 40, 42, 44])) {
                    $scores['TF']['negative'] += $score;
                } else {
                    $scores['TF']['positive'] += $score;
                }
            } else {
                // J/P dimension
                if (in_array($questionNum, [47, 49, 51, 53, 55, 57, 59])) {
                    $scores['JP']['negative'] += $score;
                } else {
                    $scores['JP']['positive'] += $score;
                }
            }
        }

        return $scores;
    }

    /**
     * Calculate MBTI type from responses
     *
     * @param  array  $responses
     * @return array
     */
    private function calculateMbtiTypeFromResponses($responses)
    {
        $scores = $this->calculateDimensionScores($responses);

        $type = '';
        $type .= $scores['EI']['positive'] > $scores['EI']['negative'] ? 'E' : 'I';
        $type .= $scores['SN']['positive'] > $scores['SN']['negative'] ? 'S' : 'N';
        $type .= $scores['TF']['positive'] > $scores['TF']['negative'] ? 'T' : 'F';
        $type .= $scores['JP']['positive'] > $scores['JP']['negative'] ? 'J' : 'P';

        return [
            'type' => $type,
            'scores' => $scores,
            'dimensions' => [
                'EI' => $scores['EI']['positive'] > $scores['EI']['negative'] ? 'E' : 'I',
                'SN' => $scores['SN']['positive'] > $scores['SN']['negative'] ? 'S' : 'N',
                'TF' => $scores['TF']['positive'] > $scores['TF']['negative'] ? 'T' : 'F',
                'JP' => $scores['JP']['positive'] > $scores['JP']['negative'] ? 'J' : 'P'
            ]
        ];
    }

    /**
     * Display the adaptive MBTI assessment form
     *
     * @return \Illuminate\View\View
     */
    public function showAdaptiveAssessment()
    {
        return view('pathfinder.mbti-adaptive');
    }

    /**
     * Process the adaptive MBTI assessment results
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processAdaptiveAssessment(Request $request)
    {
        // Validate the request
        $request->validate([
            'results' => 'required|array',
            'session_data' => 'required|array'
        ]);

        $results = $request->input('results');
        $sessionData = $request->input('session_data');

        try {
            // Get or create user
            $user = Auth::user();
            if (!$user) {
                // For guest users, we'll still process but won't save to database
                return response()->json([
                    'success' => true,
                    'message' => 'Assessment completed successfully',
                    'guest_mode' => true
                ]);
            }

            // Create test session record
            $testSession = MbtiTestSession::create([
                'user_id' => $user->id,
                'session_type' => 'adaptive',
                'questions_asked' => $sessionData['questionsAsked'] ?? [],
                'responses' => $sessionData['responses'] ?? [],
                'rl_predictions' => $sessionData['rlPredictions'] ?? [],
                'final_result' => $results,
                'questions_used' => $results['questionsUsed'] ?? 0,
                'efficiency' => $results['efficiency'] ?? 0,
                'confidence' => $results['confidence'] ?? 0,
                'completed_at' => now()
            ]);

            // Update user's MBTI type and related data
            $user->update([
                'mbti_type' => $results['primary']['type'] ?? null,
                'mbti_confidence' => $results['confidence'] ?? 0,
                'mbti_assessment_date' => now(),
                'questionnaire_answers' => json_encode($sessionData['responses'] ?? [])
            ]);

            // Update user progress
            UserProgress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'mbti_completed' => true,
                    'mbti_type' => $results['primary']['type'] ?? null,
                    'mbti_confidence' => $results['confidence'] ?? 0,
                    'assessment_efficiency' => $results['efficiency'] ?? 0,
                    'questions_answered' => $results['questionsUsed'] ?? 0,
                    'updated_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Assessment completed and saved successfully',
                'session_id' => $testSession->id,
                'redirect_url' => route('pathfinder.mbti.results')
            ]);

        } catch (\Exception $e) {
            \Log::error('Adaptive MBTI assessment processing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save assessment results',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process the MBTI questionnaire submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processQuestionnaire(Request $request)
    {
        // Validate the request - expecting JSON responses
        $request->validate([
            'responses' => 'required|string',
            'current_page' => 'required|integer|min:1|max:10'
        ]);

        // Decode the JSON responses
        $responses = json_decode($request->input('responses'), true);

        // Validate that we have all 60 questions with valid values
        if (!$responses || count($responses) !== 60) {
            return back()->withErrors(['error' => 'Please complete all questions before submitting.']);
        }

        // Convert responses to the expected format and validate values
        $answers = [];
        for ($i = 1; $i <= 60; $i++) {
            $questionKey = "q{$i}";
            if (!isset($responses[$questionKey]) || !is_numeric($responses[$questionKey]) ||
                $responses[$questionKey] < 1 || $responses[$questionKey] > 7) {
                return back()->withErrors(['error' => "Invalid response for question {$i}."]);
            }
            $answers[$questionKey] = (int) $responses[$questionKey];
        }

        // Calculate MBTI dimension scores using improved algorithm
        // Each dimension has 15 questions (questions 1-15: E/I, 16-30: S/N, 31-45: T/F, 46-60: J/P)

        // Extraversion vs Introversion (Questions 1-15)
        $eScore = 0;
        $iScore = 0;
        $eiQuestions = [
            // Direct E questions (higher score = more extraverted)
            1, 3, 5, 7, 9, 11, 13, 15,
            // Reverse I questions (higher score = more introverted, so reverse for E)
            2, 4, 6, 8, 10, 12, 14
        ];

        foreach ($eiQuestions as $qNum) {
            if (in_array($qNum, [2, 4, 6, 8, 10, 12, 14])) {
                // Reverse scoring for introverted questions
                $eScore += (8 - $answers["q{$qNum}"]);
                $iScore += $answers["q{$qNum}"];
            } else {
                // Direct scoring for extraverted questions
                $eScore += $answers["q{$qNum}"];
                $iScore += (8 - $answers["q{$qNum}"]);
            }
        }

        // Sensing vs Intuition (Questions 16-30)
        $sScore = 0;
        $nScore = 0;
        $snQuestions = [
            // Direct S questions (higher score = more sensing)
            16, 18, 20, 22, 24, 26, 28, 30,
            // Reverse N questions (higher score = more intuitive, so reverse for S)
            17, 19, 21, 23, 25, 27, 29
        ];

        foreach ($snQuestions as $qNum) {
            if (in_array($qNum, [17, 19, 21, 23, 25, 27, 29])) {
                // Reverse scoring for intuitive questions
                $sScore += (8 - $answers["q{$qNum}"]);
                $nScore += $answers["q{$qNum}"];
            } else {
                // Direct scoring for sensing questions
                $sScore += $answers["q{$qNum}"];
                $nScore += (8 - $answers["q{$qNum}"]);
            }
        }

        // Thinking vs Feeling (Questions 31-45)
        $tScore = 0;
        $fScore = 0;
        $tfQuestions = [
            // Direct T questions (higher score = more thinking)
            31, 33, 35, 37, 39, 41, 43, 45,
            // Reverse F questions (higher score = more feeling, so reverse for T)
            32, 34, 36, 38, 40, 42, 44
        ];

        foreach ($tfQuestions as $qNum) {
            if (in_array($qNum, [32, 34, 36, 38, 40, 42, 44])) {
                // Reverse scoring for feeling questions
                $tScore += (8 - $answers["q{$qNum}"]);
                $fScore += $answers["q{$qNum}"];
            } else {
                // Direct scoring for thinking questions
                $tScore += $answers["q{$qNum}"];
                $fScore += (8 - $answers["q{$qNum}"]);
            }
        }

        // Judging vs Perceiving (Questions 46-60)
        $jScore = 0;
        $pScore = 0;
        $jpQuestions = [
            // Direct J questions (higher score = more judging)
            46, 48, 50, 52, 54, 56, 58, 60,
            // Reverse P questions (higher score = more perceiving, so reverse for J)
            47, 49, 51, 53, 55, 57, 59
        ];

        foreach ($jpQuestions as $qNum) {
            if (in_array($qNum, [47, 49, 51, 53, 55, 57, 59])) {
                // Reverse scoring for perceiving questions
                $jScore += (8 - $answers["q{$qNum}"]);
                $pScore += $answers["q{$qNum}"];
            } else {
                // Direct scoring for judging questions
                $jScore += $answers["q{$qNum}"];
                $pScore += (8 - $answers["q{$qNum}"]);
            }
        }

        // Determine MBTI type
        $mbtiType = '';
        $mbtiType .= $eScore > $iScore ? 'E' : 'I';
        $mbtiType .= $sScore > $nScore ? 'S' : 'N';
        $mbtiType .= $tScore > $fScore ? 'T' : 'F';
        $mbtiType .= $jScore > $pScore ? 'J' : 'P';

        // Find the personality type from database
        $personalityType = MbtiPersonalityType::where('type_code', $mbtiType)->first();

        if (!$personalityType) {
            return redirect()->back()->with('error', 'Unable to determine personality type. Please try again.');
        }

        // Calculate percentages for display
        $mbtiScores = [
            'E_I' => [
                'E' => round(($eScore / ($eScore + $iScore)) * 100),
                'I' => round(($iScore / ($eScore + $iScore)) * 100)
            ],
            'S_N' => [
                'S' => round(($sScore / ($sScore + $nScore)) * 100),
                'N' => round(($nScore / ($sScore + $nScore)) * 100)
            ],
            'T_F' => [
                'T' => round(($tScore / ($tScore + $fScore)) * 100),
                'F' => round(($fScore / ($tScore + $fScore)) * 100)
            ],
            'J_P' => [
                'J' => round(($jScore / ($jScore + $pScore)) * 100),
                'P' => round(($pScore / ($jScore + $pScore)) * 100)
            ]
        ];

        // Create test session record
        $testSession = MbtiTestSession::create([
            'user_id' => Auth::id(),
            'responses' => $answers,
            'e_score' => $eScore,
            'i_score' => $iScore,
            's_score' => $sScore,
            'n_score' => $nScore,
            't_score' => $tScore,
            'f_score' => $fScore,
            'j_score' => $jScore,
            'p_score' => $pScore,
            'result_type' => $mbtiType,
            'personality_type_id' => $personalityType->id,
            'completed' => true,
            'completed_at' => now()
        ]);

        // Save to user profile if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'mbti_type' => $mbtiType,
                'mbti_scores' => $mbtiScores,
                'mbti_description' => $personalityType->description
            ]);

            // Store career recommendations in user_progress
            $this->storeCareerRecommendations($user->id, $mbtiType, $personalityType);
        } else {
            // Store in session for guest users
            session([
                'mbti_type' => $mbtiType,
                'mbti_scores' => $mbtiScores,
                'mbti_description' => $personalityType->description,
                'personality_type' => $personalityType
            ]);
        }

        return redirect()->route('pathfinder.mbti.results')
            ->with('success', 'Your MBTI assessment has been completed!');
    }

    /**
     * Display the MBTI results
     *
     * @return \Illuminate\View\View
     */
    public function showResults()
    {
        $personalityType = null;

        if (Auth::check()) {
            $user = Auth::user();
            $mbtiType = $user->mbti_type;
            $mbtiScores = $user->mbti_scores;
            $mbtiDescription = $user->mbti_description;

            // Get the full personality type from database
            if ($mbtiType) {
                $personalityType = MbtiPersonalityType::where('type_code', $mbtiType)->first();
            }
        } else {
            // For guest users, get from session
            $mbtiType = session('mbti_type');
            $mbtiScores = session('mbti_scores');
            $mbtiDescription = session('mbti_description');
            $personalityType = session('personality_type');

            if (!$mbtiType) {
                return redirect()->route('pathfinder.mbti-questionnaire')
                    ->with('error', 'Please complete the MBTI questionnaire first.');
            }
        }

        // If we have personality type from database, use its data
        if ($personalityType) {
            $mbtiDescription = $personalityType->description;
        }

        $careerRecommendations = $this->getMbtiCareerRecommendations($mbtiType);
        $learningStyle = $this->getMbtiLearningStyle($mbtiType);

        // Get course and job recommendations with compatibility scores
        $courseRecommendations = [];
        $jobRecommendations = [];

        if (Auth::check()) {
            // Get top 6 course recommendations with compatibility scores
            $courseRecommendations = Course::with('recommendedUsers')
                ->whereHas('recommendedUsers', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get()
                ->map(function($course) {
                    $pivot = $course->recommendedUsers->where('id', Auth::id())->first()->pivot ?? null;
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'description' => $course->description,
                        'provider' => $course->provider,
                        'url' => $course->url,
                        'compatibility_score' => $pivot ? $pivot->compatibility_score : 0,
                        'compatibility_explanation' => $pivot ? $pivot->compatibility_explanation : ''
                    ];
                })
                ->sortByDesc('compatibility_score')
                ->take(6)
                ->values();

            // Get top 6 job recommendations with compatibility scores
            $jobRecommendations = Job::with('recommendedUsers')
                ->whereHas('recommendedUsers', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->get()
                ->map(function($job) {
                    $pivot = $job->recommendedUsers->where('id', Auth::id())->first()->pivot ?? null;
                    return [
                        'id' => $job->id,
                        'title' => $job->title,
                        'description' => $job->description,
                        'company' => $job->company,
                        'location' => $job->location,
                        'salary_range' => $job->salary_range,
                        'url' => $job->url,
                        'compatibility_score' => $pivot ? $pivot->compatibility_score : 0,
                        'compatibility_explanation' => $pivot ? $pivot->compatibility_explanation : ''
                    ];
                })
                ->sortByDesc('compatibility_score')
                ->take(6)
                ->values();
        }

        return view('pathfinder.mbti-results', compact(
            'mbtiType',
            'mbtiScores',
            'mbtiDescription',
            'careerRecommendations',
            'learningStyle',
            'personalityType',
            'courseRecommendations',
            'jobRecommendations'
        ));
    }

    /**
     * Get MBTI type description
     *
     * @param string $mbtiType
     * @return string
     */
    private function getMbtiDescription($mbtiType)
    {
        $descriptions = [
            'ISTJ' => 'Quiet, serious, earn success by thoroughness and dependability. Practical, matter-of-fact, realistic, and responsible. Decide logically what should be done and work toward it steadily, regardless of distractions. Take pleasure in making everything orderly and organized - their work, their home, their life. Value traditions and loyalty.',
            'ISFJ' => 'Quiet, friendly, responsible, and conscientious. Committed and steady in meeting their obligations. Thorough, painstaking, and accurate. Loyal, considerate, notice and remember specifics about people who are important to them, concerned with how others feel. Strive to create an orderly and harmonious environment at work and at home.',
            'INFJ' => 'Seek meaning and connection in ideas, relationships, and material possessions. Want to understand what motivates people and are insightful about others. Conscientious and committed to their firm values. Develop a clear vision about how best to serve the common good. Organized and decisive in implementing their vision.',
            'INTJ' => 'Have original minds and great drive for implementing their ideas and achieving their goals. Quickly see patterns in external events and develop long-range explanatory perspectives. When committed, organize a job and carry it through. Skeptical and independent, have high standards of competence and performance - for themselves and others.',
            'ISTP' => 'Tolerant and flexible, quiet observers until a problem appears, then act quickly to find workable solutions. Analyze what makes things work and readily get through large amounts of data to isolate the core of practical problems. Interested in cause and effect, organize facts using logical principles, value efficiency.',
            'ISFP' => 'Quiet, friendly, sensitive, and kind. Enjoy the present moment, what\'s going on around them. Like to have their own space and to work within their own time frame. Loyal and committed to their values and to people who are important to them. Dislike disagreements and conflicts, do not force their opinions or values on others.',
            'INFP' => 'Idealistic, loyal to their values and to people who are important to them. Want an external life that is congruent with their values. Curious, quick to see possibilities, can be catalysts for implementing ideas. Seek to understand people and to help them fulfill their potential. Adaptable, flexible, and accepting unless a value is threatened.',
            'INTP' => 'Seek to develop logical explanations for everything that interests them. Theoretical and abstract, interested more in ideas than in social interaction. Quiet, contained, flexible, and adaptable. Have unusual ability to focus in depth to solve problems in their area of interest. Skeptical, sometimes critical, always analytical.',
            'ESTP' => 'Flexible and tolerant, they take a pragmatic approach focused on immediate results. Theories and conceptual explanations bore them - they want to act energetically to solve the problem. Focus on the here-and-now, spontaneous, enjoy each moment that they can be active with others. Enjoy material comforts and style. Learn best through doing.',
            'ESFP' => 'Outgoing, friendly, and accepting. Exuberant lovers of life, people, and material comforts. Enjoy working with others to make things happen. Bring common sense and a realistic approach to their work, and make work fun. Flexible and spontaneous, adapt readily to new people and environments.',
            'ENFP' => 'Warmly enthusiastic and imaginative. See life as full of possibilities. Make connections between events and information very quickly, and confidently proceed based on the patterns they see. Want a lot of affirmation from others, and readily give appreciation and support. Spontaneous and flexible, often rely on their ability to improvise and their verbal fluency.',
            'ENTP' => 'Quick, ingenious, stimulating, alert, and outspoken. Resourceful in solving new and challenging problems. Adept at generating conceptual possibilities and then analyzing them strategically. Good at reading other people. Bored by routine, will seldom do the same thing the same way, apt to turn to one new interest after another.',
            'ESTJ' => 'Practical, realistic, matter-of-fact. Decisive, quickly move to implement decisions. Organize projects and people to get things done, focus on getting results in the most efficient way possible. Take care of routine details. Have a clear set of logical standards, systematically follow them and want others to also. Forceful in implementing their plans.',
            'ESFJ' => 'Warmhearted, conscientious, and cooperative. Want harmony in their environment, work with determination to establish it. Like to work with others to complete tasks accurately and on time. Loyal, follow through even in small matters. Notice what others need in their day-by-day lives and try to provide it. Want to be appreciated for who they are and for what they contribute.',
            'ENFJ' => 'Warm, empathetic, responsive, and responsible. Highly attuned to the emotions, needs, and motivations of others. Find potential in everyone, want to help others fulfill their potential. May act as catalysts for individual and group growth. Loyal, responsive to praise and criticism. Sociable, facilitate others in a group, and provide inspiring leadership.',
            'ENTJ' => 'Frank, decisive, assume leadership readily. Quickly see illogical and inefficient procedures and policies, develop and implement comprehensive systems to solve organizational problems. Enjoy long-term planning and goal setting. Usually well informed, well read, enjoy expanding their knowledge and passing it on to others. Forceful in presenting their ideas.'
        ];

        return $descriptions[$mbtiType] ?? 'Description not available for this personality type.';
    }

    /**
     * Get career recommendations based on MBTI type
     *
     * @param string $mbtiType
     * @return array   Each element: ['name' => string, 'description' => string]
     */
    private function getMbtiCareerRecommendations($mbtiType)
    {
        // Descriptions per role per MBTI type explaining WHY this role fits
        $data = [
            'ISTJ' => [
                ['name' => 'Software Developer',     'description' => 'ISTJs excel at systematic problem-solving and attention to detail — core skills for writing reliable, well-structured code in a structured development environment.'],
                ['name' => 'Database Administrator', 'description' => 'ISTJs\' love for order and precision makes them natural DBAs, maintaining data integrity and enforcing consistent rules across complex systems.'],
                ['name' => 'Cybersecurity Analyst',  'description' => 'The methodical mindset and strong sense of responsibility of ISTJs is ideal for monitoring threats and enforcing security protocols with consistency.'],
                ['name' => 'Financial Analyst',      'description' => 'ISTJs are hardworking, dependable, and data-driven — qualities essential for accurate financial modeling and delivering reliable analysis.'],
                ['name' => 'Compliance Officer',     'description' => 'ISTJs deeply respect rules and procedures, making them well-suited to ensure policies are followed and regulatory standards are met.'],
            ],
            'ISFJ' => [
                ['name' => 'IT Support Specialist',      'description' => 'ISFJs are patient, thorough, and genuinely care about helping others — perfect for resolving technical issues and providing user-centered IT support.'],
                ['name' => 'Staff Nurse',                'description' => 'ISFJs\' warmth, attention to detail, and dedication to serving others make nursing a natural calling where they can make a meaningful daily difference.'],
                ['name' => 'Educational Coordinator',    'description' => 'ISFJs are organized and supportive, allowing them to effectively coordinate programs that help students and staff thrive academically.'],
                ['name' => 'Human Resources Specialist', 'description' => 'The empathetic and dependable nature of ISFJs enables them to support employees, manage sensitive concerns, and build a caring workplace culture.'],
                ['name' => 'Administrative Officer',     'description' => 'ISFJs are reliable and task-oriented, making them excellent at maintaining smooth administrative operations and supporting teams behind the scenes.'],
            ],
            'INFJ' => [
                ['name' => 'Software Developer',         'description' => 'INFJs combine creativity and structured thinking to build software with a deeper purpose, especially in products that improve people\'s lives.'],
                ['name' => 'Data Analyst',               'description' => 'INFJs\' intuition for finding meaningful patterns, combined with their drive to help others, allows them to turn raw data into actionable human insights.'],
                ['name' => 'Content Writer',             'description' => 'INFJs are gifted communicators with a strong internal vision — traits that allow them to craft authentic, impactful written content that resonates deeply.'],
                ['name' => 'Human Resources Specialist', 'description' => 'INFJs are empathetic and perceptive, giving them an innate ability to understand people\'s needs and foster a nurturing and fair workplace.'],
                ['name' => 'Educational Coordinator',    'description' => 'INFJs are passionate about personal growth and meaningful learning experiences, making them effective at designing and coordinating programs that inspire students.'],
            ],
            'INTJ' => [
                ['name' => 'Software Developer',    'description' => 'INTJs are strategic, independent thinkers who excel at designing complex systems and solving architectural-level engineering challenges efficiently.'],
                ['name' => 'Data Analyst',          'description' => 'INTJs thrive on identifying patterns in large datasets and translating those insights into long-term strategic recommendations.'],
                ['name' => 'Cybersecurity Analyst', 'description' => 'INTJs\' ability to think several steps ahead and anticipate threats makes them highly effective at proactively defending systems against sophisticated attacks.'],
                ['name' => 'Systems Administrator', 'description' => 'INTJs are disciplined and forward-thinking, ensuring system reliability and performance through careful planning and logical troubleshooting.'],
                ['name' => 'Financial Analyst',     'description' => 'INTJs are analytically rigorous and goal-oriented, enabling them to build sophisticated financial models and drive data-backed strategic decisions.'],
            ],
            'ISTP' => [
                ['name' => 'Cybersecurity Analyst',  'description' => 'ISTPs are calm under pressure and enjoy diagnosing complex technical problems — ideal for identifying vulnerabilities and responding to security incidents.'],
                ['name' => 'Network Administrator',  'description' => 'ISTPs love understanding how systems work at a granular level, making them excellent at configuring and troubleshooting network infrastructure.'],
                ['name' => 'Systems Administrator',  'description' => 'ISTPs are hands-on and adaptable, allowing them to efficiently manage servers and quickly resolve operational issues as they arise.'],
                ['name' => 'Computer Engineer',      'description' => 'ISTPs\' practical, logical mindset is perfectly suited to hardware-software integration challenges in computer engineering.'],
                ['name' => 'Electronics Engineer',   'description' => 'ISTPs enjoy working with physical systems and have strong problem-solving ability — skills directly applied in circuit design and electronics troubleshooting.'],
            ],
            'ISFP' => [
                ['name' => 'Web Developer',           'description' => 'ISFPs blend aesthetic sensibility with technical skill, making them talented at creating visually appealing, user-friendly web experiences.'],
                ['name' => 'IT Support Specialist',   'description' => 'ISFPs are kind and patient, and they enjoy helping people solve problems in a calm and empathetic way — a natural fit for hands-on user support.'],
                ['name' => 'Staff Nurse',             'description' => 'ISFPs are compassionate and present-focused, giving them the empathy and attentiveness needed to provide personalized, caring patient care.'],
                ['name' => 'Physical Therapist',      'description' => 'ISFPs are hands-on and motivated by helping others recover and improve — qualities that align well with the patient-centered work of physical therapy.'],
                ['name' => 'Occupational Therapist',  'description' => 'ISFPs\' creativity and deep care for individuals allows them to design meaningful therapeutic activities that support clients\' daily living and wellbeing.'],
            ],
            'INFP' => [
                ['name' => 'Content Writer',           'description' => 'INFPs are imaginative and value authentic self-expression — qualities that allow them to produce compelling, values-driven written content.'],
                ['name' => 'Social Worker',            'description' => 'INFPs are idealistic and empathetic, driven by a deep desire to advocate for vulnerable people and create meaningful positive change.'],
                ['name' => 'Educational Coordinator',  'description' => 'INFPs bring creativity and genuine care for students\' development, making them effective at shaping educational programs that are inspiring and inclusive.'],
                ['name' => 'Curriculum Developer',     'description' => 'INFPs\' love of ideas and learning, combined with their creativity, enables them to design meaningful and engaging curriculum content.'],
                ['name' => 'Communications Specialist','description' => 'INFPs use their expressive abilities and emotional intelligence to craft clear, empathetic communication strategies that connect with diverse audiences.'],
            ],
            'INTP' => [
                ['name' => 'Software Developer',     'description' => 'INTPs are natural problem-solvers who love exploring elegant solutions to complex challenges — at the core of what software development demands.'],
                ['name' => 'Data Analyst',           'description' => 'INTPs are drawn to discovering underlying logic in data, and bring rigorous analytical thinking that transforms complex datasets into clear insights.'],
                ['name' => 'Database Administrator', 'description' => 'INTPs\' affinity for structured, logical systems makes them well-suited to managing and optimizing relational database architectures.'],
                ['name' => 'Web Developer',          'description' => 'INTPs enjoy building systems from scratch and experimenting with new approaches, making web development a satisfying area for their curiosity.'],
                ['name' => 'Network Administrator',  'description' => 'INTPs\' desire to understand how systems communicate and interconnect drives them to master the intricacies of network design and administration.'],
            ],
            'ESTP' => [
                ['name' => 'Sales Representative',         'description' => 'ESTPs are persuasive, energetic, and action-oriented — traits that help them excel at building client rapport and closing deals.'],
                ['name' => 'IT Support Specialist',        'description' => 'ESTPs thrive in fast-paced, problem-solving environments where they can directly help users with hands-on technical fixes.'],
                ['name' => 'Marketing Coordinator',        'description' => 'ESTPs are observant and opportunistic, identifying market trends quickly and executing campaigns with urgency and practical flair.'],
                ['name' => 'Operations Manager',           'description' => 'ESTPs are decisive and results-focused, making them effective at managing day-to-day operations and mobilizing teams to hit targets.'],
                ['name' => 'Business Development Manager', 'description' => 'ESTPs\' boldness and ability to read people gives them a natural edge in identifying new business opportunities and negotiating deals.'],
            ],
            'ESFP' => [
                ['name' => 'Customer Service Representative','description' => 'ESFPs are warm, energetic, and genuinely enjoy connecting with people — making them natural at delivering outstanding customer experiences.'],
                ['name' => 'Sales Representative',          'description' => 'ESFPs are enthusiastic and people-focused, able to build genuine connections that turn conversations into lasting client relationships.'],
                ['name' => 'Event Coordinator',             'description' => 'ESFPs love creating memorable experiences for others and excel at the coordination, creativity, and people management events require.'],
                ['name' => 'Hotel Front Desk Agent',        'description' => 'ESFPs thrive in guest-facing roles where their friendliness and spontaneity create positive first impressions and memorable stays.'],
                ['name' => 'Marketing Coordinator',         'description' => 'ESFPs are creative and socially savvy — qualities that allow them to connect brands to audiences through engaging, authentic marketing.'],
            ],
            'ENFP' => [
                ['name' => 'Marketing Coordinator',         'description' => 'ENFPs are creative, enthusiastic, and skilled at reading people — perfect for crafting campaigns that inspire and engage target audiences.'],
                ['name' => 'Content Writer',                'description' => 'ENFPs\' natural curiosity and expressive communication style makes them compelling writers who can explore diverse topics with genuine enthusiasm.'],
                ['name' => 'Human Resources Specialist',    'description' => 'ENFPs are people-oriented and empathetic, drawn to building positive workplace cultures and helping individuals grow within an organization.'],
                ['name' => 'Public Relations Officer',      'description' => 'ENFPs are charismatic communicators who excel at building relationships and crafting narratives that shape positive public perception.'],
                ['name' => 'Educational Coordinator',       'description' => 'ENFPs are energized by helping others discover their potential, making them highly effective at coordinating inspiring educational programs.'],
            ],
            'ENTP' => [
                ['name' => 'Software Developer',            'description' => 'ENTPs are inventive and love solving challenging problems — qualities at the heart of technical innovation in software development.'],
                ['name' => 'Business Development Manager',  'description' => 'ENTPs are strategic and entrepreneurial, naturally identifying new business opportunities and convincing stakeholders to pursue them.'],
                ['name' => 'Financial Analyst',             'description' => 'ENTPs analyze complex systems and love debating ideas — skills that translate directly into identifying investment opportunities and market trends.'],
                ['name' => 'Operations Manager',            'description' => 'ENTPs challenge inefficiencies and enjoy redesigning processes, making them effective at driving measurable operational improvements.'],
                ['name' => 'Market Research Analyst',       'description' => 'ENTPs are intellectually curious and love uncovering hidden patterns in data to challenge assumptions and inform strategic decisions.'],
            ],
            'ESTJ' => [
                ['name' => 'Operations Manager',     'description' => 'ESTJs are natural leaders who thrive in structured environments, ensuring teams operate efficiently and consistently deliver results.'],
                ['name' => 'Financial Analyst',      'description' => 'ESTJs are disciplined, logical, and goal-oriented — well-suited to financial modeling, budgeting, and communicating recommendations clearly.'],
                ['name' => 'Administrative Officer', 'description' => 'ESTJs excel at maintaining order and following processes, making them effective at managing administrative workflows and organizational systems.'],
                ['name' => 'Compliance Officer',     'description' => 'ESTJs\' strong respect for rules and organizational standards makes them ideal for enforcing regulatory policies and maintaining compliance.'],
                ['name' => 'Systems Administrator',  'description' => 'ESTJs are systematic and responsible — critical traits for maintaining IT infrastructure reliability and managing system operations.'],
            ],
            'ESFJ' => [
                ['name' => 'Human Resources Specialist',     'description' => 'ESFJs are warm and people-focused, making them natural at supporting employees, managing conflict, and building a positive workplace culture.'],
                ['name' => 'Customer Service Representative','description' => 'ESFJs are caring and attentive, genuinely motivated to help others and ensure every customer interaction feels positive and meaningful.'],
                ['name' => 'Elementary School Teacher',      'description' => 'ESFJs are nurturing and organized, creating safe and structured learning environments where young students feel supported and inspired.'],
                ['name' => 'Staff Nurse',                    'description' => 'ESFJs combine conscientiousness with genuine compassion, making them dedicated caregivers who put patients\' wellbeing first.'],
                ['name' => 'Administrative Officer',         'description' => 'ESFJs are dependable and cooperative, ensuring administrative tasks are handled smoothly and that everyone they support feels well taken care of.'],
            ],
            'ENFJ' => [
                ['name' => 'Human Resources Specialist',  'description' => 'ENFJs are charismatic and deeply empathetic, inspiring trust and helping employees develop their full potential within the organization.'],
                ['name' => 'Educational Coordinator',     'description' => 'ENFJs are natural mentors passionate about learning; they excel at coordinating programs that make a lasting impact on learners.'],
                ['name' => 'Social Worker',               'description' => 'ENFJs are driven by a genuine desire to uplift others, making them effective advocates for individuals and communities facing hardship.'],
                ['name' => 'Communications Specialist',   'description' => 'ENFJs are perceptive and articulate, expertly tailoring messages to move people to action and convey meaningful information.'],
                ['name' => 'Public Relations Officer',    'description' => 'ENFJs are skilled at building authentic relationships and managing narratives, bringing emotional intelligence to every public-facing interaction.'],
            ],
            'ENTJ' => [
                ['name' => 'Business Development Manager', 'description' => 'ENTJs are visionary leaders who confidently identify and pursue new growth opportunities, leveraging strategic relationships and clear objectives.'],
                ['name' => 'Operations Manager',           'description' => 'ENTJs bring commanding leadership and decisive action to operations, quickly diagnosing inefficiencies and driving teams toward measurable outcomes.'],
                ['name' => 'Financial Analyst',            'description' => 'ENTJs thrive on using data to drive large-scale strategic decisions, combining analytical rigor with strong business acumen.'],
                ['name' => 'Software Developer',           'description' => 'ENTJs approach software development with strategic foresight, designing scalable solutions and leading technical teams with clarity and purpose.'],
                ['name' => 'Systems Administrator',        'description' => 'ENTJs are organized and results-driven, ensuring that IT systems operate at peak performance and that issues are resolved swiftly.'],
            ],
        ];

        return $data[$mbtiType] ?? [
            ['name' => 'Software Developer', 'description' => 'This career aligns well with your personality strengths and natural work style.'],
        ];
    }

    /**
     * Get learning style recommendations based on MBTI type
     *
     * @param string $mbtiType
     * @return array
     */
    private function getMbtiLearningStyle($mbtiType)
    {
        $styles = [
            // Sensing-Thinking types
            'ISTJ' => [
                'style' => 'Structured and Sequential Learning',
                'description' => 'You learn best with clear, step-by-step instructions and practical applications. You prefer organized learning environments with concrete examples and detailed explanations.',
                'recommendations' => [
                    'Use detailed checklists and schedules',
                    'Take thorough notes and organize them systematically',
                    'Practice with real-world examples and case studies',
                    'Break complex concepts into logical steps',
                    'Seek out structured courses with clear objectives'
                ]
            ],
            'ESTJ' => [
                'style' => 'Practical and Organized Learning',
                'description' => 'You learn best in structured environments with clear objectives and practical applications. You prefer logical organization and efficiency in learning.',
                'recommendations' => [
                    'Create structured study plans with deadlines',
                    'Participate in group discussions and debates',
                    'Apply concepts to real-world scenarios',
                    'Use traditional learning methods with clear metrics',
                    'Take leadership roles in group projects'
                ]
            ],
            'ISTP' => [
                'style' => 'Hands-on Technical Learning',
                'description' => 'You learn best through direct experience and hands-on problem-solving. You prefer practical applications and understanding how things work.',
                'recommendations' => [
                    'Engage in hands-on labs and experiments',
                    'Take apart and rebuild systems to understand them',
                    'Focus on practical skills with immediate application',
                    'Learn through troubleshooting real problems',
                    'Use simulations and interactive tutorials'
                ]
            ],
            'ESTP' => [
                'style' => 'Active and Experiential Learning',
                'description' => 'You learn best through active engagement and real-time problem-solving. You prefer dynamic, interactive learning environments with immediate results.',
                'recommendations' => [
                    'Participate in interactive workshops and hackathons',
                    'Learn through competitive challenges',
                    'Engage in role-playing and simulations',
                    'Seek opportunities for immediate application',
                    'Use trial and error with quick feedback loops'
                ]
            ],

            // Sensing-Feeling types
            'ISFJ' => [
                'style' => 'Supportive and Practical Learning',
                'description' => 'You learn best in harmonious environments with clear, practical applications. You prefer structured learning with personal relevance.',
                'recommendations' => [
                    'Connect learning to helping others',
                    'Use study groups with supportive peers',
                    'Follow clear, step-by-step tutorials',
                    'Relate concepts to personal experiences',
                    'Maintain organized notes and resources'
                ]
            ],
            'ESFJ' => [
                'style' => 'Collaborative and Structured Learning',
                'description' => 'You learn best in social, cooperative environments with clear guidelines. You prefer learning that has practical applications and helps others.',
                'recommendations' => [
                    'Participate in study groups and team projects',
                    'Teach concepts to others to reinforce learning',
                    'Connect learning to real-world impact',
                    'Use structured courses with clear milestones',
                    'Seek feedback and validation from instructors'
                ]
            ],
            'ISFP' => [
                'style' => 'Artistic and Hands-on Learning',
                'description' => 'You learn best through creative expression and personal experience. You prefer learning environments that allow for individual exploration and aesthetic appreciation.',
                'recommendations' => [
                    'Incorporate visual and artistic elements',
                    'Learn at your own pace in low-pressure environments',
                    'Connect concepts to personal values',
                    'Use hands-on, creative projects',
                    'Apply skills in personally meaningful ways'
                ]
            ],
            'ESFP' => [
                'style' => 'Interactive and Social Learning',
                'description' => 'You learn best through engaging, social experiences with immediate application. You prefer dynamic, fun learning environments with practical outcomes.',
                'recommendations' => [
                    'Participate in interactive, group-based learning',
                    'Use gamification and competitive elements',
                    'Learn through role-playing and simulations',
                    'Seek courses with social components',
                    'Apply concepts in creative, practical ways'
                ]
            ],

            // Intuitive-Thinking types
            'INTJ' => [
                'style' => 'Strategic and Conceptual Learning',
                'description' => 'You learn best through conceptual understanding and strategic thinking. You prefer independent study with focus on theoretical frameworks and systems thinking.',
                'recommendations' => [
                    'Focus on the big picture and underlying principles',
                    'Develop your own learning strategies and systems',
                    'Seek out challenging, complex material',
                    'Connect new concepts to existing knowledge',
                    'Engage in independent research and analysis'
                ]
            ],
            'ENTJ' => [
                'style' => 'Strategic and Efficient Learning',
                'description' => 'You learn best through organized, efficient study with clear objectives. You prefer conceptual understanding with practical applications and strategic relevance.',
                'recommendations' => [
                    'Create comprehensive learning plans with goals',
                    'Focus on concepts with strategic applications',
                    'Engage in debates and intellectual discussions',
                    'Take leadership in group learning situations',
                    'Seek mentorship from respected experts'
                ]
            ],
            'INTP' => [
                'style' => 'Analytical and Conceptual Learning',
                'description' => 'You learn best through logical analysis and theoretical understanding. You prefer independent exploration of complex systems and abstract concepts.',
                'recommendations' => [
                    'Focus on understanding underlying principles',
                    'Explore connections between different concepts',
                    'Engage in theoretical problem-solving',
                    'Question assumptions and explore alternatives',
                    'Allow time for independent analysis and reflection'
                ]
            ],
            'ENTP' => [
                'style' => 'Innovative and Conceptual Learning',
                'description' => 'You learn best through exploring possibilities and connecting diverse ideas. You prefer dynamic, intellectually stimulating environments with room for debate and innovation.',
                'recommendations' => [
                    'Engage in brainstorming and idea generation',
                    'Explore multiple perspectives and approaches',
                    'Participate in debates and intellectual discussions',
                    'Connect concepts across different domains',
                    'Experiment with new learning methods and tools'
                ]
            ],

            // Intuitive-Feeling types
            'INFJ' => [
                'style' => 'Insightful and Meaningful Learning',
                'description' => 'You learn best when material connects to values and long-term vision. You prefer quiet, focused environments with conceptual depth and personal meaning.',
                'recommendations' => [
                    'Connect learning to personal values and goals',
                    'Seek out meaningful patterns and insights',
                    'Allow time for reflection and integration',
                    'Focus on how concepts impact people',
                    'Use visualization and conceptual mapping'
                ]
            ],
            'ENFJ' => [
                'style' => 'Collaborative and Meaningful Learning',
                'description' => 'You learn best in harmonious environments focused on personal growth and helping others. You prefer learning with clear purpose and positive social impact.',
                'recommendations' => [
                    'Participate in collaborative learning communities',
                    'Connect concepts to human potential and growth',
                    'Teach and mentor others to reinforce learning',
                    'Seek learning with meaningful real-world impact',
                    'Use discussion and dialogue to explore ideas'
                ]
            ],
            'INFP' => [
                'style' => 'Reflective and Values-Based Learning',
                'description' => 'You learn best when material aligns with personal values and allows for creative expression. You prefer independent exploration guided by personal meaning.',
                'recommendations' => [
                    'Connect learning to personal values and ideals',
                    'Allow time for reflection and creative processing',
                    'Seek authentic, meaningful applications',
                    'Use journaling and personal expression',
                    'Explore concepts through metaphor and storytelling'
                ]
            ],
            'ENFP' => [
                'style' => 'Enthusiastic and Innovative Learning',
                'description' => 'You learn best through creative exploration and connecting diverse ideas. You prefer dynamic, interactive environments that encourage innovation and personal meaning.',
                'recommendations' => [
                    'Explore connections between diverse concepts',
                    'Engage in collaborative brainstorming',
                    'Use creative projects and presentations',
                    'Seek variety and novelty in learning approaches',
                    'Connect learning to personal growth and values'
                ]
            ]
        ];

        return $styles[$mbtiType] ?? [
            'style' => 'Personalized Learning',
            'description' => 'Learning style information not available for this personality type.',
            'recommendations' => ['Try different learning approaches to find what works best for you.']
        ];
    }

    /**
     * Store career recommendations in user_progress table
     *
     * @param int $userId
     * @param string $mbtiType
     * @param MbtiPersonalityType $personalityType
     * @return void
     */
    private function storeCareerRecommendations($userId, $mbtiType, $personalityType)
    {
        // Get career recommendations for this MBTI type
        $careerRecommendations = $this->getMbtiCareerRecommendations($mbtiType);

        // Store MBTI results with career recommendations in user_progress
        UserProgress::create([
            'user_id' => $userId,
            'feature_type' => 'mbti_assessment',
            'assessment_type' => 'personality',
            'questionnaire_answers' => null, // MBTI responses are stored in mbti_test_sessions
            'recommendation' => $mbtiType,
            'analysis_result' => [
                'mbti_type' => $mbtiType,
                'personality_description' => $personalityType->description,
                'career_recommendations' => $careerRecommendations,
                'recommended_at' => now()->toISOString()
            ],
            'completed' => true
        ]);

        // Generate course and job recommendations with compatibility scores
        $this->generateAndStoreRecommendations($userId, $mbtiType);
    }

    /**
     * Generate and store course/job recommendations with compatibility scores
     *
     * @param int $userId
     * @param string $mbtiType
     * @return void
     */
    private function generateAndStoreRecommendations($userId, $mbtiType)
    {
        // Get all active courses and calculate compatibility
        $courses = Course::active()->get();
        foreach ($courses as $course) {
            $compatibilityScore = $this->calculateCourseCompatibility($course, $mbtiType);

            if ($compatibilityScore >= 60) { // Only store high compatibility recommendations
                $course->recommendedUsers()->attach($userId, [
                    'compatibility_score' => $compatibilityScore,
                    'recommended_at' => now()
                ]);
            }
        }

        // Get all active jobs and calculate compatibility
        $jobs = Job::active()->get();
        foreach ($jobs as $job) {
            $compatibilityScore = $this->calculateJobCompatibility($job, $mbtiType);

            if ($compatibilityScore >= 60) { // Only store high compatibility recommendations
                $job->recommendedUsers()->attach($userId, [
                    'compatibility_score' => $compatibilityScore,
                    'recommended_at' => now()
                ]);
            }
        }
    }

    /**
     * Calculate compatibility score between a course and MBTI type
     *
     * @param Course $course
     * @param string $mbtiType
     * @return int
     */
    private function calculateCourseCompatibility($course, $mbtiType)
    {
        if (!$course->mbti_compatibility || !is_array($course->mbti_compatibility)) {
            return 50; // Default neutral score
        }

        return $course->mbti_compatibility[$mbtiType] ?? 50;
    }

    /**
     * Calculate compatibility score between a job and MBTI type
     *
     * @param Job $job
     * @param string $mbtiType
     * @return int
     */
    private function calculateJobCompatibility($job, $mbtiType)
    {
        if (!$job->mbti_compatibility || !is_array($job->mbti_compatibility)) {
            return 50; // Default neutral score
        }

        return $job->mbti_compatibility[$mbtiType] ?? 50;
    }
}
