--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_slug`, `category_image_path`, `category_image_alt`, `category_status`, `created_at`, `updated_at`, `parent_id`, `category_type`, `category_description`, `category_topbar_index`, `category_home_index`, `category_release`) VALUES
('3329ad6d-6d21-11ef-8e68-088fc3163dd3', 'Car', 'car', 'file-manager/categories/car.png', 'car', 1, '2024-09-06 07:58:03', '2025-05-04 13:36:40', NULL, 1, NULL, NULL, NULL, '2024-09-06 07:56:40'),
('4a715358-6d22-11ef-8e68-088fc3163dd3', 'Laptop', 'laptop', 'file-manager/categories/laptop.png', 'laptop', 1, '2024-09-06 08:05:51', '2025-04-29 20:04:07', NULL, 1, '<p>kkk</p>', NULL, NULL, '2025-04-20 11:31:59'),
('97cd453b-6d1f-11ef-8e68-088fc3163dd3', 'Wrap Stock', 'wrap-stock', 'file-manager/categories/wrap-stock.png', 'wrap stock', 1, '2024-09-06 07:46:33', '2025-04-05 12:31:49', NULL, 1, NULL, NULL, NULL, '2024-09-06 07:44:57'),
('bb733d2e-6d20-11ef-8e68-088fc3163dd3', 'Motorcycle', 'motorcycle', 'file-manager/categories/motorcycle.png', 'motorcycle', 1, '2024-09-06 07:54:42', '2025-04-29 19:43:10', NULL, 1, NULL, NULL, NULL, '2024-09-06 07:53:41'),
('c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 'Mobile', 'mobile', 'file-manager/categories/mobile.png', 'mobile', 1, '2024-09-06 07:47:45', '2025-04-13 23:04:56', NULL, 1, NULL, NULL, NULL, '2024-09-06 07:46:51'),
('02fb26b2-6d24-11ef-8e68-088fc3163dd3', 'Alienware', 'alienware', 'file-manager/categories/alienware.png', 'alienware', 1, '2024-09-06 08:18:10', '2025-04-29 19:15:12', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:06:36'),
('02fb41dd-6d24-11ef-8e68-088fc3163dd3', 'Hp', 'hp', 'file-manager/categories/hp.png', 'hp', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:13', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:06:36'),
('02fb716f-6d24-11ef-8e68-088fc3163dd3', 'Acer', 'acer', 'file-manager/categories/acer.png', 'acer', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:19', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:06:36'),
('02fb997f-6d24-11ef-8e68-088fc3163dd3', 'Asus', 'asus', 'file-manager/categories/asus.png', 'asus', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:22', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:06:36'),
('02fbb836-6d24-11ef-8e68-088fc3163dd3', 'Dell', 'dell', 'file-manager/categories/dell.png', 'dell', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:25', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:18:10'),
('02fbcfc7-6d24-11ef-8e68-088fc3163dd3', 'Lenovo', 'lenovo', 'file-manager/categories/lenovo.png', 'lenovo', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:29', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:18:10'),
('0c09750e-6d20-11ef-8e68-088fc3163dd3', 'Google', 'google', 'file-manager/categories/google.png', 'google', 1, '2024-09-06 07:49:48', '2025-04-05 12:30:32', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:48:06'),
('0c09a653-6d20-11ef-8e68-088fc3163dd3', 'Oppo', 'oppo', 'file-manager/categories/oppo.png', 'oppo', 1, '2024-09-06 07:49:48', '2025-04-05 12:30:36', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:48:06'),
('33297e81-6d21-11ef-8e68-088fc3163dd3', 'Wrap Pdf Template', 'wrap-pdf-template', 'file-manager/categories/wrap-pdf.png', 'wrap pdf', 1, '2024-09-06 07:58:03', '2025-04-29 19:39:07', 'bb733d2e-6d20-11ef-8e68-088fc3163dd3', 2, NULL, NULL, NULL, '2024-09-06 07:56:40'),
('379ced26-6d20-11ef-8e68-088fc3163dd3', 'Sony', 'sony', 'file-manager/categories/sony.png', 'sony', 1, '2024-09-06 07:51:01', '2025-04-05 12:30:40', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:50:05'),
('379d1e12-6d20-11ef-8e68-088fc3163dd3', 'Samsung', 'samsung', 'file-manager/categories/samsung.png', 'samsung', 1, '2024-09-06 07:51:01', '2025-04-05 12:30:43', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:50:05'),
('67069018-6d20-11ef-8e68-088fc3163dd3', 'Iphone', 'iphone', 'file-manager/categories/iphone.png', 'iphone', 1, '2024-09-06 07:52:20', '2025-04-05 12:30:47', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:51:14'),
('6706ae29-6d20-11ef-8e68-088fc3163dd3', 'Lg', 'lg', 'file-manager/categories/lg.png', 'lg', 1, '2024-09-06 07:52:20', '2025-04-05 12:30:50', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:51:14'),
('994491c6-6d21-11ef-8e68-088fc3163dd3', 'Car Pdf Interrior Template', 'car-pdf-interrior-template', 'file-manager/categories/car-pdf.jpg', 'car pdf', 1, '2024-09-06 08:00:54', '2025-05-03 16:47:57', '3329ad6d-6d21-11ef-8e68-088fc3163dd3', 2, '<p>kkk</p>', NULL, NULL, '2025-05-03 16:47:57'),
('9944c101-6d21-11ef-8e68-088fc3163dd3', 'Car Pdf Outside Template', 'car-pdf-outside-template', 'file-manager/categories/car-interrior.png', 'car outside', 1, '2024-09-06 08:00:54', '2025-04-13 23:07:28', '3329ad6d-6d21-11ef-8e68-088fc3163dd3', 2, NULL, NULL, NULL, '2024-09-06 07:58:22'),
('eea0f82d-6d21-11ef-8e68-088fc3163dd3', 'Car Pdf Screen Template', 'car-pdf-screen-template', 'file-manager/categories/car-screen.png', 'car screen', 1, '2024-09-06 08:03:17', '2025-04-13 23:08:10', '3329ad6d-6d21-11ef-8e68-088fc3163dd3', 2, 'Easy to clean. A vehicle paint job is perfect when new,Protects from fading,Improve your Motorcycle resale value,Protection from chemical reaction,Cheaper in the long run,Scratch-resistant protection,Easy to remove', NULL, NULL, '2024-09-06 08:01:53'),
('eea1350b-6d21-11ef-8e68-088fc3163dd3', 'Door Handle Pdf Template', 'door-handle-pdf-template', 'file-manager/categories/car-door-handle.png', 'door handle', 1, '2024-09-06 08:03:17', '2025-04-20 10:56:09', '3329ad6d-6d21-11ef-8e68-088fc3163dd3', 2, '<p><strong><em>Hello</em></strong>,<img src=\"http://127.0.0.1:8000/file-manager/categories/laptop.png\"></p>', NULL, NULL, '2024-09-06 08:01:53');


-- --------------------------------------------------------
--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `created_at`, `updated_at`) VALUES
('041bd802-821f-4302-ae8a-679055ab3362', 'ADMIN-POST', '2025-04-25 14:48:43', '2025-04-25 14:48:43'),
('0f5cf808-79f5-438e-b66c-405263edd530', 'Sub-admin', '2024-09-27 19:22:42', '2025-02-22 05:31:56'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'Admin', '2024-09-28 02:09:46', '2025-02-22 05:31:34'),
('9c3e09cf-e4b3-4269-8319-e724ddad3b17', 'User', '2024-09-28 02:09:46', '2025-02-22 05:31:45'),
('a1e2d5f3-bf01-4f32-a4f5-12e1c7722fc1', 'ADMIN-PRODUCT', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('b4c9e530-89cd-4f1c-bf9a-204741e5b64d', 'ADMIN-CATEGORY', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('c7f13925-6594-4e62-a2d2-5b27f7dc2452', 'ADMIN-SLIDESHOW', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('dd12ae76-46e3-4cc1-bc87-45ffad7b9c5d', 'ADMIN-MAIL', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('e1c49397-2b56-4ef2-8d71-5cd1033f97c1', 'ADMIN-SETTING', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('f3a3c9d2-0b3e-4d2f-9fd4-a82e908fbc11', 'ADMIN-TRACKING', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('fa78e0f3-91c7-4a9a-baa5-2de7a8e8f4fc', 'ADMIN-SUBADMIN-MANAGER', '2025-05-11 10:10:00', '2025-05-11 10:10:00'),
('6b92e7f1-26f5-4298-a0a3-df5b8ef1f439', 'ADMIN-QUESTION', '2025-05-11 10:10:00', '2025-05-11 10:10:00'),
('844b4c31-5d4a-4c9e-988b-3b11847d4a42', 'ADMIN-ROLE', '2025-05-11 10:10:00', '2025-05-11 10:10:00'),
('1d2c934a-1084-445f-9833-89c12ae4ce1b', 'ADMIN-COUPON', '2025-05-11 10:10:00', '2025-05-11 10:10:00'),
('9fe9b5b6-e063-4c3b-9f12-20e3b6441ad1', 'ADMIN-FILE', '2025-05-11 10:10:00', '2025-05-11 10:10:00');

-- --------------------------------------------------------
--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission_name`, `created_at`, `updated_at`) VALUES
('372c7c0b-fec9-4ac5-9fa9-ccdb824828f0', 'manage posts', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('14360604-1a6c-45aa-b882-ee3d3740af3e', 'manage roles', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('f4c28701-5d3e-4db8-b16b-c3d1a158ab77', 'manage products', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('3b1dbd40-6e67-4034-8f0e-77f7f67e3ac3', 'manage categories', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('e4d3171e-90f9-4c44-890d-96b52b5ef613', 'manage slideshow', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('aad80472-265f-4dc5-8b99-51f850dbbb42', 'manage mail', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('b1f6c087-dc76-4e0e-a0c6-d9c6e5e961b2', 'manage settings', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('dcf3760e-cf55-4d3a-844d-49d0c29a4789', 'manage tracking', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('c4b2f231-ef3a-4cb7-b95b-99d3440bbfc6', 'manage subadmins', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('3136824c-3fd6-4e2b-b0c1-7e6e0e291453', 'manage questions', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('71d3de9a-08fd-4ef0-84e2-fab5a71dfe3f', 'manage coupons', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('d21489b4-97d2-4e7d-a86a-d3905ad23a60', 'manage files', '2025-05-11 10:15:00', '2025-05-11 10:15:00');


-- --------------------------------------------------------
--
-- Dumping data for table `permissions_roles`
--

INSERT INTO `permissions_roles` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '372c7c0b-fec9-4ac5-9fa9-ccdb824828f0', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '14360604-1a6c-45aa-b882-ee3d3740af3e', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'f4c28701-5d3e-4db8-b16b-c3d1a158ab77', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '3b1dbd40-6e67-4034-8f0e-77f7f67e3ac3', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'e4d3171e-90f9-4c44-890d-96b52b5ef613', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'aad80472-265f-4dc5-8b99-51f850dbbb42', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'b1f6c087-dc76-4e0e-a0c6-d9c6e5e961b2', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'dcf3760e-cf55-4d3a-844d-49d0c29a4789', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'c4b2f231-ef3a-4cb7-b95b-99d3440bbfc6', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '3136824c-3fd6-4e2b-b0c1-7e6e0e291453', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '71d3de9a-08fd-4ef0-84e2-fab5a71dfe3f', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'd21489b4-97d2-4e7d-a86a-d3905ad23a60', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('041bd802-821f-4302-ae8a-679055ab3362', '372c7c0b-fec9-4ac5-9fa9-ccdb824828f0', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-POST -> manage posts
('a1e2d5f3-bf01-4f32-a4f5-12e1c7722fc1', 'f4c28701-5d3e-4db8-b16b-c3d1a158ab77', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-PRODUCT
('b4c9e530-89cd-4f1c-bf9a-204741e5b64d', '3b1dbd40-6e67-4034-8f0e-77f7f67e3ac3', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-CATEGORY
('c7f13925-6594-4e62-a2d2-5b27f7dc2452', 'e4d3171e-90f9-4c44-890d-96b52b5ef613', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-SLIDESHOW
('dd12ae76-46e3-4cc1-bc87-45ffad7b9c5d', 'aad80472-265f-4dc5-8b99-51f850dbbb42', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-MAIL
('e1c49397-2b56-4ef2-8d71-5cd1033f97c1', 'b1f6c087-dc76-4e0e-a0c6-d9c6e5e961b2', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-SETTING
('f3a3c9d2-0b3e-4d2f-9fd4-a82e908fbc11', 'dcf3760e-cf55-4d3a-844d-49d0c29a4789', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-TRACKING
('fa78e0f3-91c7-4a9a-baa5-2de7a8e8f4fc', 'c4b2f231-ef3a-4cb7-b95b-99d3440bbfc6', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-SUBADMIN-MANAGER
('6b92e7f1-26f5-4298-a0a3-df5b8ef1f439', '3136824c-3fd6-4e2b-b0c1-7e6e0e291453', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-QUESTION
('844b4c31-5d4a-4c9e-988b-3b11847d4a42', '14360604-1a6c-45aa-b882-ee3d3740af3e', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-ROLE
('1d2c934a-1084-445f-9833-89c12ae4ce1b', '71d3de9a-08fd-4ef0-84e2-fab5a71dfe3f', '2025-05-11 10:15:00', '2025-05-11 10:15:00'), -- ADMIN-COUPON
('9fe9b5b6-e063-4c3b-9f12-20e3b6441ad1', 'd21489b4-97d2-4e7d-a86a-d3905ad23a60', '2025-05-11 10:15:00', '2025-05-11 10:15:00'); -- ADMIN-FILE

-- --------------------------------------------------------

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `question_text`, `created_at`, `updated_at`) VALUES
('16cd2e4d-d584-11ef-95d6-088fc3163dd3', 'What was the name of your first school?', '2025-01-18 03:07:56', '2025-01-18 03:07:56'),
('185b62d7-d581-11ef-95d6-088fc3163dd3', 'What is the title of your favorite book?', '2025-01-18 02:46:30', '2025-01-18 02:46:30'),
('185b9518-d581-11ef-95d6-088fc3163dd3', 'What is your favorite movie?', '2025-01-18 02:46:30', '2025-01-18 02:46:30'),
('83a6b94e-eec3-11ef-b0e7-0242ac110002', 'What was the name of your first pet?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('83a6daf3-eec3-11ef-b0e7-0242ac110002', 'What is your mother’s nick name?', '2025-02-19 06:14:56', '2025-03-09 09:56:41'),
('83a6ddd5-eec3-11ef-b0e7-0242ac110002', 'What was the name of your first ever mother?', '2025-02-19 06:14:56', '2025-03-09 10:08:40'),
('83a6de61-eec3-11ef-b0e7-0242ac110002', 'What city were you born in?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('83a6dee8-eec3-11ef-b0e7-0242ac110002', 'What was your childhood nickname?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('83a6df5b-eec3-11ef-b0e7-0242ac110002', 'What is the name of your favorite female teacher?', '2025-02-19 06:14:56', '2025-04-25 09:35:39'),
('83a6e027-eec3-11ef-b0e7-0242ac110002', 'What is your father’s middle name?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('83a6eab7-eec3-11ef-b0e7-0242ac110002', 'What is the name of your first employer?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('83a6eb1a-eec3-11ef-b0e7-0242ac110002', 'What was the make and model of your first car?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('83a6eb9f-eec3-11ef-b0e7-0242ac110002', 'What is the name of your favorite childhood friend?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('83a6f57c-eec3-11ef-b0e7-0242ac110002', 'What is your dream vacation destination?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('83a6f5b9-eec3-11ef-b0e7-0242ac110002', 'What was your first online username?', '2025-02-19 06:14:56', '2025-02-19 06:14:56'),
('dbacbf2f-d583-11ef-95d6-088fc3163dd3', 'What is the name of the school you graduated from?', '2025-01-18 03:06:17', '2025-01-18 03:06:17'),
('dbace7da-d583-11ef-95d6-088fc3163dd3', 'What was the name of your first job?', '2025-01-18 03:06:17', '2025-01-18 03:06:17');

-- --------------------------------------------------------
--
-- Dumping data for table `slideshow_images`
--

INSERT INTO `slideshow_images` (`slideshow_image_id`, `slideshow_image_url`, `slideshow_image_index`, `slideshow_image_alt`, `created_at`, `updated_at`) VALUES
('055d6659-dbd8-11ef-9ef8-088fc3163dd3', 'file-manager/slides/car.png', 0, 'logo', '2025-01-26 04:23:52', '2025-04-05 12:33:58'),
('055da713-dbd8-11ef-9ef8-088fc3163dd3', 'file-manager/slides/pngtree-black-super-car-png-image_11921537.png', 100, 'pngtree-black-super-car-png-image_11921537', '2025-01-26 04:23:52', '2025-04-26 18:53:14'),
('22c138d2-dbd8-11ef-9ef8-088fc3163dd3', 'file-manager/slides/pngtree-silver-super-car-png-image_11974437.png', 40, 'pngtree-silver-super-car-png-image_11974437', '2025-01-26 04:24:41', '2025-04-05 12:34:09');

-- --------------------------------------------------------
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_first_name`, `user_last_name`, `user_email`, `user_password`, `user_password_level_2`, `user_status`, `user_phone`, `user_birthday`, `user_avatar`, `created_at`, `updated_at`) VALUES
('7578d3a9-e3ef-4488-9399-6c6736e8fbb8', 'qHC4y3S+Yy+jsybQG34P+Q==', 'eReDuREXCNxr1kPWzMaPTQ==', '4eygbqFMe5xNT1itTy6GcVaTiLQ0Cz0J7UiIBHLBX4c=', '$2y$10$0MjQeFEv7NM4aZiLt87j1uFJAikzFnJuucnmTo9f7E1nkE92jSLUK', NULL, 1, 'X2IjVbwa0xUowcY+eDPNoA==', 'EZfWY6wkoPUy4d7UwSMrxO6zhD2Pq4rZAGoa1WSJywc=', 'images/avatars/default_avatar.jpg', '2025-02-22 06:14:41', '2025-04-13 18:25:44');

-- --------------------------------------------------------
--
-- Dumping data for `users_roles`
--

INSERT INTO `users_roles` (`user_id`, `role_id`) VALUES
('7578d3a9-e3ef-4488-9399-6c6736e8fbb8', '204444d0-dd92-4724-a20a-fa2d3c0dd7fb');