-- PragmaTick Production Clean Database Dump
-- Generated: 2026-08-22 14:01:05
-- Contains strictly clean schema & initial Super Admin user (superadmin@pragmacto.com)

PRAGMA foreign_keys = OFF;
BEGIN TRANSACTION;

-- Table structure for table `activity_logs` --
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE "activity_logs" ("id" integer primary key autoincrement not null, "user_id" integer, "action" varchar not null, "description" text, "subject_type" varchar, "subject_id" integer, "properties" text, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete set null);

-- Table structure for table `cache` --
DROP TABLE IF EXISTS `cache`;
CREATE TABLE "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));

-- Table structure for table `cache_locks` --
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));

-- Table structure for table `calendar_event_user` --
DROP TABLE IF EXISTS `calendar_event_user`;
CREATE TABLE "calendar_event_user" ("id" integer primary key autoincrement not null, "calendar_event_id" integer not null, "user_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("calendar_event_id") references "calendar_events"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);

-- Table structure for table `calendar_events` --
DROP TABLE IF EXISTS `calendar_events`;
CREATE TABLE "calendar_events" ("id" integer primary key autoincrement not null, "organizer_id" integer not null, "title" varchar not null, "description" text, "start_time" datetime not null, "end_time" datetime not null, "is_super_admin_event" tinyint(1) not null default '0', "color" varchar not null default '#008b8b', "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("organizer_id") references "users"("id") on delete cascade);

-- Table structure for table `checklist_items` --
DROP TABLE IF EXISTS `checklist_items`;
CREATE TABLE "checklist_items" ("id" integer primary key autoincrement not null, "user_id" integer not null, "title" varchar not null, "description" text, "due_date" date, "priority" varchar check ("priority" in ('low', 'medium', 'high', 'urgent')) not null default 'medium', "status" varchar check ("status" in ('To-Do', 'In-Progress', 'Completed', 'Delayed')) not null default 'To-Do', "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, "start_date" date, foreign key("user_id") references "users"("id") on delete cascade);

-- Table structure for table `contacts` --
DROP TABLE IF EXISTS `contacts`;
CREATE TABLE "contacts" ("id" integer primary key autoincrement not null, "name" varchar not null, "phone" varchar, "email" varchar, "position" varchar, "company" varchar, "notes" text, "deleted_at" datetime, "created_at" datetime, "updated_at" datetime);

-- Table structure for table `failed_jobs` --
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" varchar not null, "queue" varchar not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);

-- Table structure for table `job_batches` --
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));

-- Table structure for table `jobs` --
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);

-- Table structure for table `migrations` --
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);

-- Dumping data for table `migrations` --
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_08_22_000001_create_organizations_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_08_22_000002_create_projects_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_08_22_000003_create_organization_user_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_08_22_000004_create_project_user_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_08_22_000005_create_milestones_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_08_22_000006_create_tasks_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_08_22_000007_create_wikis_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_08_22_000008_create_activity_logs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_08_22_000009_create_project_statuses_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_08_22_000010_create_milestone_user_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2026_08_22_000011_update_tasks_table_and_dependencies', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('15', '2026_08_22_000012_create_task_user_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('16', '2026_08_22_000013_create_task_comments_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('17', '2026_08_22_000014_create_wiki_books_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('18', '2026_08_22_000015_create_wiki_chapters_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('19', '2026_08_22_000016_create_wiki_pages_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('20', '2026_08_22_000017_create_wiki_book_user_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('21', '2026_08_22_000018_create_calendar_events_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('22', '2026_08_22_000019_create_calendar_event_user_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('23', '2026_08_22_000020_create_contacts_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('24', '2026_08_22_000021_create_checklist_items_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('25', '2026_08_22_000022_add_dates_to_projects_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('26', '2026_08_22_000023_add_start_date_to_checklist_items_table', '1');

-- Table structure for table `milestone_user` --
DROP TABLE IF EXISTS `milestone_user`;
CREATE TABLE "milestone_user" ("id" integer primary key autoincrement not null, "milestone_id" integer not null, "user_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("milestone_id") references "milestones"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);

-- Table structure for table `milestones` --
DROP TABLE IF EXISTS `milestones`;
CREATE TABLE "milestones" ("id" integer primary key autoincrement not null, "project_id" integer not null, "title" varchar not null, "description" text, "start_date" date, "due_date" date, "status" varchar not null default 'open', "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("project_id") references "projects"("id") on delete cascade);

-- Table structure for table `organization_user` --
DROP TABLE IF EXISTS `organization_user`;
CREATE TABLE "organization_user" ("id" integer primary key autoincrement not null, "organization_id" integer not null, "user_id" integer not null, "role" varchar not null default 'member', "position" varchar, "created_at" datetime, "updated_at" datetime, foreign key("organization_id") references "organizations"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);

-- Table structure for table `organizations` --
DROP TABLE IF EXISTS `organizations`;
CREATE TABLE "organizations" ("id" integer primary key autoincrement not null, "name" varchar not null, "description" text, "color_code" varchar not null default '#008b8b', "deleted_at" datetime, "created_at" datetime, "updated_at" datetime);

-- Table structure for table `password_reset_tokens` --
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));

-- Table structure for table `project_statuses` --
DROP TABLE IF EXISTS `project_statuses`;
CREATE TABLE "project_statuses" ("id" integer primary key autoincrement not null, "project_id" integer not null, "name" varchar not null, "slug" varchar not null, "color" varchar not null default '#008b8b', "is_mandatory" tinyint(1) not null default '0', "order" integer not null default '0', "created_at" datetime, "updated_at" datetime, foreign key("project_id") references "projects"("id") on delete cascade);

-- Table structure for table `project_user` --
DROP TABLE IF EXISTS `project_user`;
CREATE TABLE "project_user" ("id" integer primary key autoincrement not null, "project_id" integer not null, "user_id" integer not null, "role" varchar not null default 'member', "position" varchar, "created_at" datetime, "updated_at" datetime, foreign key("project_id") references "projects"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);

-- Table structure for table `projects` --
DROP TABLE IF EXISTS `projects`;
CREATE TABLE "projects" ("id" integer primary key autoincrement not null, "organization_id" integer not null, "name" varchar not null, "description" text, "abbreviation" varchar, "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, "start_date" date, "due_date" date, foreign key("organization_id") references "organizations"("id") on delete cascade);

-- Table structure for table `sessions` --
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));

-- Table structure for table `task_comments` --
DROP TABLE IF EXISTS `task_comments`;
CREATE TABLE "task_comments" ("id" integer primary key autoincrement not null, "task_id" integer not null, "user_id" integer not null, "content" text not null, "created_at" datetime, "updated_at" datetime, foreign key("task_id") references "tasks"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);

-- Table structure for table `task_user` --
DROP TABLE IF EXISTS `task_user`;
CREATE TABLE "task_user" ("id" integer primary key autoincrement not null, "task_id" integer not null, "user_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("task_id") references "tasks"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);

-- Table structure for table `tasks` --
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE "tasks" ("id" integer primary key autoincrement not null, "project_id" integer not null, "milestone_id" integer, "assigned_to" integer, "title" varchar not null, "description" text, "priority" varchar not null default ('medium'), "status" varchar not null default ('todo'), "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, "type" varchar not null default 'feature', "start_date" date, "due_date" date, "parent_id" integer, foreign key("assigned_to") references users("id") on delete set null on update no action, foreign key("milestone_id") references milestones("id") on delete set null on update no action, foreign key("project_id") references projects("id") on delete cascade on update no action, foreign key("parent_id") references "tasks"("id") on delete set null);

-- Table structure for table `users` --
DROP TABLE IF EXISTS `users`;
CREATE TABLE "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "emails" text, "phone_number" varchar, "is_super_admin" tinyint(1) not null default '0', "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "deleted_at" datetime, "created_at" datetime, "updated_at" datetime);

-- Dumping data for table `users` --
INSERT INTO `users` (`id`, `name`, `email`, `emails`, `phone_number`, `is_super_admin`, `email_verified_at`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES ('1', 'Super Admin', 'superadmin@pragmacto.com', '["superadmin@pragmacto.com"]', '+1-555-0000', '1', NULL, '$2y$12$X7.hA370thRHI/gFIjnAqewJa5KrcNwb1LwK6OiBufxVEtffStoYG', NULL, NULL, '2026-08-22 14:00:52', '2026-08-22 14:00:52');

-- Table structure for table `wiki_book_user` --
DROP TABLE IF EXISTS `wiki_book_user`;
CREATE TABLE "wiki_book_user" ("id" integer primary key autoincrement not null, "wiki_book_id" integer not null, "user_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("wiki_book_id") references "wiki_books"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);

-- Table structure for table `wiki_books` --
DROP TABLE IF EXISTS `wiki_books`;
CREATE TABLE "wiki_books" ("id" integer primary key autoincrement not null, "author_id" integer not null, "owner_type" varchar, "owner_id" integer, "title" varchar not null, "slug" varchar not null, "description" text, "is_private" tinyint(1) not null default '0', "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("author_id") references "users"("id") on delete cascade);

-- Table structure for table `wiki_chapters` --
DROP TABLE IF EXISTS `wiki_chapters`;
CREATE TABLE "wiki_chapters" ("id" integer primary key autoincrement not null, "wiki_book_id" integer not null, "title" varchar not null, "slug" varchar not null, "description" text, "order" integer not null default '0', "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("wiki_book_id") references "wiki_books"("id") on delete cascade);

-- Table structure for table `wiki_pages` --
DROP TABLE IF EXISTS `wiki_pages`;
CREATE TABLE "wiki_pages" ("id" integer primary key autoincrement not null, "wiki_chapter_id" integer not null, "author_id" integer not null, "title" varchar not null, "slug" varchar not null, "content" text, "order" integer not null default '0', "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("wiki_chapter_id") references "wiki_chapters"("id") on delete cascade, foreign key("author_id") references "users"("id") on delete cascade);

-- Table structure for table `wikis` --
DROP TABLE IF EXISTS `wikis`;
CREATE TABLE "wikis" ("id" integer primary key autoincrement not null, "organization_id" integer, "project_id" integer, "author_id" integer not null, "title" varchar not null, "slug" varchar not null, "content" text, "deleted_at" datetime, "created_at" datetime, "updated_at" datetime, foreign key("organization_id") references "organizations"("id") on delete cascade, foreign key("project_id") references "projects"("id") on delete cascade, foreign key("author_id") references "users"("id") on delete cascade);

COMMIT;
PRAGMA foreign_keys = ON;
