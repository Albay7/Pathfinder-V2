/**
 * Basic Reinforcement Learning Agent for MBTI Assessment
 * Implements Q-learning for adaptive question selection and early stopping
 * Reduces test length while maintaining accuracy
 */

class MBTIRLAgent {
    constructor() {
        this.q_table = {}; // State -> Action values
        this.learning_rate = 0.1;
        this.epsilon = 0.2; // exploration rate
        this.discount_factor = 0.9;
        this.min_confidence_threshold = 0.65; // Lowered from 0.75 for better stopping
        this.max_questions = 60; // Require all 60 questions
        this.min_questions = 8; // Minimum questions before allowing early stop

        // Dynamic thresholds based on question count
        this.confidence_thresholds = {
            early: 0.85,  // High confidence for very early stopping (8-12 questions)
            medium: 0.75, // Medium confidence for moderate stopping (13-18 questions)
            late: 0.65    // Lower confidence for later stopping (19+ questions)
        };

        // Current session state
        this.questions_asked = [];
        this.responses = [];
        this.current_scores = { EI: 0, SN: 0, TF: 0, JP: 0 };
        this.confidence_scores = { EI: 0, SN: 0, TF: 0, JP: 0 };

        // Training data
        this.training_episodes = 0;
        this.performance_history = [];

        // Load pre-trained weights if available
        this.loadPretrainedWeights();
    }

    /**
     * Reset agent state for new assessment
     */
    reset() {
        this.questions_asked = [];
        this.responses = [];
        this.current_scores = { EI: 0, SN: 0, TF: 0, JP: 0 };
        this.confidence_scores = { EI: 0, SN: 0, TF: 0, JP: 0 };
    }

    /**
     * Convert current state to string key for Q-table
     */
    getStateKey() {
        const scores = [
            Math.round(this.current_scores.EI * 10) / 10,
            Math.round(this.current_scores.SN * 10) / 10,
            Math.round(this.current_scores.TF * 10) / 10,
            Math.round(this.current_scores.JP * 10) / 10
        ];
        const questionsCount = this.questions_asked.length;
        const avgConfidence = Math.round(this.getAverageConfidence() * 10) / 10;

        return `${scores.join(',')}_${questionsCount}_${avgConfidence}`;
    }

    /**
     * Calculate average confidence across all dimensions
     */
    getAverageConfidence() {
        const confidences = Object.values(this.confidence_scores);
        return confidences.reduce((sum, conf) => sum + conf, 0) / confidences.length;
    }

    /**
     * Check if agent is confident enough to stop asking questions
     */
    isConfident() {
        // Disabled: require all 60 questions before completion
        return false;
    }

    /**
     * Check if we have strong signals for each dimension
     */
    hasStrongDimensionSignals() {
        const strongThreshold = 0.3; // Absolute score threshold for clear preference
        return Object.values(this.current_scores).every(score =>
            Math.abs(score) >= strongThreshold
        );
    }

    /**
     * Select next question using ε-greedy strategy
     */
    selectNextQuestion(availableQuestions) {
        // Force stop if max questions reached
        if (this.questions_asked.length >= this.max_questions) {
            return null;
        }

        // Stop if confident enough
        if (this.isConfident()) {
            return null;
        }

        const state = this.getStateKey();
        const unaskedQuestions = availableQuestions.filter(q =>
            !this.questions_asked.includes(q.id)
        );

        if (unaskedQuestions.length === 0) {
            return null;
        }

        // ε-greedy selection
        if (Math.random() < this.epsilon) {
            // Explore: select question that balances dimensions
            return this.selectBalancedQuestion(unaskedQuestions);
        } else {
            // Exploit: select best known question
            return this.selectBestQuestion(state, unaskedQuestions);
        }
    }

    /**
     * Select question that balances dimension coverage
     */
    selectBalancedQuestion(unaskedQuestions) {
        // Count questions asked per dimension
        const dimensionCounts = { EI: 0, SN: 0, TF: 0, JP: 0 };
        this.questions_asked.forEach(qId => {
            const question = this.findQuestionById(qId);
            if (question) {
                dimensionCounts[question.dimension]++;
            }
        });

        // Find dimension with fewest questions
        const minCount = Math.min(...Object.values(dimensionCounts));
        const underRepresentedDimensions = Object.keys(dimensionCounts)
            .filter(dim => dimensionCounts[dim] === minCount);

        // Select random question from under-represented dimension
        const targetDimension = underRepresentedDimensions[
            Math.floor(Math.random() * underRepresentedDimensions.length)
        ];

        const dimensionQuestions = unaskedQuestions.filter(q =>
            q.dimension === targetDimension
        );

        return dimensionQuestions.length > 0 ?
            dimensionQuestions[Math.floor(Math.random() * dimensionQuestions.length)] :
            unaskedQuestions[Math.floor(Math.random() * unaskedQuestions.length)];
    }

    /**
     * Select best question based on Q-values
     */
    selectBestQuestion(state, unaskedQuestions) {
        if (!this.q_table[state]) {
            return this.selectBalancedQuestion(unaskedQuestions);
        }

        let bestQuestion = null;
        let bestValue = -Infinity;

        unaskedQuestions.forEach(question => {
            const qValue = this.q_table[state][question.id] || 0;
            if (qValue > bestValue) {
                bestValue = qValue;
                bestQuestion = question;
            }
        });

        return bestQuestion || this.selectBalancedQuestion(unaskedQuestions);
    }

    /**
     * Process user response and update internal state
     */
    processResponse(questionId, response, question) {
        this.questions_asked.push(questionId);
        this.responses.push(response);

        // Update dimension scores
        this.updateDimensionScores(question, response);

        // Update confidence scores
        this.updateConfidenceScores();
    }

    /**
     * Update dimension scores based on response
     */
    updateDimensionScores(question, response) {
        const dimension = question.dimension;
        const normalizedResponse = (response - 3) / 2; // Convert 1-5 to -1 to 1

        switch (dimension) {
            case 'EI':
                const eiScore = question.extraverted ? normalizedResponse : -normalizedResponse;
                this.current_scores.EI = this.updateRunningAverage(this.current_scores.EI, eiScore);
                break;
            case 'SN':
                const snScore = question.sensing ? -normalizedResponse : normalizedResponse;
                this.current_scores.SN = this.updateRunningAverage(this.current_scores.SN, snScore);
                break;
            case 'TF':
                const tfScore = question.thinking ? normalizedResponse : -normalizedResponse;
                this.current_scores.TF = this.updateRunningAverage(this.current_scores.TF, tfScore);
                break;
            case 'JP':
                const jpScore = question.judging ? normalizedResponse : -normalizedResponse;
                this.current_scores.JP = this.updateRunningAverage(this.current_scores.JP, jpScore);
                break;
        }
    }

    /**
     * Update running average for dimension scores
     */
    updateRunningAverage(currentAvg, newValue) {
        const count = this.questions_asked.length;
        return ((currentAvg * (count - 1)) + newValue) / count;
    }

    /**
     * Update confidence scores based on current state
     */
    updateConfidenceScores() {
        Object.keys(this.current_scores).forEach(dimension => {
            const score = Math.abs(this.current_scores[dimension]);
            const questionsForDimension = this.questions_asked.filter(qId => {
                const q = this.findQuestionById(qId);
                return q && q.dimension === dimension;
            }).length;

            // Confidence increases with stronger scores and more questions
            this.confidence_scores[dimension] = Math.min(0.95,
                score * 0.8 + (questionsForDimension / 15) * 0.2
            );
        });
    }

    /**
     * Calculate reward for training with sophisticated multi-factor approach
     */
    calculateReward(actualType, predictedType) {
        let reward = 0;
        const questionCount = this.questions_asked.length;
        const avgConfidence = this.getAverageConfidence();

        // 1. Accuracy reward (most important)
        if (actualType === predictedType) {
            reward += 15; // Increased base reward for correct prediction

            // Bonus for early correct prediction
            if (questionCount <= 12) {
                reward += 8; // High bonus for very early correct prediction
            } else if (questionCount <= 18) {
                reward += 4; // Medium bonus for moderately early prediction
            }
        } else {
            // Partial credit for getting some dimensions right
            const actualDims = this.typeToArray(actualType);
            const predictedDims = this.typeToArray(predictedType);
            const correctDims = actualDims.filter((dim, i) => dim === predictedDims[i]).length;
            reward += correctDims * 2.5; // Increased partial credit

            // Penalty for wrong prediction increases with confidence
            reward -= avgConfidence * 3;
        }

        // 2. Efficiency reward (question count optimization)
        const maxQuestions = 25;
        const optimalRange = [10, 18]; // Optimal question range

        if (questionCount >= optimalRange[0] && questionCount <= optimalRange[1]) {
            // Reward for staying in optimal range
            reward += 6;
        } else if (questionCount < optimalRange[0]) {
            // Bonus for very efficient assessment (if accurate)
            if (actualType === predictedType) {
                reward += 10 - questionCount; // Higher reward for fewer questions when correct
            } else {
                reward -= 5; // Penalty for being too hasty when wrong
            }
        } else {
            // Penalty for using too many questions
            const excessQuestions = questionCount - optimalRange[1];
            reward -= excessQuestions * 0.5;
        }

        // 3. Confidence calibration reward
        const confidenceAccuracy = this.calculateConfidenceAccuracy(actualType, predictedType, avgConfidence);
        reward += confidenceAccuracy * 4;

        // 4. Dimension balance reward
        const dimensionBalance = this.calculateDimensionBalance();
        reward += dimensionBalance * 2;

        // 5. Information gain reward
        const informationGain = this.calculateInformationGain();
        reward += informationGain * 3;

        // 6. Consistency reward (penalize erratic predictions)
        const consistencyPenalty = this.calculateConsistencyPenalty();
        reward -= consistencyPenalty;

        return Math.max(-10, Math.min(50, reward)); // Clamp reward between -10 and 50
    }

    /**
     * Calculate how well confidence matches actual accuracy
     */
    calculateConfidenceAccuracy(actualType, predictedType, confidence) {
        const isCorrect = actualType === predictedType;

        if (isCorrect && confidence > 0.8) {
            return 1.0; // Perfect: confident and correct
        } else if (isCorrect && confidence > 0.6) {
            return 0.7; // Good: moderately confident and correct
        } else if (!isCorrect && confidence < 0.6) {
            return 0.5; // Acceptable: uncertain and wrong
        } else if (!isCorrect && confidence > 0.8) {
            return -1.0; // Bad: overconfident and wrong
        } else {
            return 0.2; // Neutral cases
        }
    }

    /**
     * Calculate reward for balanced dimension exploration
     */
    calculateDimensionBalance() {
        const dimensionCounts = { EI: 0, SN: 0, TF: 0, JP: 0 };

        this.questions_asked.forEach(qId => {
            const question = this.findQuestionById(qId);
            if (question && question.dimension) {
                dimensionCounts[question.dimension]++;
            }
        });

        const counts = Object.values(dimensionCounts);
        const maxCount = Math.max(...counts);
        const minCount = Math.min(...counts);
        const balance = minCount / (maxCount || 1);

        return balance; // Returns 0-1, higher is better
    }

    /**
     * Calculate information gain from question selection
     */
    calculateInformationGain() {
        if (this.questions_asked.length < 2) return 0;

        // Measure how much each question changed our confidence
        let totalGain = 0;
        const confidenceHistory = this.getConfidenceHistory();

        for (let i = 1; i < confidenceHistory.length; i++) {
            const gain = confidenceHistory[i] - confidenceHistory[i-1];
            totalGain += Math.max(0, gain); // Only count positive gains
        }

        return totalGain / this.questions_asked.length;
    }

    /**
     * Calculate penalty for inconsistent predictions
     */
    calculateConsistencyPenalty() {
        if (this.questions_asked.length < 3) return 0;

        // Check how much our prediction has changed
        const predictionHistory = this.getPredictionHistory();
        let changes = 0;

        for (let i = 1; i < predictionHistory.length; i++) {
            if (predictionHistory[i] !== predictionHistory[i-1]) {
                changes++;
            }
        }

        // Penalize excessive changes (more than 2 changes is concerning)
        return Math.max(0, changes - 2) * 0.5;
    }

    /**
     * Get confidence history (placeholder - would need to track this)
     */
    getConfidenceHistory() {
        // Simplified version - in practice, we'd track this during assessment
        return [0.3, 0.5, 0.7, this.getAverageConfidence()];
    }

    /**
     * Get prediction history (placeholder - would need to track this)
     */
    getPredictionHistory() {
        // Simplified version - in practice, we'd track this during assessment
        return [this.getPredictedType()];
    }

    /**
     * Update Q-values using Q-learning algorithm
     */
    updateQValue(state, action, reward, nextState) {
        if (!this.q_table[state]) {
            this.q_table[state] = {};
        }

        const currentQ = this.q_table[state][action] || 0;
        const nextMaxQ = nextState && this.q_table[nextState] ?
            Math.max(...Object.values(this.q_table[nextState])) : 0;

        this.q_table[state][action] = currentQ + this.learning_rate *
            (reward + this.discount_factor * nextMaxQ - currentQ);
    }

    /**
     * Get current personality type prediction
     */
    getPredictedType() {
        const e_i = this.current_scores.EI > 0 ? 'E' : 'I';
        const s_n = this.current_scores.SN > 0 ? 'N' : 'S';
        const t_f = this.current_scores.TF > 0 ? 'T' : 'F';
        const j_p = this.current_scores.JP > 0 ? 'J' : 'P';

        return e_i + s_n + t_f + j_p;
    }

    /**
     * Convert personality type string to array
     */
    typeToArray(type) {
        return [type[0], type[1], type[2], type[3]];
    }

    /**
     * Find question by ID (helper method)
     */
    findQuestionById(id) {
        // This will be injected by the main MBTI system
        return null;
    }

    /**
     * Load pre-trained weights (placeholder for future implementation)
     */
    loadPretrainedWeights() {
        // Initialize with some basic heuristics
        this.initializeHeuristicWeights();
    }

    /**
     * Initialize Q-table with basic heuristics
     */
    initializeHeuristicWeights() {
        // High-value questions for each dimension (based on psychological research)
        const highValueQuestions = {
            EI: [1, 3, 5, 8, 11], // Social energy, attention, processing style
            SN: [16, 18, 22, 25, 28], // Information processing, focus
            TF: [31, 33, 37, 40, 43], // Decision making style
            JP: [46, 48, 52, 55, 58]  // Lifestyle preferences
        };

        // Initialize with small positive values for high-value questions
        Object.keys(highValueQuestions).forEach(dimension => {
            highValueQuestions[dimension].forEach(questionId => {
                const state = "0,0,0,0_0_0"; // Initial state
                if (!this.q_table[state]) this.q_table[state] = {};
                this.q_table[state][questionId] = 0.1;
            });
        });
    }

    /**
     * Get training statistics
     */
    getTrainingStats() {
        return {
            episodes: this.training_episodes,
            q_table_size: Object.keys(this.q_table).length,
            average_questions: this.performance_history.length > 0 ?
                this.performance_history.reduce((sum, p) => sum + p.questions, 0) / this.performance_history.length : 0,
            average_accuracy: this.performance_history.length > 0 ?
                this.performance_history.reduce((sum, p) => sum + (p.correct ? 1 : 0), 0) / this.performance_history.length : 0
        };
    }

    /**
     * Record training episode results
     */
    recordEpisode(correct, questionsUsed, reward) {
        this.training_episodes++;
        this.performance_history.push({
            correct: correct,
            questions: questionsUsed,
            reward: reward,
            episode: this.training_episodes
        });

        // Keep only last 1000 episodes
        if (this.performance_history.length > 1000) {
            this.performance_history.shift();
        }
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MBTIRLAgent;
}
