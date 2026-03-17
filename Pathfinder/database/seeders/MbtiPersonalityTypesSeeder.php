<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MbtiPersonalityType;

class MbtiPersonalityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'type_code' => 'INTJ',
                'name' => 'The Architect',
                'description' => 'INTJs are strategic, independent thinkers who approach the world with a logical and analytical mindset, often seeing patterns and possibilities where others see chaos. They possess an insatiable thirst for knowledge and are driven by a desire to understand the complex systems that govern life. While they can be quiet and reserved, their intensity is fueled by a relentless pursuit of excellence and a deep commitment to their internal vision. In professional settings, they excel at high-level planning and systems design, preferring autonomy and competence over social hierarchy. Ultimately, their goal is to turn their ambitious ideas into concrete, efficient realities through disciplined execution and strategic foresight.',
                'strengths' => 'Strategic thinking, Independent, Determined, Hard-working, Open-minded',
                'weaknesses' => 'Arrogant, Judgmental, Overly analytical, Loathe highly structured environments',
                'workplace_habits' => 'INTJs thrive in environments where they can work independently on complex problems. They value competence over hierarchy and social pleasantries, often preferring to focus on high-level strategy and systems design.',
                'growth_advice' => 'Cultivating emotional intelligence and recognizing the value of team collaboration can help INTJs turn their brilliant strategies into successful, people-focused realities.',
                'career_paths' => 'Scientist, Engineer, Doctor, Dentist, Teacher, Judge, Lawyer',
                'temperament' => 'NT (Rational)',
                'role' => 'Mastermind'
            ],
            [
                'type_code' => 'INTP',
                'name' => 'The Thinker',
                'description' => 'INTPs are innovative inventors who thrive on abstract problem-solving and the intellectual freedom to explore uncharted territories of thought. They possess a unique ability to dissect complex ideas and identify logical inconsistencies, often arriving at original solutions that others might overlook. While they may appear detached or absent-minded, their internal world is a vibrant laboratory of experimentation and theoretical modeling. In the workplace, they value intellectual challenge and dislike the constraints of routine or rigid micro-management. For an INTP, the ultimate objective is not just to know the truth, but to understand the fundamental laws that define the universe.',
                'strengths' => 'Great analysts and abstract thinkers, Imaginative and original, Open-minded, Enthusiastic, Objective',
                'weaknesses' => 'Very private and withdrawn, Insensitive, Absent-minded, Condescending, Loathe rules',
                'workplace_habits' => 'INTPs are at their best when they have intellectual freedom to explore new ideas. They dislike routine and micro-management, preferring a workspace where their unique problem-solving abilities are trusted.',
                'growth_advice' => 'Focusing on practical follow-through and communicating abstract ideas in a way that is actionable for others will increase an INTPs professional impact.',
                'career_paths' => 'Scientist, Mathematician, Engineer, Computer Programmer, Forensic Investigator, Lawyer',
                'temperament' => 'NT (Rational)',
                'role' => 'Architect'
            ],
            [
                'type_code' => 'ENTJ',
                'name' => 'The Commander',
                'description' => 'ENTJs are bold and visionary leaders who possess a natural ability to organize both people and processes to achieve long-term strategic goals. They are driven by an unshakeable self-confidence and a relentless will to overcome any obstacle in their path to success. While their directness can sometimes be perceived as ruthlessness, it is born from a desire for maximum efficiency and a commitment to excellence. In a professional environment, they thrive in high-pressure roles that allow them to take charge and implement large-scale change. Their greatest satisfaction comes from seeing a complex project through from its conceptual birth to its final, impactful delivery.',
                'strengths' => 'Efficient, Energetic, Self-confident, Strong-willed, Strategic thinkers',
                'weaknesses' => 'Stubborn and dominant, Intolerant, Impatient, Arrogant, Cold and ruthless',
                'workplace_habits' => 'ENTJs are natural leaders who excel at organizing both people and processes. They have little patience for inefficiency or incompetence and thrive in high-pressure, results-oriented environments.',
                'growth_advice' => 'Learning to appreciate different perspectives and developing more patience for the process—not just the outcome—will help ENTJs build stronger, more loyal teams.',
                'career_paths' => 'Executive, Entrepreneur, Manager, Consultant, Lawyer, Judge, Scientist',
                'temperament' => 'NT (Rational)',
                'role' => 'Fieldmarshal'
            ],
            [
                'type_code' => 'ENTP',
                'name' => 'The Debater',
                'description' => 'ENTPs are quick-witted and curious thinkers who love nothing more than an intellectual challenge or a lively debate on a complex topic. They are master brainstormers who can see multiple sides of any issue, often using their charisma and energy to inspire others with new possibilities. While they may struggle with the repetitive details of implementation, their ability to innovate and think on their feet makes them invaluable in fast-paced environments. They are constantly seeking to expand their knowledge and are not afraid to challenge the status quo if they see a better way forward. Ultimately, the ENTP\'s journey is one of continuous exploration and the relentless pursuit of intellectual mastery.',
                'strengths' => 'Knowledgeable, Quick thinkers, Original, Excellent brainstormers, Charismatic',
                'weaknesses' => 'Very argumentative, Insensitive, Intolerant, Can find it difficult to focus',
                'workplace_habits' => 'ENTPs bring boundless energy and creativity to the workplace. They love to challenge the status quo and brainstorm new ideas, but they may struggle with repetitive tasks or strict adherence to rules.',
                'growth_advice' => 'Developing the discipline to follow through on ideas and being mindful of others\' emotional boundaries during debates will make an ENTP a more effective collaborator.',
                'career_paths' => 'Lawyer, Psychologist, Scientist, Engineer, Entrepreneur, Actor, Journalist',
                'temperament' => 'NT (Rational)',
                'role' => 'Inventor'
            ],
            [
                'type_code' => 'INFJ',
                'name' => 'The Advocate',
                'description' => 'INFJs are quiet and mystical visionaries who possess a deep sense of idealism and a tireless commitment to making a positive impact on the world. They have a rare ability to understand the complex emotions of others and are driven by a need for authenticity and meaningful connection. While they may appear reserved, their internal convictions are incredibly strong, often fueling a lifelong pursuit of their personal mission. In the workplace, they excel in roles that allow them to help others and work towards a higher purpose, valuing harmony over competition. For an INFJ, true success is measured by the depth of their relationships and the lasting legacy of their compassionate actions.',
                'strengths' => 'Creative, Insightful, Inspiring and convincing, Decisive, Determined',
                'weaknesses' => 'Sensitive, Extremely private, Perfectionist, Can burn out easily',
                'workplace_habits' => 'INFJs are deeply committed to their work and value meaningful impact over personal Gain. They work best in quiet, harmonious environments where they can focus on their vision for the common good.',
                'growth_advice' => 'Setting clear boundaries and learning to say "no" is crucial for INFJs to avoid burnout and maintain their creative energy.',
                'career_paths' => 'Counselor, Writer, Scientist, Doctor, Teacher, Artist, Librarian',
                'temperament' => 'NF (Idealist)',
                'role' => 'Counselor'
            ],
            [
                'type_code' => 'INFP',
                'name' => 'The Mediator',
                'description' => 'INFPs are poetic and altruistic souls who spend much of their lives exploring their internal values and looking for ways to bring their unique visions to life. They possess a deep-seated empathy and a desire to help others find their own path to self-discovery and personal meaning. While they can be incredibly private, their loyalty to the causes and people they care about is absolute and unwavering. In professional settings, they thrive in creative or service-oriented roles that allow them to align their work with their core beliefs. Ultimately, the INFP seeks to live a life that is a true reflection of their compassionate and imaginative heart.',
                'strengths' => 'Idealistic, Loyal and devoted, Hard-working, Flexible, caring',
                'weaknesses' => 'Too idealistic, Impractical, Dislike dealing with data, Take things personally',
                'workplace_habits' => 'INFPs seek harmony and meaning in their work. They are often the creative heart of a team, bringing empathy and original perspectives, but they may struggle with rigid structures or high-conflict environments.',
                'growth_advice' => 'Developing more practical organizational skills and learning to detach from criticism will help INFPs bring their beautiful visions to life.',
                'career_paths' => 'Writer, Artist, Counselor, Teacher, Psychologist, Photographer, Musician',
                'temperament' => 'NF (Idealist)',
                'role' => 'Healer'
            ],
            [
                'type_code' => 'ENFJ',
                'name' => 'The Protagonist',
                'description' => 'ENFJs are charismatic and inspiring leaders who naturally excel at building community and helping others reach their full potential. They possess a deep emotional intelligence and a genuine concern for the well-being of those around them, often acting as a bridge between diverse groups. While they are driven by a strong sense of duty, their leadership style is defined by empathy and the ability to motivate others through shared values. In the workplace, they thrive in collaborative roles that allow them to facilitate growth and foster a positive, inclusive culture. Their greatest joy comes from witnessing the success of the people they have supported and inspired.',
                'strengths' => 'Tolerant, Reliable, Charismatic, Altruistic, Natural leaders',
                'weaknesses' => 'Overly idealistic, Too sensitive, Struggle with tough decisions',
                'workplace_habits' => 'ENFJs are inspiring team players who focus on employee growth and harmony. They are excellent at facilitating communication and building a positive morale, but they may take personal responsibility for others\' failures.',
                'growth_advice' => 'Learning to detach personal value from professional outcomes and becoming comfortable with necessary conflict will grow an ENFJ\'s leadership maturity.',
                'career_paths' => 'Teacher, Counselor, Politician, Writer, Actor, Diplomat, Facilitator',
                'temperament' => 'NF (Idealist)',
                'role' => 'Teacher'
            ],
            [
                'type_code' => 'ENFP',
                'name' => 'The Campaigner',
                'description' => 'ENFPs are enthusiastic and creative free spirits who approach life with a sense of wonder and a desire to connect with others on a deep, personal level. They possess a boundless energy and a unique ability to see possibilities and patterns in the world that others might miss. While they may struggle with the structure of more traditional roles, their charm and adaptability allow them to thrive in environments that value innovation and social interaction. They are constantly seeking new experiences and are driven by a need for personal growth and the exploration of their own potential. Ultimately, the ENFP\'s life is a vibrant tapestry of discovery, connection, and the celebration of the human spirit.',
                'strengths' => 'Enthusiastic, Creative, Sociable, Energetic, Independent',
                'weaknesses' => 'Poor practical skills, Difficult to focus, Overthink things, Highly emotional',
                'workplace_habits' => 'ENFPs bring a contagious enthusiasm to the workplace. They are excellent at starting new projects and seeing possibilities where others don\'t, but they may need help with the details and follow-through.',
                'growth_advice' => 'Pairing your big-picture creativity with a reliable system for tracking details will help you turn your many great ideas into finished results.',
                'career_paths' => 'Journalist, Actor, Teacher, Counselor, Politician, TV Reporter, Writer',
                'temperament' => 'NF (Idealist)',
                'role' => 'Champion'
            ],
            [
                'type_code' => 'ISTJ',
                'name' => 'The Logistician',
                'description' => 'ISTJs are practical and fact-minded individuals who value tradition, order, and the reliable fulfillment of their responsibilities. They possess a thorough and meticulous approach to their work, ensuring that every detail is attended to and every task is completed to the highest standard. While they may appear quiet or serious, their loyalty and dependability make them the bedrock of any organization or community. In professional settings, they thrive in roles that provide structure and clear expectations, where their sense of duty can be fully utilized. For an ISTJ, success is earned through hard work, integrity, and the consistent delivery of high-quality results.',
                'strengths' => 'Honest and direct, Strong-willed and dutiful, Responsible, Calm and practical',
                'weaknesses' => 'Stubborn, Insensitive, Always by the book, Judgmental',
                'workplace_habits' => 'ISTJs are the bedrock of any organization. They value tradition, hierarchy, and clear rules. They are highly dependable and thorough, ensuring that every task is completed exactly as required.',
                'growth_advice' => 'Embracing change and innovation as opportunities for improvement rather than threats to stability will help you adapt to modern workplace shifts.',
                'career_paths' => 'Accountant, Engineer, Doctor, Dentist, Lawyer, Judge, Police Officer',
                'temperament' => 'SJ (Guardian)',
                'role' => 'Inspector'
            ],
            [
                'type_code' => 'ISFJ',
                'name' => 'The Protector',
                'description' => 'ISFJs are dedicated and warm protectors who find deep satisfaction in supporting others and ensuring that their practical needs are met with care and attention. They possess a remarkable memory for detail and a quiet strength that allows them to handle complex responsibilities without seeking recognition. While they are incredibly humble, their commitment to the well-being of their loved ones and colleagues is absolute and unwavering. In the workplace, they excel in roles that allow them to provide service and foster a sense of security and belonging. Ultimately, the ISFJ\'s goal is to create a harmonious and supportive environment where everyone can thrive.',
                'strengths' => 'Supportive, Reliable and patient, Observant, Enthusiastic, Loyal',
                'weaknesses' => 'Humble and shy, Take things too personally, Overload themselves, Reluctant to change',
                'workplace_habits' => 'ISFJs work tirelessly behind the scenes to ensure things run smoothly and everyone feels supported. They are meticulous and loyal, often remembering small details about colleagues and clients that others miss.',
                'growth_advice' => 'Learning to voice your own needs and recognizing when you\'ve taken on too much will help you maintain your legendary commitment without burning out.',
                'career_paths' => 'Teacher, Social Worker, Counselor, Nurse, Doctor, Dentist, Clerical Supervisor',
                'temperament' => 'SJ (Guardian)',
                'role' => 'Protector'
            ],
            [
                'type_code' => 'ESTJ',
                'name' => 'The Executive',
                'description' => 'ESTJs are efficient and strong-willed leaders who possess a natural talent for managing both people and resources to achieve clear, measurable results. They value honesty, directness, and the adherence to established standards, often acting as the voice of reason and stability in challenging situations. While their dedication to order can sometimes be seen as inflexibility, it is born from a desire for predictable success and the well-being of the collective. In professional environments, they thrive in managerial roles that allow them to implement structure and ensure that everyone is working towards a common goal. For an ESTJ, a job well done is the highest form of personal and professional achievement.',
                'strengths' => 'Dedicated, Strong-willed, Direct, Loyal, Excellent organizers',
                'weaknesses' => 'Inflexible, Judgmental, Too focused on status, Difficult to relax',
                'workplace_habits' => 'ESTJs are natural-born leaders who thrive on organization and clear standards. They are excellent at project management and ensuring everyone follows the established procedures to achieve high-quality results.',
                'growth_advice' => 'Practicing flexibility and being open to unconventional ideas that might improve efficiency will help an ESTJ stay competitive in evolving markets.',
                'career_paths' => 'Manager, Administrator, Executive, Entrepreneur, Judge, Teacher, Sales Representative',
                'temperament' => 'SJ (Guardian)',
                'role' => 'Supervisor'
            ],
            [
                'type_code' => 'ESFJ',
                'name' => 'The Consul',
                'description' => 'ESFJs are social and extraordinarily caring individuals who thrive on building connections and ensuring that everyone in their community feels valued and supported. They possess a strong sense of duty and a genuine desire to facilitate harmony and cooperation in all of their interactions. While they are deeply invested in the well-being of others, they also value tradition and the social structures that provide a sense of belonging and order. In the workplace, they excel in roles that require collaboration and interpersonal expertise, acting as the heart of any successful team. Ultimately, the ESFJ\'s greatest satisfaction comes from creating a vibrant and inclusive community where everyone feels at home.',
                'strengths' => 'Practical skills, Strong sense of duty, Loyal, Sensitive, Good connectors',
                'weaknesses' => 'Worried about status, Inflexible, Vulnerable to criticism, Needy',
                'workplace_habits' => 'ESFJs are the heart of a team, excelling at roles that require cooperation and interpersonal harmony. They are highly organized and ensure that everyone\'s needs are met while maintaining professional standards.',
                'growth_advice' => 'Developing a thicker skin for constructive criticism and focusing on intrinsic value rather than external validation will lead to greater professional peace of mind.',
                'career_paths' => 'Teacher, Social Worker, Nurse, Doctor, Secretary, Counselor, Paralegal',
                'temperament' => 'SJ (Guardian)',
                'role' => 'Provider'
            ],
            [
                'type_code' => 'ISTP',
                'name' => 'The Virtuoso',
                'description' => 'ISTPs are bold and practical experimenters who possess a natural mastery of tools and a keen ability to troubleshoot complex mechanical or technical problems. They approach the world with a sense of curiosity and a desire to understand how things work through hands-on experience and logical analysis. While they may appear reserved or fiercely independent, their spontaneous nature and ability to stay calm under pressure make them invaluable in a crisis. In professional settings, they thrive in roles that offer variety and tangible challenges, avoiding the constraints of purely theoretical or repetitive tasks. Ultimately, the ISTP seeks to live life on their own terms, mastering the physical world one challenge at a time.',
                'strengths' => 'Optimistic, Creative and practical, Relaxed, Great in a crisis',
                'weaknesses' => 'Stubborn, Insensitive, Easily bored, Risky behavior',
                'workplace_habits' => 'ISTPs are hands-on problem solvers who thrive on troubleshooting and figuring out how things work. They prefer practical results over abstract theories and work best in environments with tangible projects.',
                'growth_advice' => 'Working on long-term planning and considering the emotional impact of your direct communication style will enhance your professional relationships.',
                'career_paths' => 'Mechanic, Engineer, Forensic Investigator, Paramedic, Pilot, Police Officer, Military Officer',
                'temperament' => 'SP (Artisan)',
                'role' => 'Crafter'
            ],
            [
                'type_code' => 'ISFP',
                'name' => 'The Adventurer',
                'description' => 'ISFPs are flexible and charming artists who approach life with a sensitive eye and a deep-seated desire for personal expression and creative discovery. They possess a unique ability to live in the moment and find beauty in the sensory details of the world around them. While they may be quiet and private about their internal world, their passion and commitment to their art and values are absolute. In the workplace, they thrive in environments that offer freedom and allow them to align their work with their own sense of aesthetics and personal meaning. For an ISFP, life is an ongoing experiment in self-expression and the pursuit of a life lived authentically and beautifully.',
                'strengths' => 'Charming, Sensitive, Imaginative, Passionate, Curious, Artistic',
                'weaknesses' => 'Fiercely independent, Unpredictable, Easily stressed, Competitive',
                'workplace_habits' => 'ISFPs bring an artistic and sensitive touch to their work. They value freedom and personal expression, working best in environments that allow them to align their work with their internal values without restrictive schedules.',
                'growth_advice' => 'Developing a more structured approach to long-term goals and being proactive about communication during stress will help you achieve more sustained success.',
                'career_paths' => 'Artist, Musician, Designer, Writer, Counselor, Teacher, Psychologist',
                'temperament' => 'SP (Artisan)',
                'role' => 'Composer'
            ],
            [
                'type_code' => 'ESTP',
                'name' => 'The Entrepreneur',
                'description' => 'ESTPs are energetic and perceptive individuals who truly enjoy living on the edge and approaching every situation with a sense of bold action and practical directness. They possess a remarkable ability to read people and situations accurately, allowing them to think on their feet and find original solutions in fast-paced or unpredictable environments. While they may struggle with long-term planning, their charisma and "can-do" attitude make them natural leaders in any crisis or high-stakes endeavor. In professional settings, they thrive in roles that offer immediate feedback and tangible results, avoiding the boredom of routine or purely analytical work. Ultimately, the ESTP\'s journey is one of continuous action, excitement, and the relentless pursuit of the next big challenge.',
                'strengths' => 'Bold, Rational and practical, Original, Perceptive, Direct',
                'weaknesses' => 'Insensitive, Impatient, Risk-prone, Unstructured, Defiant',
                'workplace_habits' => 'ESTPs are energetic and action-oriented, preferring to "learn by doing" rather than sitting through long meetings. They are excellent in a crisis and can think on their feet, making them valuable in fast-paced or unpredictable environments.',
                'growth_advice' => 'Learning to slow down and consider the long-term impact of your decisions, especially on interpersonal relations, will help you reach higher professional ground.',
                'career_paths' => 'Entrepreneur, Police Officer, Paramedic, Sales Representative, Real Estate Agent, Stock Broker',
                'temperament' => 'SP (Artisan)',
                'role' => 'Promoter'
            ],
            [
                'type_code' => 'ESFP',
                'name' => 'The Entertainer',
                'description' => 'ESFPs are spontaneous and enthusiastic people who approach life with a contagious energy and a desire to share their joy and charisma with everyone they meet. They possess a deep-seated appreciation for the aesthetics of life and a remarkable ability to bridge social gaps and build bridges through their warm and inclusive nature. While they may struggle with the purely analytical side of some roles, their practical skills and people-centered approach make them the "glue" of any successful social or professional group. They are constantly seeking new experiences and are driven by a need for connection and the celebration of the present moment. For an ESFP, life is a grand performance, meant to be shared and enjoyed with as much laughter and connection as possible.',
                'strengths' => 'Bold, Original, Practical, Observant, Excellent people skills',
                'weaknesses' => 'Sensitive, Conflict-averse, Easily bored, Poor long-term planners',
                'workplace_habits' => 'ESFPs are the life of the office, excelling in roles that involve direct interaction with people. They bring enthusiasm and a pragmatic approach to their work, though they may struggle with monotony or purely analytical tasks.',
                'growth_advice' => 'Working on your long-term planning and developing a more objective approach to conflict will help you sustain your natural charisma throughout your career.',
                'career_paths' => 'Artist, Actor, Musician, Designer, Counselor, Social Worker, Teacher',
                'temperament' => 'SP (Artisan)',
                'role' => 'Performer'
            ]
        ];

        foreach ($types as $type) {
            MbtiPersonalityType::updateOrCreate(
                ['type_code' => $type['type_code']],
                $type
            );
        }
    }
}
