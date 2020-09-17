/*
SQLyog Community v13.1.5  (64 bit)
MySQL - 10.4.8-MariaDB : Database - v-school
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`v-school` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `v-school`;

/*Table structure for table `all_posts` */

DROP TABLE IF EXISTS `all_posts`;

CREATE TABLE `all_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` bigint(20) unsigned NOT NULL,
  `readCnt` int(11) NOT NULL DEFAULT 0,
  `likeCnt` int(11) NOT NULL DEFAULT 0,
  `isLiked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `all_posts_from_foreign` (`from`),
  CONSTRAINT `all_posts_from_foreign` FOREIGN KEY (`from`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `all_posts` */

insert  into `all_posts`(`id`,`title`,`content`,`img`,`from`,`readCnt`,`likeCnt`,`isLiked`,`created_at`,`updated_at`) values 
(1,'Shania Gleason','Rerum nulla sit consequatur consequatur dolorum incidunt maiores. Quia eligendi quia quasi est ullam explicabo.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 17:58:12'),
(2,'Dr. Haley Bartoletti V','Et quia animi occaecati. Molestiae repellat impedit exercitationem et aut qui in. Quaerat ut et aliquam. Eos dolore aut alias quam ullam qui cumque.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:43','2020-09-04 17:23:44'),
(3,'Dr. Carli Stamm V','Esse voluptas nobis enim architecto. Quos debitis nobis velit corporis ipsa eius illum. Et modi quibusdam recusandae quia odit aut.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(4,'Edward Kihn','Eum dignissimos unde est aliquid sint quia. Fugit et quibusdam amet aut. Qui dolores incidunt aut error ea. Temporibus minus asperiores molestias officiis ut repellendus exercitationem.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(5,'Wayne Grimes','Beatae veniam vel dolores quia facere fugiat sit. Autem magnam laudantium ipsa repellendus illo. Expedita repellendus quo ut ipsa explicabo. Consequatur illum beatae fuga dolores.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(6,'Miss Mabelle Ullrich','Magnam quis ut repellendus. Quos est nobis tempore qui est. Eum sunt iste doloribus consequuntur delectus deserunt nulla. Harum commodi enim id aspernatur.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(7,'Josephine Jast','Laudantium veniam deserunt aliquid quaerat eos aut omnis. Quas quia accusantium suscipit quia. Eligendi magni aut praesentium quo dicta. Quibusdam quae qui ut provident nobis est.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(8,'Mr. Glen Schimmel I','Autem sit ratione deleniti et iste aut voluptas asperiores. Earum exercitationem ut quisquam perferendis reiciendis earum nihil quia. Reiciendis magnam non rerum.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(9,'Cheyanne Rolfson','Laborum ut id repellendus eveniet non maiores enim. Magnam enim animi nihil veniam rerum amet.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(10,'Mittie Terry DDS','Amet nemo ut qui incidunt provident assumenda dicta. Possimus reiciendis suscipit incidunt est facilis nam dolorum. Animi nobis sed at est mollitia dolorum magni.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(11,'Chet Hartmann','Maiores ducimus saepe eos accusamus nobis beatae. Eaque ratione deleniti debitis exercitationem omnis omnis est deleniti. Vel aut non numquam qui culpa. Rerum natus et voluptatibus autem.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(12,'Eric Blanda MD','Nisi vel et nam hic. Quibusdam eius eum aut sit enim. Consequatur eum exercitationem qui accusamus voluptas. Excepturi quo at labore quod.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:43','2020-09-04 17:23:06'),
(13,'Aniya Gerhold','Enim voluptatem omnis et et et. Alias dolor voluptatum possimus maiores laboriosam eos. Occaecati voluptatem qui qui nostrum. Exercitationem sint nisi et hic natus incidunt maxime.','/uploads/1599176161.jpeg',1,0,0,0,'2020-09-04 15:01:43','2020-09-04 15:01:43'),
(14,'Reginald Boyer IV','Reprehenderit perferendis beatae odit modi vel quis. Culpa vitae nulla odio sint. Aperiam sit rerum voluptas et totam nihil sapiente. Suscipit blanditiis nesciunt asperiores ut tempora porro.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:43','2020-09-04 17:23:14'),
(15,'Joannie Brekke','Dolorem officiis aut in vel magni. Aut nihil molestiae dolore nemo at voluptas consequatur et.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:44','2020-09-04 17:23:17'),
(16,'Amira Pfeffer','Exercitationem quia earum nisi ipsum unde maxime. Et sed non rerum perspiciatis doloremque. Nostrum suscipit libero quos nihil consequatur. Qui enim in dolore.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:44','2020-09-04 17:23:20'),
(17,'Jarrell Kirlin','Dolor rem eos asperiores repudiandae inventore sit provident. Repellendus qui et repellendus et. Labore ducimus voluptas distinctio eligendi et beatae explicabo. Quas ipsa molestias et porro aut.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:44','2020-09-04 17:23:22'),
(18,'Chase Hegmann','Vitae qui dolorem eum velit. Aspernatur ut ipsam qui qui ex. Voluptatem dolores explicabo ab odit enim qui rerum.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:44','2020-09-04 17:23:25'),
(19,'Ima Swaniawski','Eum suscipit quisquam neque incidunt deserunt. Et qui vel modi sunt nesciunt est. Eaque tempore aut tempore temporibus.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:44','2020-09-04 17:23:28'),
(20,'Jasmin DuBuque','Laborum velit quia occaecati ipsam. Quibusdam ab adipisci quis saepe ut. Rerum non aspernatur magnam necessitatibus. Qui laborum non atque et. Laboriosam quae animi sint aspernatur.','/uploads/1599176161.jpeg',1,0,1,1,'2020-09-04 15:01:44','2020-09-04 17:23:31');

/*Table structure for table `blogcategories` */

DROP TABLE IF EXISTS `blogcategories`;

CREATE TABLE `blogcategories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `categoryId` bigint(20) unsigned NOT NULL,
  `blogId` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blogcategories_categoryid_foreign` (`categoryId`),
  KEY `blogcategories_blogid_foreign` (`blogId`),
  CONSTRAINT `blogcategories_blogid_foreign` FOREIGN KEY (`blogId`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blogcategories_categoryid_foreign` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `blogcategories` */

/*Table structure for table `blogs` */

DROP TABLE IF EXISTS `blogs`;

CREATE TABLE `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userId` bigint(20) unsigned NOT NULL,
  `featuredImage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metaDescription` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`),
  KEY `blogs_userid_foreign` (`userId`),
  CONSTRAINT `blogs_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `blogs` */

/*Table structure for table `blogtags` */

DROP TABLE IF EXISTS `blogtags`;

CREATE TABLE `blogtags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tagId` bigint(20) unsigned NOT NULL,
  `blogId` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blogtags_tagid_foreign` (`tagId`),
  KEY `blogtags_blogid_foreign` (`blogId`),
  CONSTRAINT `blogtags_blogid_foreign` FOREIGN KEY (`blogId`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blogtags_tagid_foreign` FOREIGN KEY (`tagId`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `blogtags` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iconImage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categories` */

insert  into `categories`(`id`,`categoryName`,`iconImage`,`created_at`,`updated_at`) values 
(2,'test','/uploads/1599176161.jpeg','2020-09-03 23:36:04','2020-09-03 23:36:04');

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2020_08_30_144737_create_blogs_table',1),
(5,'2020_08_30_145128_create_categories_table',1),
(6,'2020_08_30_224050_create_tags_table',1),
(7,'2020_08_30_224111_create_blogtags_table',1),
(8,'2020_08_30_224124_create_blogcategories_table',1),
(9,'2020_09_02_230534_create_user_roles_table',1),
(10,'2020_09_03_002823_add_colum_role_id_to_users',1),
(14,'2020_09_04_094641_create_all_posts_table',2);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `tags` */

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tagName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `tags` */

insert  into `tags`(`id`,`tagName`,`created_at`,`updated_at`) values 
(1,'laravel tag','2020-09-03 16:25:08','2020-09-03 16:25:08'),
(2,'test','2020-09-03 16:34:14','2020-09-03 16:34:14'),
(3,'test','2020-09-04 09:11:50','2020-09-04 09:11:50'),
(4,'t23r','2020-09-04 09:11:55','2020-09-04 09:11:55'),
(5,'234234','2020-09-04 09:11:59','2020-09-04 09:11:59'),
(6,'2323r23','2020-09-04 09:12:04','2020-09-04 09:12:04'),
(7,'423r23r2','2020-09-04 09:12:08','2020-09-04 09:12:08'),
(8,'222','2020-09-04 09:12:13','2020-09-04 09:12:13'),
(9,'222','2020-09-04 09:12:16','2020-09-04 09:12:16'),
(10,'222','2020-09-04 09:12:20','2020-09-04 09:12:20'),
(11,'2222','2020-09-04 09:12:24','2020-09-04 09:12:24'),
(12,'2','2020-09-04 09:12:30','2020-09-04 09:12:30'),
(13,'3423','2020-09-04 09:12:34','2020-09-04 09:12:34'),
(14,'12123','2020-09-04 09:12:38','2020-09-04 09:12:38'),
(15,'2412','2020-09-04 09:12:43','2020-09-04 09:12:43'),
(16,'14123','2020-09-04 09:12:47','2020-09-04 09:12:47'),
(17,'123','2020-09-04 09:13:12','2020-09-04 09:13:12'),
(18,'12412312','2020-09-04 09:13:17','2020-09-04 09:13:17'),
(19,'124123123','2020-09-04 09:13:22','2020-09-04 09:13:22'),
(20,'143242','2020-09-04 09:13:28','2020-09-04 09:13:28');

/*Table structure for table `user_roles` */

DROP TABLE IF EXISTS `user_roles`;

CREATE TABLE `user_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `roleName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_roles` */

insert  into `user_roles`(`id`,`roleName`,`permission`,`created_at`,`updated_at`) values 
(1,'admin','[{\"resourceName\":\"Home\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"/\"},{\"resourceName\":\"Tags\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"tags\"},{\"resourceName\":\"Category\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"category\"},{\"resourceName\":\"Blog\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"blog\"},{\"resourceName\":\"Admin users\",\"read\":true,\"write\":false,\"update\":true,\"delete\":true,\"name\":\"adminuser\"},{\"resourceName\":\"Role\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"name\":\"role\"},{\"resourceName\":\"Assign Role\",\"read\":true,\"write\":true,\"update\":false,\"delete\":true,\"name\":\"assignRole\"}]','2020-09-03 15:46:25','2020-09-03 19:39:18'),
(2,'manager','[{\"resourceName\":\"Home\",\"read\":true,\"write\":true,\"update\":true,\"delete\":false,\"name\":\"/\"},{\"resourceName\":\"Tags\",\"read\":true,\"write\":true,\"update\":true,\"delete\":false,\"name\":\"tags\"},{\"resourceName\":\"Category\",\"read\":true,\"write\":true,\"update\":true,\"delete\":false,\"name\":\"category\"},{\"resourceName\":\"Blog\",\"read\":true,\"write\":true,\"update\":true,\"delete\":false,\"name\":\"blog\"},{\"resourceName\":\"Admin users\",\"read\":true,\"write\":true,\"update\":true,\"delete\":false,\"name\":\"adminuser\"},{\"resourceName\":\"Role\",\"read\":true,\"write\":true,\"update\":true,\"delete\":false,\"name\":\"role\"},{\"resourceName\":\"Assign Role\",\"read\":true,\"write\":true,\"update\":true,\"delete\":false,\"name\":\"assignRole\"}]','2020-09-03 15:46:42','2020-09-03 17:57:26'),
(3,'teacher','[{\"resourceName\":\"Home\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"/\"},{\"resourceName\":\"Tags\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"tags\"},{\"resourceName\":\"Category\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"category\"},{\"resourceName\":\"Blog\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"blog\"},{\"resourceName\":\"Admin users\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"adminuser\"},{\"resourceName\":\"Role\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"role\"},{\"resourceName\":\"Assign Role\",\"read\":true,\"write\":true,\"update\":false,\"delete\":false,\"name\":\"assignRole\"}]','2020-09-03 15:46:51','2020-09-03 17:58:06'),
(4,'parent','[{\"resourceName\":\"Home\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"/\"},{\"resourceName\":\"Tags\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"tags\"},{\"resourceName\":\"Category\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"category\"},{\"resourceName\":\"Blog\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"blog\"},{\"resourceName\":\"Admin users\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"adminuser\"},{\"resourceName\":\"Role\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"role\"},{\"resourceName\":\"Assign Role\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"assignRole\"}]','2020-09-03 15:47:01','2020-09-03 17:58:29'),
(5,'student','[{\"resourceName\":\"Home\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"/\"},{\"resourceName\":\"Tags\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"tags\"},{\"resourceName\":\"Category\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"category\"},{\"resourceName\":\"Blog\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"blog\"},{\"resourceName\":\"Admin users\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"adminuser\"},{\"resourceName\":\"Role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"role\"},{\"resourceName\":\"Assign Role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"name\":\"assignRole\"}]','2020-09-03 15:47:28','2020-09-03 17:58:49');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phoneNumber` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isActived` tinyint(1) NOT NULL DEFAULT 0,
  `activationCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `roleId` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_phonenumber_unique` (`phoneNumber`),
  KEY `users_roleid_foreign` (`roleId`),
  CONSTRAINT `users_roleid_foreign` FOREIGN KEY (`roleId`) REFERENCES `user_roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`phoneNumber`,`password`,`isActived`,`activationCode`,`remember_token`,`created_at`,`updated_at`,`roleId`) values 
(1,'admin','123123123','$2y$10$qoUPSjecwU2YtUMOxvtto.x.IzcsdFOKjCUERnO/.mYtTcLL/jCfq',0,NULL,NULL,'2020-09-03 15:48:58','2020-09-03 15:48:58',1),
(2,'manager','111222333','$2y$10$mO8rDw0lutmk3QkA0lvtDOu1n9UtqfaB6HpSPDKy6afKqY1rCvfn2',0,NULL,NULL,'2020-09-03 16:01:57','2020-09-03 16:01:57',2),
(3,'teacher','2575038','$2y$10$jNXicbr1loVqQgHAtYoV6Og625BSEu5svcozWcnGRVjl3.Oz9HRPO',0,NULL,NULL,'2020-09-03 16:03:19','2020-09-03 16:33:15',3),
(4,'parent','2546646','$2y$10$tjfCYcMQRbGExpoBWNZP1e50YipC6ddtwlbHqoQDJ0/89wBsH9OV.',0,NULL,NULL,'2020-09-03 16:03:37','2020-09-03 16:03:37',4),
(5,'student','2702476','$2y$10$XK2Ub0hoyZ/jSKTprEl3Me6956xkBiCQ.FNqlYelDpmuEFr.fY6.u',0,NULL,NULL,'2020-09-03 16:03:54','2020-09-03 16:03:54',5);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
