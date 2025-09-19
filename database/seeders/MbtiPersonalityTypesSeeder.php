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
                'description' => 'Imaginative and strategic thinkers, with a plan for everything.',
                'strengths' => 'Strategic thinking, Independent, Determined, Hard-working, Open-minded',
                'weaknesses' => 'Arrogant, Judgmental, Overly analytical, Loathe highly structured environments, Clueless in romance',
                'career_paths' => 'Scientist, Engineer, Doctor, Dentist, Teacher, Judge, Lawyer',
                'temperament' => 'NT (Rational)',
                'role' => 'Mastermind'
            ],
            [
                'type_code' => 'INTP',
                'name' => 'The Thinker',
                'description' => 'Innovative inventors with an unquenchable thirst for knowledge.',
                'strengths' => 'Great analysts and abstract thinkers, Imaginative and original, Open-minded, Enthusiastic, Objective, Honest and straightforward',
                'weaknesses' => 'Very private and withdrawn, Insensitive, Absent-minded, Condescending, Loathe rules and guidelines, Second-guess themselves',
                'career_paths' => 'Scientist, Mathematician, Engineer, Computer Programmer, Forensic Investigator, Lawyer',
                'temperament' => 'NT (Rational)',
                'role' => 'Architect'
            ],
            [
                'type_code' => 'ENTJ',
                'name' => 'The Commander',
                'description' => 'Bold, imaginative and strong-willed leaders, always finding a way – or making one.',
                'strengths' => 'Efficient, Energetic, Self-confident, Strong-willed, Strategic thinkers, Charismatic and inspiring',
                'weaknesses' => 'Stubborn and dominant, Intolerant, Impatient, Arrogant, Poor handling of emotions, Cold and ruthless',
                'career_paths' => 'Executive, Entrepreneur, Manager, Consultant, Lawyer, Judge, Scientist',
                'temperament' => 'NT (Rational)',
                'role' => 'Fieldmarshal'
            ],
            [
                'type_code' => 'ENTP',
                'name' => 'The Debater',
                'description' => 'Smart and curious thinkers who cannot resist an intellectual challenge.',
                'strengths' => 'Knowledgeable, Quick thinkers, Original, Excellent brainstormers, Charismatic, Energetic',
                'weaknesses' => 'Very argumentative, Insensitive, Intolerant, Can find it difficult to focus, Dislike practical matters',
                'career_paths' => 'Lawyer, Psychologist, Scientist, Engineer, Entrepreneur, Actor, Journalist',
                'temperament' => 'NT (Rational)',
                'role' => 'Inventor'
            ],
            [
                'type_code' => 'INFJ',
                'name' => 'The Advocate',
                'description' => 'Quiet and mystical, yet very inspiring and tireless idealists.',
                'strengths' => 'Creative, Insightful, Inspiring and convincing, Decisive, Determined, Passionate, Altruistic',
                'weaknesses' => 'Sensitive, Extremely private, Perfectionist, Always need to have a cause, Can burn out easily',
                'career_paths' => 'Counselor, Writer, Scientist, Doctor, Teacher, Artist, Librarian',
                'temperament' => 'NF (Idealist)',
                'role' => 'Counselor'
            ],
            [
                'type_code' => 'INFP',
                'name' => 'The Mediator',
                'description' => 'Poetic, kind and altruistic people, always eager to help a good cause.',
                'strengths' => 'Idealistic, Loyal and devoted, Hard-working, Flexible, Warm, caring and interested in people',
                'weaknesses' => 'Too idealistic, Too altruistic, Impractical, Dislike dealing with data, Take things personally, Difficult to get to know',
                'career_paths' => 'Writer, Artist, Counselor, Teacher, Psychologist, Photographer, Musician',
                'temperament' => 'NF (Idealist)',
                'role' => 'Healer'
            ],
            [
                'type_code' => 'ENFJ',
                'name' => 'The Protagonist',
                'description' => 'Charismatic and inspiring leaders, able to mesmerize their listeners.',
                'strengths' => 'Tolerant, Reliable, Charismatic, Altruistic, Natural leaders',
                'weaknesses' => 'Overly idealistic, Too selfless, Too sensitive, Fluctuating self-esteem, Struggle to make tough decisions',
                'career_paths' => 'Teacher, Counselor, Politician, Writer, Actor, Diplomat, Facilitator',
                'temperament' => 'NF (Idealist)',
                'role' => 'Teacher'
            ],
            [
                'type_code' => 'ENFP',
                'name' => 'The Campaigner',
                'description' => 'Enthusiastic, creative and sociable free spirits, who can always find a reason to smile.',
                'strengths' => 'Enthusiastic, Creative, Sociable, Energetic, Independent, Good communication skills',
                'weaknesses' => 'Poor practical skills, Find it difficult to focus, Overthink things, Get stressed easily, Highly emotional, Independent to a fault',
                'career_paths' => 'Journalist, Actor, Teacher, Counselor, Politician, TV Reporter, Writer',
                'temperament' => 'NF (Idealist)',
                'role' => 'Champion'
            ],
            [
                'type_code' => 'ISTJ',
                'name' => 'The Logistician',
                'description' => 'Practical and fact-minded, reliable and responsible.',
                'strengths' => 'Honest and direct, Strong-willed and dutiful, Very responsible, Calm and practical, Create and enforce order, Jacks-of-all-trades',
                'weaknesses' => 'Stubborn, Insensitive, Always by the book, Judgmental, Often unreasonably blame themselves',
                'career_paths' => 'Accountant, Engineer, Doctor, Dentist, Lawyer, Judge, Police Officer',
                'temperament' => 'SJ (Guardian)',
                'role' => 'Inspector'
            ],
            [
                'type_code' => 'ISFJ',
                'name' => 'The Protector',
                'description' => 'Very dedicated and warm protectors, always ready to defend their loved ones.',
                'strengths' => 'Supportive, Reliable and patient, Imaginative and observant, Enthusiastic, Loyal, Hard-working',
                'weaknesses' => 'Humble and shy, Take things too personally, Repress their feelings, Overload themselves, Reluctant to change, Too altruistic',
                'career_paths' => 'Teacher, Social Worker, Counselor, Nurse, Doctor, Dentist, Clerical Supervisor',
                'temperament' => 'SJ (Guardian)',
                'role' => 'Protector'
            ],
            [
                'type_code' => 'ESTJ',
                'name' => 'The Executive',
                'description' => 'Excellent administrators, unsurpassed at managing things – or people.',
                'strengths' => 'Dedicated, Strong-willed, Direct and honest, Loyal, patient and reliable, Enjoy creating order, Excellent organizers',
                'weaknesses' => 'Inflexible and stubborn, Uncomfortable with unconventional situations, Judgmental, Too focused on social status, Difficult to relax, Difficulty expressing emotion',
                'career_paths' => 'Manager, Administrator, Executive, Entrepreneur, Judge, Teacher, Sales Representative',
                'temperament' => 'SJ (Guardian)',
                'role' => 'Supervisor'
            ],
            [
                'type_code' => 'ESFJ',
                'name' => 'The Consul',
                'description' => 'Extraordinarily caring, social and popular people, always eager to help.',
                'strengths' => 'Strong practical skills, Strong sense of duty, Very loyal, Sensitive and warm, Good at connecting with others',
                'weaknesses' => 'Worried about their social status, Inflexible, Reluctant to innovate or improvise, Vulnerable to criticism, Often too needy, Too selfless',
                'career_paths' => 'Teacher, Social Worker, Nurse, Doctor, Secretary, Counselor, Paralegal',
                'temperament' => 'SJ (Guardian)',
                'role' => 'Provider'
            ],
            [
                'type_code' => 'ISTP',
                'name' => 'The Virtuoso',
                'description' => 'Bold and practical experimenters, masters of all kinds of tools.',
                'strengths' => 'Optimistic and energetic, Creative and practical, Spontaneous and rational, Know how to prioritize, Great in a crisis, Relaxed',
                'weaknesses' => 'Stubborn, Insensitive, Private and reserved, Easily bored, Dislike commitment, Risky behavior',
                'career_paths' => 'Mechanic, Engineer, Forensic Investigator, Paramedic, Pilot, Police Officer, Military Officer',
                'temperament' => 'SP (Artisan)',
                'role' => 'Crafter'
            ],
            [
                'type_code' => 'ISFP',
                'name' => 'The Adventurer',
                'description' => 'Flexible and charming artists, always ready to explore new possibilities.',
                'strengths' => 'Charming, Sensitive to others, Imaginative, Passionate, Curious, Artistic',
                'weaknesses' => 'Fiercely independent, Unpredictable, Easily stressed, Overly competitive, Fluctuating self-esteem',
                'career_paths' => 'Artist, Musician, Designer, Writer, Counselor, Teacher, Psychologist',
                'temperament' => 'SP (Artisan)',
                'role' => 'Composer'
            ],
            [
                'type_code' => 'ESTP',
                'name' => 'The Entrepreneur',
                'description' => 'Smart, energetic and very perceptive people, who truly enjoy living on the edge.',
                'strengths' => 'Bold, Rational and practical, Original, Perceptive, Direct, Sociable',
                'weaknesses' => 'Insensitive, Impatient, Risk-prone, Unstructured, May miss the bigger picture, Defiant',
                'career_paths' => 'Entrepreneur, Police Officer, Paramedic, Sales Representative, Real Estate Agent, Stock Broker',
                'temperament' => 'SP (Artisan)',
                'role' => 'Promoter'
            ],
            [
                'type_code' => 'ESFP',
                'name' => 'The Entertainer',
                'description' => 'Spontaneous, energetic and enthusiastic people – life is never boring around them.',
                'strengths' => 'Bold, Original, Aesthetics and showcase, Practical, Observant, Excellent people skills',
                'weaknesses' => 'Sensitive, Conflict-averse, Easily bored, Poor long-term planners, Unfocused',
                'career_paths' => 'Artist, Actor, Musician, Designer, Counselor, Social Worker, Teacher',
                'temperament' => 'SP (Artisan)',
                'role' => 'Performer'
            ]
        ];

        foreach ($types as $type) {
            MbtiPersonalityType::create($type);
        }
    }
}
