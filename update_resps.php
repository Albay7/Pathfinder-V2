<?php
$filepath = 'C:/Users/Hendrix/OneDrive/Desktop/Projects/PathfinderApp/Pathfinder/app/Http/Controllers/PathfinderController.php';
$content = file_get_contents($filepath);

$expandedResponsibilities = [
    'Software Developer' => [
        "Design, write, and maintain clean, scalable code using modern programming languages and best practices.",
        "Collaborate with cross-functional teams to test, deploy, and monitor applications in production environments.",
        "Continuously revise, update, refactor, and debug existing codebases to improve performance and security.",
        "Architect and implement improvements to existing software interfaces and underlying systems to enhance user experience."
    ],
    'Data Analyst' => [
        "Collect, clean, and preprocess large datasets from diverse sources to ensure data integrity and usability.",
        "Identify complex trends, correlations, and actionable patterns hidden within raw business data.",
        "Design and maintain interactive dashboards and comprehensive data visualizations for ongoing monitoring.",
        "Translate technical findings into clear, strategic presentations for stakeholders and management teams."
    ],
    'Cybersecurity Analyst' => [
        "Continuously monitor network traffic and system logs to detect anomalies and potential security incidents.",
        "Conduct thorough investigations into security breaches and implement rapid incident response protocols.",
        "Install, configure, and operate advanced security software, firewalls, and data encryption programs.",
        "Perform regular vulnerability assessments and penetration testing to preemptively secure infrastructure."
    ],
    'Financial Analyst' => [
        "Analyze complex financial data, market trends, and macroeconomic indicators to guide corporate strategy.",
        "Develop robust financial models to simulate business scenarios and support major investment decisions.",
        "Prepare detailed financial reports, revenue forecasts, and budget variance analyses for executive review.",
        "Recommend optimal investment strategies and portfolio allocations to maximize returns and mitigate risk."
    ],
    'Database Administrator' => [
        "Design, build, and optimize complex database architectures to ensure fast, reliable data retrieval.",
        "Implement rigorous data security protocols and manage automated backup systems to guarantee data integrity.",
        "Proactively troubleshoot database errors, resolve performance bottlenecks, and monitor server health.",
        "Manage user access levels, permissions, and authentication to prevent unauthorized data exposure."
    ],
    'Systems Administrator' => [
        "Install, configure, and manage both hardware and software systems across on-premise and cloud environments.",
        "Maintain the health of network facilities in individual machines, ensuring maximum uptime and connectivity.",
        "Regularly audit system logs, apply critical security patches, and perform routine maintenance tasks.",
        "Provide tier-2 and tier-3 technical support to internal staff, resolving complex infrastructure issues."
    ],
    'Network Administrator' => [
        "Design, deploy, and maintain robust local area (LAN) and wide area (WAN) network infrastructures.",
        "Proactively monitor network performance, bandwidth usage, and latency to ensure optimal operations.",
        "Configure and secure core networking equipment including enterprize routers, switches, and load balancers.",
        "Implement and enforce strict network security protocols, firewalls, and VPN access policies."
    ],
    'Web Developer' => [
        "Write well-designed, highly testable, and efficient code using HTML, CSS, JavaScript, and modern frameworks.",
        "Create responsive website layouts and interactive user interfaces that function seamlessly across all devices.",
        "Integrate frontend designs with robust back-end services, APIs, and complex database architectures.",
        "Collaborate closely with UI/UX designers and stakeholders to gather and refine technical specifications."
    ],
    'IT Support Specialist' => [
        "Diagnose, troubleshoot, and resolve a wide variety of complex hardware and software technical issues.",
        "Effectively triage, prioritize, and resolve help desk tickets within established service level agreements.",
        "Set up, configure, and deploy computers, mobile devices, and peripherals for new and existing employees.",
        "Document technical knowledge, formulate FAQs, and maintain an internal knowledge base for end-users."
    ],
    'Staff Nurse' => [
        "Comprehensively assess patient conditions, take vital signs, and compile accurate medical histories.",
        "Safely administer medications, perform prescribed treatments, and assist physicians during medical procedures.",
        "Continuously monitor patient progress, document recovery milestones, and adjust care plans as necessary.",
        "Educate patients and their families regarding medical conditions, post-discharge care, and wellness strategies."
    ],
    'Human Resources Specialist' => [
        "Manage the full-cycle recruitment process, from sourcing and interviewing candidates to onboarding new hires.",
        "Administer employee compensation packages, health benefits, and coordinate regular performance evaluations.",
        "Mediate complex workplace conflicts, address employee grievances, and foster a positive organizational culture.",
        "Ensure company policies remain strictly compliant with local and national labor laws and employment regulations."
    ],
    'Educational Coordinator' => [
        "Develop comprehensive educational programs, training materials, and curricula tailored to specific learning goals.",
        "Rigorously evaluate program effectiveness through assessments, surveys, and qualitative feedback metrics.",
        "Organize and conduct workshops to train educators, administrators, and staff on new teaching methodologies.",
        "Oversee the successful implementation of new curricula across diverse classrooms and educational settings."
    ],
    'Content Writer' => [
        "Conduct in-depth research on industry-related topics to produce accurate, engaging, and authoritative content.",
        "Draft, edit, and proofread high-quality copy for blogs, websites, social media, and marketing collateral.",
        "Optimize all digital content using SEO best practices to maximize web traffic and search engine rankings.",
        "Collaborate closely with marketing and design teams to ensure content aligns with overarching brand strategies."
    ],
    'Social Worker' => [
        "Actively identify and connect with individuals and communities who are vulnerable or in urgent need of assistance.",
        "Conduct thorough assessments of clients' emotional needs, living situations, and available support networks.",
        "Develop and implement holistic intervention plans tailored to improve clients' overall well-being and safety.",
        "Provide direct crisis intervention and respond rapidly to emergency situations involving abuse or mental health."
    ],
    'Compliance Officer' => [
        "Meticulously review corporate operations to ensure they meet all legal standards and industry regulations.",
        "Plan and execute regular compliance audits, risk assessments, and internal investigations across all departments.",
        "Develop, update, and actively enforce internal policies that safeguard organizational ethics and data privacy.",
        "Conduct training sessions to educate employees on regulatory changes and the importance of compliance adherence."
    ],
    'Administrative Officer' => [
        "Expertly handle office scheduling, executive calendars, and direct communications to ensure fluid operations.",
        "Organize and securely maintain complex electronic datasets, physical files, and confidential corporate records.",
        "Draft comprehensive reports, prepare compelling presentations, and record detailed minutes during key meetings.",
        "Coordinate multifaceted office activities, manage essential vendor relationships, and oversee facility maintenance."
    ],
    'Operations Manager' => [
        "Oversee and optimize daily operational activities to ensure maximum efficiency and high organizational output.",
        "Formulate, iterate, and strictly enforce operational policies, quality control standards, and best practices.",
        "Manage end-to-end supply chains, negotiate with key vendors, and ensure seamless inventory logistics.",
        "Continuously monitor financial data, analyze operational metrics, and optimize departmental budgets."
    ],
    'Business Development Manager' => [
        "Proactively research and identify potential new geographic markets, enterprise clients, and strategic partnerships.",
        "Expertly negotiate terms, close high-value business deals, and facilitate complex contract agreements.",
        "Collaborate extensively with marketing and product teams to perfectly align offerings with shifting market demands.",
        "Develop and execute long-term strategic business plans focused on sustainable growth and revenue generation."
    ],
    'Marketing Coordinator' => [
        "Brainstorm, develop, and meticulously execute multi-channel marketing campaigns tailored to target audiences.",
        "Continuously analyze campaign performance metrics and adjust active strategies to maximize return on investment.",
        "Coordinate seamlessly with diverse sales and creative teams to produce highly effective promotional materials.",
        "Manage corporate social media channels, foster community engagement, and monitor brand reputation online."
    ],
    'Sales Representative' => [
        "Proactively prospect, identify, and consistently initiate contact with qualified leads and potential new clients.",
        "Deliver highly persuasive product demonstrations and tailor value propositions to meet specific customer pain points.",
        "Skillfully negotiate contract terms, overcome buyer objections, and successfully close lucrative sales deals.",
        "Maintain deep, long-lasting client relationships to drive recurring revenue and generate referral business."
    ],
    'Customer Service Representative' => [
        "Swiftly and professionally resolve complex customer inquiries via telephone, email, and live chat platforms.",
        "Maintain highly accurate customer interaction records, update internal databases, and document complex issues.",
        "Efficiently process incoming orders, facilitate returns, and manage financial refunds with a focus on accuracy.",
        "Expertly de-escalate stressful or confrontational situations by utilizing empathy and proven conflict resolution tactics."
    ],
    'Event Coordinator' => [
        "Meticulously plan comprehensive event details, encompassing thematic design, guest lists, and precise timeline logistics.",
        "Liaise effectively with external vendors, caterers, and venues to negotiate contracts and ensure seamless delivery.",
        "Strictly manage overarching event budgets, track all expenses, and ensure maximum value without compromising quality.",
        "Oversee critical on-site event execution, swiftly resolving unexpected issues to guarantee a flawless attendee experience."
    ],
    'Hotel Front Desk Agent' => [
        "Warmly greet guests, orchestrate smooth check-in/check-out processes, and assign rooms according to preferences.",
        "Accurately process guest payments, manage financial folios, and ensure the integrity of cash drawer transactions.",
        "Promptly handle intricate guest requests, resolve complaints with grace, and provide insightful local recommendations.",
        "Maintain real-time records of room occupancy, communicate with housekeeping, and manage daily reservation platforms."
    ],
    'Public Relations Officer' => [
        "Draft exceptional press releases, compelling media pitches, and dynamic public statements that capture attention.",
        "Cultivate and manage robust relationships with essential journalists, influential media outlets, and key stakeholders.",
        "Strategically develop and execute comprehensive PR campaigns designed to proactively shape a favorable public image.",
        "Assume a leadership role during crisis communication scenarios, rapidly mitigating reputational damage."
    ],
    'Communications Specialist' => [
        "Strategically develop and refine both internal enterprise and external public-facing communication frameworks.",
        "Expertly write and curate engaging organizational newsletters, internal memos, and executive leadership announcements.",
        "Manage diverse multidimensional corporate communications channels, evaluating engagement and message penetration.",
        "Act as a diligent brand guardian, ensuring strict consistency in tone, messaging, and visual identity across all platforms."
    ],
    'Market Research Analyst' => [
        "Scientifically design and conduct comprehensive surveys, insightful focus groups, and structured market questionnaires.",
        "Deeply analyze qualitative and quantitative data using advanced statistical software to uncover critical market insights.",
        "Accurately forecast emerging market trends, evaluate shifting consumer behaviors, and assess competitor strategies.",
        "Distill complex analytical findings into highly actionable, visually compelling reports for senior management."
    ],
    'Curriculum Developer' => [
        "Systematically design comprehensive syllabi, innovative lesson plans, and diverse evaluation metrics for students.",
        "Critically evaluate and continuously update educational materials to ensure they remain accurate and pedagogically sound.",
        "Conduct rigorous training seminars to instruct educators on implementing new curricula and best teaching practices.",
        "Pioneer the integration of emerging educational technologies and digital learning tools into standard classroom environments."
    ],
    'Computer Engineer' => [
        "Meticulously design and architecture complex computer hardware components, microprocessors, and custom circuit boards.",
        "Rigorously test and analyze hardware performance under extreme conditions to identify and resolve critical bottlenecks.",
        "Spearhead the continuous improvement and technological update of existing enterprise-grade computer equipment.",
        "Write highly optimized low-level software, critical firmware, and driver interactions connecting software to physical systems."
    ],
    'Electronics Engineer' => [
        "Pioneer the design of sophisticated electronic systems and micro-components tailored for specialized industrial applications.",
        "Rigorously evaluate proposed electronic designs for strict adherence to safety standards and maximum energy efficiency.",
        "Develop comprehensive, step-by-step maintenance procedures and operational protocols for complex electronic equipment.",
        "Diagnose and troubleshoot intricate systemic failures in broadcast, communication, and high-level electronic infrastructures."
    ],
    'Physical Therapist' => [
        "Expertly diagnose patients' functional impairments, range of motion limitations, and underlying neurological mobility issues.",
        "Develop highly customized, measurable therapeutic care plans tailored exactly to the patient's specific injury or chronic condition.",
        "Actively teach and physically guide patients through specific rehabilitative exercises, strength training, and stretching routines.",
        "Regularly evaluate patient recovery progress, modify treatment strategies dynamically, and meticulously document clinical outcomes."
    ],
    'Occupational Therapist' => [
        "Holistically assess patients' combined physical, mental, and cognitive conditions to determine their overall functional baseline.",
        "Develop innovative treatment plans focused entirely on restoring the patient's ability to perform vital daily living and work tasks.",
        "Carefully recommend and train patients in the use of specialized adaptive equipment and personalized ergonomic modifications.",
        "Extensively educate and train patients' family members and caregivers to ensure safe and continuous support in the home environment."
    ],
    'Elementary School Teacher' => [
        "Creatively conceptualize and adhere to engaging daily lesson plans that solidly cover foundational subjects like math and reading.",
        "Continuously evaluate and document individual student academic progress, identifying areas requiring targeted intervention.",
        "Skillfully manage classroom behavior utilizing positive reinforcement frameworks to maintain a safe, highly productive learning environment.",
        "Communicate consistently and effectively with parents or guardians to align on student development, behavioral goals, and academic milestones."
    ]
];

$success = 0;
foreach($expandedResponsibilities as $career => $resps) {
    if (strpos($content, "'$career' => [") === false) {
        echo "Missing career: $career\n";
        continue;
    }
    
    $escaped = preg_quote($career, '/');
    $pattern = "/('$escaped'\s*=>\s*\[.*?\'responsibilities\'\s*=>\s*\[)(.*?)(\],\s*\'skills_required\')/s";
    
    // Format the new responsibilities array string
    $newRespString = "";
    foreach($resps as $resp) {
        $newRespString .= "\n                    '" . addslashes($resp) . "',";
    }
    $newRespString .= "\n                ";

    if(preg_match($pattern, $content)) {
        $content = preg_replace($pattern, "$1" . $newRespString . "$3", $content, 1);
        $success++;
    } else {
        echo "Failed to match responsibilities array for $career\n";
    }
}

file_put_contents($filepath, $content);
echo "Successfully updated $success careers.\n";
