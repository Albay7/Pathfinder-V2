<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillResourceSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // Python
            ['job_category'=>'it_cs','skill_key'=>'python','skill_display_name'=>'Python','resource_label'=>'Python for Beginners – freeCodeCamp (4h)','url'=>'https://www.youtube.com/watch?v=rfscVS0vtbw','description'=>'Hands-on Python fundamentals with exercises','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>240,'tags'=>json_encode(['python','fundamentals'])],
            ['job_category'=>'it_cs','skill_key'=>'python','skill_display_name'=>'Python','resource_label'=>'Automate with Python – Corey Schafer playlist','url'=>'https://www.youtube.com/playlist?list=PL-osiE80TeTtoQCKZ03TU5fNfx2UY6U4p','description'=>'Practical automation and scripting','platform'=>'youtube','level'=>'beginner','is_playlist'=>true,'duration_minutes'=>null,'tags'=>json_encode(['python','automation'])],

            // JavaScript
            ['job_category'=>'it_cs','skill_key'=>'javascript','skill_display_name'=>'JavaScript','resource_label'=>'JavaScript Full Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=PkZNo7MFNFg','description'=>'Complete beginner to intermediate JS','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>195,'tags'=>json_encode(['javascript'])],
            ['job_category'=>'it_cs','skill_key'=>'javascript','skill_display_name'=>'JavaScript','resource_label'=>'Modern JS DOM & APIs – Web Dev Simplified','url'=>'https://www.youtube.com/playlist?list=PLZlA0Gpn_vH9y6wJcc8y__8FerD5Zu-1s','description'=>'Real-world DOM, fetch, and patterns','platform'=>'youtube','level'=>'beginner','is_playlist'=>true,'duration_minutes'=>null,'tags'=>json_encode(['javascript','dom'])],

            // Java
            ['job_category'=>'it_cs','skill_key'=>'java','skill_display_name'=>'Java','resource_label'=>'Java Tutorial for Beginners – Mosh','url'=>'https://www.youtube.com/watch?v=eIrMbAQSU34','description'=>'Core Java syntax, OOP, and tooling','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>275,'tags'=>json_encode(['java'])],
            ['job_category'=>'it_cs','skill_key'=>'java','skill_display_name'=>'Java','resource_label'=>'Java OOP & Collections – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=grEKMHGYyns','description'=>'Deepen fundamentals with exercises','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>240,'tags'=>json_encode(['java','oop'])],

            // React
            ['job_category'=>'it_cs','skill_key'=>'react','skill_display_name'=>'React','resource_label'=>'React Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=bMknfKXIFA8','description'=>'Hooks, state, effects, and patterns','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>480,'tags'=>json_encode(['react'])],
            ['job_category'=>'it_cs','skill_key'=>'react','skill_display_name'=>'React','resource_label'=>'React Project Tutorial – Traversy','url'=>'https://www.youtube.com/watch?v=w7ejDZ8SWv8','description'=>'Practical React app build','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>120,'tags'=>json_encode(['react','project'])],

            // Node.js
            ['job_category'=>'it_cs','skill_key'=>'node','skill_display_name'=>'Node.js','resource_label'=>'Node & Express – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=Oe421EPjeBE','description'=>'APIs, routing, MVC, and REST','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>250,'tags'=>json_encode(['node','express'])],

            // SQL / Databases
            ['job_category'=>'it_cs','skill_key'=>'sql','skill_display_name'=>'SQL','resource_label'=>'SQL Tutorial – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=HXV3zeQKqGY','description'=>'Queries, joins, and aggregations','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>260,'tags'=>json_encode(['sql'])],
            ['job_category'=>'it_cs','skill_key'=>'mysql','skill_display_name'=>'MySQL','resource_label'=>'MySQL Full Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=7S_tz1z_5bA','description'=>'Install, schema, CRUD, and joins','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>260,'tags'=>json_encode(['mysql'])],
            ['job_category'=>'it_cs','skill_key'=>'postgresql','skill_display_name'=>'PostgreSQL','resource_label'=>'PostgreSQL Tutorial – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=qw--VYLpxG4','description'=>'SQL with Postgres from scratch','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>180,'tags'=>json_encode(['postgresql'])],
            ['job_category'=>'it_cs','skill_key'=>'mongodb','skill_display_name'=>'MongoDB','resource_label'=>'MongoDB Crash Course – Traversy','url'=>'https://www.youtube.com/watch?v=-56x56UppqQ','description'=>'CRUD, models, and drivers','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>75,'tags'=>json_encode(['mongodb'])],

            // DevOps & Cloud
            ['job_category'=>'it_cs','skill_key'=>'docker','skill_display_name'=>'Docker','resource_label'=>'Docker Full Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=9zUHg7xjIqQ','description'=>'Images, containers, and compose','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>240,'tags'=>json_encode(['docker'])],
            ['job_category'=>'it_cs','skill_key'=>'kubernetes','skill_display_name'=>'Kubernetes','resource_label'=>'Kubernetes Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=X48VuDVv0do','description'=>'K8s basics, deployments, services','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>480,'tags'=>json_encode(['kubernetes','k8s'])],
            ['job_category'=>'it_cs','skill_key'=>'aws','skill_display_name'=>'AWS','resource_label'=>'AWS Cloud Practitioner – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=SOTamWNgDKc','description'=>'Core AWS services and exam prep','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>540,'tags'=>json_encode(['aws'])],
            ['job_category'=>'it_cs','skill_key'=>'azure','skill_display_name'=>'Azure','resource_label'=>'Azure Fundamentals AZ-900 – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=NKEFWyqJ5XA','description'=>'Azure basics for beginners','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>530,'tags'=>json_encode(['azure'])],

            // Tools
            ['job_category'=>'it_cs','skill_key'=>'git','skill_display_name'=>'Git & GitHub','resource_label'=>'Git & GitHub – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=RGOj5yH7evk','description'=>'Version control fundamentals','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>200,'tags'=>json_encode(['git'])],

            // IT Support & Networking
            ['job_category'=>'it_cs','skill_key'=>'linux','skill_display_name'=>'Linux Administration','resource_label'=>'Linux for Beginners – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=ROjZy1WbCIA','description'=>'Command line, filesystem, and basics','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>305,'tags'=>json_encode(['linux'])],
            ['job_category'=>'it_cs','skill_key'=>'windows-support','skill_display_name'=>'Windows Support','resource_label'=>'CompTIA A+ (Core 1) – Professor Messer playlist','url'=>'https://www.youtube.com/playlist?list=PLG49S3nxzAnlGHY8ObL8DiyP3AIu9nEgs','description'=>'Hardware, OS, and troubleshooting','platform'=>'youtube','level'=>'beginner','is_playlist'=>true,'duration_minutes'=>null,'tags'=>json_encode(['windows','support'])],
            ['job_category'=>'it_cs','skill_key'=>'networking','skill_display_name'=>'Networking & TCP/IP','resource_label'=>'Networking Fundamentals – NetworkChuck','url'=>'https://www.youtube.com/watch?v=qiQR5rTSshw','description'=>'TCP/IP, subnets, VLANs, and routing','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>90,'tags'=>json_encode(['networking'])],

            // AI & Data
            ['job_category'=>'it_cs','skill_key'=>'machine-learning','skill_display_name'=>'Machine Learning','resource_label'=>'Machine Learning Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=i_LwzRVP7bg','description'=>'ML fundamentals and models','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>600,'tags'=>json_encode(['ml'])],
            ['job_category'=>'it_cs','skill_key'=>'data-science','skill_display_name'=>'Data Science','resource_label'=>'Data Analysis with Python – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=r-uOLxNrNk8','description'=>'pandas, analysis, and projects','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>320,'tags'=>json_encode(['data-science'])],

            // Security
            ['job_category'=>'it_cs','skill_key'=>'cybersecurity','skill_display_name'=>'Cybersecurity','resource_label'=>'Practical Ethical Hacking – TCM playlist','url'=>'https://www.youtube.com/playlist?list=PLBf0hzazHTGN31ZPTzBhzEDJ9NZvMSA3v','description'=>'Hands-on ethical hacking path','platform'=>'youtube','level'=>'beginner','is_playlist'=>true,'duration_minutes'=>null,'tags'=>json_encode(['security'])],

            // Other popular languages
            ['job_category'=>'it_cs','skill_key'=>'c-sharp','skill_display_name'=>'C#','resource_label'=>'C# Full Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=GhQdlIFylQ8','description'=>'.NET and C# basics for apps','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>270,'tags'=>json_encode(['c#'])],
            ['job_category'=>'it_cs','skill_key'=>'cpp','skill_display_name'=>'C++','resource_label'=>'C++ Full Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=vLnPwxZdW4Y','description'=>'Syntax, OOP, and STL fundamentals','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>260,'tags'=>json_encode(['c++'])],
            ['job_category'=>'it_cs','skill_key'=>'php','skill_display_name'=>'PHP','resource_label'=>'PHP for Beginners – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=OK_JCtrrv-c','description'=>'Intro to PHP with projects','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>240,'tags'=>json_encode(['php'])],
            ['job_category'=>'it_cs','skill_key'=>'html-css','skill_display_name'=>'HTML & CSS','resource_label'=>'HTML & CSS Full Course – freeCodeCamp','url'=>'https://www.youtube.com/watch?v=mU6anWqZJcc','description'=>'Build responsive websites from scratch','platform'=>'youtube','level'=>'beginner','is_playlist'=>false,'duration_minutes'=>420,'tags'=>json_encode(['html','css'])],
        ];

        foreach ($rows as $r) {
            DB::table('skill_resources')->updateOrInsert(
                ['skill_key' => $r['skill_key'], 'url' => $r['url']],
                $r
            );
        }
    }
}
