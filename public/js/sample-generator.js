class MBTISampleGenerator {
    constructor(mbtiTester = null) {
        // Allow injection of MBTITester instance for Node.js compatibility
        if (mbtiTester) {
            this.mbtiTester = mbtiTester;
        } else if (typeof MBTITester !== 'undefined') {
            this.mbtiTester = new MBTITester();
        } else {
            throw new Error('MBTITester is required but not available');
        }
        this.personalityProfiles = this.initializePersonalityProfiles();
    }

    initializePersonalityProfiles() {
        return {
            'INTJ': {
                name: 'The Architect',
                description: 'Strategic, independent, and highly analytical. INTJs are natural planners who prefer working alone and focus on long-term goals.',
                traits: ['Strategic thinking', 'Independent', 'Analytical', 'Future-focused', 'Systematic'],
                responsePattern: { EI: -0.8, SN: 0.7, TF: -0.6, JP: -0.7 }
            },
            'INTP': {
                name: 'The Thinker',
                description: 'Logical, innovative, and curious. INTPs love exploring theoretical concepts and understanding how things work.',
                traits: ['Logical', 'Innovative', 'Curious', 'Theoretical', 'Flexible'],
                responsePattern: { EI: -0.7, SN: 0.8, TF: -0.8, JP: 0.6 }
            },
            'ENTJ': {
                name: 'The Commander',
                description: 'Natural leaders who are decisive, strategic, and goal-oriented. ENTJs excel at organizing people and resources.',
                traits: ['Leadership', 'Decisive', 'Strategic', 'Goal-oriented', 'Organized'],
                responsePattern: { EI: 0.8, SN: 0.6, TF: -0.7, JP: -0.8 }
            },
            'ENTP': {
                name: 'The Debater',
                description: 'Innovative, enthusiastic, and strategic. ENTPs love exploring new possibilities and challenging conventional thinking.',
                traits: ['Innovative', 'Enthusiastic', 'Strategic', 'Adaptable', 'Challenging'],
                responsePattern: { EI: 0.7, SN: 0.8, TF: -0.5, JP: 0.7 }
            },
            'INFJ': {
                name: 'The Advocate',
                description: 'Insightful, principled, and creative. INFJs are driven by their values and desire to help others.',
                traits: ['Insightful', 'Principled', 'Creative', 'Empathetic', 'Organized'],
                responsePattern: { EI: -0.6, SN: 0.7, TF: 0.8, JP: -0.6 }
            },
            'INFP': {
                name: 'The Mediator',
                description: 'Idealistic, loyal, and adaptable. INFPs are guided by their values and seek harmony in their environment.',
                traits: ['Idealistic', 'Loyal', 'Adaptable', 'Values-driven', 'Creative'],
                responsePattern: { EI: -0.7, SN: 0.6, TF: 0.8, JP: 0.7 }
            },
            'ENFJ': {
                name: 'The Protagonist',
                description: 'Charismatic, inspiring, and natural leaders. ENFJs are passionate about helping others reach their potential.',
                traits: ['Charismatic', 'Inspiring', 'Empathetic', 'Organized', 'Motivating'],
                responsePattern: { EI: 0.8, SN: 0.6, TF: 0.8, JP: -0.7 }
            },
            'ENFP': {
                name: 'The Campaigner',
                description: 'Enthusiastic, creative, and sociable. ENFPs see life as full of possibilities and are energized by new ideas.',
                traits: ['Enthusiastic', 'Creative', 'Sociable', 'Optimistic', 'Flexible'],
                responsePattern: { EI: 0.8, SN: 0.8, TF: 0.7, JP: 0.8 }
            },
            'ISTJ': {
                name: 'The Logistician',
                description: 'Practical, fact-minded, and reliable. ISTJs are responsible and hardworking, preferring proven methods.',
                traits: ['Practical', 'Reliable', 'Hardworking', 'Detail-oriented', 'Traditional'],
                responsePattern: { EI: -0.7, SN: -0.8, TF: -0.6, JP: -0.8 }
            },
            'ISFJ': {
                name: 'The Protector',
                description: 'Warm-hearted, conscientious, and cooperative. ISFJs are dedicated to helping and protecting others.',
                traits: ['Warm-hearted', 'Conscientious', 'Cooperative', 'Supportive', 'Loyal'],
                responsePattern: { EI: -0.6, SN: -0.7, TF: 0.8, JP: -0.7 }
            },
            'ESTJ': {
                name: 'The Executive',
                description: 'Organized, practical, and decisive. ESTJs are natural administrators who value efficiency and results.',
                traits: ['Organized', 'Practical', 'Decisive', 'Efficient', 'Results-oriented'],
                responsePattern: { EI: 0.7, SN: -0.6, TF: -0.7, JP: -0.8 }
            },
            'ESFJ': {
                name: 'The Consul',
                description: 'Caring, social, and popular. ESFJs are attentive to others\' needs and work to create harmony.',
                traits: ['Caring', 'Social', 'Popular', 'Supportive', 'Harmonious'],
                responsePattern: { EI: 0.8, SN: -0.6, TF: 0.8, JP: -0.6 }
            },
            'ISTP': {
                name: 'The Virtuoso',
                description: 'Bold, practical, and experimental. ISTPs are hands-on learners who love understanding how things work.',
                traits: ['Bold', 'Practical', 'Experimental', 'Independent', 'Adaptable'],
                responsePattern: { EI: -0.6, SN: -0.6, TF: -0.7, JP: 0.8 }
            },
            'ISFP': {
                name: 'The Adventurer',
                description: 'Artistic, sensitive, and flexible. ISFPs are gentle souls who value personal freedom and authenticity.',
                traits: ['Artistic', 'Sensitive', 'Flexible', 'Authentic', 'Gentle'],
                responsePattern: { EI: -0.7, SN: -0.5, TF: 0.7, JP: 0.7 }
            },
            'ESTP': {
                name: 'The Entrepreneur',
                description: 'Energetic, perceptive, and spontaneous. ESTPs live in the moment and love taking action.',
                traits: ['Energetic', 'Perceptive', 'Spontaneous', 'Action-oriented', 'Adaptable'],
                responsePattern: { EI: 0.8, SN: -0.7, TF: -0.5, JP: 0.8 }
            },
            'ESFP': {
                name: 'The Entertainer',
                description: 'Spontaneous, enthusiastic, and people-focused. ESFPs love being around others and bringing joy to situations.',
                traits: ['Spontaneous', 'Enthusiastic', 'People-focused', 'Optimistic', 'Flexible'],
                responsePattern: { EI: 0.8, SN: -0.5, TF: 0.7, JP: 0.8 }
            }
        };
    }

    generateSampleResponses(personalityType, variation = 'typical') {
        const profile = this.personalityProfiles[personalityType];
        if (!profile) {
            throw new Error(`Unknown personality type: ${personalityType}`);
        }

        const responses = [];
        const questions = this.mbtiTester.initializeQuestions();
        
        // Add some randomness based on variation type
        let variationFactor = 0.2; // Default variation
        switch (variation) {
            case 'strong': variationFactor = 0.1; break;
            case 'moderate': variationFactor = 0.3; break;
            case 'weak': variationFactor = 0.5; break;
        }

        questions.forEach((question, index) => {
            const dimension = question.dimension;
            const basePattern = profile.responsePattern[dimension];
            
            // Add some realistic variation
            const variation = (Math.random() - 0.5) * variationFactor;
            const adjustedPattern = Math.max(-1, Math.min(1, basePattern + variation));
            
            // Convert to 1-5 scale
            let response;
            if (adjustedPattern < -0.6) response = 1;
            else if (adjustedPattern < -0.2) response = 2;
            else if (adjustedPattern < 0.2) response = 3;
            else if (adjustedPattern < 0.6) response = 4;
            else response = 5;
            
            // Adjust based on question direction
            if (question.direction === -1) {
                response = 6 - response; // Reverse the response
            }
            
            responses.push(response);
        });

        return responses;
    }

    generateSampleSet(count = 5, includeAllTypes = true) {
        const samples = [];
        const types = Object.keys(this.personalityProfiles);
        
        if (includeAllTypes) {
            // Generate one sample for each personality type
            types.forEach(type => {
                const responses = this.generateSampleResponses(type, 'typical');
                const result = this.mbtiTester.calculatePersonalityType(responses);
                
                samples.push({
                    targetType: type,
                    actualType: result.type,
                    responses: responses,
                    confidence: result.confidence,
                    profile: this.personalityProfiles[type],
                    match: type === result.type
                });
            });
        } else {
            // Generate random samples
            for (let i = 0; i < count; i++) {
                const randomType = types[Math.floor(Math.random() * types.length)];
                const variations = ['strong', 'typical', 'moderate', 'weak'];
                const randomVariation = variations[Math.floor(Math.random() * variations.length)];
                
                const responses = this.generateSampleResponses(randomType, randomVariation);
                const result = this.mbtiTester.calculatePersonalityType(responses);
                
                samples.push({
                    targetType: randomType,
                    actualType: result.type,
                    responses: responses,
                    confidence: result.confidence,
                    profile: this.personalityProfiles[randomType],
                    variation: randomVariation,
                    match: randomType === result.type
                });
            }
        }
        
        return samples;
    }

    generateDetailedReport(samples) {
        const questions = this.mbtiTester.initializeQuestions();
        let report = '';
        
        report += '='.repeat(80) + '\n';
        report += 'MBTI ASSESSMENT SAMPLE REPORT\n';
        report += '='.repeat(80) + '\n\n';
        
        report += `Generated: ${new Date().toLocaleString()}\n`;
        report += `Total Samples: ${samples.length}\n`;
        report += `Accuracy: ${(samples.filter(s => s.match).length / samples.length * 100).toFixed(1)}%\n\n`;
        
        samples.forEach((sample, index) => {
            report += '-'.repeat(80) + '\n';
            report += `SAMPLE ${index + 1}: ${sample.targetType} - ${sample.profile.name}\n`;
            report += '-'.repeat(80) + '\n\n';
            
            report += `Target Type: ${sample.targetType}\n`;
            report += `Actual Type: ${sample.actualType}\n`;
            report += `Match: ${sample.match ? '✓ YES' : '✗ NO'}\n`;
            if (sample.variation) {
                report += `Variation: ${sample.variation}\n`;
            }
            report += '\n';
            
            report += 'PERSONALITY PROFILE:\n';
            report += `Description: ${sample.profile.description}\n`;
            report += `Key Traits: ${sample.profile.traits.join(', ')}\n\n`;
            
            report += 'CONFIDENCE SCORES:\n';
            Object.entries(sample.confidence).forEach(([dim, conf]) => {
                const percentage = (conf * 100).toFixed(1);
                const bar = '█'.repeat(Math.max(0, Math.floor(conf * 20))) + '░'.repeat(Math.max(0, 20 - Math.floor(conf * 20)));
                report += `${dim}: ${percentage}% [${bar}]\n`;
            });
            report += '\n';
            
            report += 'DETAILED RESPONSES:\n';
            report += 'Q#  | Response | Question\n';
            report += '-'.repeat(80) + '\n';
            
            questions.forEach((question, qIndex) => {
                const response = sample.responses[qIndex];
                const responseText = ['Strongly Disagree', 'Disagree', 'Neutral', 'Agree', 'Strongly Agree'][response - 1];
                report += `${(qIndex + 1).toString().padStart(2)}  | ${response} (${responseText.padEnd(16)}) | ${question.text}\n`;
            });
            
            report += '\n';
        });
        
        // Summary statistics
        report += '='.repeat(80) + '\n';
        report += 'SUMMARY STATISTICS\n';
        report += '='.repeat(80) + '\n\n';
        
        const typeDistribution = {};
        const confidenceStats = { EI: [], SN: [], TF: [], JP: [] };
        
        samples.forEach(sample => {
            typeDistribution[sample.actualType] = (typeDistribution[sample.actualType] || 0) + 1;
            Object.entries(sample.confidence).forEach(([dim, conf]) => {
                confidenceStats[dim].push(conf);
            });
        });
        
        report += 'TYPE DISTRIBUTION:\n';
        Object.entries(typeDistribution).sort().forEach(([type, count]) => {
            const percentage = (count / samples.length * 100).toFixed(1);
            report += `${type}: ${count} (${percentage}%)\n`;
        });
        
        report += '\nAVERAGE CONFIDENCE BY DIMENSION:\n';
        Object.entries(confidenceStats).forEach(([dim, scores]) => {
            const avg = scores.reduce((a, b) => a + b, 0) / scores.length;
            report += `${dim}: ${(avg * 100).toFixed(1)}%\n`;
        });
        
        return report;
    }

    exportToFile(samples, filename = null) {
        if (!filename) {
            filename = `mbti-samples-${new Date().toISOString().slice(0, 10)}.txt`;
        }
        
        const report = this.generateDetailedReport(samples);
        
        // Create downloadable content
        const blob = new Blob([report], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        return report;
    }

    // Method for generating PDF (requires jsPDF library)
    exportToPDF(samples, filename = null) {
        if (typeof jsPDF === 'undefined') {
            console.error('jsPDF library not loaded. Please include jsPDF to use PDF export.');
            return null;
        }
        
        if (!filename) {
            filename = `mbti-samples-${new Date().toISOString().slice(0, 10)}.pdf`;
        }
        
        const doc = new jsPDF();
        const pageHeight = doc.internal.pageSize.height;
        let yPosition = 20;
        
        // Title
        doc.setFontSize(16);
        doc.text('MBTI Assessment Sample Report', 20, yPosition);
        yPosition += 20;
        
        doc.setFontSize(12);
        doc.text(`Generated: ${new Date().toLocaleString()}`, 20, yPosition);
        yPosition += 10;
        doc.text(`Total Samples: ${samples.length}`, 20, yPosition);
        yPosition += 10;
        doc.text(`Accuracy: ${(samples.filter(s => s.match).length / samples.length * 100).toFixed(1)}%`, 20, yPosition);
        yPosition += 20;
        
        samples.forEach((sample, index) => {
            // Check if we need a new page
            if (yPosition > pageHeight - 60) {
                doc.addPage();
                yPosition = 20;
            }
            
            doc.setFontSize(14);
            doc.text(`Sample ${index + 1}: ${sample.targetType} - ${sample.profile.name}`, 20, yPosition);
            yPosition += 15;
            
            doc.setFontSize(10);
            doc.text(`Target: ${sample.targetType} | Actual: ${sample.actualType} | Match: ${sample.match ? 'YES' : 'NO'}`, 20, yPosition);
            yPosition += 10;
            
            // Confidence scores
            doc.text('Confidence Scores:', 20, yPosition);
            yPosition += 8;
            Object.entries(sample.confidence).forEach(([dim, conf]) => {
                doc.text(`${dim}: ${(conf * 100).toFixed(1)}%`, 30, yPosition);
                yPosition += 6;
            });
            yPosition += 10;
        });
        
        doc.save(filename);
        return doc;
    }
}

// Auto-run when script is executed directly
if (typeof require !== 'undefined' && require.main === module) {
    // Load MBTITester when running in Node.js
    const MBTITester = require('./mbti-tester.js');
    
    console.log('MBTI Sample Generator loaded successfully!');
    console.log('Available methods:');
    console.log('- generateSampleSet(count, includeAllTypes)');
    console.log('- generateDetailedReport(samples)');
    console.log('- exportToFile(samples, filename)');
    console.log('- exportToPDF(samples, filename)');
    
    // Generate sample data with injected MBTITester
    const mbtiTester = new MBTITester();
    const generator = new MBTISampleGenerator(mbtiTester);
    const samples = generator.generateSampleSet(5, true); // Generate all 16 types
    console.log(`\nGenerated ${samples.length} samples with ${samples.filter(s => s.match).length} matches (${(samples.filter(s => s.match).length / samples.length * 100).toFixed(1)}% accuracy)`);
    
    // Export to file
    const report = generator.generateDetailedReport(samples);
    console.log('\nSample report generated! Use exportToFile() method in browser to download.');
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MBTISampleGenerator;
}