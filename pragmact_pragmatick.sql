-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 26, 2026 at 11:24 PM
-- Server version: 10.11.19-MariaDB
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pragmact_pragmatick`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `subject_type`, `subject_id`, `properties`, `created_at`, `updated_at`) VALUES
(1, 1, 'created', 'Created organization PragmaCTO', 'App\\Models\\Organization', 1, NULL, '2026-08-24 20:21:00', '2026-08-24 20:21:00'),
(2, 1, 'created', 'Created organization TD Consulting Group', 'App\\Models\\Organization', 2, NULL, '2026-08-24 20:21:35', '2026-08-24 20:21:35'),
(3, 1, 'updated', 'Updated organization details for TD Consulting Group', 'App\\Models\\Organization', 2, NULL, '2026-08-24 20:21:51', '2026-08-24 20:21:51'),
(4, 1, 'created_user', 'Physically created user account \'Nawadit Sharma\' (nawadit.sharma@pragmacto.com)', 'App\\Models\\User', 2, NULL, '2026-08-24 20:22:06', '2026-08-24 20:22:06'),
(5, 1, 'created', 'Created organization Triporah Nepal', 'App\\Models\\Organization', 3, NULL, '2026-08-24 20:22:33', '2026-08-24 20:22:33'),
(6, 1, 'created', 'Created organization AskMeNepal', 'App\\Models\\Organization', 4, NULL, '2026-08-24 20:23:04', '2026-08-24 20:23:04'),
(7, 1, 'updated', 'Updated organization details for AskMeNepal', 'App\\Models\\Organization', 4, NULL, '2026-08-24 20:23:39', '2026-08-24 20:23:39'),
(8, 1, 'created', 'Created project Entervu - Website & Backend (EWB)', 'App\\Models\\Project', 1, NULL, '2026-08-24 20:25:32', '2026-08-24 20:25:32'),
(9, 1, 'created', 'Created project Entervu - Application & Enterprise (EAE)', 'App\\Models\\Project', 2, NULL, '2026-08-24 20:26:47', '2026-08-24 20:26:47'),
(10, 1, 'created', 'Created project Entervu - Application & Enterprise (EAE)', 'App\\Models\\Project', 3, NULL, '2026-08-24 20:26:51', '2026-08-24 20:26:51'),
(11, 2, 'created', 'Created checklist item \'Remind Sandeep dai about the isses\'', 'App\\Models\\ChecklistItem', 1, NULL, '2026-08-24 20:34:24', '2026-08-24 20:34:24'),
(12, 2, 'created', 'Created external contact \'Test Name\' (Test Comapny)', 'App\\Models\\Contact', 1, NULL, '2026-08-24 21:24:28', '2026-08-24 21:24:28'),
(13, 2, 'updated_status', 'Moved checklist item \'Remind Sandeep dai about the isses\' to Completed', 'App\\Models\\ChecklistItem', 1, NULL, '2026-08-24 21:24:36', '2026-08-24 21:24:36'),
(14, 2, 'updated_status', 'Moved checklist item \'Remind Sandeep dai about the isses\' to In-Progress', 'App\\Models\\ChecklistItem', 1, NULL, '2026-08-24 21:24:41', '2026-08-24 21:24:41'),
(15, 2, 'updated_status', 'Moved checklist item \'Remind Sandeep dai about the isses\' to Completed', 'App\\Models\\ChecklistItem', 1, NULL, '2026-08-24 21:24:43', '2026-08-24 21:24:43'),
(16, 1, 'created', 'Created Wiki Book \'test\'', 'App\\Models\\WikiBook', 1, NULL, '2026-08-24 21:44:41', '2026-08-24 21:44:41'),
(17, 1, 'created', 'Created Wiki Chapter \'tesasd\' in book test', 'App\\Models\\WikiChapter', 1, NULL, '2026-08-24 21:44:53', '2026-08-24 21:44:53'),
(18, 1, 'created', 'Created Wiki Page \'sadasdadsasd\'', 'App\\Models\\WikiPage', 1, NULL, '2026-08-24 21:57:29', '2026-08-24 21:57:29'),
(19, 1, 'created', 'Created task EWB-1: sad', 'App\\Models\\Task', 1, '{\"type\":\"feature\",\"priority\":\"medium\",\"status\":\"New\"}', '2026-08-25 04:53:25', '2026-08-25 04:53:25'),
(20, 1, 'commented', 'Added a comment on task EWB-1', 'App\\Models\\Task', 1, NULL, '2026-08-25 04:56:19', '2026-08-25 04:56:19'),
(21, 1, 'deleted', 'Deleted a comment', 'App\\Models\\Task', 1, NULL, '2026-08-25 04:56:23', '2026-08-25 04:56:23'),
(22, 1, 'commented', 'Added a comment on task EWB-1', 'App\\Models\\Task', 1, NULL, '2026-08-25 04:56:28', '2026-08-25 04:56:28'),
(23, 1, 'updated', 'Updated their comment', 'App\\Models\\Task', 1, NULL, '2026-08-25 04:56:33', '2026-08-25 04:56:33'),
(24, 1, 'commented', 'Added a comment on task EWB-1', 'App\\Models\\Task', 1, NULL, '2026-08-25 04:56:47', '2026-08-25 04:56:47'),
(25, 1, 'commented', 'Added a comment on task EWB-1', 'App\\Models\\Task', 1, NULL, '2026-08-25 04:57:01', '2026-08-25 04:57:01'),
(26, 1, 'created', 'Created checklist item \'reasd\'', 'App\\Models\\ChecklistItem', 2, NULL, '2026-08-25 04:57:17', '2026-08-25 04:57:17'),
(27, 1, 'commented', 'Added a comment on checklist item \'reasd\'', 'App\\Models\\ChecklistItem', 2, NULL, '2026-08-25 04:57:23', '2026-08-25 04:57:23'),
(28, 1, 'commented', 'Added a comment on checklist item \'reasd\'', 'App\\Models\\ChecklistItem', 2, NULL, '2026-08-25 05:00:56', '2026-08-25 05:00:56'),
(29, 1, 'created', 'Created project Vantage AMT (VAMT)', 'App\\Models\\Project', 4, NULL, '2026-08-25 05:08:31', '2026-08-25 05:08:31'),
(30, 1, 'created', 'Created task VAMT-1: test1', 'App\\Models\\Task', 2, '{\"type\":\"feature\",\"priority\":\"medium\",\"status\":\"New\"}', '2026-08-25 05:08:51', '2026-08-25 05:08:51'),
(31, 1, 'created', 'Created task VAMT-2: tes', 'App\\Models\\Task', 3, '{\"type\":\"bug\",\"priority\":\"urgent\",\"status\":\"In-Review\"}', '2026-08-25 05:09:39', '2026-08-25 05:09:39'),
(32, 1, 'created', 'Created task VAMT-3: d', 'App\\Models\\Task', 4, '{\"type\":\"documentation\",\"priority\":\"medium\",\"status\":\"New\"}', '2026-08-25 05:10:57', '2026-08-25 05:10:57'),
(33, 1, 'commented', 'Added a comment on task VAMT-2', 'App\\Models\\Task', 3, NULL, '2026-08-25 05:12:28', '2026-08-25 05:12:28'),
(34, 1, 'created', 'Created checklist item \'cc\'', 'App\\Models\\ChecklistItem', 3, NULL, '2026-08-25 05:20:57', '2026-08-25 05:20:57'),
(35, 1, 'updated_status', 'Moved checklist item \'cc\' to In-Progress', 'App\\Models\\ChecklistItem', 3, NULL, '2026-08-25 05:21:09', '2026-08-25 05:21:09'),
(36, 1, 'commented', 'Added a comment on checklist item \'cc\'', 'App\\Models\\ChecklistItem', 3, NULL, '2026-08-25 05:21:30', '2026-08-25 05:21:30'),
(37, 1, 'created_user', 'Physically created user account \'shweta khanal\' (skhanal@devkotalawfirm.com)', 'App\\Models\\User', 3, NULL, '2026-08-25 05:22:43', '2026-08-25 05:22:43'),
(38, 1, 'assigned_role', 'Assigned shweta khanal as org_admin in TD Consulting Group', 'App\\Models\\Organization', 2, '{\"target_user_id\":3,\"role\":\"org_admin\",\"position\":\"Member\"}', '2026-08-25 05:48:09', '2026-08-25 05:48:09'),
(39, 1, 'created_user', 'Physically created user account \'sandeep karki\' (skarki@devkotalawfirm.com)', 'App\\Models\\User', 4, NULL, '2026-08-25 05:48:57', '2026-08-25 05:48:57'),
(40, 1, 'assigned_role', 'Assigned shweta khanal as org_admin in PragmaCTO', 'App\\Models\\Organization', 1, '{\"target_user_id\":3,\"role\":\"org_admin\",\"position\":\"Member\"}', '2026-08-25 05:49:24', '2026-08-25 05:49:24'),
(41, 1, 'assigned_role', 'Assigned shweta khanal as member in Triporah Nepal', 'App\\Models\\Organization', 3, '{\"target_user_id\":3,\"role\":\"member\",\"position\":\"Member\"}', '2026-08-25 05:50:00', '2026-08-25 05:50:00'),
(42, 1, 'assigned_role', 'Assigned shweta khanal as org_admin in Triporah Nepal', 'App\\Models\\Organization', 3, '{\"target_user_id\":3,\"role\":\"org_admin\",\"position\":\"Member\"}', '2026-08-25 05:50:14', '2026-08-25 05:50:14'),
(43, 1, 'assigned_role', 'Assigned sandeep karki as member in Triporah Nepal', 'App\\Models\\Organization', 3, '{\"target_user_id\":4,\"role\":\"member\",\"position\":\"Member\"}', '2026-08-25 05:50:23', '2026-08-25 05:50:23'),
(44, 1, 'assigned_role', 'Assigned sandeep karki as member in Entervu - Website & Backend', 'App\\Models\\Project', 1, '{\"target_user_id\":4,\"role\":\"member\",\"position\":\"Team Member\"}', '2026-08-25 05:52:41', '2026-08-25 05:52:41'),
(45, 1, 'assigned_role', 'Assigned sandeep karki as project_admin in Entervu - Website & Backend', 'App\\Models\\Project', 1, '{\"target_user_id\":4,\"role\":\"project_admin\",\"position\":\"Team Member\"}', '2026-08-25 05:52:52', '2026-08-25 05:52:52'),
(46, 1, 'created', 'Created project vff (DD)', 'App\\Models\\Project', 5, NULL, '2026-08-25 05:54:39', '2026-08-25 05:54:39'),
(47, 2, 'assigned_role', 'Assigned Nawadit Sharma as member in PragmaCTO', 'App\\Models\\Organization', 1, '{\"target_user_id\":2,\"role\":\"member\",\"position\":\"example\"}', '2026-08-25 06:27:26', '2026-08-25 06:27:26'),
(48, 2, 'assigned_role', 'Assigned Nawadit Sharma as member in TD Consulting Group', 'App\\Models\\Organization', 2, '{\"target_user_id\":2,\"role\":\"member\",\"position\":\"test\"}', '2026-08-25 06:27:39', '2026-08-25 06:27:39'),
(49, 2, 'assigned_role', 'Assigned Nawadit Sharma as member in Triporah Nepal', 'App\\Models\\Organization', 3, '{\"target_user_id\":2,\"role\":\"member\",\"position\":\"test\"}', '2026-08-25 06:27:57', '2026-08-25 06:27:57'),
(50, 2, 'assigned_role', 'Assigned Nawadit Sharma as member in AskMeNepal', 'App\\Models\\Organization', 4, '{\"target_user_id\":2,\"role\":\"member\",\"position\":\"test\"}', '2026-08-25 06:28:05', '2026-08-25 06:28:05'),
(51, 2, 'deleted', 'Soft-deleted event \'test\'', 'App\\Models\\CalendarEvent', 4, NULL, '2026-08-25 06:28:43', '2026-08-25 06:28:43'),
(52, 2, 'deleted', 'Soft-deleted event \'RAD updates\'', 'App\\Models\\CalendarEvent', 2, NULL, '2026-08-25 06:29:47', '2026-08-25 06:29:47'),
(53, 2, 'deleted', 'Soft-deleted event \'asdasd\'', 'App\\Models\\CalendarEvent', 5, NULL, '2026-08-25 06:30:04', '2026-08-25 06:30:04'),
(54, 2, 'deleted', 'Soft-deleted event \'Test\'', 'App\\Models\\CalendarEvent', 3, NULL, '2026-08-25 06:30:16', '2026-08-25 06:30:16'),
(55, 2, 'created', 'Created checklist item \'Give new found issues to Sandeep Dai\'', 'App\\Models\\ChecklistItem', 4, NULL, '2026-08-25 06:34:01', '2026-08-25 06:34:01'),
(56, 2, 'updated', 'Updated external contact \'Test Name\'', 'App\\Models\\Contact', 1, NULL, '2026-08-25 06:34:19', '2026-08-25 06:34:19'),
(57, 2, 'created', 'Created Wiki Book \'Test book by Nawadit\'', 'App\\Models\\WikiBook', 2, NULL, '2026-08-25 06:35:12', '2026-08-25 06:35:12'),
(58, 2, 'created', 'Created Wiki Chapter \'test chapter\' in book Test book by Nawadit', 'App\\Models\\WikiChapter', 2, NULL, '2026-08-25 06:35:30', '2026-08-25 06:35:30'),
(59, 2, 'created', 'Created Wiki Page \'test page title\'', 'App\\Models\\WikiPage', 2, NULL, '2026-08-25 06:37:14', '2026-08-25 06:37:14'),
(60, 1, 'moved_task', 'Moved task EWB-1 from New to In-Progress', 'App\\Models\\Task', 1, '{\"old_status\":\"New\",\"new_status\":\"In-Progress\"}', '2026-08-25 07:37:28', '2026-08-25 07:37:28'),
(61, 1, 'created', 'Added custom Kanban column status \'tes\' to project Entervu - Website & Backend', 'App\\Models\\Project', 1, NULL, '2026-08-25 07:37:40', '2026-08-25 07:37:40'),
(62, 1, 'moved_task', 'Moved task EWB-1 from In-Progress to Completed', 'App\\Models\\Task', 1, '{\"old_status\":\"In-Progress\",\"new_status\":\"Completed\"}', '2026-08-25 07:37:50', '2026-08-25 07:37:50'),
(63, 1, 'moved_task', 'Moved task EWB-1 from Completed to tes', 'App\\Models\\Task', 1, '{\"old_status\":\"Completed\",\"new_status\":\"tes\"}', '2026-08-25 07:37:55', '2026-08-25 07:37:55'),
(64, 1, 'moved_task', 'Moved task EWB-1 from tes to Completed', 'App\\Models\\Task', 1, '{\"old_status\":\"tes\",\"new_status\":\"Completed\"}', '2026-08-25 07:38:18', '2026-08-25 07:38:18'),
(65, 1, 'moved_task', 'Moved task EWB-1 from Completed to In-Progress', 'App\\Models\\Task', 1, '{\"old_status\":\"Completed\",\"new_status\":\"In-Progress\"}', '2026-08-25 07:38:22', '2026-08-25 07:38:22'),
(66, 2, 'updated_status', 'Moved checklist item \'Give new found issues to Sandeep Dai\' to Completed', 'App\\Models\\ChecklistItem', 4, NULL, '2026-08-25 08:50:41', '2026-08-25 08:50:41'),
(67, 2, 'created', 'Created milestone test milestone', 'App\\Models\\Milestone', 1, NULL, '2026-08-25 08:52:38', '2026-08-25 08:52:38'),
(68, 2, 'moved_task', 'Moved task EWB-1 from In-Progress to In-Review', 'App\\Models\\Task', 1, '{\"old_status\":\"In-Progress\",\"new_status\":\"In-Review\"}', '2026-08-25 08:53:29', '2026-08-25 08:53:29'),
(69, 2, 'deleted', 'Deleted custom Kanban column status \'In-Review\' from project Entervu - Website & Backend', 'App\\Models\\Project', 1, NULL, '2026-08-25 08:53:38', '2026-08-25 08:53:38'),
(70, 2, 'created', 'Added custom Kanban column status \'In-Review\' to project Entervu - Website & Backend', 'App\\Models\\Project', 1, NULL, '2026-08-25 08:54:19', '2026-08-25 08:54:19'),
(71, 1, 'removed_member', 'Removed sandeep karki from Triporah Nepal', 'App\\Models\\Organization', 3, NULL, '2026-08-25 10:05:25', '2026-08-25 10:05:25'),
(72, 2, 'created', 'Created checklist item \'Get follow up timings form HImanshu\'', 'App\\Models\\ChecklistItem', 5, NULL, '2026-08-25 19:24:17', '2026-08-25 19:24:17'),
(73, 2, 'created', 'Created checklist item \'Notify Kishant Balami\'', 'App\\Models\\ChecklistItem', 6, NULL, '2026-08-25 19:25:10', '2026-08-25 19:25:10'),
(74, 2, 'created', 'Created checklist item \'Get timings from Sandeep dai\'', 'App\\Models\\ChecklistItem', 7, NULL, '2026-08-25 19:26:20', '2026-08-25 19:26:20'),
(75, 4, 'created', 'Created Wiki Book \'edcd\'', 'App\\Models\\WikiBook', 3, NULL, '2026-08-26 07:14:56', '2026-08-26 07:14:56'),
(76, 2, 'scheduled_event', 'Scheduled event \'Account\'', 'App\\Models\\CalendarEvent', 6, NULL, '2026-08-26 07:15:00', '2026-08-26 07:15:00'),
(77, 2, 'updated_status', 'Moved checklist item \'Notify Kishant Balami\' to In-Progress', 'App\\Models\\ChecklistItem', 6, NULL, '2026-08-26 07:17:11', '2026-08-26 07:17:11'),
(78, 2, 'updated_status', 'Moved checklist item \'Notify Kishant Balami\' to In-Progress', 'App\\Models\\ChecklistItem', 6, NULL, '2026-08-26 07:17:11', '2026-08-26 07:17:11'),
(79, 2, 'updated_status', 'Moved checklist item \'Get timings from Sandeep dai\' to Completed', 'App\\Models\\ChecklistItem', 7, NULL, '2026-08-26 07:17:21', '2026-08-26 07:17:21'),
(80, 2, 'created', 'Created checklist item \'Create a time for Sandeep Dai and Himanshu Dai\'s follow up on VOIP PhoneTree strategy\'', 'App\\Models\\ChecklistItem', 8, NULL, '2026-08-26 07:18:02', '2026-08-26 07:18:02'),
(81, 2, 'created', 'Created checklist item \'Find and create a list of things HImanshu dai will say to Tarak Devkota on their call on Thursday\'', 'App\\Models\\ChecklistItem', 9, NULL, '2026-08-26 07:18:59', '2026-08-26 07:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-system_timezones', 'a:419:{i:0;a:4:{s:2:\"id\";s:14:\"Africa/Abidjan\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:26:\"(UTC+00:00) Africa/Abidjan\";s:6:\"search\";s:39:\"africa/abidjan abidjan africa utc+00:00\";}i:1;a:4:{s:2:\"id\";s:12:\"Africa/Accra\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:24:\"(UTC+00:00) Africa/Accra\";s:6:\"search\";s:35:\"africa/accra accra africa utc+00:00\";}i:2;a:4:{s:2:\"id\";s:18:\"Africa/Addis_Ababa\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:30:\"(UTC+03:00) Africa/Addis_Ababa\";s:6:\"search\";s:47:\"africa/addis_ababa addis ababa africa utc+03:00\";}i:3;a:4:{s:2:\"id\";s:14:\"Africa/Algiers\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:26:\"(UTC+01:00) Africa/Algiers\";s:6:\"search\";s:39:\"africa/algiers algiers africa utc+01:00\";}i:4;a:4:{s:2:\"id\";s:13:\"Africa/Asmara\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:25:\"(UTC+03:00) Africa/Asmara\";s:6:\"search\";s:37:\"africa/asmara asmara africa utc+03:00\";}i:5;a:4:{s:2:\"id\";s:13:\"Africa/Bamako\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:25:\"(UTC+00:00) Africa/Bamako\";s:6:\"search\";s:37:\"africa/bamako bamako africa utc+00:00\";}i:6;a:4:{s:2:\"id\";s:13:\"Africa/Bangui\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:25:\"(UTC+01:00) Africa/Bangui\";s:6:\"search\";s:37:\"africa/bangui bangui africa utc+01:00\";}i:7;a:4:{s:2:\"id\";s:13:\"Africa/Banjul\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:25:\"(UTC+00:00) Africa/Banjul\";s:6:\"search\";s:37:\"africa/banjul banjul africa utc+00:00\";}i:8;a:4:{s:2:\"id\";s:13:\"Africa/Bissau\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:25:\"(UTC+00:00) Africa/Bissau\";s:6:\"search\";s:37:\"africa/bissau bissau africa utc+00:00\";}i:9;a:4:{s:2:\"id\";s:15:\"Africa/Blantyre\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:27:\"(UTC+02:00) Africa/Blantyre\";s:6:\"search\";s:41:\"africa/blantyre blantyre africa utc+02:00\";}i:10;a:4:{s:2:\"id\";s:18:\"Africa/Brazzaville\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:30:\"(UTC+01:00) Africa/Brazzaville\";s:6:\"search\";s:47:\"africa/brazzaville brazzaville africa utc+01:00\";}i:11;a:4:{s:2:\"id\";s:16:\"Africa/Bujumbura\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:28:\"(UTC+02:00) Africa/Bujumbura\";s:6:\"search\";s:43:\"africa/bujumbura bujumbura africa utc+02:00\";}i:12;a:4:{s:2:\"id\";s:12:\"Africa/Cairo\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:32:\"(UTC+03:00) Africa/Cairo - Egypt\";s:6:\"search\";s:34:\"africa/cairo cairo egypt utc+03:00\";}i:13;a:4:{s:2:\"id\";s:17:\"Africa/Casablanca\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:29:\"(UTC+01:00) Africa/Casablanca\";s:6:\"search\";s:45:\"africa/casablanca casablanca africa utc+01:00\";}i:14;a:4:{s:2:\"id\";s:12:\"Africa/Ceuta\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:24:\"(UTC+02:00) Africa/Ceuta\";s:6:\"search\";s:35:\"africa/ceuta ceuta africa utc+02:00\";}i:15;a:4:{s:2:\"id\";s:14:\"Africa/Conakry\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:26:\"(UTC+00:00) Africa/Conakry\";s:6:\"search\";s:39:\"africa/conakry conakry africa utc+00:00\";}i:16;a:4:{s:2:\"id\";s:12:\"Africa/Dakar\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:24:\"(UTC+00:00) Africa/Dakar\";s:6:\"search\";s:35:\"africa/dakar dakar africa utc+00:00\";}i:17;a:4:{s:2:\"id\";s:20:\"Africa/Dar_es_Salaam\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:32:\"(UTC+03:00) Africa/Dar_es_Salaam\";s:6:\"search\";s:51:\"africa/dar_es_salaam dar es salaam africa utc+03:00\";}i:18;a:4:{s:2:\"id\";s:15:\"Africa/Djibouti\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:27:\"(UTC+03:00) Africa/Djibouti\";s:6:\"search\";s:41:\"africa/djibouti djibouti africa utc+03:00\";}i:19;a:4:{s:2:\"id\";s:13:\"Africa/Douala\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:25:\"(UTC+01:00) Africa/Douala\";s:6:\"search\";s:37:\"africa/douala douala africa utc+01:00\";}i:20;a:4:{s:2:\"id\";s:15:\"Africa/El_Aaiun\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:27:\"(UTC+01:00) Africa/El_Aaiun\";s:6:\"search\";s:41:\"africa/el_aaiun el aaiun africa utc+01:00\";}i:21;a:4:{s:2:\"id\";s:15:\"Africa/Freetown\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:27:\"(UTC+00:00) Africa/Freetown\";s:6:\"search\";s:41:\"africa/freetown freetown africa utc+00:00\";}i:22;a:4:{s:2:\"id\";s:15:\"Africa/Gaborone\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:27:\"(UTC+02:00) Africa/Gaborone\";s:6:\"search\";s:41:\"africa/gaborone gaborone africa utc+02:00\";}i:23;a:4:{s:2:\"id\";s:13:\"Africa/Harare\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Africa/Harare\";s:6:\"search\";s:37:\"africa/harare harare africa utc+02:00\";}i:24;a:4:{s:2:\"id\";s:19:\"Africa/Johannesburg\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:46:\"(UTC+02:00) Africa/Johannesburg - South Africa\";s:6:\"search\";s:55:\"africa/johannesburg johannesburg south africa utc+02:00\";}i:25;a:4:{s:2:\"id\";s:11:\"Africa/Juba\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:23:\"(UTC+02:00) Africa/Juba\";s:6:\"search\";s:33:\"africa/juba juba africa utc+02:00\";}i:26;a:4:{s:2:\"id\";s:14:\"Africa/Kampala\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:26:\"(UTC+03:00) Africa/Kampala\";s:6:\"search\";s:39:\"africa/kampala kampala africa utc+03:00\";}i:27;a:4:{s:2:\"id\";s:15:\"Africa/Khartoum\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:27:\"(UTC+02:00) Africa/Khartoum\";s:6:\"search\";s:41:\"africa/khartoum khartoum africa utc+02:00\";}i:28;a:4:{s:2:\"id\";s:13:\"Africa/Kigali\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Africa/Kigali\";s:6:\"search\";s:37:\"africa/kigali kigali africa utc+02:00\";}i:29;a:4:{s:2:\"id\";s:15:\"Africa/Kinshasa\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:27:\"(UTC+01:00) Africa/Kinshasa\";s:6:\"search\";s:41:\"africa/kinshasa kinshasa africa utc+01:00\";}i:30;a:4:{s:2:\"id\";s:12:\"Africa/Lagos\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:34:\"(UTC+01:00) Africa/Lagos - Nigeria\";s:6:\"search\";s:36:\"africa/lagos lagos nigeria utc+01:00\";}i:31;a:4:{s:2:\"id\";s:17:\"Africa/Libreville\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:29:\"(UTC+01:00) Africa/Libreville\";s:6:\"search\";s:45:\"africa/libreville libreville africa utc+01:00\";}i:32;a:4:{s:2:\"id\";s:11:\"Africa/Lome\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:23:\"(UTC+00:00) Africa/Lome\";s:6:\"search\";s:33:\"africa/lome lome africa utc+00:00\";}i:33;a:4:{s:2:\"id\";s:13:\"Africa/Luanda\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:25:\"(UTC+01:00) Africa/Luanda\";s:6:\"search\";s:37:\"africa/luanda luanda africa utc+01:00\";}i:34;a:4:{s:2:\"id\";s:17:\"Africa/Lubumbashi\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:29:\"(UTC+02:00) Africa/Lubumbashi\";s:6:\"search\";s:45:\"africa/lubumbashi lubumbashi africa utc+02:00\";}i:35;a:4:{s:2:\"id\";s:13:\"Africa/Lusaka\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Africa/Lusaka\";s:6:\"search\";s:37:\"africa/lusaka lusaka africa utc+02:00\";}i:36;a:4:{s:2:\"id\";s:13:\"Africa/Malabo\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:25:\"(UTC+01:00) Africa/Malabo\";s:6:\"search\";s:37:\"africa/malabo malabo africa utc+01:00\";}i:37;a:4:{s:2:\"id\";s:13:\"Africa/Maputo\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Africa/Maputo\";s:6:\"search\";s:37:\"africa/maputo maputo africa utc+02:00\";}i:38;a:4:{s:2:\"id\";s:13:\"Africa/Maseru\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Africa/Maseru\";s:6:\"search\";s:37:\"africa/maseru maseru africa utc+02:00\";}i:39;a:4:{s:2:\"id\";s:14:\"Africa/Mbabane\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:26:\"(UTC+02:00) Africa/Mbabane\";s:6:\"search\";s:39:\"africa/mbabane mbabane africa utc+02:00\";}i:40;a:4:{s:2:\"id\";s:16:\"Africa/Mogadishu\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:28:\"(UTC+03:00) Africa/Mogadishu\";s:6:\"search\";s:43:\"africa/mogadishu mogadishu africa utc+03:00\";}i:41;a:4:{s:2:\"id\";s:15:\"Africa/Monrovia\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:27:\"(UTC+00:00) Africa/Monrovia\";s:6:\"search\";s:41:\"africa/monrovia monrovia africa utc+00:00\";}i:42;a:4:{s:2:\"id\";s:14:\"Africa/Nairobi\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:34:\"(UTC+03:00) Africa/Nairobi - Kenya\";s:6:\"search\";s:38:\"africa/nairobi nairobi kenya utc+03:00\";}i:43;a:4:{s:2:\"id\";s:15:\"Africa/Ndjamena\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:27:\"(UTC+01:00) Africa/Ndjamena\";s:6:\"search\";s:41:\"africa/ndjamena ndjamena africa utc+01:00\";}i:44;a:4:{s:2:\"id\";s:13:\"Africa/Niamey\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:25:\"(UTC+01:00) Africa/Niamey\";s:6:\"search\";s:37:\"africa/niamey niamey africa utc+01:00\";}i:45;a:4:{s:2:\"id\";s:17:\"Africa/Nouakchott\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:29:\"(UTC+00:00) Africa/Nouakchott\";s:6:\"search\";s:45:\"africa/nouakchott nouakchott africa utc+00:00\";}i:46;a:4:{s:2:\"id\";s:18:\"Africa/Ouagadougou\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:30:\"(UTC+00:00) Africa/Ouagadougou\";s:6:\"search\";s:47:\"africa/ouagadougou ouagadougou africa utc+00:00\";}i:47;a:4:{s:2:\"id\";s:17:\"Africa/Porto-Novo\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:29:\"(UTC+01:00) Africa/Porto-Novo\";s:6:\"search\";s:45:\"africa/porto-novo porto-novo africa utc+01:00\";}i:48;a:4:{s:2:\"id\";s:15:\"Africa/Sao_Tome\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:27:\"(UTC+00:00) Africa/Sao_Tome\";s:6:\"search\";s:41:\"africa/sao_tome sao tome africa utc+00:00\";}i:49;a:4:{s:2:\"id\";s:14:\"Africa/Tripoli\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:26:\"(UTC+02:00) Africa/Tripoli\";s:6:\"search\";s:39:\"africa/tripoli tripoli africa utc+02:00\";}i:50;a:4:{s:2:\"id\";s:12:\"Africa/Tunis\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:24:\"(UTC+01:00) Africa/Tunis\";s:6:\"search\";s:35:\"africa/tunis tunis africa utc+01:00\";}i:51;a:4:{s:2:\"id\";s:15:\"Africa/Windhoek\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:27:\"(UTC+02:00) Africa/Windhoek\";s:6:\"search\";s:41:\"africa/windhoek windhoek africa utc+02:00\";}i:52;a:4:{s:2:\"id\";s:12:\"America/Adak\";s:6:\"offset\";s:9:\"UTC-09:00\";s:5:\"label\";s:24:\"(UTC-09:00) America/Adak\";s:6:\"search\";s:35:\"america/adak adak america utc-09:00\";}i:53;a:4:{s:2:\"id\";s:17:\"America/Anchorage\";s:6:\"offset\";s:9:\"UTC-08:00\";s:5:\"label\";s:29:\"(UTC-08:00) America/Anchorage\";s:6:\"search\";s:45:\"america/anchorage anchorage america utc-08:00\";}i:54;a:4:{s:2:\"id\";s:16:\"America/Anguilla\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:28:\"(UTC-04:00) America/Anguilla\";s:6:\"search\";s:43:\"america/anguilla anguilla america utc-04:00\";}i:55;a:4:{s:2:\"id\";s:15:\"America/Antigua\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:27:\"(UTC-04:00) America/Antigua\";s:6:\"search\";s:41:\"america/antigua antigua america utc-04:00\";}i:56;a:4:{s:2:\"id\";s:17:\"America/Araguaina\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:29:\"(UTC-03:00) America/Araguaina\";s:6:\"search\";s:45:\"america/araguaina araguaina america utc-03:00\";}i:57;a:4:{s:2:\"id\";s:30:\"America/Argentina/Buenos_Aires\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:42:\"(UTC-03:00) America/Argentina/Buenos_Aires\";s:6:\"search\";s:58:\"america/argentina/buenos_aires argentina america utc-03:00\";}i:58;a:4:{s:2:\"id\";s:27:\"America/Argentina/Catamarca\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:39:\"(UTC-03:00) America/Argentina/Catamarca\";s:6:\"search\";s:55:\"america/argentina/catamarca argentina america utc-03:00\";}i:59;a:4:{s:2:\"id\";s:25:\"America/Argentina/Cordoba\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:37:\"(UTC-03:00) America/Argentina/Cordoba\";s:6:\"search\";s:53:\"america/argentina/cordoba argentina america utc-03:00\";}i:60;a:4:{s:2:\"id\";s:23:\"America/Argentina/Jujuy\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:35:\"(UTC-03:00) America/Argentina/Jujuy\";s:6:\"search\";s:51:\"america/argentina/jujuy argentina america utc-03:00\";}i:61;a:4:{s:2:\"id\";s:26:\"America/Argentina/La_Rioja\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:38:\"(UTC-03:00) America/Argentina/La_Rioja\";s:6:\"search\";s:54:\"america/argentina/la_rioja argentina america utc-03:00\";}i:62;a:4:{s:2:\"id\";s:25:\"America/Argentina/Mendoza\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:37:\"(UTC-03:00) America/Argentina/Mendoza\";s:6:\"search\";s:53:\"america/argentina/mendoza argentina america utc-03:00\";}i:63;a:4:{s:2:\"id\";s:30:\"America/Argentina/Rio_Gallegos\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:42:\"(UTC-03:00) America/Argentina/Rio_Gallegos\";s:6:\"search\";s:58:\"america/argentina/rio_gallegos argentina america utc-03:00\";}i:64;a:4:{s:2:\"id\";s:23:\"America/Argentina/Salta\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:35:\"(UTC-03:00) America/Argentina/Salta\";s:6:\"search\";s:51:\"america/argentina/salta argentina america utc-03:00\";}i:65;a:4:{s:2:\"id\";s:26:\"America/Argentina/San_Juan\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:38:\"(UTC-03:00) America/Argentina/San_Juan\";s:6:\"search\";s:54:\"america/argentina/san_juan argentina america utc-03:00\";}i:66;a:4:{s:2:\"id\";s:26:\"America/Argentina/San_Luis\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:38:\"(UTC-03:00) America/Argentina/San_Luis\";s:6:\"search\";s:54:\"america/argentina/san_luis argentina america utc-03:00\";}i:67;a:4:{s:2:\"id\";s:25:\"America/Argentina/Tucuman\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:37:\"(UTC-03:00) America/Argentina/Tucuman\";s:6:\"search\";s:53:\"america/argentina/tucuman argentina america utc-03:00\";}i:68;a:4:{s:2:\"id\";s:25:\"America/Argentina/Ushuaia\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:37:\"(UTC-03:00) America/Argentina/Ushuaia\";s:6:\"search\";s:53:\"america/argentina/ushuaia argentina america utc-03:00\";}i:69;a:4:{s:2:\"id\";s:13:\"America/Aruba\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:25:\"(UTC-04:00) America/Aruba\";s:6:\"search\";s:37:\"america/aruba aruba america utc-04:00\";}i:70;a:4:{s:2:\"id\";s:16:\"America/Asuncion\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:28:\"(UTC-03:00) America/Asuncion\";s:6:\"search\";s:43:\"america/asuncion asuncion america utc-03:00\";}i:71;a:4:{s:2:\"id\";s:16:\"America/Atikokan\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:28:\"(UTC-05:00) America/Atikokan\";s:6:\"search\";s:43:\"america/atikokan atikokan america utc-05:00\";}i:72;a:4:{s:2:\"id\";s:13:\"America/Bahia\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:25:\"(UTC-03:00) America/Bahia\";s:6:\"search\";s:37:\"america/bahia bahia america utc-03:00\";}i:73;a:4:{s:2:\"id\";s:22:\"America/Bahia_Banderas\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:34:\"(UTC-06:00) America/Bahia_Banderas\";s:6:\"search\";s:55:\"america/bahia_banderas bahia banderas america utc-06:00\";}i:74;a:4:{s:2:\"id\";s:16:\"America/Barbados\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:28:\"(UTC-04:00) America/Barbados\";s:6:\"search\";s:43:\"america/barbados barbados america utc-04:00\";}i:75;a:4:{s:2:\"id\";s:13:\"America/Belem\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:25:\"(UTC-03:00) America/Belem\";s:6:\"search\";s:37:\"america/belem belem america utc-03:00\";}i:76;a:4:{s:2:\"id\";s:14:\"America/Belize\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:26:\"(UTC-06:00) America/Belize\";s:6:\"search\";s:39:\"america/belize belize america utc-06:00\";}i:77;a:4:{s:2:\"id\";s:20:\"America/Blanc-Sablon\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:32:\"(UTC-04:00) America/Blanc-Sablon\";s:6:\"search\";s:51:\"america/blanc-sablon blanc-sablon america utc-04:00\";}i:78;a:4:{s:2:\"id\";s:17:\"America/Boa_Vista\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:29:\"(UTC-04:00) America/Boa_Vista\";s:6:\"search\";s:45:\"america/boa_vista boa vista america utc-04:00\";}i:79;a:4:{s:2:\"id\";s:14:\"America/Bogota\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:26:\"(UTC-05:00) America/Bogota\";s:6:\"search\";s:39:\"america/bogota bogota america utc-05:00\";}i:80;a:4:{s:2:\"id\";s:13:\"America/Boise\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:25:\"(UTC-06:00) America/Boise\";s:6:\"search\";s:37:\"america/boise boise america utc-06:00\";}i:81;a:4:{s:2:\"id\";s:21:\"America/Cambridge_Bay\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:33:\"(UTC-06:00) America/Cambridge_Bay\";s:6:\"search\";s:53:\"america/cambridge_bay cambridge bay america utc-06:00\";}i:82;a:4:{s:2:\"id\";s:20:\"America/Campo_Grande\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:32:\"(UTC-04:00) America/Campo_Grande\";s:6:\"search\";s:51:\"america/campo_grande campo grande america utc-04:00\";}i:83;a:4:{s:2:\"id\";s:14:\"America/Cancun\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:26:\"(UTC-05:00) America/Cancun\";s:6:\"search\";s:39:\"america/cancun cancun america utc-05:00\";}i:84;a:4:{s:2:\"id\";s:15:\"America/Caracas\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:27:\"(UTC-04:00) America/Caracas\";s:6:\"search\";s:41:\"america/caracas caracas america utc-04:00\";}i:85;a:4:{s:2:\"id\";s:15:\"America/Cayenne\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:27:\"(UTC-03:00) America/Cayenne\";s:6:\"search\";s:41:\"america/cayenne cayenne america utc-03:00\";}i:86;a:4:{s:2:\"id\";s:14:\"America/Cayman\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:26:\"(UTC-05:00) America/Cayman\";s:6:\"search\";s:39:\"america/cayman cayman america utc-05:00\";}i:87;a:4:{s:2:\"id\";s:15:\"America/Chicago\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:62:\"(UTC-05:00) America/Chicago - United States US USA Central CST\";s:6:\"search\";s:66:\"america/chicago chicago united states us usa central cst utc-05:00\";}i:88;a:4:{s:2:\"id\";s:17:\"America/Chihuahua\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:29:\"(UTC-06:00) America/Chihuahua\";s:6:\"search\";s:45:\"america/chihuahua chihuahua america utc-06:00\";}i:89;a:4:{s:2:\"id\";s:21:\"America/Ciudad_Juarez\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:33:\"(UTC-06:00) America/Ciudad_Juarez\";s:6:\"search\";s:53:\"america/ciudad_juarez ciudad juarez america utc-06:00\";}i:90;a:4:{s:2:\"id\";s:18:\"America/Costa_Rica\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:30:\"(UTC-06:00) America/Costa_Rica\";s:6:\"search\";s:47:\"america/costa_rica costa rica america utc-06:00\";}i:91;a:4:{s:2:\"id\";s:17:\"America/Coyhaique\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:29:\"(UTC-03:00) America/Coyhaique\";s:6:\"search\";s:45:\"america/coyhaique coyhaique america utc-03:00\";}i:92;a:4:{s:2:\"id\";s:15:\"America/Creston\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:27:\"(UTC-07:00) America/Creston\";s:6:\"search\";s:41:\"america/creston creston america utc-07:00\";}i:93;a:4:{s:2:\"id\";s:14:\"America/Cuiaba\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:26:\"(UTC-04:00) America/Cuiaba\";s:6:\"search\";s:39:\"america/cuiaba cuiaba america utc-04:00\";}i:94;a:4:{s:2:\"id\";s:15:\"America/Curacao\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:27:\"(UTC-04:00) America/Curacao\";s:6:\"search\";s:41:\"america/curacao curacao america utc-04:00\";}i:95;a:4:{s:2:\"id\";s:20:\"America/Danmarkshavn\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:32:\"(UTC+00:00) America/Danmarkshavn\";s:6:\"search\";s:51:\"america/danmarkshavn danmarkshavn america utc+00:00\";}i:96;a:4:{s:2:\"id\";s:14:\"America/Dawson\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:26:\"(UTC-07:00) America/Dawson\";s:6:\"search\";s:39:\"america/dawson dawson america utc-07:00\";}i:97;a:4:{s:2:\"id\";s:20:\"America/Dawson_Creek\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:32:\"(UTC-07:00) America/Dawson_Creek\";s:6:\"search\";s:51:\"america/dawson_creek dawson creek america utc-07:00\";}i:98;a:4:{s:2:\"id\";s:14:\"America/Denver\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:62:\"(UTC-06:00) America/Denver - United States US USA Mountain MST\";s:6:\"search\";s:65:\"america/denver denver united states us usa mountain mst utc-06:00\";}i:99;a:4:{s:2:\"id\";s:15:\"America/Detroit\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:27:\"(UTC-04:00) America/Detroit\";s:6:\"search\";s:41:\"america/detroit detroit america utc-04:00\";}i:100;a:4:{s:2:\"id\";s:16:\"America/Dominica\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:28:\"(UTC-04:00) America/Dominica\";s:6:\"search\";s:43:\"america/dominica dominica america utc-04:00\";}i:101;a:4:{s:2:\"id\";s:16:\"America/Edmonton\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:28:\"(UTC-06:00) America/Edmonton\";s:6:\"search\";s:43:\"america/edmonton edmonton america utc-06:00\";}i:102;a:4:{s:2:\"id\";s:16:\"America/Eirunepe\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:28:\"(UTC-05:00) America/Eirunepe\";s:6:\"search\";s:43:\"america/eirunepe eirunepe america utc-05:00\";}i:103;a:4:{s:2:\"id\";s:19:\"America/El_Salvador\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:31:\"(UTC-06:00) America/El_Salvador\";s:6:\"search\";s:49:\"america/el_salvador el salvador america utc-06:00\";}i:104;a:4:{s:2:\"id\";s:19:\"America/Fort_Nelson\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:31:\"(UTC-07:00) America/Fort_Nelson\";s:6:\"search\";s:49:\"america/fort_nelson fort nelson america utc-07:00\";}i:105;a:4:{s:2:\"id\";s:17:\"America/Fortaleza\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:29:\"(UTC-03:00) America/Fortaleza\";s:6:\"search\";s:45:\"america/fortaleza fortaleza america utc-03:00\";}i:106;a:4:{s:2:\"id\";s:17:\"America/Glace_Bay\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:29:\"(UTC-03:00) America/Glace_Bay\";s:6:\"search\";s:45:\"america/glace_bay glace bay america utc-03:00\";}i:107;a:4:{s:2:\"id\";s:17:\"America/Goose_Bay\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:29:\"(UTC-03:00) America/Goose_Bay\";s:6:\"search\";s:45:\"america/goose_bay goose bay america utc-03:00\";}i:108;a:4:{s:2:\"id\";s:18:\"America/Grand_Turk\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:30:\"(UTC-04:00) America/Grand_Turk\";s:6:\"search\";s:47:\"america/grand_turk grand turk america utc-04:00\";}i:109;a:4:{s:2:\"id\";s:15:\"America/Grenada\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:27:\"(UTC-04:00) America/Grenada\";s:6:\"search\";s:41:\"america/grenada grenada america utc-04:00\";}i:110;a:4:{s:2:\"id\";s:18:\"America/Guadeloupe\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:30:\"(UTC-04:00) America/Guadeloupe\";s:6:\"search\";s:47:\"america/guadeloupe guadeloupe america utc-04:00\";}i:111;a:4:{s:2:\"id\";s:17:\"America/Guatemala\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:29:\"(UTC-06:00) America/Guatemala\";s:6:\"search\";s:45:\"america/guatemala guatemala america utc-06:00\";}i:112;a:4:{s:2:\"id\";s:17:\"America/Guayaquil\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:29:\"(UTC-05:00) America/Guayaquil\";s:6:\"search\";s:45:\"america/guayaquil guayaquil america utc-05:00\";}i:113;a:4:{s:2:\"id\";s:14:\"America/Guyana\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:26:\"(UTC-04:00) America/Guyana\";s:6:\"search\";s:39:\"america/guyana guyana america utc-04:00\";}i:114;a:4:{s:2:\"id\";s:15:\"America/Halifax\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:27:\"(UTC-03:00) America/Halifax\";s:6:\"search\";s:41:\"america/halifax halifax america utc-03:00\";}i:115;a:4:{s:2:\"id\";s:14:\"America/Havana\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:26:\"(UTC-04:00) America/Havana\";s:6:\"search\";s:39:\"america/havana havana america utc-04:00\";}i:116;a:4:{s:2:\"id\";s:18:\"America/Hermosillo\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:30:\"(UTC-07:00) America/Hermosillo\";s:6:\"search\";s:47:\"america/hermosillo hermosillo america utc-07:00\";}i:117;a:4:{s:2:\"id\";s:28:\"America/Indiana/Indianapolis\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:40:\"(UTC-04:00) America/Indiana/Indianapolis\";s:6:\"search\";s:54:\"america/indiana/indianapolis indiana america utc-04:00\";}i:118;a:4:{s:2:\"id\";s:20:\"America/Indiana/Knox\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:32:\"(UTC-05:00) America/Indiana/Knox\";s:6:\"search\";s:46:\"america/indiana/knox indiana america utc-05:00\";}i:119;a:4:{s:2:\"id\";s:23:\"America/Indiana/Marengo\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:35:\"(UTC-04:00) America/Indiana/Marengo\";s:6:\"search\";s:49:\"america/indiana/marengo indiana america utc-04:00\";}i:120;a:4:{s:2:\"id\";s:26:\"America/Indiana/Petersburg\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:38:\"(UTC-04:00) America/Indiana/Petersburg\";s:6:\"search\";s:52:\"america/indiana/petersburg indiana america utc-04:00\";}i:121;a:4:{s:2:\"id\";s:25:\"America/Indiana/Tell_City\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:37:\"(UTC-05:00) America/Indiana/Tell_City\";s:6:\"search\";s:51:\"america/indiana/tell_city indiana america utc-05:00\";}i:122;a:4:{s:2:\"id\";s:21:\"America/Indiana/Vevay\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:33:\"(UTC-04:00) America/Indiana/Vevay\";s:6:\"search\";s:47:\"america/indiana/vevay indiana america utc-04:00\";}i:123;a:4:{s:2:\"id\";s:25:\"America/Indiana/Vincennes\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:37:\"(UTC-04:00) America/Indiana/Vincennes\";s:6:\"search\";s:51:\"america/indiana/vincennes indiana america utc-04:00\";}i:124;a:4:{s:2:\"id\";s:23:\"America/Indiana/Winamac\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:35:\"(UTC-04:00) America/Indiana/Winamac\";s:6:\"search\";s:49:\"america/indiana/winamac indiana america utc-04:00\";}i:125;a:4:{s:2:\"id\";s:14:\"America/Inuvik\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:26:\"(UTC-06:00) America/Inuvik\";s:6:\"search\";s:39:\"america/inuvik inuvik america utc-06:00\";}i:126;a:4:{s:2:\"id\";s:15:\"America/Iqaluit\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:27:\"(UTC-04:00) America/Iqaluit\";s:6:\"search\";s:41:\"america/iqaluit iqaluit america utc-04:00\";}i:127;a:4:{s:2:\"id\";s:15:\"America/Jamaica\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:27:\"(UTC-05:00) America/Jamaica\";s:6:\"search\";s:41:\"america/jamaica jamaica america utc-05:00\";}i:128;a:4:{s:2:\"id\";s:14:\"America/Juneau\";s:6:\"offset\";s:9:\"UTC-08:00\";s:5:\"label\";s:26:\"(UTC-08:00) America/Juneau\";s:6:\"search\";s:39:\"america/juneau juneau america utc-08:00\";}i:129;a:4:{s:2:\"id\";s:27:\"America/Kentucky/Louisville\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:39:\"(UTC-04:00) America/Kentucky/Louisville\";s:6:\"search\";s:54:\"america/kentucky/louisville kentucky america utc-04:00\";}i:130;a:4:{s:2:\"id\";s:27:\"America/Kentucky/Monticello\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:39:\"(UTC-04:00) America/Kentucky/Monticello\";s:6:\"search\";s:54:\"america/kentucky/monticello kentucky america utc-04:00\";}i:131;a:4:{s:2:\"id\";s:18:\"America/Kralendijk\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:30:\"(UTC-04:00) America/Kralendijk\";s:6:\"search\";s:47:\"america/kralendijk kralendijk america utc-04:00\";}i:132;a:4:{s:2:\"id\";s:14:\"America/La_Paz\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:26:\"(UTC-04:00) America/La_Paz\";s:6:\"search\";s:39:\"america/la_paz la paz america utc-04:00\";}i:133;a:4:{s:2:\"id\";s:12:\"America/Lima\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:24:\"(UTC-05:00) America/Lima\";s:6:\"search\";s:35:\"america/lima lima america utc-05:00\";}i:134;a:4:{s:2:\"id\";s:19:\"America/Los_Angeles\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:71:\"(UTC-07:00) America/Los_Angeles - United States US USA West PST Pacific\";s:6:\"search\";s:79:\"america/los_angeles los angeles united states us usa west pst pacific utc-07:00\";}i:135;a:4:{s:2:\"id\";s:21:\"America/Lower_Princes\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:33:\"(UTC-04:00) America/Lower_Princes\";s:6:\"search\";s:53:\"america/lower_princes lower princes america utc-04:00\";}i:136;a:4:{s:2:\"id\";s:14:\"America/Maceio\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:26:\"(UTC-03:00) America/Maceio\";s:6:\"search\";s:39:\"america/maceio maceio america utc-03:00\";}i:137;a:4:{s:2:\"id\";s:15:\"America/Managua\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:27:\"(UTC-06:00) America/Managua\";s:6:\"search\";s:41:\"america/managua managua america utc-06:00\";}i:138;a:4:{s:2:\"id\";s:14:\"America/Manaus\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:26:\"(UTC-04:00) America/Manaus\";s:6:\"search\";s:39:\"america/manaus manaus america utc-04:00\";}i:139;a:4:{s:2:\"id\";s:15:\"America/Marigot\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:27:\"(UTC-04:00) America/Marigot\";s:6:\"search\";s:41:\"america/marigot marigot america utc-04:00\";}i:140;a:4:{s:2:\"id\";s:18:\"America/Martinique\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:30:\"(UTC-04:00) America/Martinique\";s:6:\"search\";s:47:\"america/martinique martinique america utc-04:00\";}i:141;a:4:{s:2:\"id\";s:17:\"America/Matamoros\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:29:\"(UTC-05:00) America/Matamoros\";s:6:\"search\";s:45:\"america/matamoros matamoros america utc-05:00\";}i:142;a:4:{s:2:\"id\";s:16:\"America/Mazatlan\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:28:\"(UTC-07:00) America/Mazatlan\";s:6:\"search\";s:43:\"america/mazatlan mazatlan america utc-07:00\";}i:143;a:4:{s:2:\"id\";s:17:\"America/Menominee\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:29:\"(UTC-05:00) America/Menominee\";s:6:\"search\";s:45:\"america/menominee menominee america utc-05:00\";}i:144;a:4:{s:2:\"id\";s:14:\"America/Merida\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:26:\"(UTC-06:00) America/Merida\";s:6:\"search\";s:39:\"america/merida merida america utc-06:00\";}i:145;a:4:{s:2:\"id\";s:18:\"America/Metlakatla\";s:6:\"offset\";s:9:\"UTC-08:00\";s:5:\"label\";s:30:\"(UTC-08:00) America/Metlakatla\";s:6:\"search\";s:47:\"america/metlakatla metlakatla america utc-08:00\";}i:146;a:4:{s:2:\"id\";s:19:\"America/Mexico_City\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:40:\"(UTC-06:00) America/Mexico_City - Mexico\";s:6:\"search\";s:48:\"america/mexico_city mexico city mexico utc-06:00\";}i:147;a:4:{s:2:\"id\";s:16:\"America/Miquelon\";s:6:\"offset\";s:9:\"UTC-02:00\";s:5:\"label\";s:28:\"(UTC-02:00) America/Miquelon\";s:6:\"search\";s:43:\"america/miquelon miquelon america utc-02:00\";}i:148;a:4:{s:2:\"id\";s:15:\"America/Moncton\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:27:\"(UTC-03:00) America/Moncton\";s:6:\"search\";s:41:\"america/moncton moncton america utc-03:00\";}i:149;a:4:{s:2:\"id\";s:17:\"America/Monterrey\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:29:\"(UTC-06:00) America/Monterrey\";s:6:\"search\";s:45:\"america/monterrey monterrey america utc-06:00\";}i:150;a:4:{s:2:\"id\";s:18:\"America/Montevideo\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:30:\"(UTC-03:00) America/Montevideo\";s:6:\"search\";s:47:\"america/montevideo montevideo america utc-03:00\";}i:151;a:4:{s:2:\"id\";s:18:\"America/Montserrat\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:30:\"(UTC-04:00) America/Montserrat\";s:6:\"search\";s:47:\"america/montserrat montserrat america utc-04:00\";}i:152;a:4:{s:2:\"id\";s:14:\"America/Nassau\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:26:\"(UTC-04:00) America/Nassau\";s:6:\"search\";s:39:\"america/nassau nassau america utc-04:00\";}i:153;a:4:{s:2:\"id\";s:16:\"America/New_York\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:60:\"(UTC-04:00) America/New_York - United States US USA East EST\";s:6:\"search\";s:65:\"america/new_york new york united states us usa east est utc-04:00\";}i:154;a:4:{s:2:\"id\";s:12:\"America/Nome\";s:6:\"offset\";s:9:\"UTC-08:00\";s:5:\"label\";s:24:\"(UTC-08:00) America/Nome\";s:6:\"search\";s:35:\"america/nome nome america utc-08:00\";}i:155;a:4:{s:2:\"id\";s:15:\"America/Noronha\";s:6:\"offset\";s:9:\"UTC-02:00\";s:5:\"label\";s:27:\"(UTC-02:00) America/Noronha\";s:6:\"search\";s:41:\"america/noronha noronha america utc-02:00\";}i:156;a:4:{s:2:\"id\";s:27:\"America/North_Dakota/Beulah\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:39:\"(UTC-05:00) America/North_Dakota/Beulah\";s:6:\"search\";s:58:\"america/north_dakota/beulah north dakota america utc-05:00\";}i:157;a:4:{s:2:\"id\";s:27:\"America/North_Dakota/Center\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:39:\"(UTC-05:00) America/North_Dakota/Center\";s:6:\"search\";s:58:\"america/north_dakota/center north dakota america utc-05:00\";}i:158;a:4:{s:2:\"id\";s:30:\"America/North_Dakota/New_Salem\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:42:\"(UTC-05:00) America/North_Dakota/New_Salem\";s:6:\"search\";s:61:\"america/north_dakota/new_salem north dakota america utc-05:00\";}i:159;a:4:{s:2:\"id\";s:12:\"America/Nuuk\";s:6:\"offset\";s:9:\"UTC-01:00\";s:5:\"label\";s:24:\"(UTC-01:00) America/Nuuk\";s:6:\"search\";s:35:\"america/nuuk nuuk america utc-01:00\";}i:160;a:4:{s:2:\"id\";s:15:\"America/Ojinaga\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:27:\"(UTC-05:00) America/Ojinaga\";s:6:\"search\";s:41:\"america/ojinaga ojinaga america utc-05:00\";}i:161;a:4:{s:2:\"id\";s:14:\"America/Panama\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:26:\"(UTC-05:00) America/Panama\";s:6:\"search\";s:39:\"america/panama panama america utc-05:00\";}i:162;a:4:{s:2:\"id\";s:18:\"America/Paramaribo\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:30:\"(UTC-03:00) America/Paramaribo\";s:6:\"search\";s:47:\"america/paramaribo paramaribo america utc-03:00\";}i:163;a:4:{s:2:\"id\";s:15:\"America/Phoenix\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:27:\"(UTC-07:00) America/Phoenix\";s:6:\"search\";s:41:\"america/phoenix phoenix america utc-07:00\";}i:164;a:4:{s:2:\"id\";s:22:\"America/Port-au-Prince\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:34:\"(UTC-04:00) America/Port-au-Prince\";s:6:\"search\";s:55:\"america/port-au-prince port-au-prince america utc-04:00\";}i:165;a:4:{s:2:\"id\";s:21:\"America/Port_of_Spain\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:33:\"(UTC-04:00) America/Port_of_Spain\";s:6:\"search\";s:53:\"america/port_of_spain port of spain america utc-04:00\";}i:166;a:4:{s:2:\"id\";s:19:\"America/Porto_Velho\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:31:\"(UTC-04:00) America/Porto_Velho\";s:6:\"search\";s:49:\"america/porto_velho porto velho america utc-04:00\";}i:167;a:4:{s:2:\"id\";s:19:\"America/Puerto_Rico\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:31:\"(UTC-04:00) America/Puerto_Rico\";s:6:\"search\";s:49:\"america/puerto_rico puerto rico america utc-04:00\";}i:168;a:4:{s:2:\"id\";s:20:\"America/Punta_Arenas\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:32:\"(UTC-03:00) America/Punta_Arenas\";s:6:\"search\";s:51:\"america/punta_arenas punta arenas america utc-03:00\";}i:169;a:4:{s:2:\"id\";s:20:\"America/Rankin_Inlet\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:32:\"(UTC-05:00) America/Rankin_Inlet\";s:6:\"search\";s:51:\"america/rankin_inlet rankin inlet america utc-05:00\";}i:170;a:4:{s:2:\"id\";s:14:\"America/Recife\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:26:\"(UTC-03:00) America/Recife\";s:6:\"search\";s:39:\"america/recife recife america utc-03:00\";}i:171;a:4:{s:2:\"id\";s:14:\"America/Regina\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:26:\"(UTC-06:00) America/Regina\";s:6:\"search\";s:39:\"america/regina regina america utc-06:00\";}i:172;a:4:{s:2:\"id\";s:16:\"America/Resolute\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:28:\"(UTC-05:00) America/Resolute\";s:6:\"search\";s:43:\"america/resolute resolute america utc-05:00\";}i:173;a:4:{s:2:\"id\";s:18:\"America/Rio_Branco\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:30:\"(UTC-05:00) America/Rio_Branco\";s:6:\"search\";s:47:\"america/rio_branco rio branco america utc-05:00\";}i:174;a:4:{s:2:\"id\";s:16:\"America/Santarem\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:28:\"(UTC-03:00) America/Santarem\";s:6:\"search\";s:43:\"america/santarem santarem america utc-03:00\";}i:175;a:4:{s:2:\"id\";s:16:\"America/Santiago\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:28:\"(UTC-04:00) America/Santiago\";s:6:\"search\";s:43:\"america/santiago santiago america utc-04:00\";}i:176;a:4:{s:2:\"id\";s:21:\"America/Santo_Domingo\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:33:\"(UTC-04:00) America/Santo_Domingo\";s:6:\"search\";s:53:\"america/santo_domingo santo domingo america utc-04:00\";}i:177;a:4:{s:2:\"id\";s:17:\"America/Sao_Paulo\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:38:\"(UTC-03:00) America/Sao_Paulo - Brazil\";s:6:\"search\";s:44:\"america/sao_paulo sao paulo brazil utc-03:00\";}i:178;a:4:{s:2:\"id\";s:20:\"America/Scoresbysund\";s:6:\"offset\";s:9:\"UTC-01:00\";s:5:\"label\";s:32:\"(UTC-01:00) America/Scoresbysund\";s:6:\"search\";s:51:\"america/scoresbysund scoresbysund america utc-01:00\";}i:179;a:4:{s:2:\"id\";s:13:\"America/Sitka\";s:6:\"offset\";s:9:\"UTC-08:00\";s:5:\"label\";s:25:\"(UTC-08:00) America/Sitka\";s:6:\"search\";s:37:\"america/sitka sitka america utc-08:00\";}i:180;a:4:{s:2:\"id\";s:21:\"America/St_Barthelemy\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:33:\"(UTC-04:00) America/St_Barthelemy\";s:6:\"search\";s:53:\"america/st_barthelemy st barthelemy america utc-04:00\";}i:181;a:4:{s:2:\"id\";s:16:\"America/St_Johns\";s:6:\"offset\";s:9:\"UTC-02:30\";s:5:\"label\";s:28:\"(UTC-02:30) America/St_Johns\";s:6:\"search\";s:43:\"america/st_johns st johns america utc-02:30\";}i:182;a:4:{s:2:\"id\";s:16:\"America/St_Kitts\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:28:\"(UTC-04:00) America/St_Kitts\";s:6:\"search\";s:43:\"america/st_kitts st kitts america utc-04:00\";}i:183;a:4:{s:2:\"id\";s:16:\"America/St_Lucia\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:28:\"(UTC-04:00) America/St_Lucia\";s:6:\"search\";s:43:\"america/st_lucia st lucia america utc-04:00\";}i:184;a:4:{s:2:\"id\";s:17:\"America/St_Thomas\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:29:\"(UTC-04:00) America/St_Thomas\";s:6:\"search\";s:45:\"america/st_thomas st thomas america utc-04:00\";}i:185;a:4:{s:2:\"id\";s:18:\"America/St_Vincent\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:30:\"(UTC-04:00) America/St_Vincent\";s:6:\"search\";s:47:\"america/st_vincent st vincent america utc-04:00\";}i:186;a:4:{s:2:\"id\";s:21:\"America/Swift_Current\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:33:\"(UTC-06:00) America/Swift_Current\";s:6:\"search\";s:53:\"america/swift_current swift current america utc-06:00\";}i:187;a:4:{s:2:\"id\";s:19:\"America/Tegucigalpa\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:31:\"(UTC-06:00) America/Tegucigalpa\";s:6:\"search\";s:49:\"america/tegucigalpa tegucigalpa america utc-06:00\";}i:188;a:4:{s:2:\"id\";s:13:\"America/Thule\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:25:\"(UTC-03:00) America/Thule\";s:6:\"search\";s:37:\"america/thule thule america utc-03:00\";}i:189;a:4:{s:2:\"id\";s:15:\"America/Tijuana\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:27:\"(UTC-07:00) America/Tijuana\";s:6:\"search\";s:41:\"america/tijuana tijuana america utc-07:00\";}i:190;a:4:{s:2:\"id\";s:15:\"America/Toronto\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:36:\"(UTC-04:00) America/Toronto - Canada\";s:6:\"search\";s:40:\"america/toronto toronto canada utc-04:00\";}i:191;a:4:{s:2:\"id\";s:15:\"America/Tortola\";s:6:\"offset\";s:9:\"UTC-04:00\";s:5:\"label\";s:27:\"(UTC-04:00) America/Tortola\";s:6:\"search\";s:41:\"america/tortola tortola america utc-04:00\";}i:192;a:4:{s:2:\"id\";s:17:\"America/Vancouver\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:38:\"(UTC-07:00) America/Vancouver - Canada\";s:6:\"search\";s:44:\"america/vancouver vancouver canada utc-07:00\";}i:193;a:4:{s:2:\"id\";s:18:\"America/Whitehorse\";s:6:\"offset\";s:9:\"UTC-07:00\";s:5:\"label\";s:30:\"(UTC-07:00) America/Whitehorse\";s:6:\"search\";s:47:\"america/whitehorse whitehorse america utc-07:00\";}i:194;a:4:{s:2:\"id\";s:16:\"America/Winnipeg\";s:6:\"offset\";s:9:\"UTC-05:00\";s:5:\"label\";s:28:\"(UTC-05:00) America/Winnipeg\";s:6:\"search\";s:43:\"america/winnipeg winnipeg america utc-05:00\";}i:195;a:4:{s:2:\"id\";s:15:\"America/Yakutat\";s:6:\"offset\";s:9:\"UTC-08:00\";s:5:\"label\";s:27:\"(UTC-08:00) America/Yakutat\";s:6:\"search\";s:41:\"america/yakutat yakutat america utc-08:00\";}i:196;a:4:{s:2:\"id\";s:16:\"Antarctica/Casey\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:28:\"(UTC+08:00) Antarctica/Casey\";s:6:\"search\";s:43:\"antarctica/casey casey antarctica utc+08:00\";}i:197;a:4:{s:2:\"id\";s:16:\"Antarctica/Davis\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:28:\"(UTC+07:00) Antarctica/Davis\";s:6:\"search\";s:43:\"antarctica/davis davis antarctica utc+07:00\";}i:198;a:4:{s:2:\"id\";s:25:\"Antarctica/DumontDUrville\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:37:\"(UTC+10:00) Antarctica/DumontDUrville\";s:6:\"search\";s:61:\"antarctica/dumontdurville dumontdurville antarctica utc+10:00\";}i:199;a:4:{s:2:\"id\";s:20:\"Antarctica/Macquarie\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:32:\"(UTC+10:00) Antarctica/Macquarie\";s:6:\"search\";s:51:\"antarctica/macquarie macquarie antarctica utc+10:00\";}i:200;a:4:{s:2:\"id\";s:17:\"Antarctica/Mawson\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:29:\"(UTC+05:00) Antarctica/Mawson\";s:6:\"search\";s:45:\"antarctica/mawson mawson antarctica utc+05:00\";}i:201;a:4:{s:2:\"id\";s:18:\"Antarctica/McMurdo\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:30:\"(UTC+12:00) Antarctica/McMurdo\";s:6:\"search\";s:47:\"antarctica/mcmurdo mcmurdo antarctica utc+12:00\";}i:202;a:4:{s:2:\"id\";s:17:\"Antarctica/Palmer\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:29:\"(UTC-03:00) Antarctica/Palmer\";s:6:\"search\";s:45:\"antarctica/palmer palmer antarctica utc-03:00\";}i:203;a:4:{s:2:\"id\";s:18:\"Antarctica/Rothera\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:30:\"(UTC-03:00) Antarctica/Rothera\";s:6:\"search\";s:47:\"antarctica/rothera rothera antarctica utc-03:00\";}i:204;a:4:{s:2:\"id\";s:16:\"Antarctica/Syowa\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:28:\"(UTC+03:00) Antarctica/Syowa\";s:6:\"search\";s:43:\"antarctica/syowa syowa antarctica utc+03:00\";}i:205;a:4:{s:2:\"id\";s:16:\"Antarctica/Troll\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:28:\"(UTC+02:00) Antarctica/Troll\";s:6:\"search\";s:43:\"antarctica/troll troll antarctica utc+02:00\";}i:206;a:4:{s:2:\"id\";s:17:\"Antarctica/Vostok\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:29:\"(UTC+05:00) Antarctica/Vostok\";s:6:\"search\";s:45:\"antarctica/vostok vostok antarctica utc+05:00\";}i:207;a:4:{s:2:\"id\";s:19:\"Arctic/Longyearbyen\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:31:\"(UTC+02:00) Arctic/Longyearbyen\";s:6:\"search\";s:49:\"arctic/longyearbyen longyearbyen arctic utc+02:00\";}i:208;a:4:{s:2:\"id\";s:9:\"Asia/Aden\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:21:\"(UTC+03:00) Asia/Aden\";s:6:\"search\";s:29:\"asia/aden aden asia utc+03:00\";}i:209;a:4:{s:2:\"id\";s:11:\"Asia/Almaty\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:23:\"(UTC+05:00) Asia/Almaty\";s:6:\"search\";s:33:\"asia/almaty almaty asia utc+05:00\";}i:210;a:4:{s:2:\"id\";s:10:\"Asia/Amman\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:22:\"(UTC+03:00) Asia/Amman\";s:6:\"search\";s:31:\"asia/amman amman asia utc+03:00\";}i:211;a:4:{s:2:\"id\";s:11:\"Asia/Anadyr\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:23:\"(UTC+12:00) Asia/Anadyr\";s:6:\"search\";s:33:\"asia/anadyr anadyr asia utc+12:00\";}i:212;a:4:{s:2:\"id\";s:10:\"Asia/Aqtau\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:22:\"(UTC+05:00) Asia/Aqtau\";s:6:\"search\";s:31:\"asia/aqtau aqtau asia utc+05:00\";}i:213;a:4:{s:2:\"id\";s:11:\"Asia/Aqtobe\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:23:\"(UTC+05:00) Asia/Aqtobe\";s:6:\"search\";s:33:\"asia/aqtobe aqtobe asia utc+05:00\";}i:214;a:4:{s:2:\"id\";s:13:\"Asia/Ashgabat\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:25:\"(UTC+05:00) Asia/Ashgabat\";s:6:\"search\";s:37:\"asia/ashgabat ashgabat asia utc+05:00\";}i:215;a:4:{s:2:\"id\";s:11:\"Asia/Atyrau\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:23:\"(UTC+05:00) Asia/Atyrau\";s:6:\"search\";s:33:\"asia/atyrau atyrau asia utc+05:00\";}i:216;a:4:{s:2:\"id\";s:12:\"Asia/Baghdad\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:24:\"(UTC+03:00) Asia/Baghdad\";s:6:\"search\";s:35:\"asia/baghdad baghdad asia utc+03:00\";}i:217;a:4:{s:2:\"id\";s:12:\"Asia/Bahrain\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:24:\"(UTC+03:00) Asia/Bahrain\";s:6:\"search\";s:35:\"asia/bahrain bahrain asia utc+03:00\";}i:218;a:4:{s:2:\"id\";s:9:\"Asia/Baku\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:21:\"(UTC+04:00) Asia/Baku\";s:6:\"search\";s:29:\"asia/baku baku asia utc+04:00\";}i:219;a:4:{s:2:\"id\";s:12:\"Asia/Bangkok\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:35:\"(UTC+07:00) Asia/Bangkok - Thailand\";s:6:\"search\";s:39:\"asia/bangkok bangkok thailand utc+07:00\";}i:220;a:4:{s:2:\"id\";s:12:\"Asia/Barnaul\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:24:\"(UTC+07:00) Asia/Barnaul\";s:6:\"search\";s:35:\"asia/barnaul barnaul asia utc+07:00\";}i:221;a:4:{s:2:\"id\";s:11:\"Asia/Beirut\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:23:\"(UTC+03:00) Asia/Beirut\";s:6:\"search\";s:33:\"asia/beirut beirut asia utc+03:00\";}i:222;a:4:{s:2:\"id\";s:12:\"Asia/Bishkek\";s:6:\"offset\";s:9:\"UTC+06:00\";s:5:\"label\";s:24:\"(UTC+06:00) Asia/Bishkek\";s:6:\"search\";s:35:\"asia/bishkek bishkek asia utc+06:00\";}i:223;a:4:{s:2:\"id\";s:11:\"Asia/Brunei\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:23:\"(UTC+08:00) Asia/Brunei\";s:6:\"search\";s:33:\"asia/brunei brunei asia utc+08:00\";}i:224;a:4:{s:2:\"id\";s:10:\"Asia/Chita\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:22:\"(UTC+09:00) Asia/Chita\";s:6:\"search\";s:31:\"asia/chita chita asia utc+09:00\";}i:225;a:4:{s:2:\"id\";s:12:\"Asia/Colombo\";s:6:\"offset\";s:9:\"UTC+05:30\";s:5:\"label\";s:24:\"(UTC+05:30) Asia/Colombo\";s:6:\"search\";s:35:\"asia/colombo colombo asia utc+05:30\";}i:226;a:4:{s:2:\"id\";s:13:\"Asia/Damascus\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:25:\"(UTC+03:00) Asia/Damascus\";s:6:\"search\";s:37:\"asia/damascus damascus asia utc+03:00\";}i:227;a:4:{s:2:\"id\";s:10:\"Asia/Dhaka\";s:6:\"offset\";s:9:\"UTC+06:00\";s:5:\"label\";s:35:\"(UTC+06:00) Asia/Dhaka - Bangladesh\";s:6:\"search\";s:37:\"asia/dhaka dhaka bangladesh utc+06:00\";}i:228;a:4:{s:2:\"id\";s:9:\"Asia/Dili\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:21:\"(UTC+09:00) Asia/Dili\";s:6:\"search\";s:29:\"asia/dili dili asia utc+09:00\";}i:229;a:4:{s:2:\"id\";s:10:\"Asia/Dubai\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:49:\"(UTC+04:00) Asia/Dubai - United Arab Emirates UAE\";s:6:\"search\";s:51:\"asia/dubai dubai united arab emirates uae utc+04:00\";}i:230;a:4:{s:2:\"id\";s:13:\"Asia/Dushanbe\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:25:\"(UTC+05:00) Asia/Dushanbe\";s:6:\"search\";s:37:\"asia/dushanbe dushanbe asia utc+05:00\";}i:231;a:4:{s:2:\"id\";s:14:\"Asia/Famagusta\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:26:\"(UTC+03:00) Asia/Famagusta\";s:6:\"search\";s:39:\"asia/famagusta famagusta asia utc+03:00\";}i:232;a:4:{s:2:\"id\";s:9:\"Asia/Gaza\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:21:\"(UTC+03:00) Asia/Gaza\";s:6:\"search\";s:29:\"asia/gaza gaza asia utc+03:00\";}i:233;a:4:{s:2:\"id\";s:11:\"Asia/Hebron\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:23:\"(UTC+03:00) Asia/Hebron\";s:6:\"search\";s:33:\"asia/hebron hebron asia utc+03:00\";}i:234;a:4:{s:2:\"id\";s:16:\"Asia/Ho_Chi_Minh\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:28:\"(UTC+07:00) Asia/Ho_Chi_Minh\";s:6:\"search\";s:43:\"asia/ho_chi_minh ho chi minh asia utc+07:00\";}i:235;a:4:{s:2:\"id\";s:14:\"Asia/Hong_Kong\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:44:\"(UTC+08:00) Asia/Hong_Kong - Hong Kong China\";s:6:\"search\";s:50:\"asia/hong_kong hong kong hong kong china utc+08:00\";}i:236;a:4:{s:2:\"id\";s:9:\"Asia/Hovd\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:21:\"(UTC+07:00) Asia/Hovd\";s:6:\"search\";s:29:\"asia/hovd hovd asia utc+07:00\";}i:237;a:4:{s:2:\"id\";s:12:\"Asia/Irkutsk\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:24:\"(UTC+08:00) Asia/Irkutsk\";s:6:\"search\";s:35:\"asia/irkutsk irkutsk asia utc+08:00\";}i:238;a:4:{s:2:\"id\";s:12:\"Asia/Jakarta\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:24:\"(UTC+07:00) Asia/Jakarta\";s:6:\"search\";s:35:\"asia/jakarta jakarta asia utc+07:00\";}i:239;a:4:{s:2:\"id\";s:13:\"Asia/Jayapura\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:25:\"(UTC+09:00) Asia/Jayapura\";s:6:\"search\";s:37:\"asia/jayapura jayapura asia utc+09:00\";}i:240;a:4:{s:2:\"id\";s:14:\"Asia/Jerusalem\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:26:\"(UTC+03:00) Asia/Jerusalem\";s:6:\"search\";s:39:\"asia/jerusalem jerusalem asia utc+03:00\";}i:241;a:4:{s:2:\"id\";s:10:\"Asia/Kabul\";s:6:\"offset\";s:9:\"UTC+04:30\";s:5:\"label\";s:22:\"(UTC+04:30) Asia/Kabul\";s:6:\"search\";s:31:\"asia/kabul kabul asia utc+04:30\";}i:242;a:4:{s:2:\"id\";s:14:\"Asia/Kamchatka\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:26:\"(UTC+12:00) Asia/Kamchatka\";s:6:\"search\";s:39:\"asia/kamchatka kamchatka asia utc+12:00\";}i:243;a:4:{s:2:\"id\";s:12:\"Asia/Karachi\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:24:\"(UTC+05:00) Asia/Karachi\";s:6:\"search\";s:35:\"asia/karachi karachi asia utc+05:00\";}i:244;a:4:{s:2:\"id\";s:14:\"Asia/Kathmandu\";s:6:\"offset\";s:9:\"UTC+05:45\";s:5:\"label\";s:34:\"(UTC+05:45) Asia/Kathmandu - Nepal\";s:6:\"search\";s:40:\"asia/kathmandu kathmandu nepal utc+05:45\";}i:245;a:4:{s:2:\"id\";s:13:\"Asia/Khandyga\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:25:\"(UTC+09:00) Asia/Khandyga\";s:6:\"search\";s:37:\"asia/khandyga khandyga asia utc+09:00\";}i:246;a:4:{s:2:\"id\";s:12:\"Asia/Kolkata\";s:6:\"offset\";s:9:\"UTC+05:30\";s:5:\"label\";s:32:\"(UTC+05:30) Asia/Kolkata - India\";s:6:\"search\";s:36:\"asia/kolkata kolkata india utc+05:30\";}i:247;a:4:{s:2:\"id\";s:16:\"Asia/Krasnoyarsk\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:28:\"(UTC+07:00) Asia/Krasnoyarsk\";s:6:\"search\";s:43:\"asia/krasnoyarsk krasnoyarsk asia utc+07:00\";}i:248;a:4:{s:2:\"id\";s:17:\"Asia/Kuala_Lumpur\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:29:\"(UTC+08:00) Asia/Kuala_Lumpur\";s:6:\"search\";s:45:\"asia/kuala_lumpur kuala lumpur asia utc+08:00\";}i:249;a:4:{s:2:\"id\";s:12:\"Asia/Kuching\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:24:\"(UTC+08:00) Asia/Kuching\";s:6:\"search\";s:35:\"asia/kuching kuching asia utc+08:00\";}i:250;a:4:{s:2:\"id\";s:11:\"Asia/Kuwait\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:23:\"(UTC+03:00) Asia/Kuwait\";s:6:\"search\";s:33:\"asia/kuwait kuwait asia utc+03:00\";}i:251;a:4:{s:2:\"id\";s:10:\"Asia/Macau\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:22:\"(UTC+08:00) Asia/Macau\";s:6:\"search\";s:31:\"asia/macau macau asia utc+08:00\";}i:252;a:4:{s:2:\"id\";s:12:\"Asia/Magadan\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:24:\"(UTC+11:00) Asia/Magadan\";s:6:\"search\";s:35:\"asia/magadan magadan asia utc+11:00\";}i:253;a:4:{s:2:\"id\";s:13:\"Asia/Makassar\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:25:\"(UTC+08:00) Asia/Makassar\";s:6:\"search\";s:37:\"asia/makassar makassar asia utc+08:00\";}i:254;a:4:{s:2:\"id\";s:11:\"Asia/Manila\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:23:\"(UTC+08:00) Asia/Manila\";s:6:\"search\";s:33:\"asia/manila manila asia utc+08:00\";}i:255;a:4:{s:2:\"id\";s:11:\"Asia/Muscat\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:23:\"(UTC+04:00) Asia/Muscat\";s:6:\"search\";s:33:\"asia/muscat muscat asia utc+04:00\";}i:256;a:4:{s:2:\"id\";s:12:\"Asia/Nicosia\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:24:\"(UTC+03:00) Asia/Nicosia\";s:6:\"search\";s:35:\"asia/nicosia nicosia asia utc+03:00\";}i:257;a:4:{s:2:\"id\";s:17:\"Asia/Novokuznetsk\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:29:\"(UTC+07:00) Asia/Novokuznetsk\";s:6:\"search\";s:45:\"asia/novokuznetsk novokuznetsk asia utc+07:00\";}i:258;a:4:{s:2:\"id\";s:16:\"Asia/Novosibirsk\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:28:\"(UTC+07:00) Asia/Novosibirsk\";s:6:\"search\";s:43:\"asia/novosibirsk novosibirsk asia utc+07:00\";}i:259;a:4:{s:2:\"id\";s:9:\"Asia/Omsk\";s:6:\"offset\";s:9:\"UTC+06:00\";s:5:\"label\";s:21:\"(UTC+06:00) Asia/Omsk\";s:6:\"search\";s:29:\"asia/omsk omsk asia utc+06:00\";}i:260;a:4:{s:2:\"id\";s:9:\"Asia/Oral\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:21:\"(UTC+05:00) Asia/Oral\";s:6:\"search\";s:29:\"asia/oral oral asia utc+05:00\";}i:261;a:4:{s:2:\"id\";s:15:\"Asia/Phnom_Penh\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:27:\"(UTC+07:00) Asia/Phnom_Penh\";s:6:\"search\";s:41:\"asia/phnom_penh phnom penh asia utc+07:00\";}i:262;a:4:{s:2:\"id\";s:14:\"Asia/Pontianak\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:26:\"(UTC+07:00) Asia/Pontianak\";s:6:\"search\";s:39:\"asia/pontianak pontianak asia utc+07:00\";}i:263;a:4:{s:2:\"id\";s:14:\"Asia/Pyongyang\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:26:\"(UTC+09:00) Asia/Pyongyang\";s:6:\"search\";s:39:\"asia/pyongyang pyongyang asia utc+09:00\";}i:264;a:4:{s:2:\"id\";s:10:\"Asia/Qatar\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:22:\"(UTC+03:00) Asia/Qatar\";s:6:\"search\";s:31:\"asia/qatar qatar asia utc+03:00\";}i:265;a:4:{s:2:\"id\";s:13:\"Asia/Qostanay\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:25:\"(UTC+05:00) Asia/Qostanay\";s:6:\"search\";s:37:\"asia/qostanay qostanay asia utc+05:00\";}i:266;a:4:{s:2:\"id\";s:14:\"Asia/Qyzylorda\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:26:\"(UTC+05:00) Asia/Qyzylorda\";s:6:\"search\";s:39:\"asia/qyzylorda qyzylorda asia utc+05:00\";}i:267;a:4:{s:2:\"id\";s:11:\"Asia/Riyadh\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:23:\"(UTC+03:00) Asia/Riyadh\";s:6:\"search\";s:33:\"asia/riyadh riyadh asia utc+03:00\";}i:268;a:4:{s:2:\"id\";s:13:\"Asia/Sakhalin\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:25:\"(UTC+11:00) Asia/Sakhalin\";s:6:\"search\";s:37:\"asia/sakhalin sakhalin asia utc+11:00\";}i:269;a:4:{s:2:\"id\";s:14:\"Asia/Samarkand\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:26:\"(UTC+05:00) Asia/Samarkand\";s:6:\"search\";s:39:\"asia/samarkand samarkand asia utc+05:00\";}i:270;a:4:{s:2:\"id\";s:10:\"Asia/Seoul\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:42:\"(UTC+09:00) Asia/Seoul - South Korea Korea\";s:6:\"search\";s:44:\"asia/seoul seoul south korea korea utc+09:00\";}i:271;a:4:{s:2:\"id\";s:13:\"Asia/Shanghai\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:33:\"(UTC+08:00) Asia/Shanghai - China\";s:6:\"search\";s:38:\"asia/shanghai shanghai china utc+08:00\";}i:272;a:4:{s:2:\"id\";s:14:\"Asia/Singapore\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:38:\"(UTC+08:00) Asia/Singapore - Singapore\";s:6:\"search\";s:44:\"asia/singapore singapore singapore utc+08:00\";}i:273;a:4:{s:2:\"id\";s:18:\"Asia/Srednekolymsk\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:30:\"(UTC+11:00) Asia/Srednekolymsk\";s:6:\"search\";s:47:\"asia/srednekolymsk srednekolymsk asia utc+11:00\";}i:274;a:4:{s:2:\"id\";s:11:\"Asia/Taipei\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:23:\"(UTC+08:00) Asia/Taipei\";s:6:\"search\";s:33:\"asia/taipei taipei asia utc+08:00\";}i:275;a:4:{s:2:\"id\";s:13:\"Asia/Tashkent\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:25:\"(UTC+05:00) Asia/Tashkent\";s:6:\"search\";s:37:\"asia/tashkent tashkent asia utc+05:00\";}i:276;a:4:{s:2:\"id\";s:12:\"Asia/Tbilisi\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:24:\"(UTC+04:00) Asia/Tbilisi\";s:6:\"search\";s:35:\"asia/tbilisi tbilisi asia utc+04:00\";}i:277;a:4:{s:2:\"id\";s:11:\"Asia/Tehran\";s:6:\"offset\";s:9:\"UTC+03:30\";s:5:\"label\";s:23:\"(UTC+03:30) Asia/Tehran\";s:6:\"search\";s:33:\"asia/tehran tehran asia utc+03:30\";}i:278;a:4:{s:2:\"id\";s:12:\"Asia/Thimphu\";s:6:\"offset\";s:9:\"UTC+06:00\";s:5:\"label\";s:24:\"(UTC+06:00) Asia/Thimphu\";s:6:\"search\";s:35:\"asia/thimphu thimphu asia utc+06:00\";}i:279;a:4:{s:2:\"id\";s:10:\"Asia/Tokyo\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:30:\"(UTC+09:00) Asia/Tokyo - Japan\";s:6:\"search\";s:32:\"asia/tokyo tokyo japan utc+09:00\";}i:280;a:4:{s:2:\"id\";s:10:\"Asia/Tomsk\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:22:\"(UTC+07:00) Asia/Tomsk\";s:6:\"search\";s:31:\"asia/tomsk tomsk asia utc+07:00\";}i:281;a:4:{s:2:\"id\";s:16:\"Asia/Ulaanbaatar\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:28:\"(UTC+08:00) Asia/Ulaanbaatar\";s:6:\"search\";s:43:\"asia/ulaanbaatar ulaanbaatar asia utc+08:00\";}i:282;a:4:{s:2:\"id\";s:11:\"Asia/Urumqi\";s:6:\"offset\";s:9:\"UTC+06:00\";s:5:\"label\";s:23:\"(UTC+06:00) Asia/Urumqi\";s:6:\"search\";s:33:\"asia/urumqi urumqi asia utc+06:00\";}i:283;a:4:{s:2:\"id\";s:13:\"Asia/Ust-Nera\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:25:\"(UTC+10:00) Asia/Ust-Nera\";s:6:\"search\";s:37:\"asia/ust-nera ust-nera asia utc+10:00\";}i:284;a:4:{s:2:\"id\";s:14:\"Asia/Vientiane\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:26:\"(UTC+07:00) Asia/Vientiane\";s:6:\"search\";s:39:\"asia/vientiane vientiane asia utc+07:00\";}i:285;a:4:{s:2:\"id\";s:16:\"Asia/Vladivostok\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:28:\"(UTC+10:00) Asia/Vladivostok\";s:6:\"search\";s:43:\"asia/vladivostok vladivostok asia utc+10:00\";}i:286;a:4:{s:2:\"id\";s:12:\"Asia/Yakutsk\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:24:\"(UTC+09:00) Asia/Yakutsk\";s:6:\"search\";s:35:\"asia/yakutsk yakutsk asia utc+09:00\";}i:287;a:4:{s:2:\"id\";s:11:\"Asia/Yangon\";s:6:\"offset\";s:9:\"UTC+06:30\";s:5:\"label\";s:23:\"(UTC+06:30) Asia/Yangon\";s:6:\"search\";s:33:\"asia/yangon yangon asia utc+06:30\";}i:288;a:4:{s:2:\"id\";s:18:\"Asia/Yekaterinburg\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:30:\"(UTC+05:00) Asia/Yekaterinburg\";s:6:\"search\";s:47:\"asia/yekaterinburg yekaterinburg asia utc+05:00\";}i:289;a:4:{s:2:\"id\";s:12:\"Asia/Yerevan\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:24:\"(UTC+04:00) Asia/Yerevan\";s:6:\"search\";s:35:\"asia/yerevan yerevan asia utc+04:00\";}i:290;a:4:{s:2:\"id\";s:15:\"Atlantic/Azores\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:27:\"(UTC+00:00) Atlantic/Azores\";s:6:\"search\";s:41:\"atlantic/azores azores atlantic utc+00:00\";}i:291;a:4:{s:2:\"id\";s:16:\"Atlantic/Bermuda\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:28:\"(UTC-03:00) Atlantic/Bermuda\";s:6:\"search\";s:43:\"atlantic/bermuda bermuda atlantic utc-03:00\";}i:292;a:4:{s:2:\"id\";s:15:\"Atlantic/Canary\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:27:\"(UTC+01:00) Atlantic/Canary\";s:6:\"search\";s:41:\"atlantic/canary canary atlantic utc+01:00\";}i:293;a:4:{s:2:\"id\";s:19:\"Atlantic/Cape_Verde\";s:6:\"offset\";s:9:\"UTC-01:00\";s:5:\"label\";s:31:\"(UTC-01:00) Atlantic/Cape_Verde\";s:6:\"search\";s:49:\"atlantic/cape_verde cape verde atlantic utc-01:00\";}i:294;a:4:{s:2:\"id\";s:14:\"Atlantic/Faroe\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:26:\"(UTC+01:00) Atlantic/Faroe\";s:6:\"search\";s:39:\"atlantic/faroe faroe atlantic utc+01:00\";}i:295;a:4:{s:2:\"id\";s:16:\"Atlantic/Madeira\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:28:\"(UTC+01:00) Atlantic/Madeira\";s:6:\"search\";s:43:\"atlantic/madeira madeira atlantic utc+01:00\";}i:296;a:4:{s:2:\"id\";s:18:\"Atlantic/Reykjavik\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:30:\"(UTC+00:00) Atlantic/Reykjavik\";s:6:\"search\";s:47:\"atlantic/reykjavik reykjavik atlantic utc+00:00\";}i:297;a:4:{s:2:\"id\";s:22:\"Atlantic/South_Georgia\";s:6:\"offset\";s:9:\"UTC-02:00\";s:5:\"label\";s:34:\"(UTC-02:00) Atlantic/South_Georgia\";s:6:\"search\";s:55:\"atlantic/south_georgia south georgia atlantic utc-02:00\";}i:298;a:4:{s:2:\"id\";s:18:\"Atlantic/St_Helena\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:30:\"(UTC+00:00) Atlantic/St_Helena\";s:6:\"search\";s:47:\"atlantic/st_helena st helena atlantic utc+00:00\";}i:299;a:4:{s:2:\"id\";s:16:\"Atlantic/Stanley\";s:6:\"offset\";s:9:\"UTC-03:00\";s:5:\"label\";s:28:\"(UTC-03:00) Atlantic/Stanley\";s:6:\"search\";s:43:\"atlantic/stanley stanley atlantic utc-03:00\";}i:300;a:4:{s:2:\"id\";s:18:\"Australia/Adelaide\";s:6:\"offset\";s:9:\"UTC+09:30\";s:5:\"label\";s:30:\"(UTC+09:30) Australia/Adelaide\";s:6:\"search\";s:47:\"australia/adelaide adelaide australia utc+09:30\";}i:301;a:4:{s:2:\"id\";s:18:\"Australia/Brisbane\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:42:\"(UTC+10:00) Australia/Brisbane - Australia\";s:6:\"search\";s:47:\"australia/brisbane brisbane australia utc+10:00\";}i:302;a:4:{s:2:\"id\";s:21:\"Australia/Broken_Hill\";s:6:\"offset\";s:9:\"UTC+09:30\";s:5:\"label\";s:33:\"(UTC+09:30) Australia/Broken_Hill\";s:6:\"search\";s:53:\"australia/broken_hill broken hill australia utc+09:30\";}i:303;a:4:{s:2:\"id\";s:16:\"Australia/Darwin\";s:6:\"offset\";s:9:\"UTC+09:30\";s:5:\"label\";s:28:\"(UTC+09:30) Australia/Darwin\";s:6:\"search\";s:43:\"australia/darwin darwin australia utc+09:30\";}i:304;a:4:{s:2:\"id\";s:15:\"Australia/Eucla\";s:6:\"offset\";s:9:\"UTC+08:45\";s:5:\"label\";s:27:\"(UTC+08:45) Australia/Eucla\";s:6:\"search\";s:41:\"australia/eucla eucla australia utc+08:45\";}i:305;a:4:{s:2:\"id\";s:16:\"Australia/Hobart\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:28:\"(UTC+10:00) Australia/Hobart\";s:6:\"search\";s:43:\"australia/hobart hobart australia utc+10:00\";}i:306;a:4:{s:2:\"id\";s:18:\"Australia/Lindeman\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:30:\"(UTC+10:00) Australia/Lindeman\";s:6:\"search\";s:47:\"australia/lindeman lindeman australia utc+10:00\";}i:307;a:4:{s:2:\"id\";s:19:\"Australia/Lord_Howe\";s:6:\"offset\";s:9:\"UTC+10:30\";s:5:\"label\";s:31:\"(UTC+10:30) Australia/Lord_Howe\";s:6:\"search\";s:49:\"australia/lord_howe lord howe australia utc+10:30\";}i:308;a:4:{s:2:\"id\";s:19:\"Australia/Melbourne\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:43:\"(UTC+10:00) Australia/Melbourne - Australia\";s:6:\"search\";s:49:\"australia/melbourne melbourne australia utc+10:00\";}i:309;a:4:{s:2:\"id\";s:15:\"Australia/Perth\";s:6:\"offset\";s:9:\"UTC+08:00\";s:5:\"label\";s:39:\"(UTC+08:00) Australia/Perth - Australia\";s:6:\"search\";s:41:\"australia/perth perth australia utc+08:00\";}i:310;a:4:{s:2:\"id\";s:16:\"Australia/Sydney\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:40:\"(UTC+10:00) Australia/Sydney - Australia\";s:6:\"search\";s:43:\"australia/sydney sydney australia utc+10:00\";}i:311;a:4:{s:2:\"id\";s:16:\"Europe/Amsterdam\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:42:\"(UTC+02:00) Europe/Amsterdam - Netherlands\";s:6:\"search\";s:48:\"europe/amsterdam amsterdam netherlands utc+02:00\";}i:312;a:4:{s:2:\"id\";s:14:\"Europe/Andorra\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:26:\"(UTC+02:00) Europe/Andorra\";s:6:\"search\";s:39:\"europe/andorra andorra europe utc+02:00\";}i:313;a:4:{s:2:\"id\";s:16:\"Europe/Astrakhan\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:28:\"(UTC+04:00) Europe/Astrakhan\";s:6:\"search\";s:43:\"europe/astrakhan astrakhan europe utc+04:00\";}i:314;a:4:{s:2:\"id\";s:13:\"Europe/Athens\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:34:\"(UTC+03:00) Europe/Athens - Greece\";s:6:\"search\";s:37:\"europe/athens athens greece utc+03:00\";}i:315;a:4:{s:2:\"id\";s:15:\"Europe/Belgrade\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:27:\"(UTC+02:00) Europe/Belgrade\";s:6:\"search\";s:41:\"europe/belgrade belgrade europe utc+02:00\";}i:316;a:4:{s:2:\"id\";s:13:\"Europe/Berlin\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:35:\"(UTC+02:00) Europe/Berlin - Germany\";s:6:\"search\";s:38:\"europe/berlin berlin germany utc+02:00\";}i:317;a:4:{s:2:\"id\";s:17:\"Europe/Bratislava\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:29:\"(UTC+02:00) Europe/Bratislava\";s:6:\"search\";s:45:\"europe/bratislava bratislava europe utc+02:00\";}i:318;a:4:{s:2:\"id\";s:15:\"Europe/Brussels\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:37:\"(UTC+02:00) Europe/Brussels - Belgium\";s:6:\"search\";s:42:\"europe/brussels brussels belgium utc+02:00\";}i:319;a:4:{s:2:\"id\";s:16:\"Europe/Bucharest\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:28:\"(UTC+03:00) Europe/Bucharest\";s:6:\"search\";s:43:\"europe/bucharest bucharest europe utc+03:00\";}i:320;a:4:{s:2:\"id\";s:15:\"Europe/Budapest\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:27:\"(UTC+02:00) Europe/Budapest\";s:6:\"search\";s:41:\"europe/budapest budapest europe utc+02:00\";}i:321;a:4:{s:2:\"id\";s:15:\"Europe/Busingen\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:27:\"(UTC+02:00) Europe/Busingen\";s:6:\"search\";s:41:\"europe/busingen busingen europe utc+02:00\";}i:322;a:4:{s:2:\"id\";s:15:\"Europe/Chisinau\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:27:\"(UTC+03:00) Europe/Chisinau\";s:6:\"search\";s:41:\"europe/chisinau chisinau europe utc+03:00\";}i:323;a:4:{s:2:\"id\";s:17:\"Europe/Copenhagen\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:29:\"(UTC+02:00) Europe/Copenhagen\";s:6:\"search\";s:45:\"europe/copenhagen copenhagen europe utc+02:00\";}i:324;a:4:{s:2:\"id\";s:13:\"Europe/Dublin\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:25:\"(UTC+01:00) Europe/Dublin\";s:6:\"search\";s:37:\"europe/dublin dublin europe utc+01:00\";}i:325;a:4:{s:2:\"id\";s:16:\"Europe/Gibraltar\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:28:\"(UTC+02:00) Europe/Gibraltar\";s:6:\"search\";s:43:\"europe/gibraltar gibraltar europe utc+02:00\";}i:326;a:4:{s:2:\"id\";s:15:\"Europe/Guernsey\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:27:\"(UTC+01:00) Europe/Guernsey\";s:6:\"search\";s:41:\"europe/guernsey guernsey europe utc+01:00\";}i:327;a:4:{s:2:\"id\";s:15:\"Europe/Helsinki\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:37:\"(UTC+03:00) Europe/Helsinki - Finland\";s:6:\"search\";s:42:\"europe/helsinki helsinki finland utc+03:00\";}i:328;a:4:{s:2:\"id\";s:18:\"Europe/Isle_of_Man\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:30:\"(UTC+01:00) Europe/Isle_of_Man\";s:6:\"search\";s:47:\"europe/isle_of_man isle of man europe utc+01:00\";}i:329;a:4:{s:2:\"id\";s:15:\"Europe/Istanbul\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:36:\"(UTC+03:00) Europe/Istanbul - Turkey\";s:6:\"search\";s:41:\"europe/istanbul istanbul turkey utc+03:00\";}i:330;a:4:{s:2:\"id\";s:13:\"Europe/Jersey\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:25:\"(UTC+01:00) Europe/Jersey\";s:6:\"search\";s:37:\"europe/jersey jersey europe utc+01:00\";}i:331;a:4:{s:2:\"id\";s:18:\"Europe/Kaliningrad\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:30:\"(UTC+02:00) Europe/Kaliningrad\";s:6:\"search\";s:47:\"europe/kaliningrad kaliningrad europe utc+02:00\";}i:332;a:4:{s:2:\"id\";s:12:\"Europe/Kirov\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:24:\"(UTC+03:00) Europe/Kirov\";s:6:\"search\";s:35:\"europe/kirov kirov europe utc+03:00\";}i:333;a:4:{s:2:\"id\";s:11:\"Europe/Kyiv\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:23:\"(UTC+03:00) Europe/Kyiv\";s:6:\"search\";s:33:\"europe/kyiv kyiv europe utc+03:00\";}i:334;a:4:{s:2:\"id\";s:13:\"Europe/Lisbon\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:25:\"(UTC+01:00) Europe/Lisbon\";s:6:\"search\";s:37:\"europe/lisbon lisbon europe utc+01:00\";}i:335;a:4:{s:2:\"id\";s:16:\"Europe/Ljubljana\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:28:\"(UTC+02:00) Europe/Ljubljana\";s:6:\"search\";s:43:\"europe/ljubljana ljubljana europe utc+02:00\";}i:336;a:4:{s:2:\"id\";s:13:\"Europe/London\";s:6:\"offset\";s:9:\"UTC+01:00\";s:5:\"label\";s:45:\"(UTC+01:00) Europe/London - United Kingdom UK\";s:6:\"search\";s:48:\"europe/london london united kingdom uk utc+01:00\";}i:337;a:4:{s:2:\"id\";s:17:\"Europe/Luxembourg\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:29:\"(UTC+02:00) Europe/Luxembourg\";s:6:\"search\";s:45:\"europe/luxembourg luxembourg europe utc+02:00\";}i:338;a:4:{s:2:\"id\";s:13:\"Europe/Madrid\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:33:\"(UTC+02:00) Europe/Madrid - Spain\";s:6:\"search\";s:36:\"europe/madrid madrid spain utc+02:00\";}i:339;a:4:{s:2:\"id\";s:12:\"Europe/Malta\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:24:\"(UTC+02:00) Europe/Malta\";s:6:\"search\";s:35:\"europe/malta malta europe utc+02:00\";}i:340;a:4:{s:2:\"id\";s:16:\"Europe/Mariehamn\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:28:\"(UTC+03:00) Europe/Mariehamn\";s:6:\"search\";s:43:\"europe/mariehamn mariehamn europe utc+03:00\";}i:341;a:4:{s:2:\"id\";s:12:\"Europe/Minsk\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:24:\"(UTC+03:00) Europe/Minsk\";s:6:\"search\";s:35:\"europe/minsk minsk europe utc+03:00\";}i:342;a:4:{s:2:\"id\";s:13:\"Europe/Monaco\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Europe/Monaco\";s:6:\"search\";s:37:\"europe/monaco monaco europe utc+02:00\";}i:343;a:4:{s:2:\"id\";s:13:\"Europe/Moscow\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:34:\"(UTC+03:00) Europe/Moscow - Russia\";s:6:\"search\";s:37:\"europe/moscow moscow russia utc+03:00\";}i:344;a:4:{s:2:\"id\";s:11:\"Europe/Oslo\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:32:\"(UTC+02:00) Europe/Oslo - Norway\";s:6:\"search\";s:33:\"europe/oslo oslo norway utc+02:00\";}i:345;a:4:{s:2:\"id\";s:12:\"Europe/Paris\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:33:\"(UTC+02:00) Europe/Paris - France\";s:6:\"search\";s:35:\"europe/paris paris france utc+02:00\";}i:346;a:4:{s:2:\"id\";s:16:\"Europe/Podgorica\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:28:\"(UTC+02:00) Europe/Podgorica\";s:6:\"search\";s:43:\"europe/podgorica podgorica europe utc+02:00\";}i:347;a:4:{s:2:\"id\";s:13:\"Europe/Prague\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Europe/Prague\";s:6:\"search\";s:37:\"europe/prague prague europe utc+02:00\";}i:348;a:4:{s:2:\"id\";s:11:\"Europe/Riga\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:23:\"(UTC+03:00) Europe/Riga\";s:6:\"search\";s:33:\"europe/riga riga europe utc+03:00\";}i:349;a:4:{s:2:\"id\";s:11:\"Europe/Rome\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:31:\"(UTC+02:00) Europe/Rome - Italy\";s:6:\"search\";s:32:\"europe/rome rome italy utc+02:00\";}i:350;a:4:{s:2:\"id\";s:13:\"Europe/Samara\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:25:\"(UTC+04:00) Europe/Samara\";s:6:\"search\";s:37:\"europe/samara samara europe utc+04:00\";}i:351;a:4:{s:2:\"id\";s:17:\"Europe/San_Marino\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:29:\"(UTC+02:00) Europe/San_Marino\";s:6:\"search\";s:45:\"europe/san_marino san marino europe utc+02:00\";}i:352;a:4:{s:2:\"id\";s:15:\"Europe/Sarajevo\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:27:\"(UTC+02:00) Europe/Sarajevo\";s:6:\"search\";s:41:\"europe/sarajevo sarajevo europe utc+02:00\";}i:353;a:4:{s:2:\"id\";s:14:\"Europe/Saratov\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:26:\"(UTC+04:00) Europe/Saratov\";s:6:\"search\";s:39:\"europe/saratov saratov europe utc+04:00\";}i:354;a:4:{s:2:\"id\";s:17:\"Europe/Simferopol\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:29:\"(UTC+03:00) Europe/Simferopol\";s:6:\"search\";s:45:\"europe/simferopol simferopol europe utc+03:00\";}i:355;a:4:{s:2:\"id\";s:13:\"Europe/Skopje\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Europe/Skopje\";s:6:\"search\";s:37:\"europe/skopje skopje europe utc+02:00\";}i:356;a:4:{s:2:\"id\";s:12:\"Europe/Sofia\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:24:\"(UTC+03:00) Europe/Sofia\";s:6:\"search\";s:35:\"europe/sofia sofia europe utc+03:00\";}i:357;a:4:{s:2:\"id\";s:16:\"Europe/Stockholm\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:37:\"(UTC+02:00) Europe/Stockholm - Sweden\";s:6:\"search\";s:43:\"europe/stockholm stockholm sweden utc+02:00\";}i:358;a:4:{s:2:\"id\";s:14:\"Europe/Tallinn\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:26:\"(UTC+03:00) Europe/Tallinn\";s:6:\"search\";s:39:\"europe/tallinn tallinn europe utc+03:00\";}i:359;a:4:{s:2:\"id\";s:13:\"Europe/Tirane\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Europe/Tirane\";s:6:\"search\";s:37:\"europe/tirane tirane europe utc+02:00\";}i:360;a:4:{s:2:\"id\";s:16:\"Europe/Ulyanovsk\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:28:\"(UTC+04:00) Europe/Ulyanovsk\";s:6:\"search\";s:43:\"europe/ulyanovsk ulyanovsk europe utc+04:00\";}i:361;a:4:{s:2:\"id\";s:12:\"Europe/Vaduz\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:24:\"(UTC+02:00) Europe/Vaduz\";s:6:\"search\";s:35:\"europe/vaduz vaduz europe utc+02:00\";}i:362;a:4:{s:2:\"id\";s:14:\"Europe/Vatican\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:26:\"(UTC+02:00) Europe/Vatican\";s:6:\"search\";s:39:\"europe/vatican vatican europe utc+02:00\";}i:363;a:4:{s:2:\"id\";s:13:\"Europe/Vienna\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:35:\"(UTC+02:00) Europe/Vienna - Austria\";s:6:\"search\";s:38:\"europe/vienna vienna austria utc+02:00\";}i:364;a:4:{s:2:\"id\";s:14:\"Europe/Vilnius\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:26:\"(UTC+03:00) Europe/Vilnius\";s:6:\"search\";s:39:\"europe/vilnius vilnius europe utc+03:00\";}i:365;a:4:{s:2:\"id\";s:16:\"Europe/Volgograd\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:28:\"(UTC+03:00) Europe/Volgograd\";s:6:\"search\";s:43:\"europe/volgograd volgograd europe utc+03:00\";}i:366;a:4:{s:2:\"id\";s:13:\"Europe/Warsaw\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Europe/Warsaw\";s:6:\"search\";s:37:\"europe/warsaw warsaw europe utc+02:00\";}i:367;a:4:{s:2:\"id\";s:13:\"Europe/Zagreb\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:25:\"(UTC+02:00) Europe/Zagreb\";s:6:\"search\";s:37:\"europe/zagreb zagreb europe utc+02:00\";}i:368;a:4:{s:2:\"id\";s:13:\"Europe/Zurich\";s:6:\"offset\";s:9:\"UTC+02:00\";s:5:\"label\";s:39:\"(UTC+02:00) Europe/Zurich - Switzerland\";s:6:\"search\";s:42:\"europe/zurich zurich switzerland utc+02:00\";}i:369;a:4:{s:2:\"id\";s:19:\"Indian/Antananarivo\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:31:\"(UTC+03:00) Indian/Antananarivo\";s:6:\"search\";s:49:\"indian/antananarivo antananarivo indian utc+03:00\";}i:370;a:4:{s:2:\"id\";s:13:\"Indian/Chagos\";s:6:\"offset\";s:9:\"UTC+06:00\";s:5:\"label\";s:25:\"(UTC+06:00) Indian/Chagos\";s:6:\"search\";s:37:\"indian/chagos chagos indian utc+06:00\";}i:371;a:4:{s:2:\"id\";s:16:\"Indian/Christmas\";s:6:\"offset\";s:9:\"UTC+07:00\";s:5:\"label\";s:28:\"(UTC+07:00) Indian/Christmas\";s:6:\"search\";s:43:\"indian/christmas christmas indian utc+07:00\";}i:372;a:4:{s:2:\"id\";s:12:\"Indian/Cocos\";s:6:\"offset\";s:9:\"UTC+06:30\";s:5:\"label\";s:24:\"(UTC+06:30) Indian/Cocos\";s:6:\"search\";s:35:\"indian/cocos cocos indian utc+06:30\";}i:373;a:4:{s:2:\"id\";s:13:\"Indian/Comoro\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:25:\"(UTC+03:00) Indian/Comoro\";s:6:\"search\";s:37:\"indian/comoro comoro indian utc+03:00\";}i:374;a:4:{s:2:\"id\";s:16:\"Indian/Kerguelen\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:28:\"(UTC+05:00) Indian/Kerguelen\";s:6:\"search\";s:43:\"indian/kerguelen kerguelen indian utc+05:00\";}i:375;a:4:{s:2:\"id\";s:11:\"Indian/Mahe\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:23:\"(UTC+04:00) Indian/Mahe\";s:6:\"search\";s:33:\"indian/mahe mahe indian utc+04:00\";}i:376;a:4:{s:2:\"id\";s:15:\"Indian/Maldives\";s:6:\"offset\";s:9:\"UTC+05:00\";s:5:\"label\";s:27:\"(UTC+05:00) Indian/Maldives\";s:6:\"search\";s:41:\"indian/maldives maldives indian utc+05:00\";}i:377;a:4:{s:2:\"id\";s:16:\"Indian/Mauritius\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:28:\"(UTC+04:00) Indian/Mauritius\";s:6:\"search\";s:43:\"indian/mauritius mauritius indian utc+04:00\";}i:378;a:4:{s:2:\"id\";s:14:\"Indian/Mayotte\";s:6:\"offset\";s:9:\"UTC+03:00\";s:5:\"label\";s:26:\"(UTC+03:00) Indian/Mayotte\";s:6:\"search\";s:39:\"indian/mayotte mayotte indian utc+03:00\";}i:379;a:4:{s:2:\"id\";s:14:\"Indian/Reunion\";s:6:\"offset\";s:9:\"UTC+04:00\";s:5:\"label\";s:26:\"(UTC+04:00) Indian/Reunion\";s:6:\"search\";s:39:\"indian/reunion reunion indian utc+04:00\";}i:380;a:4:{s:2:\"id\";s:12:\"Pacific/Apia\";s:6:\"offset\";s:9:\"UTC+13:00\";s:5:\"label\";s:24:\"(UTC+13:00) Pacific/Apia\";s:6:\"search\";s:35:\"pacific/apia apia pacific utc+13:00\";}i:381;a:4:{s:2:\"id\";s:16:\"Pacific/Auckland\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:42:\"(UTC+12:00) Pacific/Auckland - New Zealand\";s:6:\"search\";s:47:\"pacific/auckland auckland new zealand utc+12:00\";}i:382;a:4:{s:2:\"id\";s:20:\"Pacific/Bougainville\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:32:\"(UTC+11:00) Pacific/Bougainville\";s:6:\"search\";s:51:\"pacific/bougainville bougainville pacific utc+11:00\";}i:383;a:4:{s:2:\"id\";s:15:\"Pacific/Chatham\";s:6:\"offset\";s:9:\"UTC+12:45\";s:5:\"label\";s:27:\"(UTC+12:45) Pacific/Chatham\";s:6:\"search\";s:41:\"pacific/chatham chatham pacific utc+12:45\";}i:384;a:4:{s:2:\"id\";s:13:\"Pacific/Chuuk\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:25:\"(UTC+10:00) Pacific/Chuuk\";s:6:\"search\";s:37:\"pacific/chuuk chuuk pacific utc+10:00\";}i:385;a:4:{s:2:\"id\";s:14:\"Pacific/Easter\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:26:\"(UTC-06:00) Pacific/Easter\";s:6:\"search\";s:39:\"pacific/easter easter pacific utc-06:00\";}i:386;a:4:{s:2:\"id\";s:13:\"Pacific/Efate\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:25:\"(UTC+11:00) Pacific/Efate\";s:6:\"search\";s:37:\"pacific/efate efate pacific utc+11:00\";}i:387;a:4:{s:2:\"id\";s:15:\"Pacific/Fakaofo\";s:6:\"offset\";s:9:\"UTC+13:00\";s:5:\"label\";s:27:\"(UTC+13:00) Pacific/Fakaofo\";s:6:\"search\";s:41:\"pacific/fakaofo fakaofo pacific utc+13:00\";}i:388;a:4:{s:2:\"id\";s:12:\"Pacific/Fiji\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:24:\"(UTC+12:00) Pacific/Fiji\";s:6:\"search\";s:35:\"pacific/fiji fiji pacific utc+12:00\";}i:389;a:4:{s:2:\"id\";s:16:\"Pacific/Funafuti\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:28:\"(UTC+12:00) Pacific/Funafuti\";s:6:\"search\";s:43:\"pacific/funafuti funafuti pacific utc+12:00\";}i:390;a:4:{s:2:\"id\";s:17:\"Pacific/Galapagos\";s:6:\"offset\";s:9:\"UTC-06:00\";s:5:\"label\";s:29:\"(UTC-06:00) Pacific/Galapagos\";s:6:\"search\";s:45:\"pacific/galapagos galapagos pacific utc-06:00\";}i:391;a:4:{s:2:\"id\";s:15:\"Pacific/Gambier\";s:6:\"offset\";s:9:\"UTC-09:00\";s:5:\"label\";s:27:\"(UTC-09:00) Pacific/Gambier\";s:6:\"search\";s:41:\"pacific/gambier gambier pacific utc-09:00\";}i:392;a:4:{s:2:\"id\";s:19:\"Pacific/Guadalcanal\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:31:\"(UTC+11:00) Pacific/Guadalcanal\";s:6:\"search\";s:49:\"pacific/guadalcanal guadalcanal pacific utc+11:00\";}i:393;a:4:{s:2:\"id\";s:12:\"Pacific/Guam\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:24:\"(UTC+10:00) Pacific/Guam\";s:6:\"search\";s:35:\"pacific/guam guam pacific utc+10:00\";}i:394;a:4:{s:2:\"id\";s:16:\"Pacific/Honolulu\";s:6:\"offset\";s:9:\"UTC-10:00\";s:5:\"label\";s:28:\"(UTC-10:00) Pacific/Honolulu\";s:6:\"search\";s:43:\"pacific/honolulu honolulu pacific utc-10:00\";}i:395;a:4:{s:2:\"id\";s:14:\"Pacific/Kanton\";s:6:\"offset\";s:9:\"UTC+13:00\";s:5:\"label\";s:26:\"(UTC+13:00) Pacific/Kanton\";s:6:\"search\";s:39:\"pacific/kanton kanton pacific utc+13:00\";}i:396;a:4:{s:2:\"id\";s:18:\"Pacific/Kiritimati\";s:6:\"offset\";s:9:\"UTC+14:00\";s:5:\"label\";s:30:\"(UTC+14:00) Pacific/Kiritimati\";s:6:\"search\";s:47:\"pacific/kiritimati kiritimati pacific utc+14:00\";}i:397;a:4:{s:2:\"id\";s:14:\"Pacific/Kosrae\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:26:\"(UTC+11:00) Pacific/Kosrae\";s:6:\"search\";s:39:\"pacific/kosrae kosrae pacific utc+11:00\";}i:398;a:4:{s:2:\"id\";s:17:\"Pacific/Kwajalein\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:29:\"(UTC+12:00) Pacific/Kwajalein\";s:6:\"search\";s:45:\"pacific/kwajalein kwajalein pacific utc+12:00\";}i:399;a:4:{s:2:\"id\";s:14:\"Pacific/Majuro\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:26:\"(UTC+12:00) Pacific/Majuro\";s:6:\"search\";s:39:\"pacific/majuro majuro pacific utc+12:00\";}i:400;a:4:{s:2:\"id\";s:17:\"Pacific/Marquesas\";s:6:\"offset\";s:9:\"UTC-09:30\";s:5:\"label\";s:29:\"(UTC-09:30) Pacific/Marquesas\";s:6:\"search\";s:45:\"pacific/marquesas marquesas pacific utc-09:30\";}i:401;a:4:{s:2:\"id\";s:14:\"Pacific/Midway\";s:6:\"offset\";s:9:\"UTC-11:00\";s:5:\"label\";s:26:\"(UTC-11:00) Pacific/Midway\";s:6:\"search\";s:39:\"pacific/midway midway pacific utc-11:00\";}i:402;a:4:{s:2:\"id\";s:13:\"Pacific/Nauru\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:25:\"(UTC+12:00) Pacific/Nauru\";s:6:\"search\";s:37:\"pacific/nauru nauru pacific utc+12:00\";}i:403;a:4:{s:2:\"id\";s:12:\"Pacific/Niue\";s:6:\"offset\";s:9:\"UTC-11:00\";s:5:\"label\";s:24:\"(UTC-11:00) Pacific/Niue\";s:6:\"search\";s:35:\"pacific/niue niue pacific utc-11:00\";}i:404;a:4:{s:2:\"id\";s:15:\"Pacific/Norfolk\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:27:\"(UTC+11:00) Pacific/Norfolk\";s:6:\"search\";s:41:\"pacific/norfolk norfolk pacific utc+11:00\";}i:405;a:4:{s:2:\"id\";s:14:\"Pacific/Noumea\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:26:\"(UTC+11:00) Pacific/Noumea\";s:6:\"search\";s:39:\"pacific/noumea noumea pacific utc+11:00\";}i:406;a:4:{s:2:\"id\";s:17:\"Pacific/Pago_Pago\";s:6:\"offset\";s:9:\"UTC-11:00\";s:5:\"label\";s:29:\"(UTC-11:00) Pacific/Pago_Pago\";s:6:\"search\";s:45:\"pacific/pago_pago pago pago pacific utc-11:00\";}i:407;a:4:{s:2:\"id\";s:13:\"Pacific/Palau\";s:6:\"offset\";s:9:\"UTC+09:00\";s:5:\"label\";s:25:\"(UTC+09:00) Pacific/Palau\";s:6:\"search\";s:37:\"pacific/palau palau pacific utc+09:00\";}i:408;a:4:{s:2:\"id\";s:16:\"Pacific/Pitcairn\";s:6:\"offset\";s:9:\"UTC-08:00\";s:5:\"label\";s:28:\"(UTC-08:00) Pacific/Pitcairn\";s:6:\"search\";s:43:\"pacific/pitcairn pitcairn pacific utc-08:00\";}i:409;a:4:{s:2:\"id\";s:15:\"Pacific/Pohnpei\";s:6:\"offset\";s:9:\"UTC+11:00\";s:5:\"label\";s:27:\"(UTC+11:00) Pacific/Pohnpei\";s:6:\"search\";s:41:\"pacific/pohnpei pohnpei pacific utc+11:00\";}i:410;a:4:{s:2:\"id\";s:20:\"Pacific/Port_Moresby\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:32:\"(UTC+10:00) Pacific/Port_Moresby\";s:6:\"search\";s:51:\"pacific/port_moresby port moresby pacific utc+10:00\";}i:411;a:4:{s:2:\"id\";s:17:\"Pacific/Rarotonga\";s:6:\"offset\";s:9:\"UTC-10:00\";s:5:\"label\";s:29:\"(UTC-10:00) Pacific/Rarotonga\";s:6:\"search\";s:45:\"pacific/rarotonga rarotonga pacific utc-10:00\";}i:412;a:4:{s:2:\"id\";s:14:\"Pacific/Saipan\";s:6:\"offset\";s:9:\"UTC+10:00\";s:5:\"label\";s:26:\"(UTC+10:00) Pacific/Saipan\";s:6:\"search\";s:39:\"pacific/saipan saipan pacific utc+10:00\";}i:413;a:4:{s:2:\"id\";s:14:\"Pacific/Tahiti\";s:6:\"offset\";s:9:\"UTC-10:00\";s:5:\"label\";s:26:\"(UTC-10:00) Pacific/Tahiti\";s:6:\"search\";s:39:\"pacific/tahiti tahiti pacific utc-10:00\";}i:414;a:4:{s:2:\"id\";s:14:\"Pacific/Tarawa\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:26:\"(UTC+12:00) Pacific/Tarawa\";s:6:\"search\";s:39:\"pacific/tarawa tarawa pacific utc+12:00\";}i:415;a:4:{s:2:\"id\";s:17:\"Pacific/Tongatapu\";s:6:\"offset\";s:9:\"UTC+13:00\";s:5:\"label\";s:29:\"(UTC+13:00) Pacific/Tongatapu\";s:6:\"search\";s:45:\"pacific/tongatapu tongatapu pacific utc+13:00\";}i:416;a:4:{s:2:\"id\";s:12:\"Pacific/Wake\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:24:\"(UTC+12:00) Pacific/Wake\";s:6:\"search\";s:35:\"pacific/wake wake pacific utc+12:00\";}i:417;a:4:{s:2:\"id\";s:14:\"Pacific/Wallis\";s:6:\"offset\";s:9:\"UTC+12:00\";s:5:\"label\";s:26:\"(UTC+12:00) Pacific/Wallis\";s:6:\"search\";s:39:\"pacific/wallis wallis pacific utc+12:00\";}i:418;a:4:{s:2:\"id\";s:3:\"UTC\";s:6:\"offset\";s:9:\"UTC+00:00\";s:5:\"label\";s:15:\"(UTC+00:00) UTC\";s:6:\"search\";s:21:\"utc utc utc utc+00:00\";}}', 1787681062);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organizer_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `is_super_admin_event` tinyint(1) NOT NULL DEFAULT 0,
  `color` varchar(255) NOT NULL DEFAULT '#008b8b',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calendar_events`
--

INSERT INTO `calendar_events` (`id`, `organizer_id`, `title`, `description`, `start_time`, `end_time`, `is_super_admin_event`, `color`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'RAD Updates', 'Discuss RAD updates. \r\nAttendees: Soujanya Subedi, Sandeep Karki, Himanshu J Bista\r\nLocaiton: TD', '2026-08-25 14:00:00', '2026-08-25 14:30:00', 1, '#f43f5e', NULL, '2026-08-25 04:46:40', '2026-08-25 04:46:40'),
(2, 2, 'RAD updates', 'Attendees: HImanshu J Bista, Sandeep Karki, Soujanya Subedi\r\nLocation: TD', '2026-08-25 14:00:00', '2026-08-25 14:30:00', 1, '#f43f5e', '2026-08-25 06:29:47', '2026-08-25 04:46:40', '2026-08-25 06:29:47'),
(3, 2, 'Test', 'Test', '2026-08-25 14:00:00', '2026-08-25 15:00:00', 1, '#f43f5e', '2026-08-25 06:30:16', '2026-08-25 04:46:40', '2026-08-25 06:30:16'),
(4, 1, 'test', 'asdasd', '2026-08-25 02:00:00', '2026-08-25 03:00:00', 1, '#f43f5e', '2026-08-25 06:28:43', '2026-08-25 04:46:40', '2026-08-25 06:28:43'),
(5, 1, 'asdasd', 'asd', '2026-08-25 10:00:00', '2026-08-25 11:00:00', 1, '#f43f5e', '2026-08-25 06:30:04', '2026-08-25 04:46:40', '2026-08-25 06:30:04'),
(6, 2, 'Account', 'Attendees: Himanshu J Bista, Tissame N/A, Umesh N/A', '2026-08-26 21:30:00', '2026-08-26 22:00:00', 1, '#f43f5e', NULL, '2026-08-26 07:15:00', '2026-08-26 07:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `calendar_event_user`
--

CREATE TABLE `calendar_event_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `calendar_event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calendar_event_user`
--

INSERT INTO `calendar_event_user` (`id`, `calendar_event_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2026-08-25 04:46:40', '2026-08-25 04:46:40'),
(2, 3, 1, '2026-08-25 04:46:40', '2026-08-25 04:46:40'),
(3, 4, 1, '2026-08-25 04:46:40', '2026-08-25 04:46:40'),
(4, 5, 1, '2026-08-25 04:46:40', '2026-08-25 04:46:40'),
(5, 1, 3, '2026-08-25 06:32:57', '2026-08-25 06:32:57'),
(6, 6, 2, '2026-08-26 07:15:00', '2026-08-26 07:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `checklist_items`
--

CREATE TABLE `checklist_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('To-Do','In-Progress','Completed','Delayed') NOT NULL DEFAULT 'To-Do',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checklist_items`
--

INSERT INTO `checklist_items` (`id`, `user_id`, `title`, `description`, `start_date`, `due_date`, `priority`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Remind Sandeep dai about the isses', 'Issues:\r\nCant add events\r\nCant select attendees', '2026-08-25', NULL, 'high', 'Completed', NULL, '2026-08-24 20:34:24', '2026-08-24 21:24:43'),
(2, 1, 'reasd', 'ads', NULL, NULL, 'medium', 'To-Do', NULL, '2026-08-25 04:57:17', '2026-08-25 04:57:17'),
(3, 1, 'cc', NULL, NULL, NULL, 'medium', 'In-Progress', NULL, '2026-08-25 05:20:57', '2026-08-25 05:21:09'),
(4, 2, 'Give new found issues to Sandeep Dai', 'Default values for dates and time in events and checklist tasks', '2026-08-25', '2026-08-25', 'high', 'Completed', NULL, '2026-08-25 06:34:01', '2026-08-25 08:50:41'),
(5, 2, 'Get follow up timings form HImanshu', 'Get follow up timings from HImanshu dai with Soujanya and Prajjwal dai and schedule in a meeting', '2026-08-26', NULL, 'medium', 'To-Do', NULL, '2026-08-25 19:24:17', '2026-08-25 19:24:17'),
(6, 2, 'Notify Kishant Balami', 'Tell Kishan Balami that you\'ll give him the timings on Thursday', NULL, NULL, 'medium', 'In-Progress', NULL, '2026-08-25 19:25:10', '2026-08-26 07:17:11'),
(7, 2, 'Get timings from Sandeep dai', 'Get timings from Sandeep dai  for his followup with HImanshu dai on VOIP/ PhoneTree', NULL, NULL, 'medium', 'Completed', NULL, '2026-08-25 19:26:20', '2026-08-26 07:17:21'),
(8, 2, 'Create a time for Sandeep Dai and Himanshu Dai\'s follow up on VOIP PhoneTree strategy', NULL, '2026-08-26', '2026-08-28', 'medium', 'To-Do', NULL, '2026-08-26 07:18:02', '2026-08-26 07:18:02'),
(9, 2, 'Find and create a list of things HImanshu dai will say to Tarak Devkota on their call on Thursday', NULL, '2026-08-26', '2026-08-26', 'high', 'To-Do', NULL, '2026-08-26 07:18:59', '2026-08-26 07:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `commentable_id` bigint(20) UNSIGNED NOT NULL,
  `commentable_type` varchar(255) NOT NULL DEFAULT 'App\\Models\\Task',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `commentable_id`, `commentable_type`, `user_id`, `content`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 1, 'App\\Models\\ChecklistItem', 1, 'Test comment via script', '2026-08-25 04:46:19', '2026-08-25 04:46:19', NULL),
(3, 1, 'App\\Models\\Task', 1, 'testr', '2026-08-25 04:56:19', '2026-08-25 04:56:23', '2026-08-25 04:56:23'),
(4, 1, 'App\\Models\\Task', 1, 'asdadsas 111', '2026-08-25 04:56:28', '2026-08-25 04:56:33', NULL),
(5, 1, 'App\\Models\\Task', 1, 'adasasdas d', '2026-08-25 04:56:47', '2026-08-25 04:56:47', NULL),
(6, 1, 'App\\Models\\Task', 1, 'dsas', '2026-08-25 04:57:01', '2026-08-25 04:57:01', NULL),
(7, 2, 'App\\Models\\ChecklistItem', 1, 'asads', '2026-08-25 04:57:23', '2026-08-25 04:57:23', NULL),
(8, 2, 'App\\Models\\ChecklistItem', 1, 'asdas dsa', '2026-08-25 05:00:56', '2026-08-25 05:00:56', NULL),
(9, 3, 'App\\Models\\Task', 1, 'sdol', '2026-08-25 05:12:28', '2026-08-25 05:12:28', NULL),
(10, 3, 'App\\Models\\ChecklistItem', 1, 'gvjhj', '2026-08-25 05:21:30', '2026-08-25 05:21:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `phone`, `email`, `position`, `company`, `notes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Test Name', '12342312', 'testemail@testdoman.com', 'Test Titl', 'Test Comapny', 'test notes', NULL, '2026-08-24 21:24:28', '2026-08-25 06:34:19');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_22_000001_create_organizations_table', 1),
(5, '2026_08_22_000002_create_projects_table', 1),
(6, '2026_08_22_000003_create_organization_user_table', 1),
(7, '2026_08_22_000004_create_project_user_table', 1),
(8, '2026_08_22_000005_create_milestones_table', 1),
(9, '2026_08_22_000006_create_tasks_table', 1),
(10, '2026_08_22_000007_create_wikis_table', 1),
(11, '2026_08_22_000008_create_activity_logs_table', 1),
(12, '2026_08_22_000009_create_project_statuses_table', 1),
(13, '2026_08_22_000010_create_milestone_user_table', 1),
(14, '2026_08_22_000011_update_tasks_table_and_dependencies', 1),
(15, '2026_08_22_000012_create_task_user_table', 1),
(16, '2026_08_22_000013_create_task_comments_table', 1),
(17, '2026_08_22_000014_create_wiki_books_table', 1),
(18, '2026_08_22_000015_create_wiki_chapters_table', 1),
(19, '2026_08_22_000016_create_wiki_pages_table', 1),
(20, '2026_08_22_000017_create_wiki_book_user_table', 1),
(21, '2026_08_22_000018_create_calendar_events_table', 1),
(22, '2026_08_22_000019_create_calendar_event_user_table', 1),
(23, '2026_08_22_000020_create_contacts_table', 1),
(24, '2026_08_22_000021_create_checklist_items_table', 1),
(25, '2026_08_22_000022_add_dates_to_projects_table', 1),
(26, '2026_08_22_000023_add_start_date_to_checklist_items_table', 1),
(27, '2026_08_23_172915_add_color_to_project_statuses_table', 1),
(28, '2026_08_23_173004_morph_task_comments_to_comments', 1),
(29, '2026_08_24_173154_add_deleted_at_to_comments_table', 1),
(30, '2026_08_24_173934_add_performance_indexes_to_core_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `milestones`
--

INSERT INTO `milestones` (`id`, `project_id`, `title`, `description`, `start_date`, `due_date`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'test milestone', 'test description', '2026-08-26', '2026-08-27', 'open', NULL, '2026-08-25 08:52:38', '2026-08-25 08:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `milestone_user`
--

CREATE TABLE `milestone_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `milestone_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `color_code` varchar(255) NOT NULL DEFAULT '#008b8b',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `description`, `color_code`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PragmaCTO', 'An IT Firm providing Fractional CTO services to international clients.', '#008b8b', NULL, '2026-08-24 20:21:00', '2026-08-24 20:21:00'),
(2, 'TD Consulting Group', 'Back-office to Devkota Law Firm\'s global expansion into technology and law.', '#dd3636', NULL, '2026-08-24 20:21:35', '2026-08-24 20:21:51'),
(3, 'Triporah Nepal', 'A modern, next-gen Travel OTA being built in Nepal for Nepalese all over the world.', '#008b8b', NULL, '2026-08-24 20:22:33', '2026-08-24 20:22:33'),
(4, 'AskMeNepal', 'A travel info portal initiative for showcasing nature, culture and heritage.', '#008b8b', NULL, '2026-08-24 20:23:04', '2026-08-24 20:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `organization_user`
--

CREATE TABLE `organization_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'member',
  `position` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_user`
--

INSERT INTO `organization_user` (`id`, `organization_id`, `user_id`, `role`, `position`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 'org_admin', 'Member', '2026-08-25 05:48:09', '2026-08-25 05:48:09'),
(2, 1, 3, 'org_admin', 'Member', '2026-08-25 05:49:24', '2026-08-25 05:49:24'),
(3, 3, 3, 'org_admin', 'Member', '2026-08-25 05:50:00', '2026-08-25 05:50:14'),
(5, 1, 2, 'member', 'example', '2026-08-25 06:27:26', '2026-08-25 06:27:26'),
(6, 2, 2, 'member', 'test', '2026-08-25 06:27:39', '2026-08-25 06:27:39'),
(7, 3, 2, 'member', 'test', '2026-08-25 06:27:57', '2026-08-25 06:27:57'),
(8, 4, 2, 'member', 'test', '2026-08-25 06:28:05', '2026-08-25 06:28:05');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `abbreviation` varchar(10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `organization_id`, `name`, `description`, `abbreviation`, `start_date`, `due_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Entervu - Website & Backend', 'Revamping https://www.entervu.com.', 'EWB', '2026-04-04', NULL, NULL, '2026-08-24 20:25:32', '2026-08-24 20:25:32'),
(2, 1, 'Entervu - Application & Enterprise', 'Maintenance and development of Entervu\'s mobile applications and enterprise dashboard.', 'EAE', '2026-08-25', NULL, NULL, '2026-08-24 20:26:47', '2026-08-24 20:26:47'),
(3, 1, 'Entervu - Application & Enterprise', 'Maintenance and development of Entervu\'s mobile applications and enterprise dashboard.', 'EAE', '2026-08-25', NULL, NULL, '2026-08-24 20:26:51', '2026-08-24 20:26:51'),
(4, 2, 'Vantage AMT', NULL, 'VAMT', NULL, NULL, NULL, '2026-08-25 05:08:31', '2026-08-25 05:08:31'),
(5, 3, 'vff', NULL, 'DD', NULL, NULL, NULL, '2026-08-25 05:54:39', '2026-08-25 05:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `project_statuses`
--

CREATE TABLE `project_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#008b8b',
  `is_mandatory` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_statuses`
--

INSERT INTO `project_statuses` (`id`, `project_id`, `name`, `slug`, `color`, `is_mandatory`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'New', 'new', '#008b8b', 1, 1, '2026-08-25 04:43:21', '2026-08-25 04:43:21'),
(2, 1, 'In-Progress', 'in-progress', '#3b82f6', 1, 2, '2026-08-25 04:43:21', '2026-08-25 04:43:21'),
(4, 1, 'Testing', 'testing', '#ec4899', 0, 4, '2026-08-25 04:43:21', '2026-08-25 04:43:21'),
(5, 1, 'Completed', 'completed', '#10b981', 1, 5, '2026-08-25 04:43:21', '2026-08-25 04:43:21'),
(6, 1, 'Reopened', 'reopened', '#f97316', 0, 6, '2026-08-25 04:43:21', '2026-08-25 04:43:21'),
(7, 1, 'On Hold', 'on-hold', '#f59e0b', 1, 7, '2026-08-25 04:43:21', '2026-08-25 04:43:21'),
(8, 1, 'Backlog', 'backlog', '#64748b', 0, 8, '2026-08-25 04:43:21', '2026-08-25 04:43:21'),
(9, 4, 'New', 'new', '#008b8b', 1, 1, '2026-08-25 05:08:36', '2026-08-25 05:08:36'),
(10, 4, 'In-Progress', 'in-progress', '#3b82f6', 1, 2, '2026-08-25 05:08:36', '2026-08-25 05:08:36'),
(11, 4, 'In-Review', 'in-review', '#8b5cf6', 0, 3, '2026-08-25 05:08:36', '2026-08-25 05:08:36'),
(12, 4, 'Testing', 'testing', '#ec4899', 0, 4, '2026-08-25 05:08:36', '2026-08-25 05:08:36'),
(13, 4, 'Completed', 'completed', '#10b981', 1, 5, '2026-08-25 05:08:36', '2026-08-25 05:08:36'),
(14, 4, 'Reopened', 'reopened', '#f97316', 0, 6, '2026-08-25 05:08:36', '2026-08-25 05:08:36'),
(15, 4, 'On Hold', 'on-hold', '#f59e0b', 1, 7, '2026-08-25 05:08:36', '2026-08-25 05:08:36'),
(16, 4, 'Backlog', 'backlog', '#64748b', 0, 8, '2026-08-25 05:08:36', '2026-08-25 05:08:36'),
(17, 1, 'tes', 'tes', '#8b5cf6', 0, 9, '2026-08-25 07:37:40', '2026-08-25 07:37:40'),
(18, 1, 'In-Review', 'in-review', '#8b5cf6', 0, 10, '2026-08-25 08:54:19', '2026-08-25 08:54:19'),
(19, 5, 'New', 'new', '#008b8b', 1, 1, '2026-08-26 02:08:12', '2026-08-26 02:08:12'),
(20, 5, 'In-Progress', 'in-progress', '#3b82f6', 1, 2, '2026-08-26 02:08:12', '2026-08-26 02:08:12'),
(21, 5, 'In-Review', 'in-review', '#8b5cf6', 0, 3, '2026-08-26 02:08:12', '2026-08-26 02:08:12'),
(22, 5, 'Testing', 'testing', '#ec4899', 0, 4, '2026-08-26 02:08:12', '2026-08-26 02:08:12'),
(23, 5, 'Completed', 'completed', '#10b981', 1, 5, '2026-08-26 02:08:12', '2026-08-26 02:08:12'),
(24, 5, 'Reopened', 'reopened', '#f97316', 0, 6, '2026-08-26 02:08:12', '2026-08-26 02:08:12'),
(25, 5, 'On Hold', 'on-hold', '#f59e0b', 1, 7, '2026-08-26 02:08:12', '2026-08-26 02:08:12'),
(26, 5, 'Backlog', 'backlog', '#64748b', 0, 8, '2026-08-26 02:08:12', '2026-08-26 02:08:12');

-- --------------------------------------------------------

--
-- Table structure for table `project_user`
--

CREATE TABLE `project_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'member',
  `position` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_user`
--

INSERT INTO `project_user` (`id`, `project_id`, `user_id`, `role`, `position`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'project_admin', 'Team Member', '2026-08-25 05:52:41', '2026-08-25 05:52:52');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5nYd9haGZRGDY0jHNjUvcEhLi1TmfgseE3bAnApQ', 1, '103.129.134.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJOZGNCdGtDMFpBaE1xcGFYdXpQN0FjYTBQanJ5T05NSU1TQ3JSUkZCIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsIl9mbGFzaCI6eyJuZXciOltdLCJvbGQiOltdfSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9wcmFnbWF0aWNrLnByYWdtYWN0by5jb21cL3Byb2plY3RzXC8xIiwicm91dGUiOiJwcm9qZWN0cy5zaG93In19', 1787765878),
('JmP57EGvSiMhpy8F0RemUxaGRrx4E00fUBr4p8Nc', NULL, '103.24.232.17', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'eyJfdG9rZW4iOiJtSmZzZFFVaVl4ZklBOVpyWHpnVXJGTmZkZzRRdUJReFg3aHZMRDRRIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL3ByYWdtYXRpY2sucHJhZ21hY3RvLmNvbSJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL3ByYWdtYXRpY2sucHJhZ21hY3RvLmNvbVwvbG9naW4iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1787763912);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `milestone_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'feature',
  `description` text DEFAULT NULL,
  `priority` varchar(255) NOT NULL DEFAULT 'medium',
  `status` varchar(255) NOT NULL DEFAULT 'todo',
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `milestone_id`, `parent_id`, `assigned_to`, `code`, `title`, `type`, `description`, `priority`, `status`, `start_date`, `due_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, 'EWB-1', 'sad', 'feature', 'asd', 'medium', 'In-Review', '2026-08-24', NULL, NULL, '2026-08-25 04:53:25', '2026-08-25 08:53:29'),
(2, 4, NULL, NULL, NULL, 'VAMT-1', 'test1', 'feature', 'yi', 'medium', 'New', NULL, NULL, NULL, '2026-08-25 05:08:51', '2026-08-25 05:08:51'),
(3, 4, NULL, 2, NULL, 'VAMT-2', 'tes', 'bug', 'hbj', 'urgent', 'In-Review', '2026-08-26', '2026-08-27', NULL, '2026-08-25 05:09:39', '2026-08-25 05:09:39'),
(4, 4, NULL, NULL, NULL, 'VAMT-3', 'd', 'documentation', NULL, 'medium', 'New', NULL, NULL, NULL, '2026-08-25 05:10:57', '2026-08-25 05:10:57');

-- --------------------------------------------------------

--
-- Table structure for table `task_user`
--

CREATE TABLE `task_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `emails` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `emails`, `phone_number`, `is_super_admin`, `email_verified_at`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@pragmacto.com', '[\"superadmin@pragmacto.com\"]', '+1-555-0000', 1, NULL, '$2y$12$KZwXc.iZ1J.2L1jLB/T6pepC0imPOsQ9HIWSNQa//sHCJPOOA5Oya', NULL, NULL, '2026-08-24 12:19:07', '2026-08-24 12:19:07'),
(2, 'Nawadit Sharma', 'nawadit.sharma@pragmacto.com', '[\"nawadit.sharma@pragmacto.com\"]', '9768820900', 1, NULL, '$2y$12$yWga/ltNolo/JuxRLgIZpu9C.SmOp4kZ3y0iM8xVUvjWAu5a4ST.S', NULL, NULL, '2026-08-24 20:22:06', '2026-08-24 20:22:06'),
(3, 'shweta khanal', 'skhanal@devkotalawfirm.com', '[\"skhanal@devkotalawfirm.com\"]', '1212123435', 0, NULL, '$2y$12$.q0MjOOT084JKOyoFMxLZ.tzF0OsB3/Z7p9mYlFodeis2WVixLY1W', NULL, NULL, '2026-08-25 05:22:43', '2026-08-25 05:22:43'),
(4, 'sandeep karki', 'skarki@devkotalawfirm.com', '[\"skarki@devkotalawfirm.com\"]', '562344', 0, NULL, '$2y$12$9E2IEA4ew0vVPbfZDQX75epvCK3HmDL1wre6fqw8HI8NbKnouBXjW', NULL, NULL, '2026-08-25 05:48:57', '2026-08-25 05:48:57');

-- --------------------------------------------------------

--
-- Table structure for table `wikis`
--

CREATE TABLE `wikis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wiki_books`
--

CREATE TABLE `wiki_books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `owner_type` varchar(255) DEFAULT NULL,
  `owner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wiki_books`
--

INSERT INTO `wiki_books` (`id`, `author_id`, `owner_type`, `owner_id`, `title`, `slug`, `description`, `is_private`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Organization', 1, 'test', 'test', 'dasd', 0, NULL, '2026-08-24 21:44:41', '2026-08-24 21:44:41'),
(2, 2, NULL, NULL, 'Test book by Nawadit', 'test-book-by-nawadit', 'testing wiki', 1, NULL, '2026-08-25 06:35:12', '2026-08-25 06:35:12'),
(3, 4, NULL, NULL, 'edcd', 'edcd', 'dc', 0, NULL, '2026-08-26 07:14:56', '2026-08-26 07:14:56');

-- --------------------------------------------------------

--
-- Table structure for table `wiki_book_user`
--

CREATE TABLE `wiki_book_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wiki_book_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wiki_chapters`
--

CREATE TABLE `wiki_chapters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wiki_book_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wiki_chapters`
--

INSERT INTO `wiki_chapters` (`id`, `wiki_book_id`, `title`, `slug`, `description`, `order`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'tesasd', 'tesasd', 'asdasd', 1, NULL, '2026-08-24 21:44:53', '2026-08-24 21:44:53'),
(2, 2, 'test chapter', 'test-chapter', 'test chapter description', 1, NULL, '2026-08-25 06:35:30', '2026-08-25 06:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `wiki_pages`
--

CREATE TABLE `wiki_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wiki_chapter_id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wiki_pages`
--

INSERT INTO `wiki_pages` (`id`, `wiki_chapter_id`, `author_id`, `title`, `slug`, `content`, `order`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'sadasdadsasd', 'sadasdadsasd', '#sfdsdsa', 1, NULL, '2026-08-24 21:57:29', '2026-08-24 21:57:29'),
(2, 2, 2, 'test page title', 'test-page-title', '<h1>Hello</h1><h3>skfa</h3><div><ol><li>randonmm</li></ol><div><pre style=\"background: var(--bg-surface-elevated); border: 1px solid var(--border-color); padding: 0.75rem 1rem; border-radius: 6px; font-family: monospace; font-size: 0.86rem; margin: 0.75rem 0px; color: var(--primary);\"><code>// Enter code snippet or diagram here...</code></pre><blockquote>\"a sdfja\"&nbsp;<br>\"asfdadjslf\"&nbsp;<br>\" sdf aksdlf \"<br><blockquote>asdfadfadsfasf asdfadf adsfasdf</blockquote></blockquote></div></div>', 1, NULL, '2026-08-25 06:37:14', '2026-08-25 06:37:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar_events_organizer_id_foreign` (`organizer_id`);

--
-- Indexes for table `calendar_event_user`
--
ALTER TABLE `calendar_event_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `calendar_event_user_calendar_event_id_user_id_unique` (`calendar_event_id`,`user_id`),
  ADD KEY `calendar_event_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `checklist_items`
--
ALTER TABLE `checklist_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checklist_items_user_id_foreign` (`user_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_comments_task_id_foreign` (`commentable_id`),
  ADD KEY `task_comments_user_id_foreign` (`user_id`),
  ADD KEY `idx_comments_perf` (`commentable_type`,`commentable_id`,`deleted_at`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `milestones_project_id_foreign` (`project_id`);

--
-- Indexes for table `milestone_user`
--
ALTER TABLE `milestone_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `milestone_user_milestone_id_user_id_unique` (`milestone_id`,`user_id`),
  ADD KEY `milestone_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_user`
--
ALTER TABLE `organization_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `organization_user_organization_id_user_id_unique` (`organization_id`,`user_id`),
  ADD KEY `organization_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_organization_id_foreign` (`organization_id`);

--
-- Indexes for table `project_statuses`
--
ALTER TABLE `project_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_statuses_project_id_slug_unique` (`project_id`,`slug`);

--
-- Indexes for table `project_user`
--
ALTER TABLE `project_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_user_project_id_user_id_unique` (`project_id`,`user_id`),
  ADD KEY `project_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_milestone_id_foreign` (`milestone_id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_parent_id_foreign` (`parent_id`),
  ADD KEY `idx_tasks_perf` (`project_id`,`status`,`deleted_at`);

--
-- Indexes for table `task_user`
--
ALTER TABLE `task_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `task_user_task_id_user_id_unique` (`task_id`,`user_id`),
  ADD KEY `task_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wikis`
--
ALTER TABLE `wikis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wikis_organization_id_foreign` (`organization_id`),
  ADD KEY `wikis_project_id_foreign` (`project_id`),
  ADD KEY `wikis_author_id_foreign` (`author_id`);

--
-- Indexes for table `wiki_books`
--
ALTER TABLE `wiki_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wiki_books_author_id_foreign` (`author_id`),
  ADD KEY `wiki_books_owner_type_owner_id_index` (`owner_type`,`owner_id`);

--
-- Indexes for table `wiki_book_user`
--
ALTER TABLE `wiki_book_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wiki_book_user_wiki_book_id_user_id_unique` (`wiki_book_id`,`user_id`),
  ADD KEY `wiki_book_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `wiki_chapters`
--
ALTER TABLE `wiki_chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wiki_chapters_wiki_book_id_foreign` (`wiki_book_id`);

--
-- Indexes for table `wiki_pages`
--
ALTER TABLE `wiki_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wiki_pages_wiki_chapter_id_foreign` (`wiki_chapter_id`),
  ADD KEY `wiki_pages_author_id_foreign` (`author_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `calendar_event_user`
--
ALTER TABLE `calendar_event_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `checklist_items`
--
ALTER TABLE `checklist_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `milestone_user`
--
ALTER TABLE `milestone_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `organization_user`
--
ALTER TABLE `organization_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `project_statuses`
--
ALTER TABLE `project_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `project_user`
--
ALTER TABLE `project_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `task_user`
--
ALTER TABLE `task_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wikis`
--
ALTER TABLE `wikis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wiki_books`
--
ALTER TABLE `wiki_books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wiki_book_user`
--
ALTER TABLE `wiki_book_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wiki_chapters`
--
ALTER TABLE `wiki_chapters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wiki_pages`
--
ALTER TABLE `wiki_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD CONSTRAINT `calendar_events_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `calendar_event_user`
--
ALTER TABLE `calendar_event_user`
  ADD CONSTRAINT `calendar_event_user_calendar_event_id_foreign` FOREIGN KEY (`calendar_event_id`) REFERENCES `calendar_events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calendar_event_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `checklist_items`
--
ALTER TABLE `checklist_items`
  ADD CONSTRAINT `checklist_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `task_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `milestones`
--
ALTER TABLE `milestones`
  ADD CONSTRAINT `milestones_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `milestone_user`
--
ALTER TABLE `milestone_user`
  ADD CONSTRAINT `milestone_user_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `milestone_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organization_user`
--
ALTER TABLE `organization_user`
  ADD CONSTRAINT `organization_user_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `organization_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_statuses`
--
ALTER TABLE `project_statuses`
  ADD CONSTRAINT `project_statuses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_user`
--
ALTER TABLE `project_user`
  ADD CONSTRAINT `project_user_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_milestone_id_foreign` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_user`
--
ALTER TABLE `task_user`
  ADD CONSTRAINT `task_user_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wikis`
--
ALTER TABLE `wikis`
  ADD CONSTRAINT `wikis_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wikis_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wikis_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wiki_books`
--
ALTER TABLE `wiki_books`
  ADD CONSTRAINT `wiki_books_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wiki_book_user`
--
ALTER TABLE `wiki_book_user`
  ADD CONSTRAINT `wiki_book_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wiki_book_user_wiki_book_id_foreign` FOREIGN KEY (`wiki_book_id`) REFERENCES `wiki_books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wiki_chapters`
--
ALTER TABLE `wiki_chapters`
  ADD CONSTRAINT `wiki_chapters_wiki_book_id_foreign` FOREIGN KEY (`wiki_book_id`) REFERENCES `wiki_books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wiki_pages`
--
ALTER TABLE `wiki_pages`
  ADD CONSTRAINT `wiki_pages_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wiki_pages_wiki_chapter_id_foreign` FOREIGN KEY (`wiki_chapter_id`) REFERENCES `wiki_chapters` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
