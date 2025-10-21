<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            // Business Administration & Related Disciplines
            [
                'title' => 'Business Administration Fundamentals',
                'description' => 'Comprehensive introduction to business management principles and practices',
                'provider' => 'Coursera',
                'category' => 'business_administration',
                'level' => 'beginner',
                'url' => 'https://www.coursera.org/learn/business-administration',
                'price' => 49.00,
                'currency' => 'USD',
                'duration_hours' => 30,
                'skills_taught' => ['Management', 'Leadership', 'Strategic Planning', 'Business Analysis'],
                'prerequisites' => [],
                'mbti_compatibility' => ['ENTJ' => 95, 'ESTJ' => 90, 'INTJ' => 85, 'ISTJ' => 80],
                'mbti_explanation' => 'Perfect for natural leaders and strategic thinkers.',
                'rating' => 4.6,
                'students_count' => 150000,
                'is_active' => true
            ],
            [
                'title' => 'MBA Essentials: Strategic Management',
                'description' => 'Advanced strategic management and business planning concepts',
                'provider' => 'edX',
                'category' => 'business_administration',
                'level' => 'advanced',
                'url' => 'https://www.edx.org/course/strategic-management',
                'price' => 299.00,
                'currency' => 'USD',
                'duration_hours' => 45,
                'skills_taught' => ['Strategic Planning', 'Business Development', 'Market Analysis', 'Financial Management'],
                'prerequisites' => ['Basic business knowledge'],
                'mbti_compatibility' => ['ENTJ' => 95, 'ESTJ' => 90, 'INTJ' => 85, 'ISTJ' => 80],
                'mbti_explanation' => 'Ideal for experienced professionals seeking advanced business skills.',
                'rating' => 4.7,
                'students_count' => 75000,
                'is_active' => true
            ],
            [
                'title' => 'Project Management Professional (PMP)',
                'description' => 'Complete PMP certification training covering all knowledge areas',
                'provider' => 'Udemy',
                'category' => 'business_administration',
                'level' => 'intermediate',
                'url' => 'https://www.udemy.com/course/project-management',
                'price' => 199.00,
                'currency' => 'USD',
                'duration_hours' => 35,
                'skills_taught' => ['Project Management', 'Risk Management', 'Quality Management', 'Team Leadership'],
                'prerequisites' => ['Work experience preferred'],
                'mbti_compatibility' => ['ESTJ' => 95, 'ENTJ' => 90, 'ISTJ' => 85, 'INTJ' => 80],
                'mbti_explanation' => 'Perfect for organized individuals who excel at managing complex projects.',
                'rating' => 4.8,
                'students_count' => 120000,
                'is_active' => true
            ],
            // Engineering & Technology
            [
                'title' => 'Complete Web Development Bootcamp',
                'description' => 'Learn HTML, CSS, JavaScript, Node.js, React, MongoDB and more!',
                'provider' => 'Udemy',
                'category' => 'engineering_technology',
                'level' => 'beginner',
                'url' => 'https://www.udemy.com/course/the-complete-web-development-bootcamp/',
                'price' => 89.99,
                'currency' => 'USD',
                'duration_hours' => 65,
                'skills_taught' => ['HTML', 'CSS', 'JavaScript', 'React', 'Node.js', 'MongoDB'],
                'prerequisites' => [],
                'mbti_compatibility' => ['INTJ' => 85, 'INTP' => 90, 'ENTJ' => 80, 'ENTP' => 85],
                'mbti_explanation' => 'Perfect for logical thinkers who enjoy problem-solving and building systems.',
                'rating' => 4.7,
                'students_count' => 850000,
                'is_active' => true
            ],
            [
                'title' => 'Python for Data Science and Machine Learning',
                'description' => 'Learn how to use NumPy, Pandas, Seaborn, Matplotlib, Plotly, Scikit-Learn, Machine Learning, Tensorflow, and more!',
                'provider' => 'Udemy',
                'category' => 'engineering_technology',
                'level' => 'intermediate',
                'url' => 'https://www.udemy.com/course/python-for-data-science-and-machine-learning-bootcamp/',
                'price' => 94.99,
                'currency' => 'USD',
                'duration_hours' => 25,
                'skills_taught' => ['Python', 'Data Science', 'Machine Learning', 'Pandas', 'NumPy'],
                'prerequisites' => ['Basic Python knowledge'],
                'mbti_compatibility' => ['INTJ' => 95, 'INTP' => 90, 'ISTJ' => 75, 'ISTP' => 80],
                'mbti_explanation' => 'Ideal for analytical minds who love working with data and patterns.',
                'rating' => 4.6,
                'students_count' => 500000,
                'is_active' => true
            ],
            [
                'title' => 'Introduction to Mechanical Engineering',
                'description' => 'Fundamentals of mechanical engineering principles and applications',
                'provider' => 'edX',
                'category' => 'engineering_technology',
                'level' => 'beginner',
                'url' => 'https://www.edx.org/course/mechanical-engineering',
                'price' => 149.00,
                'currency' => 'USD',
                'duration_hours' => 40,
                'skills_taught' => ['Thermodynamics', 'Mechanics', 'Materials Science', 'CAD Design'],
                'prerequisites' => ['Basic mathematics and physics'],
                'mbti_compatibility' => ['ISTP' => 90, 'INTP' => 85, 'ISTJ' => 80, 'INTJ' => 85],
                'mbti_explanation' => 'Ideal for practical problem-solvers who enjoy working with mechanical systems.',
                'rating' => 4.5,
                'students_count' => 60000,
                'is_active' => true
            ],

            // Health Sciences & Medicine
            [
                'title' => 'Introduction to Healthcare Management',
                'description' => 'Learn the fundamentals of managing healthcare organizations',
                'provider' => 'edX',
                'category' => 'health_sciences',
                'level' => 'beginner',
                'url' => 'https://www.edx.org/course/healthcare-management',
                'price' => 99.00,
                'currency' => 'USD',
                'duration_hours' => 25,
                'skills_taught' => ['Healthcare Administration', 'Quality Improvement', 'Healthcare Policy', 'Leadership'],
                'prerequisites' => [],
                'mbti_compatibility' => ['ESFJ' => 90, 'ENFJ' => 85, 'ESTJ' => 80, 'ENTJ' => 85],
                'mbti_explanation' => 'Perfect for caring individuals who want to improve healthcare delivery.',
                'rating' => 4.4,
                'students_count' => 45000,
                'is_active' => true
            ],
            [
                'title' => 'Medical Terminology and Anatomy',
                'description' => 'Comprehensive course on medical terminology and human anatomy',
                'provider' => 'Coursera',
                'category' => 'health_sciences',
                'level' => 'beginner',
                'url' => 'https://www.coursera.org/learn/medical-terminology',
                'price' => 79.00,
                'currency' => 'USD',
                'duration_hours' => 20,
                'skills_taught' => ['Medical Terminology', 'Anatomy', 'Physiology', 'Healthcare Communication'],
                'prerequisites' => [],
                'mbti_compatibility' => ['ISFJ' => 85, 'ESFJ' => 80, 'ISTJ' => 75, 'ESTJ' => 70],
                'mbti_explanation' => 'Great for detail-oriented individuals interested in healthcare.',
                'rating' => 4.5,
                'students_count' => 65000,
                'is_active' => true
            ],

            // Education & Social Sciences
            [
                'title' => 'Educational Psychology and Learning',
                'description' => 'Understanding how people learn and develop in educational settings',
                'provider' => 'Coursera',
                'category' => 'education_social_sciences',
                'level' => 'intermediate',
                'url' => 'https://www.coursera.org/learn/educational-psychology',
                'price' => 59.00,
                'currency' => 'USD',
                'duration_hours' => 30,
                'skills_taught' => ['Educational Psychology', 'Learning Theory', 'Child Development', 'Teaching Methods'],
                'prerequisites' => ['Basic psychology knowledge'],
                'mbti_compatibility' => ['ENFJ' => 95, 'ESFJ' => 90, 'INFJ' => 85, 'ISFJ' => 80],
                'mbti_explanation' => 'Perfect for individuals passionate about education and human development.',
                'rating' => 4.6,
                'students_count' => 85000,
                'is_active' => true
            ],
            [
                'title' => 'Social Work and Community Development',
                'description' => 'Introduction to social work principles and community intervention strategies',
                'provider' => 'edX',
                'category' => 'education_social_sciences',
                'level' => 'beginner',
                'url' => 'https://www.edx.org/course/social-work',
                'price' => 89.00,
                'currency' => 'USD',
                'duration_hours' => 35,
                'skills_taught' => ['Social Work', 'Community Development', 'Counseling', 'Social Policy'],
                'prerequisites' => [],
                'mbti_compatibility' => ['ENFJ' => 90, 'ESFJ' => 85, 'INFJ' => 80, 'ISFJ' => 85],
                'mbti_explanation' => 'Ideal for empathetic individuals who want to make a positive social impact.',
                'rating' => 4.4,
                'students_count' => 55000,
                'is_active' => true
            ],

            // Arts & Creative Industries
            [
                'title' => 'Complete Video Production Course',
                'description' => 'Learn video production from pre-production to post-production',
                'provider' => 'Udemy',
                'category' => 'arts_creative',
                'level' => 'beginner',
                'url' => 'https://www.udemy.com/course/video-production-course/',
                'price' => 89.99,
                'currency' => 'USD',
                'duration_hours' => 30,
                'skills_taught' => ['Video Editing', 'Cinematography', 'Audio Production', 'Adobe Premiere'],
                'prerequisites' => [],
                'mbti_compatibility' => ['ESFP' => 90, 'ENFP' => 85, 'ISFP' => 80, 'INFP' => 85],
                'mbti_explanation' => 'Perfect for creative individuals who love storytelling through visual media.',
                'rating' => 4.6,
                'students_count' => 85000,
                'is_active' => true
            ],
            [
                'title' => 'Graphic Design Masterclass',
                'description' => 'Complete course covering all aspects of graphic design',
                'provider' => 'Udemy',
                'category' => 'arts_creative',
                'level' => 'intermediate',
                'url' => 'https://www.udemy.com/course/graphic-design-masterclass/',
                'price' => 94.99,
                'currency' => 'USD',
                'duration_hours' => 45,
                'skills_taught' => ['Adobe Photoshop', 'Adobe Illustrator', 'Typography', 'Brand Design'],
                'prerequisites' => ['Basic design knowledge'],
                'mbti_compatibility' => ['ISFP' => 95, 'INFP' => 90, 'ESFP' => 85, 'ENFP' => 80],
                'mbti_explanation' => 'Ideal for artistic minds who want to create compelling visual communications.',
                'rating' => 4.7,
                'students_count' => 120000,
                'is_active' => true
            ],
            [
                'title' => 'Google UX Design Professional Certificate',
                'description' => 'Launch your career in UX design with this comprehensive program',
                'provider' => 'Coursera',
                'category' => 'arts_creative',
                'level' => 'beginner',
                'url' => 'https://www.coursera.org/professional-certificates/google-ux-design',
                'price' => 39.00,
                'currency' => 'USD',
                'duration_hours' => 50,
                'skills_taught' => ['User Research', 'Wireframing', 'Prototyping', 'Figma', 'Adobe XD'],
                'prerequisites' => [],
                'mbti_compatibility' => ['INFP' => 90, 'ISFP' => 85, 'ENFP' => 85, 'ESFP' => 80],
                'mbti_explanation' => 'Ideal for creative individuals who care about user experience and design.',
                'rating' => 4.6,
                'students_count' => 180000,
                'is_active' => true
            ],

            // Law & Legal Studies
            [
                'title' => 'Introduction to Law and Legal Systems',
                'description' => 'Comprehensive overview of legal principles and court systems',
                'provider' => 'Coursera',
                'category' => 'law_legal',
                'level' => 'beginner',
                'url' => 'https://www.coursera.org/learn/introduction-to-law',
                'price' => 69.00,
                'currency' => 'USD',
                'duration_hours' => 25,
                'skills_taught' => ['Legal Research', 'Constitutional Law', 'Legal Writing', 'Court Procedures'],
                'prerequisites' => [],
                'mbti_compatibility' => ['INTJ' => 85, 'ENTJ' => 80, 'ISTJ' => 90, 'ESTJ' => 85],
                'mbti_explanation' => 'Perfect for analytical minds who enjoy working with complex legal frameworks.',
                'rating' => 4.5,
                'students_count' => 75000,
                'is_active' => true
            ],
            [
                'title' => 'Business Law and Ethics',
                'description' => 'Essential legal knowledge for business professionals',
                'provider' => 'edX',
                'category' => 'law_legal',
                'level' => 'intermediate',
                'url' => 'https://www.edx.org/course/business-law',
                'price' => 129.00,
                'currency' => 'USD',
                'duration_hours' => 30,
                'skills_taught' => ['Contract Law', 'Business Ethics', 'Intellectual Property', 'Employment Law'],
                'prerequisites' => ['Basic business knowledge'],
                'mbti_compatibility' => ['ESTJ' => 90, 'ENTJ' => 85, 'ISTJ' => 85, 'INTJ' => 80],
                'mbti_explanation' => 'Great for business-minded individuals who need legal expertise.',
                'rating' => 4.4,
                'students_count' => 45000,
                'is_active' => true
            ],

            // Agriculture & Environmental Sciences
            [
                'title' => 'Sustainable Agriculture and Food Systems',
                'description' => 'Learn about sustainable farming practices and food production',
                'provider' => 'Coursera',
                'category' => 'agriculture_environmental',
                'level' => 'beginner',
                'url' => 'https://www.coursera.org/learn/sustainable-agriculture',
                'price' => 59.00,
                'currency' => 'USD',
                'duration_hours' => 28,
                'skills_taught' => ['Sustainable Farming', 'Crop Management', 'Soil Science', 'Food Systems'],
                'prerequisites' => [],
                'mbti_compatibility' => ['ISFJ' => 85, 'ISTJ' => 80, 'ESFJ' => 75, 'ESTJ' => 70],
                'mbti_explanation' => 'Perfect for individuals passionate about environmental sustainability and food production.',
                'rating' => 4.3,
                'students_count' => 35000,
                'is_active' => true
            ],
            [
                'title' => 'Environmental Science and Climate Change',
                'description' => 'Understanding environmental challenges and climate science',
                'provider' => 'edX',
                'category' => 'agriculture_environmental',
                'level' => 'intermediate',
                'url' => 'https://www.edx.org/course/environmental-science',
                'price' => 99.00,
                'currency' => 'USD',
                'duration_hours' => 35,
                'skills_taught' => ['Environmental Science', 'Climate Change', 'Ecology', 'Conservation'],
                'prerequisites' => ['Basic science knowledge'],
                'mbti_compatibility' => ['INFP' => 85, 'ISFP' => 80, 'INTP' => 75, 'ISTP' => 70],
                'mbti_explanation' => 'Ideal for environmentally conscious individuals who want to address global challenges.',
                'rating' => 4.5,
                'students_count' => 55000,
                'is_active' => true
            ],

            // Communication & Media Studies
            [
                'title' => 'Digital Marketing Specialization',
                'description' => 'Master digital marketing strategy, analytics, and implementation',
                'provider' => 'Coursera',
                'category' => 'communication_media',
                'level' => 'beginner',
                'url' => 'https://www.coursera.org/specializations/digital-marketing',
                'price' => 49.00,
                'currency' => 'USD',
                'duration_hours' => 30,
                'skills_taught' => ['SEO', 'Social Media Marketing', 'Google Analytics', 'Content Marketing'],
                'prerequisites' => [],
                'mbti_compatibility' => ['ENFP' => 90, 'ENTP' => 85, 'ESFP' => 80, 'ESTP' => 85],
                'mbti_explanation' => 'Perfect for creative and people-oriented individuals who love connecting with audiences.',
                'rating' => 4.5,
                'students_count' => 200000,
                'is_active' => true
            ],
            [
                'title' => 'Journalism and Media Production',
                'description' => 'Learn the fundamentals of journalism and media content creation',
                'provider' => 'Udemy',
                'category' => 'communication_media',
                'level' => 'beginner',
                'url' => 'https://www.udemy.com/course/journalism-media-production/',
                'price' => 79.99,
                'currency' => 'USD',
                'duration_hours' => 25,
                'skills_taught' => ['Journalism', 'Media Writing', 'Broadcasting', 'Digital Media'],
                'prerequisites' => [],
                'mbti_compatibility' => ['ENFP' => 85, 'ENTP' => 80, 'ESFP' => 75, 'ESTP' => 80],
                'mbti_explanation' => 'Great for communicative individuals who enjoy storytelling and current events.',
                'rating' => 4.4,
                'students_count' => 65000,
                'is_active' => true
            ]
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }
    }
}