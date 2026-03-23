-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2026 at 08:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `entrepreneur_portal-rehan`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_domain` varchar(150) NOT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `expertise` text DEFAULT NULL,
  `linkedin_profile` varchar(255) DEFAULT NULL,
  `profile_completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`id`, `user_id`, `business_domain`, `experience_years`, `expertise`, `linkedin_profile`, `profile_completed`, `created_at`, `updated_at`) VALUES
(1, 2, 'fdsa', 5, 'fdsa', 'http://localhost/fall25/interactive-rehan/agent/agent-profile.php', 1, '2026-03-23 06:48:07', '2026-03-23 06:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Technology', 'Software, AI, Mobile Apps, Web Development', '2026-03-23 06:51:06'),
(2, 'E-commerce', 'Online stores, Dropshipping, Retail Tech', '2026-03-23 06:51:06'),
(3, 'Fintech', 'Financial Technology, Banking, Payments', '2026-03-23 06:51:06'),
(4, 'HealthTech', 'Healthcare, Medical, Fitness & Wellness', '2026-03-23 06:51:06'),
(5, 'EdTech', 'Education Technology & E-Learning', '2026-03-23 06:51:06'),
(6, 'Agriculture', 'AgriTech, Farming, Food Processing', '2026-03-23 06:51:06'),
(7, 'Sustainability', 'Green Energy, Environment, Climate Tech', '2026-03-23 06:51:06'),
(8, 'Food & Beverage', 'Restaurant, Food Delivery, FMCG', '2026-03-23 06:51:06'),
(9, 'Fashion & Apparel', 'Clothing, Textile, Accessories', '2026-03-23 06:51:06'),
(10, 'Real Estate', 'Property, Construction, Housing Tech', '2026-03-23 06:51:06'),
(11, 'Logistics & Supply Chain', 'Transportation, Delivery, Warehousing', '2026-03-23 06:51:06'),
(12, 'Entertainment', 'Gaming, Media, Streaming, Events', '2026-03-23 06:51:06'),
(13, 'Travel & Tourism', 'Tourism, Hospitality, Booking Platforms', '2026-03-23 06:51:06'),
(14, 'Social Impact', 'NGO, Charity, Community Projects', '2026-03-23 06:51:06'),
(15, 'Manufacturing', 'Industry 4.0, Smart Factories', '2026-03-23 06:51:06'),
(16, 'Cybersecurity', 'Security, Privacy, Data Protection', '2026-03-23 06:51:06'),
(17, 'Blockchain & Crypto', 'Web3, Cryptocurrency, NFTs', '2026-03-23 06:51:06'),
(18, 'HR & Recruitment', 'Human Resources, Talent Management', '2026-03-23 06:51:06'),
(19, 'Marketing & Advertising', 'Digital Marketing, Branding', '2026-03-23 06:51:06'),
(20, 'Others', 'Miscellaneous business ideas and resources', '2026-03-23 06:51:06');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` enum('tutorial','event') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `title`, `description`, `type`, `created_at`) VALUES
(2, 'How to Validate Your Business Idea in 7 Days', 'Learn practical steps to test your startup idea before investing money. Includes customer interviews, surveys, and MVP techniques.', 'tutorial', '2026-03-23 06:41:42'),
(3, 'Startup Funding Guide 2026 - Complete Roadmap', 'Everything you need to know about raising funds from bootstrapping to angel investors, venture capital, and government grants.', 'tutorial', '2026-03-23 06:41:42'),
(4, 'Free Business Plan Template for Beginners', 'Download ready-to-use business plan template with financial projections and market analysis sections.', 'tutorial', '2026-03-23 06:41:42'),
(5, 'How to Build a Minimum Viable Product (MVP)', 'Step-by-step guide on creating your first MVP and getting early customer feedback.', 'tutorial', '2026-03-23 06:41:42'),
(6, 'Digital Marketing Strategies for New Startups', 'Learn low-cost digital marketing techniques including social media, SEO, content marketing and email campaigns.', 'tutorial', '2026-03-23 06:41:42'),
(7, 'Webinar: Legal Requirements for Starting a Business in Pakistan', 'Join our live session on company registration, taxes, trademarks, and legal compliance.', 'event', '2026-03-23 06:41:42'),
(8, 'Pitch Deck Masterclass - How to Impress Investors', 'Learn how to create a winning pitch deck with real examples from successful startups.', 'tutorial', '2026-03-23 06:41:42'),
(9, 'E-commerce Business Launch Checklist', 'Complete checklist to successfully launch your online store in 30 days.', 'tutorial', '2026-03-23 06:41:42'),
(10, 'Startup Networking Event - Multan Edition', 'Connect with fellow entrepreneurs, mentors, and investors at our monthly networking meetup.', 'event', '2026-03-23 06:41:42'),
(11, 'Financial Management for Early Stage Startups', 'How to manage cash flow, budgeting, and financial planning in the first year of business.', 'tutorial', '2026-03-23 06:41:42'),
(12, 'Workshop: From Idea to Business Model Canvas', 'Interactive workshop to help you map your business model using the famous Business Model Canvas.', 'event', '2026-03-23 06:41:42'),
(13, 'SEO for Beginners - Rank Higher on Google', 'Complete beginner-friendly SEO guide to improve your website ranking organically.', 'tutorial', '2026-03-23 06:41:42'),
(14, 'Women Entrepreneurship Summit 2026', 'A full-day event celebrating and empowering women entrepreneurs with keynote speakers and panel discussions.', 'event', '2026-03-23 06:41:42'),
(15, 'How to Protect Your Startup Idea', 'Guide on patents, copyrights, trademarks, and NDAs for protecting your intellectual property.', 'tutorial', '2026-03-23 06:41:42'),
(16, 'Social Media Marketing Masterclass', 'Advanced strategies to grow your brand and generate leads using Instagram, Facebook, and TikTok.', 'tutorial', '2026-03-23 06:41:42'),
(17, 'Tech Startup Funding Pitch Night', 'Selected startups will get a chance to pitch in front of real angel investors and VCs.', 'event', '2026-03-23 06:41:42'),
(18, 'Time Management Techniques for Busy Entrepreneurs', 'Proven productivity hacks and tools to help entrepreneurs manage their time effectively.', 'tutorial', '2026-03-23 06:41:42'),
(19, 'Customer Discovery Interview Techniques', 'Learn how to conduct effective customer interviews to validate your product-market fit.', 'tutorial', '2026-03-23 06:41:42'),
(20, 'Annual Entrepreneurship Bootcamp 2026', 'Intensive 3-day bootcamp covering ideation, validation, business planning, and pitching.', 'event', '2026-03-23 06:41:42'),
(21, 'Building a Strong Brand Identity on a Budget', 'Step-by-step guide to creating professional branding even with limited resources.', 'tutorial', '2026-03-23 06:41:42');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `agent_id`, `message`, `rating`, `created_at`) VALUES
(1, 3, 1, 'dsafd', 5, '2026-03-23 07:25:58'),
(2, 1, 1, 'sadf', 5, '2026-03-23 07:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `topic_id`, `user_id`, `message`, `created_at`) VALUES
(1, 1, 2, 'fdssadfds', '2026-03-23 07:22:49'),
(2, 1, 3, 'dsadfs', '2026-03-23 07:23:29');

-- --------------------------------------------------------

--
-- Table structure for table `forum_topics`
--

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_topics`
--

INSERT INTO `forum_topics` (`id`, `user_id`, `title`, `created_at`) VALUES
(1, 2, 'saf', '2026-03-23 07:22:43'),
(2, 1, 'fdsafdsd', '2026-03-23 07:36:29');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`) VALUES
(1, 3, 2, 'Hy Sir', 0, '2026-03-23 07:15:08'),
(2, 3, 2, 'fdsa', 0, '2026-03-23 07:16:16'),
(3, 2, 3, 'fsdd', 0, '2026-03-23 07:18:48'),
(4, 1, 2, 'sadsadf', 0, '2026-03-23 07:27:45'),
(5, 2, 1, 'dsa', 0, '2026-03-23 07:27:58'),
(6, 1, 2, 'ds', 0, '2026-03-23 07:28:25');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content_type` enum('idea','framework','template','startup_plan','financial_support') NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `agent_id`, `category_id`, `title`, `content_type`, `description`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 2, 18, 'sda', 'template', 'fsa', 'uploads/1774248812_Health_Report_monthly.pdf', '2026-03-23 06:53:32', '2026-03-23 06:53:32'),
(2, 2, 1, 'AI-Powered Customer Support Chatbot Idea', 'template', 'A complete business idea for an intelligent AI chatbot that provides 24/7 customer support for e-commerce businesses.', 'uploads/1774248812_AI_Chatbot_Idea.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:46'),
(3, 2, 2, 'E-commerce Business Model Canvas Template', 'template', 'Ready-to-use Business Model Canvas template specifically designed for online stores and dropshipping businesses.', 'uploads/1774248825_Ecommerce_BMC_Template.docx', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(4, 2, 3, 'Complete Financial Projection Excel Template', 'template', 'Monthly and yearly financial model with income statement, cash flow, and break-even analysis.', 'uploads/1774248839_Financial_Projection_Template.xlsx', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(5, 2, 1, 'How to Build a SaaS Product - Framework', 'framework', 'Step-by-step framework for developing and launching a successful Software as a Service product.', 'uploads/1774248851_SaaS_Development_Framework.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(6, 2, 4, 'HealthTech Startup Pitch Deck', 'startup_plan', 'Professional pitch deck template for HealthTech startups seeking seed funding.', 'uploads/1774248867_HealthTech_Pitch_Deck.pptx', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(7, 2, 5, 'EdTech Market Research Report 2026', 'idea', 'In-depth market research on the growing EdTech sector in Pakistan and South Asia.', 'uploads/1774248882_EdTech_Market_Report.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(8, 2, 3, 'Startup Funding Guide & Checklist', 'startup_plan', 'Comprehensive guide covering all types of funding options available for early-stage startups.', 'uploads/1774248895_Startup_Funding_Guide.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(9, 2, 6, 'Smart Farming Business Plan', 'startup_plan', 'Detailed business plan for a smart agriculture startup using IoT sensors and data analytics.', 'uploads/1774248910_Smart_Farming_Business_Plan.docx', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(10, 2, 7, 'Sustainable Packaging Ideas for Businesses', 'idea', 'Innovative eco-friendly packaging solutions for small and medium enterprises.', 'uploads/1774248923_Sustainable_Packaging_Ideas.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(11, 2, 2, 'Dropshipping Product Research Framework', 'framework', 'Powerful framework to find winning products for your dropshipping store.', 'uploads/1774248938_Dropshipping_Product_Research.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(12, 2, 8, 'Food Delivery App Business Model', 'idea', 'Complete business idea and model for a hyperlocal food delivery startup.', 'uploads/1774248952_Food_Delivery_Business_Model.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(13, 2, 9, 'Fashion Brand Launch Checklist', 'template', 'Step-by-step checklist and timeline for launching your own fashion brand.', 'uploads/1774248967_Fashion_Brand_Launch_Checklist.docx', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(14, 2, 10, 'Real Estate Investment Analysis Template', 'template', 'Professional template to analyze real estate investment opportunities.', 'uploads/1774248981_Real_Estate_Analysis_Template.xlsx', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(15, 2, 11, 'Supply Chain Optimization Framework', 'framework', 'Advanced framework to optimize logistics and supply chain operations.', 'uploads/1774248995_Supply_Chain_Optimization.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(16, 2, 12, 'Mobile Game Development Business Plan', 'startup_plan', 'Detailed plan for starting a mobile game development studio.', 'uploads/1774249010_Mobile_Game_Studio_Plan.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(17, 2, 13, 'Travel Startup Idea - Eco Tourism Platform', 'idea', 'Business idea for a platform connecting travelers with sustainable tourism experiences.', 'uploads/1774249024_Eco_Tourism_Platform.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(18, 2, 3, 'Grant Application Template for Startups', 'template', 'Professional template to apply for government and private grants.', 'uploads/1774249039_Grant_Application_Template.docx', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(19, 2, 14, 'Social Impact Business Framework', 'framework', 'Framework for building businesses that create measurable social impact.', 'uploads/1774249052_Social_Impact_Framework.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(20, 2, 4, 'Monthly Health Report Template', 'template', 'Professional monthly health and wellness report template for clinics and fitness centers.', 'uploads/1774248812_Health_Report_monthly.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17'),
(21, 2, 15, 'Digital Marketing Strategy Template 2026', 'template', 'Complete digital marketing strategy and calendar template for new businesses.', 'uploads/1774249078_Digital_Marketing_Strategy_Template.pdf', '2026-03-23 06:55:17', '2026-03-23 06:55:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `role` enum('user','agent','admin') NOT NULL DEFAULT 'user',
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `bio`, `location`, `role`, `status`, `created_at`) VALUES
(1, 'System Admin', 'admin@admin.com', '123456', NULL, NULL, NULL, 'admin', 'approved', '2026-03-23 06:05:59'),
(2, 'Agnet Haider', 'agent@gmail.com', '123456', '3176526827', 'XYZ', 'Near Multan Road', 'agent', 'approved', '2026-03-23 06:13:09'),
(3, 'User Asad', 'asad@gmail.com', '123456', '03266029044', 'fdsa', 'Karachi Korangi', 'user', 'approved', '2026-03-23 06:57:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agents`
--
ALTER TABLE `agents`
  ADD CONSTRAINT `agents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
