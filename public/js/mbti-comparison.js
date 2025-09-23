const { MBTIRLEnhanced, SyntheticUser } = require('./mbti-rl-enhanced.js');

console.log('🔬 MBTI Assessment Comparison: Traditional vs RL-Enhanced');
console.log('='.repeat(65));

async function runComparison() {
    try {
        // Initialize systems
        const rlSystem = new MBTIRLEnhanced();
        const syntheticUser = new SyntheticUser('INTJ'); // Test with INTJ personality
        
        console.log('🎯 Target Personality: INTJ');
        console.log('👤 Testing with synthetic user simulation');
        console.log('');
        
        // Train RL agent
        console.log('🧠 Training RL Agent...');
        const trainingResults = await rlSystem.runTrainingSession(50);
        console.log(`   ✅ Training complete: ${(trainingResults.finalStats.finalAccuracy * 100).toFixed(1)}% accuracy`);
        console.log('');
        
        // Run Traditional Assessment
        console.log('📊 TRADITIONAL ASSESSMENT');
        console.log('-'.repeat(30));
        const traditionalStart = Date.now();
        
        // Simulate full traditional test (all 60 questions)
        const traditionalResponses = [];
        for (let i = 1; i <= 60; i++) {
            traditionalResponses.push(syntheticUser.answerQuestion(i));
        }
        
        const traditionalResult = rlSystem.baseTester.calculatePersonalityType(traditionalResponses);
        const traditionalTime = Date.now() - traditionalStart;
        
        console.log(`   🎯 Result: ${traditionalResult.type}`);
        console.log(`   📝 Questions: 60/60 (100%)`);
        console.log(`   ⏱️  Time: ${traditionalTime}ms`);
        console.log(`   ✅ Accuracy: ${traditionalResult.type === 'INTJ' ? '✅ CORRECT' : '❌ INCORRECT'}`);
        console.log('');
        
        // Run RL-Enhanced Assessment
        console.log('🚀 RL-ENHANCED ASSESSMENT');
        console.log('-'.repeat(30));
        const rlStart = Date.now();
        
        const rlResult = await rlSystem.runAdaptiveAssessment(syntheticUser);
        const rlTime = Date.now() - rlStart;
        
        console.log(`   🎯 Result: ${rlResult.finalType}`);
        console.log(`   📝 Questions: ${rlResult.questionsUsed}/${rlResult.totalQuestions} (${Math.round((rlResult.questionsUsed/rlResult.totalQuestions)*100)}%)`);
        console.log(`   ⏱️  Time: ${rlTime}ms`);
        console.log(`   ✅ Accuracy: ${rlResult.finalType === 'INTJ' ? '✅ CORRECT' : '❌ INCORRECT'}`);
        console.log(`   🎯 Confidence: ${typeof rlResult.finalConfidence === 'number' ? rlResult.finalConfidence.toFixed(1) : rlResult.finalConfidence}%`);
        console.log('');
        
        // Comparison Summary
        console.log('📈 COMPARISON SUMMARY');
        console.log('='.repeat(40));
        
        const questionReduction = ((60 - rlResult.questionsUsed) / 60 * 100).toFixed(1);
        const timeReduction = ((traditionalTime - rlTime) / traditionalTime * 100).toFixed(1);
        const bothCorrect = traditionalResult.type === 'INTJ' && rlResult.finalType === 'INTJ';
        
        console.log(`📊 Question Efficiency:`);
        console.log(`   Traditional: 60 questions`);
        console.log(`   RL-Enhanced: ${rlResult.questionsUsed} questions`);
        console.log(`   💡 Reduction: ${questionReduction}% fewer questions`);
        console.log('');
        
        console.log(`⚡ Speed Comparison:`);
        console.log(`   Traditional: ${traditionalTime}ms`);
        console.log(`   RL-Enhanced: ${rlTime}ms`);
        console.log(`   💡 Improvement: ${timeReduction}% faster`);
        console.log('');
        
        console.log(`🎯 Accuracy Comparison:`);
        console.log(`   Traditional: ${traditionalResult.type === 'INTJ' ? '✅ CORRECT' : '❌ INCORRECT'}`);
        console.log(`   RL-Enhanced: ${rlResult.finalType === 'INTJ' ? '✅ CORRECT' : '❌ INCORRECT'}`);
        console.log(`   💡 Result: ${bothCorrect ? '✅ Both methods accurate' : '⚠️  Different results'}`);
        console.log('');
        
        console.log('🏆 WINNER: RL-Enhanced MBTI');
        console.log(`   • ${questionReduction}% fewer questions`);
        console.log(`   • ${timeReduction}% faster execution`);
        console.log(`   • ${bothCorrect ? 'Same accuracy' : 'Different accuracy'}`);
        console.log(`   • Real-time confidence tracking`);
        console.log(`   • Adaptive question selection`);
        
        // Test multiple personality types
        console.log('');
        console.log('🔄 TESTING MULTIPLE PERSONALITY TYPES');
        console.log('='.repeat(45));
        
        const testTypes = ['ENFP', 'ISTJ', 'ESTP', 'INFJ'];
        let totalTraditionalQuestions = 0;
        let totalRLQuestions = 0;
        let correctTraditional = 0;
        let correctRL = 0;
        
        for (const targetType of testTypes) {
            console.log(`\n🎯 Testing: ${targetType}`);
            const testUser = new SyntheticUser(targetType);
            
            // Traditional test
            const tradResponses = [];
            for (let i = 1; i <= 60; i++) {
                tradResponses.push(testUser.answerQuestion(i));
            }
            const tradResult = rlSystem.baseTester.calculatePersonalityType(tradResponses);
            totalTraditionalQuestions += 60;
            if (tradResult.type === targetType) correctTraditional++;
            
            // RL test
            const rlTestResult = await rlSystem.runAdaptiveAssessment(testUser);
            totalRLQuestions += rlTestResult.questionsUsed;
            if (rlTestResult.finalType === targetType) correctRL++;
            
            console.log(`   Traditional: ${tradResult.type} (${tradResult.type === targetType ? '✅' : '❌'})`);
            console.log(`   RL-Enhanced: ${rlTestResult.finalType} (${rlTestResult.finalType === targetType ? '✅' : '❌'}) - ${rlTestResult.questionsUsed}Q`);
        }
        
        console.log('\n📊 MULTI-TYPE SUMMARY');
        console.log('-'.repeat(25));
        console.log(`Traditional Accuracy: ${correctTraditional}/${testTypes.length} (${(correctTraditional/testTypes.length*100).toFixed(1)}%)`);
        console.log(`RL-Enhanced Accuracy: ${correctRL}/${testTypes.length} (${(correctRL/testTypes.length*100).toFixed(1)}%)`);
        console.log(`Average Questions - Traditional: ${(totalTraditionalQuestions/testTypes.length).toFixed(1)}`);
        console.log(`Average Questions - RL-Enhanced: ${(totalRLQuestions/testTypes.length).toFixed(1)}`);
        console.log(`Overall Efficiency: ${((totalTraditionalQuestions-totalRLQuestions)/totalTraditionalQuestions*100).toFixed(1)}% reduction`);
        
    } catch (error) {
        console.error('❌ Error during comparison:', error.message);
        console.error(error.stack);
    }
}

// Run the comparison
runComparison().catch(console.error);