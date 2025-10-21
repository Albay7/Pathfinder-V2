<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tutorial;

class TutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutorials = [
            // Python Tutorials
            [
                'title' => 'Python for Beginners - Full Course',
                'description' => 'Learn Python programming from scratch. This comprehensive course covers variables, data types, functions, loops, and more.',
                'skill' => 'Python',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=rfscVS0vtbw',
                'provider' => 'freeCodeCamp',
                'duration_minutes' => 270,
                'rating' => 4.8,
                'difficulty' => 1,
                'prerequisites' => [],
                'tags' => ['programming', 'basics', 'syntax'],
                'is_free' => true,
                'is_active' => true
            ],
            [
                'title' => 'Python Data Structures and Algorithms',
                'description' => 'Master data structures and algorithms in Python. Learn lists, dictionaries, sets, and algorithm optimization.',
                'skill' => 'Python',
                'level' => 'intermediate',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=pkYVOmU3MgA',
                'provider' => 'freeCodeCamp',
                'duration_minutes' => 720,
                'rating' => 4.7,
                'difficulty' => 3,
                'prerequisites' => ['Basic Python'],
                'tags' => ['data-structures', 'algorithms', 'optimization'],
                'is_free' => true,
                'is_active' => true
            ],
            [
                'title' => 'Python Official Documentation',
                'description' => 'The official Python documentation with comprehensive guides, tutorials, and reference materials.',
                'skill' => 'Python',
                'level' => 'beginner',
                'type' => 'documentation',
                'url' => 'https://docs.python.org/3/tutorial/',
                'provider' => 'Python.org',
                'duration_minutes' => null,
                'rating' => 4.9,
                'difficulty' => 2,
                'prerequisites' => [],
                'tags' => ['official', 'reference', 'comprehensive'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // JavaScript Tutorials
            [
                'title' => 'JavaScript Crash Course for Beginners',
                'description' => 'Learn JavaScript fundamentals including variables, functions, DOM manipulation, and ES6 features.',
                'skill' => 'JavaScript',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=hdI2bqOjy3c',
                'provider' => 'Traversy Media',
                'duration_minutes' => 100,
                'rating' => 4.6,
                'difficulty' => 1,
                'prerequisites' => ['HTML', 'CSS'],
                'tags' => ['web-development', 'frontend', 'basics'],
                'is_free' => true,
                'is_active' => true
            ],
            [
                'title' => 'Modern JavaScript ES6+ Features',
                'description' => 'Deep dive into modern JavaScript features including arrow functions, destructuring, modules, and async/await.',
                'skill' => 'JavaScript',
                'level' => 'intermediate',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=nZ1DMMsyVyI',
                'provider' => 'Dev Ed',
                'duration_minutes' => 180,
                'rating' => 4.5,
                'difficulty' => 3,
                'prerequisites' => ['Basic JavaScript'],
                'tags' => ['es6', 'modern-js', 'advanced'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // React Tutorials
            [
                'title' => 'React Tutorial for Beginners',
                'description' => 'Learn React from scratch. Build your first React application with components, props, state, and hooks.',
                'skill' => 'React',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=SqcY0GlETPk',
                'provider' => 'React',
                'duration_minutes' => 240,
                'rating' => 4.7,
                'difficulty' => 2,
                'prerequisites' => ['JavaScript', 'HTML', 'CSS'],
                'tags' => ['frontend', 'components', 'hooks'],
                'is_free' => true,
                'is_active' => true
            ],
            [
                'title' => 'React Hooks Complete Guide',
                'description' => 'Master React Hooks including useState, useEffect, useContext, and custom hooks.',
                'skill' => 'React',
                'level' => 'intermediate',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=TNhaISOUy6Q',
                'provider' => 'Codevolution',
                'duration_minutes' => 300,
                'rating' => 4.8,
                'difficulty' => 3,
                'prerequisites' => ['Basic React'],
                'tags' => ['hooks', 'state-management', 'advanced'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // HTML Tutorials
            [
                'title' => 'HTML Full Course - Build a Website Tutorial',
                'description' => 'Complete HTML tutorial covering all HTML elements, semantic markup, forms, and best practices.',
                'skill' => 'HTML',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=pQN-pnXPaVg',
                'provider' => 'freeCodeCamp',
                'duration_minutes' => 120,
                'rating' => 4.5,
                'difficulty' => 1,
                'prerequisites' => [],
                'tags' => ['web-development', 'markup', 'basics'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // CSS Tutorials
            [
                'title' => 'CSS Complete Course - Zero to Hero',
                'description' => 'Master CSS from basics to advanced topics including Flexbox, Grid, animations, and responsive design.',
                'skill' => 'CSS',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=1Rs2ND1ryYc',
                'provider' => 'freeCodeCamp',
                'duration_minutes' => 660,
                'rating' => 4.6,
                'difficulty' => 2,
                'prerequisites' => ['HTML'],
                'tags' => ['styling', 'responsive', 'flexbox', 'grid'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // Node.js Tutorials
            [
                'title' => 'Node.js Tutorial for Beginners',
                'description' => 'Learn Node.js from scratch. Build server-side applications with Express.js and work with databases.',
                'skill' => 'Node.js',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=TlB_eWDSMt4',
                'provider' => 'Programming with Mosh',
                'duration_minutes' => 180,
                'rating' => 4.7,
                'difficulty' => 2,
                'prerequisites' => ['JavaScript'],
                'tags' => ['backend', 'server', 'express'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // SQL Tutorials
            [
                'title' => 'SQL Tutorial - Full Database Course for Beginners',
                'description' => 'Complete SQL course covering database design, queries, joins, and database management.',
                'skill' => 'SQL',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=HXV3zeQKqGY',
                'provider' => 'freeCodeCamp',
                'duration_minutes' => 240,
                'rating' => 4.8,
                'difficulty' => 2,
                'prerequisites' => [],
                'tags' => ['database', 'queries', 'joins'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // Git Tutorials
            [
                'title' => 'Git and GitHub for Beginners - Crash Course',
                'description' => 'Learn version control with Git and GitHub. Master branching, merging, and collaboration workflows.',
                'skill' => 'Git',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=RGOj5yH7evk',
                'provider' => 'freeCodeCamp',
                'duration_minutes' => 70,
                'rating' => 4.6,
                'difficulty' => 1,
                'prerequisites' => [],
                'tags' => ['version-control', 'collaboration', 'github'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // Machine Learning Tutorials
            [
                'title' => 'Machine Learning Course for Beginners',
                'description' => 'Introduction to machine learning concepts, algorithms, and practical implementation with Python.',
                'skill' => 'Machine Learning',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=NWONeJKn6kc',
                'provider' => 'freeCodeCamp',
                'duration_minutes' => 600,
                'rating' => 4.7,
                'difficulty' => 3,
                'prerequisites' => ['Python', 'Statistics'],
                'tags' => ['ai', 'algorithms', 'data-science'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // Data Analysis Tutorials
            [
                'title' => 'Data Analysis with Python - Full Course',
                'description' => 'Learn data analysis using Python, Pandas, NumPy, and Matplotlib. Work with real datasets.',
                'skill' => 'Data Analysis',
                'level' => 'intermediate',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=r-uOLxNrNk8',
                'provider' => 'freeCodeCamp',
                'duration_minutes' => 600,
                'rating' => 4.8,
                'difficulty' => 3,
                'prerequisites' => ['Python', 'Pandas', 'Numpy'],
                'tags' => ['data-science', 'pandas', 'visualization'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // UX Design Tutorials
            [
                'title' => 'UX Design Course - User Experience Design Fundamentals',
                'description' => 'Learn UX design principles, user research methods, wireframing, and prototyping.',
                'skill' => 'UX Design',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=uL2ZB7XXIgg',
                'provider' => 'AJ&Smart',
                'duration_minutes' => 180,
                'rating' => 4.5,
                'difficulty' => 2,
                'prerequisites' => [],
                'tags' => ['design', 'user-research', 'wireframing'],
                'is_free' => true,
                'is_active' => true
            ],
            
            // Figma Tutorials
            [
                'title' => 'Figma Tutorial for UI Design - Course for Beginners',
                'description' => 'Complete Figma tutorial covering interface design, prototyping, and collaboration features.',
                'skill' => 'Figma',
                'level' => 'beginner',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=jwCmIBJ8Jtc',
                'provider' => 'Bring Your Own Laptop',
                'duration_minutes' => 120,
                'rating' => 4.6,
                'difficulty' => 1,
                'prerequisites' => [],
                'tags' => ['design-tool', 'ui-design', 'prototyping'],
                'is_free' => true,
                'is_active' => true
            ]
        ];
        
        foreach ($tutorials as $tutorial) {
            Tutorial::create($tutorial);
        }
    }
}
