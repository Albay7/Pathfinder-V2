<?php

$controllerFile = __DIR__ . '/app/Http/Controllers/PathfinderController.php';
$content = file_get_contents($controllerFile);

// Helper function to generate a short description
function generateShortDescription($title) {
    $shortDescriptions = [
        // Original list
        'Software Developer' => 'Design, build, and maintain the applications and systems that run on computers and mobile devices.',
        'Data Analyst' => 'Collect, clean, and interpret complex datasets to help organizations make strategic business decisions.',
        'Cybersecurity Analyst' => 'Protect IT infrastructure by monitoring for threats, identifying vulnerabilities, and responding to cyber attacks.',
        'Network Engineer' => 'Design, implement, and manage the complex computer networks that organizations rely on for daily operations.',
        'Cloud Architect' => 'Design and manage scalable cloud computing environments, migrating traditional infrastructure to modern platforms.',
        'Machine Learning Engineer' => 'Build advanced AI systems and predictive models that allow computers to learn from massive amounts of data.',
        'DevOps Engineer' => 'Bridge the gap between development and operations by automating deployment pipelines and infrastructure.',
        'IT Project Manager' => 'Plan, execute, and deliver complex technology initiatives on time, within budget, and to specification.',
        'UX/UI Designer' => 'Design intuitive, engaging, and accessible digital interfaces that provide exceptional user experiences.',
        'Database Administrator' => 'Ensure that vital organizational databases are secure, organized, and constantly available to users.',
        'Registered Nurse' => 'Provide direct, compassionate patient care, administer treatments, and educate patients about health conditions.',
        'Medical Technologist' => 'Perform crucial laboratory tests and procedures that help physicians diagnose and treat medical conditions.',
        'Pharmacist' => 'Dispense prescription medications, provide vital healthcare advice, and ensure patients use drugs safely.',
        'Physical Therapist' => 'Help injured or ill people improve physical movement, manage pain, and recover through guided exercise.',
        'Radiologic Technologist' => 'Perform detailed diagnostic imaging examinations, such as X-rays and MRI scans, to assist in medical diagnosis.',
        'Civil Engineer' => 'Design, construct, and maintain critical infrastructure projects including roads, bridges, buildings, and water systems.',
        'Mechanical Engineer' => 'Design, analyze, and manufacture mechanical systems, ranging from small devices to large industrial machinery.',
        'Electrical Engineer' => 'Design, develop, and test electrical equipment and systems, from microchips to massive power grids.',
        'Architect' => 'Design aesthetically pleasing, safe, and functional buildings and structures for residential and commercial use.',
        'Industrial Engineer' => 'Optimize complex processes and systems by eliminating wastefulness in production and manufacturing facilities.',
        'CPA' => 'Manage financial records, prepare taxes, ensure compliance, and provide strategic financial advice to businesses.',
        'Financial Analyst' => 'Guide investment decisions for businesses and individuals by evaluating financial data and economic trends.',
        'Marketing Manager' => 'Develop comprehensive strategies and lead campaigns to promote products, services, and brand awareness.',
        'Human Resources Manager' => 'Recruit, interview, and hire staff, while managing employee relations and organizational development.',
        'Supply Chain Manager' => 'Direct the intricate flow of goods and services, from raw materials procurement to final product delivery.',
        'High School Teacher' => 'Educate and mentor students in specific subjects, preparing them for higher education or the workforce.',
        'Lawyer' => 'Advise clients on legal rights, represent them in legal proceedings, and draft complex legal documents.',
        
        // Newly discovered list
        'Systems Administrator' => 'Maintain, configure, and ensure the reliable operation of computer systems and corporate servers.',
        'Web Developer' => 'Write code to create visually engaging, responsive, and highly functional websites and web applications.',
        'IT Support Specialist' => 'Diagnose and rapidly resolve technical hardware and software issues to keep employees working efficiently.',
        'Staff Nurse' => 'Provide direct medical care, monitor patient recovery, and assist doctors in critical medical procedures.',
        'Human Resources Specialist' => 'Recruit top talent, administer employee benefits, and foster a positive, supportive company culture.',
        'Educational Coordinator' => 'Design engaging school curricula and provide critical training for instructional staff and teachers.',
        'Content Writer' => 'Craft compelling, well-researched written content for blogs, marketing materials, and digital platforms.',
        'Social Worker' => 'Advocate for vulnerable individuals, connect them with vital resources, and provide crisis intervention strategies.',
        'Compliance Officer' => 'Audit internal operations to ensure strict company adherence to complex legal and regulatory standards.',
        'Administrative Officer' => 'Manage daily office operations, execute scheduling, and maintain complex organizational records.',
        'Operations Manager' => 'Optimize daily business procedures to maximize efficiency, cut costs, and increase company output.',
        'Business Development Manager' => 'Identify lucrative new market opportunities and aggressively negotiate high-value corporate partnerships.',
        'Marketing Coordinator' => 'Execute creative, multi-channel promotional campaigns to elevate brand visibility and drive product sales.',
        'Sales Representative' => 'Pitch products directly to clients, negotiate contracts, and consistently close high-value business deals.',
        'Customer Service Representative' => 'Interact directly with customers to resolve complaints, answer inquiries, and ensure total satisfaction.',
        'Event Coordinator' => 'Meticulously plan and execute large-scale gatherings, managing vendors, budgets, and on-site logistics.',
        'Hotel Front Desk Agent' => 'Warmly welcome guests, process check-ins expertly, and handle critical room reservations for the property.',
        'Public Relations Officer' => 'Shape a positive public image for organizations through strategic media releases and relationship management.',
        'Communications Specialist' => 'Develop clear, unified messaging for both internal staff communications and external public engagement.',
        'Market Research Analyst' => 'Analyze shifting market trends and complex consumer data to guide strategic corporate decision-making.',
        'Curriculum Developer' => 'Create structured, engaging educational materials and instructional models for diverse learning environments.',
        'Computer Engineer' => 'Design, build, and rigorously test cutting-edge computer hardware, processors, and circuit boards.',
        'Electronics Engineer' => 'Develop sophisticated electronic components and circuitry utilized in commercial and industrial devices.',
        'Occupational Therapist' => 'Help patients regain the crucial physical skills required for daily living and working independence.',
        'Elementary School Teacher' => 'Instruct young children in foundational subjects, fostering both their emotional and academic development.'
    ];
    
    return $shortDescriptions[$title] ?? 'A dynamic role offering significant opportunities for professional growth and impact.';
}

// Regex to match the career definition up through the short_description (or description if short_description missing)
// We will replace existing 'short_description' entirely.
$pattern = '/(\'title\'\s*=>\s*\'([^\']+)\',\s*\'tagline\'\s*=>\s*\'[^\']+\',\s*\'description\'\s*=>\s*\'[^\']+\',(?:\s*\'short_description\'\s*=>\s*\'[^\']+\',)?)/';

$newContent = preg_replace_callback($pattern, function($matches) {
    // Rebuild the matched portion but replace/inject the correct short_description
    $title = $matches[2];
    $shortDesc = generateShortDescription($title);
    
    // Original match block up to description
    $baseBlock = preg_replace('/,\s*\'short_description\'\s*=>\s*\'[^\']+\',$/', ',', $matches[1]);
    
    return $baseBlock . "\n                'short_description' => '" . addslashes($shortDesc) . "',";
}, $content);

file_put_contents($controllerFile, $newContent);
echo "Added comprehensive short descriptions!\n";
