#!/usr/bin/env node

/**
 * RL-Enhanced MBTI Terminal Test Runner
 * Demonstrates the reinforcement learning enhanced MBTI assessment
 */

// Load required modules
const { MBTIRLEnhanced, SyntheticUser } = require('./mbti-rl-enhanced.js');

async function runRLTest() {
    console.log('🧠 RL-Enhanced MBTI Terminal Test Runner');
    console.log('==========================================\n');
    
    try {
        // Initialize system
        console.log('⚡ Initializing RL-Enhanced MBTI System...');
        const rlSystem = new MBTIRLEnhanced();
        console.log('✅ System initialized successfully!\n');
        
        // Train the agent first
        console.log('🎓 Training RL Agent (50 episodes)...');
        const trainingResults = await rlSystem.runTrainingSession(50);
        console.log(`✅ Training Complete! Final Accuracy: ${(trainingResults.finalStats.finalAccuracy * 100).toFixed(1)}%`);
        console.log(`📊 Average Questions: ${trainingResults.finalStats.averageQuestions.toFixed(1)}`);
        console.log(`🧠 Q-Table Size: ${trainingResults.finalStats.qTableSize} states\n`);
        
        // Run a test with synthetic user
        console.log('🤖 Running RL-Enhanced Assessment with Synthetic User');
        console.log('====================================================\n');
        
        // Generate random target type
        const types = ['ENFP', 'INFP', 'ENFJ', 'INFJ', 'ENTP', 'INTP', 'ENTJ', 'INTJ',
                      'ESFP', 'ISFP', 'ESFJ', 'ISFJ', 'ESTP', 'ISTP', 'ESTJ', 'ISTJ'];
        const targetType = types[Math.floor(Math.random() * types.length)];
        const syntheticUser = new SyntheticUser(targetType);
        
        console.log(`🎯 Target Personality Type: ${targetType}`);
        console.log(`👤 User Consistency: ${(syntheticUser.consistency * 100).toFixed(1)}%`);
        console.log('🚀 Starting adaptive assessment...\n');
        
        // Start session
        rlSystem.startAdaptiveAssessment();
        let questionCount = 0;
        
        // Simulate responses
        while (questionCount < 20) {
            const questionData = rlSystem.getNextQuestion();
            
            if (!questionData || !questionData.question) {
                console.log('🛑 RL Agent decided to stop - confidence threshold reached!');
                break;
            }
            
            const response = syntheticUser.answerQuestion(questionData.question.id);
            const result = rlSystem.processUserResponse(questionData.question.id, response);
            
            questionCount++;
            
            // Format response text
            const responseText = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'][response - 1];
            
            console.log(`Q${questionCount.toString().padStart(2, '0')}: ${questionData.question.text}`);
            console.log(`     📝 Response: ${response} (${responseText})`);
            console.log(`     🔮 Prediction: ${result.currentPrediction} | Confidence: ${(result.confidence * 100).toFixed(1)}%`);
            console.log(`     📊 Progress: ${questionData.progress.toFixed(1)}%\n`);
            
            // Stop if very confident
            if (result.confidence > 0.9) {
                console.log('🎯 High confidence reached - stopping early!');
                break;
            }
        }
        
        // Get final results
        const finalResults = rlSystem.completeAssessment();
        
        console.log('🎉 ASSESSMENT COMPLETE!');
        console.log('========================');
        console.log(`🎯 Target Type: ${targetType}`);
        console.log(`🔮 Predicted Type: ${finalResults.primary.type}`);
        console.log(`✅ Accuracy: ${targetType === finalResults.primary.type ? '✅ CORRECT' : '❌ INCORRECT'}`);
        console.log(`📝 Questions Used: ${finalResults.questionsAsked}/60`);
        console.log(`⚡ Efficiency: ${finalResults.efficiency}`);
        console.log(`🎯 Final Confidence: ${(finalResults.primary.averageConfidence * 100).toFixed(1)}%`);
        console.log(`⏱️  Time Saved: ${finalResults.timeSaved}\n`);
        
        // Show dimension breakdown
        console.log('📊 Dimension Analysis:');
        console.log('======================');
        const dimensions = {
            'EI': 'Extraversion vs Introversion',
            'SN': 'Sensing vs Intuition', 
            'TF': 'Thinking vs Feeling',
            'JP': 'Judging vs Perceiving'
        };
        
        Object.entries(finalResults.primary.dimensionScores).forEach(([dim, score]) => {
            const confidence = (finalResults.primary.confidence[dim] * 100).toFixed(1);
            const tendency = score > 0 ? dim[0] : dim[1];
            const strength = Math.abs(score);
            console.log(`   ${dim} (${dimensions[dim]})`);
            console.log(`      Tendency: ${tendency} | Strength: ${strength.toFixed(2)} | Confidence: ${confidence}%`);
        });
        
        console.log('\n🚀 RL Enhancement Results:');
        console.log(`   • Reduced questions by ${finalResults.efficiency}`);
        console.log(`   • Saved ${finalResults.timeSaved}`);
        console.log(`   • Maintained high accuracy with adaptive questioning`);
        
        // Show personality description
        console.log(`\n📖 Personality Description for ${finalResults.primary.type}:`);
        console.log(`   ${finalResults.description.name}`);
        console.log(`   ${finalResults.description.description.substring(0, 200)}...`);
        
        console.log('\n✨ Test completed successfully!');
        
    } catch (error) {
        console.error('❌ Error running RL test:', error.message);
        console.error('Stack trace:', error.stack);
    }
}

// Run multiple tests for comparison
async function runComparisonTest() {
    console.log('\n🔬 Running RL vs Traditional Comparison');
    console.log('=======================================\n');
    
    try {
        const rlSystem = new MBTIRLEnhanced();
        
        // Train first
        console.log('🎓 Training agent for comparison...');
        await rlSystem.runTrainingSession(30);
        
        // Run comparison
        const comparison = await rlSystem.runComparison(20);
        
        console.log('\n📈 Comparison Results:');
        console.log('┌─────────────┬─────────────┬─────────────┬─────────────┐');
        console.log('│   Method    │  Accuracy   │ Avg Questions│ Efficiency  │');
        console.log('├─────────────┼─────────────┼─────────────┼─────────────┤');
        console.log(`│ RL Enhanced │   ${comparison.rl.accuracy.padEnd(8)} │    ${comparison.rl.avgQuestions.padEnd(8)} │   ${comparison.rl.efficiency.padEnd(8)} │`);
        console.log(`│ Traditional │   ${comparison.traditional.accuracy.padEnd(8)} │    ${comparison.traditional.avgQuestions.padEnd(8)} │   ${comparison.traditional.efficiency.padEnd(8)} │`);
        console.log('└─────────────┴─────────────┴─────────────┴─────────────┘');
        
    } catch (error) {
        console.error('❌ Comparison test failed:', error.message);
    }
}

// Main execution
async function main() {
    const args = process.argv.slice(2);
    
    if (args.includes('--compare')) {
        await runComparisonTest();
    } else {
        await runRLTest();
    }
    
    if (args.includes('--both')) {
        await runComparisonTest();
    }
}

// Execute if run directly
if (require.main === module) {
    main().catch(console.error);
}

module.exports = { runRLTest, runComparisonTest };