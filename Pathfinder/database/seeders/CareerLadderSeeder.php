<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CareerLadder;
use Illuminate\Support\Facades\DB;

class CareerLadderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('career_ladders')->truncate();

        $progressions = array_merge(
            $this->getITProgressions(),
            $this->getBusinessProgressions(),
            $this->getEducationProgressions(),
            $this->getEngineeringProgressions(),
            $this->getHealthcareProgressions(),
            $this->getLawProgressions(),
            $this->getLiberalArtsProgressions(),
            $this->getTourismProgressions()
        );

        foreach ($progressions as $targetRole => $steps) {
            foreach ($steps as $step) {
                CareerLadder::create([
                    'target_role' => $targetRole,
                    'step_role' => $step['step_role'],
                    'level' => $step['level'],
                    'sequence_order' => $step['sequence'],
                    'prerequisites' => $step['prereq'],
                    'typical_duration_months' => $step['duration'],
                    'min_years_experience' => $step['min_exp'],
                    'max_years_experience' => $step['max_exp'],
                    'transition_requirements' => $step['transition'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ Career ladder progressions seeded successfully!');
        $this->command->info('   Total roles: ' . count($progressions));
        $this->command->info('   - IT & Computer Science: 8 roles');
        $this->command->info('   - Business & Administration: 8 roles');
        $this->command->info('   - Education & Training: 8 roles');
        $this->command->info('   - Engineering & Technology: 8 roles');
        $this->command->info('   - Healthcare & Medical: 8 roles');
        $this->command->info('   - Law & Public Administration: 6 roles');
        $this->command->info('   - Liberal Arts & Social Sciences: 8 roles');
        $this->command->info('   - Tourism & Hospitality: 8 roles');
    }

    private function getITProgressions()
    {
        return [
            // 1. SOFTWARE DEVELOPER
            'Software Developer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete BS Computer Science/IT degree, build portfolio projects'],
                ['step_role' => 'CS/IT Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Computer Science/IT degree', 'Portfolio of projects'], 'transition' => 'Apply for junior developer positions, contribute to open source'],
                ['step_role' => 'Junior Software Developer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Programming fundamentals', 'Git version control'], 'transition' => 'Master at least one tech stack, complete features independently'],
                ['step_role' => 'Software Developer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Full-stack capabilities'], 'transition' => 'Lead projects, mentor juniors, optimize application performance'],
                ['step_role' => 'Senior Software Developer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'System design skills'], 'transition' => 'Define architecture, drive technical decisions, establish coding standards'],
                ['step_role' => 'Staff Engineer / Tech Lead', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 36, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership and mentoring skills'], 'transition' => 'Shape engineering culture, guide multiple teams'],
                ['step_role' => 'Principal Engineer', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 48, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Strategic technical vision'], 'transition' => 'Set technical direction for entire organization'],
                ['step_role' => 'VP of Engineering / CTO', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive leadership', 'Business acumen'], 'transition' => 'Highest technical leadership position'],
            ],

            // 2. IT SUPPORT SPECIALIST
            'IT Support Specialist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete IT/Computer Science degree or certifications'],
                ['step_role' => 'IT Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['IT degree or CompTIA A+ certification', 'Basic troubleshooting skills'], 'transition' => 'Get CompTIA A+, Network+ certifications'],
                ['step_role' => 'Help Desk Technician', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['CompTIA A+ certification', 'Customer service skills'], 'transition' => 'Develop Windows/Mac administration skills, learn ticketing systems'],
                ['step_role' => 'IT Support Specialist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years help desk experience', 'OS administration'], 'transition' => 'Specialize in network or systems support, get ITIL certification'],
                ['step_role' => 'Senior IT Support Specialist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Advanced troubleshooting', 'ITIL certified'], 'transition' => 'Lead support team, handle escalations, implement IT processes'],
                ['step_role' => 'IT Support Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership', 'ITSM knowledge'], 'transition' => 'Manage IT service delivery, oversee multiple support teams'],
                ['step_role' => 'IT Service Delivery Manager', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic IT service management'], 'transition' => 'Oversee all IT support operations, align with business goals'],
            ],

            // 3. DATA ANALYST
            'Data Analyst' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'Strong math skills'], 'transition' => 'Complete degree in Statistics, Mathematics, CS, or Business Analytics'],
                ['step_role' => 'Analytics Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Relevant degree', 'Excel proficiency', 'Basic SQL'], 'transition' => 'Learn Tableau/Power BI, advanced SQL, Python basics'],
                ['step_role' => 'Junior Data Analyst', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['SQL and Excel skills', 'Data visualization basics'], 'transition' => 'Master visualization tools, develop statistical analysis skills'],
                ['step_role' => 'Data Analyst', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Statistical analysis', 'Dashboard creation'], 'transition' => 'Learn predictive modeling, advanced Python/R'],
                ['step_role' => 'Senior Data Analyst', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Advanced analytics', 'Business intelligence'], 'transition' => 'Lead analytics projects, develop data strategy'],
                ['step_role' => 'Lead Data Analyst / Analytics Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership', 'Data strategy'], 'transition' => 'Manage analytics team, drive data-driven decision making'],
                ['step_role' => 'Director of Analytics', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic analytics leadership'], 'transition' => 'Lead enterprise analytics function, influence executive decisions'],
            ],

            // 4. WEB DEVELOPER
            'Web Developer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete CS/IT degree or web development bootcamp, build portfolio'],
                ['step_role' => 'Web Development Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['HTML/CSS/JavaScript fundamentals', 'Portfolio website'], 'transition' => 'Master a modern framework (React/Vue/Angular), responsive design'],
                ['step_role' => 'Junior Web Developer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Front-end fundamentals', 'Responsive design principles'], 'transition' => 'Learn back-end development, become full-stack capable'],
                ['step_role' => 'Web Developer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Full-stack capabilities', 'Database knowledge'], 'transition' => 'Optimize web performance, implement best practices, lead features'],
                ['step_role' => 'Senior Web Developer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Web architecture design', 'Security best practices'], 'transition' => 'Define technical standards, mentor developers, review code'],
                ['step_role' => 'Lead Web Developer', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership', 'Modern web technologies'], 'transition' => 'Manage web development teams, set technical direction'],
                ['step_role' => 'Web Development Manager / Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'People management', 'Strategic planning'], 'transition' => 'Lead web development function, drive digital transformation'],
            ],

            // 5. SYSTEMS ADMINISTRATOR
            'Systems Administrator' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete IT/Computer Science degree, get Linux/Windows certifications'],
                ['step_role' => 'IT Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['IT degree', 'Basic server knowledge'], 'transition' => 'Get CompTIA Linux+, Microsoft certifications, hands-on server experience'],
                ['step_role' => 'Junior Systems Administrator', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Linux/Windows administration basics', 'Networking fundamentals'], 'transition' => 'Master server management, learn automation with scripts'],
                ['step_role' => 'Systems Administrator', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Server OS expertise', 'Shell scripting'], 'transition' => 'Implement automation, learn cloud platforms (AWS/Azure)'],
                ['step_role' => 'Senior Systems Administrator', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Advanced automation', 'Cloud platforms'], 'transition' => 'Design infrastructure, lead system migrations, mentor juniors'],
                ['step_role' => 'Systems Architect / DevOps Engineer', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Infrastructure as Code', 'CI/CD pipelines'], 'transition' => 'Define infrastructure strategy, implement DevOps practices'],
                ['step_role' => 'Infrastructure Manager / Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Team leadership', 'Strategic infrastructure planning'], 'transition' => 'Oversee all infrastructure operations, align with business needs'],
            ],

            // 6. CYBERSECURITY ANALYST
            'Cybersecurity Analyst' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'Interest in cybersecurity'], 'transition' => 'Complete degree in CS/IT/Cybersecurity, get Security+ certification'],
                ['step_role' => 'Cybersecurity Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Relevant degree', 'CompTIA Security+', 'Networking basics'], 'transition' => 'Get hands-on with security tools, pursue CEH certification'],
                ['step_role' => 'Junior Security Analyst', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Security+ or CEH certification', 'Security tools knowledge'], 'transition' => 'Develop incident response skills, master SIEM tools'],
                ['step_role' => 'Security Analyst', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years security experience', 'SIEM proficiency', 'Threat analysis'], 'transition' => 'Lead security investigations, get advanced certifications (CISSP)'],
                ['step_role' => 'Senior Security Analyst', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'CISSP or CISM', 'Incident response'], 'transition' => 'Develop security architecture skills, mentor junior analysts'],
                ['step_role' => 'Security Architect / Security Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Security architecture', 'Team leadership'], 'transition' => 'Lead enterprise security strategy, manage security teams'],
                ['step_role' => 'Principal Security Engineer', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 48, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Strategic security planning'], 'transition' => 'Drive organizational security transformation'],
                ['step_role' => 'Chief Information Security Officer (CISO)', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive leadership', 'Risk management'], 'transition' => 'Highest cybersecurity position in organization'],
            ],

            // 7. DATABASE ADMINISTRATOR
            'Database Administrator' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'Interest in data management'], 'transition' => 'Complete CS/IT degree with focus on databases'],
                ['step_role' => 'IT/Database Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Relevant degree', 'SQL proficiency', 'Database fundamentals'], 'transition' => 'Get database certifications (MySQL, PostgreSQL, Oracle, SQL Server)'],
                ['step_role' => 'Junior Database Administrator', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['SQL expertise', 'Database management basics'], 'transition' => 'Learn backup/recovery, performance tuning, monitoring tools'],
                ['step_role' => 'Database Administrator', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Backup/recovery procedures', 'Performance optimization'], 'transition' => 'Master high availability, replication, security hardening'],
                ['step_role' => 'Senior Database Administrator', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'High availability design', 'Advanced tuning'], 'transition' => 'Design database architecture, lead migrations, mentor team'],
                ['step_role' => 'Lead DBA / Database Architect', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Database architecture', 'Team leadership'], 'transition' => 'Define data strategy, manage DBA team, oversee all databases'],
                ['step_role' => 'Database Manager / Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic data management', 'Enterprise architecture'], 'transition' => 'Lead database operations, align with business goals'],
            ],

            // 8. NETWORK ADMINISTRATOR
            'Network Administrator' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete IT/Network Engineering degree, get Network+ certification'],
                ['step_role' => 'Network Engineering Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['IT degree', 'CompTIA Network+', 'TCP/IP knowledge'], 'transition' => 'Get Cisco CCNA or equivalent, hands-on networking experience'],
                ['step_role' => 'Junior Network Administrator', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Network+ and CCNA', 'Router/switch basics'], 'transition' => 'Master network monitoring, learn firewall configuration'],
                ['step_role' => 'Network Administrator', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Routing/switching proficiency', 'Firewall management'], 'transition' => 'Implement VPNs, learn wireless technologies, get CCNP'],
                ['step_role' => 'Senior Network Administrator', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'CCNP or equivalent', 'Advanced routing'], 'transition' => 'Design network architecture, lead infrastructure projects'],
                ['step_role' => 'Network Architect / Network Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Network design', 'Team leadership'], 'transition' => 'Define network strategy, manage network operations team'],
                ['step_role' => 'Director of Network Operations', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic network planning', 'Enterprise networking'], 'transition' => 'Oversee all network infrastructure, drive digital connectivity'],
            ],
        ];
    }

    private function getBusinessProgressions()
    {
        return [
            // 1. ACCOUNTANT
            'Accountant' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'Accountancy track'], 'transition' => 'Complete BS Accountancy, start CPA review'],
                ['step_role' => 'BS Accountancy Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Accountancy', 'CPA board exam review'], 'transition' => 'Pass CPA board, gain audit internship experience'],
                ['step_role' => 'Junior Accountant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['CPA passer or licensed', 'Basic accounting systems'], 'transition' => 'Handle GL and AP/AR, build audit skills'],
                ['step_role' => 'Accountant', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'CPA license'], 'transition' => 'Own monthly close, tax compliance, start specialization (audit/tax/FP&A)'],
                ['step_role' => 'Senior Accountant', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Advanced accounting standards'], 'transition' => 'Lead audits, manage close calendar, mentor juniors'],
                ['step_role' => 'Accounting Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership', 'CPA'], 'transition' => 'Own financial statements, oversee controls, coordinate with external auditors'],
                ['step_role' => 'Financial Controller', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Strong FP&A and controls'], 'transition' => 'Lead controllership, policies, and compliance'],
                ['step_role' => 'Chief Financial Officer (CFO)', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Strategic finance leadership'], 'transition' => 'Set financial strategy, capital structure, and governance'],
            ],

            // 2. SALES REPRESENTATIVE
            'Sales Representative' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete business/marketing degree or sales training'],
                ['step_role' => 'Business/Marketing Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Business/Marketing'], 'transition' => 'Take sales internships, learn CRM basics'],
                ['step_role' => 'Junior Sales Representative', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Prospecting skills', 'CRM usage'], 'transition' => 'Hit activity metrics, build product knowledge'],
                ['step_role' => 'Sales Representative', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['Consistent quota attainment'], 'transition' => 'Manage full sales cycle, improve negotiation'],
                ['step_role' => 'Senior Sales Representative', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'High win rates'], 'transition' => 'Handle key accounts, mentor juniors'],
                ['step_role' => 'Sales Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Manage team quotas, forecasting, pipeline health'],
                ['step_role' => 'Head of Sales / Director', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Strategic sales planning'], 'transition' => 'Set sales strategy, compensation plans, territories'],
                ['step_role' => 'VP Sales / Chief Revenue Officer', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive leadership'], 'transition' => 'Own revenue strategy, GTM alignment, enterprise deals'],
            ],

            // 3. ADMINISTRATIVE OFFICER
            'Administrative Officer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete business administration degree or office management training'],
                ['step_role' => 'Business Admin Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSBA or related'], 'transition' => 'Learn office systems, procurement basics, scheduling'],
                ['step_role' => 'Administrative Assistant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Office software proficiency', 'Organization skills'], 'transition' => 'Handle calendars, vendor coordination, records'],
                ['step_role' => 'Administrative Officer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years admin experience'], 'transition' => 'Manage procurement, facilities coordination, policy compliance'],
                ['step_role' => 'Senior Administrative Officer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Process improvement'], 'transition' => 'Lead admin processes, supervise assistants'],
                ['step_role' => 'Administration Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Vendor and facilities management'], 'transition' => 'Oversee office operations, budget, contracts'],
                ['step_role' => 'Director of Administration', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic operations'], 'transition' => 'Own administrative strategy, compliance, and service levels'],
            ],

            // 4. MARKETING COORDINATOR
            'Marketing Coordinator' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete marketing/communications degree, build portfolio'],
                ['step_role' => 'Marketing Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Marketing/Comms'], 'transition' => 'Do marketing internships, learn digital channels and analytics'],
                ['step_role' => 'Marketing Assistant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Content and social basics', 'Canva/Adobe familiarity'], 'transition' => 'Execute campaigns, track metrics, learn SEO/SEM'],
                ['step_role' => 'Marketing Coordinator', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['Campaign coordination', 'Analytics tools'], 'transition' => 'Own channel performance, optimize spend'],
                ['step_role' => 'Marketing Specialist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Channel expertise'], 'transition' => 'Design multi-channel strategies, A/B testing, growth experiments'],
                ['step_role' => 'Marketing Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Budget ownership'], 'transition' => 'Lead team, manage agencies, own KPIs and ROI'],
                ['step_role' => 'Marketing Director / Head of Marketing', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Brand and growth strategy'], 'transition' => 'Set marketing strategy, positioning, and GTM'],
                ['step_role' => 'Chief Marketing Officer (CMO)', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive marketing leadership'], 'transition' => 'Own brand, demand, and market expansion'],
            ],

            // 5. FINANCIAL ANALYST
            'Financial Analyst' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'Strong math skills'], 'transition' => 'Complete finance/accounting degree'],
                ['step_role' => 'Finance Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Finance/Accounting'], 'transition' => 'Learn Excel modeling, take CFA Level 1 prep'],
                ['step_role' => 'Junior Financial Analyst', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Financial modeling basics', 'Excel/Sheets'], 'transition' => 'Support budgeting/forecasting, variance analysis'],
                ['step_role' => 'Financial Analyst', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Modeling and reporting'], 'transition' => 'Own P&L models, partner with business units'],
                ['step_role' => 'Senior Financial Analyst', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Advanced modeling', 'CFA progress'], 'transition' => 'Lead FP&A cycles, scenario planning, board decks'],
                ['step_role' => 'FP&A Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership', 'Budget ownership'], 'transition' => 'Manage FP&A team, refine forecasting process'],
                ['step_role' => 'Director of FP&A', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Strategic finance'], 'transition' => 'Drive long-range planning, executive reporting'],
                ['step_role' => 'Vice President of Finance', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive finance leadership'], 'transition' => 'Oversee corporate finance, capital allocation'],
            ],

            // 6. HUMAN RESOURCES SPECIALIST
            'Human Resources Specialist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete HR/psychology/industrial relations degree'],
                ['step_role' => 'HR Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS HR/PSY/IRM'], 'transition' => 'Learn labor laws, recruitment basics, HRIS tools'],
                ['step_role' => 'HR Assistant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['HRIS and recruitment support'], 'transition' => 'Handle onboarding, payroll support, employee queries'],
                ['step_role' => 'Human Resources Specialist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Recruitment/ER'], 'transition' => 'Own recruitment cycles or HR operations, ensure compliance'],
                ['step_role' => 'Senior HR Specialist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Employee relations', 'Policy design'], 'transition' => 'Lead HR programs, handle complex ER cases'],
                ['step_role' => 'HR Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership', 'Comp & ben knowledge'], 'transition' => 'Manage HR team, drive performance management'],
                ['step_role' => 'HR Director / Head of HR', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Strategic HR'], 'transition' => 'Set HR strategy, workforce planning, culture'],
                ['step_role' => 'Chief Human Resources Officer (CHRO)', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive HR leadership'], 'transition' => 'Own people strategy, org design, and talent roadmap'],
            ],

            // 7. BUSINESS DEVELOPMENT MANAGER
            'Business Development Manager' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete business/marketing degree, learn sales fundamentals'],
                ['step_role' => 'Business Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Business/Marketing'], 'transition' => 'Intern in sales or partnerships, practice presentations'],
                ['step_role' => 'Junior Business Development Associate', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Prospecting and research'], 'transition' => 'Source leads, schedule demos, support proposals'],
                ['step_role' => 'Business Development Executive', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Pitching and negotiation'], 'transition' => 'Own pipeline, close mid-size deals, manage partners'],
                ['step_role' => 'Senior Business Development Executive', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Consistent deal closures'], 'transition' => 'Drive enterprise deals, structure partnerships, mentor team'],
                ['step_role' => 'Business Development Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Set BD targets, coach team, coordinate with marketing and product'],
                ['step_role' => 'Director of Business Development', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Strategic partnerships'], 'transition' => 'Shape growth strategy, alliances, and market expansion'],
                ['step_role' => 'VP Strategic Partnerships', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive partnership leadership'], 'transition' => 'Own ecosystem strategy and major alliances'],
            ],

            // 8. OPERATIONS MANAGER
            'Operations Manager' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete business/industrial engineering/operations degree'],
                ['step_role' => 'Operations/Business Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Business/Operations'], 'transition' => 'Learn process mapping, basic supply chain, quality tools'],
                ['step_role' => 'Operations Assistant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Process documentation', 'Data tracking'], 'transition' => 'Support daily ops, track KPIs, identify bottlenecks'],
                ['step_role' => 'Operations Supervisor', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'People supervision'], 'transition' => 'Oversee shifts/teams, implement SOPs, improve throughput'],
                ['step_role' => 'Operations Manager', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Process improvement'], 'transition' => 'Own KPIs, budgets, continuous improvement projects'],
                ['step_role' => 'Senior Operations Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Lean/Six Sigma'], 'transition' => 'Scale operations, capacity planning, cross-functional leadership'],
                ['step_role' => 'Director of Operations', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Strategic operations leadership'], 'transition' => 'Define operations strategy, multi-site oversight'],
                ['step_role' => 'Chief Operating Officer (COO)', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive operations leadership'], 'transition' => 'Own company operations, execution, and efficiency'],
            ],
        ];
    }

    private function getEducationProgressions()
    {
        return [
            // 1. ELEMENTARY SCHOOL TEACHER
            'Elementary School Teacher' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'Education strand'], 'transition' => 'Complete Bachelor of Elementary Education (BEEd)'],
                ['step_role' => 'BEEd Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BEEd degree', 'Demo teaching'], 'transition' => 'Pass Licensure Exam for Teachers (LET), complete practice teaching'],
                ['step_role' => 'Student Teacher / Intern', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['LET passer (or pending release)', 'Classroom practicum'], 'transition' => 'Handle supervised classes, lesson planning, assessment'],
                ['step_role' => 'Elementary School Teacher', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 36, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['LET license', 'Classroom management'], 'transition' => 'Lead a class, integrate differentiated instruction'],
                ['step_role' => 'Senior Elementary Teacher', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years teaching', 'Training credits'], 'transition' => 'Mentor teachers, lead subject coordination'],
                ['step_role' => 'Master Teacher', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Outstanding performance'], 'transition' => 'Lead pedagogy improvements, demo lessons'],
                ['step_role' => 'Assistant Principal', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years teaching', 'Admin training'], 'transition' => 'Oversee grade-level operations, parent engagement'],
                ['step_role' => 'School Principal', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Educational leadership'], 'transition' => 'Lead school strategy, budget, and faculty development'],
            ],

            // 2. HIGH SCHOOL ENGLISH TEACHER
            'High School English Teacher' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'Humanities track'], 'transition' => 'Complete BSEd Major in English'],
                ['step_role' => 'BSEd English Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSEd English', 'Practice teaching'], 'transition' => 'Pass LET, build teaching portfolio'],
                ['step_role' => 'Junior High School English Teacher', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['LET license', 'Classroom practicum'], 'transition' => 'Teach junior HS English, manage classroom routines'],
                ['step_role' => 'High School English Teacher', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 36, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1+ years experience', 'Curriculum planning'], 'transition' => 'Integrate literature and composition, assessment design'],
                ['step_role' => 'Senior English Teacher', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Training credits'], 'transition' => 'Lead English program, coach teachers'],
                ['step_role' => 'Master Teacher (English)', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Demo teaching excellence'], 'transition' => 'Develop curriculum guides, conduct LAC sessions'],
                ['step_role' => 'Department Head (English)', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Program leadership'], 'transition' => 'Oversee English department, teacher evaluations'],
                ['step_role' => 'School Principal', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Leadership credential'], 'transition' => 'Lead entire school operations and strategy'],
            ],

            // 3. HIGH SCHOOL MATH TEACHER
            'High School Math Teacher' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BSEd Major in Mathematics'],
                ['step_role' => 'BSEd Math Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSEd Math', 'Practice teaching'], 'transition' => 'Pass LET, prepare math lesson portfolio'],
                ['step_role' => 'Junior High School Math Teacher', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['LET license', 'Math pedagogy basics'], 'transition' => 'Teach junior HS math, classroom management'],
                ['step_role' => 'High School Math Teacher', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 36, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1+ years experience', 'Lesson planning'], 'transition' => 'Handle algebra/geometry/calculus, differentiated instruction'],
                ['step_role' => 'Senior Math Teacher', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Training credits'], 'transition' => 'Lead math competitions, enrichment programs'],
                ['step_role' => 'Master Teacher (Math)', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Instructional excellence'], 'transition' => 'Design math curriculum materials, mentor teachers'],
                ['step_role' => 'Department Head (Math)', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Program leadership'], 'transition' => 'Oversee math department, teacher evaluations'],
                ['step_role' => 'School Principal', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Leadership credential'], 'transition' => 'Lead entire school operations and strategy'],
            ],

            // 4. HIGH SCHOOL SCIENCE TEACHER
            'High School Science Teacher' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BSEd Major in Science or Biology/Chemistry/Physics'],
                ['step_role' => 'BSEd Science Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSEd Science', 'Laboratory practicum'], 'transition' => 'Pass LET, compile lab safety training'],
                ['step_role' => 'Junior High School Science Teacher', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['LET license', 'Lab management basics'], 'transition' => 'Teach basic science, manage lab activities'],
                ['step_role' => 'High School Science Teacher', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 36, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1+ years experience', 'Lesson and lab plans'], 'transition' => 'Handle specialized subjects (bio/chem/physics/earth sci)'],
                ['step_role' => 'Senior Science Teacher', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Lab safety leadership'], 'transition' => 'Lead science fairs, lab upgrades, teacher mentoring'],
                ['step_role' => 'Master Teacher (Science)', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Instructional excellence'], 'transition' => 'Develop science curriculum and lab standards'],
                ['step_role' => 'Department Head (Science)', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Program leadership'], 'transition' => 'Oversee science department, professional development'],
                ['step_role' => 'School Principal', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Leadership credential'], 'transition' => 'Lead entire school operations and strategy'],
            ],

            // 5. PRESCHOOL TEACHER
            'Preschool Teacher' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete Early Childhood Education degree or related'],
                ['step_role' => 'ECE Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['ECE degree', 'Child development training'], 'transition' => 'Gain practicum hours, pass LET (if required)'],
                ['step_role' => 'Assistant Preschool Teacher', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Childcare basics', 'First aid training'], 'transition' => 'Support lead teacher, manage activities'],
                ['step_role' => 'Preschool Teacher', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 36, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['ECE training', 'Classroom routines'], 'transition' => 'Plan play-based curriculum, assess early learners'],
                ['step_role' => 'Senior Preschool Teacher', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Parent communication'], 'transition' => 'Lead class programs, mentor assistants'],
                ['step_role' => 'Lead Teacher / Center Supervisor', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Center operations'], 'transition' => 'Oversee center curriculum, compliance, and safety'],
                ['step_role' => 'Early Childhood Center Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Leadership and licensing knowledge'], 'transition' => 'Manage center operations, staff, and programs'],
            ],

            // 6. SPECIAL EDUCATION TEACHER
            'Special Education Teacher' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete Special Education degree'],
                ['step_role' => 'SpEd Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['SpEd degree', 'Practicum'], 'transition' => 'Pass LET (SpEd), complete practicums with diverse learners'],
                ['step_role' => 'Assistant Special Education Teacher', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['SpEd training', 'Behavior management'], 'transition' => 'Support IEP implementation, assist lead teacher'],
                ['step_role' => 'Special Education Teacher', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 36, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['LET (SpEd)', 'IEP creation'], 'transition' => 'Lead IEPs, collaborate with therapists and parents'],
                ['step_role' => 'Senior Special Education Teacher', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Advanced strategies'], 'transition' => 'Coach teachers, refine interventions and accommodations'],
                ['step_role' => 'Lead SpEd Teacher / Program Coordinator', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Program leadership'], 'transition' => 'Oversee SpEd programs, compliance, and parent engagement'],
                ['step_role' => 'Director of Special Education', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'SpEd leadership'], 'transition' => 'Set SpEd strategy, budgets, and staffing'],
            ],

            // 7. EDUCATIONAL COORDINATOR
            'Educational Coordinator' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete education/psychology/management degree'],
                ['step_role' => 'Education Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Education degree', 'Fieldwork'], 'transition' => 'Assist in program design, data collection'],
                ['step_role' => 'Program Assistant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Program coordination basics', 'Documentation'], 'transition' => 'Support scheduling, materials, and reports'],
                ['step_role' => 'Educational Coordinator', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Curriculum support'], 'transition' => 'Coordinate programs, trainings, stakeholder communication'],
                ['step_role' => 'Senior Educational Coordinator', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Project management'], 'transition' => 'Lead initiatives, manage budgets, monitor outcomes'],
                ['step_role' => 'Program Manager / Training Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Oversee multiple programs, vendor/partner coordination'],
                ['step_role' => 'Director of Educational Programs', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic program leadership'], 'transition' => 'Set program strategy, evaluation, and expansion'],
            ],

            // 8. CURRICULUM DEVELOPER
            'Curriculum Developer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete education or instructional design degree'],
                ['step_role' => 'Education/ID Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Education or Instructional Design degree'], 'transition' => 'Learn assessment design, standards alignment'],
                ['step_role' => 'Curriculum Writer / Specialist', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Lesson planning skills', 'Standards familiarity'], 'transition' => 'Create lesson plans, pilot materials, gather feedback'],
                ['step_role' => 'Curriculum Developer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Assessment design'], 'transition' => 'Develop full units, align to standards, teacher guides'],
                ['step_role' => 'Senior Curriculum Developer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Instructional design expertise'], 'transition' => 'Lead content teams, incorporate multimedia and technology'],
                ['step_role' => 'Curriculum Manager / Instructional Design Lead', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Oversee curriculum roadmap, quality assurance, training'],
                ['step_role' => 'Director of Curriculum and Instruction', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic curriculum leadership'], 'transition' => 'Set curriculum strategy, research-based improvements'],
            ],
        ];
    }

    private function getEngineeringProgressions()
    {
        return [
            // 1. CIVIL ENGINEER
            'Civil Engineer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Civil Engineering'],
                ['step_role' => 'BS Civil Engineering Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSCE degree'], 'transition' => 'Pass Civil Engineering board exam, apprentice under licensed engineer'],
                ['step_role' => 'Junior Civil Engineer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Board passer (or pending license)', 'AutoCAD/structural basics'], 'transition' => 'Assist site supervision, QC, surveying'],
                ['step_role' => 'Civil Engineer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['PRC license', 'Site/office coordination'], 'transition' => 'Handle sub-projects, prepare estimates, manage contractors'],
                ['step_role' => 'Senior Civil Engineer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Structural/roads/utilities experience'], 'transition' => 'Lead discipline work, sign/seal plans, mentor juniors'],
                ['step_role' => 'Civil Engineering Lead / Project Engineer', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Project leadership'], 'transition' => 'Own project delivery, interface with clients and LGUs'],
                ['step_role' => 'Engineering Manager', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Portfolio leadership'], 'transition' => 'Manage multiple projects/teams, budgets, compliance'],
                ['step_role' => 'Director of Engineering', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive oversight'], 'transition' => 'Set engineering strategy, safety, and quality standards'],
            ],

            // 2. MECHANICAL ENGINEER
            'Mechanical Engineer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Mechanical Engineering'],
                ['step_role' => 'BSME Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSME degree'], 'transition' => 'Pass Mechanical Engineering board exam, OJT with plants/workshops'],
                ['step_role' => 'Junior Mechanical Engineer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Board passer (or pending license)', 'CAD and fabrication basics'], 'transition' => 'Support design, maintenance, testing'],
                ['step_role' => 'Mechanical Engineer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['PRC license', 'Plant/production exposure'], 'transition' => 'Own subsystem design, maintenance plans'],
                ['step_role' => 'Senior Mechanical Engineer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'HVAC/rotating equipment'], 'transition' => 'Lead designs, reliability improvements, mentor engineers'],
                ['step_role' => 'Mechanical Lead / Maintenance Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Manage mechanical teams, budgets, vendors'],
                ['step_role' => 'Engineering Manager (Mechanical)', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Project/asset ownership'], 'transition' => 'Oversee multiple plants/projects, standards'],
                ['step_role' => 'Director of Mechanical Engineering', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive leadership'], 'transition' => 'Set mechanical strategy, reliability, and safety policy'],
            ],

            // 3. ELECTRICAL ENGINEER
            'Electrical Engineer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Electrical Engineering'],
                ['step_role' => 'BSEE Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSEE degree'], 'transition' => 'Pass Electrical Engineering board exam, safety training'],
                ['step_role' => 'Junior Electrical Engineer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Board passer (or pending license)', 'Power systems basics'], 'transition' => 'Assist design, testing, commissioning'],
                ['step_role' => 'Electrical Engineer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['PRC license', 'Load calculations', 'Protection design'], 'transition' => 'Own circuits/panels, site inspections'],
                ['step_role' => 'Senior Electrical Engineer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Power distribution'], 'transition' => 'Lead electrical designs, sign/seal plans, mentor'],
                ['step_role' => 'Electrical Lead / Power Systems Engineer', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Protection/SCADA'], 'transition' => 'Lead power projects, coordinate with utilities'],
                ['step_role' => 'Engineering Manager (Electrical)', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Portfolio leadership'], 'transition' => 'Oversee multiple electrical projects and standards'],
                ['step_role' => 'Director of Electrical Engineering', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive oversight'], 'transition' => 'Set electrical engineering policy, reliability, compliance'],
            ],

            // 4. ELECTRONICS ENGINEER
            'Electronics Engineer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Electronics Engineering'],
                ['step_role' => 'BSECE Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSECE degree'], 'transition' => 'Pass ECE board exam, projects on circuits and embedded systems'],
                ['step_role' => 'Junior Electronics Engineer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Board passer (or pending license)', 'Circuit design basics'], 'transition' => 'Assist PCB design, testing, firmware basics'],
                ['step_role' => 'Electronics Engineer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['PRC license', 'Signal/embedded knowledge'], 'transition' => 'Develop and test boards, integrate firmware/hardware'],
                ['step_role' => 'Senior Electronics Engineer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'High-speed/EMI'], 'transition' => 'Lead designs, certifications (EMC/FCC), mentor team'],
                ['step_role' => 'Lead Electronics Engineer / Hardware Lead', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Own hardware roadmap, vendor coordination'],
                ['step_role' => 'Hardware Engineering Manager', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Program management'], 'transition' => 'Manage multiple products, quality and compliance'],
                ['step_role' => 'Director of Hardware Engineering', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive leadership'], 'transition' => 'Set hardware strategy, supply chain and quality governance'],
            ],

            // 5. INDUSTRIAL ENGINEER
            'Industrial Engineer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM/ABM track'], 'transition' => 'Complete BS Industrial Engineering'],
                ['step_role' => 'BSIE Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSIE degree'], 'transition' => 'Take IE board (if pursuing license), internships in manufacturing/services'],
                ['step_role' => 'Junior Industrial Engineer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Process mapping', 'Basic statistics'], 'transition' => 'Collect time-motion data, support process improvements'],
                ['step_role' => 'Industrial Engineer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Lean basics'], 'transition' => 'Design workflows, capacity plans, KPI dashboards'],
                ['step_role' => 'Senior Industrial Engineer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Lean Six Sigma'], 'transition' => 'Lead CI projects, layout optimization, supply chain support'],
                ['step_role' => 'Process Improvement Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Manage CI teams, standardize processes, automation projects'],
                ['step_role' => 'Operations Excellence Manager', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Multi-site experience'], 'transition' => 'Drive OE strategy across plants/sites'],
                ['step_role' => 'Director of Operational Excellence', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive CI leadership'], 'transition' => 'Own enterprise CI/lean strategy and governance'],
            ],

            // 6. COMPUTER ENGINEER
            'Computer Engineer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Computer Engineering'],
                ['step_role' => 'BSCpE Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSCpE degree'], 'transition' => 'Projects in embedded systems, networking, or hardware-software integration'],
                ['step_role' => 'Junior Computer Engineer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Embedded/network basics'], 'transition' => 'Assist in firmware, device testing, or network setups'],
                ['step_role' => 'Computer Engineer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Systems integration'], 'transition' => 'Own modules (firmware, drivers, network), troubleshoot'],
                ['step_role' => 'Senior Computer Engineer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Embedded or network depth'], 'transition' => 'Lead subsystem design, security/reliability improvements'],
                ['step_role' => 'Systems / Hardware-Software Lead', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Architect integrated systems, coordinate across HW/SW/Network'],
                ['step_role' => 'Engineering Manager (Computer Engineering)', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Program ownership'], 'transition' => 'Lead multiple teams, roadmaps, and quality'],
                ['step_role' => 'Director of Computer Engineering', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive leadership'], 'transition' => 'Set strategy for embedded/networked products'],
            ],

            // 7. CHEMICAL ENGINEER
            'Chemical Engineer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Chemical Engineering'],
                ['step_role' => 'BSChemEng Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BSChE degree'], 'transition' => 'Pass Chemical Engineering board, plant OJT'],
                ['step_role' => 'Junior Chemical Engineer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Board passer (or pending license)', 'Process fundamentals'], 'transition' => 'Support process monitoring, lab testing, safety compliance'],
                ['step_role' => 'Chemical Engineer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['PRC license', 'Unit operations knowledge'], 'transition' => 'Optimize process parameters, material/energy balances'],
                ['step_role' => 'Senior Chemical Engineer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Process optimization'], 'transition' => 'Lead debottlenecking, scale-up, safety reviews'],
                ['step_role' => 'Process Engineering Lead', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Own process design/operations, coordinate with production/QC'],
                ['step_role' => 'Plant / Operations Manager', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Plant leadership'], 'transition' => 'Manage plant performance, safety, and reliability'],
                ['step_role' => 'Director of Process Engineering / Plant Director', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive operations leadership'], 'transition' => 'Set process strategy, capital projects, EHS governance'],
            ],

            // 8. PROJECT ENGINEER
            'Project Engineer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete engineering degree (civil/mech/elec/industrial)'],
                ['step_role' => 'Engineering Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Engineering degree'], 'transition' => 'Join project teams, learn scheduling and cost control'],
                ['step_role' => 'Junior Project Engineer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Project coordination basics'], 'transition' => 'Track progress, materials, RFIs, site coordination'],
                ['step_role' => 'Project Engineer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Scheduling (Primavera/MSP)'], 'transition' => 'Manage work packages, interface with suppliers'],
                ['step_role' => 'Senior Project Engineer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Cost/schedule control'], 'transition' => 'Lead full projects, risk management, client reporting'],
                ['step_role' => 'Project Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'PM certification preferred'], 'transition' => 'Own scope, schedule, cost, quality; lead project team'],
                ['step_role' => 'Program Manager', 'level' => 'Principal-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Multi-project leadership'], 'transition' => 'Oversee multiple projects/portfolios, governance'],
                ['step_role' => 'Director of Projects / PMO Director', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive project leadership'], 'transition' => 'Set PMO standards, capital planning, stakeholder alignment'],
            ],
        ];
    }

    private function getHealthcareProgressions()
    {
        return [
            // 1. STAFF NURSE
            'Staff Nurse' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM/Health track'], 'transition' => 'Complete BS Nursing'],
                ['step_role' => 'BS Nursing Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Nursing', 'Hospital internship'], 'transition' => 'Pass Nursing board (PRC), basic life support'],
                ['step_role' => 'Staff Nurse', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 24, 'min_exp' => 0, 'max_exp' => 2,
                 'prereq' => ['RN license', 'Clinical rotation'], 'transition' => 'Build specialization (ER/ICU/Ward), patient care'],
                ['step_role' => 'Senior Staff Nurse', 'level' => 'Mid-Level', 'sequence' => 4, 'duration' => 36, 'min_exp' => 2, 'max_exp' => 5,
                 'prereq' => ['2+ years experience', 'Specialty cert'], 'transition' => 'Serve as charge nurse, precept new nurses'],
                ['step_role' => 'Nurse Supervisor / Charge Nurse', 'level' => 'Senior-Level', 'sequence' => 5, 'duration' => 48, 'min_exp' => 5, 'max_exp' => 10,
                 'prereq' => ['5+ years experience', 'Leadership training'], 'transition' => 'Manage unit staffing, quality, and safety'],
                ['step_role' => 'Nurse Manager / Head Nurse', 'level' => 'Leadership-Level', 'sequence' => 6, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Masters in Nursing preferred'], 'transition' => 'Oversee multiple units, policy and compliance'],
                ['step_role' => 'Chief Nursing Officer', 'level' => 'Executive-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive leadership'], 'transition' => 'Own nursing strategy, staffing, quality programs'],
            ],

            // 2. MEDICAL TECHNOLOGIST
            'Medical Technologist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Medical Technology/Medical Laboratory Science'],
                ['step_role' => 'MedTech Intern', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS MedTech internship'], 'transition' => 'Pass MedTech board exam, infection control training'],
                ['step_role' => 'Junior Medical Technologist', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['PRC MedTech license', 'Lab procedures'], 'transition' => 'Handle routine tests, QA, equipment calibration'],
                ['step_role' => 'Medical Technologist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Section specialization'], 'transition' => 'Own hematology/chemistry/microbiology sections'],
                ['step_role' => 'Senior Medical Technologist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'QA/QC leadership'], 'transition' => 'Lead validations, supervise juniors, manage QC'],
                ['step_role' => 'Lab Supervisor', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Lab management'], 'transition' => 'Manage lab operations, inventory, compliance'],
                ['step_role' => 'Laboratory Manager / Chief MedTech', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Leadership and accreditation'], 'transition' => 'Oversee entire lab, accreditation, staffing'],
            ],

            // 3. PHARMACIST
            'Pharmacist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Pharmacy'],
                ['step_role' => 'Pharmacy Intern', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Pharmacy internship'], 'transition' => 'Pass Pharmacist licensure exam, patient counseling basics'],
                ['step_role' => 'Junior Pharmacist', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['PRC Pharmacist license'], 'transition' => 'Dispense, validate prescriptions, manage inventory'],
                ['step_role' => 'Pharmacist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Clinical/retail exposure'], 'transition' => 'Clinical reviews, antimicrobial stewardship, patient counseling'],
                ['step_role' => 'Senior Pharmacist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Operations leadership'], 'transition' => 'Lead pharmacy ops, supervise assistants/techs'],
                ['step_role' => 'Pharmacy Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Regulatory compliance'], 'transition' => 'Manage budgets, procurement, regulatory audits'],
                ['step_role' => 'Chief Pharmacist / Pharmacy Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Leadership'], 'transition' => 'Oversee enterprise pharmacy services, policy, quality'],
            ],

            // 4. PHYSICAL THERAPIST
            'Physical Therapist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete Bachelor of Physical Therapy'],
                ['step_role' => 'PT Intern', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BPT internship'], 'transition' => 'Pass PT licensure exam, CPR/BLS'],
                ['step_role' => 'Junior Physical Therapist', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['PRC PT license'], 'transition' => 'Treat patients under supervision, document plans'],
                ['step_role' => 'Physical Therapist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Modalities and manual therapy'], 'transition' => 'Own caseloads, coordinate with physicians'],
                ['step_role' => 'Senior Physical Therapist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Specialization (sports/neuro/peds)'], 'transition' => 'Lead rehab programs, mentor juniors'],
                ['step_role' => 'Rehabilitation Supervisor', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Manage rehab unit, protocols, quality'],
                ['step_role' => 'Rehabilitation Manager / Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Operations management'], 'transition' => 'Oversee rehab services, budgets, clinical outcomes'],
            ],

            // 5. RADIOLOGIC TECHNOLOGIST
            'Radiologic Technologist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Radiologic Technology'],
                ['step_role' => 'RadTech Intern', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS RadTech internship'], 'transition' => 'Pass Radiologic Technology licensure exam, radiation safety'],
                ['step_role' => 'Junior Radiologic Technologist', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['PRC RadTech license'], 'transition' => 'Operate X-ray/CT under supervision, QA checks'],
                ['step_role' => 'Radiologic Technologist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Modality proficiency'], 'transition' => 'Handle CT/MRI/Ultrasound (with training), patient safety'],
                ['step_role' => 'Senior Radiologic Technologist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'QA leadership'], 'transition' => 'Lead modality shift, train juniors, equipment QC'],
                ['step_role' => 'Imaging Supervisor', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership'], 'transition' => 'Manage imaging schedules, maintenance, accreditation'],
                ['step_role' => 'Imaging Manager / Chief RadTech', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Operations management'], 'transition' => 'Oversee imaging department, budget, quality and safety'],
            ],

            // 6. RESPIRATORY THERAPIST
            'Respiratory Therapist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Respiratory Therapy'],
                ['step_role' => 'RT Intern', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Respiratory Therapy internship'], 'transition' => 'Pass RT licensure exam, ACLS/BLS'],
                ['step_role' => 'Junior Respiratory Therapist', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['PRC RT license'], 'transition' => 'Manage basic oxygen therapy, nebulization, under supervision'],
                ['step_role' => 'Respiratory Therapist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Ventilator management basics'], 'transition' => 'Handle ventilator settings, ABG sampling, ICU support'],
                ['step_role' => 'Senior Respiratory Therapist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Critical care'], 'transition' => 'Lead RT shifts, train juniors, complex ventilator care'],
                ['step_role' => 'Respiratory Therapy Supervisor', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership'], 'transition' => 'Schedule RT staff, manage equipment, protocols'],
                ['step_role' => 'Respiratory Therapy Manager', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Department management'], 'transition' => 'Oversee RT department, quality, budgets'],
            ],

            // 7. OCCUPATIONAL THERAPIST
            'Occupational Therapist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma', 'STEM track'], 'transition' => 'Complete BS Occupational Therapy'],
                ['step_role' => 'OT Intern', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['OT internship'], 'transition' => 'Pass OT licensure exam, basic life support'],
                ['step_role' => 'Junior Occupational Therapist', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['PRC OT license'], 'transition' => 'Assist with treatment plans, documentation'],
                ['step_role' => 'Occupational Therapist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Assessment skills'], 'transition' => 'Lead sessions, adapt activities, coordinate with care team'],
                ['step_role' => 'Senior Occupational Therapist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Specialization (peds/rehab/geriatrics)'], 'transition' => 'Design programs, mentor juniors'],
                ['step_role' => 'OT Supervisor', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Oversee OT services, scheduling, quality'],
                ['step_role' => 'Director of Occupational Therapy', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Department leadership'], 'transition' => 'Lead OT department strategy, budgets, outcomes'],
            ],

            // 8. PUBLIC HEALTH OFFICER
            'Public Health Officer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete BS Public Health/Community Health or related'],
                ['step_role' => 'Public Health Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Public Health degree'], 'transition' => 'Field exposure, epidemiology basics'],
                ['step_role' => 'Public Health Associate', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Data collection', 'Community engagement'], 'transition' => 'Assist surveillance, health education'],
                ['step_role' => 'Public Health Officer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Program implementation'], 'transition' => 'Run local health programs, coordinate with LGUs/DOH'],
                ['step_role' => 'Senior Public Health Officer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Epidemiology/biostats'], 'transition' => 'Lead surveillance, outbreak response, analytics'],
                ['step_role' => 'Public Health Program Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Manage programs, budgets, donor coordination'],
                ['step_role' => 'Director of Public Health', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Policy and strategy'], 'transition' => 'Set public health strategy, inter-agency coordination'],
            ],
        ];
    }

    private function getLawProgressions()
    {
        return [
            // 1. LEGAL ASSISTANT / PARALEGAL
            'Legal Assistant / Paralegal' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete pre-law or legal management course'],
                ['step_role' => 'Legal Management Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Legal management/pre-law degree'], 'transition' => 'Intern at law office, learn legal research and drafting'],
                ['step_role' => 'Junior Paralegal', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Legal research skills', 'Document handling'], 'transition' => 'Prepare pleadings, filings, calendaring'],
                ['step_role' => 'Paralegal', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Case management'], 'transition' => 'Coordinate with clients/courts, draft basic motions'],
                ['step_role' => 'Senior Paralegal', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Specialized practice'], 'transition' => 'Lead paralegal team, complex filings, mentor juniors'],
                ['step_role' => 'Paralegal Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Manage paralegal operations, process standards'],
                ['step_role' => 'Legal Operations Manager', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Process and tech'], 'transition' => 'Oversee legal ops, tooling, vendors, compliance'],
            ],

            // 2. POLICE OFFICER
            'Police Officer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete criminology or related degree'],
                ['step_role' => 'Criminology Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['BS Criminology'], 'transition' => 'PNP recruitment, NAPOLCOM exams, physical training'],
                ['step_role' => 'Police Officer I', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['PNP training', 'NAPOLCOM eligibility'], 'transition' => 'Field assignments, patrol, basic investigations'],
                ['step_role' => 'Police Officer II / Investigator', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['Experience in patrol/investigation'], 'transition' => 'Handle cases, evidence management, community policing'],
                ['step_role' => 'Police Staff Sergeant / Senior Investigator', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Specialized training'], 'transition' => 'Lead squads, specialized units'],
                ['step_role' => 'Police Lieutenant / Captain', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership'], 'transition' => 'Command precinct sections, operational planning'],
                ['step_role' => 'Police Colonel / Provincial Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => 60, 'min_exp' => 10, 'max_exp' => 15,
                 'prereq' => ['10+ years experience', 'Command experience'], 'transition' => 'Lead provincial commands, strategy, coordination'],
                ['step_role' => 'Chief of Police / Regional Director', 'level' => 'Executive-Level', 'sequence' => 8, 'duration' => null, 'min_exp' => 15, 'max_exp' => null,
                 'prereq' => ['15+ years experience', 'Executive command'], 'transition' => 'Top police leadership for city/region'],
            ],

            // 3. COMPLIANCE OFFICER
            'Compliance Officer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete business/law/accounting degree'],
                ['step_role' => 'Compliance/Legal Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Relevant degree'], 'transition' => 'Intern in compliance/legal, learn regs for industry'],
                ['step_role' => 'Junior Compliance Analyst', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Policy reading', 'Report writing'], 'transition' => 'Assist audits, compliance monitoring, KYC'],
                ['step_role' => 'Compliance Officer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Control testing'], 'transition' => 'Own compliance reviews, train teams'],
                ['step_role' => 'Senior Compliance Officer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Risk assessment'], 'transition' => 'Design controls, manage regulatory submissions'],
                ['step_role' => 'Compliance Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership'], 'transition' => 'Lead compliance program, policy ownership'],
                ['step_role' => 'Chief Compliance Officer', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Executive reporting'], 'transition' => 'Own enterprise compliance strategy and governance'],
            ],

            // 4. COURT PERSONNEL
            'Court Personnel' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete legal management/political science or related'],
                ['step_role' => 'Legal/PolSci Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Bachelor degree'], 'transition' => 'Intern in court/clerical roles, learn rules of court'],
                ['step_role' => 'Court Clerk / Staff', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Clerical skills', 'Court procedures'], 'transition' => 'Manage dockets, records, hearing schedules'],
                ['step_role' => 'Court Legal Researcher', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['Legal research', 'Drafting'], 'transition' => 'Assist judges with research, draft summaries'],
                ['step_role' => 'Senior Court Staff / Division Officer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Process oversight'], 'transition' => 'Oversee docketing, staff supervision'],
                ['step_role' => 'Clerk of Court / Division Head', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership'], 'transition' => 'Manage court administration, compliance, reporting'],
                ['step_role' => 'Court Administrator', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Judiciary operations'], 'transition' => 'Oversee court system administration and policy'],
            ],

            // 5. IMMIGRATION OFFICER
            'Immigration Officer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete political science, law, or related degree'],
                ['step_role' => 'Public Admin/PolSci Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Bachelor degree'], 'transition' => 'Civil service eligibility, apply to BI, security/background checks'],
                ['step_role' => 'Immigration Officer I', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Civil service eligible', 'Training'], 'transition' => 'Process arrivals/departures, document verification'],
                ['step_role' => 'Immigration Officer II', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Law/policy knowledge'], 'transition' => 'Handle complex cases, investigations'],
                ['step_role' => 'Senior Immigration Officer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Case leadership'], 'transition' => 'Lead airport/port teams, high-risk cases'],
                ['step_role' => 'Immigration Supervisor / Division Chief', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership'], 'transition' => 'Manage operations, policy implementation, coordination'],
                ['step_role' => 'Immigration Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic leadership'], 'transition' => 'Oversee immigration strategy, international coordination'],
            ],

            // 6. CUSTOMS OFFICER
            'Customs Officer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Complete customs administration, law, or business degree'],
                ['step_role' => 'Customs/Admin Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Bachelor degree'], 'transition' => 'Civil service/BOC hiring, training on tariffs/import-export'],
                ['step_role' => 'Customs Officer I', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Customs training'], 'transition' => 'Inspection, documentation, tariff classification'],
                ['step_role' => 'Customs Officer II', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years experience', 'Import/export rules'], 'transition' => 'Lead inspections, enforcement coordination'],
                ['step_role' => 'Senior Customs Officer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Anti-smuggling operations'], 'transition' => 'Manage teams, risk profiling, audits'],
                ['step_role' => 'Customs Division Chief', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership'], 'transition' => 'Oversee customs district/division operations'],
                ['step_role' => 'Customs Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Strategic leadership'], 'transition' => 'Lead customs strategy, policy, and international coordination'],
            ],
        ];
    }

    private function getLiberalArtsProgressions()
    {
        return [
            // 1. PSYCHOLOGIST
            'Psychologist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Earn BS/BA Psychology'],
                ['step_role' => 'Psychology Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Psychology degree'], 'transition' => 'RA 10029 requirements, supervised practicum'],
                ['step_role' => 'Psychometrician', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 24, 'min_exp' => 0, 'max_exp' => 2,
                 'prereq' => ['Psychometrician license'], 'transition' => 'Testing, assessment, supervised counseling support'],
                ['step_role' => 'Registered Psychologist', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 36, 'min_exp' => 2, 'max_exp' => 4,
                 'prereq' => ['Board exam / RPm-RPsy path'], 'transition' => 'Conduct therapy under supervision, assessments'],
                ['step_role' => 'Senior Psychologist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 48, 'min_exp' => 4, 'max_exp' => 7,
                 'prereq' => ['4+ years practice', 'Specialization (clinical/industrial)'], 'transition' => 'Lead cases, supervise juniors'],
                ['step_role' => 'Principal Psychologist / Chief', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 60, 'min_exp' => 7, 'max_exp' => 12,
                 'prereq' => ['7+ years practice', 'Supervision experience'], 'transition' => 'Head psychology services, program design'],
                ['step_role' => 'Director of Behavioral Health', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 12, 'max_exp' => null,
                 'prereq' => ['12+ years practice', 'Leadership'], 'transition' => 'Oversee behavioral health strategy and policy'],
            ],

            // 2. SOCIOLOGY/ANTHROPOLOGY RESEARCHER
            'Sociology/Anthropology Researcher' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Earn BA Sociology/Anthropology'],
                ['step_role' => 'SocSci Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Sociology/Anthro degree'], 'transition' => 'Intern on field research, surveys'],
                ['step_role' => 'Research Assistant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 18, 'min_exp' => 0, 'max_exp' => 2,
                 'prereq' => ['Qual/quant methods basics'], 'transition' => 'Data collection, coding, basic analysis'],
                ['step_role' => 'Research Associate', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 2, 'max_exp' => 4,
                 'prereq' => ['2+ years fieldwork'], 'transition' => 'Lead small studies, author sections'],
                ['step_role' => 'Senior Researcher', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 4, 'max_exp' => 7,
                 'prereq' => ['4+ years experience', 'Study design'], 'transition' => 'PI on projects, stakeholder presentations'],
                ['step_role' => 'Research Program Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 7, 'max_exp' => 12,
                 'prereq' => ['7+ years research', 'Grant management'], 'transition' => 'Manage portfolio, budgets, teams'],
                ['step_role' => 'Director of Research', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 12, 'max_exp' => null,
                 'prereq' => ['12+ years experience', 'Strategic leadership'], 'transition' => 'Shape research agenda and partnerships'],
            ],

            // 3. GUIDANCE COUNSELOR
            'Guidance Counselor' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Earn BA/BS Psychology/Education'],
                ['step_role' => 'Counseling Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Degree in counseling-related'], 'transition' => 'Complete practicum, guidance coursework'],
                ['step_role' => 'Guidance Intern', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Practicum hours'], 'transition' => 'Assist counseling sessions, student records'],
                ['step_role' => 'Licensed Guidance Counselor', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['Board exam for Guidance'], 'transition' => 'Handle caseload, testing, parent engagement'],
                ['step_role' => 'Senior Guidance Counselor', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years counseling'], 'transition' => 'Lead programs, crisis intervention, mentor juniors'],
                ['step_role' => 'Guidance Coordinator', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Program design'], 'transition' => 'Oversee school guidance programs, policies'],
                ['step_role' => 'Director of Student Services', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Leadership'], 'transition' => 'Lead student services, mental health strategy'],
            ],

            // 4. WRITER/EDITOR
            'Writer/Editor' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Earn BA Communication/Journalism/Literature'],
                ['step_role' => 'Communication Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Comm/Journalism degree'], 'transition' => 'Campus publication, portfolio building'],
                ['step_role' => 'Junior Writer', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Writing portfolio'], 'transition' => 'Produce articles, copy, follow style guides'],
                ['step_role' => 'Writer / Reporter', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['Beat experience', 'Research/fact-check'], 'transition' => 'Own beats, longer form pieces'],
                ['step_role' => 'Senior Writer / Editor', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years writing', 'Editing skills'], 'transition' => 'Edit others, guide tone, mentor juniors'],
                ['step_role' => 'Managing Editor', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Plan content calendar, workflows, quality'],
                ['step_role' => 'Editor-in-Chief / Content Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Brand leadership'], 'transition' => 'Own editorial strategy and standards'],
            ],

            // 5. PUBLIC RELATIONS OFFICER
            'Public Relations Officer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Earn BA Communication/PR'],
                ['step_role' => 'PR/Communication Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Communication degree'], 'transition' => 'Intern in PR/agency, build media list'],
                ['step_role' => 'PR Assistant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Writing and coordination'], 'transition' => 'Draft releases, track coverage, events'],
                ['step_role' => 'PR Officer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years agency/in-house'], 'transition' => 'Pitch media, manage accounts, crisis basics'],
                ['step_role' => 'Senior PR Officer', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years PR', 'Campaign planning'], 'transition' => 'Lead campaigns, coach spokespeople'],
                ['step_role' => 'PR Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Own PR strategy, budgets, agency mgmt'],
                ['step_role' => 'Head of Communications', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Executive comms'], 'transition' => 'Lead corporate communications and reputation'],
            ],

            // 6. ECONOMIST
            'Economist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Earn BS Economics/Statistics'],
                ['step_role' => 'Economics Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Economics degree'], 'transition' => 'Intern with research/finance agency'],
                ['step_role' => 'Junior Economic Analyst', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Stats/econometrics basics'], 'transition' => 'Data cleaning, basic models, reporting'],
                ['step_role' => 'Economic Analyst', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years analysis', 'Regression/econ modeling'], 'transition' => 'Publish analyses, policy notes'],
                ['step_role' => 'Senior Economist', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years economics', 'Policy/market expertise'], 'transition' => 'Lead studies, supervise analysts'],
                ['step_role' => 'Principal Economist / Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Direct research agenda, stakeholder briefings'],
                ['step_role' => 'Chief Economist / Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years experience', 'Executive influence'], 'transition' => 'Set economic strategy and advise leadership'],
            ],

            // 7. SOCIAL WORKER
            'Social Worker' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Earn BS Social Work'],
                ['step_role' => 'Social Work Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Social Work degree'], 'transition' => 'Board exam, supervised practicum'],
                ['step_role' => 'Registered Social Worker', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 18, 'min_exp' => 0, 'max_exp' => 2,
                 'prereq' => ['RSW license'], 'transition' => 'Casework, community visits, documentation'],
                ['step_role' => 'Senior Social Worker', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 2, 'max_exp' => 4,
                 'prereq' => ['2+ years practice'], 'transition' => 'Lead cases, partner coordination'],
                ['step_role' => 'Supervising Social Worker', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 4, 'max_exp' => 7,
                 'prereq' => ['4+ years experience', 'Program management'], 'transition' => 'Oversee teams, quality assurance'],
                ['step_role' => 'Social Welfare Officer IV / Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 7, 'max_exp' => 12,
                 'prereq' => ['7+ years service', 'Leadership'], 'transition' => 'Manage programs, budget, inter-agency work'],
                ['step_role' => 'Director for Social Welfare', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 12, 'max_exp' => null,
                 'prereq' => ['12+ years experience', 'Policy leadership'], 'transition' => 'Lead welfare strategy and nationwide programs'],
            ],

            // 8. HISTORIAN/ARCHIVIST
            'Historian/Archivist' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Earn BA History/Library Science'],
                ['step_role' => 'History/Library Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['History or LIS degree'], 'transition' => 'Intern at archives/museums, preservation basics'],
                ['step_role' => 'Archive Assistant / Junior Historian', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 18, 'min_exp' => 0, 'max_exp' => 2,
                 'prereq' => ['Cataloging basics'], 'transition' => 'Inventory, digitization, research assistance'],
                ['step_role' => 'Archivist / Researcher', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 2, 'max_exp' => 4,
                 'prereq' => ['2+ years experience', 'Preservation methods'], 'transition' => 'Curate collections, write findings'],
                ['step_role' => 'Senior Archivist / Historian', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 4, 'max_exp' => 7,
                 'prereq' => ['4+ years experience', 'Curation leadership'], 'transition' => 'Lead exhibits, research projects'],
                ['step_role' => 'Collections Manager / Chief Archivist', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 7, 'max_exp' => 12,
                 'prereq' => ['7+ years experience', 'Team leadership'], 'transition' => 'Manage collections strategy, preservation policy'],
                ['step_role' => 'Museum/Archives Director', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 12, 'max_exp' => null,
                 'prereq' => ['12+ years experience', 'Executive stewardship'], 'transition' => 'Lead cultural institution and partnerships'],
            ],
        ];
    }

    private function getTourismProgressions()
    {
        return [
            // 1. HOTEL FRONT DESK OFFICER
            'Hotel Front Desk Officer' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Take BS Hotel and Restaurant Management/Tourism'],
                ['step_role' => 'Hospitality Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Hospitality/Tourism degree'], 'transition' => 'Intern in hotel operations, FO basics'],
                ['step_role' => 'Front Desk Associate', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Customer service', 'Reservation systems'], 'transition' => 'Handle check-in/out, guest queries'],
                ['step_role' => 'Front Desk Officer', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years front office'], 'transition' => 'Lead shifts, resolve escalations'],
                ['step_role' => 'Front Office Supervisor', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years FO'], 'transition' => 'Oversee team, training, service recovery'],
                ['step_role' => 'Front Office Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 10,
                 'prereq' => ['6+ years experience', 'Leadership'], 'transition' => 'Manage FO operations, budgets, KPIs'],
                ['step_role' => 'Rooms Division Manager', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 10, 'max_exp' => null,
                 'prereq' => ['10+ years hospitality'], 'transition' => 'Oversee rooms and guest services strategy'],
            ],

            // 2. HOUSEKEEPING SUPERVISOR
            'Housekeeping Supervisor' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Hospitality/HRM course'],
                ['step_role' => 'Hospitality Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Hospitality degree'], 'transition' => 'Intern in housekeeping operations'],
                ['step_role' => 'Room Attendant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Cleaning standards'], 'transition' => 'Room cleaning, inventory basics'],
                ['step_role' => 'Senior Room Attendant', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 18, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years housekeeping'], 'transition' => 'Inspect rooms, mentor attendants'],
                ['step_role' => 'Housekeeping Supervisor', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 30, 'min_exp' => 3, 'max_exp' => 5,
                 'prereq' => ['3+ years housekeeping', 'Leadership'], 'transition' => 'Assign schedules, quality audits'],
                ['step_role' => 'Assistant Executive Housekeeper', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 42, 'min_exp' => 5, 'max_exp' => 8,
                 'prereq' => ['5+ years experience', 'Cost control'], 'transition' => 'Manage procurement, training, standards'],
                ['step_role' => 'Executive Housekeeper', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 8, 'max_exp' => null,
                 'prereq' => ['8+ years experience', 'Leadership'], 'transition' => 'Lead housekeeping strategy and budgets'],
            ],

            // 3. CHEF / CULINARY PROFESSIONAL
            'Chef / Culinary Professional' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Take culinary arts/HRM'],
                ['step_role' => 'Culinary Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Culinary/HRM certificate'], 'transition' => 'Kitchen internship/apprenticeship'],
                ['step_role' => 'Commis Chef', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 18, 'min_exp' => 0, 'max_exp' => 2,
                 'prereq' => ['Knife skills', 'Food safety'], 'transition' => 'Station prep, follow recipes'],
                ['step_role' => 'Demi Chef de Partie', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 2, 'max_exp' => 4,
                 'prereq' => ['2+ years kitchen'], 'transition' => 'Run station segments, specials prep'],
                ['step_role' => 'Chef de Partie', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 4, 'max_exp' => 6,
                 'prereq' => ['4+ years kitchen'], 'transition' => 'Own station, train juniors, quality control'],
                ['step_role' => 'Sous Chef', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 9,
                 'prereq' => ['6+ years experience', 'Menu execution'], 'transition' => 'Manage kitchen ops, ordering, staff'],
                ['step_role' => 'Executive Chef', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 9, 'max_exp' => null,
                 'prereq' => ['9+ years experience', 'Leadership'], 'transition' => 'Lead menus, culinary strategy, branding'],
            ],

            // 4. TOUR GUIDE
            'Tour Guide' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Take tourism/HRM course'],
                ['step_role' => 'Tourism Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Tourism degree'], 'transition' => 'DOT accreditation, local guiding workshop'],
                ['step_role' => 'Apprentice Tour Guide', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Accreditation'], 'transition' => 'Assist tours, learn scripts, safety'],
                ['step_role' => 'Tour Guide', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years guiding'], 'transition' => 'Lead tours, manage groups, customer care'],
                ['step_role' => 'Senior Tour Guide', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years guiding'], 'transition' => 'Design itineraries, train junior guides'],
                ['step_role' => 'Tour Operations Supervisor', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 9,
                 'prereq' => ['6+ years experience', 'Operations'], 'transition' => 'Coordinate suppliers, scheduling, quality'],
                ['step_role' => 'Tour Operations Manager', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 9, 'max_exp' => null,
                 'prereq' => ['9+ years experience', 'Leadership'], 'transition' => 'Own tour business strategy and partnerships'],
            ],

            // 5. TRAVEL AGENT / CONSULTANT
            'Travel Agent / Consultant' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Tourism/business course'],
                ['step_role' => 'Tourism/Business Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Tourism or business degree'], 'transition' => 'GDS training (Amadeus/Sabre)'],
                ['step_role' => 'Travel Consultant', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['GDS basics', 'Customer service'], 'transition' => 'Book flights/hotels, itineraries'],
                ['step_role' => 'Senior Travel Consultant', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years booking'], 'transition' => 'Handle complex itineraries, visas, corporate accounts'],
                ['step_role' => 'Travel Team Lead', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Sales leadership'], 'transition' => 'Manage team KPIs, escalations'],
                ['step_role' => 'Travel Operations Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 9,
                 'prereq' => ['6+ years experience', 'Vendor management'], 'transition' => 'Oversee ops, supplier contracts'],
                ['step_role' => 'Director of Travel Services', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 9, 'max_exp' => null,
                 'prereq' => ['9+ years experience', 'Strategy'], 'transition' => 'Lead travel business strategy and partnerships'],
            ],

            // 6. EVENT PLANNER
            'Event Planner' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Tourism/marketing/communications course'],
                ['step_role' => 'Event Management Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Relevant degree'], 'transition' => 'Volunteer/intern on events'],
                ['step_role' => 'Event Coordinator', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Coordination skills'], 'transition' => 'Logistics, vendor follow-ups, on-site support'],
                ['step_role' => 'Event Planner', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 24, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years events'], 'transition' => 'Own small events, budgeting, client comms'],
                ['step_role' => 'Senior Event Planner', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 36, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years events', 'Vendor network'], 'transition' => 'Lead major events, creative concepts'],
                ['step_role' => 'Events Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 48, 'min_exp' => 6, 'max_exp' => 9,
                 'prereq' => ['6+ years experience', 'Team leadership'], 'transition' => 'Oversee portfolio, P&L, teams'],
                ['step_role' => 'Head of Events', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 9, 'max_exp' => null,
                 'prereq' => ['9+ years experience', 'Strategy'], 'transition' => 'Set events strategy, partnerships, branding'],
            ],

            // 7. RESTAURANT MANAGER
            'Restaurant Manager' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'HRM/Business course'],
                ['step_role' => 'HRM/Business Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Hospitality or business degree'], 'transition' => 'Intern in F&B service/kitchen'],
                ['step_role' => 'Service Crew / Server', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['Customer service', 'Basic F&B'], 'transition' => 'Serve guests, POS, cleanliness'],
                ['step_role' => 'Shift Supervisor', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 18, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 years F&B'], 'transition' => 'Handle shift ops, cash, staff scheduling'],
                ['step_role' => 'Assistant Restaurant Manager', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 30, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years experience', 'Inventory control'], 'transition' => 'Manage ordering, training, customer recovery'],
                ['step_role' => 'Restaurant Manager', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 42, 'min_exp' => 6, 'max_exp' => 9,
                 'prereq' => ['6+ years F&B', 'Leadership'], 'transition' => 'Own store P&L, operations, marketing'],
                ['step_role' => 'Area Manager / Operations Head', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 9, 'max_exp' => null,
                 'prereq' => ['9+ years experience', 'Multi-unit leadership'], 'transition' => 'Oversee multiple stores and strategy'],
            ],

            // 8. CRUISE SHIP CREW / MARITIME HOSPITALITY
            'Cruise Ship Crew / Maritime Hospitality' => [
                ['step_role' => 'Student', 'level' => 'Student', 'sequence' => 1, 'duration' => 48, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['High school diploma'], 'transition' => 'Hospitality/maritime basics, STCW training'],
                ['step_role' => 'Hospitality/Maritime Graduate', 'level' => 'Graduate', 'sequence' => 2, 'duration' => 6, 'min_exp' => 0, 'max_exp' => 0,
                 'prereq' => ['Hospitality or maritime course'], 'transition' => 'Obtain seafarer documents, basic safety'],
                ['step_role' => 'Junior Crew / Utility', 'level' => 'Entry-Level', 'sequence' => 3, 'duration' => 12, 'min_exp' => 0, 'max_exp' => 1,
                 'prereq' => ['STCW', 'Medically fit'], 'transition' => 'Assist housekeeping/service onboard'],
                ['step_role' => 'Cabin Steward / F&B Assistant', 'level' => 'Junior-Level', 'sequence' => 4, 'duration' => 18, 'min_exp' => 1, 'max_exp' => 3,
                 'prereq' => ['1-2 contracts experience'], 'transition' => 'Own cabin/service sections, guest relations'],
                ['step_role' => 'Senior Steward / Head Waiter', 'level' => 'Mid-Level', 'sequence' => 5, 'duration' => 30, 'min_exp' => 3, 'max_exp' => 6,
                 'prereq' => ['3+ years ship experience'], 'transition' => 'Lead teams, training, quality checks'],
                ['step_role' => 'Department Supervisor (Cabin/F&B)', 'level' => 'Senior-Level', 'sequence' => 6, 'duration' => 42, 'min_exp' => 6, 'max_exp' => 9,
                 'prereq' => ['6+ years maritime hospitality', 'Leadership'], 'transition' => 'Oversee department operations and compliance'],
                ['step_role' => 'Hotel Director / Ship Operations Manager', 'level' => 'Leadership-Level', 'sequence' => 7, 'duration' => null, 'min_exp' => 9, 'max_exp' => null,
                 'prereq' => ['9+ years cruise experience', 'Multi-department leadership'], 'transition' => 'Lead shipboard hotel operations and guest experience'],
            ],
        ];
    }
}
