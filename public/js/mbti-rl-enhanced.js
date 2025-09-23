/**
 * RL-Enhanced MBTI Assessment System
 * Integrates reinforcement learning for adaptive question selection
 * Reduces test length while maintaining or improving accuracy
 */

class MBTIRLEnhanced {
    constructor() {
        // Load the base MBTI system
        let MBTITester;
        if (typeof require !== 'undefined') {
            // Node.js environment
            try {
                MBTITester = require('./mbti-tester.js').MBTITester || require('./mbti-tester.js');
            } catch (e) {
                // Fallback for different export formats
                const mbtiModule = require('./mbti-tester.js');
                MBTITester = mbtiModule.MBTITester || mbtiModule.default || mbtiModule;
            }
        } else {
            // Browser environment
            MBTITester = window.MBTITester;
        }
        
        if (!MBTITester) {
            throw new Error('MBTITester class not found. Make sure mbti-tester.js is loaded.');
        }
        
        this.baseTester = new MBTITester();
        
        // Initialize RL agent
        let MBTIRLAgent;
        if (typeof require !== 'undefined') {
            // Node.js environment
            try {
                MBTIRLAgent = require('./mbti-rl-agent.js').MBTIRLAgent || require('./mbti-rl-agent.js');
            } catch (e) {
                const rlModule = require('./mbti-rl-agent.js');
                MBTIRLAgent = rlModule.MBTIRLAgent || rlModule.default || rlModule;
            }
        } else {
            // Browser environment
            MBTIRLAgent = window.MBTIRLAgent;
        }
        
        if (!MBTIRLAgent) {
            throw new Error('MBTIRLAgent class not found. Make sure mbti-rl-agent.js is loaded.');
        }
        
        this.rlAgent = new MBTIRLAgent();
        
        // Configuration
        this.useRL = true; // Toggle for A/B testing
        this.trainingMode = false;
        
        // Inject question finder into RL agent
        this.rlAgent.findQuestionById = (id) => this.findQuestionById(id);
        
        // Session tracking
        this.currentSession = null;
        this.sessionHistory = [];
    }

    /**
     * Start a new adaptive assessment session
     */
    startAdaptiveAssessment() {
        this.currentSession = {
            id: Date.now(),
            startTime: new Date(),
            responses: [],
            questionsAsked: [],
            rlPredictions: [],
            finalResult: null,
            completed: false
        };

        this.rlAgent.reset();
        
        console.log('🚀 Starting RL-Enhanced MBTI Assessment');
        console.log('This adaptive test will ask 8-15 questions based on your responses.\n');
        
        return this.currentSession.id;
    }

    /**
     * Get next question using RL agent
     */
    getNextQuestion() {
        if (!this.currentSession || this.currentSession.completed) {
            return null;
        }

        const availableQuestions = this.baseTester.questions;
        const nextQuestion = this.rlAgent.selectNextQuestion(availableQuestions);

        if (!nextQuestion) {
            // RL agent decided to stop
            return this.completeAssessment();
        }

        return {
            question: nextQuestion,
            questionNumber: this.currentSession.questionsAsked.length + 1,
            totalQuestions: '8-15 (adaptive)',
            progress: Math.min(100, (this.currentSession.questionsAsked.length / 12) * 100)
        };
    }

    /**
     * Process user response and update RL agent
     */
    processUserResponse(questionId, response) {
        if (!this.currentSession || this.currentSession.completed) {
            throw new Error('No active session');
        }

        const question = this.findQuestionById(questionId);
        if (!question) {
            throw new Error('Question not found');
        }

        // Record response
        this.currentSession.responses.push({
            questionId: questionId,
            response: response,
            timestamp: new Date()
        });

        this.currentSession.questionsAsked.push(questionId);

        // Update RL agent
        this.rlAgent.processResponse(questionId, response, question);

        // Store intermediate prediction for analysis
        const currentPrediction = this.rlAgent.getPredictedType();
        const currentConfidence = this.rlAgent.getAverageConfidence();
        
        this.currentSession.rlPredictions.push({
            questionNumber: this.currentSession.questionsAsked.length,
            predictedType: currentPrediction,
            confidence: currentConfidence,
            dimensionScores: { ...this.rlAgent.current_scores },
            dimensionConfidences: { ...this.rlAgent.confidence_scores }
        });

        console.log(`Question ${this.currentSession.questionsAsked.length}: Response recorded`);
        console.log(`Current prediction: ${currentPrediction} (${(currentConfidence * 100).toFixed(1)}% confidence)`);

        return {
            processed: true,
            currentPrediction: currentPrediction,
            confidence: currentConfidence,
            questionsAsked: this.currentSession.questionsAsked.length
        };
    }

    /**
     * Complete the assessment and return results
     */
    completeAssessment() {
        if (!this.currentSession || this.currentSession.completed) {
            return null;
        }

        // Get final prediction from RL agent
        const rlResult = {
            type: this.rlAgent.getPredictedType(),
            confidence: this.rlAgent.confidence_scores,
            averageConfidence: this.rlAgent.getAverageConfidence(),
            questionsUsed: this.currentSession.questionsAsked.length,
            dimensionScores: this.rlAgent.current_scores
        };

        // Also calculate using traditional method for comparison
        const traditionalResponses = this.mapResponsesToTraditionalFormat();
        const traditionalResult = this.baseTester.calculatePersonalityType(traditionalResponses);

        // Generate comprehensive analysis even with partial data
        const comprehensiveAnalysis = this.generateComprehensiveAnalysis(rlResult);

        // Combine results
        const finalResult = {
            rl: rlResult,
            traditional: traditionalResult,
            primary: rlResult, // Use RL as primary result
            questionsAsked: this.currentSession.questionsAsked.length,
            timeSaved: `${60 - this.currentSession.questionsAsked.length} questions saved`,
            efficiency: `${Math.round((1 - this.currentSession.questionsAsked.length / 60) * 100)}% reduction`,
            sessionId: this.currentSession.id,
            completedAt: new Date(),
            duration: new Date() - this.currentSession.startTime,
            
            // Enhanced comprehensive analysis
            comprehensiveAnalysis: comprehensiveAnalysis,
            dimensionInsights: this.generateDimensionInsights(rlResult),
            alternativeTypes: this.generateAlternativeTypes(rlResult),
            improvementSuggestions: this.generateImprovementSuggestions(rlResult),
            confidenceAnalysis: this.analyzeConfidenceLevels(rlResult)
        };

        // Add personality description
        finalResult.description = this.baseTester.personalityTypes[rlResult.type];

        this.currentSession.finalResult = finalResult;
        this.currentSession.completed = true;
        this.sessionHistory.push(this.currentSession);

        console.log('\n🎉 Assessment Complete!');
        console.log(`Final Type: ${rlResult.type}`);
        console.log(`Questions Used: ${this.currentSession.questionsAsked.length}/60`);
        console.log(`Time Saved: ${finalResult.timeSaved}`);
        console.log(`Efficiency: ${finalResult.efficiency}`);

        return finalResult;
    }

    /**
     * Generate comprehensive analysis even with partial question data
     */
    generateComprehensiveAnalysis(rlResult) {
        const analysis = {
            primaryType: rlResult.type,
            overallConfidence: rlResult.averageConfidence,
            questionsAnalyzed: this.currentSession.questionsAsked.length,
            dataCompleteness: (this.currentSession.questionsAsked.length / 60) * 100,
            
            // Dimension-specific analysis
            dimensions: {
                EI: this.analyzeDimension('EI', rlResult),
                SN: this.analyzeDimension('SN', rlResult),
                TF: this.analyzeDimension('TF', rlResult),
                JP: this.analyzeDimension('JP', rlResult)
            },
            
            // Reliability indicators
            reliability: {
                sufficientData: this.currentSession.questionsAsked.length >= 8,
                highConfidence: rlResult.averageConfidence > 0.7,
                balancedCoverage: this.assessDimensionCoverage(),
                consistentResponses: this.assessResponseConsistency()
            }
        };

        return analysis;
    }

    /**
     * Analyze individual dimension with partial data
     */
    analyzeDimension(dimension, rlResult) {
        const score = rlResult.dimensionScores[dimension];
        const confidence = rlResult.confidence[dimension];
        const preference = rlResult.type[this.getDimensionIndex(dimension)];
        
        // Count questions asked for this dimension
        const dimensionQuestions = this.currentSession.questionsAsked.filter(q => {
            const question = this.findQuestionById(q.id);
            return question && question.dimension === dimension;
        }).length;

        return {
            preference: preference,
            score: score,
            confidence: confidence,
            questionsAsked: dimensionQuestions,
            strength: this.categorizeDimensionStrength(score, confidence),
            reliability: this.assessDimensionReliability(dimensionQuestions, confidence),
            insights: this.generateDimensionSpecificInsights(dimension, preference, score, confidence)
        };
    }

    /**
     * Generate insights for specific dimensions
     */
    generateDimensionSpecificInsights(dimension, preference, score, confidence) {
        const insights = [];
        
        // Confidence-based insights
        if (confidence > 0.8) {
            insights.push(`Strong ${preference} preference with high confidence`);
        } else if (confidence > 0.6) {
            insights.push(`Moderate ${preference} preference`);
        } else {
            insights.push(`Developing ${preference} preference - may benefit from more exploration`);
        }

        // Score-based insights
        const absScore = Math.abs(score);
        if (absScore > 0.7) {
            insights.push('Clear directional preference');
        } else if (absScore > 0.4) {
            insights.push('Moderate directional preference');
        } else {
            insights.push('Balanced tendencies - context may influence behavior');
        }

        return insights;
    }

    /**
     * Generate alternative personality types based on confidence levels
     */
    generateAlternativeTypes(rlResult) {
        const alternatives = [];
        const currentType = rlResult.type;
        
        // For each dimension with low confidence, generate alternative types
        Object.keys(rlResult.confidence).forEach(dimension => {
            if (rlResult.confidence[dimension] < 0.7) {
                const alternativeType = this.flipDimension(currentType, dimension);
                const alternativeConfidence = this.estimateAlternativeConfidence(dimension, rlResult);
                
                alternatives.push({
                    type: alternativeType,
                    reason: `Alternative if ${dimension} preference is different`,
                    probability: alternativeConfidence,
                    dimensionChanged: dimension
                });
            }
        });

        return alternatives.sort((a, b) => b.probability - a.probability);
    }

    /**
     * Generate improvement suggestions based on available data
     */
    generateImprovementSuggestions(rlResult) {
        const suggestions = [];
        
        Object.keys(rlResult.confidence).forEach(dimension => {
            const confidence = rlResult.confidence[dimension];
            const preference = rlResult.type[this.getDimensionIndex(dimension)];
            
            if (confidence < 0.6) {
                suggestions.push({
                    dimension: dimension,
                    priority: 'high',
                    suggestion: `Explore both ${dimension} preferences to better understand your natural tendencies`,
                    reason: 'Low confidence in this dimension'
                });
            }
            
            // Add specific suggestions based on preference
            suggestions.push(...this.getDimensionSpecificSuggestions(dimension, preference, confidence));
        });

        return suggestions;
    }

    /**
     * Get dimension-specific improvement suggestions
     */
    getDimensionSpecificSuggestions(dimension, preference, confidence) {
        const suggestions = [];
        const priority = confidence > 0.7 ? 'medium' : 'high';
        
        const dimensionSuggestions = {
            'EI': {
                'E': [
                    { suggestion: 'Practice active listening to balance your natural tendency to express thoughts', priority },
                    { suggestion: 'Schedule regular quiet time for reflection and planning', priority }
                ],
                'I': [
                    { suggestion: 'Practice expressing your ideas more openly in group settings', priority },
                    { suggestion: 'Engage in collaborative activities to expand your comfort zone', priority }
                ]
            },
            'SN': {
                'S': [
                    { suggestion: 'Practice brainstorming and exploring "what if" scenarios', priority },
                    { suggestion: 'Read about future trends and theoretical concepts in your field', priority }
                ],
                'N': [
                    { suggestion: 'Focus on implementing your ideas with concrete, actionable steps', priority },
                    { suggestion: 'Practice attention to detail in your daily tasks', priority }
                ]
            },
            'TF': {
                'T': [
                    { suggestion: 'Consider the emotional impact of your decisions on others', priority },
                    { suggestion: 'Practice expressing empathy and understanding in conversations', priority }
                ],
                'F': [
                    { suggestion: 'Practice objective analysis before making important decisions', priority },
                    { suggestion: 'Learn to separate personal feelings from logical evaluation', priority }
                ]
            },
            'JP': {
                'J': [
                    { suggestion: 'Practice flexibility by leaving some plans open-ended', priority },
                    { suggestion: 'Embrace spontaneous opportunities when they arise', priority }
                ],
                'P': [
                    { suggestion: 'Create structured routines for important recurring tasks', priority },
                    { suggestion: 'Practice setting and meeting specific deadlines', priority }
                ]
            }
        };

        const dimSuggestions = dimensionSuggestions[dimension]?.[preference] || [];
        return dimSuggestions.map(s => ({
            dimension,
            ...s,
            reason: `To develop balance in ${dimension} dimension`
        }));
    }

    /**
     * Helper methods for comprehensive analysis
     */
    getDimensionIndex(dimension) {
        const indices = { 'EI': 0, 'SN': 1, 'TF': 2, 'JP': 3 };
        return indices[dimension];
    }

    flipDimension(type, dimension) {
        const index = this.getDimensionIndex(dimension);
        const typeArray = type.split('');
        const opposites = { 'E': 'I', 'I': 'E', 'S': 'N', 'N': 'S', 'T': 'F', 'F': 'T', 'J': 'P', 'P': 'J' };
        typeArray[index] = opposites[typeArray[index]];
        return typeArray.join('');
    }

    estimateAlternativeConfidence(dimension, rlResult) {
        return Math.max(0.1, 1 - rlResult.confidence[dimension]);
    }

    categorizeDimensionStrength(score, confidence) {
        if (confidence > 0.8 && Math.abs(score) > 0.7) return 'Very Strong';
        if (confidence > 0.6 && Math.abs(score) > 0.5) return 'Strong';
        if (confidence > 0.4 && Math.abs(score) > 0.3) return 'Moderate';
        return 'Developing';
    }

    assessDimensionReliability(questionsAsked, confidence) {
        if (questionsAsked >= 3 && confidence > 0.7) return 'High';
        if (questionsAsked >= 2 && confidence > 0.5) return 'Moderate';
        return 'Low';
    }

    assessDimensionCoverage() {
        const dimensionCounts = { EI: 0, SN: 0, TF: 0, JP: 0 };
        this.currentSession.questionsAsked.forEach(q => {
            const question = this.findQuestionById(q.id);
            if (question && question.dimension) {
                dimensionCounts[question.dimension]++;
            }
        });
        
        const minCoverage = Math.min(...Object.values(dimensionCounts));
        return minCoverage >= 2; // At least 2 questions per dimension
    }

    assessResponseConsistency() {
        // Simple consistency check based on response patterns
        const responses = this.currentSession.responses;
        if (responses.length < 4) return true; // Too few to assess
        
        const variance = this.calculateResponseVariance(responses);
        return variance < 2.0; // Reasonable consistency threshold
    }

    calculateResponseVariance(responses) {
        const values = responses.map(r => r.response);
        const mean = values.reduce((a, b) => a + b, 0) / values.length;
        const variance = values.reduce((sum, val) => sum + Math.pow(val - mean, 2), 0) / values.length;
        return variance;
    }

    generateDimensionInsights(rlResult) {
        const insights = {};
        Object.keys(rlResult.confidence).forEach(dimension => {
            insights[dimension] = this.analyzeDimension(dimension, rlResult);
        });
        return insights;
    }

    analyzeConfidenceLevels(rlResult) {
        const confidences = Object.values(rlResult.confidence);
        return {
            overall: rlResult.averageConfidence,
            highest: Math.max(...confidences),
            lowest: Math.min(...confidences),
            range: Math.max(...confidences) - Math.min(...confidences),
            distribution: {
                high: confidences.filter(c => c > 0.7).length,
                medium: confidences.filter(c => c >= 0.5 && c <= 0.7).length,
                low: confidences.filter(c => c < 0.5).length
            }
        };
    }

    /**
     * Map RL responses to traditional format for comparison
     */
    mapResponsesToTraditionalFormat() {
        const traditionalResponses = new Array(60).fill(3); // Default neutral responses
        
        this.currentSession.responses.forEach(resp => {
            const questionIndex = resp.questionId - 1; // Convert to 0-based index
            if (questionIndex >= 0 && questionIndex < 60) {
                traditionalResponses[questionIndex] = resp.response;
            }
        });

        return traditionalResponses;
    }

    /**
     * Find question by ID
     */
    findQuestionById(id) {
        return this.baseTester.questions.find(q => q.id === id);
    }

    /**
     * Run complete adaptive assessment with synthetic user
     */
    async runAdaptiveAssessment(syntheticUser) {
        // Start new session
        this.startAdaptiveAssessment();
        
        let questionCount = 0;
        const maxQuestions = 25; // Safety limit
        
        while (questionCount < maxQuestions) {
            const nextQuestionData = this.getNextQuestion();
            
            if (!nextQuestionData || !nextQuestionData.question) {
                // Assessment completed by RL agent
                break;
            }
            
            const question = nextQuestionData.question;
            const response = syntheticUser.answerQuestion(question.id);
            
            this.processUserResponse(question.id, response);
            questionCount++;
            
            // Check if RL agent wants to stop
            if (this.rlAgent.isConfident()) {
                break;
            }
        }
        
        // Complete the assessment
        const result = this.completeAssessment();
        
        return {
            finalType: result?.primary?.type || result?.rl?.type || 'UNKNOWN',
            questionsUsed: this.currentSession.questionsAsked.length,
            totalQuestions: 60,
            finalConfidence: result?.rl?.averageConfidence ? (result.rl.averageConfidence * 100).toFixed(1) : 50,
            efficiency: `${Math.round(((60 - this.currentSession.questionsAsked.length) / 60) * 100)}% reduction`,
            timeSaved: `${60 - this.currentSession.questionsAsked.length} questions saved`,
            rlResult: result?.rl,
            traditionalResult: result?.traditional
        };
    }

    /**
     * Run training session with synthetic data
     */
    async runTrainingSession(episodes = 100) {
        console.log(`🧠 Starting RL Training Session (${episodes} episodes)`);
        this.trainingMode = true;

        const trainingResults = {
            episodes: episodes,
            accuracyHistory: [],
            questionHistory: [],
            rewardHistory: []
        };

        for (let episode = 0; episode < episodes; episode++) {
            // Generate synthetic user
            const targetType = this.getRandomPersonalityType();
            const syntheticUser = new SyntheticUser(targetType);

            // Reset for new episode
            this.rlAgent.reset();
            let totalReward = 0;
            let questionsAsked = 0;

            // Simulate assessment
            while (questionsAsked < 20 && !this.rlAgent.isConfident()) {
                const state = this.rlAgent.getStateKey();
                const nextQuestion = this.rlAgent.selectNextQuestion(this.baseTester.questions);
                
                if (!nextQuestion) break;

                // Get synthetic response
                const response = syntheticUser.answerQuestion(nextQuestion.id);
                
                // Process response
                this.rlAgent.processResponse(nextQuestion.id, response, nextQuestion);
                questionsAsked++;

                // Calculate intermediate reward
                const currentPrediction = this.rlAgent.getPredictedType();
                const reward = this.calculateTrainingReward(targetType, currentPrediction, questionsAsked);
                totalReward += reward;

                // Update Q-values
                const nextState = this.rlAgent.getStateKey();
                this.rlAgent.updateQValue(state, nextQuestion.id, reward, nextState);
            }

            // Final evaluation
            const finalPrediction = this.rlAgent.getPredictedType();
            const finalReward = this.rlAgent.calculateReward(targetType, finalPrediction);
            totalReward += finalReward;

            // Record episode results
            const isCorrect = targetType === finalPrediction;
            this.rlAgent.recordEpisode(isCorrect, questionsAsked, totalReward);

            trainingResults.accuracyHistory.push(isCorrect ? 1 : 0);
            trainingResults.questionHistory.push(questionsAsked);
            trainingResults.rewardHistory.push(totalReward);

            if (episode % 20 === 0) {
                const recentAccuracy = trainingResults.accuracyHistory.slice(-20).reduce((a, b) => a + b, 0) / 20;
                const recentQuestions = trainingResults.questionHistory.slice(-20).reduce((a, b) => a + b, 0) / 20;
                console.log(`Episode ${episode}: Accuracy=${(recentAccuracy * 100).toFixed(1)}%, Avg Questions=${recentQuestions.toFixed(1)}`);
            }
        }

        this.trainingMode = false;

        // Calculate final statistics
        const finalStats = {
            totalEpisodes: episodes,
            finalAccuracy: trainingResults.accuracyHistory.slice(-50).reduce((a, b) => a + b, 0) / 50,
            averageQuestions: trainingResults.questionHistory.reduce((a, b) => a + b, 0) / episodes,
            qTableSize: Object.keys(this.rlAgent.q_table).length,
            trainingComplete: true
        };

        console.log('\n🎓 Training Complete!');
        console.log(`Final Accuracy: ${(finalStats.finalAccuracy * 100).toFixed(1)}%`);
        console.log(`Average Questions: ${finalStats.averageQuestions.toFixed(1)}`);
        console.log(`Q-Table Size: ${finalStats.qTableSize} states`);

        return { trainingResults, finalStats };
    }

    /**
     * Calculate training reward
     */
    calculateTrainingReward(actualType, predictedType, questionsUsed) {
        let reward = 0;

        // Accuracy component
        if (actualType === predictedType) {
            reward += 5;
        } else {
            // Partial credit for correct dimensions
            const actualDims = actualType.split('');
            const predictedDims = predictedType.split('');
            const correctDims = actualDims.filter((dim, i) => dim === predictedDims[i]).length;
            reward += correctDims * 1;
        }

        // Efficiency component
        reward += Math.max(0, (15 - questionsUsed) * 0.2);

        return reward;
    }

    /**
     * Get random personality type for training
     */
    getRandomPersonalityType() {
        const types = Object.keys(this.baseTester.personalityTypes);
        return types[Math.floor(Math.random() * types.length)];
    }

    /**
     * Compare RL vs Traditional approach
     */
    async runComparison(testCases = 50) {
        console.log(`📊 Running RL vs Traditional Comparison (${testCases} test cases)`);

        const results = {
            rl: { correct: 0, totalQuestions: 0, times: [] },
            traditional: { correct: 0, totalQuestions: 0, times: [] },
            testCases: testCases
        };

        for (let i = 0; i < testCases; i++) {
            const targetType = this.getRandomPersonalityType();
            const syntheticUser = new SyntheticUser(targetType);

            // Test RL approach
            const rlStart = Date.now();
            this.rlAgent.reset();
            let rlQuestions = 0;

            while (rlQuestions < 20 && !this.rlAgent.isConfident()) {
                const nextQuestion = this.rlAgent.selectNextQuestion(this.baseTester.questions);
                if (!nextQuestion) break;

                const response = syntheticUser.answerQuestion(nextQuestion.id);
                this.rlAgent.processResponse(nextQuestion.id, response, nextQuestion);
                rlQuestions++;
            }

            const rlPrediction = this.rlAgent.getPredictedType();
            const rlTime = Date.now() - rlStart;

            // Test traditional approach
            const traditionalStart = Date.now();
            const allResponses = this.baseTester.questions.map(q => syntheticUser.answerQuestion(q.id));
            const traditionalResult = this.baseTester.calculatePersonalityType(allResponses);
            const traditionalTime = Date.now() - traditionalStart;

            // Record results
            if (rlPrediction === targetType) results.rl.correct++;
            if (traditionalResult.type === targetType) results.traditional.correct++;

            results.rl.totalQuestions += rlQuestions;
            results.traditional.totalQuestions += 60;

            results.rl.times.push(rlTime);
            results.traditional.times.push(traditionalTime);

            if (i % 10 === 0) {
                console.log(`Completed ${i + 1}/${testCases} test cases`);
            }
        }

        // Calculate statistics
        const comparison = {
            rl: {
                accuracy: (results.rl.correct / testCases * 100).toFixed(1) + '%',
                avgQuestions: (results.rl.totalQuestions / testCases).toFixed(1),
                avgTime: (results.rl.times.reduce((a, b) => a + b, 0) / testCases).toFixed(0) + 'ms',
                efficiency: ((1 - results.rl.totalQuestions / results.traditional.totalQuestions) * 100).toFixed(1) + '%'
            },
            traditional: {
                accuracy: (results.traditional.correct / testCases * 100).toFixed(1) + '%',
                avgQuestions: '60.0',
                avgTime: (results.traditional.times.reduce((a, b) => a + b, 0) / testCases).toFixed(0) + 'ms',
                efficiency: '0%'
            }
        };

        console.log('\n📈 Comparison Results:');
        console.log('RL Approach:');
        console.log(`  Accuracy: ${comparison.rl.accuracy}`);
        console.log(`  Avg Questions: ${comparison.rl.avgQuestions}`);
        console.log(`  Efficiency: ${comparison.rl.efficiency} reduction`);
        console.log('Traditional Approach:');
        console.log(`  Accuracy: ${comparison.traditional.accuracy}`);
        console.log(`  Avg Questions: ${comparison.traditional.avgQuestions}`);

        return comparison;
    }

    /**
     * Get session statistics
     */
    getSessionStats() {
        return {
            totalSessions: this.sessionHistory.length,
            averageQuestions: this.sessionHistory.length > 0 ?
                this.sessionHistory.reduce((sum, s) => sum + s.questionsAsked.length, 0) / this.sessionHistory.length : 0,
            averageTime: this.sessionHistory.length > 0 ?
                this.sessionHistory.reduce((sum, s) => sum + (s.completedAt - s.startTime), 0) / this.sessionHistory.length : 0,
            rlAgentStats: this.rlAgent.getTrainingStats()
        };
    }
}

/**
 * Synthetic User for Training and Testing
 */
class SyntheticUser {
    constructor(personalityType) {
        this.trueType = personalityType;
        this.consistency = 0.7 + Math.random() * 0.25; // 70-95% consistency
        this.responseStyle = Math.random(); // Individual variation
        
        // Parse personality type
        this.traits = {
            E: personalityType[0] === 'E',
            S: personalityType[1] === 'S', 
            T: personalityType[2] === 'T',
            J: personalityType[3] === 'J'
        };
    }

    answerQuestion(questionId) {
        // Find the question (this would be injected in real implementation)
        let questions;
        if (typeof require !== 'undefined') {
            // Node.js environment - load MBTITester
            try {
                const MBTITester = require('./mbti-tester.js').MBTITester || require('./mbti-tester.js');
                questions = new MBTITester().questions;
            } catch (e) {
                const mbtiModule = require('./mbti-tester.js');
                const MBTITester = mbtiModule.MBTITester || mbtiModule.default || mbtiModule;
                questions = new MBTITester().questions;
            }
        } else {
            // Browser environment
            questions = new MBTITester().questions;
        }
        
        const question = questions.find(q => q.id === questionId);
        
        if (!question) return 3; // Neutral if question not found

        let baseResponse = 3; // Start neutral

        // Determine response based on personality and question
        switch (question.dimension) {
            case 'EI':
                baseResponse = this.traits.E === question.extraverted ? 4.5 : 1.5;
                break;
            case 'SN':
                baseResponse = this.traits.S === question.sensing ? 4.5 : 1.5;
                break;
            case 'TF':
                baseResponse = this.traits.T === question.thinking ? 4.5 : 1.5;
                break;
            case 'JP':
                baseResponse = this.traits.J === question.judging ? 4.5 : 1.5;
                break;
        }

        // Add noise based on consistency
        const noise = (Math.random() - 0.5) * 2 * (1 - this.consistency);
        const finalResponse = Math.max(1, Math.min(5, baseResponse + noise));

        return Math.round(finalResponse);
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { MBTIRLEnhanced, SyntheticUser };
}

// Auto-initialize if running in browser
if (typeof window !== 'undefined') {
    window.MBTIRLEnhanced = MBTIRLEnhanced;
    window.SyntheticUser = SyntheticUser;
    console.log('🚀 RL-Enhanced MBTI System Loaded');
    console.log('Usage: const rlMBTI = new MBTIRLEnhanced();');
}