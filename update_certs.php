<?php
$filepath = 'C:/Users/Hendrix/OneDrive/Desktop/Projects/PathfinderApp/Pathfinder/app/Http/Controllers/PathfinderController.php';
$content = file_get_contents($filepath);

$certs = [
    'Software Developer' => "['AWS Certified Developer', 'Scrum Master (CSM)', 'Oracle Certified Professional']",
    'Data Analyst' => "['Google Data Analytics Certificate', 'Microsoft Certified: Power BI Data Analyst', 'CAP']",
    'Cybersecurity Analyst' => "['CISSP', 'CompTIA Security+', 'Certified Ethical Hacker (CEH)']",
    'Financial Analyst' => "['Chartered Financial Analyst (CFA)', 'Certified Public Accountant (CPA)', 'Certified Financial Planner (CFP)']",
    'Database Administrator' => "['Oracle Database Administrator Certified Professional', 'Microsoft Certified: Azure DB Administrator']",
    'Systems Administrator' => "['CompTIA Server+', 'Red Hat Certified System Administrator (RHCSA)', 'CCNA']",
    'Network Administrator' => "['Cisco Certified Network Associate (CCNA)', 'CompTIA Network+', 'JNCIA']",
    'Web Developer' => "['AWS Certified Developer', 'Zend Certified PHP Engineer', 'Google Developers Certification']",
    'IT Support Specialist' => "['CompTIA A+', 'Google IT Support Professional Certificate', 'Microsoft 365 Certified']",
    'Staff Nurse' => "['Philippine Nursing Licensure Examination (PNLE)', 'Basic Life Support (BLS)', 'Advance Cardiac Life Support (ACLS)']",
    'Human Resources Specialist' => "['Certified Human Resources Professional (CHRP)', 'SHRM Certified Professional']",
    'Educational Coordinator' => "['Licensure Examination for Teachers (LET)', 'Educational Leadership & Administration Certificate']",
    'Content Writer' => "['HubSpot Content Marketing Certification', 'Google Digital Garage Certificate', 'SEO Certification']",
    'Social Worker' => "['Licensure Exam for Social Workers', 'Certified Clinical Social Worker']",
    'Compliance Officer' => "['Certified Compliance & Ethics Professional (CCEP)', 'Certified Regulatory Compliance Manager (CRCM)']",
    'Administrative Officer' => "['Certified Administrative Professional (CAP)', 'Civil Service Eligibility (Professional)']",
    'Operations Manager' => "['Project Management Professional (PMP)', 'Six Sigma Green Belt', 'Certified Supply Chain Professional']",
    'Business Development Manager' => "['Certified Business Development Professional', 'Salesforce Certified Administrator']",
    'Marketing Coordinator' => "['Google Analytics Individual Qualification', 'Facebook Blueprint Certification', 'HubSpot Certification']",
    'Sales Representative' => "['Certified Sales Professional (CSP)', 'HubSpot Sales Software Certification']",
    'Customer Service Representative' => "['Certified Customer Service Professional (CCSP)', 'Call Center Fundamentals Certificate']",
    'Event Coordinator' => "['Certified Special Events Professional (CSEP)', 'Certified Meeting Professional (CMP)']",
    'Hotel Front Desk Agent' => "['Certified Front Desk Representative', 'Hospitality Management Diploma']",
    'Public Relations Officer' => "['Accreditation in Public Relations (APR)', 'Crisis Communication Certificate']",
    'Communications Specialist' => "['Accredited Business Communicator (ABC)', 'Digital Marketing Certificate']",
    'Market Research Analyst' => "['Professional Researcher Certification (PRC)', 'Google Analytics Certification']",
    'Curriculum Developer' => "['Licensure Examination for Teachers (LET)', 'Instructional Design Certificate']",
    'Computer Engineer' => "['Licensure Exam for Electronics/Computer Engineers', 'Cisco Certified Network Professional (CCNP)']",
    'Electronics Engineer' => "['Electronics Engineer Licensure Examination', 'Certified Electronics Technician (CET)']",
    'Physical Therapist' => "['Physical and Occupational Therapy Licensure Examination', 'Basic Life Support (BLS)']",
    'Occupational Therapist' => "['Physical and Occupational Therapy Licensure Examination', 'NBCOT Certification']",
    'Elementary School Teacher' => "['Licensure Examination for Teachers (LET)', 'TEFL/TESOL Certification']",
];

$success = 0;
foreach($certs as $career => $certList) {
    if (strpos($content, "'$career' => [") === false) {
        echo "Missing career: $career\n";
        continue;
    }
    
    // Use regex to inject 'certifications_required' => $certList after 'skills_required' => [...]
    $escaped = preg_quote($career, '/');
    $pattern = "/('$escaped'\s*=>\s*\[.*?\'skills_required\'\s*=>\s*\[.*?\])(,)/s";
    
    // Check if certifications_required already exists
    $careerBlock = substr($content, strpos($content, "'$career' => ["), 1000);
    if (strpos($careerBlock, "'certifications_required'") === false) {
        if(preg_match($pattern, $content)) {
            $content = preg_replace($pattern, "$1,\n                'certifications_required' => $certList,", $content, 1);
            $success++;
        }
    } else {
        echo "Certifications already exist for $career\n";
    }
}

file_put_contents($filepath, $content);
echo "Successfully updated $success careers.\n";
