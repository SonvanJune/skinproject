-- Database data dump for skinproject
-- Generated on 2026-10-14 04:22:29

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
--
-- Dumping data for table `categories` (42 rows)
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_slug`, `category_image_path`, `category_image_alt`, `category_status`, `created_at`, `updated_at`, `parent_id`, `category_type`, `category_description`, `category_topbar_index`, `category_home_index`, `category_release`) VALUES
('015ca582-4f65-4f6f-9beb-4349f21c1102', 'BMW Outside', 'bmw-outside', 'file-manager/categories/car-pdf.png', 'BMW Outside', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '9944c101-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('02fb26b2-6d24-11ef-8e68-088fc3163dd3', 'Alienware', 'alienware', 'file-manager/categories/alienware.png', 'alienware', 1, '2024-09-06 08:18:10', '2025-04-29 19:15:12', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:06:36'),
('02fb41dd-6d24-11ef-8e68-088fc3163dd3', 'Hp', 'hp', 'file-manager/categories/hp.png', 'hp', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:13', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:06:36'),
('02fb716f-6d24-11ef-8e68-088fc3163dd3', 'Acer', 'acer', 'file-manager/categories/acer.png', 'acer', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:19', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:06:36'),
('02fb997f-6d24-11ef-8e68-088fc3163dd3', 'Asus', 'asus', 'file-manager/categories/asus.png', 'asus', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:22', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:06:36'),
('02fbb836-6d24-11ef-8e68-088fc3163dd3', 'Dell', 'dell', 'file-manager/categories/dell.png', 'dell', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:25', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:18:10'),
('02fbcfc7-6d24-11ef-8e68-088fc3163dd3', 'Lenovo', 'lenovo', 'file-manager/categories/lenovo.png', 'lenovo', 1, '2024-09-06 08:18:10', '2025-04-05 12:30:29', '4a715358-6d22-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 08:18:10'),
('0c09750e-6d20-11ef-8e68-088fc3163dd3', 'Google', 'google', 'file-manager/categories/google.png', 'google', 1, '2024-09-06 07:49:48', '2025-04-05 12:30:32', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:48:06'),
('0c09a653-6d20-11ef-8e68-088fc3163dd3', 'Oppo', 'oppo', 'file-manager/categories/oppo.png', 'oppo', 1, '2024-09-06 07:49:48', '2025-04-05 12:30:36', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:48:06'),
('11f0962a-2ae6-4e75-bb07-0559db42e4e1', 'Ferrari Interior', 'ferrari-interior', 'file-manager/categories/car-interrior.png', 'Ferrari Interior', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '994491c6-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('2129d8f3-c7ee-42e1-aa25-9d243a2430fa', 'Porsche Screen', 'porsche-screen', 'file-manager/categories/car-screen.png', 'Porsche Screen', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', 'eea0f82d-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('2673e0af-d369-48bd-91ba-fdefb2e87f24', 'Tesla Screen', 'tesla-screen', 'file-manager/categories/car-screen.png', 'Tesla Screen', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', 'eea0f82d-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('33297e81-6d21-11ef-8e68-088fc3163dd3', 'Wrap Pdf Template', 'wrap-pdf-template', 'file-manager/categories/wrap-pdf.png', 'wrap pdf', 1, '2024-09-06 07:58:03', '2025-04-29 19:39:07', 'bb733d2e-6d20-11ef-8e68-088fc3163dd3', 2, NULL, NULL, NULL, '2024-09-06 07:56:40'),
('3329ad6d-6d21-11ef-8e68-088fc3163dd3', 'Car', 'car', 'file-manager/categories/car.png', 'car', 1, '2024-09-06 07:58:03', '2025-05-04 13:36:40', NULL, 1, NULL, NULL, NULL, '2024-09-06 07:56:40'),
('34638ce2-1818-4cb0-839f-454f6ec93a97', 'Porsche Door Handle', 'porsche-door-handle', 'file-manager/categories/car-door-handle.png', 'Porsche Door Handle', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', 'eea1350b-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('379ced26-6d20-11ef-8e68-088fc3163dd3', 'Sony', 'sony', 'file-manager/categories/sony.png', 'sony', 1, '2024-09-06 07:51:01', '2025-04-05 12:30:40', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:50:05'),
('379d1e12-6d20-11ef-8e68-088fc3163dd3', 'Samsung', 'samsung', 'file-manager/categories/samsung.png', 'samsung', 1, '2024-09-06 07:51:01', '2025-04-05 12:30:43', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:50:05'),
('3b22a604-b090-40d8-9120-be4597d65608', 'BMW Interior', 'bmw-interior', 'file-manager/categories/car-interrior.png', 'BMW Interior', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '994491c6-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('4157d742-265e-4d26-94dd-2483b297af58', 'Porsche Interior', 'porsche-interior', 'file-manager/categories/car-interrior.png', 'Porsche Interior', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '994491c6-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('4a715358-6d22-11ef-8e68-088fc3163dd3', 'Laptop', 'laptop', 'file-manager/categories/laptop.png', 'laptop', 1, '2024-09-06 08:05:51', '2025-04-29 20:04:07', NULL, 1, '<p>kkk</p>', NULL, NULL, '2025-04-20 11:31:59'),
('4c530e35-7ddd-48e9-95df-fcbf787e044d', 'Ferrari Outside', 'ferrari-outside', 'file-manager/categories/car-pdf.png', 'Ferrari Outside', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '9944c101-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('550854e1-7172-4834-a904-1b039e21921b', 'Porsche Outside', 'porsche-outside', 'file-manager/categories/car-pdf.png', 'Porsche Outside', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '9944c101-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('67069018-6d20-11ef-8e68-088fc3163dd3', 'Iphone', 'iphone', 'file-manager/categories/iphone.png', 'iphone', 1, '2024-09-06 07:52:20', '2025-04-05 12:30:47', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:51:14'),
('6706ae29-6d20-11ef-8e68-088fc3163dd3', 'Lg', 'lg', 'file-manager/categories/lg.png', 'lg', 1, '2024-09-06 07:52:20', '2025-04-05 12:30:50', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2024-09-06 07:51:14'),
('69f6151c-2c78-4bb1-87af-303daa51e176', 'Audi Interior', 'audi-interior', 'file-manager/categories/car-interrior.png', 'Audi Interior', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '994491c6-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('7ee35e52-8fd9-4fb4-befe-a34ce964f1ae', 'BMW Door Handle', 'bmw-door-handle', 'file-manager/categories/car-door-handle.png', 'BMW Door Handle', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', 'eea1350b-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('8485cce2-f5f6-4633-a3df-dc2f0050cf8f', 'Lamborghini Outside', 'lamborghini-outside', 'file-manager/categories/car-pdf.png', 'Lamborghini Outside', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '9944c101-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('92c57d29-8ef4-4f38-bc97-1979047ec9ca', 'Tesla Door Handle', 'tesla-door-handle', 'file-manager/categories/car-door-handle.png', 'Tesla Door Handle', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', 'eea1350b-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('9594f9aa-07eb-4c87-bbe2-e6c612c060d2', 'BMW Screen', 'bmw-screen', 'file-manager/categories/car-screen.png', 'BMW Screen', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', 'eea0f82d-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('97cd453b-6d1f-11ef-8e68-088fc3163dd3', 'Wrap Stock', 'wrap-stock', 'file-manager/categories/wrap-stock.png', 'wrap stock', 1, '2024-09-06 07:46:33', '2025-04-05 12:31:49', NULL, 1, NULL, NULL, NULL, '2024-09-06 07:44:57'),
('994491c6-6d21-11ef-8e68-088fc3163dd3', 'Car Pdf Interior Template', 'car-pdf-interior-template', 'file-manager/categories/car-interrior.png', 'car pdf', 1, '2024-09-06 08:00:54', '2026-10-13 13:47:10', '3329ad6d-6d21-11ef-8e68-088fc3163dd3', 2, '<p>kkk</p>', NULL, NULL, '2025-05-03 16:47:57'),
('9944c101-6d21-11ef-8e68-088fc3163dd3', 'Car Pdf Outside Template', 'car-pdf-outside-template', 'file-manager/categories/car-pdf.png', 'car outside', 1, '2024-09-06 08:00:54', '2026-10-13 13:47:10', '3329ad6d-6d21-11ef-8e68-088fc3163dd3', 2, NULL, NULL, NULL, '2024-09-06 07:58:22'),
('9e592462-65ac-43c7-8659-0252b6f3d9fc', 'Mercedes-Benz Interior', 'mercedes-benz-interior', 'file-manager/categories/car-interrior.png', 'Mercedes-Benz Interior', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '994491c6-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('a28e9106-7534-46fc-9eb5-1c610afc94e8', 'Tesla Interior', 'tesla-interior', 'file-manager/categories/car-interrior.png', 'Tesla Interior', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '994491c6-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('a35c5118-919c-47fd-99a1-882e0c5eb0ab', 'Mercedes-Benz Outside', 'mercedes-benz-outside', 'file-manager/categories/car-pdf.png', 'Mercedes-Benz Outside', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '9944c101-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('a9d86144-3fbd-4f4f-9319-7a9aa66e3f22', 'Mercedes-Benz Screen', 'mercedes-benz-screen', 'file-manager/categories/car-screen.png', 'Mercedes-Benz Screen', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', 'eea0f82d-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('bb733d2e-6d20-11ef-8e68-088fc3163dd3', 'Motorcycle', 'motorcycle', 'file-manager/categories/motorcycle.png', 'motorcycle', 1, '2024-09-06 07:54:42', '2025-04-29 19:43:10', NULL, 1, NULL, NULL, NULL, '2024-09-06 07:53:41'),
('c3309eb5-6d1f-11ef-8e68-088fc3163dd3', 'Mobile', 'mobile', 'file-manager/categories/mobile.png', 'mobile', 1, '2024-09-06 07:47:45', '2025-04-13 23:04:56', NULL, 1, NULL, NULL, NULL, '2024-09-06 07:46:51'),
('defa439f-0737-4176-96c6-d9d3fd0cf10a', 'Universal Kits', 'universal-door-handle', 'file-manager/categories/car-door-handle.png', 'Universal Kits', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', 'eea1350b-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('e6f54961-98da-4613-bb3a-2125eef37a06', 'McLaren Outside', 'mclaren-outside', 'file-manager/categories/car-pdf.png', 'McLaren Outside', 1, '2026-10-13 13:47:11', '2026-10-13 13:47:11', '9944c101-6d21-11ef-8e68-088fc3163dd3', 1, NULL, NULL, NULL, '2026-10-12 13:47:11'),
('eea0f82d-6d21-11ef-8e68-088fc3163dd3', 'Car Pdf Screen Template', 'car-pdf-screen-template', 'file-manager/categories/car-screen.png', 'car screen', 1, '2024-09-06 08:03:17', '2025-04-13 23:08:10', '3329ad6d-6d21-11ef-8e68-088fc3163dd3', 2, 'Easy to clean. A vehicle paint job is perfect when new,Protects from fading,Improve your Motorcycle resale value,Protection from chemical reaction,Cheaper in the long run,Scratch-resistant protection,Easy to remove', NULL, NULL, '2024-09-06 08:01:53'),
('eea1350b-6d21-11ef-8e68-088fc3163dd3', 'Door Handle Pdf Template', 'door-handle-pdf-template', 'file-manager/categories/car-door-handle.png', 'door handle', 1, '2024-09-06 08:03:17', '2025-04-20 10:56:09', '3329ad6d-6d21-11ef-8e68-088fc3163dd3', 2, '<p><strong><em>Hello</em></strong>,<img src=\"http://127.0.0.1:8000/file-manager/categories/laptop.png\"></p>', NULL, NULL, '2024-09-06 08:01:53');

-- --------------------------------------------------------
--
-- Dumping data for table `roles` (15 rows)
--

INSERT INTO `roles` (`role_id`, `role_name`, `created_at`, `updated_at`) VALUES
('041bd802-821f-4302-ae8a-679055ab3362', 'ADMIN-POST', '2025-04-25 14:48:43', '2025-04-25 14:48:43'),
('0f5cf808-79f5-438e-b66c-405263edd530', 'Sub-admin', '2024-09-27 19:22:42', '2025-02-22 05:31:56'),
('1d2c934a-1084-445f-9833-89c12ae4ce1b', 'ADMIN-COUPON', '2025-05-11 10:10:00', '2025-05-11 10:10:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'Admin', '2024-09-28 02:09:46', '2025-02-22 05:31:34'),
('6b92e7f1-26f5-4298-a0a3-df5b8ef1f439', 'ADMIN-QUESTION', '2025-05-11 10:10:00', '2025-05-11 10:10:00'),
('844b4c31-5d4a-4c9e-988b-3b11847d4a42', 'ADMIN-ROLE', '2025-05-11 10:10:00', '2025-05-11 10:10:00'),
('9c3e09cf-e4b3-4269-8319-e724ddad3b17', 'User', '2024-09-28 02:09:46', '2025-02-22 05:31:45'),
('9fe9b5b6-e063-4c3b-9f12-20e3b6441ad1', 'ADMIN-FILE', '2025-05-11 10:10:00', '2025-05-11 10:10:00'),
('a1e2d5f3-bf01-4f32-a4f5-12e1c7722fc1', 'ADMIN-PRODUCT', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('b4c9e530-89cd-4f1c-bf9a-204741e5b64d', 'ADMIN-CATEGORY', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('c7f13925-6594-4e62-a2d2-5b27f7dc2452', 'ADMIN-SLIDESHOW', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('dd12ae76-46e3-4cc1-bc87-45ffad7b9c5d', 'ADMIN-MAIL', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('e1c49397-2b56-4ef2-8d71-5cd1033f97c1', 'ADMIN-SETTING', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('f3a3c9d2-0b3e-4d2f-9fd4-a82e908fbc11', 'ADMIN-TRACKING', '2025-05-11 10:00:00', '2025-05-11 10:00:00'),
('fa78e0f3-91c7-4a9a-baa5-2de7a8e8f4fc', 'ADMIN-SUBADMIN-MANAGER', '2025-05-11 10:10:00', '2025-05-11 10:10:00');

-- --------------------------------------------------------
--
-- Dumping data for table `permissions` (12 rows)
--

INSERT INTO `permissions` (`permission_id`, `permission_name`, `created_at`, `updated_at`) VALUES
('14360604-1a6c-45aa-b882-ee3d3740af3e', 'manage roles', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('3136824c-3fd6-4e2b-b0c1-7e6e0e291453', 'manage questions', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('372c7c0b-fec9-4ac5-9fa9-ccdb824828f0', 'manage posts', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('3b1dbd40-6e67-4034-8f0e-77f7f67e3ac3', 'manage categories', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('71d3de9a-08fd-4ef0-84e2-fab5a71dfe3f', 'manage coupons', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('aad80472-265f-4dc5-8b99-51f850dbbb42', 'manage mail', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('b1f6c087-dc76-4e0e-a0c6-d9c6e5e961b2', 'manage settings', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('c4b2f231-ef3a-4cb7-b95b-99d3440bbfc6', 'manage subadmins', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('d21489b4-97d2-4e7d-a86a-d3905ad23a60', 'manage files', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('dcf3760e-cf55-4d3a-844d-49d0c29a4789', 'manage tracking', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('e4d3171e-90f9-4c44-890d-96b52b5ef613', 'manage slideshow', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('f4c28701-5d3e-4db8-b16b-c3d1a158ab77', 'manage products', '2025-05-11 10:15:00', '2025-05-11 10:15:00');

-- --------------------------------------------------------
--
-- Dumping data for table `permissions_roles` (24 rows)
--

INSERT INTO `permissions_roles` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '14360604-1a6c-45aa-b882-ee3d3740af3e', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('844b4c31-5d4a-4c9e-988b-3b11847d4a42', '14360604-1a6c-45aa-b882-ee3d3740af3e', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '3136824c-3fd6-4e2b-b0c1-7e6e0e291453', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('6b92e7f1-26f5-4298-a0a3-df5b8ef1f439', '3136824c-3fd6-4e2b-b0c1-7e6e0e291453', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('041bd802-821f-4302-ae8a-679055ab3362', '372c7c0b-fec9-4ac5-9fa9-ccdb824828f0', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '372c7c0b-fec9-4ac5-9fa9-ccdb824828f0', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '3b1dbd40-6e67-4034-8f0e-77f7f67e3ac3', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('b4c9e530-89cd-4f1c-bf9a-204741e5b64d', '3b1dbd40-6e67-4034-8f0e-77f7f67e3ac3', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('1d2c934a-1084-445f-9833-89c12ae4ce1b', '71d3de9a-08fd-4ef0-84e2-fab5a71dfe3f', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', '71d3de9a-08fd-4ef0-84e2-fab5a71dfe3f', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'aad80472-265f-4dc5-8b99-51f850dbbb42', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('dd12ae76-46e3-4cc1-bc87-45ffad7b9c5d', 'aad80472-265f-4dc5-8b99-51f850dbbb42', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'b1f6c087-dc76-4e0e-a0c6-d9c6e5e961b2', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('e1c49397-2b56-4ef2-8d71-5cd1033f97c1', 'b1f6c087-dc76-4e0e-a0c6-d9c6e5e961b2', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'c4b2f231-ef3a-4cb7-b95b-99d3440bbfc6', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('fa78e0f3-91c7-4a9a-baa5-2de7a8e8f4fc', 'c4b2f231-ef3a-4cb7-b95b-99d3440bbfc6', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'd21489b4-97d2-4e7d-a86a-d3905ad23a60', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('9fe9b5b6-e063-4c3b-9f12-20e3b6441ad1', 'd21489b4-97d2-4e7d-a86a-d3905ad23a60', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'dcf3760e-cf55-4d3a-844d-49d0c29a4789', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('f3a3c9d2-0b3e-4d2f-9fd4-a82e908fbc11', 'dcf3760e-cf55-4d3a-844d-49d0c29a4789', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'e4d3171e-90f9-4c44-890d-96b52b5ef613', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('c7f13925-6594-4e62-a2d2-5b27f7dc2452', 'e4d3171e-90f9-4c44-890d-96b52b5ef613', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('204444d0-dd92-4724-a20a-fa2d3c0dd7fb', 'f4c28701-5d3e-4db8-b16b-c3d1a158ab77', '2025-05-11 10:15:00', '2025-05-11 10:15:00'),
('a1e2d5f3-bf01-4f32-a4f5-12e1c7722fc1', 'f4c28701-5d3e-4db8-b16b-c3d1a158ab77', '2025-05-11 10:15:00', '2025-05-11 10:15:00');

-- --------------------------------------------------------
--
-- Dumping data for table `questions` (17 rows)
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
-- Dumping data for table `users` (2 rows)
--

INSERT INTO `users` (`user_id`, `user_first_name`, `user_last_name`, `user_email`, `user_password`, `user_password_level_2`, `user_status`, `user_phone`, `user_birthday`, `user_avatar`, `created_at`, `updated_at`) VALUES
('4a707f19-1339-460b-ba9e-237a77e771d9', 'QlOSzUOKxuhkRu72btZlSA==', 'ofBstGaNbdH9Qw4aVfkt6A==', 'HJRghi2NYmWdvffMBaKDrNNDpflxEdzP1Ptfo4JHuNw=', '$2y$10$2tqUxrO429SYuYIg1Kz3d.fhsBQHpfjApyp3ydUCCRD.fjj4Xn9lG', 'oT3aHCXiP24ADmKQIAxmaA==', 1, 'h3NcNYi2ZacGNO5vDyVILA==', '3BhLiu2bFOEWBMEkboZls48nXTGwmyLsq8pXlADCckQ=', 'images/avatars/default_avatar.jpg', '2026-10-08 11:06:36', '2026-10-08 11:07:48'),
('7578d3a9-e3ef-4488-9399-6c6736e8fbb8', 'qHC4y3S+Yy+jsybQG34P+Q==', 'eReDuREXCNxr1kPWzMaPTQ==', '4eygbqFMe5xNT1itTy6GcVaTiLQ0Cz0J7UiIBHLBX4c=', '$2y$10$0MjQeFEv7NM4aZiLt87j1uFJAikzFnJuucnmTo9f7E1nkE92jSLUK', NULL, 1, 'X2IjVbwa0xUowcY+eDPNoA==', 'EZfWY6wkoPUy4d7UwSMrxO6zhD2Pq4rZAGoa1WSJywc=', 'images/avatars/default_avatar.jpg', '2025-02-22 06:14:41', '2025-04-13 18:25:44');

-- --------------------------------------------------------
--
-- Dumping data for table `users_roles` (2 rows)
--

INSERT INTO `users_roles` (`user_id`, `role_id`) VALUES
('7578d3a9-e3ef-4488-9399-6c6736e8fbb8', '204444d0-dd92-4724-a20a-fa2d3c0dd7fb'),
('4a707f19-1339-460b-ba9e-237a77e771d9', '9c3e09cf-e4b3-4269-8319-e724ddad3b17');

-- --------------------------------------------------------
--
-- Dumping data for table `users_questions` (3 rows)
--

INSERT INTO `users_questions` (`user_id`, `question_id`, `user_answer`) VALUES
('4a707f19-1339-460b-ba9e-237a77e771d9', '83a6daf3-eec3-11ef-b0e7-0242ac110002', '$2y$10$LEQZ/fX2W3yA0rUYJuuLP.k4W8SzTbj/ptaABvP0GVebx9PCkHwny'),
('4a707f19-1339-460b-ba9e-237a77e771d9', '83a6de61-eec3-11ef-b0e7-0242ac110002', '$2y$10$OENW6jdsnw7vgSOsWpj2I.yRro1gU4iwMKcNMoEh4qqQQV/n9ptqS'),
('4a707f19-1339-460b-ba9e-237a77e771d9', '83a6eb9f-eec3-11ef-b0e7-0242ac110002', '$2y$10$arzgv6jRR17TLjEFMx2vOOyTnyDOpVCBEK8fgpAtFmsZ1LIAslEk.');

-- --------------------------------------------------------
--
-- Dumping data for table `slideshow_images` (8 rows)
--

INSERT INTO `slideshow_images` (`slideshow_image_id`, `slideshow_image_url`, `slideshow_image_index`, `slideshow_image_alt`, `created_at`, `updated_at`) VALUES
('055d6659-dbd8-11ef-9ef8-088fc3163dd3', 'file-manager/slides/car.png', 0, 'logo', '2025-01-26 04:23:52', '2025-04-05 12:33:58'),
('055da713-dbd8-11ef-9ef8-088fc3163dd3', 'file-manager/slides/pngtree-black-super-car-png-image_11921537.png', 100, 'pngtree-black-super-car-png-image_11921537', '2025-01-26 04:23:52', '2025-04-26 18:53:14'),
('22c138d2-dbd8-11ef-9ef8-088fc3163dd3', 'file-manager/slides/pngtree-silver-super-car-png-image_11974437.png', 40, 'pngtree-silver-super-car-png-image_11974437', '2025-01-26 04:24:41', '2025-04-05 12:34:09'),
('46d80d6d-1b2f-4333-8a2c-e2d9854baf78', 'file-manager/slides/porsche-911-gt3-rs.jpg', 10, 'PORSCHE 911 GT3 RS', '2026-10-08 16:16:53', '2026-10-08 16:16:53'),
('69a059b4-1fa2-42e6-81b0-1872196af76d', 'file-manager/slides/mclaren-spider-720s.jpg', 30, 'MCLAREN 720S PERFORMANCE', '2026-10-08 16:16:53', '2026-10-08 16:16:53'),
('7dff9abb-6699-4154-a889-f7344070747c', 'file-manager/slides/mercedes-amg-gt-r.jpg', 60, 'MERCEDES-AMG GT R PRO', '2026-10-08 16:16:53', '2026-10-08 16:16:53'),
('972553b2-871c-4c84-a72b-0fc9c889e053', 'file-manager/slides/lamborghini-aventador.jpg', 50, 'LAMBORGHINI AVENTADOR SVJ', '2026-10-08 16:16:53', '2026-10-08 16:16:53'),
('ef876f31-2521-4725-be7f-c3d49b2f344f', 'file-manager/slides/ferrari-f8-tributo.jpg', 20, 'FERRARI F8 TRIBUTO', '2026-10-08 16:16:53', '2026-10-08 16:16:53');

-- --------------------------------------------------------
--
-- Dumping data for table `posts` (41 rows)
--

INSERT INTO `posts` (`post_id`, `post_name`, `post_slug`, `post_release`, `post_status`, `post_type`, `user_id`, `created_at`, `updated_at`, `post_content`, `post_image_path`, `post_image_alt`) VALUES
('0273210e-a298-4dd0-aa30-5c2557b02efb', 'Liquid Bronze & Brushed Titanium Textured Wrap Vector', 'liquid-bronze-brushed-titanium-textured-wrap-vector', '2026-09-08 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:19', '<p>Luxury metallic bronze texture overlay template for automotive and consumer tech wraps.</p>', 'file-manager/products/liquid-bronze-brushed-titanium-textured-wrap-vector/main.png', 'Liquid Bronze & Brushed Titanium Textured Wrap Vector'),
('0564bbf2-4b43-4748-bcfa-283a65818167', 'Cyberpunk Geometric Camo Full Vehicle Wrap Pattern', 'cyberpunk-geometric-camo-full-vehicle-wrap-pattern', '2026-10-06 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:19', '<p>Scalable vector camouflage graphics package for full automotive color-change wraps. 100% vector, infinite resolution.</p>', 'file-manager/products/cyberpunk-geometric-camo-full-vehicle-wrap-pattern/main.png', 'Cyberpunk Geometric Camo Full Vehicle Wrap Pattern'),
('072b2927-f729-43fe-975d-c9d2d6db2ddc', 'Mercedes Hyperscreen Triple Display Ultra-Clear Protection Cut File', 'mercedes-hyperscreen-triple-display-ultra-clear-protection-cut-file', '2026-09-17 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:19', '<p>Pillar-to-pillar glass protection pattern for <strong>Mercedes EQS / EQE Hyperscreen</strong> curved OLED panels.</p>', 'file-manager/products/mercedes-hyperscreen-triple-display-ultra-clear-protection-cut-file/main.png', 'Mercedes Hyperscreen Triple Display Ultra-Clear Protection Cut File'),
('101af6aa-3216-4078-9166-c1560f80fe37', 'LG Velvet 5G - Raindrop Camera Skin Template', 'lg-velvet-5g-raindrop-camera-skin-template', '2026-09-23 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Precision skin template for <strong>LG Velvet 5G</strong> raindrop camera styling and back curve.</p>', 'file-manager/products/lg-velvet-5g-raindrop-camera-skin-template/main.png', 'LG Velvet 5G - Raindrop Camera Skin Template'),
('1392253f-ce01-47b5-996d-bb167486b763', 'ThinkPad X1 Carbon Gen 12 - Precision Cut File', 'thinkpad-x1-carbon-gen-12-precision-cut-file', '2026-09-04 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Iconic TrackPoint-compatible cut file for <strong>ThinkPad X1 Carbon Gen 12</strong>.</p>', 'file-manager/products/thinkpad-x1-carbon-gen-12-precision-cut-file/main.png', 'ThinkPad X1 Carbon Gen 12 - Precision Cut File'),
('1f863020-6569-4cf5-bc97-d60e673e3e2b', 'Acer Swift Go 14 - Slim Ultrabook Skin Template', 'acer-swift-go-14-slim-ultrabook-skin-template', '2026-09-14 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:20', '<p>Minimalist precision template for <strong>Acer Swift Go 14</strong> OLED laptop.</p>', 'file-manager/products/acer-swift-go-14-slim-ultrabook-skin-template/main.png', 'Acer Swift Go 14 - Slim Ultrabook Skin Template'),
('25bfa09a-62b6-4391-b79c-9bcd83c4fefa', 'Tesla Model 3 / Y Center Screen 15.4\" Protector Template', 'tesla-model-3-y-center-screen-154-protector-template', '2026-09-01 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:17', '<p>Matte anti-glare & ultra-clear digital cutting templates for <strong>Tesla Model 3 & Model Y</strong> 15.4-inch navigation touchscreens.</p>', 'file-manager/products/tesla-model-3-y-center-screen-154-protector-template/main.png', 'Tesla Model 3 / Y Center Screen 15.4\" Protector Template'),
('2d73d7b8-1f1f-4438-904a-6f32e0907545', 'Ultra-Realistic 3D Carbon Fiber Vector Pattern Pack', 'ultra-realistic-3d-carbon-fiber-vector-pattern-pack', '2026-10-10 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:20', '<p>Seamless vector twill weave carbon fiber textures optimized for large format printing and vinyl wrapping.</p>', 'file-manager/products/ultra-realistic-3d-carbon-fiber-vector-pattern-pack/main.png', 'Ultra-Realistic 3D Carbon Fiber Vector Pattern Pack'),
('3a23d887-96d3-4ba8-9df6-7ac7c41fa1b4', 'Samsung Galaxy S24 Ultra - Full Coverage Skin Template', 'samsung-galaxy-s24-ultra-full-coverage-skin-template', '2026-09-22 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Full body vector cut template for <strong>Samsung Galaxy S24 Ultra</strong> with S-Pen cutout and individual camera lens protection.</p>', 'file-manager/products/samsung-galaxy-s24-ultra-full-coverage-skin-template/main.png', 'Samsung Galaxy S24 Ultra - Full Coverage Skin Template'),
('40cb77a3-7c97-42b7-b576-cdc11ed45e78', 'LG Wing - Dual Swivel Screen Wrap Template', 'lg-wing-dual-swivel-screen-wrap-template', '2026-10-06 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Unique dual-display cut template for <strong>LG Wing</strong> swivel mechanism.</p>', 'file-manager/products/lg-wing-dual-swivel-screen-wrap-template/main.png', 'LG Wing - Dual Swivel Screen Wrap Template'),
('426f6880-d804-413b-a4d5-052a206b9083', 'Sony Xperia 5 V - Compact Vector Template', 'sony-xperia-5-v-compact-vector-template', '2026-10-11 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Precision wrap cut file for <strong>Sony Xperia 5 V</strong>. CDR, DXF, and SVG included.</p>', 'file-manager/products/sony-xperia-5-v-compact-vector-template/main.png', 'Sony Xperia 5 V - Compact Vector Template'),
('49b1b62e-e962-4522-92b7-bb6d19412636', 'HP Omen 16 - Top Lid & Keyboard Deck Cut File', 'hp-omen-16-top-lid-keyboard-deck-cut-file', '2026-09-26 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Vector cut file for <strong>HP Omen 16</strong> gaming laptop. 1:1 real-device verified.</p>', 'file-manager/products/hp-omen-16-top-lid-keyboard-deck-cut-file/main.png', 'HP Omen 16 - Top Lid & Keyboard Deck Cut File'),
('5192f3a4-0fae-4112-b079-e596a2ccb3eb', 'iPhone 15 Pro - Full Body & Frame Skin Cut File', 'iphone-15-pro-full-body-frame-skin-cut-file', '2026-09-08 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Precision cut files for <strong>iPhone 15 Pro</strong> with Action Button cutouts. Formats included: SVG, DXF, CDR, EPS, PLT.</p>', 'file-manager/products/iphone-15-pro-full-body-frame-skin-cut-file/main.png', 'iPhone 15 Pro - Full Body & Frame Skin Cut File'),
('60e3506c-ece8-4f4d-9d2b-33e4e0624461', 'Samsung Galaxy Z Fold 5 - Dual Screen Wrap Template', 'samsung-galaxy-z-fold-5-dual-screen-wrap-template', '2026-08-30 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Comprehensive skin template for <strong>Samsung Galaxy Z Fold 5</strong> covering hinge, outer screen bezel, and rear chassis.</p>', 'file-manager/products/samsung-galaxy-z-fold-5-dual-screen-wrap-template/main.png', 'Samsung Galaxy Z Fold 5 - Dual Screen Wrap Template'),
('6435e924-588f-407c-93a6-bcb05b3ba7bf', 'Mercedes-AMG Interior Carbon & Screen Trim Protection File', 'mercedes-amg-interior-carbon-screen-trim-protection-file', '2026-09-06 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:18', '<p>High-wear area interior protection templates for <strong>Mercedes-AMG</strong> steering wheel accents and central console.</p>', 'file-manager/products/mercedes-amg-interior-carbon-screen-trim-protection-file/main.png', 'Mercedes-AMG Interior Carbon & Screen Trim Protection File'),
('69b6f64f-a325-4c0b-b305-64e7407282cd', 'Dell XPS 13 Plus - Seamless Touch Bar Skin File', 'dell-xps-13-plus-seamless-touch-bar-skin-file', '2026-09-11 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Ultra-accurate template for <strong>Dell XPS 13 Plus</strong> zero-lattice keyboard and invisible glass trackpad.</p>', 'file-manager/products/dell-xps-13-plus-seamless-touch-bar-skin-file/main.png', 'Dell XPS 13 Plus - Seamless Touch Bar Skin File'),
('6a6d7f98-f23b-4c54-8b5f-0ad7efc9d02b', 'Porsche 911 (992) Center Console & Dashboard PPF Template', 'porsche-911-992-center-console-dashboard-ppf-template', '2026-10-09 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:20', '<p>Interior anti-scratch protective film cut file for <strong>Porsche 911 (992)</strong> gloss piano black trims, gear toggle, and cup holder bezel.</p>', 'file-manager/products/porsche-911-992-center-console-dashboard-ppf-template/main.png', 'Porsche 911 (992) Center Console & Dashboard PPF Template'),
('6e5ed4c6-5ca6-497b-9bd8-a33ecb5af37f', 'Asus ROG Zephyrus G16 - Full Body Skin Template', 'asus-rog-zephyrus-g16-full-body-skin-template', '2026-10-01 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Precision cut file for <strong>Asus ROG Zephyrus G16</strong> slash lighting lid and stealth keyboard deck.</p>', 'file-manager/products/asus-rog-zephyrus-g16-full-body-skin-template/main.png', 'Asus ROG Zephyrus G16 - Full Body Skin Template'),
('81095b4d-d622-4d57-8eae-cbf4b08aea35', 'Ferrari F8 Tributo - Complete Body PPF Template', 'ferrari-f8-tributo-complete-body-ppf-template', '2026-10-09 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>High-precision digital PPF cutting patterns for <strong>Ferrari F8 Tributo</strong>. Zero-stretch design for flawless film installation without blade-on-paint risks.</p>', 'file-manager/products/ferrari-f8-tributo-complete-body-ppf-template/main.jpg', 'Ferrari F8 Tributo - Complete Body PPF Template'),
('820de0c2-670e-4735-ad41-515330ccaeb5', 'Dell XPS 15 (9530) - Full Coverage Skin Template', 'dell-xps-15-9530-full-coverage-skin-template', '2026-10-02 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Industry-standard skin template for <strong>Dell XPS 15</strong> carbon fiber palmrest, top lid, and bottom panel.</p>', 'file-manager/products/dell-xps-15-9530-full-coverage-skin-template/main.png', 'Dell XPS 15 (9530) - Full Coverage Skin Template'),
('8c866ebb-bd24-488e-955d-3efd01f9eec3', 'iPhone 16 Pro Max - Full Body Skin Template', 'iphone-16-pro-max-full-body-skin-template', '2026-10-10 11:01:54', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:01:54', '2026-10-13 11:01:54', '<p>Ultra-accurate precision cut template for <strong>Apple iPhone 16 Pro Max</strong>. Includes full back, camera bump, edge wraps, and separated lens protectors. Tested on real devices for Graphtec, Roland, and Cricut vinyl cutters.</p>', 'file-manager/categories/iphone.png', 'iPhone 16 Pro Max - Full Body Skin Template'),
('8cbae845-b277-4196-88c3-c46ccf7974d9', 'Google Pixel 8 Pro - Full Body Vinyl Skin Template', 'google-pixel-8-pro-full-body-vinyl-skin-template', '2026-09-13 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Tested cutting template for <strong>Google Pixel 8 Pro</strong>. 100% accurate alignment on original device.</p>', 'file-manager/products/google-pixel-8-pro-full-body-vinyl-skin-template/main.png', 'Google Pixel 8 Pro - Full Body Vinyl Skin Template'),
('9e698364-bac7-472b-9b9e-de588fc8b279', 'Kawasaki Ninja ZX-10R - Custom Wrap Template Package', 'kawasaki-ninja-zx-10r-custom-wrap-template-package', '2026-09-14 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:19', '<p>Ready-to-cut vector template for <strong>Kawasaki Ninja ZX-10R</strong> fairing graphics and stone chip defense.</p>', 'file-manager/products/kawasaki-ninja-zx-10r-custom-wrap-template-package/main.png', 'Kawasaki Ninja ZX-10R - Custom Wrap Template Package'),
('9f2a653e-d87a-438d-a24a-9ef5bd89d0a4', 'Mercedes-AMG GT R - Full Body Wrap & PPF Template', 'mercedes-amg-gt-r-full-body-wrap-ppf-template', '2026-09-26 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:18', '<p>Computer-engineered PPF cut file for <strong>Mercedes-AMG GT R</strong> Panamericana grille surrounds, long hood, and wide rear fenders.</p>', 'file-manager/products/mercedes-amg-gt-r-full-body-wrap-ppf-template/main.jpg', 'Mercedes-AMG GT R - Full Body Wrap & PPF Template'),
('a1fea46c-eb38-4d91-9daa-d7c7f4b34f9d', 'Sony Xperia 1 VI - Full Body Vinyl Skin Cut File', 'sony-xperia-1-vi-full-body-vinyl-skin-cut-file', '2026-10-04 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Accurate vector file for <strong>Sony Xperia 1 VI</strong> textured frame and triple Zeiss optics.</p>', 'file-manager/products/sony-xperia-1-vi-full-body-vinyl-skin-cut-file/main.png', 'Sony Xperia 1 VI - Full Body Vinyl Skin Cut File'),
('a355e520-02fc-4bd8-a7fa-6d295e43360d', 'Lamborghini Aventador - Paint Protection Film Cut File', 'lamborghini-aventador-paint-protection-film-cut-file', '2026-08-30 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:17', '<p>Extensive PPF cut package for <strong>Lamborghini Aventador LP780-4 Ultimae</strong> sharp edges, aggressive splitters, and engine louvers.</p>', 'file-manager/products/lamborghini-aventador-paint-protection-film-cut-file/main.jpg', 'Lamborghini Aventador - Paint Protection Film Cut File'),
('a9fe9f46-1810-46bb-8dc4-4d5d7b98d47b', 'Asus Zenbook 14 OLED - Geometric Lid Wrap Template', 'asus-zenbook-14-oled-geometric-lid-wrap-template', '2026-09-26 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Laser-measured cut file for <strong>Asus Zenbook 14 OLED</strong> monogram lid.</p>', 'file-manager/products/asus-zenbook-14-oled-geometric-lid-wrap-template/main.png', 'Asus Zenbook 14 OLED - Geometric Lid Wrap Template'),
('aa244f41-f424-47c9-9d55-3d282f5751ad', 'Acer Predator Helios 16 - Vinyl Skin Cut File', 'acer-predator-helios-16-vinyl-skin-cut-file', '2026-09-17 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Custom skin template for <strong>Acer Predator Helios 16</strong> RGB exhaust and lid badge.</p>', 'file-manager/products/acer-predator-helios-16-vinyl-skin-cut-file/main.png', 'Acer Predator Helios 16 - Vinyl Skin Cut File'),
('ad9d502f-8623-4b07-97ad-3b90e848d19b', 'Oppo Reno 11 Pro - Full Wrap Cut File', 'oppo-reno-11-pro-full-wrap-cut-file', '2026-10-07 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Accurate skin cut file for <strong>Oppo Reno 11 Pro</strong>. Includes back and antenna split lines.</p>', 'file-manager/products/oppo-reno-11-pro-full-wrap-cut-file/main.png', 'Oppo Reno 11 Pro - Full Wrap Cut File'),
('b67122dd-3402-4352-a946-ac8061d0fad6', 'Google Pixel 9 Pro - Camera Visor & Back Cut File', 'google-pixel-9-pro-camera-visor-back-cut-file', '2026-09-20 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Precision vector files for <strong>Google Pixel 9 Pro</strong> with the newly redesigned camera island and chamfered edges.</p>', 'file-manager/products/google-pixel-9-pro-camera-visor-back-cut-file/main.png', 'Google Pixel 9 Pro - Camera Visor & Back Cut File'),
('c5aedd2f-4832-48b9-a6fc-ce71d7a10d5f', 'Alienware m18 R2 - Top Lid & Palmrest Skin Template', 'alienware-m18-r2-top-lid-palmrest-skin-template', '2026-09-10 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>High-precision vinyl cut file for <strong>Alienware m18 R2</strong> gaming laptop. Covers top lid alien logo, keyboard deck, and trackpad.</p>', 'file-manager/products/alienware-m18-r2-top-lid-palmrest-skin-template/main.png', 'Alienware m18 R2 - Top Lid & Palmrest Skin Template'),
('c9439239-f801-4788-ad68-e04fdf1e5154', 'Ducati Panigale V4 - Full Livery & Armor PPF Template', 'ducati-panigale-v4-full-livery-armor-ppf-template', '2026-09-14 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:20', '<p>Aero winglet and sculpted tank protection wrap file for <strong>Ducati Panigale V4 / V4S</strong>.</p>', 'file-manager/products/ducati-panigale-v4-full-livery-armor-ppf-template/main.png', 'Ducati Panigale V4 - Full Livery & Armor PPF Template'),
('d92c8b1e-807b-4db6-a781-63fb5a428008', 'Porsche 911 GT3 RS - Full Exterior PPF Cut File', 'porsche-911-gt3-rs-full-exterior-ppf-cut-file', '2026-09-10 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:20', '<p>Full vehicle exterior Paint Protection Film (PPF) pre-cut package for <strong>Porsche 911 GT3 RS</strong>. Includes front bumper, aero fenders, bonnet with nostrils, swan-neck wing, doors, and rear quarter panels. Pre-tested on Graphtec FC9000 & Roland cutters.</p>', 'file-manager/products/porsche-911-gt3-rs-full-exterior-ppf-cut-file/main.jpg', 'Porsche 911 GT3 RS - Full Exterior PPF Cut File'),
('d9732744-c59e-4c44-b335-e578526fdee1', 'HP Spectre x360 14 - Full Body Skin Template', 'hp-spectre-x360-14-full-body-skin-template', '2026-09-09 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:20', '<p>Precision template for <strong>HP Spectre x360 14</strong> gem-cut corners, lid, and bottom vent cutouts.</p>', 'file-manager/products/hp-spectre-x360-14-full-body-skin-template/main.png', 'HP Spectre x360 14 - Full Body Skin Template'),
('dd3d27e8-adfa-4797-a91a-3b9284f41bc0', 'Samsung Galaxy S23 Plus - Precision Vinyl Cut File', 'samsung-galaxy-s23-plus-precision-vinyl-cut-file', '2026-09-15 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:18', '<p>Ready-to-cut vector file for <strong>Samsung Galaxy S23 Plus</strong>. Flawless 1:1 scale dimensions.</p>', 'file-manager/products/samsung-galaxy-s23-plus-precision-vinyl-cut-file/main.png', 'Samsung Galaxy S23 Plus - Precision Vinyl Cut File'),
('de1b360a-2736-492d-a4b9-7803906d2655', 'Oppo Find X7 Ultra - Quad Camera Skin Template', 'oppo-find-x7-ultra-quad-camera-skin-template', '2026-09-06 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Precision cut vector file for <strong>Oppo Find X7 Ultra</strong> circular camera module and curved back panel.</p>', 'file-manager/products/oppo-find-x7-ultra-quad-camera-skin-template/main.png', 'Oppo Find X7 Ultra - Quad Camera Skin Template'),
('e7cee885-e080-42a4-a84f-02c76fba1deb', 'iPhone 14 Pro Max - Vector Cut Template', 'iphone-14-pro-max-vector-cut-template', '2026-09-04 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:19', '<p>Accurate vector wrap template for <strong>iPhone 14 Pro Max</strong>. Fits standard vinyl and 3M Di-Noc / 1080 materials.</p>', 'file-manager/products/iphone-14-pro-max-vector-cut-template/main.png', 'iPhone 14 Pro Max - Vector Cut Template'),
('ec5339e9-a4bc-488f-b12e-14e30f692986', 'Lenovo Legion Pro 7i - Heavy Gaming Skin Template', 'lenovo-legion-pro-7i-heavy-gaming-skin-template', '2026-09-23 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:17', '<p>Engineered cut template for <strong>Lenovo Legion Pro 7i</strong> lid, palmrest, and vented base.</p>', 'file-manager/products/lenovo-legion-pro-7i-heavy-gaming-skin-template/main.png', 'Lenovo Legion Pro 7i - Heavy Gaming Skin Template'),
('f50ab8d1-6add-4c2d-ac23-7ea6e230f5e5', 'Alienware x16 - Slim Gaming Laptop Wrap File', 'alienware-x16-slim-gaming-laptop-wrap-file', '2026-09-09 11:03:28', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:28', '2026-10-13 14:01:20', '<p>Full wrap template for <strong>Alienware x16</strong> with rear stadium lighting cutouts.</p>', 'file-manager/products/alienware-x16-slim-gaming-laptop-wrap-file/main.png', 'Alienware x16 - Slim Gaming Laptop Wrap File'),
('f68940c7-8bee-4b24-8019-9da8fb8fd61d', 'Universal Vehicle Door Cup & Handle Scratch Protection PPF Kit', 'universal-vehicle-door-cup-handle-scratch-protection-ppf-kit', '2026-09-21 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:18', '<p>Set of 12 universal contoured door handle cup & handle outer templates for cars, SUVs, and trucks. Defends against ring and nail scratches.</p>', 'file-manager/products/universal-vehicle-door-cup-handle-scratch-protection-ppf-kit/main.png', 'Universal Vehicle Door Cup & Handle Scratch Protection PPF Kit'),
('f902dad2-f716-4028-9238-62c80db543e1', 'Yamaha YZF-R1 - Full Racing Fairing Wrap Vector File', 'yamaha-yzf-r1-full-racing-fairing-wrap-vector-file', '2026-09-26 11:03:29', 1, 1, '4a707f19-1339-460b-ba9e-237a77e771d9', '2026-10-13 11:03:29', '2026-10-13 14:01:19', '<p>Complete digital livery and PPF template for <strong>Yamaha YZF-R1</strong> supersport bike. Includes tank, nose, side fairings, and tail cowl.</p>', 'file-manager/products/yamaha-yzf-r1-full-racing-fairing-wrap-vector-file/main.png', 'Yamaha YZF-R1 - Full Racing Fairing Wrap Vector File');

-- --------------------------------------------------------
--
-- Dumping data for table `products` (40 rows)
--

INSERT INTO `products` (`product_id`, `product_name`, `product_price`, `product_file_path`, `post_id`, `product_views`, `product_fake_views`, `product_status_views`, `created_at`, `updated_at`) VALUES
('029803d4-c800-4ed8-9558-d666db1a18d9', 'Lenovo Legion Pro 7i - Heavy Gaming Skin Template', '37.99', 'file-manager/products/laptop.jpg', 'ec5339e9-a4bc-488f-b12e-14e30f692986', 52, 10, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('029bbbe3-9e85-4f33-bda0-1577f013a7d0', 'Lamborghini Aventador - Paint Protection Film Cut File', '179.99', 'file-manager/products/laptop.jpg', 'a355e520-02fc-4bd8-a7fa-6d295e43360d', 137, 52, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('065689c5-ec35-454e-8532-046faed23a79', 'Tesla Model 3 / Y Center Screen 15.4\" Protector Template', '19.99', 'file-manager/products/laptop.jpg', '25bfa09a-62b6-4391-b79c-9bcd83c4fefa', 429, 49, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('0b3f17f9-03a8-4bc5-a463-cb15f0412506', 'Google Pixel 9 Pro - Camera Visor & Back Cut File', '26.99', 'file-manager/products/laptop.jpg', 'b67122dd-3402-4352-a946-ac8061d0fad6', 103, 34, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('0c834dc4-050f-465b-b04a-b88792e423ba', 'Samsung Galaxy S23 Plus - Precision Vinyl Cut File', '22.99', 'file-manager/products/laptop.jpg', 'dd3d27e8-adfa-4797-a91a-3b9284f41bc0', 323, 16, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('196a02c8-f868-4a8b-b5ed-937b0d123340', 'Dell XPS 13 Plus - Seamless Touch Bar Skin File', '31.99', 'file-manager/products/laptop.jpg', '69b6f64f-a325-4c0b-b305-64e7407282cd', 92, 13, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('243cab68-27bf-49ae-95eb-f4ad37e38566', 'Alienware m18 R2 - Top Lid & Palmrest Skin Template', '39.99', 'file-manager/products/laptop.jpg', 'c5aedd2f-4832-48b9-a6fc-ce71d7a10d5f', 341, 35, 1, '2026-10-13 11:03:28', '2026-10-14 11:09:09'),
('2d284119-c521-4043-9bd6-a061c504e32e', 'iPhone 15 Pro - Full Body & Frame Skin Cut File', '24.99', 'file-manager/products/laptop.jpg', '5192f3a4-0fae-4112-b079-e596a2ccb3eb', 110, 43, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('385dbfe3-a662-4c25-a813-14fee38baed0', 'LG Velvet 5G - Raindrop Camera Skin Template', '18.99', 'file-manager/products/laptop.jpg', '101af6aa-3216-4078-9166-c1560f80fe37', 319, 34, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('3882b1bc-f194-48c5-83e2-e569ff967f77', 'HP Omen 16 - Top Lid & Keyboard Deck Cut File', '29.99', 'file-manager/products/laptop.jpg', '49b1b62e-e962-4522-92b7-bb6d19412636', 66, 46, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('3d69419b-2869-4b5f-995d-c15de97f166c', 'Mercedes-AMG Interior Carbon & Screen Trim Protection File', '54.99', 'file-manager/products/laptop.jpg', '6435e924-588f-407c-93a6-bcb05b3ba7bf', 115, 23, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('4304eb5f-337d-4a77-bd04-8c87356399e7', 'LG Wing - Dual Swivel Screen Wrap Template', '24.99', 'file-manager/products/laptop.jpg', '40cb77a3-7c97-42b7-b576-cdc11ed45e78', 293, 11, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('4725d9ae-8dfd-48e0-9ccb-30b44646f8ad', 'Mercedes-AMG GT R - Full Body Wrap & PPF Template', '139.99', 'file-manager/products/laptop.jpg', '9f2a653e-d87a-438d-a24a-9ef5bd89d0a4', 171, 36, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('4917076a-3c58-4847-b9b7-5b5c1589c020', 'Universal Vehicle Door Cup & Handle Scratch Protection PPF Kit', '14.99', 'file-manager/products/laptop.jpg', 'f68940c7-8bee-4b24-8019-9da8fb8fd61d', 272, 11, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('5894f9e3-06f6-4b34-ab1d-c2a9775d3865', 'Asus ROG Zephyrus G16 - Full Body Skin Template', '38.99', 'file-manager/products/laptop.jpg', '6e5ed4c6-5ca6-497b-9bd8-a33ecb5af37f', 284, 46, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('5bd89968-ec2a-4515-b5ce-73e773971c2b', 'Asus Zenbook 14 OLED - Geometric Lid Wrap Template', '28.99', 'file-manager/products/laptop.jpg', 'a9fe9f46-1810-46bb-8dc4-4d5d7b98d47b', 222, 50, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('6744e1b3-6eae-431b-a911-94160230b2fe', 'Kawasaki Ninja ZX-10R - Custom Wrap Template Package', '64.99', 'file-manager/products/laptop.jpg', '9e698364-bac7-472b-9b9e-de588fc8b279', 39, 43, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('6c064a82-14ac-4f0d-a14c-fec268ab21d8', 'ThinkPad X1 Carbon Gen 12 - Precision Cut File', '32.99', 'file-manager/products/laptop.jpg', '1392253f-ce01-47b5-996d-bb167486b763', 77, 32, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('70c3510d-fb47-4403-adc9-8e4949bc7da0', 'Mercedes Hyperscreen Triple Display Ultra-Clear Protection Cut File', '39.99', 'file-manager/products/laptop.jpg', '072b2927-f729-43fe-975d-c9d2d6db2ddc', 391, 23, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('75c78c81-3338-4819-8d89-f061c3be30f9', 'Oppo Reno 11 Pro - Full Wrap Cut File', '19.99', 'file-manager/products/laptop.jpg', 'ad9d502f-8623-4b07-97ad-3b90e848d19b', 388, 60, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('8351e2b2-7c3a-472b-b59f-381540841a71', 'iPhone 14 Pro Max - Vector Cut Template', '19.99', 'file-manager/products/laptop.jpg', 'e7cee885-e080-42a4-a84f-02c76fba1deb', 315, 47, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('a1060a2b-c1d8-4542-823a-fb8e2e19955d', 'Liquid Bronze & Brushed Titanium Textured Wrap Vector', '39.99', 'file-manager/products/laptop.jpg', '0273210e-a298-4dd0-aa30-5c2557b02efb', 55, 33, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('a6e443e2-fdb4-423b-bed8-9eb50737a7f5', 'Google Pixel 8 Pro - Full Body Vinyl Skin Template', '21.99', 'file-manager/products/laptop.jpg', '8cbae845-b277-4196-88c3-c46ccf7974d9', 76, 10, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('a7c22105-4b7d-4275-909e-6c2e28bdcd08', 'Sony Xperia 1 VI - Full Body Vinyl Skin Cut File', '27.99', 'file-manager/products/laptop.jpg', 'a1fea46c-eb38-4d91-9daa-d7c7f4b34f9d', 426, 48, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('a81a4f71-8b7e-49be-9175-9fe947b9d514', 'Dell XPS 15 (9530) - Full Coverage Skin Template', '35.99', 'file-manager/products/laptop.jpg', '820de0c2-670e-4735-ad41-515330ccaeb5', 163, 32, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('a886a9f6-a7b6-468e-9b57-0c613f10f51c', 'Acer Predator Helios 16 - Vinyl Skin Cut File', '34.99', 'file-manager/products/laptop.jpg', 'aa244f41-f424-47c9-9d55-3d282f5751ad', 106, 35, 1, '2026-10-13 11:03:28', '2026-10-13 11:21:58'),
('a94af25d-a177-4264-acb5-30ec69b79f11', 'Samsung Galaxy S24 Ultra - Full Coverage Skin Template', '29.99', 'file-manager/products/laptop.jpg', '3a23d887-96d3-4ba8-9df6-7ac7c41fa1b4', 278, 50, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('bc6df49c-c7f8-4f7f-9ab3-af2b6c93dcb8', 'Oppo Find X7 Ultra - Quad Camera Skin Template', '25.99', 'file-manager/products/laptop.jpg', 'de1b360a-2736-492d-a4b9-7803906d2655', 236, 24, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('bc8cce3f-572c-4037-99dc-1d48ec2ac693', 'Ferrari F8 Tributo - Complete Body PPF Template', '169.99', 'file-manager/products/laptop.jpg', '81095b4d-d622-4d57-8eae-cbf4b08aea35', 51, 12, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('c002a74d-c37d-4c38-a53d-41de9ec5b94e', 'Samsung Galaxy Z Fold 5 - Dual Screen Wrap Template', '34.99', 'file-manager/products/laptop.jpg', '60e3506c-ece8-4f4d-9d2b-33e4e0624461', 186, 24, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('c2b756f2-45b7-4d7a-b9ec-afdb1e38e21c', 'Cyberpunk Geometric Camo Full Vehicle Wrap Pattern', '59.99', 'file-manager/products/laptop.jpg', '0564bbf2-4b43-4748-bcfa-283a65818167', 371, 12, 1, '2026-10-13 11:03:29', '2026-10-13 11:20:58'),
('c6aedfb9-2526-44c9-ad32-a6d9da7b07fa', 'Sony Xperia 5 V - Compact Vector Template', '22.99', 'file-manager/products/laptop.jpg', '426f6880-d804-413b-a4d5-052a206b9083', 121, 26, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('cc9cdf64-ce50-4523-8173-6a2ae10f67ac', 'Yamaha YZF-R1 - Full Racing Fairing Wrap Vector File', '69.99', 'file-manager/products/laptop.jpg', 'f902dad2-f716-4028-9238-62c80db543e1', 377, 39, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('d075c0b9-022f-4aee-a353-67c73243c5be', 'Ultra-Realistic 3D Carbon Fiber Vector Pattern Pack', '45.99', 'file-manager/products/laptop.jpg', '2d73d7b8-1f1f-4438-904a-6f32e0907545', 158, 45, 1, '2026-10-13 11:03:29', '2026-10-13 13:47:35'),
('d6032815-0be5-47b1-9593-f049f03ab19f', 'Porsche 911 (992) Center Console & Dashboard PPF Template', '49.99', 'file-manager/products/laptop.jpg', '6a6d7f98-f23b-4c54-8b5f-0ad7efc9d02b', 364, 16, 1, '2026-10-13 11:03:29', '2026-10-13 15:13:29'),
('dac6412f-2644-4f07-b985-09ee8ec7b8b5', 'Acer Swift Go 14 - Slim Ultrabook Skin Template', '26.99', 'file-manager/products/laptop.jpg', '1f863020-6569-4cf5-bc97-d60e673e3e2b', 110, 30, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('eb690ab8-f9f7-43ed-8dcc-416216e5263a', 'HP Spectre x360 14 - Full Body Skin Template', '32.99', 'file-manager/products/laptop.jpg', 'd9732744-c59e-4c44-b335-e578526fdee1', 385, 28, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('f5edba64-6acd-4ed1-957e-69de04ad2bf0', 'Alienware x16 - Slim Gaming Laptop Wrap File', '36.99', 'file-manager/products/laptop.jpg', 'f50ab8d1-6add-4c2d-ac23-7ea6e230f5e5', 204, 29, 1, '2026-10-13 11:03:28', '2026-10-13 11:03:28'),
('fa62534a-617d-459c-b96a-a54526b97b4f', 'Ducati Panigale V4 - Full Livery & Armor PPF Template', '79.99', 'file-manager/products/laptop.jpg', 'c9439239-f801-4788-ad68-e04fdf1e5154', 33, 51, 1, '2026-10-13 11:03:29', '2026-10-13 11:03:29'),
('fb8efce9-3628-47b9-95fe-6156fbc7f714', 'Porsche 911 GT3 RS - Full Exterior PPF Cut File', '149.99', 'file-manager/products/laptop.jpg', 'd92c8b1e-807b-4db6-a781-63fb5a428008', 373, 28, 1, '2026-10-13 11:03:28', '2026-10-13 12:44:43');

-- --------------------------------------------------------
--
-- Dumping data for table `product_images` (160 rows)
--

INSERT INTO `product_images` (`product_image_id`, `product_image_path`, `product_image_alt`, `created_at`, `product_id`) VALUES
('002d4e03-d84b-42d5-a372-fdf17e312a93', 'file-manager/products/hp-omen-16-top-lid-keyboard-deck-cut-file/real-lid.jpg', 'HP Omen 16 - Top Lid & Keyboard Deck Cut File - Top Lid Wrap View', '2026-10-13 14:01:18', '3882b1bc-f194-48c5-83e2-e569ff967f77'),
('0034b878-bad4-424d-a650-ce87a001e9b2', 'file-manager/products/google-pixel-8-pro-full-body-vinyl-skin-template/vector-dxf.jpg', 'Google Pixel 8 Pro - Full Body Vinyl Skin Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', 'a6e443e2-fdb4-423b-bed8-9eb50737a7f5'),
('005a52e8-c7de-4e2b-bae2-d26d3458a961', 'file-manager/products/dell-xps-13-plus-seamless-touch-bar-skin-file/keyboard-deck.jpg', 'Dell XPS 13 Plus - Seamless Touch Bar Skin File - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:18', '196a02c8-f868-4a8b-b5ed-937b0d123340'),
('01f1028e-1724-4dcf-a3a3-b57c7f8966e5', 'file-manager/products/asus-rog-zephyrus-g16-full-body-skin-template/real-lid.jpg', 'Asus ROG Zephyrus G16 - Full Body Skin Template - Top Lid Wrap View', '2026-10-13 14:01:18', '5894f9e3-06f6-4b34-ab1d-c2a9775d3865'),
('02779568-1479-49bc-80a2-57e661d32b4a', 'file-manager/products/lg-wing-dual-swivel-screen-wrap-template/main.png', 'LG Wing - Dual Swivel Screen Wrap Template - Official Cut Template', '2026-10-13 14:01:18', '4304eb5f-337d-4a77-bd04-8c87356399e7'),
('033118ae-71b2-4721-9de5-e2f7117d0cd8', 'file-manager/products/lg-velvet-5g-raindrop-camera-skin-template/vector-dxf.jpg', 'LG Velvet 5G - Raindrop Camera Skin Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:18', '385dbfe3-a662-4c25-a813-14fee38baed0'),
('03e697ce-6e43-492b-bd81-ad07058d7d56', 'file-manager/products/samsung-galaxy-s24-ultra-full-coverage-skin-template/vector-dxf.jpg', 'Samsung Galaxy S24 Ultra - Full Coverage Skin Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', 'a94af25d-a177-4264-acb5-30ec69b79f11'),
('04dfa2c1-990f-4119-945b-eee03dcdddc6', 'file-manager/products/lg-wing-dual-swivel-screen-wrap-template/camera-bump.png', 'LG Wing - Dual Swivel Screen Wrap Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:18', '4304eb5f-337d-4a77-bd04-8c87356399e7'),
('0990c35e-4316-4d50-842b-8ff0c852867e', 'file-manager/products/oppo-reno-11-pro-full-wrap-cut-file/full-wrap.png', 'Oppo Reno 11 Pro - Full Wrap Cut File - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', '75c78c81-3338-4819-8d89-f061c3be30f9'),
('0a317ffb-88d3-4307-becb-f1236c9bb9c5', 'file-manager/products/dell-xps-15-9530-full-coverage-skin-template/real-lid.jpg', 'Dell XPS 15 (9530) - Full Coverage Skin Template - Top Lid Wrap View', '2026-10-13 14:01:19', 'a81a4f71-8b7e-49be-9175-9fe947b9d514'),
('0c083b72-4430-47a9-8fd0-2ae4bae66f15', 'file-manager/products/mercedes-hyperscreen-triple-display-ultra-clear-protection-cut-file/matte-stealth.png', 'Mercedes Hyperscreen Triple Display Ultra-Clear Protection Cut File - Matte Stealth 3D Angle', '2026-10-13 14:01:19', '70c3510d-fb47-4403-adc9-8e4949bc7da0'),
('0d1593d0-dbd4-4682-8eb2-29340a4450a0', 'file-manager/products/ducati-panigale-v4-full-livery-armor-ppf-template/main.png', 'Ducati Panigale V4 - Full Livery & Armor PPF Template - Official Cut Template', '2026-10-13 14:01:20', 'fa62534a-617d-459c-b96a-a54526b97b4f'),
('0d2ef6d0-1032-4473-9440-a157e2028c7a', 'file-manager/products/porsche-911-gt3-rs-full-exterior-ppf-cut-file/cut-layout.png', 'Porsche 911 GT3 RS - Full Exterior PPF Cut File - Precision Vector Cutting Sheet', '2026-10-13 14:01:20', 'fb8efce9-3628-47b9-95fe-6156fbc7f714'),
('0e4aa856-a652-4fb3-98f2-c56f275b4f94', 'file-manager/products/acer-predator-helios-16-vinyl-skin-cut-file/main.png', 'Acer Predator Helios 16 - Vinyl Skin Cut File - Official Cut Template', '2026-10-13 14:01:19', 'a886a9f6-a7b6-468e-9b57-0c613f10f51c'),
('0e54fd2f-f0ca-4616-b945-ffe531867f20', 'file-manager/products/asus-zenbook-14-oled-geometric-lid-wrap-template/real-lid.jpg', 'Asus Zenbook 14 OLED - Geometric Lid Wrap Template - Top Lid Wrap View', '2026-10-13 14:01:19', '5bd89968-ec2a-4515-b5ce-73e773971c2b'),
('0fc85523-4c0b-41eb-86d6-1ae326a48883', 'file-manager/products/google-pixel-9-pro-camera-visor-back-cut-file/full-wrap.png', 'Google Pixel 9 Pro - Camera Visor & Back Cut File - 360 Full Coverage Blueprint', '2026-10-13 14:01:18', '0b3f17f9-03a8-4bc5-a463-cb15f0412506'),
('114e8368-5885-4635-b661-db45d9710fb7', 'file-manager/products/dell-xps-15-9530-full-coverage-skin-template/edge-cutlines.png', 'Dell XPS 15 (9530) - Full Coverage Skin Template - Precision Edge Cutlines', '2026-10-13 14:01:19', 'a81a4f71-8b7e-49be-9175-9fe947b9d514'),
('1377e708-3ede-450f-a897-5667a6b891bb', 'file-manager/products/mercedes-hyperscreen-triple-display-ultra-clear-protection-cut-file/blueprint.png', 'Mercedes Hyperscreen Triple Display Ultra-Clear Protection Cut File - CAD Vector Cutline Blueprint', '2026-10-13 14:01:19', '70c3510d-fb47-4403-adc9-8e4949bc7da0'),
('16baaa2c-7e52-419f-a03d-93602a482c97', 'file-manager/products/google-pixel-8-pro-full-body-vinyl-skin-template/full-wrap.png', 'Google Pixel 8 Pro - Full Body Vinyl Skin Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', 'a6e443e2-fdb4-423b-bed8-9eb50737a7f5'),
('16f74291-42e1-46fc-8534-45ca13ecfdf2', 'file-manager/products/asus-zenbook-14-oled-geometric-lid-wrap-template/keyboard-deck.jpg', 'Asus Zenbook 14 OLED - Geometric Lid Wrap Template - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:19', '5bd89968-ec2a-4515-b5ce-73e773971c2b'),
('196afb15-cdca-47fc-b6fd-3992c13cf516', 'file-manager/products/alienware-x16-slim-gaming-laptop-wrap-file/real-lid.jpg', 'Alienware x16 - Slim Gaming Laptop Wrap File - Top Lid Wrap View', '2026-10-13 14:01:20', 'f5edba64-6acd-4ed1-957e-69de04ad2bf0'),
('1aa8a0be-7ac1-46dc-834d-5f103409979b', 'file-manager/products/mercedes-amg-gt-r-full-body-wrap-ppf-template/cut-layout.png', 'Mercedes-AMG GT R - Full Body Wrap & PPF Template - Precision Vector Cutting Sheet', '2026-10-13 14:01:18', '4725d9ae-8dfd-48e0-9ccb-30b44646f8ad'),
('1b32502c-69b4-4d6c-b847-db38f72fba6c', 'file-manager/products/dell-xps-15-9530-full-coverage-skin-template/main.png', 'Dell XPS 15 (9530) - Full Coverage Skin Template - Official Cut Template', '2026-10-13 14:01:19', 'a81a4f71-8b7e-49be-9175-9fe947b9d514'),
('1eb4771a-4f9f-49bb-9297-81faaa416ae7', 'file-manager/products/hp-spectre-x360-14-full-body-skin-template/edge-cutlines.png', 'HP Spectre x360 14 - Full Body Skin Template - Precision Edge Cutlines', '2026-10-13 14:01:20', 'eb690ab8-f9f7-43ed-8dcc-416216e5263a'),
('1f10d5b9-b1d2-43ad-9405-0211f1f06d6e', 'file-manager/products/mercedes-amg-gt-r-full-body-wrap-ppf-template/blueprint.png', 'Mercedes-AMG GT R - Full Body Wrap & PPF Template - CAD Vector Cutline Blueprint', '2026-10-13 14:01:18', '4725d9ae-8dfd-48e0-9ccb-30b44646f8ad'),
('1fb85999-b852-4a96-b872-8dbdc731601c', 'file-manager/products/acer-swift-go-14-slim-ultrabook-skin-template/main.png', 'Acer Swift Go 14 - Slim Ultrabook Skin Template - Official Cut Template', '2026-10-13 14:01:20', 'dac6412f-2644-4f07-b985-09ee8ec7b8b5'),
('1fd9ae0e-bad4-4b9c-8cff-614d51865121', 'file-manager/products/porsche-911-gt3-rs-full-exterior-ppf-cut-file/matte-stealth.png', 'Porsche 911 GT3 RS - Full Exterior PPF Cut File - Matte Stealth 3D Angle', '2026-10-13 14:01:20', 'fb8efce9-3628-47b9-95fe-6156fbc7f714'),
('203cbb43-1d9b-432e-b341-e99bdde4b7eb', 'file-manager/products/acer-predator-helios-16-vinyl-skin-cut-file/real-lid.jpg', 'Acer Predator Helios 16 - Vinyl Skin Cut File - Top Lid Wrap View', '2026-10-13 14:01:19', 'a886a9f6-a7b6-468e-9b57-0c613f10f51c'),
('2162a995-d4c5-4590-ad4a-b2e5a84c0d5f', 'file-manager/products/mercedes-hyperscreen-triple-display-ultra-clear-protection-cut-file/main.png', 'Mercedes Hyperscreen Triple Display Ultra-Clear Protection Cut File - Official Cut Template', '2026-10-13 14:01:19', '70c3510d-fb47-4403-adc9-8e4949bc7da0'),
('2290d3db-c274-43f6-9e0f-598efabc9c7d', 'file-manager/products/acer-predator-helios-16-vinyl-skin-cut-file/edge-cutlines.png', 'Acer Predator Helios 16 - Vinyl Skin Cut File - Precision Edge Cutlines', '2026-10-13 14:01:19', 'a886a9f6-a7b6-468e-9b57-0c613f10f51c'),
('23e21f5a-a725-491f-bb66-a0dd077ab02a', 'file-manager/products/dell-xps-15-9530-full-coverage-skin-template/keyboard-deck.jpg', 'Dell XPS 15 (9530) - Full Coverage Skin Template - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:19', 'a81a4f71-8b7e-49be-9175-9fe947b9d514'),
('26b963b0-1e13-4c22-97ee-31413b75e47e', 'file-manager/products/iphone-15-pro-full-body-frame-skin-cut-file/vector-dxf.jpg', 'iPhone 15 Pro - Full Body & Frame Skin Cut File - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:18', '2d284119-c521-4043-9bd6-a061c504e32e'),
('27aeb179-ca4a-4e91-9dc1-b37a593830ef', 'file-manager/products/lenovo-legion-pro-7i-heavy-gaming-skin-template/main.png', 'Lenovo Legion Pro 7i - Heavy Gaming Skin Template - Official Cut Template', '2026-10-13 14:01:17', '029803d4-c800-4ed8-9558-d666db1a18d9'),
('28cc383e-910e-468f-84ec-d09c8c79b64e', 'file-manager/products/mercedes-amg-gt-r-full-body-wrap-ppf-template/matte-stealth.png', 'Mercedes-AMG GT R - Full Body Wrap & PPF Template - Matte Stealth 3D Angle', '2026-10-13 14:01:18', '4725d9ae-8dfd-48e0-9ccb-30b44646f8ad'),
('2a10e129-0daa-4021-8d86-af389d0546af', 'file-manager/products/google-pixel-8-pro-full-body-vinyl-skin-template/main.png', 'Google Pixel 8 Pro - Full Body Vinyl Skin Template - Official Cut Template', '2026-10-13 14:01:19', 'a6e443e2-fdb4-423b-bed8-9eb50737a7f5'),
('2b2eb551-faa7-403d-bb46-62fa48367c9c', 'file-manager/products/samsung-galaxy-s23-plus-precision-vinyl-cut-file/vector-dxf.jpg', 'Samsung Galaxy S23 Plus - Precision Vinyl Cut File - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:18', '0c834dc4-050f-465b-b04a-b88792e423ba'),
('2c7bdb27-4daa-4e85-a50a-fb05dd76f0a0', 'file-manager/products/alienware-m18-r2-top-lid-palmrest-skin-template/main.png', 'Alienware m18 R2 - Top Lid & Palmrest Skin Template - Official Cut Template', '2026-10-13 14:01:18', '243cab68-27bf-49ae-95eb-f4ad37e38566'),
('2cf8e9bd-3740-47f2-ae1f-81f79d2d7239', 'file-manager/products/porsche-911-992-center-console-dashboard-ppf-template/blueprint.png', 'Porsche 911 (992) Center Console & Dashboard PPF Template - CAD Vector Cutline Blueprint', '2026-10-13 14:01:20', 'd6032815-0be5-47b1-9593-f049f03ab19f'),
('2d6e282e-3fb2-4df0-a106-6cffed3b2dc0', 'file-manager/products/mercedes-amg-interior-carbon-screen-trim-protection-file/matte-stealth.png', 'Mercedes-AMG Interior Carbon & Screen Trim Protection File - Matte Stealth 3D Angle', '2026-10-13 14:01:18', '3d69419b-2869-4b5f-995d-c15de97f166c'),
('2f0a00bb-4ed1-4707-9fad-df58030ff783', 'file-manager/products/google-pixel-9-pro-camera-visor-back-cut-file/vector-dxf.jpg', 'Google Pixel 9 Pro - Camera Visor & Back Cut File - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:18', '0b3f17f9-03a8-4bc5-a463-cb15f0412506'),
('2f1e6368-063b-4237-af71-4b586da448e4', 'file-manager/products/iphone-14-pro-max-vector-cut-template/camera-bump.png', 'iPhone 14 Pro Max - Vector Cut Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', '8351e2b2-7c3a-472b-b59f-381540841a71'),
('2faccd3a-12f9-4254-97eb-957d34b28a10', 'file-manager/products/tesla-model-3-y-center-screen-154-protector-template/matte-stealth.png', 'Tesla Model 3 / Y Center Screen 15.4\" Protector Template - Matte Stealth 3D Angle', '2026-10-13 14:01:17', '065689c5-ec35-454e-8532-046faed23a79'),
('30419456-966c-417e-bab8-1d59a3b2f8ba', 'file-manager/products/iphone-14-pro-max-vector-cut-template/main.png', 'iPhone 14 Pro Max - Vector Cut Template - Official Cut Template', '2026-10-13 14:01:19', '8351e2b2-7c3a-472b-b59f-381540841a71'),
('324afba4-8fd0-4304-b328-e67b1b4cd325', 'file-manager/products/oppo-find-x7-ultra-quad-camera-skin-template/camera-bump.png', 'Oppo Find X7 Ultra - Quad Camera Skin Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', 'bc6df49c-c7f8-4f7f-9ab3-af2b6c93dcb8'),
('35c38210-a350-4916-98e9-d55a6f489732', 'file-manager/products/hp-omen-16-top-lid-keyboard-deck-cut-file/keyboard-deck.jpg', 'HP Omen 16 - Top Lid & Keyboard Deck Cut File - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:18', '3882b1bc-f194-48c5-83e2-e569ff967f77'),
('3993089a-9720-465b-a0f2-29e6f2cf2420', 'file-manager/products/sony-xperia-5-v-compact-vector-template/vector-dxf.jpg', 'Sony Xperia 5 V - Compact Vector Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', 'c6aedfb9-2526-44c9-ad32-a6d9da7b07fa'),
('39b0cc44-f332-4bf4-8dd6-6e2b4a5183aa', 'file-manager/products/mercedes-amg-interior-carbon-screen-trim-protection-file/cut-layout.png', 'Mercedes-AMG Interior Carbon & Screen Trim Protection File - Precision Vector Cutting Sheet', '2026-10-13 14:01:18', '3d69419b-2869-4b5f-995d-c15de97f166c'),
('3a2b8338-f608-401a-8da2-470ec5fccaa7', 'file-manager/products/porsche-911-gt3-rs-full-exterior-ppf-cut-file/blueprint.png', 'Porsche 911 GT3 RS - Full Exterior PPF Cut File - CAD Vector Cutline Blueprint', '2026-10-13 14:01:20', 'fb8efce9-3628-47b9-95fe-6156fbc7f714'),
('3b2410a4-f22d-4099-92aa-2afff50a83b5', 'file-manager/products/iphone-15-pro-full-body-frame-skin-cut-file/main.png', 'iPhone 15 Pro - Full Body & Frame Skin Cut File - Official Cut Template', '2026-10-13 14:01:18', '2d284119-c521-4043-9bd6-a061c504e32e'),
('3d4175e9-ced0-4dd3-8c92-fb019702d1a6', 'file-manager/products/ultra-realistic-3d-carbon-fiber-vector-pattern-pack/main.png', 'Ultra-Realistic 3D Carbon Fiber Vector Pattern Pack - Official Cut Template', '2026-10-13 14:01:20', 'd075c0b9-022f-4aee-a353-67c73243c5be');

INSERT INTO `product_images` (`product_image_id`, `product_image_path`, `product_image_alt`, `created_at`, `product_id`) VALUES
('3ddded56-bea9-4c5b-80f9-783e5d9856cb', 'file-manager/products/oppo-reno-11-pro-full-wrap-cut-file/camera-bump.png', 'Oppo Reno 11 Pro - Full Wrap Cut File - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', '75c78c81-3338-4819-8d89-f061c3be30f9'),
('3e127b6c-3a7d-4b0e-804d-0a9e914acb76', 'file-manager/products/samsung-galaxy-z-fold-5-dual-screen-wrap-template/full-wrap.png', 'Samsung Galaxy Z Fold 5 - Dual Screen Wrap Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', 'c002a74d-c37d-4c38-a53d-41de9ec5b94e'),
('3eab4170-ac1e-4c08-8666-4a59bb1d04c2', 'file-manager/products/cyberpunk-geometric-camo-full-vehicle-wrap-pattern/vector-dxf.jpg', 'Cyberpunk Geometric Camo Full Vehicle Wrap Pattern - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', 'c2b756f2-45b7-4d7a-b9ec-afdb1e38e21c'),
('3ead9dd4-81c5-4ecb-813b-f3309e54416f', 'file-manager/products/sony-xperia-5-v-compact-vector-template/main.png', 'Sony Xperia 5 V - Compact Vector Template - Official Cut Template', '2026-10-13 14:01:19', 'c6aedfb9-2526-44c9-ad32-a6d9da7b07fa'),
('3f604848-cf73-4d13-859a-290146aad3f4', 'file-manager/products/lenovo-legion-pro-7i-heavy-gaming-skin-template/real-lid.jpg', 'Lenovo Legion Pro 7i - Heavy Gaming Skin Template - Top Lid Wrap View', '2026-10-13 14:01:17', '029803d4-c800-4ed8-9558-d666db1a18d9'),
('44a06d53-08af-4f3b-a760-68c1156fd540', 'file-manager/products/universal-vehicle-door-cup-handle-scratch-protection-ppf-kit/main.png', 'Universal Vehicle Door Cup & Handle Scratch Protection PPF Kit - Official Cut Template', '2026-10-13 14:01:18', '4917076a-3c58-4847-b9b7-5b5c1589c020'),
('49047691-ad74-4797-ac38-e4475739694d', 'file-manager/products/asus-rog-zephyrus-g16-full-body-skin-template/edge-cutlines.png', 'Asus ROG Zephyrus G16 - Full Body Skin Template - Precision Edge Cutlines', '2026-10-13 14:01:18', '5894f9e3-06f6-4b34-ab1d-c2a9775d3865'),
('495ba1e8-4622-453a-80a0-b31cb2fd819b', 'file-manager/products/sony-xperia-1-vi-full-body-vinyl-skin-cut-file/camera-bump.png', 'Sony Xperia 1 VI - Full Body Vinyl Skin Cut File - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', 'a7c22105-4b7d-4275-909e-6c2e28bdcd08'),
('4a75e6bb-da0d-4f91-b8d7-7b64fd65de9c', 'file-manager/products/samsung-galaxy-z-fold-5-dual-screen-wrap-template/main.png', 'Samsung Galaxy Z Fold 5 - Dual Screen Wrap Template - Official Cut Template', '2026-10-13 14:01:19', 'c002a74d-c37d-4c38-a53d-41de9ec5b94e'),
('4aaecab8-639b-4563-8443-d6807c2a73c7', 'file-manager/products/lg-wing-dual-swivel-screen-wrap-template/full-wrap.png', 'LG Wing - Dual Swivel Screen Wrap Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:18', '4304eb5f-337d-4a77-bd04-8c87356399e7'),
('4aafe3d0-dc08-4cf5-a7c4-7957330e68d9', 'file-manager/products/ducati-panigale-v4-full-livery-armor-ppf-template/camera-bump.png', 'Ducati Panigale V4 - Full Livery & Armor PPF Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:20', 'fa62534a-617d-459c-b96a-a54526b97b4f'),
('4d47310a-6613-4a8e-8371-89fc99606313', 'file-manager/products/acer-predator-helios-16-vinyl-skin-cut-file/keyboard-deck.jpg', 'Acer Predator Helios 16 - Vinyl Skin Cut File - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:19', 'a886a9f6-a7b6-468e-9b57-0c613f10f51c'),
('4de66777-dc90-461d-950a-a75d343ef4a0', 'file-manager/products/liquid-bronze-brushed-titanium-textured-wrap-vector/full-wrap.png', 'Liquid Bronze & Brushed Titanium Textured Wrap Vector - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', 'a1060a2b-c1d8-4542-823a-fb8e2e19955d'),
('4e86b224-329e-4b09-bddd-b0200bb642aa', 'file-manager/products/universal-vehicle-door-cup-handle-scratch-protection-ppf-kit/matte-stealth.png', 'Universal Vehicle Door Cup & Handle Scratch Protection PPF Kit - Matte Stealth 3D Angle', '2026-10-13 14:01:18', '4917076a-3c58-4847-b9b7-5b5c1589c020'),
('50b522d3-eb6b-40b7-87f2-edb4ae39c31c', 'file-manager/products/kawasaki-ninja-zx-10r-custom-wrap-template-package/camera-bump.png', 'Kawasaki Ninja ZX-10R - Custom Wrap Template Package - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', '6744e1b3-6eae-431b-a911-94160230b2fe'),
('52f98011-c2ac-42ca-91a7-411588527c93', 'file-manager/products/oppo-reno-11-pro-full-wrap-cut-file/vector-dxf.jpg', 'Oppo Reno 11 Pro - Full Wrap Cut File - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', '75c78c81-3338-4819-8d89-f061c3be30f9'),
('53a34e07-6695-4bcc-afd8-da9867541f12', 'file-manager/products/thinkpad-x1-carbon-gen-12-precision-cut-file/main.png', 'ThinkPad X1 Carbon Gen 12 - Precision Cut File - Official Cut Template', '2026-10-13 14:01:19', '6c064a82-14ac-4f0d-a14c-fec268ab21d8'),
('54491c00-dbef-49aa-9480-93884a3493db', 'file-manager/products/lamborghini-aventador-paint-protection-film-cut-file/blueprint.png', 'Lamborghini Aventador - Paint Protection Film Cut File - CAD Vector Cutline Blueprint', '2026-10-13 14:01:17', '029bbbe3-9e85-4f33-bda0-1577f013a7d0'),
('546366b7-3650-4145-98e4-41939c74feab', 'file-manager/products/samsung-galaxy-s23-plus-precision-vinyl-cut-file/camera-bump.png', 'Samsung Galaxy S23 Plus - Precision Vinyl Cut File - Camera Bump & Side Rails Template', '2026-10-13 14:01:18', '0c834dc4-050f-465b-b04a-b88792e423ba'),
('54c56cfa-19bd-412f-b3f2-b453d1b5c437', 'file-manager/products/ultra-realistic-3d-carbon-fiber-vector-pattern-pack/camera-bump.png', 'Ultra-Realistic 3D Carbon Fiber Vector Pattern Pack - Camera Bump & Side Rails Template', '2026-10-13 14:01:20', 'd075c0b9-022f-4aee-a353-67c73243c5be'),
('553bb493-df7d-4c6d-acc6-5344bda8a33d', 'file-manager/products/dell-xps-13-plus-seamless-touch-bar-skin-file/main.png', 'Dell XPS 13 Plus - Seamless Touch Bar Skin File - Official Cut Template', '2026-10-13 14:01:18', '196a02c8-f868-4a8b-b5ed-937b0d123340'),
('554911ca-2df6-4450-9581-0462fbfe620b', 'file-manager/products/ferrari-f8-tributo-complete-body-ppf-template/cut-layout.png', 'Ferrari F8 Tributo - Complete Body PPF Template - Precision Vector Cutting Sheet', '2026-10-13 14:01:19', 'bc8cce3f-572c-4037-99dc-1d48ec2ac693'),
('55b447a4-3982-4c49-a265-eff27ba7e783', 'file-manager/products/tesla-model-3-y-center-screen-154-protector-template/blueprint.png', 'Tesla Model 3 / Y Center Screen 15.4\" Protector Template - CAD Vector Cutline Blueprint', '2026-10-13 14:01:17', '065689c5-ec35-454e-8532-046faed23a79'),
('576085ec-ce8a-40df-8149-048fd1577816', 'file-manager/products/iphone-15-pro-full-body-frame-skin-cut-file/camera-bump.png', 'iPhone 15 Pro - Full Body & Frame Skin Cut File - Camera Bump & Side Rails Template', '2026-10-13 14:01:18', '2d284119-c521-4043-9bd6-a061c504e32e'),
('5824f8e4-ecd7-4622-a220-3b73ecbdb8ac', 'file-manager/products/dell-xps-13-plus-seamless-touch-bar-skin-file/edge-cutlines.png', 'Dell XPS 13 Plus - Seamless Touch Bar Skin File - Precision Edge Cutlines', '2026-10-13 14:01:18', '196a02c8-f868-4a8b-b5ed-937b0d123340'),
('58c64e4d-f6b0-484e-bc0b-1de21d0f0f7f', 'file-manager/products/samsung-galaxy-z-fold-5-dual-screen-wrap-template/camera-bump.png', 'Samsung Galaxy Z Fold 5 - Dual Screen Wrap Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', 'c002a74d-c37d-4c38-a53d-41de9ec5b94e'),
('5ac7b425-513a-4db7-81d5-7fb8d3eab5bb', 'file-manager/products/iphone-14-pro-max-vector-cut-template/full-wrap.png', 'iPhone 14 Pro Max - Vector Cut Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', '8351e2b2-7c3a-472b-b59f-381540841a71'),
('5c78159c-b8d0-4fac-b36e-60e19e51e60a', 'file-manager/products/hp-spectre-x360-14-full-body-skin-template/real-lid.jpg', 'HP Spectre x360 14 - Full Body Skin Template - Top Lid Wrap View', '2026-10-13 14:01:20', 'eb690ab8-f9f7-43ed-8dcc-416216e5263a'),
('601ccae7-4bab-4057-9dc5-90204ac5b562', 'file-manager/products/alienware-x16-slim-gaming-laptop-wrap-file/edge-cutlines.png', 'Alienware x16 - Slim Gaming Laptop Wrap File - Precision Edge Cutlines', '2026-10-13 14:01:20', 'f5edba64-6acd-4ed1-957e-69de04ad2bf0'),
('61191688-d291-459b-bbc5-f92e13d3d0b5', 'file-manager/products/oppo-reno-11-pro-full-wrap-cut-file/main.png', 'Oppo Reno 11 Pro - Full Wrap Cut File - Official Cut Template', '2026-10-13 14:01:19', '75c78c81-3338-4819-8d89-f061c3be30f9'),
('637fbd4c-fa2f-4b62-b5ed-9b554e8ecc7a', 'file-manager/products/thinkpad-x1-carbon-gen-12-precision-cut-file/real-lid.jpg', 'ThinkPad X1 Carbon Gen 12 - Precision Cut File - Top Lid Wrap View', '2026-10-13 14:01:19', '6c064a82-14ac-4f0d-a14c-fec268ab21d8'),
('658e494c-f9b8-4ab9-b969-0e2af5dfb6a2', 'file-manager/products/google-pixel-9-pro-camera-visor-back-cut-file/camera-bump.png', 'Google Pixel 9 Pro - Camera Visor & Back Cut File - Camera Bump & Side Rails Template', '2026-10-13 14:01:18', '0b3f17f9-03a8-4bc5-a463-cb15f0412506'),
('6cc2f48b-d833-41a6-8420-4afc6f09fd49', 'file-manager/products/ducati-panigale-v4-full-livery-armor-ppf-template/full-wrap.png', 'Ducati Panigale V4 - Full Livery & Armor PPF Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:20', 'fa62534a-617d-459c-b96a-a54526b97b4f'),
('6e2a20f6-aacb-45b0-a783-ad0a394998ad', 'file-manager/products/asus-rog-zephyrus-g16-full-body-skin-template/keyboard-deck.jpg', 'Asus ROG Zephyrus G16 - Full Body Skin Template - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:18', '5894f9e3-06f6-4b34-ab1d-c2a9775d3865'),
('6f6c5600-ac46-4cfa-ac81-03df08302934', 'file-manager/products/samsung-galaxy-s24-ultra-full-coverage-skin-template/full-wrap.png', 'Samsung Galaxy S24 Ultra - Full Coverage Skin Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', 'a94af25d-a177-4264-acb5-30ec69b79f11'),
('70272550-d2b3-4101-b3e2-d6f62e96f522', 'file-manager/products/yamaha-yzf-r1-full-racing-fairing-wrap-vector-file/vector-dxf.jpg', 'Yamaha YZF-R1 - Full Racing Fairing Wrap Vector File - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:20', 'cc9cdf64-ce50-4523-8173-6a2ae10f67ac'),
('707ab914-6109-4753-8cdb-c3ca12fa8573', 'file-manager/products/yamaha-yzf-r1-full-racing-fairing-wrap-vector-file/full-wrap.png', 'Yamaha YZF-R1 - Full Racing Fairing Wrap Vector File - 360 Full Coverage Blueprint', '2026-10-13 14:01:20', 'cc9cdf64-ce50-4523-8173-6a2ae10f67ac'),
('72805a71-ec2a-4fab-afae-c40d5598f510', 'file-manager/products/liquid-bronze-brushed-titanium-textured-wrap-vector/vector-dxf.jpg', 'Liquid Bronze & Brushed Titanium Textured Wrap Vector - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', 'a1060a2b-c1d8-4542-823a-fb8e2e19955d'),
('763b4620-c900-47fe-98f2-776f98796e67', 'file-manager/products/tesla-model-3-y-center-screen-154-protector-template/main.png', 'Tesla Model 3 / Y Center Screen 15.4\" Protector Template - Official Cut Template', '2026-10-13 14:01:17', '065689c5-ec35-454e-8532-046faed23a79'),
('7722e5ed-d339-4fd1-abc9-1310c89d5113', 'file-manager/products/iphone-14-pro-max-vector-cut-template/vector-dxf.jpg', 'iPhone 14 Pro Max - Vector Cut Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', '8351e2b2-7c3a-472b-b59f-381540841a71'),
('78de5dc9-8913-49bb-a826-34f9b1181a2b', 'file-manager/products/hp-spectre-x360-14-full-body-skin-template/main.png', 'HP Spectre x360 14 - Full Body Skin Template - Official Cut Template', '2026-10-13 14:01:20', 'eb690ab8-f9f7-43ed-8dcc-416216e5263a'),
('7a946ac3-c507-4b04-b2fc-c5b54c3d2bac', 'file-manager/products/ducati-panigale-v4-full-livery-armor-ppf-template/vector-dxf.jpg', 'Ducati Panigale V4 - Full Livery & Armor PPF Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:20', 'fa62534a-617d-459c-b96a-a54526b97b4f'),
('7ca31cd7-9cff-45dd-b152-bd34c62e54ed', 'file-manager/products/tesla-model-3-y-center-screen-154-protector-template/cut-layout.png', 'Tesla Model 3 / Y Center Screen 15.4\" Protector Template - Precision Vector Cutting Sheet', '2026-10-13 14:01:17', '065689c5-ec35-454e-8532-046faed23a79'),
('825abeb9-db7c-4706-89c2-a468669fea9a', 'file-manager/products/yamaha-yzf-r1-full-racing-fairing-wrap-vector-file/camera-bump.png', 'Yamaha YZF-R1 - Full Racing Fairing Wrap Vector File - Camera Bump & Side Rails Template', '2026-10-13 14:01:20', 'cc9cdf64-ce50-4523-8173-6a2ae10f67ac'),
('84dedef0-9553-41e2-8ad4-3b46c862b5f7', 'file-manager/products/mercedes-amg-gt-r-full-body-wrap-ppf-template/main.jpg', 'Mercedes-AMG GT R - Full Body Wrap & PPF Template - Official Cut Template', '2026-10-13 14:01:18', '4725d9ae-8dfd-48e0-9ccb-30b44646f8ad'),
('8519141b-0427-4128-93e4-e02c17d9bf58', 'file-manager/products/samsung-galaxy-z-fold-5-dual-screen-wrap-template/vector-dxf.jpg', 'Samsung Galaxy Z Fold 5 - Dual Screen Wrap Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', 'c002a74d-c37d-4c38-a53d-41de9ec5b94e'),
('86bdc0e2-2037-4bd5-aaa0-73516a3e085b', 'file-manager/products/porsche-911-gt3-rs-full-exterior-ppf-cut-file/main.jpg', 'Porsche 911 GT3 RS - Full Exterior PPF Cut File - Official Cut Template', '2026-10-13 14:01:20', 'fb8efce9-3628-47b9-95fe-6156fbc7f714'),
('8bddbe86-d0d7-4509-a690-ba614a0b66bf', 'file-manager/products/hp-omen-16-top-lid-keyboard-deck-cut-file/edge-cutlines.png', 'HP Omen 16 - Top Lid & Keyboard Deck Cut File - Precision Edge Cutlines', '2026-10-13 14:01:18', '3882b1bc-f194-48c5-83e2-e569ff967f77'),
('8cd5dde2-5966-4218-b117-3b8e9c76a9f9', 'file-manager/products/ferrari-f8-tributo-complete-body-ppf-template/main.jpg', 'Ferrari F8 Tributo - Complete Body PPF Template - Official Cut Template', '2026-10-13 14:01:19', 'bc8cce3f-572c-4037-99dc-1d48ec2ac693'),
('8e203441-17df-4ef7-a5b6-56fbfabda191', 'file-manager/products/sony-xperia-1-vi-full-body-vinyl-skin-cut-file/full-wrap.png', 'Sony Xperia 1 VI - Full Body Vinyl Skin Cut File - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', 'a7c22105-4b7d-4275-909e-6c2e28bdcd08');

INSERT INTO `product_images` (`product_image_id`, `product_image_path`, `product_image_alt`, `created_at`, `product_id`) VALUES
('8fc29f76-5d99-49f6-8102-7e7b87de08d1', 'file-manager/products/lamborghini-aventador-paint-protection-film-cut-file/cut-layout.png', 'Lamborghini Aventador - Paint Protection Film Cut File - Precision Vector Cutting Sheet', '2026-10-13 14:01:17', '029bbbe3-9e85-4f33-bda0-1577f013a7d0'),
('9148a4fb-c2a0-4144-a96c-143047de7e81', 'file-manager/products/mercedes-amg-interior-carbon-screen-trim-protection-file/main.png', 'Mercedes-AMG Interior Carbon & Screen Trim Protection File - Official Cut Template', '2026-10-13 14:01:18', '3d69419b-2869-4b5f-995d-c15de97f166c'),
('91dfad65-52cf-4fd4-89ac-2165c5b1f1b8', 'file-manager/products/kawasaki-ninja-zx-10r-custom-wrap-template-package/full-wrap.png', 'Kawasaki Ninja ZX-10R - Custom Wrap Template Package - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', '6744e1b3-6eae-431b-a911-94160230b2fe'),
('984119c5-8075-4986-ab33-d469e78e6818', 'file-manager/products/oppo-find-x7-ultra-quad-camera-skin-template/full-wrap.png', 'Oppo Find X7 Ultra - Quad Camera Skin Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', 'bc6df49c-c7f8-4f7f-9ab3-af2b6c93dcb8'),
('9a926e1c-32ac-41e5-8085-9b49587cb984', 'file-manager/products/oppo-find-x7-ultra-quad-camera-skin-template/main.png', 'Oppo Find X7 Ultra - Quad Camera Skin Template - Official Cut Template', '2026-10-13 14:01:19', 'bc6df49c-c7f8-4f7f-9ab3-af2b6c93dcb8'),
('9aaa8f22-9e50-43b6-bf18-9f575b94c470', 'file-manager/products/asus-zenbook-14-oled-geometric-lid-wrap-template/main.png', 'Asus Zenbook 14 OLED - Geometric Lid Wrap Template - Official Cut Template', '2026-10-13 14:01:18', '5bd89968-ec2a-4515-b5ce-73e773971c2b'),
('9f9b4b57-55ca-4b2c-b69e-3091b1ed1158', 'file-manager/products/alienware-m18-r2-top-lid-palmrest-skin-template/real-lid.jpg', 'Alienware m18 R2 - Top Lid & Palmrest Skin Template - Top Lid Wrap View', '2026-10-13 14:01:18', '243cab68-27bf-49ae-95eb-f4ad37e38566'),
('a25a03ff-afad-4b40-9fbd-17942b92d391', 'file-manager/products/google-pixel-8-pro-full-body-vinyl-skin-template/camera-bump.png', 'Google Pixel 8 Pro - Full Body Vinyl Skin Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', 'a6e443e2-fdb4-423b-bed8-9eb50737a7f5'),
('a2fb69a7-47ba-4326-8101-e566778e91a3', 'file-manager/products/sony-xperia-1-vi-full-body-vinyl-skin-cut-file/main.png', 'Sony Xperia 1 VI - Full Body Vinyl Skin Cut File - Official Cut Template', '2026-10-13 14:01:19', 'a7c22105-4b7d-4275-909e-6c2e28bdcd08'),
('a511db1f-0cfc-4d73-b7d4-d2158fbefd20', 'file-manager/products/mercedes-amg-interior-carbon-screen-trim-protection-file/blueprint.png', 'Mercedes-AMG Interior Carbon & Screen Trim Protection File - CAD Vector Cutline Blueprint', '2026-10-13 14:01:18', '3d69419b-2869-4b5f-995d-c15de97f166c'),
('a54300ce-8332-40e6-8030-a4cdc174fcbf', 'file-manager/products/samsung-galaxy-s23-plus-precision-vinyl-cut-file/full-wrap.png', 'Samsung Galaxy S23 Plus - Precision Vinyl Cut File - 360 Full Coverage Blueprint', '2026-10-13 14:01:18', '0c834dc4-050f-465b-b04a-b88792e423ba'),
('a6034815-d9e4-4110-bdea-a6e77bb03b35', 'file-manager/products/hp-spectre-x360-14-full-body-skin-template/keyboard-deck.jpg', 'HP Spectre x360 14 - Full Body Skin Template - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:20', 'eb690ab8-f9f7-43ed-8dcc-416216e5263a'),
('a7461b06-8daa-4a24-9f85-ae04cd61a666', 'file-manager/products/porsche-911-992-center-console-dashboard-ppf-template/matte-stealth.png', 'Porsche 911 (992) Center Console & Dashboard PPF Template - Matte Stealth 3D Angle', '2026-10-13 14:01:20', 'd6032815-0be5-47b1-9593-f049f03ab19f'),
('ac59e1ba-c043-4dd1-9040-cb5aa070fce0', 'file-manager/products/acer-swift-go-14-slim-ultrabook-skin-template/real-lid.jpg', 'Acer Swift Go 14 - Slim Ultrabook Skin Template - Top Lid Wrap View', '2026-10-13 14:01:20', 'dac6412f-2644-4f07-b985-09ee8ec7b8b5'),
('ac85ac5b-3ffc-4fc8-9ab1-f2eaf59f60b7', 'file-manager/products/yamaha-yzf-r1-full-racing-fairing-wrap-vector-file/main.png', 'Yamaha YZF-R1 - Full Racing Fairing Wrap Vector File - Official Cut Template', '2026-10-13 14:01:19', 'cc9cdf64-ce50-4523-8173-6a2ae10f67ac'),
('aed69a5c-f23d-4f7a-bccc-7dcf2df0d9a1', 'file-manager/products/ferrari-f8-tributo-complete-body-ppf-template/blueprint.png', 'Ferrari F8 Tributo - Complete Body PPF Template - CAD Vector Cutline Blueprint', '2026-10-13 14:01:19', 'bc8cce3f-572c-4037-99dc-1d48ec2ac693'),
('b16dccc4-62c2-42f1-95eb-4656f73571f0', 'file-manager/products/alienware-m18-r2-top-lid-palmrest-skin-template/edge-cutlines.png', 'Alienware m18 R2 - Top Lid & Palmrest Skin Template - Precision Edge Cutlines', '2026-10-13 14:01:18', '243cab68-27bf-49ae-95eb-f4ad37e38566'),
('b238c15a-3849-4b8d-ac8a-3d4bb52bf6a2', 'file-manager/products/cyberpunk-geometric-camo-full-vehicle-wrap-pattern/camera-bump.png', 'Cyberpunk Geometric Camo Full Vehicle Wrap Pattern - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', 'c2b756f2-45b7-4d7a-b9ec-afdb1e38e21c'),
('ba5e8771-26cc-42ff-a00a-febb3faf56d1', 'file-manager/products/lamborghini-aventador-paint-protection-film-cut-file/main.jpg', 'Lamborghini Aventador - Paint Protection Film Cut File - Official Cut Template', '2026-10-13 14:01:17', '029bbbe3-9e85-4f33-bda0-1577f013a7d0'),
('ba800c48-daf0-420e-a8ed-c1c034e530d2', 'file-manager/products/porsche-911-992-center-console-dashboard-ppf-template/main.png', 'Porsche 911 (992) Center Console & Dashboard PPF Template - Official Cut Template', '2026-10-13 14:01:20', 'd6032815-0be5-47b1-9593-f049f03ab19f'),
('bd100b74-06f5-4248-acfe-7f79f81b6d06', 'file-manager/products/sony-xperia-5-v-compact-vector-template/camera-bump.png', 'Sony Xperia 5 V - Compact Vector Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', 'c6aedfb9-2526-44c9-ad32-a6d9da7b07fa'),
('be02c0b1-f517-4824-ab01-684cd830acbe', 'file-manager/products/acer-swift-go-14-slim-ultrabook-skin-template/keyboard-deck.jpg', 'Acer Swift Go 14 - Slim Ultrabook Skin Template - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:20', 'dac6412f-2644-4f07-b985-09ee8ec7b8b5'),
('c07f7db3-b406-4aca-b2ee-fb763fe685c7', 'file-manager/products/thinkpad-x1-carbon-gen-12-precision-cut-file/keyboard-deck.jpg', 'ThinkPad X1 Carbon Gen 12 - Precision Cut File - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:19', '6c064a82-14ac-4f0d-a14c-fec268ab21d8'),
('c18f426a-b2c1-4ec3-9b60-59a213d47823', 'file-manager/products/cyberpunk-geometric-camo-full-vehicle-wrap-pattern/full-wrap.png', 'Cyberpunk Geometric Camo Full Vehicle Wrap Pattern - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', 'c2b756f2-45b7-4d7a-b9ec-afdb1e38e21c'),
('c1fc7c62-fe24-4f45-b7ab-526042df0533', 'file-manager/products/hp-omen-16-top-lid-keyboard-deck-cut-file/main.png', 'HP Omen 16 - Top Lid & Keyboard Deck Cut File - Official Cut Template', '2026-10-13 14:01:18', '3882b1bc-f194-48c5-83e2-e569ff967f77'),
('c2718f0e-601d-4e91-8265-10cff9d0d812', 'file-manager/products/dell-xps-13-plus-seamless-touch-bar-skin-file/real-lid.jpg', 'Dell XPS 13 Plus - Seamless Touch Bar Skin File - Top Lid Wrap View', '2026-10-13 14:01:18', '196a02c8-f868-4a8b-b5ed-937b0d123340'),
('c77e4377-beaf-4ab4-89a1-01fe53212397', 'file-manager/products/lenovo-legion-pro-7i-heavy-gaming-skin-template/keyboard-deck.jpg', 'Lenovo Legion Pro 7i - Heavy Gaming Skin Template - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:17', '029803d4-c800-4ed8-9558-d666db1a18d9'),
('c7f5687f-aae2-4699-ab07-285cda877b33', 'file-manager/products/oppo-find-x7-ultra-quad-camera-skin-template/vector-dxf.jpg', 'Oppo Find X7 Ultra - Quad Camera Skin Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', 'bc6df49c-c7f8-4f7f-9ab3-af2b6c93dcb8'),
('c8994b5e-49ff-43c5-b924-fd006e793580', 'file-manager/products/ultra-realistic-3d-carbon-fiber-vector-pattern-pack/full-wrap.png', 'Ultra-Realistic 3D Carbon Fiber Vector Pattern Pack - 360 Full Coverage Blueprint', '2026-10-13 14:01:20', 'd075c0b9-022f-4aee-a353-67c73243c5be'),
('cabb4093-3a63-4ea0-b50a-99ad84c81bce', 'file-manager/products/alienware-m18-r2-top-lid-palmrest-skin-template/keyboard-deck.jpg', 'Alienware m18 R2 - Top Lid & Palmrest Skin Template - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:18', '243cab68-27bf-49ae-95eb-f4ad37e38566'),
('cc5e2602-a7c4-4b4c-88fa-0e485ba9db54', 'file-manager/products/liquid-bronze-brushed-titanium-textured-wrap-vector/camera-bump.png', 'Liquid Bronze & Brushed Titanium Textured Wrap Vector - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', 'a1060a2b-c1d8-4542-823a-fb8e2e19955d'),
('cf78bb59-869f-44b0-a622-e3bcbd7be11d', 'file-manager/products/porsche-911-992-center-console-dashboard-ppf-template/cut-layout.png', 'Porsche 911 (992) Center Console & Dashboard PPF Template - Precision Vector Cutting Sheet', '2026-10-13 14:01:20', 'd6032815-0be5-47b1-9593-f049f03ab19f'),
('d009c39c-c8dc-487c-9ab3-eb9b4e791565', 'file-manager/products/asus-zenbook-14-oled-geometric-lid-wrap-template/edge-cutlines.png', 'Asus Zenbook 14 OLED - Geometric Lid Wrap Template - Precision Edge Cutlines', '2026-10-13 14:01:19', '5bd89968-ec2a-4515-b5ce-73e773971c2b'),
('d0fe8a64-3f1b-47d6-b827-7d115743c28d', 'file-manager/products/acer-swift-go-14-slim-ultrabook-skin-template/edge-cutlines.png', 'Acer Swift Go 14 - Slim Ultrabook Skin Template - Precision Edge Cutlines', '2026-10-13 14:01:20', 'dac6412f-2644-4f07-b985-09ee8ec7b8b5'),
('d143c447-483b-4ed3-bd20-1c8938a1df2b', 'file-manager/products/lg-velvet-5g-raindrop-camera-skin-template/main.png', 'LG Velvet 5G - Raindrop Camera Skin Template - Official Cut Template', '2026-10-13 14:01:18', '385dbfe3-a662-4c25-a813-14fee38baed0'),
('d3548171-d6aa-4eee-91c7-864f4c69e83e', 'file-manager/products/thinkpad-x1-carbon-gen-12-precision-cut-file/edge-cutlines.png', 'ThinkPad X1 Carbon Gen 12 - Precision Cut File - Precision Edge Cutlines', '2026-10-13 14:01:19', '6c064a82-14ac-4f0d-a14c-fec268ab21d8'),
('d393d264-53c9-4c7f-aaff-3395d662fa57', 'file-manager/products/lenovo-legion-pro-7i-heavy-gaming-skin-template/edge-cutlines.png', 'Lenovo Legion Pro 7i - Heavy Gaming Skin Template - Precision Edge Cutlines', '2026-10-13 14:01:17', '029803d4-c800-4ed8-9558-d666db1a18d9'),
('d3ad6b1b-9c6f-41bb-92b2-4bb260d95a19', 'file-manager/products/ferrari-f8-tributo-complete-body-ppf-template/matte-stealth.png', 'Ferrari F8 Tributo - Complete Body PPF Template - Matte Stealth 3D Angle', '2026-10-13 14:01:19', 'bc8cce3f-572c-4037-99dc-1d48ec2ac693'),
('d9c66e32-4763-4a8d-b626-879f0e44c48c', 'file-manager/products/liquid-bronze-brushed-titanium-textured-wrap-vector/main.png', 'Liquid Bronze & Brushed Titanium Textured Wrap Vector - Official Cut Template', '2026-10-13 14:01:19', 'a1060a2b-c1d8-4542-823a-fb8e2e19955d'),
('da82dc87-8031-434d-9af9-3d49e5ce8a79', 'file-manager/products/alienware-x16-slim-gaming-laptop-wrap-file/main.png', 'Alienware x16 - Slim Gaming Laptop Wrap File - Official Cut Template', '2026-10-13 14:01:20', 'f5edba64-6acd-4ed1-957e-69de04ad2bf0'),
('da8e6190-a9a9-4a95-978d-56fc0aeff516', 'file-manager/products/cyberpunk-geometric-camo-full-vehicle-wrap-pattern/main.png', 'Cyberpunk Geometric Camo Full Vehicle Wrap Pattern - Official Cut Template', '2026-10-13 14:01:19', 'c2b756f2-45b7-4d7a-b9ec-afdb1e38e21c'),
('db126ef9-71a1-4ad7-b3bf-b1ae04c984fd', 'file-manager/products/samsung-galaxy-s23-plus-precision-vinyl-cut-file/main.png', 'Samsung Galaxy S23 Plus - Precision Vinyl Cut File - Official Cut Template', '2026-10-13 14:01:18', '0c834dc4-050f-465b-b04a-b88792e423ba'),
('ddd4c2ae-263f-4f56-86d0-dd7dd0c39d64', 'file-manager/products/samsung-galaxy-s24-ultra-full-coverage-skin-template/main.png', 'Samsung Galaxy S24 Ultra - Full Coverage Skin Template - Official Cut Template', '2026-10-13 14:01:19', 'a94af25d-a177-4264-acb5-30ec69b79f11'),
('de1d9c1d-b630-4098-9e6f-7a2df8ecdd96', 'file-manager/products/lg-velvet-5g-raindrop-camera-skin-template/full-wrap.png', 'LG Velvet 5G - Raindrop Camera Skin Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:18', '385dbfe3-a662-4c25-a813-14fee38baed0'),
('dea81fab-94d7-4239-bacd-61df1307c828', 'file-manager/products/sony-xperia-5-v-compact-vector-template/full-wrap.png', 'Sony Xperia 5 V - Compact Vector Template - 360 Full Coverage Blueprint', '2026-10-13 14:01:19', 'c6aedfb9-2526-44c9-ad32-a6d9da7b07fa'),
('df00f84e-6247-4409-9eec-11d595541b1a', 'file-manager/products/mercedes-hyperscreen-triple-display-ultra-clear-protection-cut-file/cut-layout.png', 'Mercedes Hyperscreen Triple Display Ultra-Clear Protection Cut File - Precision Vector Cutting Sheet', '2026-10-13 14:01:19', '70c3510d-fb47-4403-adc9-8e4949bc7da0'),
('df3a3d1b-dc03-44fe-9242-ed5f4e227c75', 'file-manager/products/sony-xperia-1-vi-full-body-vinyl-skin-cut-file/vector-dxf.jpg', 'Sony Xperia 1 VI - Full Body Vinyl Skin Cut File - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', 'a7c22105-4b7d-4275-909e-6c2e28bdcd08'),
('e0f06ffa-0f9e-4f42-86c4-4c9e031c119b', 'file-manager/products/asus-rog-zephyrus-g16-full-body-skin-template/main.png', 'Asus ROG Zephyrus G16 - Full Body Skin Template - Official Cut Template', '2026-10-13 14:01:18', '5894f9e3-06f6-4b34-ab1d-c2a9775d3865'),
('e2eaf19d-cd58-40fb-b4e4-922201d835be', 'file-manager/products/universal-vehicle-door-cup-handle-scratch-protection-ppf-kit/cut-layout.png', 'Universal Vehicle Door Cup & Handle Scratch Protection PPF Kit - Precision Vector Cutting Sheet', '2026-10-13 14:01:18', '4917076a-3c58-4847-b9b7-5b5c1589c020'),
('e2f95c2f-7998-46c7-aff2-23a480927353', 'file-manager/products/lamborghini-aventador-paint-protection-film-cut-file/matte-stealth.png', 'Lamborghini Aventador - Paint Protection Film Cut File - Matte Stealth 3D Angle', '2026-10-13 14:01:17', '029bbbe3-9e85-4f33-bda0-1577f013a7d0');

INSERT INTO `product_images` (`product_image_id`, `product_image_path`, `product_image_alt`, `created_at`, `product_id`) VALUES
('e9c1819c-3a08-4658-baeb-aaab32c139de', 'file-manager/products/ultra-realistic-3d-carbon-fiber-vector-pattern-pack/vector-dxf.jpg', 'Ultra-Realistic 3D Carbon Fiber Vector Pattern Pack - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:20', 'd075c0b9-022f-4aee-a353-67c73243c5be'),
('eaec7dcb-2ad2-4b07-93e5-1799b22ca973', 'file-manager/products/google-pixel-9-pro-camera-visor-back-cut-file/main.png', 'Google Pixel 9 Pro - Camera Visor & Back Cut File - Official Cut Template', '2026-10-13 14:01:18', '0b3f17f9-03a8-4bc5-a463-cb15f0412506'),
('ebbd3c8d-35f9-4bd1-92f0-c70211cf6b61', 'file-manager/products/kawasaki-ninja-zx-10r-custom-wrap-template-package/main.png', 'Kawasaki Ninja ZX-10R - Custom Wrap Template Package - Official Cut Template', '2026-10-13 14:01:19', '6744e1b3-6eae-431b-a911-94160230b2fe'),
('ed32373e-126b-4e34-b3fa-81a21a226f4c', 'file-manager/products/lg-wing-dual-swivel-screen-wrap-template/vector-dxf.jpg', 'LG Wing - Dual Swivel Screen Wrap Template - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:18', '4304eb5f-337d-4a77-bd04-8c87356399e7'),
('edf243b3-98c6-4319-a677-9b80d8bbd73e', 'file-manager/products/iphone-15-pro-full-body-frame-skin-cut-file/full-wrap.png', 'iPhone 15 Pro - Full Body & Frame Skin Cut File - 360 Full Coverage Blueprint', '2026-10-13 14:01:18', '2d284119-c521-4043-9bd6-a061c504e32e'),
('f46c2bce-8a2c-4deb-9efb-c146c60b4944', 'file-manager/products/kawasaki-ninja-zx-10r-custom-wrap-template-package/vector-dxf.jpg', 'Kawasaki Ninja ZX-10R - Custom Wrap Template Package - Plotter Ready DXF Vector Layout', '2026-10-13 14:01:19', '6744e1b3-6eae-431b-a911-94160230b2fe'),
('f4b270b6-3735-41cf-9e83-ee8be6253aef', 'file-manager/products/lg-velvet-5g-raindrop-camera-skin-template/camera-bump.png', 'LG Velvet 5G - Raindrop Camera Skin Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:18', '385dbfe3-a662-4c25-a813-14fee38baed0'),
('f77a4880-0141-4dbd-be7d-4c00b1ee768a', 'file-manager/products/samsung-galaxy-s24-ultra-full-coverage-skin-template/camera-bump.png', 'Samsung Galaxy S24 Ultra - Full Coverage Skin Template - Camera Bump & Side Rails Template', '2026-10-13 14:01:19', 'a94af25d-a177-4264-acb5-30ec69b79f11'),
('f7bb669d-4e6a-4096-83c2-7314b41dff7c', 'file-manager/products/universal-vehicle-door-cup-handle-scratch-protection-ppf-kit/blueprint.png', 'Universal Vehicle Door Cup & Handle Scratch Protection PPF Kit - CAD Vector Cutline Blueprint', '2026-10-13 14:01:18', '4917076a-3c58-4847-b9b7-5b5c1589c020'),
('fdf9da04-12e0-42b2-a731-b3aeec823a0c', 'file-manager/products/alienware-x16-slim-gaming-laptop-wrap-file/keyboard-deck.jpg', 'Alienware x16 - Slim Gaming Laptop Wrap File - Keyboard Deck & Palmrest Template', '2026-10-13 14:01:20', 'f5edba64-6acd-4ed1-957e-69de04ad2bf0');

-- --------------------------------------------------------
--
-- Dumping data for table `products_categories` (86 rows)
--

INSERT INTO `products_categories` (`product_id`, `category_id`) VALUES
('243cab68-27bf-49ae-95eb-f4ad37e38566', '02fb26b2-6d24-11ef-8e68-088fc3163dd3'),
('f5edba64-6acd-4ed1-957e-69de04ad2bf0', '02fb26b2-6d24-11ef-8e68-088fc3163dd3'),
('3882b1bc-f194-48c5-83e2-e569ff967f77', '02fb41dd-6d24-11ef-8e68-088fc3163dd3'),
('eb690ab8-f9f7-43ed-8dcc-416216e5263a', '02fb41dd-6d24-11ef-8e68-088fc3163dd3'),
('a886a9f6-a7b6-468e-9b57-0c613f10f51c', '02fb716f-6d24-11ef-8e68-088fc3163dd3'),
('dac6412f-2644-4f07-b985-09ee8ec7b8b5', '02fb716f-6d24-11ef-8e68-088fc3163dd3'),
('5894f9e3-06f6-4b34-ab1d-c2a9775d3865', '02fb997f-6d24-11ef-8e68-088fc3163dd3'),
('5bd89968-ec2a-4515-b5ce-73e773971c2b', '02fb997f-6d24-11ef-8e68-088fc3163dd3'),
('196a02c8-f868-4a8b-b5ed-937b0d123340', '02fbb836-6d24-11ef-8e68-088fc3163dd3'),
('a81a4f71-8b7e-49be-9175-9fe947b9d514', '02fbb836-6d24-11ef-8e68-088fc3163dd3'),
('029803d4-c800-4ed8-9558-d666db1a18d9', '02fbcfc7-6d24-11ef-8e68-088fc3163dd3'),
('6c064a82-14ac-4f0d-a14c-fec268ab21d8', '02fbcfc7-6d24-11ef-8e68-088fc3163dd3'),
('0b3f17f9-03a8-4bc5-a463-cb15f0412506', '0c09750e-6d20-11ef-8e68-088fc3163dd3'),
('a6e443e2-fdb4-423b-bed8-9eb50737a7f5', '0c09750e-6d20-11ef-8e68-088fc3163dd3'),
('75c78c81-3338-4819-8d89-f061c3be30f9', '0c09a653-6d20-11ef-8e68-088fc3163dd3'),
('bc6df49c-c7f8-4f7f-9ab3-af2b6c93dcb8', '0c09a653-6d20-11ef-8e68-088fc3163dd3'),
('065689c5-ec35-454e-8532-046faed23a79', '2673e0af-d369-48bd-91ba-fdefb2e87f24'),
('6744e1b3-6eae-431b-a911-94160230b2fe', '33297e81-6d21-11ef-8e68-088fc3163dd3'),
('cc9cdf64-ce50-4523-8173-6a2ae10f67ac', '33297e81-6d21-11ef-8e68-088fc3163dd3'),
('fa62534a-617d-459c-b96a-a54526b97b4f', '33297e81-6d21-11ef-8e68-088fc3163dd3'),
('029bbbe3-9e85-4f33-bda0-1577f013a7d0', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('065689c5-ec35-454e-8532-046faed23a79', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('3d69419b-2869-4b5f-995d-c15de97f166c', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('4725d9ae-8dfd-48e0-9ccb-30b44646f8ad', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('4917076a-3c58-4847-b9b7-5b5c1589c020', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('70c3510d-fb47-4403-adc9-8e4949bc7da0', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('bc8cce3f-572c-4037-99dc-1d48ec2ac693', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('d6032815-0be5-47b1-9593-f049f03ab19f', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('fb8efce9-3628-47b9-95fe-6156fbc7f714', '3329ad6d-6d21-11ef-8e68-088fc3163dd3'),
('a7c22105-4b7d-4275-909e-6c2e28bdcd08', '379ced26-6d20-11ef-8e68-088fc3163dd3'),
('c6aedfb9-2526-44c9-ad32-a6d9da7b07fa', '379ced26-6d20-11ef-8e68-088fc3163dd3'),
('0c834dc4-050f-465b-b04a-b88792e423ba', '379d1e12-6d20-11ef-8e68-088fc3163dd3'),
('a94af25d-a177-4264-acb5-30ec69b79f11', '379d1e12-6d20-11ef-8e68-088fc3163dd3'),
('c002a74d-c37d-4c38-a53d-41de9ec5b94e', '379d1e12-6d20-11ef-8e68-088fc3163dd3'),
('d6032815-0be5-47b1-9593-f049f03ab19f', '4157d742-265e-4d26-94dd-2483b297af58'),
('029803d4-c800-4ed8-9558-d666db1a18d9', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('196a02c8-f868-4a8b-b5ed-937b0d123340', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('243cab68-27bf-49ae-95eb-f4ad37e38566', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('3882b1bc-f194-48c5-83e2-e569ff967f77', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('5894f9e3-06f6-4b34-ab1d-c2a9775d3865', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('5bd89968-ec2a-4515-b5ce-73e773971c2b', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('6c064a82-14ac-4f0d-a14c-fec268ab21d8', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('a81a4f71-8b7e-49be-9175-9fe947b9d514', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('a886a9f6-a7b6-468e-9b57-0c613f10f51c', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('dac6412f-2644-4f07-b985-09ee8ec7b8b5', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('eb690ab8-f9f7-43ed-8dcc-416216e5263a', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('f5edba64-6acd-4ed1-957e-69de04ad2bf0', '4a715358-6d22-11ef-8e68-088fc3163dd3'),
('bc8cce3f-572c-4037-99dc-1d48ec2ac693', '4c530e35-7ddd-48e9-95df-fcbf787e044d'),
('fb8efce9-3628-47b9-95fe-6156fbc7f714', '550854e1-7172-4834-a904-1b039e21921b'),
('2d284119-c521-4043-9bd6-a061c504e32e', '67069018-6d20-11ef-8e68-088fc3163dd3');

INSERT INTO `products_categories` (`product_id`, `category_id`) VALUES
('8351e2b2-7c3a-472b-b59f-381540841a71', '67069018-6d20-11ef-8e68-088fc3163dd3'),
('385dbfe3-a662-4c25-a813-14fee38baed0', '6706ae29-6d20-11ef-8e68-088fc3163dd3'),
('4304eb5f-337d-4a77-bd04-8c87356399e7', '6706ae29-6d20-11ef-8e68-088fc3163dd3'),
('029bbbe3-9e85-4f33-bda0-1577f013a7d0', '8485cce2-f5f6-4633-a3df-dc2f0050cf8f'),
('a1060a2b-c1d8-4542-823a-fb8e2e19955d', '97cd453b-6d1f-11ef-8e68-088fc3163dd3'),
('c2b756f2-45b7-4d7a-b9ec-afdb1e38e21c', '97cd453b-6d1f-11ef-8e68-088fc3163dd3'),
('d075c0b9-022f-4aee-a353-67c73243c5be', '97cd453b-6d1f-11ef-8e68-088fc3163dd3'),
('3d69419b-2869-4b5f-995d-c15de97f166c', '994491c6-6d21-11ef-8e68-088fc3163dd3'),
('d6032815-0be5-47b1-9593-f049f03ab19f', '994491c6-6d21-11ef-8e68-088fc3163dd3'),
('029bbbe3-9e85-4f33-bda0-1577f013a7d0', '9944c101-6d21-11ef-8e68-088fc3163dd3'),
('4725d9ae-8dfd-48e0-9ccb-30b44646f8ad', '9944c101-6d21-11ef-8e68-088fc3163dd3'),
('bc8cce3f-572c-4037-99dc-1d48ec2ac693', '9944c101-6d21-11ef-8e68-088fc3163dd3'),
('fb8efce9-3628-47b9-95fe-6156fbc7f714', '9944c101-6d21-11ef-8e68-088fc3163dd3'),
('3d69419b-2869-4b5f-995d-c15de97f166c', '9e592462-65ac-43c7-8659-0252b6f3d9fc'),
('4725d9ae-8dfd-48e0-9ccb-30b44646f8ad', 'a35c5118-919c-47fd-99a1-882e0c5eb0ab'),
('70c3510d-fb47-4403-adc9-8e4949bc7da0', 'a9d86144-3fbd-4f4f-9319-7a9aa66e3f22'),
('6744e1b3-6eae-431b-a911-94160230b2fe', 'bb733d2e-6d20-11ef-8e68-088fc3163dd3'),
('cc9cdf64-ce50-4523-8173-6a2ae10f67ac', 'bb733d2e-6d20-11ef-8e68-088fc3163dd3'),
('fa62534a-617d-459c-b96a-a54526b97b4f', 'bb733d2e-6d20-11ef-8e68-088fc3163dd3'),
('0b3f17f9-03a8-4bc5-a463-cb15f0412506', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('0c834dc4-050f-465b-b04a-b88792e423ba', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('2d284119-c521-4043-9bd6-a061c504e32e', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('385dbfe3-a662-4c25-a813-14fee38baed0', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('4304eb5f-337d-4a77-bd04-8c87356399e7', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('75c78c81-3338-4819-8d89-f061c3be30f9', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('8351e2b2-7c3a-472b-b59f-381540841a71', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('a6e443e2-fdb4-423b-bed8-9eb50737a7f5', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('a7c22105-4b7d-4275-909e-6c2e28bdcd08', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('a94af25d-a177-4264-acb5-30ec69b79f11', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('bc6df49c-c7f8-4f7f-9ab3-af2b6c93dcb8', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('c002a74d-c37d-4c38-a53d-41de9ec5b94e', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('c6aedfb9-2526-44c9-ad32-a6d9da7b07fa', 'c3309eb5-6d1f-11ef-8e68-088fc3163dd3'),
('4917076a-3c58-4847-b9b7-5b5c1589c020', 'defa439f-0737-4176-96c6-d9d3fd0cf10a'),
('065689c5-ec35-454e-8532-046faed23a79', 'eea0f82d-6d21-11ef-8e68-088fc3163dd3'),
('70c3510d-fb47-4403-adc9-8e4949bc7da0', 'eea0f82d-6d21-11ef-8e68-088fc3163dd3'),
('4917076a-3c58-4847-b9b7-5b5c1589c020', 'eea1350b-6d21-11ef-8e68-088fc3163dd3');

-- --------------------------------------------------------
--
-- Dumping data for table `access_products` (1 rows)
--

INSERT INTO `access_products` (`user_id`, `product_id`, `created_at`) VALUES
('4a707f19-1339-460b-ba9e-237a77e771d9', '243cab68-27bf-49ae-95eb-f4ad37e38566', '2026-10-14 11:09:09');

-- --------------------------------------------------------
--
-- Dumping data for table `carts` (1 rows)
--

INSERT INTO `carts` (`cart_id`, `created_at`, `updated_at`, `user_id`, `cart_status`) VALUES
('5b337b8e-b311-4cac-8aa2-45ea0563f68f', '2026-10-14 11:00:25', '2026-10-14 11:00:25', '4a707f19-1339-460b-ba9e-237a77e771d9', 1);

-- --------------------------------------------------------
--
-- Dumping data for table `carts_products` (4 rows)
--

INSERT INTO `carts_products` (`cart_id`, `product_id`) VALUES
('5b337b8e-b311-4cac-8aa2-45ea0563f68f', '4304eb5f-337d-4a77-bd04-8c87356399e7'),
('5b337b8e-b311-4cac-8aa2-45ea0563f68f', 'c2b756f2-45b7-4d7a-b9ec-afdb1e38e21c'),
('5b337b8e-b311-4cac-8aa2-45ea0563f68f', 'c6aedfb9-2526-44c9-ad32-a6d9da7b07fa'),
('5b337b8e-b311-4cac-8aa2-45ea0563f68f', 'd075c0b9-022f-4aee-a353-67c73243c5be');

-- --------------------------------------------------------
--
-- Dumping data for table `url_redirects` (28 rows)
--

INSERT INTO `url_redirects` (`id`, `source_path`, `target_path`, `status_code`, `hits`, `last_accessed_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '/product-category/audi1', '/en/c/car/audi/exterior', 301, 0, NULL, 1, NOW(), NOW()),
(2, '/en/product-category/audi1', '/en/c/car/audi/exterior', 301, 1, NOW(), 1, NOW(), NOW()),
(3, '/vi/product-category/audi1', '/vi/c/car/audi/exterior', 301, 0, NULL, 1, NOW(), NOW()),
(4, '/ja/product-category/audi1', '/ja/c/car/audi/exterior', 301, 0, NULL, 1, NOW(), NOW()),
(5, '/es/product-category/audi1', '/es/c/car/audi/exterior', 301, 0, NULL, 1, NOW(), NOW()),
(6, '/fr/product-category/audi1', '/fr/c/car/audi/exterior', 301, 0, NULL, 1, NOW(), NOW()),
(7, '/zh/product-category/audi1', '/zh/c/car/audi/exterior', 301, 0, NULL, 1, NOW(), NOW()),
(8, '/product-category/sgsff', '/en/c/wrap-stock/3m-vinyl', 301, 0, NULL, 1, NOW(), NOW()),
(9, '/en/product-category/sgsff', '/en/c/wrap-stock/3m-vinyl', 301, 0, NULL, 1, NOW(), NOW()),
(10, '/vi/product-category/sgsff', '/vi/c/wrap-stock/3m-vinyl', 301, 0, NULL, 1, NOW(), NOW()),
(11, '/ja/product-category/sgsff', '/ja/c/wrap-stock/3m-vinyl', 301, 0, NULL, 1, NOW(), NOW()),
(12, '/es/product-category/sgsff', '/es/c/wrap-stock/3m-vinyl', 301, 0, NULL, 1, NOW(), NOW()),
(13, '/fr/product-category/sgsff', '/fr/c/wrap-stock/3m-vinyl', 301, 0, NULL, 1, NOW(), NOW()),
(14, '/zh/product-category/sgsff', '/zh/c/wrap-stock/3m-vinyl', 301, 0, NULL, 1, NOW(), NOW()),
(15, '/brand/car-pdf-interrior-template', '/en/brand/car-pdf-interior-template', 301, 0, NULL, 1, NOW(), NOW()),
(16, '/en/brand/car-pdf-interrior-template', '/en/brand/car-pdf-interior-template', 301, 0, NULL, 1, NOW(), NOW()),
(17, '/vi/brand/car-pdf-interrior-template', '/vi/brand/car-pdf-interior-template', 301, 0, NULL, 1, NOW(), NOW()),
(18, '/ja/brand/car-pdf-interrior-template', '/ja/brand/car-pdf-interior-template', 301, 0, NULL, 1, NOW(), NOW()),
(19, '/es/brand/car-pdf-interrior-template', '/es/brand/car-pdf-interior-template', 301, 0, NULL, 1, NOW(), NOW()),
(20, '/fr/brand/car-pdf-interrior-template', '/fr/brand/car-pdf-interior-template', 301, 0, NULL, 1, NOW(), NOW()),
(21, '/zh/brand/car-pdf-interrior-template', '/zh/brand/car-pdf-interior-template', 301, 0, NULL, 1, NOW(), NOW()),
(22, '/product/mini-coopers-hardtop-2023-2-door---interior---for-right-hand-drive', '/en/p/mini-cooper-hardtop-2023-interior-rhd', 301, 0, NULL, 1, NOW(), NOW()),
(23, '/en/product/mini-coopers-hardtop-2023-2-door---interior---for-right-hand-drive', '/en/p/mini-cooper-hardtop-2023-interior-rhd', 301, 0, NULL, 1, NOW(), NOW()),
(24, '/vi/product/mini-coopers-hardtop-2023-2-door---interior---for-right-hand-drive', '/vi/p/mini-cooper-hardtop-2023-interior-rhd', 301, 0, NULL, 1, NOW(), NOW()),
(25, '/ja/product/mini-coopers-hardtop-2023-2-door---interior---for-right-hand-drive', '/ja/p/mini-cooper-hardtop-2023-interior-rhd', 301, 0, NULL, 1, NOW(), NOW()),
(26, '/es/product/mini-coopers-hardtop-2023-2-door---interior---for-right-hand-drive', '/es/p/mini-cooper-hardtop-2023-interior-rhd', 301, 0, NULL, 1, NOW(), NOW()),
(27, '/fr/product/mini-coopers-hardtop-2023-2-door---interior---for-right-hand-drive', '/fr/p/mini-cooper-hardtop-2023-interior-rhd', 301, 0, NULL, 1, NOW(), NOW()),
(28, '/zh/product/mini-coopers-hardtop-2023-2-door---interior---for-right-hand-drive', '/zh/p/mini-cooper-hardtop-2023-interior-rhd', 301, 0, NULL, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
