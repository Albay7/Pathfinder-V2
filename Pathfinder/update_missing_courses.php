<?php

// Load existing data
$jsonStr = file_get_contents('storage/app/courses_data.json');
$courses = json_decode($jsonStr, true);

// Add missing Education Courses from the Questionnaire
$missingEduCourses = [
    "Bachelor of Secondary Education (BSEd) major in English" => [
        "title" => "Bachelor of Secondary Education (BSEd) major in English",
        "tagline" => "Empowering students through the mastery of language and literature.",
        "description" => "A specialized education degree focused on developing highly effective English educators for secondary schools. Emphasizes pedagogy, linguistics, and literature.",
        "short_description" => "A specialized education degree focused on developing English educators for secondary schools.",
        "curriculum_highlights" => ["Language Teaching Methodologies", "World Literature", "Linguistics", "Speech Communication"],
        "skills_gained" => ["Language Proficiency", "Lesson Planning", "Public Speaking", "Curriculum Creation"],
        "career_opportunities" => ["High School English Teacher", "ESL Instructor", "Literacy Coach", "School Publication Adviser"],
        "duration" => "4 Years", "difficulty" => "Moderate", "tuition" => "₱35,000 - ₱70,000 / Sem",
        "mbti_alignment" => ["ENFJ", "INFJ", "ENFP", "ISFJ"],
        "top_universities" => [
            ["name" => "Philippine Normal University", "location" => "Manila", "description" => "The National Center for Teacher Education."],
            ["name" => "University of the Philippines Diliman", "location" => "Quezon City", "description" => "College of Education is a CHED Center of Excellence."]
        ]
    ],
    "Bachelor of Elementary Education (BEEd)" => [
        "title" => "Bachelor of Elementary Education (BEEd)",
        "tagline" => "Shaping the minds and futures of young learners.",
        "description" => "Prepares teachers for elementary schools (Grades 1-6) with a focus on child development and universally effective pedagogical strategies.",
        "short_description" => "Prepares teachers for elementary schools with a focus on child development.",
        "curriculum_highlights" => ["Child Development", "Methods of Teaching", "Educational Technology", "Student Teaching"],
        "skills_gained" => ["Lesson Planning", "Classroom Management", "Child Psychology", "Instructional Facilitation"],
        "career_opportunities" => ["Elementary Teacher", "Tutor", "Curriculum Developer", "School Administrator"],
        "duration" => "4 Years", "difficulty" => "Moderate", "tuition" => "₱35,000 - ₱70,000 / Sem",
        "mbti_alignment" => ["ESFJ", "ISFJ", "ENFJ", "ESFP"],
        "top_universities" => [
            ["name" => "Philippine Normal University", "location" => "Manila", "description" => "The designated National Center for Teacher Education."],
            ["name" => "University of Santo Tomas", "location" => "Manila", "description" => "Produces top LET board passers consistently."]
        ]
    ],
    "Bachelor of Early Childhood Education (BECEd)" => [
        "title" => "Bachelor of Early Childhood Education (BECEd)",
        "tagline" => "Nurturing the most crucial years of development.",
        "description" => "Specialized program aimed at teaching and caring for young children from birth to age eight.",
        "short_description" => "Specialized program aimed at teaching and caring for young children.",
        "curriculum_highlights" => ["Play-Based Learning", "Early Literacy", "Special Needs Education", "Child Health and Nutrition"],
        "skills_gained" => ["Patience & Empathy", "Creative Instruction", "Behavior Management", "Parent-Teacher Communication"],
        "career_opportunities" => ["Preschool Teacher", "Daycare Manager", "Special Education Aide", "Child Life Specialist"],
        "duration" => "4 Years", "difficulty" => "Low", "tuition" => "₱35,000 - ₱70,000 / Sem",
        "mbti_alignment" => ["ESFJ", "ISFJ", "ENFP", "INFP"],
        "top_universities" => [
            ["name" => "De La Salle University", "location" => "Manila", "description" => "Excellent child development labs."],
            ["name" => "Miriam College", "location" => "Quezon City", "description" => "Pioneers in Early Childhood Education in the PH."]
        ]
    ],
    "Bachelor of Secondary Education (BSEd) major in Social Studies" => [
        "title" => "Bachelor of Secondary Education (BSEd) major in Social Studies",
        "tagline" => "Educating students on history, geography, and civic duty.",
        "description" => "Prepares educators to teach history, economics, and sociology to high school students, fostering critical thinking about society.",
        "short_description" => "Prepares educators to teach history, economics, and sociology to high school students.",
        "curriculum_highlights" => ["Asian History", "Economics", "Geography", "Philippine Government and Constitution"],
        "skills_gained" => ["Historical Analysis", "Civic Education", "Critical Thinking", "Debate Facilitation"],
        "career_opportunities" => ["High School Social Studies Teacher", "History Instructor", "Civic Educator", "Curriculum Writer"],
        "duration" => "4 Years", "difficulty" => "Moderate", "tuition" => "₱35,000 - ₱70,000 / Sem",
        "mbti_alignment" => ["INFJ", "ENFJ", "INTJ", "ENTJ"],
        "top_universities" => [
            ["name" => "Philippine Normal University", "location" => "Manila", "description" => "The premier teaching institution for social sciences."],
            ["name" => "Ateneo de Manila University", "location" => "Quezon City", "description" => "Strong interdisciplinary social science programs."]
        ]
    ],
    "Bachelor of Technology and Livelihood Education (BTLEd)" => [
        "title" => "Bachelor of Technology and Livelihood Education (BTLEd)",
        "tagline" => "Equipping students with practical and technical life skills.",
        "description" => "Focuses on teaching technical-vocational and livelihood tracks (TLE) such as home economics, industrial arts, and agri-fishery.",
        "short_description" => "Focuses on teaching technical-vocational and livelihood tracks (TLE).",
        "curriculum_highlights" => ["Home Economics", "Industrial Arts", "ICT Education", "Vocational Training Methods"],
        "skills_gained" => ["Practical Resource Management", "Technical Teaching", "Project-Based Learning", "Vocational Assessment"],
        "career_opportunities" => ["TLE Teacher", "Vocational Instructor", "Technical Trainer", "Livelihood Program Coordinator"],
        "duration" => "4 Years", "difficulty" => "Moderate", "tuition" => "₱30,000 - ₱65,000 / Sem",
        "mbti_alignment" => ["ESTJ", "ISTJ", "ISFJ", "ESFJ"],
        "top_universities" => [
            ["name" => "Technological University of the Philippines", "location" => "Manila", "description" => "Premier state university for technical/vocational education."],
            ["name" => "Marikina Polytechnic College", "location" => "Marikina City", "description" => "Specialized hub for technical teacher education."]
        ]
    ],
    "Bachelor of Secondary Education (BSEd) major in Science" => [
        "title" => "Bachelor of Secondary Education (BSEd) major in Science",
        "tagline" => "Inspiring the next generation of scientific minds.",
        "description" => "Equips future teachers with comprehensive knowledge in biology, chemistry, and physics, paired with effective science pedagogy.",
        "short_description" => "Equips future teachers with comprehensive knowledge in biology, chemistry, and physics.",
        "curriculum_highlights" => ["General Science Pedagogy", "Laboratory Management", "Earth Science", "Environmental Science"],
        "skills_gained" => ["Scientific Experimentation", "Safety Protocols", "Analytical Teaching", "Science Exhibit Curation"],
        "career_opportunities" => ["High School Science Teacher", "Lab Supervisor", "Science Coordinator", "Educational Content Creator"],
        "duration" => "4 Years", "difficulty" => "High", "tuition" => "₱35,000 - ₱75,000 / Sem",
        "mbti_alignment" => ["INTJ", "INTP", "ENTJ", "ENFJ"],
        "top_universities" => [
            ["name" => "Philippine Normal University", "location" => "Manila", "description" => "Center of Excellence with strong science teacher tracks."],
            ["name" => "University of San Carlos", "location" => "Cebu", "description" => "Top regional university for science education."]
        ]
    ],
    "Bachelor of Secondary Education (BSEd) major in Mathematics" => [
        "title" => "Bachelor of Secondary Education (BSEd) major in Mathematics",
        "tagline" => "Making numbers and logic accessible to adolescent learners.",
        "description" => "Trains educators to effectively teach complex mathematical concepts like algebra, geometry, and calculus to high school students.",
        "short_description" => "Trains educators to effectively teach complex mathematical concepts to high school students.",
        "curriculum_highlights" => ["Advanced Algebra", "Calculus & Geometry", "Statistics and Probability", "Math Teaching Methods"],
        "skills_gained" => ["Logical Breakdown of Concepts", "Math Assessment", "Patience in Instruction", "Problem Solving Frameworks"],
        "career_opportunities" => ["High School Math Teacher", "Math Tutor", "Test Prep Instructor", "Curriculum Developer"],
        "duration" => "4 Years", "difficulty" => "Very High", "tuition" => "₱35,000 - ₱70,000 / Sem",
        "mbti_alignment" => ["ISTJ", "INTJ", "ESTJ", "INTP"],
        "top_universities" => [
            ["name" => "University of the Philippines Diliman", "location" => "Quezon City", "description" => "World-class mathematics and education synergy."],
            ["name" => "Philippine Normal University", "location" => "Manila", "description" => "Leading producer of top-tier math educators."]
        ]
    ],
    "Bachelor of Physical Education (BPEd)" => [
        "title" => "Bachelor of Physical Education (BPEd)",
        "tagline" => "Promoting lifelong fitness and athletic development.",
        "description" => "Focuses on human movement, sports management, and physical health. Prepares students to be PE teachers, coaches, and fitness instructors.",
        "short_description" => "Focuses on human movement, sports management, and physical health as an educator.",
        "curriculum_highlights" => ["Anatomy and Kinesiology", "Sports Officiating", "Fitness Assessment", "Dance and Gymnastics"],
        "skills_gained" => ["Physical Fitness Monitoring", "Sports Coaching", "First Aid", "Event Organization"],
        "career_opportunities" => ["PE Teacher", "Sports Coach", "Fitness Instructor", "Athletic Director"],
        "duration" => "4 Years", "difficulty" => "Moderate", "tuition" => "₱30,000 - ₱65,000 / Sem",
        "mbti_alignment" => ["ESTP", "ESFP", "ENFJ", "ESTJ"],
        "top_universities" => [
            ["name" => "University of the Philippines Diliman", "location" => "Quezon City", "description" => "College of Human Kinetics is a premier sports science hub."],
            ["name" => "National University", "location" => "Manila", "description" => "Renowned for robust athletic and sports programs."]
        ]
    ]
];

// Merge the newly found courses into our existing courses array
foreach ($missingEduCourses as $courseName => $courseData) {
    if (!isset($courses[$courseName])) {
        $courses[$courseName] = $courseData;
    }
}

// Ensure the old, un-suffixed ones (e.g. "Bachelor of Secondary Education")
// fallback properly to "default" if they aren't the exact major 
// (or leave them as they are since we added them previously).

// Save the updated JSON
file_put_contents('storage/app/courses_data.json', json_encode($courses, JSON_PRETTY_PRINT));
echo "Successfully appended " . count($missingEduCourses) . " missing education courses to JSON.\n";
