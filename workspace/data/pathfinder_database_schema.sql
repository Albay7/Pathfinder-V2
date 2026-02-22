-- =====================================================
-- Pathfinder Application - Complete Database Schema
-- =====================================================
-- This SQL script contains all the database tables used in the Pathfinder career guidance platform
-- Generated from Laravel migrations

-- Set character set and collation
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- Core Application Tables
-- =====================================================

-- Users table - Main user authentication and profile
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `mbti_type` varchar(255) NULL DEFAULT NULL COMMENT 'MBTI personality type (e.g., INTJ, ENFP)',
  `mbti_scores` json NULL DEFAULT NULL COMMENT 'MBTI dimension scores',
  `mbti_description` text NULL DEFAULT NULL COMMENT 'MBTI personality description',
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Progress table - Stores assessment results and career progress
CREATE TABLE `user_progress` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `feature_type` varchar(255) NOT NULL COMMENT 'career_guidance, career_path, skill_gap',
  `assessment_type` varchar(255) NULL DEFAULT NULL COMMENT 'course, job for career guidance',
  `questionnaire_answers` json NULL DEFAULT NULL COMMENT 'Store questionnaire responses',
  `recommendation` varchar(255) NULL DEFAULT NULL COMMENT 'Store the recommendation result',
  `current_role` varchar(255) NULL DEFAULT NULL COMMENT 'For career path',
  `target_role` varchar(255) NULL DEFAULT NULL COMMENT 'For career path and skill gap',
  `current_skills` json NULL DEFAULT NULL COMMENT 'For skill gap analysis',
  `analysis_result` json NULL DEFAULT NULL COMMENT 'Store complete analysis results',
  `match_percentage` decimal(5,2) NULL DEFAULT NULL COMMENT 'For skill gap match percentage',
  `completed` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether the assessment was completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_progress_user_id_feature_type_index` (`user_id`, `feature_type`),
  KEY `user_progress_user_id_completed_index` (`user_id`, `completed`),
  CONSTRAINT `user_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tutorials table - Learning resources and skill development content
CREATE TABLE `tutorials` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `skill` varchar(255) NOT NULL COMMENT 'The skill this tutorial teaches',
  `level` varchar(255) NOT NULL DEFAULT 'beginner' COMMENT 'beginner, intermediate, advanced',
  `type` varchar(255) NOT NULL DEFAULT 'video' COMMENT 'video, article, course, documentation',
  `url` varchar(255) NOT NULL COMMENT 'Tutorial link',
  `provider` varchar(255) NULL DEFAULT NULL COMMENT 'YouTube, Coursera, freeCodeCamp, etc.',
  `duration_minutes` int(11) NULL DEFAULT NULL COMMENT 'Estimated duration',
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00 COMMENT 'User rating out of 5',
  `difficulty` int(11) NOT NULL DEFAULT 1 COMMENT '1-5 difficulty scale',
  `prerequisites` json NULL DEFAULT NULL COMMENT 'Required skills/knowledge',
  `tags` json NULL DEFAULT NULL COMMENT 'Additional tags for filtering',
  `is_free` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutorials_skill_level_index` (`skill`, `level`),
  KEY `tutorials_is_active_is_free_index` (`is_active`, `is_free`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Tutorial Progress table - Tracks learning progress and completion
CREATE TABLE `user_tutorial_progress` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tutorial_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('not_started','in_progress','completed','bookmarked') NOT NULL DEFAULT 'not_started',
  `progress_percentage` int(11) NOT NULL DEFAULT 0 COMMENT '0-100',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `time_spent_minutes` int(11) NOT NULL DEFAULT 0 COMMENT 'Time spent on tutorial',
  `user_rating` decimal(3,2) NULL DEFAULT NULL COMMENT 'User rating of the tutorial',
  `notes` text NULL DEFAULT NULL COMMENT 'User personal notes',
  `bookmarks` json NULL DEFAULT NULL COMMENT 'Specific timestamps or sections bookmarked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_tutorial_progress_user_id_tutorial_id_unique` (`user_id`, `tutorial_id`),
  KEY `user_tutorial_progress_user_id_status_index` (`user_id`, `status`),
  KEY `user_tutorial_progress_tutorial_id_status_index` (`tutorial_id`, `status`),
  CONSTRAINT `user_tutorial_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_tutorial_progress_tutorial_id_foreign` FOREIGN KEY (`tutorial_id`) REFERENCES `tutorials` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Laravel System Tables
-- =====================================================

-- Password Reset Tokens table - For password reset functionality
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions table - User session management
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) NULL DEFAULT NULL,
  `user_agent` text NULL DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache table - Application caching
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache Locks table - Cache locking mechanism
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs table - Queue job management
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED NULL DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Job Batches table - Batch job management
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext NULL DEFAULT NULL,
  `cancelled_at` int(11) NULL DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Failed Jobs table - Failed queue jobs
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Sample Data Inserts (Optional)
-- =====================================================

-- Sample tutorial data
INSERT INTO `tutorials` (`title`, `description`, `skill`, `level`, `type`, `url`, `provider`, `duration_minutes`, `rating`, `difficulty`, `is_free`, `is_active`) VALUES
('Introduction to JavaScript', 'Learn the basics of JavaScript programming language', 'JavaScript', 'beginner', 'video', 'https://www.youtube.com/watch?v=example', 'YouTube', 120, 4.50, 2, 1, 1),
('Advanced React Concepts', 'Deep dive into React hooks, context, and performance optimization', 'React', 'advanced', 'course', 'https://www.coursera.org/example', 'Coursera', 480, 4.80, 4, 0, 1),
('SQL Database Design', 'Learn how to design efficient database schemas', 'SQL', 'intermediate', 'article', 'https://www.freecodecamp.org/example', 'freeCodeCamp', 90, 4.20, 3, 1, 1),
('Python for Data Science', 'Introduction to data analysis with Python and pandas', 'Python', 'beginner', 'course', 'https://www.edx.org/example', 'edX', 360, 4.60, 2, 1, 1),
('Digital Marketing Fundamentals', 'Learn the basics of digital marketing strategies', 'Digital Marketing', 'beginner', 'video', 'https://www.udemy.com/example', 'Udemy', 180, 4.30, 2, 0, 1);

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- Database Schema Summary
-- =====================================================
/*
Core Application Tables:
1. users - User authentication and MBTI personality data
2. user_progress - Assessment results and career progress tracking
3. tutorials - Learning resources and skill development content
4. user_tutorial_progress - Individual learning progress and completion tracking

Laravel System Tables:
5. password_reset_tokens - Password reset functionality
6. sessions - User session management
7. cache & cache_locks - Application caching
8. jobs, job_batches, failed_jobs - Queue management

Key Features:
- MBTI personality integration in users table
- Comprehensive progress tracking for assessments and tutorials
- Flexible tutorial system with ratings, prerequisites, and bookmarks
- Full Laravel authentication and session management
- Queue system for background processing
- Caching system for performance optimization
*/