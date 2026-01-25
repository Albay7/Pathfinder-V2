<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CareerLevel;
use Illuminate\Support\Facades\DB;

class CareerLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Bootstrap data - will be replaced by web scraping
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('career_levels')->truncate();

        $dataVersion = now()->format('Y-m');
        $bootstrapData = [
            // CYBERSECURITY ROLES
            ['role' => 'Junior Security Analyst', 'level' => 'Entry-Level', 'desc' => 'Monitor security systems, investigate alerts, and assist in incident response under supervision. Learn security tools and best practices.', 'sal_min' => 22000, 'sal_max' => 35000, 'skills' => ['SIEM tools', 'Log analysis', 'Network fundamentals', 'Security awareness'], 'resp' => ['Monitor security dashboards', 'Document security incidents', 'Assist in vulnerability scans', 'Follow security procedures']],
            ['role' => 'Security Analyst', 'level' => 'Junior-Level', 'desc' => 'Independently analyze security events, conduct investigations, and respond to incidents. Implement security controls and maintain documentation.', 'sal_min' => 35000, 'sal_max' => 55000, 'skills' => ['Incident response', 'Threat analysis', 'Security frameworks', 'SIEM administration'], 'resp' => ['Lead incident investigations', 'Configure security tools', 'Conduct risk assessments', 'Create security reports']],
            ['role' => 'Senior Security Analyst', 'level' => 'Mid-Level', 'desc' => 'Lead complex security investigations, mentor junior analysts, and implement advanced security measures. Develop security policies and procedures.', 'sal_min' => 55000, 'sal_max' => 90000, 'skills' => ['Advanced threat hunting', 'Forensics', 'Security architecture', 'CISSP certified'], 'resp' => ['Design security solutions', 'Mentor team members', 'Lead incident response', 'Develop security strategies']],
            ['role' => 'Security Architect', 'level' => 'Senior-Level', 'desc' => 'Design enterprise security architecture, set security standards, and guide strategic security initiatives. Interface with executives on security matters.', 'sal_min' => 90000, 'sal_max' => 150000, 'skills' => ['Enterprise architecture', 'Cloud security', 'Compliance frameworks', 'Risk management'], 'resp' => ['Define security architecture', 'Lead security projects', 'Ensure compliance', 'Guide security team']],

            // SOFTWARE DEVELOPMENT ROLES
            ['role' => 'Junior Software Developer', 'level' => 'Entry-Level', 'desc' => 'Write clean code, fix bugs, and implement features under guidance. Learn software development best practices and company standards.', 'sal_min' => 20000, 'sal_max' => 30000, 'skills' => ['Programming basics', 'Version control', 'Debugging', 'Code review'], 'resp' => ['Write code following standards', 'Fix minor bugs', 'Participate in code reviews', 'Learn development tools']],
            ['role' => 'Software Developer', 'level' => 'Junior-Level', 'desc' => 'Independently develop features, write tests, and contribute to technical decisions. Handle customer requirements and deployment tasks.', 'sal_min' => 30000, 'sal_max' => 50000, 'skills' => ['Full-stack development', 'Testing frameworks', 'API design', 'Database optimization'], 'resp' => ['Develop complete features', 'Write technical documentation', 'Troubleshoot production issues', 'Mentor junior developers']],
            ['role' => 'Senior Software Developer', 'level' => 'Mid-Level', 'desc' => 'Lead feature development, design scalable systems, and mentor team members. Make architectural decisions and optimize application performance.', 'sal_min' => 50000, 'sal_max' => 90000, 'skills' => ['System design', 'Performance optimization', 'Microservices', 'Cloud platforms'], 'resp' => ['Lead technical projects', 'Design architecture', 'Code review and mentoring', 'Drive technical decisions']],
            ['role' => 'Staff Engineer / Tech Lead', 'level' => 'Senior-Level', 'desc' => 'Define technical direction, lead cross-functional teams, and drive innovation. Establish engineering standards and best practices across teams.', 'sal_min' => 90000, 'sal_max' => 150000, 'skills' => ['Technical leadership', 'Strategic planning', 'Team mentoring', 'Emerging technologies'], 'resp' => ['Set technical strategy', 'Lead multiple teams', 'Drive innovation', 'Establish best practices']],

            // NURSING ROLES
            ['role' => 'Staff Nurse', 'level' => 'Entry-Level', 'desc' => 'Provide direct patient care, administer medications, and monitor patient conditions. Work under supervision of senior nurses and doctors.', 'sal_min' => 18000, 'sal_max' => 28000, 'skills' => ['Patient care', 'Medical procedures', 'Health assessment', 'Documentation'], 'resp' => ['Administer medications', 'Monitor vital signs', 'Assist in procedures', 'Maintain patient records']],
            ['role' => 'Senior Staff Nurse', 'level' => 'Mid-Level', 'desc' => 'Provide specialized nursing care, mentor junior nurses, and handle complex patient cases. May serve as charge nurse during shifts.', 'sal_min' => 28000, 'sal_max' => 45000, 'skills' => ['Specialty certification', 'Critical care', 'Patient education', 'Team coordination'], 'resp' => ['Lead patient care teams', 'Handle complex cases', 'Mentor new nurses', 'Quality improvement']],
            ['role' => 'Nurse Supervisor / Charge Nurse', 'level' => 'Senior-Level', 'desc' => 'Supervise nursing staff, manage unit operations, and ensure quality patient care. Handle administrative duties and staff scheduling.', 'sal_min' => 45000, 'sal_max' => 70000, 'skills' => ['Leadership', 'Staff management', 'Quality assurance', 'Resource allocation'], 'resp' => ['Supervise nursing staff', 'Manage unit operations', 'Ensure care standards', 'Handle escalations']],

            // DATA SCIENCE ROLES
            ['role' => 'Junior Data Analyst', 'level' => 'Entry-Level', 'desc' => 'Clean and analyze data, create visualizations, and generate reports. Learn statistical methods and data analysis tools.', 'sal_min' => 22000, 'sal_max' => 35000, 'skills' => ['SQL', 'Excel', 'Data visualization', 'Basic statistics'], 'resp' => ['Clean and prepare data', 'Create dashboards', 'Generate reports', 'Support data projects']],
            ['role' => 'Data Scientist', 'level' => 'Junior-Level', 'desc' => 'Build predictive models, conduct statistical analysis, and develop data-driven solutions. Apply machine learning to business problems.', 'sal_min' => 35000, 'sal_max' => 60000, 'skills' => ['Python/R', 'Machine learning', 'Statistical modeling', 'Data engineering'], 'resp' => ['Build ML models', 'Conduct experiments', 'Present insights', 'Deploy solutions']],
            ['role' => 'Senior Data Scientist', 'level' => 'Mid-Level', 'desc' => 'Lead data science projects, develop advanced models, and mentor team members. Define data strategy and collaborate with stakeholders.', 'sal_min' => 60000, 'sal_max' => 100000, 'skills' => ['Deep learning', 'Big data', 'MLOps', 'Business strategy'], 'resp' => ['Lead DS projects', 'Architect ML solutions', 'Mentor data scientists', 'Drive data strategy']],

            // WEB DEVELOPMENT ROLES
            ['role' => 'Junior Web Developer', 'level' => 'Entry-Level', 'desc' => 'Build responsive websites, implement UI/UX designs, and fix front-end issues. Learn modern web frameworks and best practices.', 'sal_min' => 18000, 'sal_max' => 28000, 'skills' => ['HTML/CSS', 'JavaScript', 'Responsive design', 'Git'], 'resp' => ['Code web pages', 'Fix UI bugs', 'Implement designs', 'Learn frameworks']],
            ['role' => 'Web Developer', 'level' => 'Junior-Level', 'desc' => 'Develop full-stack web applications, optimize performance, and maintain existing websites. Handle both front-end and back-end tasks.', 'sal_min' => 28000, 'sal_max' => 48000, 'skills' => ['React/Vue', 'Node.js/PHP', 'Databases', 'APIs'], 'resp' => ['Build web applications', 'Optimize performance', 'Integrate APIs', 'Deploy applications']],
            ['role' => 'Senior Web Developer', 'level' => 'Mid-Level', 'desc' => 'Lead web development projects, design technical architecture, and mentor junior developers. Set coding standards and best practices.', 'sal_min' => 48000, 'sal_max' => 85000, 'skills' => ['Advanced frameworks', 'Architecture design', 'DevOps', 'Performance tuning'], 'resp' => ['Lead web projects', 'Design architecture', 'Code reviews', 'Establish standards']],
        ];

        foreach ($bootstrapData as $data) {
            CareerLevel::create([
                'role_name' => $data['role'],
                'level' => $data['level'],
                'description' => $data['desc'],
                'salary_min' => $data['sal_min'],
                'salary_max' => $data['sal_max'],
                'salary_currency' => 'PHP',
                'responsibilities' => $data['resp'],
                'required_skills' => $data['skills'],
                'data_version' => $dataVersion,
                'is_current' => true,
                'scraped_at' => now(),
                'data_source' => 'Bootstrap Data',
            ]);
        }

        $this->command->info('✅ Career level bootstrap data seeded successfully!');
        $this->command->info('   - 20 role-level combinations created');
        $this->command->info('   - Data marked as current (version: ' . $dataVersion . ')');
        $this->command->warn('   ⚠️  This is bootstrap data. Run php artisan career:scrape-market-data to get real market data.');
    }
}
