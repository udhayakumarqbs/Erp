#
# TABLE STRUCTURE FOR: account_groups
#

DROP TABLE IF EXISTS `account_groups`;

CREATE TABLE `account_groups` (
  `acc_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `base_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `profit_loss` tinyint(4) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`acc_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `account_groups` (acc_group_id, base_id, group_name, profit_loss, created_at, created_by) VALUES (3, 6, QBS, 1, 1701952649, 1);
INSERT INTO `account_groups` (acc_group_id, base_id, group_name, profit_loss, created_at, created_by) VALUES (4, 6, softriders, 1, 1702013923, 1);
INSERT INTO `account_groups` (acc_group_id, base_id, group_name, profit_loss, created_at, created_by) VALUES (8, 1, test, 1, 1703764174, 1);
INSERT INTO `account_groups` (acc_group_id, base_id, group_name, profit_loss, created_at, created_by) VALUES (9, 2, Marketing, 1, 1703828104, 1);
INSERT INTO `account_groups` (acc_group_id, base_id, group_name, profit_loss, created_at, created_by) VALUES (11, 3, test, 1, 1717759633, 1);


#
# TABLE STRUCTURE FOR: accountbase
#

DROP TABLE IF EXISTS `accountbase`;

CREATE TABLE `accountbase` (
  `base_id` int(11) NOT NULL AUTO_INCREMENT,
  `base_name` varchar(255) NOT NULL,
  `general_name` varchar(100) NOT NULL,
  PRIMARY KEY (`base_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `accountbase` (base_id, base_name, general_name) VALUES (1, Income, revenue);
INSERT INTO `accountbase` (base_id, base_name, general_name) VALUES (2, Overheads, expense);
INSERT INTO `accountbase` (base_id, base_name, general_name) VALUES (3, Fixed Assets, asset);
INSERT INTO `accountbase` (base_id, base_name, general_name) VALUES (4, Accounts Payable, liability);
INSERT INTO `accountbase` (base_id, base_name, general_name) VALUES (5, Accounts Receivable, asset);
INSERT INTO `accountbase` (base_id, base_name, general_name) VALUES (6, Current Assets, asset);


#
# TABLE STRUCTURE FOR: additions
#

DROP TABLE IF EXISTS `additions`;

CREATE TABLE `additions` (
  `add_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `value` decimal(14,2) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`add_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `additions` (add_id, name, description, type, value, created_at, created_by) VALUES (1, Bonus 1, , 1, 2000.00, 1651040252, 1);
INSERT INTO `additions` (add_id, name, description, type, value, created_at, created_by) VALUES (2, Bonus 2, , 0, 10.00, 1651040275, 1);
INSERT INTO `additions` (add_id, name, description, type, value, created_at, created_by) VALUES (3, T Tax, 5, 1, 5.00, 1703164036, 1);


#
# TABLE STRUCTURE FOR: amenity
#

DROP TABLE IF EXISTS `amenity`;

CREATE TABLE `amenity` (
  `amenity_id` int(11) NOT NULL AUTO_INCREMENT,
  `amenity_name` varchar(255) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`amenity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `amenity` (amenity_id, amenity_name, created_at, created_by) VALUES (1, Security, 1648624842, 1);
INSERT INTO `amenity` (amenity_id, amenity_name, created_at, created_by) VALUES (2, CCTV, 1648624848, 1);


#
# TABLE STRUCTURE FOR: announcements
#

DROP TABLE IF EXISTS `announcements`;

CREATE TABLE `announcements` (
  `announcementid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `message` text NOT NULL,
  `showtousers` int(11) NOT NULL,
  `showtostaff` int(11) NOT NULL,
  `showname` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `userid` varchar(100) NOT NULL,
  PRIMARY KEY (`announcementid`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (20, udhaya, <p>daii</p>, 0, 1, 0, 2024-05-04 16:58:33, 2024-05-04 16:58:33, Admin);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (21, kumar, <p>hello</p>, 0, 1, 0, 2024-05-04 16:58:47, 2024-05-04 16:58:47, Admin);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (22, ashok , <p>baiyaa</p>, 0, 1, 0, 2024-05-04 16:59:01, 2024-05-04 16:59:01, Admin);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (23, tamil, <p>vrooo</p>, 0, 1, 0, 2024-05-04 16:59:24, 2024-05-04 16:59:24, Admin);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (25, today, <p>hello</p>, 0, 1, 0, 2024-05-06 11:54:55, 2024-05-06 11:54:55, 1);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (26, q, <p>q</p>, 0, 1, 0, 2024-05-06 11:55:09, 2024-05-06 11:55:09, 1);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (28, df, <p>sdf</p>, 0, 1, 0, 2024-05-08 12:16:26, 2024-05-08 12:16:26, 1);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (29, demo, <p>heloo</p>, 0, 1, 0, 2024-05-08 15:02:51, 2024-05-08 15:02:51, 1);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (30, dsf, <p>sdf</p>, 0, 1, 0, 2024-05-09 11:48:26, 2024-05-09 11:48:26, 1);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (31, Testing, , 1, 1, 1, 2024-06-10 12:06:17, 2024-06-10 12:06:17, 1);
INSERT INTO `announcements` (announcementid, name, message, showtousers, showtostaff, showname, dateadded, updated_at, userid) VALUES (32, Today Day, <p>Today is Wednesday</p>, 0, 0, 0, 2024-06-11 12:15:39, 2024-06-11 12:15:39, 1);


#
# TABLE STRUCTURE FOR: attachments
#

DROP TABLE IF EXISTS `attachments`;

CREATE TABLE `attachments` (
  `attach_id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  PRIMARY KEY (`attach_id`)
) ENGINE=InnoDB AUTO_INCREMENT=267 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (8, journal-book.png, lead, 3);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (9, Nichiyu_files.xlsx, lead, 3);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (11, TAG_MANPOWER.docx, lead, 3);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (12, htdocscounter.png, lead, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (13, htdocscounter.txt, lead, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (16, Chrysanthemum.jpg, request, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (17, cat.png, request, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (18, enquiry_trigger.txt, request, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (19, KHSSPA.docx, raw_material, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (21, Desert.jpg, raw_material, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (22, export.pdf, raw_material, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (24, harina_sms_templates.docx, finished_good, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (36, Staff-Details-Form.docx, employee, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (38, cat.png, contractor, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (39, Staff-Details-Form.docx, contractor, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (40, ContractorImport.csv, contractor, 3);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (42, export.pdf, inventory_service, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (46, form3.png, ticket, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (51, Ads.docx, sale_order, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (55, form2.png, sale_invoice, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (58, cat.png, equipment, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (62, export.pdf, lead, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (63, Ads.docx, project_testing, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (64, CREXI_TENX.docx, project_testing, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (65, Staff-Details-Form.docx, project, 3);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (89, export(1).xlsx, raw_material, 24);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (113, rsz_whatsapp_image_2023-11-30_at_185343_0b2b413a.jpg, project_testing, 4);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (129, manageincome.png, credit_note, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (137, novsales.xlsx, property, 4);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (141, file-sample_100kB-Copy.docx, contractor, 7);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (142, novsales.xlsx, contractor, 4);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (156, WhatsAppImage2023-12-25at15.07.50_d3f71240.jpg, employee, 6);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (163, ManufacturingERP.pdf, sale_invoice, 3);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (164, QBrainstormSoftwareManufacturingCompanyERPDoc.pdf, sale_invoice, 3);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (170, Outlook_Configuration.docx, sale_order, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (171, ManufacturingERP.pdf, estimate, 3);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (172, insta.txt, quotation, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (173, QBrainstormSoftwareManufacturingCompanyERPDoc.pdf, estimate, 4);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (174, Outlook_Configuration.docx, estimate, 22);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (175, export(9).xlsx, customer, 6);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (182, academicyearselectboxvcvrtoldcode.txt, quotation, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (183, Massinfra-content.docx, semi_finished, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (184, consulgurususpiciouscode.txt, sale_order, 27);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (186, cronss.txt, sale_invoice, 1);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (189, Massinfra-content.docx, purchase_invoice, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (192, Massinfra-content.docx, project, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (193, Massinfra-content.docx, equipment, 4);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (195, Massinfra-content.docx, contractor, 9);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (196, AttendanceImport.xlsx, ticket, 6);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (199, export(2).xlsx, semi_finished, 12);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (201, post2.png, rfq, 8);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (204, AttendanceImport(3).xlsx, employee, 14);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (207, Massinfra-content.docx, sale_invoice, 39);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (208, QbsSeo.txt, sale_invoice, 44);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (210, export(12).pdf, estimate, 35);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (211, export(9).pdf, quotation, 5);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (212, export(9).pdf, sale_order, 31);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (217, export(9).pdf, team, 15);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (218, export(8).pdf, project_testing, 11);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (220, export(6).pdf, equipment, 6);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (225, daily-report(3).xlsx, project, 23);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (226, daily-report.xlsx, project, 23);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (227, daily-report-m11.xlsx, project, 24);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (237, daily-report.xlsx, contract_Attachment, 10);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (238, daily-report-13.xlsx, contract_Attachment, 8);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (239, daily-report-12.xlsx, contract_Attachment, 6);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (240, daily-report-m11.xlsx, contract_Attachment, 12);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (241, daily-report-m14.xlsx, contract_Attachment, 13);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (242, daily-report-m23.xlsx, contract_Attachment, 15);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (243, daily-report-m24.xlsx, contract_Attachment, 20);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (244, daily-report-m29.xlsx, contract_Attachment, 22);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (245, daily-report-m30.xlsx, contract_Attachment, 24);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (246, daily-report(3).xlsx, contract_Attachment, 25);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (247, signed.jpg, Expenses_Attachment, 22);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (248, notsigned.webp, Expenses_Attachment, 24);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (249, Cool-8k-Computer-Wallpaper-scaled.jpg, Expenses_Attachment, 27);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (250, signed.jpg, Expenses_Attachment, 30);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (251, notsigned.webp, Expenses_Attachment, 32);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (254, signed.jpg, Expenses_Attachment, 35);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (255, notsigned.svg, sale_invoice, 2);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (256, tblexpenses.sql, Expenses_Attachment, 36);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (257, signed.jpg, Expenses_Attachment, 38);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (260, 3138297.png, Expenses_Attachment, 40);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (261, ERP.docx, sale_invoice, 13);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (262, contract(1).pdf, sale_invoice, 19);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (263, Awesome4KUltraHDGalaxyWallpapers-WallpaperAccess.jpeg, Expenses_Attachment, 41);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (264, wall.jpg, sale_invoice, 20);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (265, contract(3).pdf, Expenses_Attachment, 42);
INSERT INTO `attachments` (attach_id, filename, related_to, related_id) VALUES (266, wilfred_1st_poster.png, Expenses_Attachment, 43);


#
# TABLE STRUCTURE FOR: auto_trans_list
#

DROP TABLE IF EXISTS `auto_trans_list`;

CREATE TABLE `auto_trans_list` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_name` varchar(140) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  PRIMARY KEY (`trans_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auto_trans_list` (trans_id, transaction_name, related_to) VALUES (1, Marketing Expense, marketing);


#
# TABLE STRUCTURE FOR: auto_transaction
#

DROP TABLE IF EXISTS `auto_transaction`;

CREATE TABLE `auto_transaction` (
  `autotrans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_id` int(11) NOT NULL,
  `debit_gl_account` int(11) NOT NULL,
  `credit_gl_account` int(11) NOT NULL,
  `auto_posting` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`autotrans_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `auto_transaction` (autotrans_id, trans_id, debit_gl_account, credit_gl_account, auto_posting, active) VALUES (6, 1, 1, 2, 1, 0);


#
# TABLE STRUCTURE FOR: bankaccounts
#

DROP TABLE IF EXISTS `bankaccounts`;

CREATE TABLE `bankaccounts` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `gl_acc_id` int(11) NOT NULL,
  `bank_name` varchar(140) NOT NULL,
  `bank_acc_no` varchar(40) NOT NULL DEFAULT '',
  `bank_code` varchar(255) NOT NULL,
  `branch` varchar(140) NOT NULL,
  `address` varchar(500) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bankaccounts` (bank_id, gl_acc_id, bank_name, bank_acc_no, bank_code, branch, address, created_at, created_by) VALUES (1, 2, TKD Bank, 1234567890123, 1234, chennai, No.164, First Floor, Arcot Rd, Valasaravakkam, 1645264242, 1);
INSERT INTO `bankaccounts` (bank_id, gl_acc_id, bank_name, bank_acc_no, bank_code, branch, address, created_at, created_by) VALUES (2, 2, State, 1234567890123, 659850, chennai, No.164, First Floor, Arcot Rd, Valasaravakkam, 1645264266, 1);
INSERT INTO `bankaccounts` (bank_id, gl_acc_id, bank_name, bank_acc_no, bank_code, branch, address, created_at, created_by) VALUES (3, 1, state, 12454, IOB234, valasarvakkam, 12, 1703333072, 1);
INSERT INTO `bankaccounts` (bank_id, gl_acc_id, bank_name, bank_acc_no, bank_code, branch, address, created_at, created_by) VALUES (4, 15, testi, 123, 23, test, 12, 1703333140, 1);
INSERT INTO `bankaccounts` (bank_id, gl_acc_id, bank_name, bank_acc_no, bank_code, branch, address, created_at, created_by) VALUES (5, 1, TKD Bank, 12454, 1234, chennai, madurai, 1705750080, 1);


#
# TABLE STRUCTURE FOR: brands
#

DROP TABLE IF EXISTS `brands`;

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(140) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `brands` (brand_id, brand_name, created_at, created_by) VALUES (1, Audi, 1645593031, 1);
INSERT INTO `brands` (brand_id, brand_name, created_at, created_by) VALUES (2, BMW, 1645593256, 1);
INSERT INTO `brands` (brand_id, brand_name, created_at, created_by) VALUES (5, KTM, 1703420310, 1);
INSERT INTO `brands` (brand_id, brand_name, created_at, created_by) VALUES (8, MT -15, 1718428400, 1);


#
# TABLE STRUCTURE FOR: calender_events
#

DROP TABLE IF EXISTS `calender_events`;

CREATE TABLE `calender_events` (
  `event_id` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `Start_data` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_data` datetime NOT NULL,
  `public_event` int(100) NOT NULL,
  `is_start_notified` int(100) NOT NULL,
  `reminder_before` int(100) NOT NULL,
  `reminder_before_type` varchar(100) NOT NULL,
  `event_color` varchar(100) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `calender_events` (event_id, title, description, user_id, Start_data, end_data, public_event, is_start_notified, reminder_before, reminder_before_type, event_color) VALUES (20, today, test, 0, 2024-05-06 16:09:00, 2024-05-24 17:10:00, 1, 0, 45, Minutes, #da6110);


#
# TABLE STRUCTURE FOR: contract_commend
#

DROP TABLE IF EXISTS `contract_commend`;

CREATE TABLE `contract_commend` (
  `discussion_id` int(100) NOT NULL AUTO_INCREMENT,
  `dicussion_note` text NOT NULL,
  `contract_id` int(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`discussion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (60, hi, 12, 1, 2024-05-27 18:10:59);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (61, test commend, 13, 1, 2024-05-27 18:14:05);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (62, test commend 2
, 13, 1, 2024-05-27 18:14:23);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (63, Test comment_1_2, 15, 1, 2024-05-27 18:50:51);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (64, Heloo, 16, 1, 2024-05-28 10:28:29);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (65, Comment, 14, 1, 2024-05-28 10:36:13);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (66, test, 14, 1, 2024-05-28 10:36:28);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (67, hi, 17, 1, 2024-05-28 10:45:12);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (68, njknjk, 19, 1, 2024-05-28 10:47:01);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (69, z, 18, 1, 2024-05-28 10:53:11);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (79, aaa, 22, 1, 2024-05-30 18:37:31);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (80, sss, 22, 1, 2024-05-30 18:49:13);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (81, new
, 24, 1, 2024-05-31 16:00:33);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (82, New1, 31, 1, 2024-06-03 10:39:41);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (83, Hello, 36, 1, 2024-06-10 12:24:14);
INSERT INTO `contract_commend` (discussion_id, dicussion_note, contract_id, user_id, created_at) VALUES (84, test, 36, 1, 2024-06-10 12:24:19);


#
# TABLE STRUCTURE FOR: contract_renewals
#

DROP TABLE IF EXISTS `contract_renewals`;

CREATE TABLE `contract_renewals` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `contractid` int(100) NOT NULL,
  `old_start_date` date NOT NULL DEFAULT current_timestamp(),
  `new_start_date` date NOT NULL DEFAULT current_timestamp(),
  `old_end_date` date NOT NULL DEFAULT current_timestamp(),
  `new_end_date` date NOT NULL DEFAULT current_timestamp(),
  `old_value` int(100) NOT NULL,
  `new_value` int(100) NOT NULL,
  `date_renewed` datetime NOT NULL DEFAULT current_timestamp(),
  `renewed_by` varchar(100) NOT NULL,
  `renewed_by_staff_id` int(100) NOT NULL,
  `is_on_old_expiry_notified` smallint(6) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contract_renewals` (id, contractid, old_start_date, new_start_date, old_end_date, new_end_date, old_value, new_value, date_renewed, renewed_by, renewed_by_staff_id, is_on_old_expiry_notified) VALUES (12, 20, 2024-05-28, 2024-05-28, 2024-06-27, 2024-06-29, 750000, 750000, 2024-05-28 17:43:09, Admin, 1, 0);
INSERT INTO `contract_renewals` (id, contractid, old_start_date, new_start_date, old_end_date, new_end_date, old_value, new_value, date_renewed, renewed_by, renewed_by_staff_id, is_on_old_expiry_notified) VALUES (13, 32, 2024-06-03, 2024-06-03, 2024-06-29, 2024-06-29, 560000, 560000, 2024-06-07 14:37:41, Admin, 1, 0);


#
# TABLE STRUCTURE FOR: contract_tasks
#

DROP TABLE IF EXISTS `contract_tasks`;

CREATE TABLE `contract_tasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `related_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `assignees` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `task_description` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contract_tasks` (task_id, contract_id, name, status, start_date, due_date, related_id, priority, assignees, followers, task_description, created_by, created_at) VALUES (7, 20, QBrainstorm Software, 2, 2024-05-30, 2024-06-06, 21, 3, 14, 14, helloo, 1, 2024-05-30);
INSERT INTO `contract_tasks` (task_id, contract_id, name, status, start_date, due_date, related_id, priority, assignees, followers, task_description, created_by, created_at) VALUES (10, 32, asasaS, 1, 2024-06-21, 2024-06-14, 31, 0, 14, 14, adaad, 1, 2024-06-07);
INSERT INTO `contract_tasks` (task_id, contract_id, name, status, start_date, due_date, related_id, priority, assignees, followers, task_description, created_by, created_at) VALUES (11, 32, adfdsaf, 2, 2024-06-07, 2024-06-08, 32, 0, 14, 14, adfdfdf, 1, 2024-06-07);
INSERT INTO `contract_tasks` (task_id, contract_id, name, status, start_date, due_date, related_id, priority, assignees, followers, task_description, created_by, created_at) VALUES (12, 32, adsff, 2, 2024-05-29, 2024-06-23, 31, 2, 14, 14, assdsd, 1, 2024-06-07);


#
# TABLE STRUCTURE FOR: contractor_payments
#

DROP TABLE IF EXISTS `contractor_payments`;

CREATE TABLE `contractor_payments` (
  `contractor_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_wgrp_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `paid_on` date NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`contractor_pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contractor_payments` (contractor_pay_id, project_wgrp_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (2, 7, 1, 2000.00, 2022-05-05);
INSERT INTO `contractor_payments` (contractor_pay_id, project_wgrp_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (3, 7, 1, 1000.00, 2022-05-04);


#
# TABLE STRUCTURE FOR: contractors
#

DROP TABLE IF EXISTS `contractors`;

CREATE TABLE `contractors` (
  `contractor_id` int(11) NOT NULL AUTO_INCREMENT,
  `con_code` varchar(140) NOT NULL,
  `name` varchar(140) NOT NULL,
  `contact_person` varchar(140) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_1` varchar(13) NOT NULL,
  `phone_2` varchar(13) NOT NULL,
  `gst_no` varchar(30) NOT NULL,
  `pan_no` varchar(30) NOT NULL,
  `website` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`contractor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contractors` (contractor_id, con_code, name, contact_person, email, phone_1, phone_2, gst_no, pan_no, website, address, city, state, country, zipcode, active, description, created_at, created_by) VALUES (13, CON01, Production , test, test@gmail.com, 01234567890, 01234567890, 8785657, 744168579663, , chennai, Thiruvallur, tamilnadu, India, 602024, 1, szgseh, 1704176004, 1);


#
# TABLE STRUCTURE FOR: contracttype
#

DROP TABLE IF EXISTS `contracttype`;

CREATE TABLE `contracttype` (
  `cont_id` int(100) NOT NULL AUTO_INCREMENT,
  `cont_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cont_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (57, test@1, 2024-05-17 14:56:42);
INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (58, test@2, 2024-05-17 14:58:40);
INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (60, test@4, 2024-05-17 15:03:09);
INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (68, udhaya-ci4, 2024-05-27 17:46:05);
INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (69, Hello, 2024-05-27 17:48:19);
INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (73, sdsfd, 2024-05-27 17:51:34);
INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (80, last, 2024-05-27 18:10:20);
INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (81, Last type, 2024-05-30 17:58:29);
INSERT INTO `contracttype` (cont_id, cont_name, created_at) VALUES (83, New_one, 2024-06-03 10:36:38);


#
# TABLE STRUCTURE FOR: credit_items
#

DROP TABLE IF EXISTS `credit_items`;

CREATE TABLE `credit_items` (
  `credit_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `credit_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  PRIMARY KEY (`credit_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# TABLE STRUCTURE FOR: credit_notes
#

DROP TABLE IF EXISTS `credit_notes`;

CREATE TABLE `credit_notes` (
  `credit_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(140) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `applied` tinyint(1) NOT NULL DEFAULT 0,
  `issued_date` date NOT NULL,
  `other_charge` decimal(14,2) NOT NULL,
  `applied_amount` int(11) NOT NULL,
  `balance_amount` int(11) NOT NULL,
  `payment_terms` varchar(140) NOT NULL,
  `terms_condition` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`credit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `credit_notes` (credit_id, code, cust_id, invoice_id, applied, issued_date, other_charge, applied_amount, balance_amount, payment_terms, terms_condition, remarks, created_at, created_by) VALUES (1, CN-001, 45, 0, 0, 2024-02-06, 5000.00, 0, 0, setset, <p>test</p>, test, 1707223371, 1);
INSERT INTO `credit_notes` (credit_id, code, cust_id, invoice_id, applied, issued_date, other_charge, applied_amount, balance_amount, payment_terms, terms_condition, remarks, created_at, created_by) VALUES (2, CN-002, 45, 0, 0, 2024-06-11, 50000.00, 0, 0, expense add, <p>asdsads</p>, asdsdsdsd, 1718087205, 1);
INSERT INTO `credit_notes` (credit_id, code, cust_id, invoice_id, applied, issued_date, other_charge, applied_amount, balance_amount, payment_terms, terms_condition, remarks, created_at, created_by) VALUES (3, CN-003, 45, 0, 0, 2024-06-25, 0.00, 0, 0, syfdsg, , , 1719293318, 1);
INSERT INTO `credit_notes` (credit_id, code, cust_id, invoice_id, applied, issued_date, other_charge, applied_amount, balance_amount, payment_terms, terms_condition, remarks, created_at, created_by) VALUES (4, CN-004, 37, 0, 0, 2024-06-25, 0.00, 0, 0, sadsad, , , 1719299435, 1);


#
# TABLE STRUCTURE FOR: credits_applied
#

DROP TABLE IF EXISTS `credits_applied`;

CREATE TABLE `credits_applied` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `credit_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date_applied` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `credits_applied` (id, credit_id, invoice_id, staff_id, date_applied, amount) VALUES (1, 1, 3, 1, 2024-02-06, 10.00);
INSERT INTO `credits_applied` (id, credit_id, invoice_id, staff_id, date_applied, amount) VALUES (2, 1, 3, 1, 2024-02-06, 10.00);
INSERT INTO `credits_applied` (id, credit_id, invoice_id, staff_id, date_applied, amount) VALUES (3, 1, 3, 1, 2024-02-06, 4060.00);
INSERT INTO `credits_applied` (id, credit_id, invoice_id, staff_id, date_applied, amount) VALUES (4, 1, 3, 1, 2024-02-06, 100.00);
INSERT INTO `credits_applied` (id, credit_id, invoice_id, staff_id, date_applied, amount) VALUES (5, 1, 4, 1, 2024-02-06, 820.00);
INSERT INTO `credits_applied` (id, credit_id, invoice_id, staff_id, date_applied, amount) VALUES (6, 4, 19, 1, 2024-06-25, 0.00);


#
# TABLE STRUCTURE FOR: currencies
#

DROP TABLE IF EXISTS `currencies`;

CREATE TABLE `currencies` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `iso_code` char(3) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `decimal_sep` char(1) NOT NULL,
  `thousand_sep` char(1) NOT NULL,
  `place` varchar(10) NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `currencies` (currency_id, iso_code, symbol, decimal_sep, thousand_sep, place, is_default, created_at, created_by) VALUES (6, INR, र, ., ,, after, 0, 1704190884, 1);
INSERT INTO `currencies` (currency_id, iso_code, symbol, decimal_sep, thousand_sep, place, is_default, created_at, created_by) VALUES (8, INR, र, ., ,, before, 0, 1705642847, 1);
INSERT INTO `currencies` (currency_id, iso_code, symbol, decimal_sep, thousand_sep, place, is_default, created_at, created_by) VALUES (11, USD, $, ., ,, before, 0, 1705643125, 1);
INSERT INTO `currencies` (currency_id, iso_code, symbol, decimal_sep, thousand_sep, place, is_default, created_at, created_by) VALUES (13, USD, $, ., ,, after, 0, 1708424855, 1);


#
# TABLE STRUCTURE FOR: custom_field_values
#

DROP TABLE IF EXISTS `custom_field_values`;

CREATE TABLE `custom_field_values` (
  `cfv_id` int(11) NOT NULL AUTO_INCREMENT,
  `cf_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  PRIMARY KEY (`cfv_id`)
) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (6, 1, 9, FeMale);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (7, 1, 10, Female);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (10, 1, 12, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (57, 1, 38, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (58, 4, 38, Grade1,Grade2);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (63, 5, 7, Test);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (70, 5, 11, Test);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (73, 5, 13, Common);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (74, 5, 14, Common);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (75, 5, 15, Common);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (86, 1, 31, FeMale);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (87, 1, 32, emale);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (88, 6, 3, dcghfyj);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (89, 6, 4, awerhw);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (91, 6, 6, wrewr);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (92, 6, 7, Import);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (93, 7, 1, 2024-01-09);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (94, 7, 2, 2024-01-26);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (95, 8, 1, Outside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (96, 8, 2, Inside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (97, 6, 18, werwer);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (98, 6, 19, werwer);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (99, 6, 20, Test);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (100, 6, 21, Test 1);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (101, 6, 24, 234432);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (102, 6, 25, 234432);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (103, 6, 26, 234432);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (104, 6, 27, sdfsdf);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (105, 6, 28, 345345);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (106, 6, 29, 345345);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (107, 6, 30, 345345);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (108, 6, 31, Kumar Company);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (109, 6, 32, 45645);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (115, 6, 39, 45645);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (116, 6, 40, 45645);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (117, 6, 41, 45645);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (118, 6, 42, 45645);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (119, 6, 43, 45645);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (120, 6, 44, 45645);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (121, 6, 45, 45645);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (124, 5, 18, Test);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (125, 5, 16, Test);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (126, 5, 25, SivaKumar);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (127, 5, 26, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (128, 5, 27, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (129, 5, 28, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (130, 5, 29, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (131, 5, 30, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (132, 5, 31, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (133, 5, 32, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (134, 5, 33, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (135, 5, 34, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (136, 5, 35, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (137, 5, 36, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (138, 5, 37, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (139, 5, 38, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (140, 5, 39, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (141, 5, 40, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (142, 5, 41, rwet);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (146, 8, 4, Outside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (147, 5, 2, Test);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (148, 5, 3, Test);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (149, 5, 17, Test);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (150, 5, 20, Common);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (151, 5, 21, Common);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (152, 5, 24, SevenSanjay);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (153, 5, 42, 6456);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (154, 5, 43, SivaKumar);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (155, 1, 41, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (156, 1, 43, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (157, 4, 43, Grade2);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (158, 1, 44, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (159, 4, 44, Grade2);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (162, 1, 11, Female);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (163, 5, 44, dfggdsfgf);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (165, 8, 5, Inside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (166, 8, 6, Outside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (167, 6, 46, 234324);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (168, 7, 5, 2023-12-26);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (169, 7, 6, 2023-12-27);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (170, 1, 6, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (171, 4, 6, Grade1);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (173, 5, 45, dfggdsfgf);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (175, 5, 47, setj);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (176, 5, 46, aez6t);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (178, 5, 49, ser);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (181, 5, 48, er7yk);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (182, 8, 7, Inside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (183, 6, 47, test concern);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (184, 6, 48, jh);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (185, 5, 50, ser);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (187, 5, 51, ser);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (188, 5, 52, awrweth);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (189, 7, 7, 2023-12-29);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (191, 5, 53, sdfdsf);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (192, 7, 8, 2023-12-29);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (193, 7, 9, 2023-12-30);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (194, 6, 49, ERST5UJR5);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (195, 7, 10, 2023-12-30);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (196, 6, 50, srycfjrs);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (197, 7, 11, 2023-12-30);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (198, 7, 12, 2023-12-30);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (199, 7, 13, 2023-12-30);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (200, 6, 51, esthedthe);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (201, 6, 52, bghkvfg);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (202, 6, 1, awerhw);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (203, 6, 2, dertu);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (204, 8, 8, Inside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (205, 8, 9, Outside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (206, 8, 10, Inside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (207, 5, 54, xdfg);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (208, 5, 55, adrgsh);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (210, 5, 56, awzrhgesh);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (211, 5, 57, sertyhrth);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (212, 7, 14, 2024-01-02);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (214, 5, 58, sdgr);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (216, 5, 59, dxfzh);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (220, 5, 61, aefgae);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (222, 5, 62, dxhfxg);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (224, 5, 63, Seven Sanjay);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (225, 5, 64, asdfsdf);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (226, 5, 65, sadd);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (227, 5, 60, eharxr);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (228, 7, 15, 2024-01-03);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (235, 7, 16, 2024-01-09);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (237, 5, 66, awerg);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (238, 5, 67, aehrst);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (239, 8, 11, Inside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (240, 1, 46, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (241, 4, 46, Grade2);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (242, 1, 47, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (243, 4, 47, Grade2);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (247, 5, 69, sdagsag);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (249, 5, 70, fsdgsdfh);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (250, 8, 12, Inside);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (251, 5, 68, asdgasg);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (252, 6, 5, as);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (253, 1, 37, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (254, 4, 37, Grade1,Grade2);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (255, 1, 45, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (256, 4, 45, Grade2);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (257, 1, 48, Female);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (258, 4, 48, Grade2);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (259, 1, 49, Male);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (260, 4, 49, Grade1);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (261, 7, 3, 2024-05-02);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (262, 1, 50, Female);
INSERT INTO `custom_field_values` (cfv_id, cf_id, related_id, field_value) VALUES (263, 4, 50, Grade1);


#
# TABLE STRUCTURE FOR: custom_fields
#

DROP TABLE IF EXISTS `custom_fields`;

CREATE TABLE `custom_fields` (
  `cf_id` int(11) NOT NULL AUTO_INCREMENT,
  `field_type` varchar(140) NOT NULL,
  `field_related_to` varchar(255) NOT NULL,
  `field_name` varchar(140) NOT NULL,
  `field_options` varchar(255) NOT NULL,
  `required` tinyint(4) NOT NULL,
  `order_num` int(11) NOT NULL,
  `can_be_purged` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`cf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `custom_fields` (cf_id, field_type, field_related_to, field_name, field_options, required, order_num, can_be_purged) VALUES (1, radio, customer, Gender, Male,Female,Transgender, 1, 2, 0);
INSERT INTO `custom_fields` (cf_id, field_type, field_related_to, field_name, field_options, required, order_num, can_be_purged) VALUES (2, date, proposal, Payment Date, , 1, 3, 1);
INSERT INTO `custom_fields` (cf_id, field_type, field_related_to, field_name, field_options, required, order_num, can_be_purged) VALUES (4, checkbox, customer, Type, Grade1,Grade2,Grade3, 0, 3, 0);
INSERT INTO `custom_fields` (cf_id, field_type, field_related_to, field_name, field_options, required, order_num, can_be_purged) VALUES (5, input, raw_material, Common Name, , 1, 0, 0);
INSERT INTO `custom_fields` (cf_id, field_type, field_related_to, field_name, field_options, required, order_num, can_be_purged) VALUES (6, input, supplier, Concern Name, , 1, 1, 0);
INSERT INTO `custom_fields` (cf_id, field_type, field_related_to, field_name, field_options, required, order_num, can_be_purged) VALUES (7, date, rfq, Created At, , 1, 1, 0);
INSERT INTO `custom_fields` (cf_id, field_type, field_related_to, field_name, field_options, required, order_num, can_be_purged) VALUES (8, radio, inventory_service, Provider, Inside,Outside, 1, 1, 0);


#
# TABLE STRUCTURE FOR: customer_billingaddr
#

DROP TABLE IF EXISTS `customer_billingaddr`;

CREATE TABLE `customer_billingaddr` (
  `billingaddr_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`billingaddr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer_billingaddr` (billingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (2, No.164, First Floor, Arcot Rd, Valasaravakkam, Chennai, Tamil Nadu, India, 600087, 37, 1706620472, 1);
INSERT INTO `customer_billingaddr` (billingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (4, t nagar, south street, 100 ft, Chennai, Tamil Nadu fdsf, India, 600089, 46, 1707904376, 1);
INSERT INTO `customer_billingaddr` (billingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (6, ABC street, Chennai, Tamil Nadu, India, 600089, 47, 1707914049, 1);
INSERT INTO `customer_billingaddr` (billingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (7, ABC street , Chennai, Tamil Nadu, India, 600089, 38, 1707915673, 1);
INSERT INTO `customer_billingaddr` (billingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (8, ABCD, Chennai, Tamil Nadu, India, 600089, 45, 1707916741, 1);
INSERT INTO `customer_billingaddr` (billingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (9, No.164, Walmon,south street, coimbator, Tamil Nadu, India, 600060, 48, 1708432182, 1);


#
# TABLE STRUCTURE FOR: customer_contacts
#

DROP TABLE IF EXISTS `customer_contacts`;

CREATE TABLE `customer_contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(140) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `position` varchar(140) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (1, Production, jacob, admin3@example.com, 9080780700, 0, Purchase Manager, 6, 1645002120, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (2, Production, john, admin2@example.com, 9080780700, 1, Purchase Manager, 6, 1645002166, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (3, Production, john, admin@example.com, 9080780700, 1, Purchase Manager, 6, 1645073995, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (4, QBS, Support, support@qbrainstorm.com, 9080780700, 1, werwer, 41, 1703066718, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (5, QBS, Support, support@qbrainstorm.com, 9080780700, 1, werwer, 41, 1703066783, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (6, Production, Thamizharasi, test@gmail.com, 1234567890, 1, web developer, 6, 1703827099, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (7, Production, Thamizh, test@gmail.com, 1234567890, 1, web developer, 7, 1704446817, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (10, QBS, Support, support@qbrainstorm.com, 9080780700, 1, DEV, 47, 1704958727, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (11, test, qbs, test1@gmail.com, 1234567890, 1, web dev, 45, 1706098930, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (12, jac, jacob, thamilarasi.v@qbrainstorm.com, 1234567890, 1, manager, 46, 1707470737, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (13, test, jac, jaccaj@gmail.com, 1234567890, 1, tet, 37, 1707470788, 1);
INSERT INTO `customer_contacts` (contact_id, firstname, lastname, email, phone, active, position, cust_id, created_at, created_by) VALUES (14, test, est, thamilarasi.v@qbrainstorm.com, 9080780700, 1, tadf, 37, 1707545493, 1);


#
# TABLE STRUCTURE FOR: customer_shippingaddr
#

DROP TABLE IF EXISTS `customer_shippingaddr`;

CREATE TABLE `customer_shippingaddr` (
  `shippingaddr_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`shippingaddr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer_shippingaddr` (shippingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (3, ABC street, Chennai, Tamil Nadu, India, 600084 , 37, 1645082553, 1);
INSERT INTO `customer_shippingaddr` (shippingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (10, chennai, Thiruvallur, tamilnadu, India, 602024, 6, 1703827150, 1);
INSERT INTO `customer_shippingaddr` (shippingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (11, Test Address, chennai, Tamil Nadu, India, 600087, 41, 1703828468, 1);
INSERT INTO `customer_shippingaddr` (shippingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (12, chennai, Thiruvallur, tamilnadu, India, 602024 , 46, 1704447100, 1);
INSERT INTO `customer_shippingaddr` (shippingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (18, No.164, First Floor, Arcot Rd, Valasaravakkam, chennai, Tamil Nadu, India, 600089, 47, 1707914049, 1);
INSERT INTO `customer_shippingaddr` (shippingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (19, No.164, First Floor, Arcot Rd, Valasaravakkam, chsdf, Tamil Nadu, India, 600089, 38, 1707915673, 1);
INSERT INTO `customer_shippingaddr` (shippingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (20, kesavardhini, chennai 09, Tamil Nadu, India, 6000890, 45, 1707916741, 1);
INSERT INTO `customer_shippingaddr` (shippingaddr_id, address, city, state, country, zipcode, cust_id, created_at, created_by) VALUES (21, Ek son , west street, coimbator, Tamil Nadu, India, 600089, 48, 1708432220, 1);


#
# TABLE STRUCTURE FOR: customers
#

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `cust_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(140) NOT NULL,
  `position` varchar(140) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `fax_num` varchar(11) NOT NULL,
  `office_num` varchar(11) NOT NULL,
  `company` varchar(255) NOT NULL,
  `gst` varchar(20) NOT NULL,
  `website` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customers` (cust_id, name, position, address, city, state, country, zip, email, phone, fax_num, office_num, company, gst, website, description, remarks, created_at, created_by) VALUES (37, admin, test, test, test, test, test, test, test@1gmail.com, 9876543210, , , test company1, 1234, , , , 1645095130, 1);
INSERT INTO `customers` (cust_id, name, position, address, city, state, country, zip, email, phone, fax_num, office_num, company, gst, website, description, remarks, created_at, created_by) VALUES (38, admin2, test, No.76, First Floor, test, test, test, test, test@2gmail.com, 9876543211, , , test company2, 1235, , , , 1645095130, 1);
INSERT INTO `customers` (cust_id, name, position, address, city, state, country, zip, email, phone, fax_num, office_num, company, gst, website, description, remarks, created_at, created_by) VALUES (45, john, web developer, No.190, First Floor, Thiruvallur, tamilnadu, India, 602024, udhayakumar@qbrainstorm.com , 1234567890, 1233554, 4654878554, qbsoftware company, 5754654466689898, , sdfghdf, gdfshsfd, 1704452307, 1);
INSERT INTO `customers` (cust_id, name, position, address, city, state, country, zip, email, phone, fax_num, office_num, company, gst, website, description, remarks, created_at, created_by) VALUES (46, jacob, Purchase Manager, No.178, First Floor, Thiruvallur, tamilnadu, India, 602024, softriders@gmail.com, 23256789, 65454546, 8765446, softriders Global Pvvt Limited, 535468564, , sdvdf, dfgsd, 1704452363, 1);
INSERT INTO `customers` (cust_id, name, position, address, city, state, country, zip, email, phone, fax_num, office_num, company, gst, website, description, remarks, created_at, created_by) VALUES (47, geroge, web developer, No.16, First Floor, Thiruvallur, tamilnadu, India, 602024, hrfksj@gmail.com, 8768797887, 896987687, 7984655868, Google, 87987897687, , drtytd, srthrdh, 1704452430, 1);
INSERT INTO `customers` (cust_id, name, position, address, city, state, country, zip, email, phone, fax_num, office_num, company, gst, website, description, remarks, created_at, created_by) VALUES (49, DEMO , test, No.164, First Floor, Chennai, Tamil Nadu, India, 600087, Newmail@gmail.com, 9865647121, 8886876, 9876565735, NEWEMAIL, 7, https://brito.com, Description done, Description done, 1714197989, 1);
INSERT INTO `customers` (cust_id, name, position, address, city, state, country, zip, email, phone, fax_num, office_num, company, gst, website, description, remarks, created_at, created_by) VALUES (50, TDWWS, test, Alvarpettai, Chennai, Tamil Nadu, India, 600087, Demo@test.com, 9564784521, , , GB BABA , , www.demo.com, demo , , 1731151111, 1);


#
# TABLE STRUCTURE FOR: deductions
#

DROP TABLE IF EXISTS `deductions`;

CREATE TABLE `deductions` (
  `deduct_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `value` decimal(14,2) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`deduct_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `deductions` (deduct_id, name, description, type, value, created_at, created_by) VALUES (1, T Tax, hello2, 0, 5.00, 1651037240, 1);
INSERT INTO `deductions` (deduct_id, name, description, type, value, created_at, created_by) VALUES (2, X Tax, , 1, 1000.00, 1651037261, 1);
INSERT INTO `deductions` (deduct_id, name, description, type, value, created_at, created_by) VALUES (3, QBS , dwsd, 0, 343.00, 1703163194, 1);


#
# TABLE STRUCTURE FOR: delivery_records
#

DROP TABLE IF EXISTS `delivery_records`;

CREATE TABLE `delivery_records` (
  `delivery_id` int(11) NOT NULL AUTO_INCREMENT,
  `transport_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`delivery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `delivery_records` (delivery_id, transport_id, related_to, related_id, status, type) VALUES (2, 2, purchase_order, 2, 2, 0);
INSERT INTO `delivery_records` (delivery_id, transport_id, related_to, related_id, status, type) VALUES (3, 1, purchase_order, 1, 2, 0);
INSERT INTO `delivery_records` (delivery_id, transport_id, related_to, related_id, status, type) VALUES (4, 6, purchase_order, 3, 2, 0);
INSERT INTO `delivery_records` (delivery_id, transport_id, related_to, related_id, status, type) VALUES (8, 7, purchase_order, 26, 2, 0);


#
# TABLE STRUCTURE FOR: department
#

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `department` (department_id, name, description, created_at, created_by) VALUES (1, Testing, Testing Product, 1648194570, 1);
INSERT INTO `department` (department_id, name, description, created_at, created_by) VALUES (3, Developing, Developing product
, 1648194606, 1);
INSERT INTO `department` (department_id, name, description, created_at, created_by) VALUES (4, Marketing, No Department of, 1648197404, 1);
INSERT INTO `department` (department_id, name, description, created_at, created_by) VALUES (5, Civil, Services Department, 1703144707, 1);
INSERT INTO `department` (department_id, name, description, created_at, created_by) VALUES (11, asas, aaa, 1715064476, 1);


#
# TABLE STRUCTURE FOR: designation
#

DROP TABLE IF EXISTS `designation`;

CREATE TABLE `designation` (
  `designation_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`designation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `designation` (designation_id, department_id, name, description, created_at, created_by) VALUES (1, 4, Executive, chief executive position 2, 1648199872, 1);
INSERT INTO `designation` (designation_id, department_id, name, description, created_at, created_by) VALUES (2, 3, Manager, Jobs, 1648200065, 1);
INSERT INTO `designation` (designation_id, department_id, name, description, created_at, created_by) VALUES (3, 3, Domain, , 1703148468, 1);


#
# TABLE STRUCTURE FOR: dispatch
#

DROP TABLE IF EXISTS `dispatch`;

CREATE TABLE `dispatch` (
  `dispatch_id` int(100) NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) NOT NULL,
  `warehouse` varchar(255) NOT NULL,
  `delivery_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `customer` varchar(255) NOT NULL,
  `suppiler_id` int(100) NOT NULL,
  `order_id` int(255) NOT NULL,
  `cust_id` int(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `pick_list_id` int(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  PRIMARY KEY (`dispatch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `dispatch` (dispatch_id, order_code, warehouse, delivery_date, customer, suppiler_id, order_id, cust_id, status, pick_list_id, description, created_at, updated_at) VALUES (16, ghdrfg5868796, , 2024-01-13 17:45:00, geroge, 0, 31, 47, 1, 0, asd, 1705140797, 1705140797);
INSERT INTO `dispatch` (dispatch_id, order_code, warehouse, delivery_date, customer, suppiler_id, order_id, cust_id, status, pick_list_id, description, created_at, updated_at) VALUES (19, ghdrfg5868796, , 0000-00-00 00:00:00, geroge, 0, 31, 47, 3, 0, xgsagr, 1705146617, 1705146617);
INSERT INTO `dispatch` (dispatch_id, order_code, warehouse, delivery_date, customer, suppiler_id, order_id, cust_id, status, pick_list_id, description, created_at, updated_at) VALUES (20, ghdrfg5868796, , 0000-00-00 00:00:00, geroge, 0, 31, 47, 0, 0, , 1705148902, 1705148902);
INSERT INTO `dispatch` (dispatch_id, order_code, warehouse, delivery_date, customer, suppiler_id, order_id, cust_id, status, pick_list_id, description, created_at, updated_at) VALUES (21, ghdrfg5868796, , 2024-01-24 20:45:00, geroge, 0, 31, 47, 1, 0, zdfhafh, 1705749070, 1705749070);
INSERT INTO `dispatch` (dispatch_id, order_code, warehouse, delivery_date, customer, suppiler_id, order_id, cust_id, status, pick_list_id, description, created_at, updated_at) VALUES (23, USAP234, , 2024-01-22 16:41:00, geroge, 0, 36, 47, 2, 0, gdfgdf, 1705907263, 1705907263);


#
# TABLE STRUCTURE FOR: emp_attendance
#

DROP TABLE IF EXISTS `emp_attendance`;

CREATE TABLE `emp_attendance` (
  `attend_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `rec_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `work_hours` tinyint(4) NOT NULL,
  `ot_hours` tinyint(4) NOT NULL,
  PRIMARY KEY (`attend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `emp_attendance` (attend_id, employee_id, rec_date, status, work_hours, ot_hours) VALUES (1, 1, 2022-04-01, 0, 8, 2);
INSERT INTO `emp_attendance` (attend_id, employee_id, rec_date, status, work_hours, ot_hours) VALUES (2, 2, 2022-04-01, 0, 12, 0);
INSERT INTO `emp_attendance` (attend_id, employee_id, rec_date, status, work_hours, ot_hours) VALUES (3, 13, 2024-01-01, 2, 12, 3);
INSERT INTO `emp_attendance` (attend_id, employee_id, rec_date, status, work_hours, ot_hours) VALUES (4, 13, 2022-01-02, 0, 12, 3);
INSERT INTO `emp_attendance` (attend_id, employee_id, rec_date, status, work_hours, ot_hours) VALUES (5, 2, 2022-04-02, 0, 12, 2);
INSERT INTO `emp_attendance` (attend_id, employee_id, rec_date, status, work_hours, ot_hours) VALUES (6, 3, 2022-04-02, 2, 0, 0);
INSERT INTO `emp_attendance` (attend_id, employee_id, rec_date, status, work_hours, ot_hours) VALUES (7, 13, 2024-01-09, 0, 1, 2);
INSERT INTO `emp_attendance` (attend_id, employee_id, rec_date, status, work_hours, ot_hours) VALUES (8, 13, 2024-01-19, 2, 8, 2);


#
# TABLE STRUCTURE FOR: employees
#

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_code` varchar(140) NOT NULL,
  `first_name` varchar(140) NOT NULL,
  `last_name` varchar(140) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT 0,
  `joining_date` date NOT NULL,
  `phone_no` varchar(13) NOT NULL,
  `mobile_no` varchar(13) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `marital_status` tinyint(1) NOT NULL DEFAULT 0,
  `blood_group` varchar(5) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `years_of_exp` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `w_hr_salary` decimal(14,2) NOT NULL,
  `ot_hr_salary` decimal(14,2) NOT NULL,
  `salary` decimal(14,2) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `employees` (employee_id, emp_code, first_name, last_name, designation_id, gender, joining_date, phone_no, mobile_no, email, date_of_birth, marital_status, blood_group, address, city, state, country, zipcode, qualification, years_of_exp, status, w_hr_salary, ot_hr_salary, salary, created_at, created_by) VALUES (13, EP03, test, test, 3, 1, 2024-01-10, 1234567890, , support@qbrainstorm.com, 2024-01-16, 0, O+, chennai, Thiruvallur, tamilnadu, India, 602024, bca, 2, 0, 5210000.00, 55440.00, 747441.00, 1704175462, 1);
INSERT INTO `employees` (employee_id, emp_code, first_name, last_name, designation_id, gender, joining_date, phone_no, mobile_no, email, date_of_birth, marital_status, blood_group, address, city, state, country, zipcode, qualification, years_of_exp, status, w_hr_salary, ot_hr_salary, salary, created_at, created_by) VALUES (14, EP01, Raju, Kumar, 1, 0, 2024-01-03, 9845612304, 456123789, rajukumar@gmail.com, 2024-01-03, 0, O+, Delete Address2, chennai2, Tamil Nadu, India, 600087, MSC, 12, 0, 78000.00, 68000.00, 58000.00, 1704179913, 1);
INSERT INTO `employees` (employee_id, emp_code, first_name, last_name, designation_id, gender, joining_date, phone_no, mobile_no, email, date_of_birth, marital_status, blood_group, address, city, state, country, zipcode, qualification, years_of_exp, status, w_hr_salary, ot_hr_salary, salary, created_at, created_by) VALUES (15, EP02, Raju, Kumar, 3, 1, 2024-01-05, 9845612302, , raju@gmail.com, 2024-01-03, 1, O+, Delete Address2, chennai2, Tamil Nadu, India, 600087, MSC, 12, 0, 78000.00, 68000.00, 58000.00, 1704197974, 1);
INSERT INTO `employees` (employee_id, emp_code, first_name, last_name, designation_id, gender, joining_date, phone_no, mobile_no, email, date_of_birth, marital_status, blood_group, address, city, state, country, zipcode, qualification, years_of_exp, status, w_hr_salary, ot_hr_salary, salary, created_at, created_by) VALUES (16, EMP005, QBS, Support, 3, 0, 2024-01-19, 09080780700, 9845612304, test@qbrainstorm.com, 2000-04-06, 0, O+, Test Address, chennai, Tamil Nadu, India, 600087, MSC, 12, 0, 78000.00, 68000.00, 58000.00, 1705663000, 1);


#
# TABLE STRUCTURE FOR: equipments
#

DROP TABLE IF EXISTS `equipments`;

CREATE TABLE `equipments` (
  `equip_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(140) NOT NULL,
  `code` varchar(140) NOT NULL,
  `model` varchar(140) NOT NULL,
  `maker` varchar(255) NOT NULL,
  `bought_date` date NOT NULL,
  `age` varchar(140) NOT NULL,
  `work_type` varchar(50) NOT NULL,
  `consump_type` varchar(50) NOT NULL,
  `consumption` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  PRIMARY KEY (`equip_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `equipments` (equip_id, name, code, model, maker, bought_date, age, work_type, consump_type, consumption, status, description, created_at, created_by, updated_at) VALUES (1, test, EQ123, ABC-0001, test, 2022-03-27, , Manual, Electric, , 0, hello, 1650267326, 1);
INSERT INTO `equipments` (equip_id, name, code, model, maker, bought_date, age, work_type, consump_type, consumption, status, description, created_at, created_by, updated_at) VALUES (4, test, EQ125, ABC-0069, test, 2022-04-02, , Manual, Fuel, 4, 2, hello, 1650267407, 1);


#
# TABLE STRUCTURE FOR: erp_companyinfo
#

DROP TABLE IF EXISTS `erp_companyinfo`;

CREATE TABLE `erp_companyinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(50) NOT NULL,
  `company_logo` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `vat_number` varchar(255) NOT NULL,
  `license_number` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_companyinfo` (id, company_name, company_logo, address, city, state, country, zipcode, phone_number, vat_number, license_number, created_at, updated_at) VALUES (1, Q Brainstorm Software, 1707110052_b4b496e1d7ddda887343.png, No.164, First Floor, Arcot Rd,  Valasaravakkam, chennai, Tamil Nadu , India, 600089, 9080780700, 1234567891, #12345 , 2024-02-03 12:55:33, 2024-02-03 12:55:33);


#
# TABLE STRUCTURE FOR: erp_expenses_categories
#

DROP TABLE IF EXISTS `erp_expenses_categories`;

CREATE TABLE `erp_expenses_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `dateadded` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_expenses_categories` (id, name, description, dateadded) VALUES (1, Travel, Demo, 2024-06-03);
INSERT INTO `erp_expenses_categories` (id, name, description, dateadded) VALUES (2, Rent, Demo_1, 2024-06-03);
INSERT INTO `erp_expenses_categories` (id, name, description, dateadded) VALUES (3, Product, , 2024-06-03);
INSERT INTO `erp_expenses_categories` (id, name, description, dateadded) VALUES (8, Purchase, , 2024-06-03);
INSERT INTO `erp_expenses_categories` (id, name, description, dateadded) VALUES (9, data, , 2024-06-03);


#
# TABLE STRUCTURE FOR: erp_goals
#

DROP TABLE IF EXISTS `erp_goals`;

CREATE TABLE `erp_goals` (
  `goals_id` int(200) NOT NULL AUTO_INCREMENT,
  `subject` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `goal_type` int(11) NOT NULL,
  `contract_type` int(11) NOT NULL,
  `achievement` int(11) NOT NULL,
  `notify_when_fail` int(11) NOT NULL,
  `notify_when_achieve` int(11) NOT NULL,
  `notified` int(11) NOT NULL DEFAULT 0,
  `staff_id` int(11) NOT NULL,
  `dateadded` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`goals_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_goals` (goals_id, subject, description, start_date, end_date, goal_type, contract_type, achievement, notify_when_fail, notify_when_achieve, notified, staff_id, dateadded) VALUES (6, New Goals with staff, sadsad, 2024-04-02, 2024-11-09, 2, 0, 2, 1, 1, 1, 1, 2024-06-14);
INSERT INTO `erp_goals` (goals_id, subject, description, start_date, end_date, goal_type, contract_type, achievement, notify_when_fail, notify_when_achieve, notified, staff_id, dateadded) VALUES (9, Second Goals, test, 2024-06-14, 2024-06-22, 1, 0, 0, 1, 1, 0, 1, 2024-06-14);
INSERT INTO `erp_goals` (goals_id, subject, description, start_date, end_date, goal_type, contract_type, achievement, notify_when_fail, notify_when_achieve, notified, staff_id, dateadded) VALUES (10, test, test, 2024-02-29, 2024-11-30, 2, 0, 5, 0, 0, 0, 0, 2024-11-11);


#
# TABLE STRUCTURE FOR: erp_groups
#

DROP TABLE IF EXISTS `erp_groups`;

CREATE TABLE `erp_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  `related_to` varchar(100) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (1, Construction, customer);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (4, International, customer);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (5, VIP, customer);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (8, Domestic, customer);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (9, Grade A, raw_material);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (10, Grade B, raw_material);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (11, Grade A, semi_finished);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (12, Grade B, semi_finished);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (13, Grade A, finished_good);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (14, Grade B, finished_good);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (15, Domestic, expense);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (16, Supplier G1, supplier);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (17, Supplier G2, supplier);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (19, Serv B, inventory_service);
INSERT INTO `erp_groups` (group_id, group_name, related_to) VALUES (23, test, supplier);


#
# TABLE STRUCTURE FOR: erp_groups_map
#

DROP TABLE IF EXISTS `erp_groups_map`;

CREATE TABLE `erp_groups_map` (
  `groupmap_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `related_id` int(11) NOT NULL,
  PRIMARY KEY (`groupmap_id`)
) ENGINE=InnoDB AUTO_INCREMENT=344 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (10, 4, 7);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (19, 4, 8);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (20, 5, 8);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (21, 5, 9);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (22, 8, 9);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (23, 5, 10);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (26, 4, 12);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (91, 5, 38);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (92, 4, 38);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (101, 16, 6);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (102, 17, 7);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (103, 16, 7);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (127, 17, 20);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (128, 16, 21);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (130, 1, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (131, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (132, 234234, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (133, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (134, 234324, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (135, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (136, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (137, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (138, 2147483647, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (139, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (140, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (141, 600087, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (142, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (143, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (144, 0, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (145, 234324, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (146, 3423, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (147, 23434, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (148, 1, 24);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (149, 1, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (150, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (151, 234234, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (152, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (153, 234324, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (154, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (155, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (156, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (157, 2147483647, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (158, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (159, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (160, 600087, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (161, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (162, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (163, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (164, 234324, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (165, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (166, 0, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (167, 1, 25);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (168, 1, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (169, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (170, 234234, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (171, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (172, 234324, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (173, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (174, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (175, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (176, 2147483647, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (177, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (178, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (179, 600087, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (180, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (181, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (182, 0, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (183, 234324, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (184, 234234, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (185, 234234, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (186, 1, 26);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (187, 1, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (188, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (189, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (190, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (191, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (192, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (193, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (194, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (195, 908078070, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (196, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (197, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (198, 600087, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (199, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (200, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (201, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (202, 234324, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (203, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (204, 0, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (205, 1, 27);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (206, 16, 3);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (207, 1, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (208, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (209, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (210, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (211, 345345, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (212, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (213, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (214, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (215, 2147483647, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (216, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (217, 345345, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (218, 600087, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (219, 345345, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (220, 345345, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (221, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (222, 345345, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (223, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (224, 0, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (225, 1, 28);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (226, 1, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (227, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (228, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (229, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (230, 345345, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (231, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (232, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (233, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (234, 2147483647, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (235, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (236, 345345, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (237, 600087, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (238, 345345, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (239, 345345, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (240, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (241, 345345, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (242, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (243, 0, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (244, 1, 29);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (245, 1, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (246, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (247, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (248, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (249, 345345, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (250, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (251, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (252, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (253, 2147483647, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (254, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (255, 345345, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (256, 600087, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (257, 345345, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (258, 345345, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (259, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (260, 345345, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (261, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (262, 0, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (263, 1, 30);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (264, 17, 31);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (265, 1, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (266, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (267, 456456, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (268, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (269, 456456, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (270, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (271, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (272, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (273, 2147483647, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (274, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (275, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (276, 600087, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (277, 4456456, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (278, 456456, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (279, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (280, 456456, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (281, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (282, 0, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (283, 1, 32);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (296, 16, 39);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (297, 17, 39);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (298, 17, 40);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (299, 16, 40);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (300, 17, 41);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (301, 16, 42);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (302, 17, 43);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (303, 17, 44);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (304, 17, 45);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (305, 5, 41);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (306, 8, 43);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (307, 5, 43);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (308, 4, 43);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (309, 1, 43);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (310, 8, 44);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (311, 5, 44);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (312, 4, 44);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (313, 1, 44);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (316, 5, 11);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (317, 17, 46);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (318, 5, 6);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (319, 8, 6);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (320, 16, 47);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (321, 16, 48);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (322, 17, 49);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (323, 23, 50);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (324, 16, 51);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (325, 16, 52);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (326, 17, 1);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (327, 17, 2);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (331, 4, 46);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (332, 8, 47);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (334, 17, 5);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (335, 5, 37);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (336, 4, 37);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (337, 1, 37);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (338, 4, 45);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (339, 8, 45);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (340, 5, 48);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (341, 5, 49);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (342, 23, 4);
INSERT INTO `erp_groups_map` (groupmap_id, group_id, related_id) VALUES (343, 5, 50);


#
# TABLE STRUCTURE FOR: erp_jobqueue
#

DROP TABLE IF EXISTS `erp_jobqueue`;

CREATE TABLE `erp_jobqueue` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_name` varchar(255) NOT NULL,
  `job_params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `attempt` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `priority` tinyint(4) NOT NULL DEFAULT 0,
  `run_at` varchar(20) NOT NULL,
  `system` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (1, notify, {"notify_id":20}, 3, 5, 3, 1702256760, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (2, notify, {"notify_id":21}, 3, 5, 3, 1702576980, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (3, notify, {"notify_id":22}, 3, 5, 3, 1703074140, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (4, notify, {"notify_id":23}, 3, 5, 3, 1703074620, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (5, notify, {"notify_id":24}, 3, 5, 3, 1703082360, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (6, notify, {"notify_id":25}, 3, 5, 3, 1703428380, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (9, notify, {"notify_id":28}, 3, 5, 3, 1703087460, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (10, notify, {"notify_id":29}, 3, 5, 3, 1703524560, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (11, notify, {"notify_id":30}, 3, 5, 3, 1703524560, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (12, notify, {"notify_id":31}, 3, 5, 3, 1703591520, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (13, notify, {"notify_id":32}, 3, 5, 3, 1703594340, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (14, notify, {"notify_id":33}, 3, 5, 3, 1703594340, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (15, notify, {"notify_id":34}, 3, 5, 3, 1703594340, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (16, notify, {"notify_id":35}, 3, 5, 3, 1703594340, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (17, notify, {"notify_id":36}, 3, 5, 3, 1703595780, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (18, notify, {"notify_id":37}, 3, 5, 3, 1703509920, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (22, notify, {"notify_id":"19"}, 3, 5, 3, 1651901160, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (23, notify, {"notify_id":40}, 3, 5, 3, 1703714040, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (28, notify, {"notify_id":45}, 3, 5, 3, 1701724080, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (29, notify, {"notify_id":46}, 3, 5, 3, 1703195460, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (32, rfqsend, {"supp_rfq_id":"5"}, 3, 5, 3, 1703673627, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (34, rfqsend, {"supp_rfq_id":"5"}, 3, 5, 3, 1703677637, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (40, notify, {"notify_id":"15"}, 3, 5, 3, 1651931400, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (41, notify, {"notify_id":"49"}, 3, 5, 3, 1703782500, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (42, notify, {"notify_id":54}, 3, 5, 3, 1703871840, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (44, notify, {"notify_id":56}, 3, 5, 3, 1703891220, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (45, notify, {"notify_id":57}, 3, 5, 3, 1702501560, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (46, notify, {"notify_id":58}, 3, 5, 3, 1702328760, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (49, notify, {"notify_id":"59"}, 3, 5, 3, 1703891580, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (50, notify, {"notify_id":60}, 3, 5, 3, 1703200800, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (51, notify, {"notify_id":61}, 3, 5, 3, 1703719740, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (53, notify, {"notify_id":63}, 3, 5, 3, 1703547120, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (54, notify, {"notify_id":64}, 3, 5, 3, 1703111520, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (56, notify, {"notify_id":65}, 3, 5, 3, 1703759940, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (57, notify, {"notify_id":66}, 3, 5, 3, 1703846400, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (61, notify, {"notify_id":68}, 3, 5, 3, 1703934480, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (62, notify, {"notify_id":69}, 3, 5, 3, 1703934600, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (63, notify, {"notify_id":70}, 3, 5, 3, 1704021360, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (65, notify, {"notify_id":"71"}, 3, 5, 3, 1703848800, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (67, notify, {"notify_id":73}, 3, 5, 3, 1703935380, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (69, notify, {"notify_id":74}, 3, 5, 3, 1703936160, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (70, notify, {"notify_id":75}, 3, 5, 3, 1703849760, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (72, notify, {"notify_id":"76"}, 3, 5, 3, 1703849880, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (73, notify, {"notify_id":77}, 3, 5, 3, 1703997600, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (77, notify, {"notify_id":80}, 3, 5, 3, 1701724080, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (80, notify, {"notify_id":"81"}, 3, 5, 3, 1703851500, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (81, notify, {"notify_id":82}, 3, 5, 3, 1703701020, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (82, notify, {"notify_id":83}, 3, 5, 3, 1703873880, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (83, notify, {"notify_id":84}, 3, 5, 3, 1703877480, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (84, notify, {"notify_id":85}, 3, 5, 3, 1703960400, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (85, notify, {"notify_id":86}, 3, 5, 3, 1703704860, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (86, notify, {"notify_id":87}, 3, 5, 3, 1703946960, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (87, notify, {"notify_id":88}, 3, 5, 3, 1703947620, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (88, notify, {"notify_id":89}, 3, 5, 3, 1703948040, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (89, notify, {"notify_id":90}, 3, 5, 3, 1703948460, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (90, notify, {"notify_id":91}, 3, 5, 3, 1703948460, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (91, notify, {"notify_id":92}, 3, 5, 3, 1703948520, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (92, notify, {"notify_id":93}, 3, 5, 3, 1703948580, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (93, notify, {"notify_id":94}, 3, 5, 3, 1703948640, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (94, notify, {"notify_id":95}, 3, 5, 3, 1703950320, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (95, notify, {"notify_id":96}, 3, 5, 3, 1703869680, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (96, rfqsend, {"supp_rfq_id":"14"}, 3, 5, 3, 1704172781, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (97, rfqsend, {"supp_rfq_id":"14"}, 3, 5, 3, 1704173222, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (98, rfqsend, {"supp_rfq_id":"14"}, 3, 5, 3, 1704173395, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (99, notify, {"notify_id":97}, 3, 5, 3, 1704196260, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (100, rfqsend, {"supp_rfq_id":"14"}, 3, 5, 3, 1704188744, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (102, notify, {"notify_id":99}, 3, 5, 3, 1704265140, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (103, notify, {"notify_id":100}, 3, 5, 3, 1704378240, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (104, notify, {"notify_id":101}, 3, 5, 3, 1704470760, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (105, notify, {"notify_id":102}, 3, 5, 3, 1704986580, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (106, notify, {"notify_id":103}, 3, 5, 3, 1704986580, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (107, notify, {"notify_id":104}, 3, 5, 3, 1704904380, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (108, notify, {"notify_id":105}, 3, 5, 3, 1705166940, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (109, notify, {"notify_id":106}, 3, 5, 3, 1704477000, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (113, notify, {"notify_id":"107"}, 3, 5, 3, 1704550260, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (114, rfqsend, {"supp_rfq_id":"1"}, 3, 5, 3, 1704777800, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (116, notify, {"notify_id":111}, 3, 5, 3, 1704847200, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (117, notify, {"notify_id":112}, 3, 5, 3, 1704937380, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (119, notify, {"notify_id":114}, 3, 5, 3, 1705583460, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (120, rfqsend, {"supp_rfq_id":"1"}, 3, 5, 3, 1704970930, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (121, rfqsend, {"supp_rfq_id":"1"}, 3, 5, 3, 1705057291, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (122, notify, {"notify_id":115}, 3, 5, 3, 1705573680, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (123, rfqsend, {"supp_rfq_id":"1"}, 3, 5, 3, 1705743775, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (124, rfqsend, {"supp_rfq_id":"1"}, 3, 5, 3, 1705743787, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (129, notify, {"notify_id":118}, 3, 5, 3, 1706129040, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (130, notify, {"notify_id":119}, 3, 5, 3, 1706028540, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (131, notify, {"notify_id":120}, 3, 5, 3, 1706119920, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (132, notify, {"notify_id":121}, 3, 5, 3, 1706206380, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (134, notify, {"notify_id":123}, 3, 5, 3, 1706119440, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (135, notify, {"notify_id":"122"}, 3, 5, 3, 1706205420, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (136, notify, {"notify_id":124}, 3, 5, 3, 1706184420, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (137, notify, {"notify_id":125}, 3, 5, 3, 1706621100, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (144, notify, {"notify_id":"5"}, 3, 5, 3, 1711475820, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (145, notify, {"notify_id":6}, 3, 5, 3, 1711480020, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (146, notify, {"notify_id":15}, 3, 5, 3, 1718887260, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (147, notify, {"notify_id":16}, 3, 5, 3, 1718887500, 0);
INSERT INTO `erp_jobqueue` (job_id, job_name, job_params, attempt, status, priority, run_at, system) VALUES (148, rfqsend, {"supp_rfq_id":"0"}, 3, 5, 3, 1729233199, 0);


#
# TABLE STRUCTURE FOR: erp_log
#

DROP TABLE IF EXISTS `erp_log`;

CREATE TABLE `erp_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) NOT NULL,
  `log_text` text NOT NULL,
  `ref_link` varchar(255) NOT NULL,
  `additional_info` varchar(1000) DEFAULT NULL,
  `done_by` varchar(120) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3080 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2600, Logout, [ User successfully logout ], , , Qbs , 2024-02-05 07:11:19);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2601, Login, [ User successfully logged in ], , , Qbs , 2024-02-05 07:11:25);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2602, Login, [ User successfully logged in ], , , Qbs , 2024-02-05 12:04:13);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2603, Estimate Update, [ Estimate successfully updated ], erp/sale/estimateview/41, , Qbs , 2024-02-05 12:48:27);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2604, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/10, , Qbs , 2024-02-05 12:59:37);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2605, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/10, , Qbs , 2024-02-05 12:59:37);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2606, Estimate Insert, [ Estimate successfully created ], erp/sale/estimateview/44, , Qbs , 2024-02-05 13:27:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2607, Estimate Insert, [ Estimate successfully created ], erp/sale/estimateview/49, , Qbs , 2024-02-05 13:49:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2608, Login, [ User successfully logged in ], , , Qbs , 2024-02-06 05:16:21);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2609, Estimate Deletion, [ Estimate successfully deleted ], erp/sale/delete_estimate/42, {"estimate_id":"42","code":"e-2","estimate_date":"2024-01-22","terms_condition":"fsf","name":"jacob","cust_id":"46","shippingaddr_id":"0"}, Qbs , 2024-02-06 05:17:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2610, Estimate Insert, [ Estimate successfully created ], erp/sale/estimateview/50, , Qbs , 2024-02-06 05:25:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2611, Estimate Update, [ Estimate successfully updated ], erp/sale/estimateview/50, , Qbs , 2024-02-06 05:29:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2612, Estimate Update, [ Estimate successfully updated ], erp/sale/estimateview/50, , Qbs , 2024-02-06 05:29:57);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2613, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/11, , Qbs , 2024-02-06 05:44:16);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2614, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/13, , Qbs , 2024-02-06 05:52:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2615, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/14, , Qbs , 2024-02-06 06:01:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2616, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/1, , Qbs , 2024-02-06 06:03:11);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2617, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/2, , Qbs , 2024-02-06 06:04:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2618, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-02-06 06:06:55);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2619, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/3, , Qbs , 2024-02-06 06:09:21);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2620, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/4, , Qbs , 2024-02-06 06:11:57);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2621, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/2, , Qbs , 2024-02-06 06:14:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2622, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/2, , Qbs , 2024-02-06 06:18:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2623, Sale Order Insert, [ Sale Order successfully created ], erp/sale/orderview/1, , Qbs , 2024-02-06 06:46:32);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2624, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 06:53:19);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2625, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 06:54:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2626, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 06:55:17);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2627, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 06:55:34);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2628, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 06:55:49);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2629, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/75, , Qbs , 2024-02-06 07:37:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2630, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/1, , Qbs , 2024-02-06 07:38:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2631, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/1, , Qbs , 2024-02-06 07:49:51);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2632, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/1, , Qbs , 2024-02-06 07:51:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2633, Credit Note Insert, [ Credit Note successfully created ], http://localhost/erpcinew/public/erp/sales/credit_notes, , Qbs , 2024-02-06 08:05:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2634, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 08:48:50);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2635, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 08:49:09);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2636, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 08:49:57);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2637, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/4, , Qbs , 2024-02-06 08:57:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2638, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 08:57:33);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2639, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 09:22:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2640, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 09:31:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2641, Sale Order Update, [ Sale Order failed to update ], erp/sale/orderview/1, , Qbs , 2024-02-06 09:36:43);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2642, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/1, , Qbs , 2024-02-06 09:39:53);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2643, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/1, , Qbs , 2024-02-06 09:42:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2644, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/1, , Qbs , 2024-02-06 09:43:31);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2645, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/2, , Qbs , 2024-02-06 09:45:54);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2646, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/1, , Qbs , 2024-02-06 09:46:41);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2647, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/2, , Qbs , 2024-02-06 09:47:45);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2648, Credit Note Update, [ Credit Note successfully updated ], http://localhost/erpcinew/public/erp/sales/credit_notes, , Qbs , 2024-02-06 09:49:21);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2649, Credit Note Update, [ Credit Note successfully updated ], http://localhost/erpcinew/public/erp/sales/credit_notes, , Qbs , 2024-02-06 09:49:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2650, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/1, , Qbs , 2024-02-06 09:55:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2651, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/2, , Qbs , 2024-02-06 09:55:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2652, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/1, , Qbs , 2024-02-06 10:14:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2653, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/1, , Qbs , 2024-02-06 10:18:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2654, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/2, , Qbs , 2024-02-06 10:42:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2655, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/1, , Qbs , 2024-02-06 10:42:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2656, Credit Note Insert, [ Credit Note successfully created ], http://localhost/erpcinew/public/erp/sales/credit_notes, , Qbs , 2024-02-06 10:57:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2657, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/3, , Qbs , 2024-02-06 11:14:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2658, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/3, , Qbs , 2024-02-06 11:15:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2659, Credit Note Update, [ Credit Note successfully updated ], http://localhost/erpcinew/public/erp/sales/credit_notes, , Qbs , 2024-02-06 11:15:50);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2660, Credit Note Update, [ Credit Note successfully updated ], http://localhost/erpcinew/public/erp/sales/credit_notes, , Qbs , 2024-02-06 11:39:42);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2661, Sale Payment Update, [ Sale Payment successfully updated ], erp/sale/manage_view/3, , Qbs , 2024-02-06 12:16:29);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2662, Sale Payment Update, [ Sale Payment successfully updated ], erp/sale/manage_view/3, , Qbs , 2024-02-06 12:26:09);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2663, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/4, , Qbs , 2024-02-06 12:27:02);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2664, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/4, , Qbs , 2024-02-06 12:38:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2665, Sale Payment Delete, [ Sale Payment successfully deleted ], erp/sale/manage_view/4, , Qbs , 2024-02-06 12:40:46);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2666, Credit Note Update, [ Credit Note successfully updated ], http://localhost/erpcinew/public/erp/sales/credit_notes, , Qbs , 2024-02-06 12:42:51);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2667, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/5, , Qbs , 2024-02-06 13:28:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2668, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/5, , Qbs , 2024-02-06 13:29:04);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2669, Login, [ User successfully logged in ], , , Qbs , 2024-02-08 04:42:11);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2670, Sale Order Insert, [ Sale Order successfully created ], erp/sale/orderview/2, , Qbs , 2024-02-08 04:43:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2671, Sale Order Insert, [ Sale Order successfully created ], erp/sale/orderview/3, , Qbs , 2024-02-08 04:44:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2672, Sale Order Insert, [ Sale Order successfully created ], erp/sale/orderview/4, , Qbs , 2024-02-08 05:47:10);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2673, Sale Order Insert, [ Sale Order successfully created ], erp/sale/orderview/5, , Qbs , 2024-02-08 05:47:51);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2674, Sale Order Insert, [ Sale Order successfully created ], erp/sale/orderview/6, , Qbs , 2024-02-08 05:48:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2675, Login, [ User successfully logged in ], , , Qbs , 2024-02-09 05:06:42);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2676, Login, [ User successfully logged in ], , , Qbs , 2024-02-09 05:08:50);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2677, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/6, , Qbs , 2024-02-09 05:09:29);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2678, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/7, , Qbs , 2024-02-09 05:38:42);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2679, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/7, , Qbs , 2024-02-09 05:43:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2680, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/7, , Qbs , 2024-02-09 05:46:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2681, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/6, , Qbs , 2024-02-09 05:46:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2682, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/5, , Qbs , 2024-02-09 05:50:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2683, Login, [ User successfully logged in ], , , Qbs , 2024-02-09 08:59:49);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2684, Customer Contact Insert, [ Customer Contact successfully created ], http://localhost/erpcinew/public/erp/crm/lead-customer-view/46, , Qbs , 2024-02-09 09:25:37);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2685, Customer Contact Insert, [ Customer Contact successfully created ], http://localhost/erpcinew/public/erp/crm/lead-customer-view/46, , Qbs , 2024-02-09 09:26:28);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2686, Customer Contact Update, [ Customer Contact successfully updated ], http://localhost/erpcinew/public/erp/crm/lead-customer-view/46, , Qbs , 2024-02-09 12:40:33);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2687, Login, [ User successfully logged in ], , , Qbs , 2024-02-10 04:53:04);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2688, Customer Contact Insert, [ Customer Contact successfully created ], http://localhost/erpcinew/public/erp/crm/lead-customer-view/37, , Qbs , 2024-02-10 06:11:33);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2689, Login, [ User successfully logged in ], , , Qbs , 2024-02-10 12:59:01);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2690, Login, [ User successfully logged in ], , , Qbs , 2024-02-12 05:47:38);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2691, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/5, , Qbs , 2024-02-12 06:27:25);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2692, Login, [ User successfully logged in ], , , Qbs , 2024-02-12 10:27:53);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2693, Login, [ User successfully logged in ], , , Qbs , 2024-02-12 12:22:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2694, Customer Shipping Address Delete, [ Customer Shipping Address successfully deleted ], /erp/crm/lead-customer-view/37, , Qbs , 2024-02-12 13:37:46);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2695, Customer Shipping Address Delete, [ Customer Shipping Address successfully deleted ], /erp/crm/lead-customer-view/37, , Qbs , 2024-02-12 13:37:55);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2696, Login, [ User successfully logged in ], , , Qbs , 2024-02-13 04:47:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2697, Login, [ User successfully logged in ], , , Qbs , 2024-02-13 10:24:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2698, Login, [ User successfully logged in ], , , Qbs , 2024-02-13 11:13:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2699, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/7, , Qbs , 2024-02-13 13:09:11);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2700, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/7, , Qbs , 2024-02-13 13:09:28);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2701, Login, [ User successfully logged in ], , , Qbs , 2024-02-14 05:07:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2702, Login, [ User successfully logged in ], , , Qbs , 2024-02-14 05:58:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2703, Login, [ User successfully logged in ], , , Qbs , 2024-02-14 07:48:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2704, Customer Billing Address Insert, [ Customer Billing Address successfully created ], http://192.168.29.9/erpcinew/public/erp/crm/lead-customer-view/46, , Qbs , 2024-02-14 09:52:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2705, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/5, , Qbs , 2024-02-14 12:45:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2706, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/5, , Qbs , 2024-02-14 12:45:57);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2707, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/5, , Qbs , 2024-02-14 12:47:02);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2708, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/5, , Qbs , 2024-02-14 12:47:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2709, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/5, , Qbs , 2024-02-14 12:47:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2710, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/6, , Qbs , 2024-02-14 12:47:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2711, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/8, , Qbs , 2024-02-14 13:03:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2712, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/8, , Qbs , 2024-02-14 13:04:28);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2713, Estimate Insert, [ Estimate successfully created ], erp/sale/estimateview/52, , Qbs , 2024-02-14 13:06:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2714, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/7, , Qbs , 2024-02-14 13:19:10);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2715, Estimate Insert, [ Estimate successfully created ], erp/sale/estimateview/53, , Qbs , 2024-02-14 13:24:53);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2716, Estimate Update, [ Estimate successfully updated ], erp/sale/estimateview/52, , Qbs , 2024-02-14 13:31:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2717, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/6, , Qbs , 2024-02-14 13:36:23);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2718, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/6, , Qbs , 2024-02-14 13:36:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2719, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/6, , Qbs , 2024-02-14 13:37:17);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2720, Sale Order Insert, [ Sale Order successfully created ], erp/sale/orderview/7, , Qbs , 2024-02-14 13:41:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2721, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/7, , Qbs , 2024-02-14 13:41:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2722, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/6, , Qbs , 2024-02-14 13:42:11);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2723, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/5, , Qbs , 2024-02-14 13:42:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2724, Login, [ User successfully logged in ], , , Qbs , 2024-02-15 04:36:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2725, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/8, , Qbs , 2024-02-15 06:57:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2726, Estimate Insert, [ Estimate successfully created ], erp/sale/estimateview/54, , Qbs , 2024-02-15 09:34:31);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2727, Estimate Update, [ Estimate successfully updated ], erp/sale/estimateview/54, , Qbs , 2024-02-15 09:35:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2728, Estimate Update, [ Estimate successfully updated ], erp/sale/estimateview/54, , Qbs , 2024-02-15 10:09:28);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2729, Estimate Update, [ Estimate successfully updated ], erp/sale/estimateview/49, , Qbs , 2024-02-15 10:39:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2730, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/7, , Qbs , 2024-02-15 11:06:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2731, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/7, , Qbs , 2024-02-15 11:53:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2732, Sale Order Insert, [ Sale Order successfully created ], erp/sale/orderview/8, , Qbs , 2024-02-15 12:16:17);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2733, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/8, , Qbs , 2024-02-15 12:23:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2734, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/8, , Qbs , 2024-02-15 12:24:32);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2735, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/5, , Qbs , 2024-02-15 12:33:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2736, Sale Order Update, [ Sale Order successfully updated ], erp/sale/orderview/5, , Qbs , 2024-02-15 12:33:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2737, Login, [ User successfully logged in ], , , Qbs , 2024-02-16 04:58:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2738, Login, [ User successfully logged in ], , , Qbs , 2024-02-17 04:56:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2739, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-17 05:26:03);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2740, Currency Delete, [ Currency successfully deleted ], erp/finance/journalentry/, , Qbs , 2024-02-17 05:26:41);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2741, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/5, , Qbs , 2024-02-17 07:30:19);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2742, Login, [ User successfully logged in ], , , Qbs , 2024-02-19 05:30:53);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2743, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/8, , Qbs , 2024-02-19 07:38:22);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2744, Quotation Deletion, [ Quotation successfully deleted ], erp.sale.quotation.delete8, {"quote_id":"8","code":"PRO-008","subject":"tes","expiry_date":"2024-02-27","quote_date":"2024-02-19","cust_id":"45","shippingaddr_id":"0","currency_id":"8","currency_place":"","transport_req":"0","trans_charge":"0.00","discount":"0.00","terms_condition":"<p>resrr<\/p>","payment_terms":"30 day","status":"0","created_at":"1708328301","created_by":"1"}, Qbs , 2024-02-19 07:41:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2745, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/20, , Qbs , 2024-02-19 09:44:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2746, Quotation Insert, [ Quotation failed to create ], erp.sale.quotation.add, , Qbs , 2024-02-19 09:51:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2747, Quotation Insert, [ Quotation failed to create ], erp.sale.quotation.add, , Qbs , 2024-02-19 09:57:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2748, Quotation Insert, [ Quotation failed to create ], erp.sale.quotation.add, , Qbs , 2024-02-19 10:00:37);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2749, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/29, , Qbs , 2024-02-19 11:17:49);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2750, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/29, , Qbs , 2024-02-19 12:55:25);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2751, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/29, , Qbs , 2024-02-19 13:11:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2752, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/29, , Qbs , 2024-02-19 13:14:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2753, Login, [ User successfully logged in ], , , Qbs , 2024-02-20 04:57:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2754, Currency Insert, [ Currency successfully created ], erp/finance/currency/, , Qbs , 2024-02-20 10:27:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2755, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-20 10:28:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2756, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/29, , Qbs , 2024-02-20 10:29:27);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2757, Quotation Deletion, [ Quotation successfully deleted ], erp.sale.quotation.delete29, {"quote_id":"29","code":"PRO-021","subject":"test","expiry_date":"2024-02-27","quote_date":"2024-02-19","cust_id":"46","shippingaddr_id":"0","billingaddr_id":"0","currency_id":"9","currency_place":"after","transport_req":"0","trans_charge":"0.00","discount":"1.00","terms_condition":"<p>fsd<\/p>","payment_terms":"sdf","status":"0","created_at":"1708341469","created_by":"1"}, Qbs , 2024-02-20 10:29:42);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2758, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/30, , Qbs , 2024-02-20 10:30:50);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2759, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/30, , Qbs , 2024-02-20 10:33:13);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2760, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/31, , Qbs , 2024-02-20 10:34:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2761, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/1, , Qbs , 2024-02-20 10:35:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2762, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-02-20 10:36:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2763, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-02-20 10:36:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2764, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-02-20 10:36:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2765, Currency Update, [ Currency failed to update ], , , Qbs , 2024-02-20 10:39:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2766, Currency Update, [ Currency failed to update ], , , Qbs , 2024-02-20 10:39:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2767, Currency Update, [ Currency failed to update ], , , Qbs , 2024-02-20 10:39:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2768, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-20 10:40:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2769, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-02-20 10:40:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2770, Customer Billing Address Insert, [ Customer Billing Address successfully created ], http://192.168.29.9/erpcinew/public/erp/crm/lead-customer-view/48, , Qbs , 2024-02-20 12:29:42);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2771, Customer Shipping Address Insert, [ Customer Shipping Address successfully created ], http://192.168.29.9/erpcinew/public/erp/crm/lead-customer-view/48, , Qbs , 2024-02-20 12:30:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2772, Login, [ User successfully logged in ], , , Qbs , 2024-02-21 04:59:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2773, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-21 06:47:02);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2774, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-21 06:47:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2775, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-21 06:48:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2776, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-21 06:49:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2777, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-21 06:49:50);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2778, Currency Delete, [ Currency successfully deleted ], erp/finance/journalentry/, , Qbs , 2024-02-21 06:55:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2779, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-21 06:56:11);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2780, Currency Update, [ Currency successfully updated ], erp/finance/currency/, , Qbs , 2024-02-21 06:56:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2781, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-02-21 07:14:27);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2782, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/9, , Qbs , 2024-02-21 07:15:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2783, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/10, , Qbs , 2024-02-21 07:43:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2784, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/10, , Qbs , 2024-02-21 08:40:57);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2785, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/10, , Qbs , 2024-02-21 08:42:45);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2786, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-02-21 08:46:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2787, warehouse Deletion, [ Warehouse successfully deleted ], erp.sale.invoice.delete9, {"invoice_id":"9","code":"INV-2024009","cust_id":"37","name":"admin","invoice_date":"2024-02-21","invoice_expiry":"2024-02-28","shippingaddr_id":"0","billingaddr_id":"","transport_req":"0","trans_charge":"0.00","payment_terms":"fsdf","terms_condition":"<p>fsf<\/p>","discount":"0.00"}, Qbs , 2024-02-21 08:49:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2788, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/10, , Qbs , 2024-02-21 08:50:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2789, Sale Invoice Update, [ Sale Invoice successfully updated ], erp/sale/invoiceview/10, , Qbs , 2024-02-21 08:52:54);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2790, warehouse Deletion, [ Warehouse successfully deleted ], erp.sale.invoice.delete8, {"invoice_id":"8","code":"INV-2024008","cust_id":"38","name":"admin2","invoice_date":"2024-02-14","invoice_expiry":"2024-02-21","shippingaddr_id":"0","billingaddr_id":"","transport_req":"1","trans_charge":"100.00","payment_terms":"1 day","terms_condition":"<p>fsd<\/p>","discount":"0.00"}, Qbs , 2024-02-21 09:25:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2791, warehouse Deletion, [ Warehouse successfully deleted ], erp.sale.invoice.delete7, {"invoice_id":"7","code":"INV-2024007","cust_id":"45","name":"john","invoice_date":"2024-02-09","invoice_expiry":"2024-02-16","shippingaddr_id":"13","billingaddr_id":"","transport_req":"0","trans_charge":"1.00","payment_terms":"30 day","terms_condition":"<p>sdffsf<\/p>","discount":"0.00"}, Qbs , 2024-02-21 09:25:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2792, Quotation Update, [ Quotation successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-02-21 09:35:50);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2793, Quotation Accepted, [ Quotation Accepted successfully ], erp/sale/quotationview/1, , Qbs , 2024-02-21 09:36:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2794, Order Conversion, [ Order Conversion successfully ], http://192.168.29.9/erpcinew/public/erp/sales/order_view/9, , Qbs , 2024-02-21 09:47:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2795, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/10, , Qbs , 2024-02-21 09:54:57);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2796, warehouse Deletion, [ Warehouse successfully deleted ], erp.sale.invoice.delete3, {"invoice_id":"3","code":"INV-2024003","cust_id":"45","name":"john","invoice_date":"2024-02-06","invoice_expiry":"2024-02-13","shippingaddr_id":"13","billingaddr_id":"2","transport_req":"0","trans_charge":"0.00","payment_terms":"fds","terms_condition":"<p>test<\/p>","discount":"0.00"}, Qbs , 2024-02-21 11:39:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2797, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/6, , Qbs , 2024-02-21 11:51:22);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2798, Invoice Deletion, [ Invoice successfully deleted ], erp.sale.invoice.delete6, {"invoice_id":"6","code":"INV-2024006","cust_id":"46","name":"jacob","invoice_date":"2024-02-09","invoice_expiry":"2024-02-16","shippingaddr_id":"14","billingaddr_id":"","transport_req":"0","trans_charge":"1.00","payment_terms":"dsf","terms_condition":"<p>fsdf<\/p>","discount":"0.00"}, Qbs , 2024-02-21 11:52:04);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2799, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/5, , Qbs , 2024-02-21 11:52:33);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2800, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/5, , Qbs , 2024-02-21 11:52:46);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2801, Invoice Deletion, [ Invoice successfully deleted ], erp.sale.invoice.delete5, {"invoice_id":"5","code":"INV-2024005","cust_id":"37","name":"admin","invoice_date":"2024-02-06","invoice_expiry":"2024-02-13","shippingaddr_id":"3","billingaddr_id":"","transport_req":"0","trans_charge":"10.00","payment_terms":"test1","terms_condition":"<p>sdf<\/p>","discount":"0.00"}, Qbs , 2024-02-21 11:53:06);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2802, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/4, , Qbs , 2024-02-21 11:56:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2803, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/4, , Qbs , 2024-02-21 11:56:39);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2804, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/10, , Qbs , 2024-02-21 13:13:09);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2805, Login, [ User successfully logged in ], , , Qbs , 2024-02-22 04:57:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2806, Login, [ User successfully logged in ], , , Qbs , 2024-02-22 04:59:04);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2807, Login, [ User successfully logged in ], , , Qbs , 2024-02-22 06:49:32);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2808, Login, [ User successfully logged in ], , , Qbs , 2024-02-22 06:49:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2809, Login, [ User successfully logged in ], , , Qbs , 2024-02-22 06:50:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2810, Login, [ User successfully logged in ], , , Qbs , 2024-02-22 07:29:54);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2811, Warehouse Insert, [ Warehouse failed to create ], erp/warehouse/warehouses, , Qbs , 2024-02-22 08:52:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2812, Planning Insert, [ Planning failed to create ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule, , Qbs , 2024-02-22 10:16:25);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2813, Planning Insert, [ Planning successfully created ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule, , Qbs , 2024-02-22 10:19:38);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2814, Planning Update, [ Planning successfully updated ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule, , Qbs , 2024-02-22 10:31:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2815, Planning Update, [ Planning failed to update ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule, , Qbs , 2024-02-22 10:32:10);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2816, Planning Update, [ Planning failed to update ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule, , Qbs , 2024-02-22 10:32:16);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2817, Planning Update, [ Planning successfully updated ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule, , Qbs , 2024-02-22 10:32:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2818, Planning Update, [ Planning successfully updated ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule, , Qbs , 2024-02-22 10:33:39);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2819, Planning Update, [ Planning successfully updated ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-schedule, , Qbs , 2024-02-22 10:40:16);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2820, MRP Scheduling Insert, [ MRP Scheduling successfully created ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-view/1, , Qbs , 2024-02-22 10:42:06);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2821, MRP Scheduling Insert, [ MRP Scheduling successfully created ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-view/7, , Qbs , 2024-02-22 10:46:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2822, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/11, , Qbs , 2024-02-22 11:05:46);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2823, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/11, , Qbs , 2024-02-22 11:06:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2824, MRP Scheduling Insert, [ MRP Scheduling successfully created ], http://192.168.29.9/erpcinew/public/erp/mrp/planning-view/4, , Qbs , 2024-02-22 11:34:55);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2825, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/11, , Qbs , 2024-02-22 11:40:57);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2826, Login, [ User successfully logged in ], , , Qbs , 2024-02-24 05:07:53);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2827, Login, [ User successfully logged in ], , , Qbs , 2024-02-24 06:49:55);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2828, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/12, , Qbs , 2024-02-24 07:59:16);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2829, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/1, , Qbs , 2024-02-24 09:56:19);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2830, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/1, , Qbs , 2024-02-24 09:57:02);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2831, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/2, , Qbs , 2024-02-24 12:41:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2832, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/2, , Qbs , 2024-02-24 12:42:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2833, Login, [ User successfully logged in ], , , Qbs , 2024-03-14 07:39:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2834, Login, [ User successfully logged in ], , , Qbs , 2024-03-14 07:42:45);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2835, Login, [ User successfully logged in ], , , Qbs , 2024-03-14 10:36:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2836, Requisition Insert, [ Requisition successfully created ], erp/procurement/requisitionview/7, , Qbs , 2024-03-14 10:47:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2837, Logout, [ User successfully logout ], , , Qbs , 2024-03-14 11:28:42);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2838, Login, [ User successfully logged in ], , , Qbs , 2024-03-14 11:29:01);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2839, Logout, [ User successfully logout ], , , Qbs , 2024-03-14 12:50:43);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2840, Login, [ User successfully logged in ], , , Qbs , 2024-03-14 12:53:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2841, Login, [ User successfully logged in ], , , Qbs , 2024-03-15 04:40:49);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2842, Login, [ User successfully logged in ], , , Qbs , 2024-03-26 11:31:06);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2843, Quotation Notification Insert, [ Quotation Notification successfully created ], http://192.168.29.3/ERP/public/erp/sales/quotations_view/1, , Qbs , 2024-03-26 11:47:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2844, Quotation Notification Insert, [ Quotation Notification successfully created ], http://192.168.29.3/ERP/public/erp/sales/quotations_view/1, , Qbs , 2024-03-26 12:10:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2845, Quotation Notification Delete, [ Quotation Notification successfully deleted ], erp/sale/quotationview/1, , Qbs , 2024-03-26 12:10:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2846, Quotation Notification Delete, [ Quotation Notification successfully deleted ], erp/sale/quotationview/1, , Qbs , 2024-03-26 12:12:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2847, Quotation Notification Insert, [ Quotation Notification successfully created ], http://192.168.29.3/ERP/public/erp/sales/quotations_view/1, , Qbs , 2024-03-26 12:12:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2848, Quotation Notification Delete, [ Quotation Notification successfully deleted ], erp/sale/quotationview/1, , Qbs , 2024-03-26 12:23:22);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2849, Quotation Notification Insert, [ Quotation Notification successfully created ], http://192.168.29.3/ERP/public/erp/sales/quotations_view/1, , Qbs , 2024-03-26 12:23:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2850, Quotation Notification Delete, [ Quotation Notification successfully deleted ], erp/sale/quotationview/1, , Qbs , 2024-03-26 12:27:09);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2851, Quotation Notification Insert, [ Quotation Notification successfully created ], http://192.168.29.3/ERP/public/erp/sales/quotations_view/1, , Qbs , 2024-03-26 12:27:27);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2852, Quotation Notification Update, [ Quotation Notification successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-03-26 13:05:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2853, Quotation Notification Update, [ Quotation Notification successfully updated ], erp/sale/quotationview/1, , Qbs , 2024-03-26 13:07:17);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2854, Login, [ User successfully logged in ], , , Qbs , 2024-03-26 13:36:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2855, Login, [ User successfully logged in ], , , Qbs , 2024-03-26 13:36:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2856, Quotation Notification Insert, [ Quotation Notification successfully created ], http://192.168.29.3/ERP/public/erp/sales/quotations_view/1, , Qbs , 2024-03-26 13:37:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2857, Login, [ User successfully logged in ], , , Qbs , 2024-03-27 05:15:10);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2858, Login, [ User successfully logged in ], , , Qbs , 2024-03-27 05:31:01);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2859, Login, [ User successfully logged in ], , , Qbs , 2024-03-27 12:59:41);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2860, Login, [ User successfully logged in ], , , Qbs , 2024-03-28 06:11:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2861, Logout, [ User successfully logout ], , , Qbs , 2024-03-28 07:31:21);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2862, Login, [ User successfully logged in ], , , Qbs , 2024-03-28 07:32:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2863, Logout, [ User successfully logout ], , , Qbs , 2024-03-28 07:36:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2864, Login, [ User successfully logged in ], , , Qbs , 2024-03-28 09:17:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2865, Logout, [ User successfully logout ], , , Qbs , 2024-03-28 09:17:37);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2866, Login, [ User successfully logged in ], , , Qbs , 2024-03-28 09:19:28);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2867, Logout, [ User successfully logout ], , , Qbs , 2024-03-28 09:19:41);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2868, Login, [ User successfully logged in ], , , Qbs , 2024-03-28 09:20:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2869, Logout, [ User successfully logout ], , , Qbs , 2024-03-28 09:29:09);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2870, Login, [ User successfully logged in ], , , Qbs , 2024-04-06 05:17:49);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2871, Login, [ User successfully logged in ], , , Qbs , 2024-04-13 08:42:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2872, Logout, [ User successfully logout ], , , Qbs , 2024-04-13 08:46:03);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2873, Login, [ User successfully logged in ], , , Qbs , 2024-04-13 08:46:13);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2874, Login, [ User successfully logged in ], , , Qbs , 2024-04-13 08:46:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2875, Login, [ User successfully logged in ], , , Qbs , 2024-04-27 04:54:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2876, Login, [ User successfully logged in ], , , Qbs , 2024-04-27 04:56:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2877, Lead Insert, [ Lead successfully created ], http://192.168.29.3/ERP/public/erp/crm/lead-view/0, , Qbs , 2024-04-27 05:49:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2878, Task Insert, [ Task failed to create ], erp/crm/task, , Qbs , 2024-04-27 05:55:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2879, Customer Insert, [ Customer successfully created ], erp/crm/customerview/49, , Qbs , 2024-04-27 06:06:29);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2880, Customer Deletion, [ Customer successfully deleted ], erp.crm.customerdelete48, {"cust_id":"48","name":"Production Thamizharasi","position":"web developer","address":"chennai","city":"Thiruvallur","state":"tamilnadu","country":"India","zip":"602024","email":"tamil@gmail.com","phone":"9578523633","fax_num":"3253546","office_num":"656554","company":"qbs soft","gst":"45666666666","website":"","description":"sfgarg","remarks":"sadgasfg","created_at":"1705748532","created_by":"1"}, Qbs , 2024-04-27 06:07:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2881, Ticket Insert, [ Ticket failed to create ], erp/crm/tickets, , Qbs , 2024-04-27 06:37:39);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2882, Estimate Insert, [ Estimate successfully created ], erp/sale/estimateview/55, , Qbs , 2024-04-27 06:55:21);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2883, Quotation Insert, [ Quotation successfully created ], erp/sale/quotationview/2, , Qbs , 2024-04-27 06:57:09);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2884, Property Type Insert, [ Property Type failed to create ], erp/inventory/propertytype, , Qbs , 2024-04-27 07:20:19);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2885, Login, [ User successfully logged in ], , , Qbs , 2024-04-27 07:34:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2886, RFQ Insert, [ RFQ successfully created ], erp/procurement/rfqview/3, , Qbs , 2024-04-27 07:44:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2887, GRN Update, [ GRN successfully updated ], erp/warehouse/grnview/1, , Qbs , 2024-04-27 07:51:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2888, Login, [ User successfully logged in ], , , Qbs , 2024-04-29 05:19:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2889, Login, [ User successfully logged in ], , , Qbs , 2024-04-29 05:20:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2890, Lead Insert, [ Lead successfully created ], http://192.168.29.3/ERP/public/erp/crm/lead-view/22, , Qbs , 2024-04-29 05:26:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2891, Login, [ User successfully logged in ], , , Qbs , 2024-04-29 06:18:34);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2892, Login, [ User successfully logged in ], , , Qbs , 2024-04-30 04:52:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2893, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 07:10:06);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2894, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 07:10:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2895, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 07:14:27);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2896, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 07:15:04);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2897, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 07:16:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2898, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 07:21:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2899, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 07:22:19);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2900, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 07:23:23);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2901, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 09:25:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2902, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 09:26:22);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2903, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 09:27:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2904, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 09:31:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2905, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 09:32:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2906, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 09:34:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2907, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 09:43:29);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2908, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 10:17:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2909, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 11:25:46);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2910, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 11:40:10);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2911, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 11:40:21);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2912, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 11:40:43);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2913, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-04-30 18:03:21);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2914, Login, [ User successfully logged in ], , , Qbs , 2024-05-02 10:35:29);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2915, RFQ Send Batch, [ RFQ Sent without suppliers ], erp/procurement/rfqview/2, , Qbs , 2024-05-02 16:20:25);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2916, Supplier Supply List Insert, [ Supplier Supply List failed to create ], erp/supplier/supplier-view/6, , Qbs , 2024-05-02 16:21:28);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2917, Supplier Update, [ Supplier successfully updated ], erp/supplier/supplier-view/4, , Qbs , 2024-05-02 16:22:39);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2918, RFQ Supplier Insert, [ RFQ Supplier failed to update ], erp/procurement/rfqview/2, , Qbs , 2024-05-02 16:23:31);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2919, Login, [ User successfully logged in ], , , Qbs , 2024-05-03 10:16:41);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2920, Logout, [ User successfully logout ], , , Qbs , 2024-05-03 11:51:11);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2921, Login, [ User successfully logged in ], , , Qbs , 2024-05-03 11:56:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2922, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-05-03 15:55:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2923, Login, [ User successfully logged in ], , , Qbs , 2024-05-04 10:21:19);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2924, Login, [ User successfully logged in ], , , Qbs , 2024-05-06 11:21:28);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2925, Logout, [ User successfully logout ], , , Qbs , 2024-05-06 17:57:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2926, Login, [ User successfully logged in ], , , Qbs , 2024-05-06 17:58:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2927, Login, [ User successfully logged in ], , , Qbs , 2024-05-07 10:31:51);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2928, Generating Backup, [Datebase Backup Created Successfully.], http://localhost/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-05-07 12:12:46);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2929, Department Insert, [ Department successfully created ], erp/hr/departments/, , Qbs , 2024-05-07 12:17:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2930, Login, [ User successfully logged in ], , , Qbs , 2024-05-08 10:08:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2931, Login, [ User successfully logged in ], , , Qbs , 2024-05-08 15:24:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2932, Login, [ User successfully logged in ], , , Qbs , 2024-05-08 15:25:09);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2933, Login, [ User successfully logged in ], , , Qbs , 2024-05-09 10:14:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2934, Login, [ User successfully logged in ], , , Qbs , 2024-05-10 10:41:19);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2935, Login, [ User successfully logged in ], , , Qbs , 2024-05-11 10:09:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2936, Login, [ User successfully logged in ], , , Qbs , 2024-05-13 10:05:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2937, Login, [ User successfully logged in ], , , Qbs , 2024-05-14 10:04:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2938, Logout, [ User successfully logout ], , , Qbs , 2024-05-14 15:29:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2939, Login, [ User successfully logged in ], , , Qbs , 2024-05-14 15:29:43);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2940, Login, [ User successfully logged in ], , , Qbs , 2024-05-15 10:09:39);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2941, Mail Settings Update, [ Mail Settings successfully updated ], , , Qbs , 2024-05-15 15:50:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2942, Login, [ User successfully logged in ], , , Qbs , 2024-05-16 10:21:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2943, Login, [ User successfully logged in ], , , Qbs , 2024-05-16 16:02:43);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2944, Login, [ User successfully logged in ], , , Qbs , 2024-05-16 17:53:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2945, Login, [ User successfully logged in ], , , Qbs , 2024-05-17 10:20:03);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2946, Login, [ User successfully logged in ], , , Qbs , 2024-05-17 18:49:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2947, Login, [ User successfully logged in ], , , Qbs , 2024-05-18 10:08:46);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2948, Login, [ User successfully logged in ], , , Qbs , 2024-05-20 10:07:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2949, Login, [ User successfully logged in ], , , Qbs , 2024-05-20 10:07:16);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2950, Login, [ User successfully logged in ], , , Qbs , 2024-05-21 10:12:32);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2951, Login, [ User successfully logged in ], , , Qbs , 2024-05-23 10:10:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2952, Login, [ User successfully logged in ], , , Qbs , 2024-05-24 10:29:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2953, Login, [ User successfully logged in ], , , Qbs , 2024-05-25 10:07:06);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2954, Login, [ User successfully logged in ], , , Qbs , 2024-05-27 10:09:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2955, Login, [ User successfully logged in ], , , Qbs , 2024-05-28 10:08:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2956, Login, [ User successfully logged in ], , , Qbs , 2024-05-28 10:51:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2957, Login, [ User successfully logged in ], , , Qbs , 2024-05-29 10:09:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2958, Login, [ User successfully logged in ], , , Qbs , 2024-05-30 10:11:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2959, Login, [ User successfully logged in ], , , Qbs , 2024-05-31 10:24:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2960, Login, [ User successfully logged in ], , , Qbs , 2024-06-01 10:21:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2961, Login, [ User successfully logged in ], , , Qbs , 2024-06-03 10:35:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2962, Login, [ User successfully logged in ], , , Qbs , 2024-06-04 10:12:55);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2963, Login, [ User successfully logged in ], , , Qbs , 2024-06-04 17:51:03);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2964, Login, [ User successfully logged in ], , , Qbs , 2024-06-05 10:08:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2965, Login, [ User successfully logged in ], , , Qbs , 2024-06-05 15:29:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2966, Quotation Accepted, [ Quotation Accepted successfully ], erp/sale/quotationview/2, , Qbs , 2024-06-05 18:23:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2967, Order Conversion, [ Order Conversion successfully ], http://192.168.29.138/Erp/public/erp/sales/order_view/10, , Qbs , 2024-06-05 18:23:28);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2968, Login, [ User successfully logged in ], , , Qbs , 2024-06-06 10:10:06);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2969, Login, [ User successfully logged in ], , , Qbs , 2024-06-07 10:19:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2970, Login, [ User successfully logged in ], , , Qbs , 2024-06-07 11:10:23);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2971, Login, [ User successfully logged in ], , , Qbs , 2024-06-07 16:03:10);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2972, Account Group Insert, [ Account Group successfully created ], erp/finance/accountgroup/, , Qbs , 2024-06-07 16:57:13);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2973, Login, [ User successfully logged in ], , , Qbs , 2024-06-07 18:05:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2974, Login, [ User successfully logged in ], , , Qbs , 2024-06-08 10:08:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2975, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/1, , Qbs , 2024-06-08 15:22:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2976, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/2, , Qbs , 2024-06-08 15:24:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2977, Login, [ User successfully logged in ], , , Qbs , 2024-06-10 10:21:10);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2978, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/3, , Qbs , 2024-06-10 10:39:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2979, Login, [ User successfully logged in ], , , Qbs , 2024-06-10 12:04:04);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2980, User Update, [ User successfully updated ], http://192.168.29.138/Erp/public/erp/setting/user-edit/1, , Qbs , 2024-06-10 12:24:54);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2981, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/7, , Qbs , 2024-06-10 12:57:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2982, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/7, , Qbs , 2024-06-10 12:58:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2983, Invoice Deletion, [ Invoice successfully deleted ], erp.sale.invoice.delete7, {"invoice_id":"7","code":"INV-2024004","cust_id":"37","name":"admin","invoice_date":"2024-06-10","invoice_expiry":"2024-06-17","shippingaddr_id":"0","billingaddr_id":"","transport_req":"0","trans_charge":"0.00","payment_terms":"expense add","terms_condition":"","discount":"0.00"}, Qbs , 2024-06-10 13:13:17);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2984, Invoice Deletion, [ Invoice failed to delete ], 3, , Qbs , 2024-06-10 13:13:21);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2985, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/8, , Qbs , 2024-06-10 13:14:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2986, Invoice Deletion, [ Invoice failed to delete ], 8, , Qbs , 2024-06-10 13:17:25);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2987, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/9, , Qbs , 2024-06-10 13:18:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2988, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/10, , Qbs , 2024-06-10 13:23:43);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2989, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/11, , Qbs , 2024-06-10 13:25:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2990, Invoice Deletion, [ Invoice successfully deleted ], erp.sale.invoice.delete2, {"invoice_id":"2","code":"INV-2024002","cust_id":"45","name":"john","invoice_date":"2024-02-24","invoice_expiry":"2024-03-02","shippingaddr_id":"0","billingaddr_id":"","transport_req":"0","trans_charge":"0.00","payment_terms":"fds","terms_condition":"<p>fdsf<\/p>","discount":"0.00"}, Qbs , 2024-06-10 14:22:29);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2991, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/12, , Qbs , 2024-06-10 14:23:08);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2992, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/13, , Qbs , 2024-06-10 14:35:16);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2993, Invoice Deletion, [ Invoice failed to delete ], 13, , Qbs , 2024-06-10 15:59:56);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2994, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/14, , Qbs , 2024-06-10 16:00:17);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2995, Invoice Deletion, [ Invoice failed to delete ], 14, , Qbs , 2024-06-10 16:03:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2996, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/15, , Qbs , 2024-06-10 16:05:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2997, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/16, , Qbs , 2024-06-10 16:14:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2998, Invoice Deletion, [ Invoice failed to delete ], 16, , Qbs , 2024-06-10 16:44:22);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (2999, Invoice Deletion, [ Invoice failed to delete ], 15, , Qbs , 2024-06-10 16:44:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3000, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/17, , Qbs , 2024-06-10 16:50:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3001, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/18, , Qbs , 2024-06-10 17:00:30);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3002, Invoice Deletion, [ Invoice failed to delete ], 18, , Qbs , 2024-06-10 17:00:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3003, Invoice Deletion, [ Invoice failed to delete ], 17, , Qbs , 2024-06-10 17:25:39);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3004, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/19, , Qbs , 2024-06-10 17:26:38);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3005, Sale Payment Insert, [ Sale Payment failed to create ], erp/sale/invoice_view/19, , Qbs , 2024-06-10 17:53:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3006, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/19, , Qbs , 2024-06-10 18:04:34);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3007, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/19, , Qbs , 2024-06-10 18:10:45);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3008, Sale Invoice Notification Insert, [ Sale Invoice Notification successfully created ], erp/sale/invoiceview/19, , Qbs , 2024-06-10 18:11:27);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3009, Sale Invoice Notification Insert, [ Sale Invoice Notification successfully created ], erp/sale/invoiceview/19, , Qbs , 2024-06-10 18:15:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3010, Sale Payment Delete, [ Sale Payment failed to delete ], erp/sale/manage_view/19, , Qbs , 2024-06-10 19:08:49);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3011, Sale Payment Update, [ Sale Payment failed to update ], erp/sale/manage_view/19, , Qbs , 2024-06-10 19:09:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3012, Sale Payment Update, [ Sale Payment successfully updated ], erp/sale/manage_view/19, , Qbs , 2024-06-10 19:15:57);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3013, Sale Payment Delete, [ Sale Payment successfully deleted ], erp/sale/manage_view/19, , Qbs , 2024-06-10 19:19:02);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3014, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/19, , Qbs , 2024-06-10 19:19:51);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3015, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/20, , Qbs , 2024-06-10 19:21:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3016, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/20, , Qbs , 2024-06-10 19:22:11);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3017, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/20, , Qbs , 2024-06-10 19:22:31);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3018, Login, [ User successfully logged in ], , , Qbs , 2024-06-11 10:19:39);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3019, Credit Note Insert, [ Credit Note successfully created ], http://192.168.29.138/Erp/public/erp/sales/credit_notes, , Qbs , 2024-06-11 11:56:45);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3020, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/21, , Qbs , 2024-06-11 12:32:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3021, Sale Payment Insert, [ Sale Payment successfully created ], erp/sale/invoice_view/21, , Qbs , 2024-06-11 12:32:42);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3022, Login, [ User successfully logged in ], , , Qbs , 2024-06-12 10:20:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3023, Login, [ User successfully logged in ], , , Qbs , 2024-06-12 16:50:04);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3024, Login, [ User successfully logged in ], , , Qbs , 2024-06-13 10:33:07);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3025, Login, [ User successfully logged in ], , , Qbs , 2024-06-13 10:45:15);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3026, Login, [ User successfully logged in ], , , Qbs , 2024-06-13 12:56:14);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3027, Login, [ User successfully logged in ], , , Qbs , 2024-06-14 10:22:00);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3028, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.138/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-06-14 18:55:09);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3029, Login, [ User successfully logged in ], , , Qbs , 2024-06-15 10:39:36);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3030, Logout, [ User successfully logout ], , , Qbs , 2024-06-15 10:40:51);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3031, Login, [ User successfully logged in ], , , Qbs , 2024-06-15 10:40:59);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3032, Logout, [ User successfully logout ], , , Qbs , 2024-06-15 10:41:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3033, Login, [ User successfully logged in ], , , Qbs , 2024-06-15 10:41:12);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3034, Brand Insert, [ Brand successfully created ], erp/inventory/brands, , Qbs , 2024-06-15 10:43:20);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3035, Login, [ User successfully logged in ], , , Qbs , 2024-06-17 10:23:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3036, Role Insert, [ Role successfully created ], http://192.168.29.138/Erp/public/erp/setting/role-view/0, , Qbs , 2024-06-17 14:05:27);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3037, Logout, [ User successfully logout ], , , Qbs , 2024-06-17 15:41:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3038, Login, [ User successfully logged in ], , , Udhaya, 2024-06-17 15:42:02);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3039, Login, [ User successfully logged in ], , , Qbs , 2024-06-25 10:45:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3040, Credit Note Insert, [ Credit Note successfully created ], http://192.168.29.138/Erp/public/erp/sales/credit_notes, , Qbs , 2024-06-25 10:58:38);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3041, Credit Note Insert, [ Credit Note successfully created ], http://192.168.29.138/Erp/public/erp/sales/credit_notes, , Qbs , 2024-06-25 12:40:35);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3042, Login, [ User successfully logged in ], , , Qbs , 2024-10-09 10:11:43);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3043, Login, [ User successfully logged in ], , , Qbs , 2024-10-09 10:21:38);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3044, Login, [ User successfully logged in ], , , Qbs , 2024-10-10 12:46:42);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3045, Login, [ User successfully logged in ], , , Qbs , 2024-10-18 11:49:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3046, RFQ Send Batch, [ RFQ Send Batch sucessfully created ], erp/procurement/rfqview/2, , Qbs , 2024-10-18 11:59:49);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3047, Login, [ User successfully logged in ], , , Qbs , 2024-11-01 19:11:38);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3048, Estimate Insert, [ Estimate successfully created ], erp/sale/estimateview/56, , Qbs , 2024-11-01 19:23:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3049, Login, [ User successfully logged in ], , , Qbs , 2024-11-02 10:14:05);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3050, Sale Invoice Insert, [ Sale Invoice successfully created ], erp/sale/invoiceview/22, , Qbs , 2024-11-02 17:32:06);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3051, Login, [ User successfully logged in ], , , Qbs , 2024-11-07 17:53:23);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3052, Login, [ User successfully logged in ], , , Qbs , 2024-11-09 15:20:34);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3053, Customer Insert, [ Customer successfully created ], http://192.168.29.3/Erp/public/erp/crm/lead-customer-view/50, , Qbs , 2024-11-09 16:48:31);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3054, Login, [ User successfully logged in ], , , Qbs , 2024-11-09 18:44:58);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3055, Login, [ User successfully logged in ], , , Qbs , 2024-11-11 10:46:06);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3056, Lead Update, [ Lead successfully updated ], http://192.168.29.3/Erp/public/erp/crm/lead-view/1, , Qbs , 2024-11-11 15:08:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3057, Lead Update, [ Lead successfully updated ], http://192.168.29.3/Erp/public/erp/crm/lead-view/1, , Qbs , 2024-11-11 15:09:10);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3058, Lead Update, [ Lead successfully updated ], http://192.168.29.3/Erp/public/erp/crm/lead-view/1, , Qbs , 2024-11-11 15:13:13);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3059, Lead Update, [ Lead failed to update ], http://192.168.29.3/Erp/public/erp/crm/lead-view/1, , Qbs , 2024-11-11 15:13:40);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3060, Lead Update, [ Lead successfully updated ], http://192.168.29.3/Erp/public/erp/crm/lead-view/1, , Qbs , 2024-11-11 15:13:48);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3061, Login, [ User successfully logged in ], , , Qbs , 2024-11-11 16:07:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3062, Lead Update, [ Lead successfully updated ], http://192.168.29.3/Erp/public/erp/crm/lead-view/5, , Qbs , 2024-11-11 16:55:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3063, Login, [ User successfully logged in ], , , Qbs , 2024-11-11 19:41:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3064, Login, [ User successfully logged in ], , , Qbs , 2024-11-13 10:10:18);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3065, Login, [ User successfully logged in ], , , Qbs , 2024-11-13 10:35:22);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3066, Login, [ User successfully logged in ], , , Qbs , 2024-11-13 10:45:45);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3067, Login, [ User successfully logged in ], , , Qbs , 2024-11-13 11:53:27);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3068, Login, [ User successfully logged in ], , , Qbs , 2024-11-13 11:53:34);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3069, Login, [ User successfully logged in ], , , Qbs , 2024-11-13 11:53:52);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3070, Login, [ User successfully logged in ], , , Qbs , 2024-11-13 11:55:47);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3071, Login, [ User successfully logged in ], , , Qbs , 2024-11-13 12:34:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3072, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-11-13 16:31:24);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3073, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-11-13 16:31:26);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3074, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-11-13 16:31:29);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3075, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-11-13 16:31:32);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3076, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-11-13 16:31:34);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3077, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-11-13 16:31:39);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3078, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-11-13 16:31:44);
INSERT INTO `erp_log` (log_id, title, log_text, ref_link, additional_info, done_by, created_at) VALUES (3079, Generating Backup, [Datebase Backup Created Successfully.], http://192.168.29.3/Erp/public/erp/setting/backupdata_base_view, , Qbs , 2024-11-13 16:31:47);


#
# TABLE STRUCTURE FOR: erp_roles
#

DROP TABLE IF EXISTS `erp_roles`;

CREATE TABLE `erp_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(60) NOT NULL,
  `role_desc` varchar(1000) NOT NULL,
  `permissions` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `referrer_role` int(11) NOT NULL,
  `can_be_purged` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (0, Employee, Employee , [], 1718613327, 0, 0);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (11, Manager, Manages all work, ["crm_lead_view_global","crm_lead_create","crm_lead_update","crm_lead_delete","crm_customer_view_global","crm_customer_create","crm_customer_update","crm_customer_delete"], 1643965958, 0, 1);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (15, Sales Rep, sales representative, ["crm_lead_view_own","crm_lead_create","crm_lead_update","crm_lead_delete","crm_customer_view_own","crm_customer_create","crm_customer_update","crm_customer_delete"], 1643966205, 0, 1);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (16, test, sss, ["crm_lead_create","crm_lead_update","crm_customer_view_own"], 1643967631, 0, 1);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (17, test, test, ["do_reflect","crm_lead_view_own","crm_lead_create","crm_lead_update","crm_customer_view_own","crm_customer_update","crm_customer_delete"], 1643967665, 0, 1);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (18, test, sss, ["crm_lead_create","crm_lead_update","crm_customer_view_global"], 1643969374, 16, 1);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (19, Manager, Manages all work, [], 1703230264, 11, 0);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (20, Test Role, Test, ["crm_lead_view_global","crm_lead_delete","crm_customer_view_own","crm_customer_update","notify_create"], 1703230341, 0, 1);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (21, Sales Rep, sales representative, ["crm_lead_view_global","crm_lead_create","crm_lead_update","crm_lead_delete","crm_customer_view_own","crm_customer_create","crm_customer_update","crm_customer_delete"], 1703834978, 15, 1);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (22, Sales Rep, sales representative, ["crm_lead_view_own","crm_lead_create","crm_lead_update","crm_lead_delete","crm_customer_view_own","crm_customer_create","crm_customer_update","crm_customer_delete"], 1703835014, 21, 0);
INSERT INTO `erp_roles` (role_id, role_name, role_desc, permissions, created_at, referrer_role, can_be_purged) VALUES (23, employee, tamil, ["crm_lead_view_global","crm_customer_delete"], 1705754516, 0, 1);


#
# TABLE STRUCTURE FOR: erp_settings
#

DROP TABLE IF EXISTS `erp_settings`;

CREATE TABLE `erp_settings` (
  `setting_id` int(11) NOT NULL,
  `s_name` varchar(255) NOT NULL,
  `s_value` text NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (1, company_logo, logo.png);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (2, favicon, favicon.png);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (3, company_name, Q Brainstorm Software);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (4, address, No.164, First Floor, Arcot Rd, Valasaravakkam);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (5, city, Chennai);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (6, state, Tamil Nadu);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (7, country, India);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (8, zip, 600087);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (9, phone, 9080780700);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (10, gst);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (11, mail_engine, CodeIgniter);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (12, email_encryption, SSL);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (13, smtp_host, smtp.gmail.com);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (14, smtp_port, 587);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (15, smtp_username, support@qbrainstorm.com);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (16, smtp_password, QBSSupport4cus@2023#1234);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (17, bcc_list);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (18, cc_list);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (19, track_quota, 0);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (20, close_account_book, 2024-01-26);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (21, finance_capital, 10000.00);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (22, finance_capital_vary, 0.00);
INSERT INTO `erp_settings` (setting_id, s_name, s_value) VALUES (23, system_type, manufacturing);


#
# TABLE STRUCTURE FOR: erp_users
#

DROP TABLE IF EXISTS `erp_users`;

CREATE TABLE `erp_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `last_name` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `position` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `expired` tinyint(4) NOT NULL DEFAULT 0,
  `is_admin` tinyint(4) NOT NULL DEFAULT 0,
  `last_login` varchar(20) NOT NULL,
  `role_id` int(11) NOT NULL,
  `remember` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erp_users` (user_id, name, staff_id, last_name, email, phone, position, password, active, expired, is_admin, last_login, role_id, remember, description, created_at) VALUES (1, Qbs , 0, Support, support@qbrainstorm.com, 9080780700, admin1, $2y$10$JcJTUdh3pp9H7h317ZzfpegXKpbQowDyHcWauUV7d0uQiLVZrnjWm, 1, 0, 1, 1657538814, 19, fcc923a6de504c37932e619e44128a4e3aedc51b, no descriptions, 1643791190);
INSERT INTO `erp_users` (user_id, name, staff_id, last_name, email, phone, position, password, active, expired, is_admin, last_login, role_id, remember, description, created_at) VALUES (2, John, 0, J, john@qbrainstorm.com, 1234567890, test, $2y$10$rZ1Uu51LjTwSb1tIGJ4l7eWEMi83eAg6hQrJI2TS.bnNVqaWLAN.i, 1, 0, 0, 1646822983, 22, , 0, 1643791190);
INSERT INTO `erp_users` (user_id, name, staff_id, last_name, email, phone, position, password, active, expired, is_admin, last_login, role_id, remember, description, created_at) VALUES (3, Jacob, 0, K, jacob@qbrainstorm.com, 9080780700, Purchase Manager, $2y$10$Sv15lDTaSUefO7JPYo4VQehQB6pD7lXTwZH3Q8SGPLeQVWFG4WyjW, 1, 0, 0, 1648889292, 11, 92bdd0f09edabdde89c2a3e5a5bdfc3b5e580245, 0, 1643791243);
INSERT INTO `erp_users` (user_id, name, staff_id, last_name, email, phone, position, password, active, expired, is_admin, last_login, role_id, remember, description, created_at) VALUES (15, Udhaya, 14, Kumar, udhaya@qbrainstorm.com, 9080780700, Junior, $2y$10$z/AEa4L6pFWKXyvUyW5me.JQtTL6T3D8aI8hOTMb.9PsFWnLsM4yO, 1, 0, 1, , 19, , des, 2024-06-17 15:41:21);


#
# TABLE STRUCTURE FOR: erpcontract
#

DROP TABLE IF EXISTS `erpcontract`;

CREATE TABLE `erpcontract` (
  `contract_id` int(100) NOT NULL AUTO_INCREMENT,
  `content` longtext DEFAULT NULL,
  `description` text DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `client` int(11) NOT NULL,
  `datestart` date DEFAULT NULL,
  `dateend` date DEFAULT NULL,
  `contract_type` int(11) DEFAULT NULL,
  `project_id` int(100) NOT NULL,
  `addedfrom` int(11) NOT NULL,
  `dateadded` datetime NOT NULL DEFAULT current_timestamp(),
  `isexpirynotified` int(11) NOT NULL,
  `contract_value` int(11) DEFAULT NULL,
  `trash` tinyint(1) NOT NULL,
  `not_visible_to_client` tinyint(1) NOT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `signed` tinyint(1) NOT NULL,
  `signature` longtext DEFAULT NULL,
  `marked_as_signed` tinyint(1) NOT NULL,
  `acceptance_firstname` varchar(50) DEFAULT NULL,
  `acceptance_lastname` varchar(50) DEFAULT NULL,
  `acceptance_email` varchar(100) DEFAULT NULL,
  `acceptance_date` date DEFAULT NULL,
  `acceptance_ip` varchar(40) DEFAULT NULL,
  `short_link` varchar(100) DEFAULT NULL,
  `last_sent_at` datetime DEFAULT NULL,
  `contacts_sent_to` text DEFAULT NULL,
  `last_sign_reminder_at` datetime DEFAULT NULL,
  PRIMARY KEY (`contract_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erpcontract` (contract_id, content, description, subject, client, datestart, dateend, contract_type, project_id, addedfrom, dateadded, isexpirynotified, contract_value, trash, not_visible_to_client, hash, signed, signature, marked_as_signed, acceptance_firstname, acceptance_lastname, acceptance_email, acceptance_date, acceptance_ip, short_link, last_sent_at, contacts_sent_to, last_sign_reminder_at) VALUES (31, yes  it is, The Description, Contract_1, 46, 2024-06-03, 2024-06-14, 83, 0, 1, 2024-06-03 10:37:02, 0, 50000, 1, 1, , 1, data:image/svg+xml,<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="231" height="78"><path fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 40 21 c 0 0.14 -0.68 5.66 0 8 c 1.49 5.1 5.94 10.71 7 16 c 1.45 7.27 3.87 23.94 1 24 c -6.26 0.14 -43.44 -21.01 -47 -23 c -0.28 -0.16 2.67 -0.99 4 -1 c 32.59 -0.31 86.97 0.85 101 0 c 0.88 -0.05 -0.84 -4.63 -2 -6 c -2.1 -2.48 -6.78 -4.33 -9 -7 c -2.44 -2.93 -3.79 -7.68 -6 -11 c -1 -1.5 -3.39 -2.48 -4 -4 c -1.78 -4.44 -2.47 -13.45 -4 -16 c -0.47 -0.79 -4.1 0.03 -5 1 c -2.68 2.89 -6.79 8.77 -8 13 c -1.19 4.16 -0.25 10.09 0 15 c 0.08 1.67 0.3 3.6 1 5 c 1.48 2.97 5.15 5.96 6 9 c 1.26 4.5 1.35 14.49 1 16 c -0.11 0.47 -3.2 -1.89 -4 -3 c -0.66 -0.92 -1.49 -3.16 -1 -4 c 1.34 -2.3 5.11 -7.3 8 -8 c 6.38 -1.55 17.16 -0.51 25 0 c 1.98 0.13 4.8 0.8 6 2 c 1.95 1.95 3.62 6 5 9 c 0.55 1.2 0.44 2.87 1 4 c 0.68 1.37 1.94 3.79 3 4 c 1.54 0.31 5.85 -0.64 7 -2 c 1.85 -2.19 3.21 -7.44 4 -11 c 0.47 -2.13 0.47 -4.97 0 -7 c -0.45 -1.95 -1.83 -4.25 -3 -6 c -0.74 -1.11 -1.87 -2.57 -3 -3 c -2.71 -1.04 -6.77 -1.84 -10 -2 c -3.18 -0.16 -8.99 -0.46 -10 1 c -1.21 1.75 0.02 8.88 1 12 c 0.46 1.47 2.55 3.13 4 4 c 1.62 0.97 3.99 1.74 6 2 c 5.42 0.71 12.31 1.61 17 1 c 1.99 -0.26 4.87 -2.36 6 -4 c 1.49 -2.16 2.59 -6.31 3 -9 c 0.18 -1.17 -0.95 -4.1 -1 -4 c -0.07 0.15 -0.59 5.65 0 6 c 0.57 0.34 3.65 -1.8 5 -3 c 1.49 -1.32 2.77 -3.24 4 -5 c 1.12 -1.6 2.39 -3.31 3 -5 c 0.64 -1.76 1.03 -6.1 1 -6 c -0.1 0.33 -3.71 12.82 -5 19 c -0.33 1.57 -0.32 3.54 0 5 c 0.29 1.3 1.14 2.89 2 4 c 1.36 1.75 5.54 4.81 5 5 c -1.21 0.44 -16.28 -0.99 -16 -1 c 1.15 -0.02 48.69 0.75 66 0 c 1.11 -0.05 3.03 -1.95 3 -3 c -0.23 -7.75 -1.67 -21.28 -4 -31 c -1.54 -6.41 -5.33 -16.64 -8 -19 c -1.28 -1.13 -7.13 2.13 -9 4 c -1.87 1.87 -2.92 5.89 -4 9 c -1.6 4.6 -3.02 9.29 -4 14 c -0.68 3.25 -1 6.65 -1 10 c 0 7.01 2.1 16.62 1 21 c -0.4 1.59 -4.68 2.92 -7 3 c -25.6 0.84 -82.53 0.04 -84 0 c -0.87 -0.03 35.62 -2.39 50 -1 c 4 0.39 7.84 6.15 12 7 c 18.42 3.79 40.46 5.95 61 8 l 19 0"/></svg>, 0, Devid, johns, support@qbrainstorm.com, 2024-06-03, 192.168.29.138);
INSERT INTO `erpcontract` (contract_id, content, description, subject, client, datestart, dateend, contract_type, project_id, addedfrom, dateadded, isexpirynotified, contract_value, trash, not_visible_to_client, hash, signed, signature, marked_as_signed, acceptance_firstname, acceptance_lastname, acceptance_email, acceptance_date, acceptance_ip, short_link, last_sent_at, contacts_sent_to, last_sign_reminder_at) VALUES (32, asasas, jk, Contract_1, 47, 2024-06-03, 2024-06-29, 69, 0, 1, 2024-06-03 11:41:07, 0, 560000, 0, 0, , 1, data:image/svg+xml,<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="299" height="76"><path fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 60 29 l 1 1"/><path fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 84 27 c -0.05 0.09 -2.32 3.31 -3 5 c -0.59 1.48 -0.17 3.75 -1 5 c -2.77 4.16 -6.96 10.45 -11 13 c -3.92 2.47 -10.7 3.56 -16 4 c -10.29 0.86 -21.68 0.29 -32 0 c -1.33 -0.04 -2.85 -0.43 -4 -1 c -2.01 -1.01 -4.55 -2.34 -6 -4 c -2.91 -3.32 -6.25 -7.97 -8 -12 c -1.36 -3.12 -1.59 -7.33 -2 -11 c -0.26 -2.3 -0.28 -4.74 0 -7 c 0.37 -2.98 0.72 -6.92 2 -9 c 1 -1.63 3.88 -3.13 6 -4 c 4.94 -2.02 10.71 -4.72 16 -5 c 25.27 -1.32 58.37 -1.49 80 0 c 2.49 0.17 5.14 3.81 7 6 c 1.62 1.92 3.27 4.56 4 7 c 2.14 7.12 3.94 15.4 5 23 c 0.58 4.2 1.31 9.94 0 13 c -1.21 2.83 -5.83 6 -9 8 c -2.84 1.79 -10.46 3.99 -10 4 c 6.23 0.18 113.69 0.89 147 0 c 1.01 -0.03 2.58 -3.52 2 -4 c -1.97 -1.64 -9.32 -4.93 -14 -6 c -6.49 -1.48 -13.85 -1.63 -21 -2 c -6.1 -0.31 -12.2 -0.26 -18 0 c -1.33 0.06 -3.79 0.37 -4 1 c -0.27 0.82 0.87 4.21 2 5 c 2.47 1.71 7.21 3.16 11 4 c 14.16 3.15 28.08 5.54 43 8 c 16.54 2.73 48 7 48 7"/></svg>, 0, Devid, johns, support@qbrainstorm.com, 2024-06-11, 192.168.29.138);
INSERT INTO `erpcontract` (contract_id, content, description, subject, client, datestart, dateend, contract_type, project_id, addedfrom, dateadded, isexpirynotified, contract_value, trash, not_visible_to_client, hash, signed, signature, marked_as_signed, acceptance_firstname, acceptance_lastname, acceptance_email, acceptance_date, acceptance_ip, short_link, last_sent_at, contacts_sent_to, last_sign_reminder_at) VALUES (33, , , Front End, 0, 2024-06-10, 2024-06-11, 83, 0, 1, 2024-06-10 12:09:03, 0, 0, 0, 0, , 0, , 0);
INSERT INTO `erpcontract` (contract_id, content, description, subject, client, datestart, dateend, contract_type, project_id, addedfrom, dateadded, isexpirynotified, contract_value, trash, not_visible_to_client, hash, signed, signature, marked_as_signed, acceptance_firstname, acceptance_lastname, acceptance_email, acceptance_date, acceptance_ip, short_link, last_sent_at, contacts_sent_to, last_sign_reminder_at) VALUES (34, , bfhjfbj, Front End, 0, 2024-06-10, 2024-06-11, 83, 0, 1, 2024-06-10 12:09:37, 0, 1200, 0, 0, , 0, , 0);
INSERT INTO `erpcontract` (contract_id, content, description, subject, client, datestart, dateend, contract_type, project_id, addedfrom, dateadded, isexpirynotified, contract_value, trash, not_visible_to_client, hash, signed, signature, marked_as_signed, acceptance_firstname, acceptance_lastname, acceptance_email, acceptance_date, acceptance_ip, short_link, last_sent_at, contacts_sent_to, last_sign_reminder_at) VALUES (35, , asddas, Front End, 0, 2024-06-10, 2024-06-26, 73, 0, 1, 2024-06-10 12:17:55, 0, 100, 0, 0, , 0, , 0);
INSERT INTO `erpcontract` (contract_id, content, description, subject, client, datestart, dateend, contract_type, project_id, addedfrom, dateadded, isexpirynotified, contract_value, trash, not_visible_to_client, hash, signed, signature, marked_as_signed, acceptance_firstname, acceptance_lastname, acceptance_email, acceptance_date, acceptance_ip, short_link, last_sent_at, contacts_sent_to, last_sign_reminder_at) VALUES (36, , 4210rgre, Front End, 38, 2024-06-10, 2024-06-19, 68, 0, 1, 2024-06-10 12:23:50, 0, 1000, 1, 1, , 1, data:image/svg+xml,<?xml version="1.0" encoding="UTF-8" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="408" height="102"><path fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 11 74 c 0.14 -0.17 6.51 -6.45 8 -10 c 2.51 -5.99 3.89 -13.89 5 -21 c 1.23 -7.93 1.05 -15.82 2 -24 c 0.76 -6.53 2.58 -17.74 3 -19 c 0.1 -0.29 1.95 2.67 2 4 c 0.55 14.31 -0.44 31.62 0 48 c 0.24 8.9 1.2 16.99 2 26 c 0.59 6.6 0.84 13.89 2 19 c 0.26 1.13 2.94 3.37 3 3 c 0.3 -1.9 0.64 -14.98 0 -22 c -0.33 -3.64 -1.26 -8.25 -3 -11 c -1.84 -2.91 -5.82 -6.09 -9 -8 c -3.09 -1.85 -7.48 -3.58 -11 -4 c -4.23 -0.51 -13.59 0.91 -14 1 c -0.15 0.03 3.33 0.94 5 1 c 6.88 0.26 14.26 0.88 21 0 c 8.25 -1.08 16.57 -3.65 25 -6 c 6.22 -1.74 12.13 -3.61 18 -6 c 4.85 -1.97 14.22 -7.24 14 -7 c -0.44 0.48 -19.99 12.5 -25 19 c -2.57 3.34 -0.14 13.37 -2 16 c -1.16 1.64 -7.9 1.53 -10 1 c -0.94 -0.23 -2.17 -2.89 -2 -4 c 0.38 -2.45 1.87 -7.05 4 -9 c 8.34 -7.62 20.89 -15.02 31 -23 c 2.59 -2.05 5.03 -4.59 7 -7 c 0.89 -1.09 1.81 -2.68 2 -4 c 0.42 -2.95 0.58 -7.28 0 -10 c -0.29 -1.36 -1.87 -3.03 -3 -4 c -1.01 -0.87 -3.25 -2.32 -4 -2 c -0.98 0.42 -2.77 3.4 -3 5 c -0.36 2.49 0.15 6.32 1 9 c 1.05 3.31 3.62 6.51 5 10 c 3 7.6 7.15 22.68 8 23 c 0.7 0.26 -0.51 -13.37 0 -20 c 0.49 -6.38 2.25 -19.11 3 -19 c 0.8 0.11 3.03 17.5 4 20 c 0.17 0.44 2.46 -1.2 3 -2 c 0.61 -0.92 0.21 -3.6 1 -4 c 1.74 -0.87 6.64 -0.21 9 -1 c 1.11 -0.37 2.61 -1.96 3 -3 c 0.46 -1.23 0.47 -3.89 0 -5 c -0.34 -0.8 -2.06 -1.88 -3 -2 c -1.33 -0.17 -5.14 0.86 -5 1 c 0.19 0.19 4.9 1.16 7 1 c 1.88 -0.14 4.58 -0.79 6 -2 c 4.91 -4.21 13.04 -16.25 15 -16 c 1.61 0.2 1.36 12.06 1 18 c -0.64 10.62 -3.93 31.44 -4 32 c -0.01 0.12 0.13 -5.04 1 -7 c 1.6 -3.61 4.71 -8.9 7 -11 c 0.88 -0.8 3.62 -0.34 5 0 c 1 0.25 1.97 1.61 3 2 c 1.46 0.55 3.33 0.86 5 1 l 7 0"/><path fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M 226 18 c 0 0.23 0.34 8.62 0 13 c -1.01 12.89 -3.18 25.1 -4 38 c -0.54 8.44 -0.27 16.8 0 25 c 0.06 1.67 0.77 5.33 1 5 c 1.57 -2.26 10.89 -20.5 17 -31 c 4.89 -8.41 9.55 -16.08 15 -24 c 5.09 -7.39 12.93 -18.09 16 -21 c 0.49 -0.47 2.35 1.86 3 3 c 1.86 3.25 3.09 7.61 5 11 c 1.01 1.8 2.5 3.95 4 5 c 1.48 1.04 4.02 1.87 6 2 c 7.52 0.5 16.19 0.62 24 0 c 4.66 -0.37 9.55 -1.52 14 -3 c 5.43 -1.81 10.73 -4.37 16 -7 c 4.19 -2.09 8.29 -4.52 12 -7 c 1.13 -0.76 2.93 -3.21 3 -3 c 0.1 0.31 -0.86 4.44 -2 6 c -3.82 5.26 -8.63 12.31 -14 16 c -9.61 6.61 -22.21 12.46 -34 17 c -18.55 7.13 -37.59 13.32 -57 18 c -18.1 4.36 -35.9 6.23 -55 9 c -23.85 3.46 -67.8 8.8 -69 9 c -0.24 0.04 9.37 0.63 14 0 c 52.14 -7.05 101.68 -15.44 156 -23 c 38.2 -5.31 110 -14 110 -14"/></svg>, 0, Josmar, Consulting Engineering, vps2cm3qq3@elatter.com, 2024-06-10, 192.168.29.189);


#
# TABLE STRUCTURE FOR: erpexpenses
#

DROP TABLE IF EXISTS `erpexpenses`;

CREATE TABLE `erpexpenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exp_name` varchar(100) NOT NULL,
  `category` int(11) NOT NULL,
  `currency` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `tax` int(11) DEFAULT NULL,
  `tax2` int(11) NOT NULL DEFAULT 0,
  `reference_no` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `expense_name` varchar(191) DEFAULT NULL,
  `clientid` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `billable` int(11) NOT NULL DEFAULT 0,
  `invoice_id` int(11) DEFAULT NULL,
  `paymentmode` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `dateadded` date NOT NULL DEFAULT current_timestamp(),
  `addedfrom` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clientid` (`clientid`),
  KEY `project_id` (`project_id`),
  KEY `category` (`category`),
  KEY `currency` (`currency`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `erpexpenses` (id, exp_name, category, currency, amount, tax, tax2, reference_no, note, expense_name, clientid, project_id, billable, invoice_id, paymentmode, date, dateadded, addedfrom) VALUES (40, last dup, 9, 11, 50000.00, 2, 2, , note, , 37, 23, 1, 19, 1, 2024-06-06, 2024-06-06, 1);
INSERT INTO `erpexpenses` (id, exp_name, category, currency, amount, tax, tax2, reference_no, note, expense_name, clientid, project_id, billable, invoice_id, paymentmode, date, dateadded, addedfrom) VALUES (41, Lst Expense, 3, 6, 5000.00, 1, 3, , Nothing, , 37, 23, 1, 20, 1, 2024-06-10, 2024-06-10, 1);
INSERT INTO `erpexpenses` (id, exp_name, category, currency, amount, tax, tax2, reference_no, note, expense_name, clientid, project_id, billable, invoice_id, paymentmode, date, dateadded, addedfrom) VALUES (42, Exp last, 1, 11, 4000.00, 1, 1, , note, , 37, 23, 0, 21, , 2024-06-12, 2024-06-11, 1);
INSERT INTO `erpexpenses` (id, exp_name, category, currency, amount, tax, tax2, reference_no, note, expense_name, clientid, project_id, billable, invoice_id, paymentmode, date, dateadded, addedfrom) VALUES (43, test, 2, 6, 100.00, 1, 2, asd, test notes, , 0, 0, 0, , , 2024-11-02, 2024-11-02, 1);
INSERT INTO `erpexpenses` (id, exp_name, category, currency, amount, tax, tax2, reference_no, note, expense_name, clientid, project_id, billable, invoice_id, paymentmode, date, dateadded, addedfrom) VALUES (44, test, 1, 6, 69.00, 0, 0, , 1000, , 37, 23, 1, , , 2024-11-02, 2024-11-02, 1);


#
# TABLE STRUCTURE FOR: estimate_items
#

DROP TABLE IF EXISTS `estimate_items`;

CREATE TABLE `estimate_items` (
  `est_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `estimate_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  PRIMARY KEY (`est_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (1, 44, finished_good, 22, 2, 1, 3000.00, 3000.00, 18, 9);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (7, 32, finished_good, 22, 2, 1, 3000.00, 3000.00, 18, 9);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (8, 33, finished_good, 21, 1, 1, 2000.00, 2000.00, 9, 18);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (12, 36, finished_good, 25, 8, 1, 120.00, 120.00, 9, 18);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (14, 40, finished_good, 22, 2, 1, 3000.00, 3000.00, 18, 9);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (15, 41, finished_good, 25, 8, 1, 120.00, 120.00, 9, 18);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (17, 49, finished_good, 22, 2, 1, 3000.00, 3000.00, 18, 9);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (18, 50, finished_good, 22, 2, 1, 3000.00, 3000.00, 18, 9);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (19, 52, finished_good, 22, 2, 1, 3000.00, 3000.00, 18, 9);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (20, 53, finished_good, 22, 2, 1, 3000.00, 3000.00, 18, 9);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (21, 54, finished_good, 22, 2, 1, 3000.00, 3000.00, 18, 9);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (22, 54, finished_good, 25, 8, 1, 120.00, 120.00, 20, 18);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (23, 49, finished_good, 25, 8, 1, 120.00, 120.00, 20, 18);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (24, 55, finished_good, 25, 8, 2, 120.00, 240.00, 20, 18);
INSERT INTO `estimate_items` (est_item_id, estimate_id, related_to, related_id, price_id, quantity, unit_price, amount, tax1, tax2) VALUES (25, 56, finished_good, 21, 1, 4, 2000.00, 8000.00, 9, 18);


#
# TABLE STRUCTURE FOR: estimates
#

DROP TABLE IF EXISTS `estimates`;

CREATE TABLE `estimates` (
  `estimate_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(140) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `estimate_date` date NOT NULL,
  `terms_condition` text NOT NULL,
  `shippingaddr_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `updated_at` varchar(50) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`estimate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (1, es90, 37, 2024-02-05, fsd, 15, 1707138996, 1707138996, 1);
INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (41, e-12, 46, 2024-01-22, afdsf, 0, 1698878592, 1707137307, 1);
INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (49, EST-0043, 37, 2024-02-05, <p>fsdffsfsf</p>, 0, 1707140970, 1707993552, 1);
INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (50, EST-0050, 37, 2024-02-06, <ol><li><h3>fdsfsdfds</h3></li><li><h2>fdsfas</h2></li><li><h2>fasdf</h2></li><li><h2>sdff</h2></li><li><h2>fdsf</h2></li><li><h2>fds</h2></li></ol>, 3, 1707197111, 1707197397, 1);
INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (52, EST-0051, 37, 2024-02-14, <p>fsf</p>, 0, 1707915986, 1707917474, 1);
INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (53, EST-0053, 46, 2024-02-14, <p>fsf</p>, 0, 1707917093, 1707917093, 1);
INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (54, EST-0054, 46, 2024-02-15, <p>dsffsf test</p>, 0, 1707989671, 1707991768, 1);
INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (55, EST-0055, 37, 2024-04-27, , 0, 1714200921, 1714200921, 1);
INSERT INTO `estimates` (estimate_id, code, cust_id, estimate_date, terms_condition, shippingaddr_id, created_at, updated_at, created_by) VALUES (56, EST-0056, 45, 2024-11-01, <p>test</p>, 0, 1730469185, 1730469185, 1);


#
# TABLE STRUCTURE FOR: expense_items
#

DROP TABLE IF EXISTS `expense_items`;

CREATE TABLE `expense_items` (
  `expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  `send_qty` int(11) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `expense_items` (expense_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (67, expense, 40, 0, 0, 0, 18, 1, 0.00, 50000.00, 9, 9, 0, 0, 2024-06-10 17:00:30);
INSERT INTO `expense_items` (expense_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (68, expense, 40, 0, 0, 0, 19, 1, 0.00, 50000.00, 9, 9, 0, 0, 2024-06-10 17:26:38);
INSERT INTO `expense_items` (expense_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (69, expense, 41, 0, 0, 0, 20, 1, 0.00, 5000.00, 9, 18, 0, 0, 2024-06-10 19:21:52);
INSERT INTO `expense_items` (expense_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (70, expense, 42, 0, 0, 0, 21, 1, 0.00, 4000.00, 9, 9, 0, 0, 2024-06-11 12:32:07);


#
# TABLE STRUCTURE FOR: expense_task
#

DROP TABLE IF EXISTS `expense_task`;

CREATE TABLE `expense_task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `expense_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `related_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `assignees` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `task_description` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `expense_task` (task_id, expense_id, name, status, start_date, due_date, related_id, priority, assignees, followers, task_description, created_by, created_at) VALUES (4, 40, Last Update, 3, 2024-06-06, 2024-06-23, 40, 1, 14, 14, aaaa, 1, 2024-06-07 12:06:52);


#
# TABLE STRUCTURE FOR: finished_goods
#

DROP TABLE IF EXISTS `finished_goods`;

CREATE TABLE `finished_goods` (
  `finished_good_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `short_desc` varchar(1000) NOT NULL,
  `long_desc` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`finished_good_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `finished_goods` (finished_good_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (21, test, rgawerh, wearghetdh, 13, dgedh465465, 2, 1, 1704177598, 1);
INSERT INTO `finished_goods` (finished_good_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (22, a, adfgf, dfsgf, 13, SO128, 2, 1, 1704177621, 1);
INSERT INTO `finished_goods` (finished_good_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (23, product1, fsda, agffsd, 14, dsfg4565, 2, 1, 1704863783, 1);
INSERT INTO `finished_goods` (finished_good_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (24, oil, hyfty, ytf, 14, 23, 2, 2, 1704864633, 1);
INSERT INTO `finished_goods` (finished_good_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (25, hairOil, test, test, 13, PD10, 2, 1, 1704865011, 1);


#
# TABLE STRUCTURE FOR: general_ledger
#

DROP TABLE IF EXISTS `general_ledger`;

CREATE TABLE `general_ledger` (
  `ledger_id` int(11) NOT NULL,
  `gl_acc_id` int(11) NOT NULL,
  `period` date NOT NULL,
  `actual_amt` decimal(16,2) NOT NULL,
  `balance_fwd` decimal(16,2) NOT NULL,
  PRIMARY KEY (`ledger_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `general_ledger` (ledger_id, gl_acc_id, period, actual_amt, balance_fwd) VALUES (1, 2, 2022-02-01, 0.00, 0.00);
INSERT INTO `general_ledger` (ledger_id, gl_acc_id, period, actual_amt, balance_fwd) VALUES (2, 2, 2022-01-01, 0.00, 0.00);
INSERT INTO `general_ledger` (ledger_id, gl_acc_id, period, actual_amt, balance_fwd) VALUES (9, 1, 2022-03-01, 4000.00, 4000.00);
INSERT INTO `general_ledger` (ledger_id, gl_acc_id, period, actual_amt, balance_fwd) VALUES (10, 2, 2022-03-01, -4000.00, -4000.00);
INSERT INTO `general_ledger` (ledger_id, gl_acc_id, period, actual_amt, balance_fwd) VALUES (17, 1, 2023-12-01, -10000.00, -10000.00);
INSERT INTO `general_ledger` (ledger_id, gl_acc_id, period, actual_amt, balance_fwd) VALUES (18, 15, 2023-12-01, 0.00, 15.00);
INSERT INTO `general_ledger` (ledger_id, gl_acc_id, period, actual_amt, balance_fwd) VALUES (21, 18, 2024-01-01, 0.00, 12254.00);


#
# TABLE STRUCTURE FOR: gl_accounts
#

DROP TABLE IF EXISTS `gl_accounts`;

CREATE TABLE `gl_accounts` (
  `gl_acc_id` int(11) NOT NULL,
  `acc_group_id` int(11) NOT NULL,
  `account_code` smallint(6) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `cash_flow` tinyint(4) NOT NULL,
  `order_num` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`gl_acc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `gl_accounts` (gl_acc_id, acc_group_id, account_code, account_name, cash_flow, order_num, created_at, created_by) VALUES (1, 3, 1010, petty cash, 4, 1, 1645248842, 1);
INSERT INTO `gl_accounts` (gl_acc_id, acc_group_id, account_code, account_name, cash_flow, order_num, created_at, created_by) VALUES (2, 4, 1011, Advertise, 2, 1, 1645248923, 1);
INSERT INTO `gl_accounts` (gl_acc_id, acc_group_id, account_code, account_name, cash_flow, order_num, created_at, created_by) VALUES (15, 8, 9999, test, 1, 3, 1703760391, 1);
INSERT INTO `gl_accounts` (gl_acc_id, acc_group_id, account_code, account_name, cash_flow, order_num, created_at, created_by) VALUES (18, 4, 32767, 78767778, 2, 0, 1705750016, 1);


#
# TABLE STRUCTURE FOR: grn
#

DROP TABLE IF EXISTS `grn`;

CREATE TABLE `grn` (
  `grn_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `delivered_on` varchar(20) NOT NULL,
  `updated_on` varchar(20) NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`grn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `grn` (grn_id, order_id, status, remarks, delivered_on, updated_on, updated_by) VALUES (1, 1, 1, one product nalla ella Grape 2345, 2024-04-27, 2024-04-27 07:51:00, 1);
INSERT INTO `grn` (grn_id, order_id, status, remarks, delivered_on, updated_on, updated_by) VALUES (2, 2, 1, okay, 2023-12-15, 2023-12-27 10:33:28, 1);
INSERT INTO `grn` (grn_id, order_id, status, remarks, delivered_on, updated_on, updated_by) VALUES (55846, 3, 1, rdyidrs, 2023-12-30, 2023-12-29 06:34:51, 1);
INSERT INTO `grn` (grn_id, order_id, status, remarks, delivered_on, updated_on, updated_by) VALUES (55847, 9, 1, xdcfgxj, 2024-01-03, 2024-01-02 07:37:05, 1);
INSERT INTO `grn` (grn_id, order_id, status, remarks, delivered_on, updated_on, updated_by) VALUES (55848, 1, 1, Test, 2024-01-09, 2024-01-09 05:46:51, 1);
INSERT INTO `grn` (grn_id, order_id, status, remarks, delivered_on, updated_on, updated_by) VALUES (55849, 3, 1, test, 2024-01-14, 2024-01-12 10:47:08, 1);


#
# TABLE STRUCTURE FOR: inventory_requisition
#

DROP TABLE IF EXISTS `inventory_requisition`;

CREATE TABLE `inventory_requisition` (
  `invent_req_id` int(11) NOT NULL AUTO_INCREMENT,
  `req_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`invent_req_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `inventory_requisition` (invent_req_id, req_id, related_to, related_id, qty) VALUES (1, 1, raw_material, 43, 5);
INSERT INTO `inventory_requisition` (invent_req_id, req_id, related_to, related_id, qty) VALUES (2, 1, raw_material, 53, 5);
INSERT INTO `inventory_requisition` (invent_req_id, req_id, related_to, related_id, qty) VALUES (3, 2, raw_material, 53, 1);
INSERT INTO `inventory_requisition` (invent_req_id, req_id, related_to, related_id, qty) VALUES (6, 5, raw_material, 47, 12);
INSERT INTO `inventory_requisition` (invent_req_id, req_id, related_to, related_id, qty) VALUES (7, 6, semi_finished, 17, 12);
INSERT INTO `inventory_requisition` (invent_req_id, req_id, related_to, related_id, qty) VALUES (8, 7, raw_material, 42, 4);
INSERT INTO `inventory_requisition` (invent_req_id, req_id, related_to, related_id, qty) VALUES (9, 7, raw_material, 50, 4);
INSERT INTO `inventory_requisition` (invent_req_id, req_id, related_to, related_id, qty) VALUES (10, 7, raw_material, 56, 4);


#
# TABLE STRUCTURE FOR: inventory_services
#

DROP TABLE IF EXISTS `inventory_services`;

CREATE TABLE `inventory_services` (
  `invent_service_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `short_desc` varchar(1000) NOT NULL,
  `long_desc` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `tax1` int(11) NOT NULL,
  `tax2` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`invent_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `inventory_services` (invent_service_id, name, short_desc, long_desc, group_id, code, price, tax1, tax2, created_at, created_by) VALUES (10, test, sdfharth, esrhryjhry, 19, SO128, 156532.00, 1, 1, 1704178086, 1);
INSERT INTO `inventory_services` (invent_service_id, name, short_desc, long_desc, group_id, code, price, tax1, tax2, created_at, created_by) VALUES (11, bottle, rexs, rse, 19, 12, 120.00, 1, 2, 1704864422, 1);


#
# TABLE STRUCTURE FOR: inventory_warehouse
#

DROP TABLE IF EXISTS `inventory_warehouse`;

CREATE TABLE `inventory_warehouse` (
  `invent_house_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  PRIMARY KEY (`invent_house_id`)
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (41, 2, semi_finished, 42);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (42, 2, semi_finished, 7);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (43, 2, semi_finished, 8);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (46, 1, finished_good, 3);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (49, 2, finished_good, 4);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (50, 1, finished_good, 4);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (51, 2, finished_good, 5);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (52, 1, finished_good, 5);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (55, 0, raw_material, 18);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (56, 0, raw_material, 16);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (57, 0, raw_material, 25);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (58, 9, raw_material, 26);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (59, 8, raw_material, 26);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (60, 6, raw_material, 26);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (61, 9, raw_material, 27);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (62, 8, raw_material, 27);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (63, 6, raw_material, 27);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (64, 9, raw_material, 28);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (65, 8, raw_material, 28);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (66, 6, raw_material, 28);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (67, 9, raw_material, 29);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (68, 8, raw_material, 29);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (69, 6, raw_material, 29);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (70, 9, raw_material, 30);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (71, 8, raw_material, 30);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (72, 6, raw_material, 30);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (73, 9, raw_material, 31);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (74, 8, raw_material, 31);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (75, 6, raw_material, 31);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (76, 9, raw_material, 32);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (77, 8, raw_material, 32);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (78, 6, raw_material, 32);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (79, 9, raw_material, 33);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (80, 8, raw_material, 33);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (81, 6, raw_material, 33);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (82, 9, raw_material, 34);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (83, 8, raw_material, 34);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (84, 6, raw_material, 34);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (85, 9, raw_material, 35);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (86, 8, raw_material, 35);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (87, 6, raw_material, 35);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (88, 9, raw_material, 36);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (89, 8, raw_material, 36);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (90, 6, raw_material, 36);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (91, 9, raw_material, 37);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (92, 8, raw_material, 37);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (93, 6, raw_material, 37);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (94, 9, raw_material, 38);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (95, 8, raw_material, 38);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (96, 6, raw_material, 38);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (97, 9, raw_material, 39);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (98, 8, raw_material, 39);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (99, 6, raw_material, 39);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (100, 9, raw_material, 40);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (101, 8, raw_material, 40);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (102, 6, raw_material, 40);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (103, 9, raw_material, 41);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (104, 8, raw_material, 41);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (105, 6, raw_material, 41);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (112, 11, semi_finished, 9);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (118, 11, semi_finished, 10);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (119, 11, semi_finished, 11);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (120, 9, semi_finished, 11);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (121, 8, semi_finished, 11);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (122, 7, semi_finished, 11);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (123, 6, semi_finished, 11);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (124, 4, semi_finished, 11);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (125, 3, semi_finished, 11);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (126, 11, finished_good, 6);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (128, 11, finished_good, 7);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (135, 11, finished_good, 15);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (136, 9, finished_good, 15);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (137, 8, finished_good, 15);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (138, 6, finished_good, 15);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (139, 4, finished_good, 15);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (140, 0, raw_material, 2);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (141, 0, raw_material, 3);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (142, 0, raw_material, 17);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (143, 0, raw_material, 20);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (144, 0, raw_material, 21);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (145, 0, raw_material, 24);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (146, 0, raw_material, 42);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (147, 0, raw_material, 43);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (148, 9, raw_material, 44);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (149, 8, raw_material, 44);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (152, 0, raw_material, 45);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (154, 0, raw_material, 47);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (155, 0, raw_material, 46);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (157, 11, raw_material, 49);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (160, 0, raw_material, 48);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (161, 2, semi_finished, 2);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (162, 1, semi_finished, 2);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (163, 2, finished_good, 2);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (164, 13, finished_good, 16);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (165, 13, finished_good, 17);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (166, 13, finished_good, 18);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (167, 0, raw_material, 50);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (168, 13, semi_finished, 12);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (170, 6, semi_finished, 13);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (171, 1, semi_finished, 14);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (172, 2, semi_finished, 14);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (173, 8, semi_finished, 14);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (174, 0, raw_material, 51);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (175, 0, raw_material, 52);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (177, 0, raw_material, 53);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (178, 8, semi_finished, 15);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (179, 4, semi_finished, 16);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (180, 6, semi_finished, 17);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (181, 11, finished_good, 19);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (182, 8, finished_good, 20);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (183, 9, finished_good, 21);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (184, 2, finished_good, 22);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (185, 9, raw_material, 54);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (186, 9, raw_material, 55);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (188, 0, raw_material, 56);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (189, 9, raw_material, 57);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (191, 0, raw_material, 58);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (193, 0, raw_material, 59);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (197, 0, raw_material, 61);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (199, 0, raw_material, 62);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (202, 0, raw_material, 63);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (203, 13, raw_material, 64);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (204, 11, raw_material, 64);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (205, 9, raw_material, 64);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (206, 8, raw_material, 64);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (207, 6, raw_material, 64);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (208, 4, raw_material, 64);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (209, 2, raw_material, 64);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (210, 1, raw_material, 64);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (211, 4, raw_material, 65);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (212, 0, raw_material, 60);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (214, 0, raw_material, 66);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (215, 2, raw_material, 67);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (216, 4, semi_finished, 18);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (217, 11, finished_good, 23);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (218, 9, finished_good, 24);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (219, 11, finished_good, 25);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (221, 11, raw_material, 69);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (223, 0, raw_material, 70);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (224, 9, finished_good, 26);
INSERT INTO `inventory_warehouse` (invent_house_id, warehouse_id, related_to, related_id) VALUES (225, 0, raw_material, 68);


#
# TABLE STRUCTURE FOR: journal_entry
#

DROP TABLE IF EXISTS `journal_entry`;

CREATE TABLE `journal_entry` (
  `journal_id` int(11) NOT NULL,
  `gl_acc_id` int(11) NOT NULL,
  `credit` tinyint(1) NOT NULL,
  `debit` tinyint(1) NOT NULL,
  `narration` text NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `posted` tinyint(1) NOT NULL DEFAULT 0,
  `posted_date` varchar(20) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `prev_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`journal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (21, 1, 0, 1, [ Finance Automation added Marketing Entry ], 2000.00, 2022-03-02, 0, 1, 2022-03-02 18:05:11, 1646224511, 1, marketing, 1, 2000.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (22, 2, 1, 0, [ Finance Automation added Marketing Entry ], 2000.00, 2022-03-02, 0, 1, 2022-03-02 18:05:11, 1646224511, 1, marketing, 1, 2000.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (23, 1, 0, 1, [ Finance Automation added Marketing Entry ], 2000.00, 2022-03-02, 0, 1, 2022-03-02 18:08:51, 1646224731, 1, marketing, 1, 2000.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (24, 2, 1, 0, [ Finance Automation added Marketing Entry ], 2000.00, 2022-03-02, 0, 1, 2022-03-02 18:08:51, 1646224731, 1, marketing, 1, 2000.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (25, 1, 1, 0, , 5000.00, 2023-12-24, 0, 1, 2023-12-23, 1703311285, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (26, 1, 0, 1, , 5000.00, 2023-12-24, 0, 1, 2023-12-23, 1703311285, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (27, 1, 1, 0, , 5000.00, 2023-12-24, 0, 1, 2024-01-09, 1703311401, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (28, 1, 0, 1, , 5000.00, 2023-12-24, 0, 0, , 1703311401, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (29, 1, 1, 0, , 5000.00, 2023-12-24, 0, 0, , 1703311809, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (30, 1, 0, 1, , 5000.00, 2023-12-24, 0, 0, , 1703311809, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (31, 1, 1, 0, , 5000.00, 2023-12-24, 0, 1, 2023-12-26, 1703311839, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (33, 1, 1, 0, edsgatz, 1000.00, 2023-12-28, 1, 0, , 1703843930, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (34, 2, 0, 1, sdzfvwgatf, 1000.00, 2023-12-28, 1, 0, , 1703843930, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (35, 2, 0, 1, , 500.00, 2024-01-09, 0, 0, , 1704802663, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (36, 1, 0, 1, , 500.00, 2024-01-09, 0, 0, , 1704802663, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (37, 1, 0, 1, , 100.00, 2024-01-09, 0, 0, , 1704802663, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (38, 1, 1, 0, , 1100.00, 2024-01-09, 0, 0, , 1704802663, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (39, 1, 1, 0, fhsfh, 100.00, 2024-01-19, 0, 0, , 1705750247, 1, , 0, 0.00);
INSERT INTO `journal_entry` (journal_id, gl_acc_id, credit, debit, narration, amount, transaction_date, type, posted, posted_date, created_at, created_by, related_to, related_id, prev_amount) VALUES (40, 2, 0, 1, vdhsdf, 100.00, 2024-01-19, 0, 0, , 1705750247, 1, , 0, 0.00);


#
# TABLE STRUCTURE FOR: knowledge_base
#

DROP TABLE IF EXISTS `knowledge_base`;

CREATE TABLE `knowledge_base` (
  `article_id` int(100) NOT NULL AUTO_INCREMENT,
  `article_subject` varchar(100) NOT NULL,
  `article_group_id` int(100) NOT NULL,
  `Internal_article` int(100) NOT NULL,
  `disabled` int(100) NOT NULL,
  `article_description` varchar(200) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `knowledge_base` (article_id, article_subject, article_group_id, Internal_article, disabled, article_description, date_added) VALUES (13, A_!, 96, 1, 0, <p>fg</p>, 2024-05-10 14:12:43);
INSERT INTO `knowledge_base` (article_id, article_subject, article_group_id, Internal_article, disabled, article_description, date_added) VALUES (14, A_2, 97, 0, 0, <p>A_2</p>, 2024-05-10 14:16:35);


#
# TABLE STRUCTURE FOR: knowledgebase_groups
#

DROP TABLE IF EXISTS `knowledgebase_groups`;

CREATE TABLE `knowledgebase_groups` (
  `group_id` int(100) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  `short_description` varchar(100) NOT NULL,
  `group_order` int(100) NOT NULL,
  `disabled` int(100) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (96, group, hello, 6, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (97, group_2, none, 2, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (98, ssaaas, aasasasas, 0, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (99, cvbvcb, vcvbvcb, 0, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (100, ddsdfsdfsdfsdfdf, dfdfdfdf, 111, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (101, fgfgf, gfgfgfg, 111, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (102, sdfdfdf, dffdffdfdf, 0, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (103, sdfdfdfdf111, dfdfdfdfdfds, 0, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (104, gfhghg, ghghgh, 0, 1);
INSERT INTO `knowledgebase_groups` (group_id, group_name, short_description, group_order, disabled) VALUES (105, yuiyh, hjjhjhj, 0, 1);


#
# TABLE STRUCTURE FOR: lead_source
#

DROP TABLE IF EXISTS `lead_source`;

CREATE TABLE `lead_source` (
  `source_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_name` varchar(40) NOT NULL,
  `marketing_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`source_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (1, Ads, 0);
INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (2, Google, 0);
INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (3, Facebook, 0);
INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (4, Email Marketing, 0);
INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (5, Tellecaller, 1);
INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (6, twitter, 2);
INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (7, instagram ads, 3);
INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (32, offline, 28);
INSERT INTO `lead_source` (source_id, source_name, marketing_id) VALUES (33, affiliate, 29);


#
# TABLE STRUCTURE FOR: leads
#

DROP TABLE IF EXISTS `leads`;

CREATE TABLE `leads` (
  `lead_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `assigned_to` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `position` varchar(140) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `company` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`lead_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (1, 2, 4, 1, TDWWS, test, Alvarpettai , Chennai, Tamil Nadu, India, 600087, 9564784521, Demo@test.com, www.TDWWS.com, GB BABA , demo, , 1714196976, 1);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (5, 1, 4, 2, john, Purchase Manager, No.164, First Floor, Arcot Rd, Valasaravakkam, Chennai, Tamil Nadu, India, 600087, 09080780700, support@example.com, www.qbrainstorm.com, john enterprise, gh, , , 1);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (8, 3, 0, 3, admin, test, test, test, test, test, test, test, 9876543210, support@gmail.com, test, test, , , 0);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (9, 2, 4, 2, shankar, manager, test, chennai, tamilnadu, india, 6000125, 9876543210, shankar@qqq.com, , Shankar Company, , , , 0);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (10, 1, 3, 3, Saran, Sales, test, chennai, tamilnadu, india, 6000125, 9876543210, saran@qqq.com, , Shankar Company, test, , , 0);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (11, 1, 0, 3, Raj, Driver, test, chennai, tamilnadu, india, 6000125, 9876543210, raj@qqq.com, , Shankar Company, demo, , , 0);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (15, 1, 1, 3, kumar, 63680954, No Position, -, Dhrmapuri, TN, INDIA, 636809, 9874561230, 57665, 78, kumar@gmail.com, , 1703061296, 1);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (19, 3, 1, 2, Thamizharasi, web developer, chennai, Thiruvallur, tamilnadu, India, 602024, 01234567890, test@gmail.com, , qbs, sdgagdsfgs, gfdsheshsdh, 1705746799, 1);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (20, 4, 0, 3, sgh, gfsgff, 35, chennai, tamilnadu, india, 600087, 8556654, tamil@gmail.com, , qbs, cdbsdhsdh, , 1705747957, 1);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (21, 2, 1, 2, name, devl, test, chennai, TN, india, 636809, 1234567890, pasupthi@gamil.com, www.test.com, test, tesst, , 1705748139, 1);
INSERT INTO `leads` (lead_id, source_id, status, assigned_to, name, position, address, city, state, country, zip, phone, email, website, company, description, remarks, created_at, created_by, updated_at) VALUES (22, 1, 0, 1, Q Brainstorm, test, test 1, Chennai, Tamil Nadu, India, 600087, 09080780700, support@qbrainstorm.com, test, ecommerce website development company in chennai, test, test, 1714368375, 1);


#
# TABLE STRUCTURE FOR: marketing
#

DROP TABLE IF EXISTS `marketing`;

CREATE TABLE `marketing` (
  `marketing_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `done_by` varchar(140) NOT NULL,
  `company` varchar(140) NOT NULL,
  `address` varchar(1000) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL,
  `phone1` varchar(13) NOT NULL,
  `phone2` varchar(13) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`marketing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `marketing` (marketing_id, name, related_to, related_id, amount, done_by, company, address, active, email, phone1, phone2, description, created_at, created_by) VALUES (2, Kemar, finished_good, 21, 3000.00, kevin, test company, , 1, kumar@gmail.com, 09845612304, 8428054262, test, 1645790766, 1);
INSERT INTO `marketing` (marketing_id, name, related_to, related_id, amount, done_by, company, address, active, email, phone1, phone2, description, created_at, created_by) VALUES (3, gear ad, semi_finished, 1, 2000.00, joe, , test, 1, , +919876543210, , test, 1645792193, 1);
INSERT INTO `marketing` (marketing_id, name, related_to, related_id, amount, done_by, company, address, active, email, phone1, phone2, description, created_at, created_by) VALUES (29, Kumar, semi_finished, 1, 34.35, ghhth, qbrainstorm, Test Address
test address, 1, support@qbrainstorm.com, 09080780700, 09080780708, 4343434343, 1703078657, 1);


#
# TABLE STRUCTURE FOR: master
#

DROP TABLE IF EXISTS `master`;

CREATE TABLE `master` (
  `master_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`master_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `master` (master_id, name, email, password) VALUES (1, Master, support@qbrainstorm.com, $2y$10$FCznQZaoK.VRnpkl3YmbYu8gRm.z5fsRRPHZq.r3.0vnZAl6RVgt6);


#
# TABLE STRUCTURE FOR: mrp_bom
#

DROP TABLE IF EXISTS `mrp_bom`;

CREATE TABLE `mrp_bom` (
  `bom_id` int(200) NOT NULL AUTO_INCREMENT,
  `product_id` int(200) NOT NULL,
  `related_to` varchar(200) NOT NULL,
  `mrp_scheduling_id` int(255) NOT NULL,
  `warhouse_id` int(11) NOT NULL,
  `quantity` int(200) NOT NULL,
  `material_id` int(200) NOT NULL,
  `planning_id` int(100) NOT NULL,
  `material_related_to` varchar(200) NOT NULL,
  `material_consumption` int(200) NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`bom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# TABLE STRUCTURE FOR: mrp_dataset_forecast
#

DROP TABLE IF EXISTS `mrp_dataset_forecast`;

CREATE TABLE `mrp_dataset_forecast` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `related_to` varchar(200) NOT NULL,
  `related_id` int(200) NOT NULL,
  `quantity` int(200) NOT NULL,
  `current_stocks_on_inventory` int(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (1, 2023-01-18 17:01:37, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (2, 2023-11-20 09:45:30, finished_good, 21, 114, 88);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (3, 2023-06-21 20:26:41, finished_good, 21, 94, 97);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (4, 2023-08-30 05:53:36, finished_good, 22, 112, 110);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (5, 2023-12-24 04:44:30, finished_good, 22, 108, 109);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (6, 2023-06-28 13:37:40, finished_good, 23, 81, 84);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (7, 2023-12-18 01:21:53, finished_good, 23, 85, 97);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (8, 2023-01-17 03:41:53, finished_good, 24, 107, 112);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (9, 2023-02-20 13:05:36, finished_good, 24, 91, 72);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (10, 2023-01-10 17:52:04, finished_good, 25, 85, 79);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (11, 2023-04-04 14:12:28, finished_good, 25, 98, 85);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (12, 2023-04-09 23:35:19, finished_good, 21, 90, 85);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (13, 2023-11-18 12:57:45, finished_good, 21, 89, 100);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (14, 2023-12-26 07:44:30, finished_good, 22, 111, 111);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (15, 2023-07-28 06:57:45, finished_good, 22, 98, 82);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (16, 2023-11-06 03:12:05, finished_good, 23, 104, 96);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (17, 2023-11-10 11:16:29, finished_good, 23, 113, 81);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (18, 2023-07-06 16:21:12, finished_good, 24, 108, 81);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (19, 2023-10-26 21:07:19, finished_good, 24, 84, 108);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (20, 2023-12-24 13:11:45, finished_good, 25, 87, 99);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (21, 2023-08-31 06:32:31, finished_good, 25, 109, 93);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (22, 2023-03-03 23:03:17, finished_good, 21, 120, 88);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (23, 2023-07-07 01:28:05, finished_good, 21, 96, 82);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (24, 2023-11-02 09:18:48, finished_good, 22, 80, 108);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (25, 2023-05-18 19:19:24, finished_good, 22, 101, 120);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (26, 2023-05-04 12:50:35, finished_good, 23, 94, 78);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (27, 2023-05-16 21:59:05, finished_good, 23, 105, 96);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (28, 2023-04-10 10:09:32, finished_good, 24, 83, 98);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (29, 2023-08-01 23:11:37, finished_good, 24, 112, 104);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (30, 2023-01-12 12:36:14, finished_good, 25, 120, 116);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (31, 2023-09-12 11:18:40, finished_good, 25, 99, 118);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (32, 2023-05-07 22:30:34, finished_good, 21, 117, 103);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (33, 2023-07-22 03:30:38, finished_good, 21, 83, 99);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (34, 2023-08-06 10:48:27, finished_good, 22, 118, 106);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (35, 2023-01-19 22:14:14, finished_good, 22, 98, 83);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (36, 2023-08-12 09:59:52, finished_good, 23, 117, 106);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (37, 2023-06-26 15:25:19, finished_good, 23, 97, 91);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (38, 2023-02-26 03:03:51, finished_good, 24, 80, 99);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (39, 2023-11-27 10:05:23, finished_good, 24, 116, 118);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (40, 2023-01-09 09:17:44, finished_good, 25, 82, 87);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (41, 2023-07-04 21:22:53, finished_good, 25, 118, 110);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (42, 2023-08-23 06:24:01, finished_good, 21, 89, 98);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (43, 2023-11-01 11:01:20, finished_good, 21, 86, 101);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (44, 2023-09-24 01:00:50, finished_good, 22, 106, 101);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (45, 2023-04-21 07:57:00, finished_good, 22, 119, 93);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (46, 2023-02-11 20:56:15, finished_good, 23, 116, 79);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (47, 2023-09-21 17:29:11, finished_good, 23, 97, 89);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (48, 2023-06-17 07:51:57, finished_good, 24, 118, 78);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (49, 2023-02-21 18:41:00, finished_good, 24, 86, 114);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (50, 2023-07-06 15:35:14, finished_good, 25, 88, 117);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (51, 2023-03-26 15:32:29, finished_good, 25, 86, 103);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (52, 2024-01-23 13:27:44, finished_good, 21, 1, 5);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (53, 2024-01-27 15:59:40, finished_good, 21, 1, 5);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (54, 2024-02-06 15:09:53, finished_good, 25, 1, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (55, 2024-02-06 15:12:59, finished_good, 25, 1, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (56, 2024-02-06 15:13:31, finished_good, 25, 1, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (57, 2024-02-06 15:16:41, finished_good, 25, 1, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (58, 2024-02-08 10:13:44, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (59, 2024-02-08 10:14:58, finished_good, 23, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (60, 2024-02-08 11:17:10, finished_good, 21, 1, 5);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (61, 2024-02-08 11:17:51, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (62, 2024-02-08 11:18:26, finished_good, 25, 2, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (63, 2024-02-14 19:11:26, finished_good, 23, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (64, 2024-02-14 19:11:26, finished_good, 21, 2, 5);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (65, 2024-02-14 19:11:47, finished_good, 23, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (66, 2024-02-14 19:11:47, finished_good, 21, 2, 5);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (67, 2024-02-14 19:12:11, finished_good, 25, 2, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (68, 2024-02-14 19:12:44, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (69, 2024-02-14 19:12:44, finished_good, 23, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (70, 2024-02-15 17:46:17, finished_good, 25, 1, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (71, 2024-02-15 17:46:17, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (72, 2024-02-15 17:53:30, finished_good, 25, 1, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (73, 2024-02-15 17:53:30, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (74, 2024-02-15 17:54:32, finished_good, 25, 1, 2);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (75, 2024-02-15 17:54:32, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (76, 2024-02-15 18:03:48, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (77, 2024-02-15 18:03:48, finished_good, 23, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (78, 2024-02-15 18:03:59, finished_good, 22, 1, 1);
INSERT INTO `mrp_dataset_forecast` (id, timestamp, related_to, related_id, quantity, current_stocks_on_inventory) VALUES (79, 2024-02-15 18:03:59, finished_good, 23, 1, 1);


#
# TABLE STRUCTURE FOR: mrp_scheduling
#

DROP TABLE IF EXISTS `mrp_scheduling`;

CREATE TABLE `mrp_scheduling` (
  `mrp_scheduling_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `bin_name` varchar(140) NOT NULL,
  `mfg_date` date NOT NULL,
  `batch_no` varchar(140) NOT NULL,
  `lot_no` varchar(140) NOT NULL,
  `stock` int(11) NOT NULL,
  `planning_id` int(11) NOT NULL,
  PRIMARY KEY (`mrp_scheduling_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mrp_scheduling` (mrp_scheduling_id, sku, related_to, related_id, warehouse_id, price_id, bin_name, mfg_date, batch_no, lot_no, stock, planning_id) VALUES (0, 9, finished_good, 23, 11, 2, 2, 2024-02-23, 4, 3, 50, 4);
INSERT INTO `mrp_scheduling` (mrp_scheduling_id, sku, related_to, related_id, warehouse_id, price_id, bin_name, mfg_date, batch_no, lot_no, stock, planning_id) VALUES (6, SKU62, finished_good, 23, 2, 1, BIN34, 2024-01-23, 45, 16, 6, 1);
INSERT INTO `mrp_scheduling` (mrp_scheduling_id, sku, related_to, related_id, warehouse_id, price_id, bin_name, mfg_date, batch_no, lot_no, stock, planning_id) VALUES (7, #12345, finished_good, 23, 2, 1, A5, 2024-01-25, 10, 55, 6, 1);
INSERT INTO `mrp_scheduling` (mrp_scheduling_id, sku, related_to, related_id, warehouse_id, price_id, bin_name, mfg_date, batch_no, lot_no, stock, planning_id) VALUES (8, SKU63, finished_good, 23, 2, 1, BIN34, 2024-01-23, 45, 16, 6, 1);
INSERT INTO `mrp_scheduling` (mrp_scheduling_id, sku, related_to, related_id, warehouse_id, price_id, bin_name, mfg_date, batch_no, lot_no, stock, planning_id) VALUES (9, SKU65, finished_good, 23, 4, 2, BIN34, 2024-01-24, 45, 16, 6, 1);
INSERT INTO `mrp_scheduling` (mrp_scheduling_id, sku, related_to, related_id, warehouse_id, price_id, bin_name, mfg_date, batch_no, lot_no, stock, planning_id) VALUES (10, SKU66, finished_good, 23, 1, 1, BIN34, 2024-01-24, 45, 16, 100, 1);


#
# TABLE STRUCTURE FOR: notification
#

DROP TABLE IF EXISTS `notification`;

CREATE TABLE `notification` (
  `notify_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(140) NOT NULL,
  `notify_text` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `notify_email` tinyint(1) NOT NULL DEFAULT 0,
  `notify_at` datetime NOT NULL,
  `is_notified` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `related_to` varchar(100) NOT NULL,
  `related_id` int(11) NOT NULL,
  `related_base_url` varchar(100) NOT NULL,
  `job_id` int(11) NOT NULL,
  PRIMARY KEY (`notify_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `notification` (notify_id, title, notify_text, status, user_id, notify_email, notify_at, is_notified, created_at, updated_at, created_by, related_to, related_id, related_base_url, job_id) VALUES (5, Product Inspection, Kindly Inspect the Product Before sending it., 0, 1, 0, 2024-06-14 14:10:00, 0, 2024-06-08, 0, 1, quotation, 2, erp.sale.quotations.view, 0);
INSERT INTO `notification` (notify_id, title, notify_text, status, user_id, notify_email, notify_at, is_notified, created_at, updated_at, created_by, related_to, related_id, related_base_url, job_id) VALUES (6, For Ashoke, Kindly see if Products are in good shape, 0, 1, 0, 2024-06-14 14:10:00, 0, 2024-06-08, 0, 1, quotation, 2, erp.sale.quotations.view, 0);
INSERT INTO `notification` (notify_id, title, notify_text, status, user_id, notify_email, notify_at, is_notified, created_at, updated_at, created_by, related_to, related_id, related_base_url, job_id) VALUES (14, Expense Reminder, save this, 0, 16, 1, 2024-06-14 14:10:00, 0, 2024-06-08, 0, 1, expense, 40, erp.expenses.view.page, 0);
INSERT INTO `notification` (notify_id, title, notify_text, status, user_id, notify_email, notify_at, is_notified, created_at, updated_at, created_by, related_to, related_id, related_base_url, job_id) VALUES (15, helloo, ghjgfgh, 0, 3, 1, 2024-06-14 14:10:00, 0, 2024-06-14, 0, 1, sale_invoice, 19, , 146);
INSERT INTO `notification` (notify_id, title, notify_text, status, user_id, notify_email, notify_at, is_notified, created_at, updated_at, created_by, related_to, related_id, related_base_url, job_id) VALUES (16, helloo, wqwqwqw, 0, 1, 0, 0000-00-00 00:00:00, 0, 0000-00-00, 0, 1, sale_invoice, 19, , 147);
INSERT INTO `notification` (notify_id, title, notify_text, status, user_id, notify_email, notify_at, is_notified, created_at, updated_at, created_by, related_to, related_id, related_base_url, job_id) VALUES (17, Expense Reminder, a, 0, 14, 0, 2024-06-12 19:22:00, 0, 2024-06-10, 0, 1, expense, 41, erp.expenses.view.page, 0);
INSERT INTO `notification` (notify_id, title, notify_text, status, user_id, notify_email, notify_at, is_notified, created_at, updated_at, created_by, related_to, related_id, related_base_url, job_id) VALUES (28, Goals Notification, not_goal_message_success, 0, 16, 0, 2024-06-14 12:04:55, 1, 2024-06-14, 0, 1, Goal, 6, , 0);


#
# TABLE STRUCTURE FOR: pack_records
#

DROP TABLE IF EXISTS `pack_records`;

CREATE TABLE `pack_records` (
  `pack_rec_id` int(11) NOT NULL,
  `pack_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `grn_id` int(11) NOT NULL,
  `mfg_date` varchar(20) NOT NULL,
  `batch_no` varchar(140) NOT NULL,
  `lot_no` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`pack_rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# TABLE STRUCTURE FOR: pack_unit
#

DROP TABLE IF EXISTS `pack_unit`;

CREATE TABLE `pack_unit` (
  `pack_unit_id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `bin_name` varchar(140) NOT NULL,
  `mfg_date` date NOT NULL,
  `batch_no` varchar(140) NOT NULL,
  `lot_no` varchar(140) NOT NULL,
  PRIMARY KEY (`pack_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (2, 54, raw_material, 2, , 2022-02-04);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (3, 4, raw_material, 2, , 2022-02-01);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (4, 4, raw_material, 2, , 2022-02-06, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (5, 5, raw_material, 2, , 2022-02-07, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (6, 6, raw_material, 2, , 2022-02-08, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (7, 7, raw_material, 2, , 2022-02-09, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (8, 8, raw_material, 2, , 2022-02-10, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (10, 13, raw_material, 16, , 2022-02-15, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (11, 14, raw_material, 16, , 2022-02-16, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (12, 15, raw_material, 16, , 2022-02-17, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (13, 16, raw_material, 16, , 2022-02-18, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (14, 17, raw_material, 16, , 2022-02-19, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (15, 18, raw_material, 16, , 2022-02-20, , 
);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (16, ABC12, finished_good, 4, 123, 2022-04-04);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (17, ABC13, finished_good, 4, , 2022-04-04);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (18, ABC135, finished_good, 4, 1235, 2022-04-29);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (21, 85, finished_good, 21, tamil, 2024-01-03, 10, 55);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (22, 12, semi_finished, 17, 3, 2024-01-04, 2, 3);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (23, 26, semi_finished, 17, 8, 2024-01-03, 2, 7);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (24, 123, finished_good, 22, tt, 2024-01-19, tts, sf);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (25, dsh868, finished_good, 25, 9, 2024-01-10, 2, 3);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (26, TEST1234, finished_good, 21, B44, 2024-01-10, 5, 801);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (27, 32, semi_finished, 18, e, 2024-01-13, fd, fsdf);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (28, sk1, finished_good, 25, 23, 2024-01-26, 432, dsf);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (29, #12345, finished_good, 21, A5, 2024-01-28, 10, 12);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (31, SKU56, raw_material, 50, BIN34, 2024-01-27, 45, 16);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (32, SKU57, finished_good, 21, BIN34, 2024-01-20, 45, 16);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (33, SKU58, semi_finished, 17, BIN34, 2024-01-20, 45, 16);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (34, SKU59, raw_material, 50, BIN34, 2024-01-23, 45, 16);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (35, SKU60, finished_good, 23, BIN34, 2024-01-22, 45, 16);
INSERT INTO `pack_unit` (pack_unit_id, sku, related_to, related_id, bin_name, mfg_date, batch_no, lot_no) VALUES (36, SKU61, raw_material, 47, BIN34, 2024-01-24, 45, 16);


#
# TABLE STRUCTURE FOR: packs
#

DROP TABLE IF EXISTS `packs`;

CREATE TABLE `packs` (
  `pack_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `capacity` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`pack_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `packs` (pack_id, name, capacity, related_to, related_id, height, width, description, created_at, created_by) VALUES (1, test, 25, finished_good, 4, 0, 0, good, 1647931364, 1);
INSERT INTO `packs` (pack_id, name, capacity, related_to, related_id, height, width, description, created_at, created_by) VALUES (2, Pack1, 25, raw_material, 2, 0, 0, , 1647931411, 1);


#
# TABLE STRUCTURE FOR: payment_modes
#

DROP TABLE IF EXISTS `payment_modes`;

CREATE TABLE `payment_modes` (
  `payment_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_modes` (payment_id, name, description, active, created_at, created_by) VALUES (1, CASH, Money in raw form, 1, 1647351646, 1);
INSERT INTO `payment_modes` (payment_id, name, description, active, created_at, created_by) VALUES (2, Bank Transfer, test, 1, 1647351681, 1);
INSERT INTO `payment_modes` (payment_id, name, description, active, created_at, created_by) VALUES (3, RTGS, , 1, 1647351724, 1);
INSERT INTO `payment_modes` (payment_id, name, description, active, created_at, created_by) VALUES (6, cheque, dghgjgdj, 0, 1703828966, 1);


#
# TABLE STRUCTURE FOR: payroll_additions
#

DROP TABLE IF EXISTS `payroll_additions`;

CREATE TABLE `payroll_additions` (
  `pay_add_id` int(11) NOT NULL,
  `pay_entry_id` int(11) NOT NULL,
  `add_id` int(11) NOT NULL,
  PRIMARY KEY (`pay_add_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (1, 2, 2);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (2, 3, 1);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (3, 2, 1);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (4, 8, 3);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (5, 8, 2);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (6, 9, 3);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (7, 9, 2);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (8, 10, 3);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (9, 11, 3);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (10, 12, 2);
INSERT INTO `payroll_additions` (pay_add_id, pay_entry_id, add_id) VALUES (11, 13, 2);


#
# TABLE STRUCTURE FOR: payroll_deductions
#

DROP TABLE IF EXISTS `payroll_deductions`;

CREATE TABLE `payroll_deductions` (
  `pay_deduct_id` int(11) NOT NULL,
  `pay_entry_id` int(11) NOT NULL,
  `deduct_id` int(11) NOT NULL,
  PRIMARY KEY (`pay_deduct_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (1, 2, 2);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (3, 3, 2);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (6, 8, 2);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (7, 9, 2);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (8, 10, 3);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (9, 10, 2);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (10, 10, 1);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (11, 11, 2);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (12, 12, 3);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (13, 13, 3);
INSERT INTO `payroll_deductions` (pay_deduct_id, pay_entry_id, deduct_id) VALUES (14, 13, 2);


#
# TABLE STRUCTURE FOR: payroll_entry
#

DROP TABLE IF EXISTS `payroll_entry`;

CREATE TABLE `payroll_entry` (
  `pay_entry_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_from` date NOT NULL,
  `payment_to` date NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`pay_entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payroll_entry` (pay_entry_id, name, payment_date, payment_from, payment_to, processed) VALUES (2, Jan salary, 2022-04-28, 2022-01-01, 2022-01-31, 1);
INSERT INTO `payroll_entry` (pay_entry_id, name, payment_date, payment_from, payment_to, processed) VALUES (3, Feb salary, 2022-04-28, 2022-02-01, 2022-02-28, 1);
INSERT INTO `payroll_entry` (pay_entry_id, name, payment_date, payment_from, payment_to, processed) VALUES (8, QBS Support, 2023-12-21, 2023-12-22, 2023-12-31, 1);
INSERT INTO `payroll_entry` (pay_entry_id, name, payment_date, payment_from, payment_to, processed) VALUES (12, QBS Support, 2024-01-13, 2024-01-12, 2024-01-14, 1);


#
# TABLE STRUCTURE FOR: payroll_process
#

DROP TABLE IF EXISTS `payroll_process`;

CREATE TABLE `payroll_process` (
  `pay_proc_id` int(11) NOT NULL,
  `pay_entry_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `total_w_hours` mediumint(9) NOT NULL,
  `total_ot_hours` mediumint(9) NOT NULL,
  `w_hr_salary` decimal(14,2) NOT NULL,
  `ot_hr_salary` decimal(14,2) NOT NULL,
  `gross_pay` decimal(14,2) NOT NULL,
  `total_deductions` decimal(14,2) NOT NULL,
  `total_additions` decimal(14,2) NOT NULL,
  `net_pay` decimal(14,2) NOT NULL,
  PRIMARY KEY (`pay_proc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (1, 2, 1, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (2, 2, 2, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (3, 2, 3, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (4, 3, 1, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (5, 3, 2, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (6, 3, 3, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (7, 3, 4, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (8, 3, 5, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (9, 3, 8, 0, 0, 0.00, 0.00, 0.00, 1000.00, 2000.00, 1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (10, 8, 1, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (11, 8, 2, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (12, 8, 3, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (13, 8, 4, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (14, 8, 5, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (15, 8, 8, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (16, 11, 1, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (17, 11, 2, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (18, 11, 3, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (19, 11, 4, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (20, 11, 9, 0, 0, 0.00, 0.00, 0.00, 1000.00, 5.00, -995.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (21, 12, 13, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (22, 12, 14, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (23, 12, 15, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (24, 12, 16, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (25, 13, 13, 8, 2, 41680000.00, 110880.00, 41790880.00, 142963400.00, 4168000.00, -97004520.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (26, 13, 14, 0, 0, 0.00, 0.00, 0.00, 1000.00, 0.00, -1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (27, 13, 15, 0, 0, 0.00, 0.00, 0.00, 1000.00, 0.00, -1000.00);
INSERT INTO `payroll_process` (pay_proc_id, pay_entry_id, employee_id, total_w_hours, total_ot_hours, w_hr_salary, ot_hr_salary, gross_pay, total_deductions, total_additions, net_pay) VALUES (28, 13, 16, 0, 0, 0.00, 0.00, 0.00, 1000.00, 0.00, -1000.00);


#
# TABLE STRUCTURE FOR: planning
#

DROP TABLE IF EXISTS `planning`;

CREATE TABLE `planning` (
  `planning_id` int(11) NOT NULL AUTO_INCREMENT,
  `finished_good_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `finished_date` date DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`planning_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (1, 25, 2024-02-22, 2024-02-29, 0000-00-00, 10, 0, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (2, 23, 2024-01-27, 2024-01-27, 0000-00-00, 1, 1, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (3, 24, 2024-01-20, 2024-01-24, 0000-00-00, 2, 1, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (4, 23, 2024-01-22, 2024-01-23, 0000-00-00, 50, 0, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (5, 21, 2024-01-23, 2024-01-31, , 5, 0, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (6, 23, 2024-01-25, 2024-01-31, , 2, 0, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (7, 23, 2024-01-24, 2024-01-31, , 10, 0, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (8, 25, 2024-01-24, 2024-01-31, , 1, 0, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (9, 21, 2024-01-24, 2024-02-01, , 1, 0, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (10, 23, 2024-01-24, 2024-01-25, , 10, 0, 1);
INSERT INTO `planning` (planning_id, finished_good_id, start_date, end_date, finished_date, stock, status, created_by) VALUES (11, 25, 2024-02-22, 2024-02-29, , 10, 0, 1);


#
# TABLE STRUCTURE FOR: price_list
#

DROP TABLE IF EXISTS `price_list`;

CREATE TABLE `price_list` (
  `price_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `price_list` (price_id, name, amount, tax1, tax2, description, active, created_at, created_by) VALUES (1, Amount 1, 2000.00, 1, 3, , 1, 1645602972, 1);
INSERT INTO `price_list` (price_id, name, amount, tax1, tax2, description, active, created_at, created_by) VALUES (2, Amount 2, 3000.00, 3, 1, , 1, 1645603028, 1);
INSERT INTO `price_list` (price_id, name, amount, tax1, tax2, description, active, created_at, created_by) VALUES (6, amount 3, 8000.00, 1, 1, hi, 1, 1703658950, 1);
INSERT INTO `price_list` (price_id, name, amount, tax1, tax2, description, active, created_at, created_by) VALUES (8, Amount 4, 120.00, 4, 3, dsghsdh, 1, 1704864693, 1);


#
# TABLE STRUCTURE FOR: project_amenity
#

DROP TABLE IF EXISTS `project_amenity`;

CREATE TABLE `project_amenity` (
  `project_amen_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL,
  PRIMARY KEY (`project_amen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_amenity` (project_amen_id, project_id, amenity_id) VALUES (1, 3, 3);
INSERT INTO `project_amenity` (project_amen_id, project_id, amenity_id) VALUES (2, 3, 2);
INSERT INTO `project_amenity` (project_amen_id, project_id, amenity_id) VALUES (3, 3, 1);
INSERT INTO `project_amenity` (project_amen_id, project_id, amenity_id) VALUES (4, 5, 3);
INSERT INTO `project_amenity` (project_amen_id, project_id, amenity_id) VALUES (5, 6, 2);
INSERT INTO `project_amenity` (project_amen_id, project_id, amenity_id) VALUES (6, 7, 2);


#
# TABLE STRUCTURE FOR: project_expense
#

DROP TABLE IF EXISTS `project_expense`;

CREATE TABLE `project_expense` (
  `expense_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `expense_date` date NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `receipt` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`expense_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_expense` (expense_id, project_id, name, expense_date, amount, payment_id, receipt, description) VALUES (3, 3, test, 2022-05-03, 2000.00, 1);
INSERT INTO `project_expense` (expense_id, project_id, name, expense_date, amount, payment_id, receipt, description) VALUES (4, 3, kumar, 2023-12-16, 34.35, 1, export(1)_4.xlsx, test);
INSERT INTO `project_expense` (expense_id, project_id, name, expense_date, amount, payment_id, receipt, description) VALUES (5, 3, kumar, 2023-12-16, 34.35, 1, export(1)_5.xlsx, test);
INSERT INTO `project_expense` (expense_id, project_id, name, expense_date, amount, payment_id, receipt, description) VALUES (6, 3, kumar, 2023-12-16, 34.35, 1, export(1)_6.xlsx, test);
INSERT INTO `project_expense` (expense_id, project_id, name, expense_date, amount, payment_id, receipt, description) VALUES (14, 24, Thamizharasi, 2024-01-19, 8000.00, 2, export(49)_7.csv, eshtg);


#
# TABLE STRUCTURE FOR: project_members
#

DROP TABLE IF EXISTS `project_members`;

CREATE TABLE `project_members` (
  `project_mem_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`project_mem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (1, 1, 3);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (2, 1, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (3, 1, 1);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (4, 2, 3);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (5, 2, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (6, 2, 1);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (7, 3, 3);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (8, 3, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (9, 3, 1);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (10, 5, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (11, 6, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (12, 7, 1);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (13, 8, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (14, 9, 3);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (15, 9, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (16, 9, 1);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (17, 10, 3);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (18, 10, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (19, 10, 1);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (20, 11, 1);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (21, 12, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (22, 19, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (23, 20, 3);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (24, 21, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (25, 22, 3);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (26, 23, 1);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (27, 24, 2);
INSERT INTO `project_members` (project_mem_id, project_id, member_id) VALUES (28, 25, 2);


#
# TABLE STRUCTURE FOR: project_phase
#

DROP TABLE IF EXISTS `project_phase`;

CREATE TABLE `project_phase` (
  `phase_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `started` tinyint(4) NOT NULL DEFAULT 0,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`phase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_phase` (phase_id, name, started, project_id) VALUES (3, Phase1, 1, 3);
INSERT INTO `project_phase` (phase_id, name, started, project_id) VALUES (15, test, 1, 2);
INSERT INTO `project_phase` (phase_id, name, started, project_id) VALUES (16, Domain, 1, 2);
INSERT INTO `project_phase` (phase_id, name, started, project_id) VALUES (17, QBS Support, 1, 2);
INSERT INTO `project_phase` (phase_id, name, started, project_id) VALUES (18, test, 1, 2);
INSERT INTO `project_phase` (phase_id, name, started, project_id) VALUES (22, test, 1, 24);


#
# TABLE STRUCTURE FOR: project_rawmaterials
#

DROP TABLE IF EXISTS `project_rawmaterials`;

CREATE TABLE `project_rawmaterials` (
  `project_raw_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `req_qty` int(11) NOT NULL,
  `req_for_dispatch` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`project_raw_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_rawmaterials` (project_raw_id, related_to, related_id, project_id, req_qty, req_for_dispatch) VALUES (6, raw_material, 2, 3, 5, 0);
INSERT INTO `project_rawmaterials` (project_raw_id, related_to, related_id, project_id, req_qty, req_for_dispatch) VALUES (7, raw_material, 16, 3, 20, 0);
INSERT INTO `project_rawmaterials` (project_raw_id, related_to, related_id, project_id, req_qty, req_for_dispatch) VALUES (17, raw_material, 47, 2, 2, 0);
INSERT INTO `project_rawmaterials` (project_raw_id, related_to, related_id, project_id, req_qty, req_for_dispatch) VALUES (18, raw_material, 21, 2, 20, 0);
INSERT INTO `project_rawmaterials` (project_raw_id, related_to, related_id, project_id, req_qty, req_for_dispatch) VALUES (19, raw_material, 3, 2, 78, 0);


#
# TABLE STRUCTURE FOR: project_testing
#

DROP TABLE IF EXISTS `project_testing`;

CREATE TABLE `project_testing` (
  `project_test_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `complete_before` date NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` date NOT NULL,
  `description` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`project_test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

#
# TABLE STRUCTURE FOR: project_workgroup
#

DROP TABLE IF EXISTS `project_workgroup`;

CREATE TABLE `project_workgroup` (
  `project_wgrp_id` int(11) NOT NULL,
  `phase_id` int(11) NOT NULL,
  `wgroup_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `started_at` varchar(40) NOT NULL,
  `completed_at` varchar(40) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `fetched` tinyint(1) NOT NULL DEFAULT 0,
  `worker_type` varchar(60) NOT NULL,
  `contractor_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `paid_till` decimal(14,2) NOT NULL,
  `pay_before` varchar(10) NOT NULL,
  `c_status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`project_wgrp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (1, 1, 1, 1, 1, 2022-05-02, 2022-05-03, 1, 1, , 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (2, 1, 2, 2, 1, 2022-05-03, 2023-12-27, 1, 1, , 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (3, 1, 3, 2, 1, 2023-12-27, 2023-12-27, 1, 0, , 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (4, 2, 1, 1, 1, 2022-05-02, 2023-12-27, 1, 1, , 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (7, 3, 1, 0, 3, 2022-05-04, 2022-05-04, 1, 1, Contractor, 3, 30000.00, 3000.00, 2022-06-11, 1);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (12, 6, 1, 1, 8, 2023-12-16, 2023-12-16, 1, 0, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (13, 7, 2, 2, 9, , , 0, 0, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (14, 7, 10, 2, 9, , , 0, 0, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (15, 9, 2, 2, 10, , , 0, 0, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (16, 10, 1, 1, 11, 2023-12-26, 2023-12-26, 1, 1, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (17, 11, 1, 1, 11, 2023-12-26, , 0, 1, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (18, 12, 1, 1, 12, , , 0, 0, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (20, 16, 3, 15, 2, 2024-01-02, , 0, 1, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (21, 17, 3, 15, 2, 2024-01-02, , 0, 1, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (22, 18, 10, 15, 2, 2024-01-02, , 0, 1, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (24, 19, 2, 15, 22, 2024-01-11, 2024-01-11, 1, 1, Team, 0, 0.00, 0.00, , 0);
INSERT INTO `project_workgroup` (project_wgrp_id, phase_id, wgroup_id, team_id, project_id, started_at, completed_at, completed, fetched, worker_type, contractor_id, amount, paid_till, pay_before, c_status) VALUES (25, 20, 2, 15, 22, 2024-01-11, 2024-01-11, 1, 1, Team, 0, 0.00, 0.00, , 0);


#
# TABLE STRUCTURE FOR: projects
#

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `budget` decimal(14,2) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `units` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (2, project 2, 0, finished_good, 4, 2022-04-20, 2022-04-30, 10000.00, hello world, 0, 1, 0, 0, , , , , , 1650436903, 1);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (3, C Project 1, 8, , 0, 2022-04-20, 2022-08-19, 40000000.00, hello , 0, 0, 25, 1, No.164, First Floor, Arcot Rd, Valasaravakkam, Chennai, Tamil Nadu, India, 600087, 1650453923, 1);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (5, SevenSanjay, 6, , 0, 2023-12-15, 2023-12-16, 1234343.78, project test, 0, 0, 54, 1, Test Address, chennai, Tamil Nadu, India, 600087, 1702627269, 1);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (6, Seven, 8, , 0, 2023-12-15, 2023-12-16, 1234343.78, test, 0, 0, 54, 1, Test Address, chennai, Tamil Nadu, India, 600087, 1702627562, 1);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (7, kumar, 38, , 0, 2023-12-21, 2023-12-29, 1234343.78, test company, 0, 0, 54, 1, Test Address, chennai, Tamil Nadu, India, 600087, 1702629066, 1);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (13, , 0, , 0, 0000-00-00, 0000-00-00, 0.00, , 1, 0, 0, 0, , , , , , , 0);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (14, , 0, , 0, 0000-00-00, 0000-00-00, 0.00, , 1, 0, 0, 0, , , , , , , 0);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (15, , 0, , 0, 0000-00-00, 0000-00-00, 0.00, , 1, 0, 0, 0, , , , , , , 0);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (16, , 0, , 0, 0000-00-00, 0000-00-00, 0.00, , 1, 0, 0, 0, , , , , , , 0);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (17, , 0, , 0, 0000-00-00, 0000-00-00, 0.00, , 4, 0, 0, 0, , , , , , , 0);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (18, , 0, , 0, 0000-00-00, 0000-00-00, 0.00, , 3, 0, 0, 0, , , , , , , 0);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (23, TEst, 37, finished_good, 21, 2024-01-18, 2024-02-10, 10000.00, asdf, 0, 1, 0, 0, , , , , , 1705490992, 1);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (24, Thamizharasi, 45, finished_good, 21, 2024-01-18, 2024-01-19, 11221401.00, eafgeghef, 0, 1, 0, 0, , , , , , 1705752813, 1);
INSERT INTO `projects` (project_id, name, cust_id, related_to, related_id, start_date, end_date, budget, description, status, type, units, type_id, address, city, state, country, zipcode, created_at, created_by) VALUES (25, Production , 45, finished_good, 21, 2024-01-23, 2024-01-30, 11221401.00, sdgdfhdddgfsfdahgm, 0, 1, 0, 0, , , , , , 1705916085, 1);


#
# TABLE STRUCTURE FOR: properties
#

DROP TABLE IF EXISTS `properties`;

CREATE TABLE `properties` (
  `property_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `units` smallint(6) NOT NULL,
  `type_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `construct_start` varchar(20) NOT NULL,
  `construct_end` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `properties` (property_id, name, units, type_id, address, city, state, country, zipcode, construct_start, construct_end, status, description, created_at, created_by) VALUES (4, Rainbow, 1, 2, No.164, First Floor, Arcot Rd, Valasaravakkam, Chennai, Tamil Nadu, India, 600087, , , 1, , 1648643990, 1);
INSERT INTO `properties` (property_id, name, units, type_id, address, city, state, country, zipcode, construct_start, construct_end, status, description, created_at, created_by) VALUES (7, QBS Support, 0, 1, Test Address, chennai, Tamil Nadu, India, 600087, 2023-12-13, 2023-12-30, 1, , 1702386459, 1);


#
# TABLE STRUCTURE FOR: property_amenity
#

DROP TABLE IF EXISTS `property_amenity`;

CREATE TABLE `property_amenity` (
  `prop_ament_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `amenity_id` int(11) NOT NULL,
  PRIMARY KEY (`prop_ament_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (4, 4, 3);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (5, 4, 2);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (6, 4, 1);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (7, 3, 3);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (8, 3, 2);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (9, 3, 1);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (10, 7, 3);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (11, 8, 1);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (12, 9, 3);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (13, 10, 2);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (14, 11, 2);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (15, 12, 2);
INSERT INTO `property_amenity` (prop_ament_id, property_id, amenity_id) VALUES (16, 13, 2);


#
# TABLE STRUCTURE FOR: property_unit
#

DROP TABLE IF EXISTS `property_unit`;

CREATE TABLE `property_unit` (
  `prop_unit_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `unit_name` varchar(140) NOT NULL,
  `floor_no` varchar(80) NOT NULL,
  `area_sqft` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `price` decimal(14,2) NOT NULL,
  `tax1` int(11) NOT NULL,
  `tax2` int(11) NOT NULL,
  `direction` varchar(140) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`prop_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `property_unit` (prop_unit_id, property_id, unit_name, floor_no, area_sqft, status, price, tax1, tax2, direction, description) VALUES (1, 1, FLAT123454545, 1, 2500, 1, 3200000.00, 1, 2);
INSERT INTO `property_unit` (prop_unit_id, property_id, unit_name, floor_no, area_sqft, status, price, tax1, tax2, direction, description) VALUES (2, 2, FLAT124234234234234234234234, 2, 2500, 1, 3000000.00, 3, 0);
INSERT INTO `property_unit` (prop_unit_id, property_id, unit_name, floor_no, area_sqft, status, price, tax1, tax2, direction, description) VALUES (3, 4, FLAT125, 1, 2500, 1, 4500000.00, 1, 2, South, hello);
INSERT INTO `property_unit` (prop_unit_id, property_id, unit_name, floor_no, area_sqft, status, price, tax1, tax2, direction, description) VALUES (4, 4, QBS Support, 76, 454, 0, 12.23, 1, 2, h);
INSERT INTO `property_unit` (prop_unit_id, property_id, unit_name, floor_no, area_sqft, status, price, tax1, tax2, direction, description) VALUES (5, 5, siva, 76, 454, 0, 12.23, 1, 2, h);
INSERT INTO `property_unit` (prop_unit_id, property_id, unit_name, floor_no, area_sqft, status, price, tax1, tax2, direction, description) VALUES (6, 4, Sevensanja, 12, 212122, 0, 545.55, 3, 2, 87);
INSERT INTO `property_unit` (prop_unit_id, property_id, unit_name, floor_no, area_sqft, status, price, tax1, tax2, direction, description) VALUES (7, 7, sevens, 76, 2121.22, 0, 545.55, 1, 1, h);


#
# TABLE STRUCTURE FOR: propertytype
#

DROP TABLE IF EXISTS `propertytype`;

CREATE TABLE `propertytype` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(140) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `propertytype` (type_id, type_name, created_at, created_by) VALUES (0, mouse, 1714202419, 1);
INSERT INTO `propertytype` (type_id, type_name, created_at, created_by) VALUES (1, Flat, 1648623239, 1);
INSERT INTO `propertytype` (type_id, type_name, created_at, created_by) VALUES (2, Villa, 1648623248, 1);
INSERT INTO `propertytype` (type_id, type_name, created_at, created_by) VALUES (6, shop, 1705904222, 1);


#
# TABLE STRUCTURE FOR: purchase_invoice
#

DROP TABLE IF EXISTS `purchase_invoice`;

CREATE TABLE `purchase_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `amount` decimal(14,2) NOT NULL,
  `paid_till` decimal(14,2) NOT NULL,
  `grn_updated` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `purchase_invoice` (invoice_id, order_id, status, amount, paid_till, grn_updated) VALUES (1, 1, 3, 20000.00, 20000.00, 0);
INSERT INTO `purchase_invoice` (invoice_id, order_id, status, amount, paid_till, grn_updated) VALUES (2, 2, 0, 24000.00, 0.00, 0);
INSERT INTO `purchase_invoice` (invoice_id, order_id, status, amount, paid_till, grn_updated) VALUES (3, 3, 0, 96000.00, 0.00, 0);


#
# TABLE STRUCTURE FOR: purchase_order
#

DROP TABLE IF EXISTS `purchase_order`;

CREATE TABLE `purchase_order` (
  `order_id` int(11) NOT NULL,
  `order_code` varchar(140) NOT NULL,
  `req_id` int(11) NOT NULL,
  `selection_rule` varchar(140) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `internal_transport` tinyint(1) NOT NULL DEFAULT 0,
  `transport_id` int(11) NOT NULL,
  `transport_unit` int(11) NOT NULL,
  `transport_charge` decimal(14,2) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `supp_location_id` int(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `warehouse_id` int(11) NOT NULL,
  `terms_condition` text NOT NULL,
  `notes` text NOT NULL,
  `grn_created` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_created` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `purchase_order` (order_id, order_code, req_id, selection_rule, supplier_id, internal_transport, transport_id, transport_unit, transport_charge, rfq_id, supp_location_id, delivery_date, status, warehouse_id, terms_condition, notes, grn_created, invoice_created, created_at, created_by) VALUES (1, RFQ_ORDER - 1, 1, 3, 4, 0, 0, 0, 0.00, 0, 5, 2024-01-09, 2, 1, Test, Test, 1, 1, 1704777683, 1);
INSERT INTO `purchase_order` (order_id, order_code, req_id, selection_rule, supplier_id, internal_transport, transport_id, transport_unit, transport_charge, rfq_id, supp_location_id, delivery_date, status, warehouse_id, terms_condition, notes, grn_created, invoice_created, created_at, created_by) VALUES (2, QWE14, 5, 3, 4, 0, 0, 0, 0.00, 0, 5, 2024-01-12, 2, 1, test, test, 0, 1, 1705053527, 1);
INSERT INTO `purchase_order` (order_id, order_code, req_id, selection_rule, supplier_id, internal_transport, transport_id, transport_unit, transport_charge, rfq_id, supp_location_id, delivery_date, status, warehouse_id, terms_condition, notes, grn_created, invoice_created, created_at, created_by) VALUES (3, CO01, 6, 3, 4, 0, 0, 0, 0.00, 0, 5, 2024-01-13, 2, 1, test, test, 1, 1, 1705056235, 1);


#
# TABLE STRUCTURE FOR: purchase_order_items
#

DROP TABLE IF EXISTS `purchase_order_items`;

CREATE TABLE `purchase_order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `received_qty` int(11) NOT NULL,
  `returned_qty` int(11) NOT NULL,
  PRIMARY KEY (`order_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `purchase_order_items` (order_item_id, order_id, related_to, related_id, price_id, quantity, received_qty, returned_qty) VALUES (1, 1, raw_material, 43, 1, 5, 4, 1);
INSERT INTO `purchase_order_items` (order_item_id, order_id, related_to, related_id, price_id, quantity, received_qty, returned_qty) VALUES (2, 1, raw_material, 53, 1, 5, 5, 0);
INSERT INTO `purchase_order_items` (order_item_id, order_id, related_to, related_id, price_id, quantity, received_qty, returned_qty) VALUES (3, 2, raw_material, 47, 1, 12, 0, 0);
INSERT INTO `purchase_order_items` (order_item_id, order_id, related_to, related_id, price_id, quantity, received_qty, returned_qty) VALUES (4, 3, semi_finished, 17, 6, 12, 1, 11);


#
# TABLE STRUCTURE FOR: purchase_payments
#

DROP TABLE IF EXISTS `purchase_payments`;

CREATE TABLE `purchase_payments` (
  `purchase_pay_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `paid_on` date NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`purchase_pay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `purchase_payments` (purchase_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (4, 1, 1, 20000.00, 2022-03-18, , Advance payment);
INSERT INTO `purchase_payments` (purchase_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (5, 1, 1, 2000.00, 2022-03-17, , first off);
INSERT INTO `purchase_payments` (purchase_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (6, 2, 1, 1000.00, 2023-12-27, RFT-1234, nothing);
INSERT INTO `purchase_payments` (purchase_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (7, 2, 2, 100000.00, 2023-12-29, 6538796879, 987);
INSERT INTO `purchase_payments` (purchase_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (8, 3, 1, 1000.00, 2024-01-02, TEST TEST, Nothing);
INSERT INTO `purchase_payments` (purchase_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (9, 1, 1, 20000.00, 2024-01-13, 1234567890, fdgsagsdr3e r);


#
# TABLE STRUCTURE FOR: push_notify
#

DROP TABLE IF EXISTS `push_notify`;

CREATE TABLE `push_notify` (
  `push_id` int(11) NOT NULL,
  `notify_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `fetched` tinyint(1) NOT NULL DEFAULT 0,
  `pushed_at` varchar(20) NOT NULL,
  PRIMARY KEY (`push_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `push_notify` (push_id, notify_id, to_id, fetched, pushed_at) VALUES (1, 5, 1, 0, 12);


#
# TABLE STRUCTURE FOR: quotations
#

DROP TABLE IF EXISTS `quotations`;

CREATE TABLE `quotations` (
  `quote_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(140) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `quote_date` date NOT NULL,
  `cust_id` int(11) NOT NULL,
  `shippingaddr_id` int(11) NOT NULL,
  `billingaddr_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_place` varchar(255) NOT NULL,
  `transport_req` tinyint(1) NOT NULL DEFAULT 0,
  `trans_charge` decimal(14,2) NOT NULL,
  `discount` decimal(14,2) NOT NULL,
  `terms_condition` text NOT NULL,
  `payment_terms` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`quote_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `quotations` (quote_id, code, subject, expiry_date, quote_date, cust_id, shippingaddr_id, billingaddr_id, currency_id, currency_place, transport_req, trans_charge, discount, terms_condition, payment_terms, status, created_at, created_by) VALUES (1, PRO-001, test, 2024-02-28, 2024-02-20, 48, 0, 0, 8, after, 0, 0.00, 141.00, <p>fda</p>, 1 day, 4, 1708425326, 1);
INSERT INTO `quotations` (quote_id, code, subject, expiry_date, quote_date, cust_id, shippingaddr_id, billingaddr_id, currency_id, currency_place, transport_req, trans_charge, discount, terms_condition, payment_terms, status, created_at, created_by) VALUES (2, PRO-002, order, 2024-05-04, 2024-04-27, 45, 0, 0, 8, before, 1, 2700.00, 0.00, <p>mnmmm,mkmuummbki9lfdkguibnvbi</p>, demo, 4, 1714201029, 1);


#
# TABLE STRUCTURE FOR: raw_materials
#

DROP TABLE IF EXISTS `raw_materials`;

CREATE TABLE `raw_materials` (
  `raw_material_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `short_desc` varchar(1000) NOT NULL,
  `long_desc` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`raw_material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (42, Pens, test, test34, 9, 78768768, 1, 2, 1702365934, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (43, Grape, no, no
, 10, 2345, 1, 1, 1702365979, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (45, Raj, sdfsdf, dfgsdfg, 9, 1517, 2, 2, 1703418703, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (47, test2, wsera, rhyseht, 9, c58554, 2, 2, 1703829990, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (50, IGST, , , 9, 15, 2, 5, 1703830282, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (53, vegetabl, fsf, fsdf, 9, JHJ, 1, 2, 1703852159, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (56, test123, wearghedth, esraghedth, 9, b650745, 1, 1, 1704178435, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (58, Thamizh, swgreg, wegfdg, 10, cfseh, 1, 2, 1704189142, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (60, dhssdfzkhc, dhtaezt, erheazd, 9, dfxhjn6, 1, 1, 1704191459, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (66, TEST EMAIL, esgrseg, aer, 9, SO128, 1, 1, 1704800020, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (68, test, sfgag, asdgasg, 9, SO128dfs, 2, 1, 1705664411, 1);
INSERT INTO `raw_materials` (raw_material_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (70, fdhseh, xfgsjg, fgjsjsrfgjsg
, 10, 876, 2, 2, 1705750892, 1);


#
# TABLE STRUCTURE FOR: request
#

DROP TABLE IF EXISTS `request`;

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `from_m` varchar(140) NOT NULL,
  `to_m` varchar(140) NOT NULL,
  `purpose` varchar(140) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `mail_request` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `requested_at` varchar(20) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `responded_by` int(11) NOT NULL,
  `responded_at` varchar(20) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `updated_at` varchar(50) NOT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `request` (request_id, from_m, to_m, purpose, related_to, related_id, description, mail_request, status, requested_at, requested_by, responded_by, responded_at, created_at, updated_at) VALUES (1, CRM, Sales, Order Request, customer, 9, Want audi car, 1, 0, 1645877705, 1, 0, 1646477095);
INSERT INTO `request` (request_id, from_m, to_m, purpose, related_to, related_id, description, mail_request, status, requested_at, requested_by, responded_by, responded_at, created_at, updated_at) VALUES (2, CRM, Sales, Order Request, customer, 8, Test, 0, 0, 1646023067, 1, 0);


#
# TABLE STRUCTURE FOR: requisition
#

DROP TABLE IF EXISTS `requisition`;

CREATE TABLE `requisition` (
  `req_id` int(11) NOT NULL AUTO_INCREMENT,
  `req_code` varchar(140) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `priority` tinyint(1) NOT NULL,
  `mail_sent` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`req_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `requisition` (req_id, req_code, assigned_to, status, priority, mail_sent, description, remarks, created_at, created_by) VALUES (1, REQ -1, 1, 2, 1, 0, Raw materials, arrpoved, 1704777210, 1);
INSERT INTO `requisition` (req_id, req_code, assigned_to, status, priority, mail_sent, description, remarks, created_at, created_by) VALUES (2, USAP1234, 2, 0, 3, 1, test, , 1704970233, 1);
INSERT INTO `requisition` (req_id, req_code, assigned_to, status, priority, mail_sent, description, remarks, created_at, created_by) VALUES (5, USAP12345, 1, 2, 2, 0, dfsdfgfg, ttt, 1705053250, 1);
INSERT INTO `requisition` (req_id, req_code, assigned_to, status, priority, mail_sent, description, remarks, created_at, created_by) VALUES (6, CR01, 1, 2, 2, 0, test, test, 1705054691, 1);
INSERT INTO `requisition` (req_id, req_code, assigned_to, status, priority, mail_sent, description, remarks, created_at, created_by) VALUES (7, 12345, 1, 0, 0, 0, test, , 1710413255, 1);


#
# TABLE STRUCTURE FOR: rfq
#

DROP TABLE IF EXISTS `rfq`;

CREATE TABLE `rfq` (
  `rfq_id` int(11) NOT NULL AUTO_INCREMENT,
  `rfq_code` varchar(140) NOT NULL,
  `req_id` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `terms_condition` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rfq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `rfq` (rfq_id, rfq_code, req_id, expiry_date, terms_condition, created_at, created_by, status) VALUES (2, RFQ1436, 6, 2024-01-26, dsagseg, 1705752467, 1, 1);
INSERT INTO `rfq` (rfq_id, rfq_code, req_id, expiry_date, terms_condition, created_at, created_by, status) VALUES (3, RFQ1435, 1, 2024-04-28, uvuv, 1714203870, 1, 0);


#
# TABLE STRUCTURE FOR: sale_invoice
#

DROP TABLE IF EXISTS `sale_invoice`;

CREATE TABLE `sale_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(140) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_expiry` date NOT NULL,
  `cust_id` int(11) NOT NULL,
  `shippingaddr_id` int(11) NOT NULL,
  `billingaddr_id` varchar(50) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency_place` varchar(255) NOT NULL,
  `transport_req` tinyint(1) NOT NULL DEFAULT 0,
  `trans_charge` decimal(14,2) NOT NULL,
  `discount` decimal(14,2) NOT NULL,
  `terms_condition` text NOT NULL,
  `payment_terms` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `paid_till` decimal(14,2) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sale_invoice` (invoice_id, code, invoice_date, invoice_expiry, cust_id, shippingaddr_id, billingaddr_id, currency_id, currency_place, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, paid_till, type, can_edit, created_at, created_by) VALUES (1, INV-2024001, 2024-02-24, 2024-03-02, 38, 0, , 6, after, 0, 100.00, 10.00, <p>fsf</p>, fdsf, 2, , 5100.00, 1, 0, 1708768579, 1);
INSERT INTO `sale_invoice` (invoice_id, code, invoice_date, invoice_expiry, cust_id, shippingaddr_id, billingaddr_id, currency_id, currency_place, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, paid_till, type, can_edit, created_at, created_by) VALUES (19, INV-2024002, 2024-06-10, 2024-06-17, 37, 0, , 8, before, 0, 0.00, 0.00, , expense add, 2, , 59000.00, 3, 0, 1718020598, 1);
INSERT INTO `sale_invoice` (invoice_id, code, invoice_date, invoice_expiry, cust_id, shippingaddr_id, billingaddr_id, currency_id, currency_place, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, paid_till, type, can_edit, created_at, created_by) VALUES (20, INV-2024020, 2024-06-10, 2024-06-17, 37, 0, , 6, after, 0, 0.00, 0.00, , last, 2, , 6350.00, 3, 0, 1718027512, 1);
INSERT INTO `sale_invoice` (invoice_id, code, invoice_date, invoice_expiry, cust_id, shippingaddr_id, billingaddr_id, currency_id, currency_place, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, paid_till, type, can_edit, created_at, created_by) VALUES (21, INV-2024021, 2024-06-11, 2024-06-18, 37, 0, , 11, after, 0, 0.00, 0.00, , last, 2, , 4750.00, 3, 0, 1718089327, 1);
INSERT INTO `sale_invoice` (invoice_id, code, invoice_date, invoice_expiry, cust_id, shippingaddr_id, billingaddr_id, currency_id, currency_place, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, paid_till, type, can_edit, created_at, created_by) VALUES (22, INV-2024022, 2024-11-02, 2024-11-09, 45, 0, , 6, after, 0, 0.00, 1000.00, , test, 0, , 0.00, 1, 1, 1730548926, 1);


#
# TABLE STRUCTURE FOR: sale_order
#

DROP TABLE IF EXISTS `sale_order`;

CREATE TABLE `sale_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(140) NOT NULL,
  `order_date` date NOT NULL,
  `order_expiry` date NOT NULL,
  `cust_id` int(11) NOT NULL,
  `shippingaddr_id` int(11) NOT NULL,
  `transport_req` tinyint(1) NOT NULL DEFAULT 0,
  `trans_charge` decimal(14,2) NOT NULL,
  `discount` decimal(14,2) NOT NULL,
  `terms_condition` text NOT NULL,
  `payment_terms` varchar(140) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit` tinyint(1) NOT NULL DEFAULT 1,
  `stock_pick` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (1, ORD-001, 2024-02-06, 2024-02-13, 37, 3, 1, 20.00, 0.00, <h2><i><strong>fdsf</strong></i></h2>, fsdf, 0, , 1, 1, 0, 1707201992, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (2, ORD-002, 2024-02-08, 2024-02-15, 37, 3, 0, 0.00, 0.00, <p>test</p>, test, 0, , 1, 1, 0, 1707367424, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (3, ORD-003, 2024-02-08, 2024-02-15, 38, 0, 1, 0.00, 0.00, <p>dsf</p>, test, 0, , 1, 1, 0, 1707367498, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (4, ORD-004, 2024-02-08, 2024-02-15, 46, 0, 1, 0.00, 0.00, <p>dsf</p>, df, 0, , 1, 1, 0, 1707371230, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (5, ORD-005, 2024-02-08, 2024-02-15, 38, 0, 1, 10.00, 10.00, <p>fds</p>, dsf, 0, , 1, 1, 0, 1707371271, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (6, ORD-006, 2024-02-08, 2024-02-15, 37, 0, 0, 0.00, 0.00, <p>sdf</p>, fsd, 0, , 1, 1, 0, 1707371306, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (7, ORD-007, 2024-02-14, 2024-02-21, 45, 0, 0, 500.00, 0.00, <p>rerserfdf</p>, 1 day, 0, , 1, 1, 0, 1707918086, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (8, ORD-008, 2024-02-15, 2024-02-22, 38, 0, 0, 0.00, 10.00, <p>test</p>, asdf, 0, , 1, 1, 0, 1707999377, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (9, ORD-5, 2024-02-20, 2024-02-22, 48, 0, 0, 0.00, 141.00, <p>fda</p>, 1 day, 0, , 1, 0, 0, 1708508832, 1);
INSERT INTO `sale_order` (order_id, code, order_date, order_expiry, cust_id, shippingaddr_id, transport_req, trans_charge, discount, terms_condition, payment_terms, status, remarks, type, can_edit, stock_pick, created_at, created_by) VALUES (10, 1234567890, 2024-04-27, 2024-06-30, 45, 0, 1, 2700.00, 0.00, <p>mnmmm,mkmuummbki9lfdkguibnvbi</p>, demo, 0, , 1, 0, 0, 1717592008, 1);


#
# TABLE STRUCTURE FOR: sale_order_items
#

DROP TABLE IF EXISTS `sale_order_items`;

CREATE TABLE `sale_order_items` (
  `sale_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(14,2) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `tax1` tinyint(4) NOT NULL,
  `tax2` tinyint(4) NOT NULL,
  `send_qty` int(11) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sale_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (2, finished_good, 25, 8, 0, 0, 4, 1, 120.00, 120.00, 9, 18, 0, 0, 2024-02-06 17:57:02);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (3, finished_good, 22, 2, 0, 0, 4, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-06 17:57:02);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (4, finished_good, 22, 2, 0, 0, 5, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-06 18:58:36);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (5, finished_good, 23, 1, 0, 0, 5, 2, 2000.00, 4000.00, 9, 18, 0, 0, 2024-02-06 18:59:04);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (6, finished_good, 22, 2, 0, 2, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-08 10:13:44);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (7, finished_good, 23, 1, 0, 3, 0, 1, 2000.00, 2000.00, 9, 18, 0, 0, 2024-02-08 10:14:58);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (8, finished_good, 21, 1, 0, 4, 0, 1, 2000.00, 2000.00, 9, 18, 0, 0, 2024-02-08 11:17:10);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (9, finished_good, 22, 2, 0, 5, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-08 11:17:51);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (10, finished_good, 25, 8, 0, 6, 0, 2, 120.00, 240.00, 20, 18, 0, 0, 2024-02-08 11:18:26);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (11, finished_good, 25, 8, 0, 0, 6, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-09 10:39:29);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (13, finished_good, 25, 8, 0, 0, 7, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-09 11:08:41);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (15, finished_good, 22, 2, 0, 0, 7, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-09 11:16:12);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (16, finished_good, 21, 1, 0, 0, 6, 1, 2000.00, 2000.00, 9, 18, 0, 0, 2024-02-09 11:16:35);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (17, finished_good, 22, 2, 5, 0, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-09 11:20:24);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (18, finished_good, 22, 2, 0, 0, 8, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-14 18:33:15);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (19, finished_good, 22, 2, 6, 0, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-14 19:06:23);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (20, finished_good, 25, 8, 6, 0, 0, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-14 19:06:23);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (21, finished_good, 23, 1, 0, 7, 0, 1, 2000.00, 2000.00, 9, 18, 0, 0, 2024-02-14 19:11:26);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (22, finished_good, 21, 1, 0, 7, 0, 2, 2000.00, 4000.00, 9, 18, 0, 0, 2024-02-14 19:11:26);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (23, finished_good, 23, 1, 0, 5, 0, 1, 2000.00, 2000.00, 9, 18, 0, 0, 2024-02-14 19:12:44);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (24, finished_good, 22, 2, 7, 0, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-15 16:36:08);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (25, finished_good, 25, 8, 7, 0, 0, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-15 16:36:08);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (27, finished_good, 25, 8, 0, 8, 0, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-15 17:46:17);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (28, finished_good, 22, 2, 0, 8, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-15 17:46:17);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (29, finished_good, 22, 2, 8, 0, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-19 13:08:21);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (30, finished_good, 25, 8, 8, 0, 0, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-19 13:08:22);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (31, finished_good, 22, 2, 20, 0, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-19 15:14:35);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (35, finished_good, 22, 2, 29, 0, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-19 16:47:49);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (38, finished_good, 25, 8, 30, 0, 0, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-20 16:00:50);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (39, finished_good, 22, 2, 30, 0, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-20 16:03:13);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (40, finished_good, 25, 8, 31, 0, 0, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-20 16:04:18);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (41, finished_good, 25, 8, 1, 9, 0, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-20 16:05:26);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (42, finished_good, 22, 2, 1, 9, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-20 16:06:14);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (45, finished_good, 22, 2, 0, 0, 9, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-21 12:45:35);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (47, finished_good, 23, 1, 0, 0, 10, 1, 2000.00, 2000.00, 9, 18, 0, 0, 2024-02-21 14:10:57);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (48, finished_good, 21, 1, 0, 0, 10, 1, 2000.00, 2000.00, 9, 18, 0, 0, 2024-02-21 14:22:54);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (49, finished_good, 22, 2, 0, 0, 11, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-22 16:35:46);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (50, finished_good, 25, 8, 0, 0, 11, 2, 120.00, 240.00, 20, 18, 0, 0, 2024-02-22 16:35:46);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (51, finished_good, 25, 8, 0, 0, 12, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-24 13:29:16);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (52, finished_good, 25, 8, 0, 0, 1, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-24 15:26:19);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (53, finished_good, 22, 2, 0, 0, 2, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-02-24 18:11:39);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (54, finished_good, 25, 8, 0, 0, 2, 1, 120.00, 120.00, 20, 18, 0, 0, 2024-02-24 18:11:40);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (55, finished_good, 22, 2, 2, 10, 0, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-04-27 12:27:09);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (56, finished_good, 25, 8, 0, 0, 3, 44, 120.00, 5280.00, 20, 18, 0, 0, 2024-06-10 10:39:26);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (57, finished_good, 22, 2, 0, 0, 16, 1, 3000.00, 3000.00, 18, 9, 0, 0, 2024-06-10 16:14:07);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (58, finished_good, 21, 1, 0, 0, 22, 5, 2000.00, 10000.00, 9, 18, 0, 0, 2024-11-02 17:32:06);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (59, finished_good, 23, 8, 0, 0, 22, 69, 120.00, 8280.00, 20, 18, 0, 0, 2024-11-02 17:32:06);
INSERT INTO `sale_order_items` (sale_item_id, related_to, related_id, price_id, quote_id, order_id, invoice_id, quantity, unit_price, amount, tax1, tax2, send_qty, return_qty, timestamp) VALUES (60, finished_good, 25, 8, 0, 0, 22, 20, 120.00, 2400.00, 20, 18, 0, 0, 2024-11-02 17:32:06);


#
# TABLE STRUCTURE FOR: sale_payments
#

DROP TABLE IF EXISTS `sale_payments`;

CREATE TABLE `sale_payments` (
  `sale_pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `paid_on` date NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`sale_pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sale_payments` (sale_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (1, 1, 2, 10.00, 2024-02-24, test123, test);
INSERT INTO `sale_payments` (sale_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (3, 1, 1, 5000.00, 2024-06-09);
INSERT INTO `sale_payments` (sale_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (8, 19, 1, 54000.00, 2024-06-15);
INSERT INTO `sale_payments` (sale_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (9, 19, 1, 5000.00, 2024-06-06);
INSERT INTO `sale_payments` (sale_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (10, 20, 1, 4000.00, 2024-06-10);
INSERT INTO `sale_payments` (sale_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (11, 20, 1, 2350.00, 2024-06-11);
INSERT INTO `sale_payments` (sale_pay_id, invoice_id, payment_id, amount, paid_on, transaction_id, notes) VALUES (12, 21, 1, 4750.00, 2024-06-11);


#
# TABLE STRUCTURE FOR: scheduling
#

DROP TABLE IF EXISTS `scheduling`;

CREATE TABLE `scheduling` (
  `scheduling_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `service_id` int(11) NOT NULL,
  PRIMARY KEY (`scheduling_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `scheduling` (scheduling_id, start_date, due_date, location, service_id) VALUES (2, 2024-01-28, 2024-01-31, address , 4);
INSERT INTO `scheduling` (scheduling_id, start_date, due_date, location, service_id) VALUES (4, 2024-01-19, 2024-01-21, test, 5);


#
# TABLE STRUCTURE FOR: selection_rule
#

DROP TABLE IF EXISTS `selection_rule`;

CREATE TABLE `selection_rule` (
  `rule_id` int(11) NOT NULL,
  `rule_name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `selection_rule` (rule_id, rule_name, description, created_at, created_by) VALUES (3, rule 1, Test rule, 1646907504, 1);
INSERT INTO `selection_rule` (rule_id, rule_name, description, created_at, created_by) VALUES (8, test, rtet, 1705909175, 1);


#
# TABLE STRUCTURE FOR: selection_rule_segment
#

DROP TABLE IF EXISTS `selection_rule_segment`;

CREATE TABLE `selection_rule_segment` (
  `rule_seg_id` int(11) NOT NULL,
  `rule_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `segment_value_idx` tinyint(4) NOT NULL,
  `above_below` tinyint(1) NOT NULL DEFAULT 0,
  `exclude` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rule_seg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `selection_rule_segment` (rule_seg_id, rule_id, segment_id, segment_value_idx, above_below, exclude) VALUES (3, 3, 1, 1, 1, 0);
INSERT INTO `selection_rule_segment` (rule_seg_id, rule_id, segment_id, segment_value_idx, above_below, exclude) VALUES (4, 3, 2, 2, 1, 0);
INSERT INTO `selection_rule_segment` (rule_seg_id, rule_id, segment_id, segment_value_idx, above_below, exclude) VALUES (5, 3, 3, 1, 1, 0);
INSERT INTO `selection_rule_segment` (rule_seg_id, rule_id, segment_id, segment_value_idx, above_below, exclude) VALUES (17, 8, 2, 3, 1, 1);
INSERT INTO `selection_rule_segment` (rule_seg_id, rule_id, segment_id, segment_value_idx, above_below, exclude) VALUES (18, 8, 3, 0, 1, 1);


#
# TABLE STRUCTURE FOR: semi_finished
#

DROP TABLE IF EXISTS `semi_finished`;

CREATE TABLE `semi_finished` (
  `semi_finished_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `short_desc` varchar(1000) NOT NULL,
  `long_desc` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`semi_finished_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `semi_finished` (semi_finished_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (17, test, rawegheath, awreghthea, 12, SO128, 1, 1, 1704177246, 1);
INSERT INTO `semi_finished` (semi_finished_id, name, short_desc, long_desc, group_id, code, unit_id, brand_id, created_at, created_by) VALUES (18, Thinner, wsefgeawfg, fesafgearg, 12, egfes74565, 2, 1, 1704863727, 1);


#
# TABLE STRUCTURE FOR: service
#

DROP TABLE IF EXISTS `service`;

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `assigned_to` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `service_desc` text NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`service_id`),
  UNIQUE KEY `service_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `service` (service_id, code, name, priority, status, assigned_to, employee_id, service_desc, created_by) VALUES (4, SA03, test, 1, 0, 3, 14, test, 1);
INSERT INTO `service` (service_id, code, name, priority, status, assigned_to, employee_id, service_desc, created_by) VALUES (5, SA04, Qbs Support, 3, 2, 1, 13, test, 1);
INSERT INTO `service` (service_id, code, name, priority, status, assigned_to, employee_id, service_desc, created_by) VALUES (6, dsgsd, test, 1, 0, 2, 14, sdfhsh, 1);
INSERT INTO `service` (service_id, code, name, priority, status, assigned_to, employee_id, service_desc, created_by) VALUES (7, fdhs574, test, 1, 0, 2, 13, edfgdfhdf, 1);


#
# TABLE STRUCTURE FOR: stock_alerts
#

DROP TABLE IF EXISTS `stock_alerts`;

CREATE TABLE `stock_alerts` (
  `stock_alert_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `alert_qty_level` int(11) NOT NULL,
  `alert_before` tinyint(4) NOT NULL,
  `recurring` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`stock_alert_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `stock_alerts` (stock_alert_id, related_to, related_id, alert_qty_level, alert_before, recurring) VALUES (2, raw_material, 2, 30, 20, 1);
INSERT INTO `stock_alerts` (stock_alert_id, related_to, related_id, alert_qty_level, alert_before, recurring) VALUES (3, semi_finished, 2, 45, 127, 1);
INSERT INTO `stock_alerts` (stock_alert_id, related_to, related_id, alert_qty_level, alert_before, recurring) VALUES (4, finished_good, 2, 30, 20, 0);
INSERT INTO `stock_alerts` (stock_alert_id, related_to, related_id, alert_qty_level, alert_before, recurring) VALUES (5, raw_material, 24, 324, 76, 1);
INSERT INTO `stock_alerts` (stock_alert_id, related_to, related_id, alert_qty_level, alert_before, recurring) VALUES (6, finished_good, 7, 324, 76, 1);
INSERT INTO `stock_alerts` (stock_alert_id, related_to, related_id, alert_qty_level, alert_before, recurring) VALUES (7, raw_material, 17, 10000, 2, 1);
INSERT INTO `stock_alerts` (stock_alert_id, related_to, related_id, alert_qty_level, alert_before, recurring) VALUES (9, raw_material, 45, 324, 76, 1);
INSERT INTO `stock_alerts` (stock_alert_id, related_to, related_id, alert_qty_level, alert_before, recurring) VALUES (12, semi_finished, 12, 20, 1, 1);


#
# TABLE STRUCTURE FOR: stock_entry
#

DROP TABLE IF EXISTS `stock_entry`;

CREATE TABLE `stock_entry` (
  `stock_entry_id` int(11) NOT NULL,
  `entry_type` tinyint(1) NOT NULL DEFAULT 0,
  `stock_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `entry_log` varchar(1000) NOT NULL,
  `order_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  PRIMARY KEY (`stock_entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (3, 0, 1, 8, , 0, 0, 2022-03-22);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (4, 0, 2, 10, , 0, 0, 2022-03-22);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (5, 1, 0, 1, , 0, 0, 2022-04-04);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (6, 1, 0, 1, , 0, 0, 2022-04-04);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (7, 1, 9, 1, , 0, 0, 2022-04-12);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (22, 2, 9, 1, , 1, 0, 2022-04-13);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (23, 2, 10, 1, , 1, 0, 2022-04-13);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (37, 3, 7, 5, , 0, 1);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (41, 3, 8, 5, , 0, 1, 2022-05-03);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (42, 3, 2, 3, , 0, 1, 2022-05-03);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (43, 3, 8, 2, , 0, 1, 2023-12-16);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (44, 3, 2, 15, , 0, 1, 2023-12-16);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (45, 1, 0, 1, , 0, 0, 2024-01-02);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (46, 1, 0, 1, , 0, 0, 2024-01-03);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (47, 1, 0, 1, , 0, 0, 2024-01-03);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (48, 1, 0, 1, , 0, 0, 2024-01-04);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (49, 1, 0, 1, , 0, 0, 2024-01-10);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (50, 1, 0, 1, , 0, 0, 2024-01-10);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (51, 1, 0, 1, , 0, 0, 2024-01-13);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (52, 1, 0, 1, , 0, 0, 2024-01-13);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (53, 1, 14, 1, , 0, 0, 2024-01-20);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (54, 1, 21, 1, , 0, 0, 2024-01-20);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (55, 1, 0, 1, , 0, 0, 2024-01-20);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (56, 1, 14, 1, , 0, 0, 2024-01-20);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (57, 1, 0, 1, , 0, 0, 2024-01-20);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (58, 1, 0, 1, , 0, 0, 2024-01-22);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (59, 1, 0, 1, , 0, 0, 2024-01-22);
INSERT INTO `stock_entry` (stock_entry_id, entry_type, stock_id, qty, entry_log, order_id, project_id, created_at) VALUES (60, 1, 0, 1, , 0, 0, 2024-01-22);


#
# TABLE STRUCTURE FOR: stocks
#

DROP TABLE IF EXISTS `stocks`;

CREATE TABLE `stocks` (
  `stock_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (12, raw_material, 2, 15, 0, 1, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (13, raw_material, 16, 8, 0, 1, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (14, finished_good, 21, 1, 1, 1, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (15, semi_finished, 17, 4, 1, 2, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (17, semi_finished, 17, 11, 0, 2, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (18, finished_good, 22, 6, 1, 2, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (19, finished_good, 25, 11, 1, 8, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (20, finished_good, 21, 1, 1, 8, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (21, finished_good, 21, 4, 1, 1, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (22, semi_finished, 18, 2, 1, 1, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (23, finished_good, 25, 1, 1, 8, 2024-01-17 16:09:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (24, finished_good, 21, 2, 0, 8, 2024-01-20 17:24:33);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (25, semi_finished, 18, 1, 0, 1, 2024-01-20 17:24:44);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (26, finished_good, 21, 6, 0, 8, 2024-01-20 17:25:00);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (27, finished_good, 21, 6, 1, 1, 2024-01-20 17:25:24);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (28, raw_material, 50, 1, 1, 1, 2024-01-20 17:34:54);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (29, finished_good, 21, 2, 1, 1, 2024-01-20 17:37:11);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (30, semi_finished, 17, 1, 1, 2, 2024-01-20 18:20:23);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (31, raw_material, 50, 9, 1, 1, 2024-01-22 10:55:45);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (32, finished_good, 23, 1, 1, 1, 2024-01-22 11:01:30);
INSERT INTO `stocks` (stock_id, related_to, related_id, warehouse_id, quantity, price_id, timestamp) VALUES (33, raw_material, 47, 2, 1, 1, 2024-01-22 13:21:43);


#
# TABLE STRUCTURE FOR: supplier_contacts
#

DROP TABLE IF EXISTS `supplier_contacts`;

CREATE TABLE `supplier_contacts` (
  `contact_id` int(11) NOT NULL,
  `firstname` varchar(140) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `position` varchar(140) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier_contacts` (contact_id, firstname, lastname, email, phone, active, position, supplier_id, created_at, created_by) VALUES (10, QBSsss, Support, supporttr@qbrainstorm.comf, 9080780700, 1, werwer, 4, 1702126664, 1);
INSERT INTO `supplier_contacts` (contact_id, firstname, lastname, email, phone, active, position, supplier_id, created_at, created_by) VALUES (13, delete, Support, support@qbrainstorm.com, 9080780700, 1, Purchase Managers, 40, 1702208458, 1);


#
# TABLE STRUCTURE FOR: supplier_locations
#

DROP TABLE IF EXISTS `supplier_locations`;

CREATE TABLE `supplier_locations` (
  `location_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier_locations` (location_id, address, city, state, country, zipcode, supplier_id, created_at, created_by) VALUES (7, Delete Address, chennai, Tamil Nadu, India, 600087, 40, 1702208450, 1);
INSERT INTO `supplier_locations` (location_id, address, city, state, country, zipcode, supplier_id, created_at, created_by) VALUES (15, chennai, Thiruvallur, tamilnadu, India, 602024, 4, 1705907980, 1);
INSERT INTO `supplier_locations` (location_id, address, city, state, country, zipcode, supplier_id, created_at, created_by) VALUES (16, chennai, Thiruvallur, tamilnadu, India, 602024, 4, 1705907987, 1);


#
# TABLE STRUCTURE FOR: supplier_rfq
#

DROP TABLE IF EXISTS `supplier_rfq`;

CREATE TABLE `supplier_rfq` (
  `supp_rfq_id` int(11) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `selection_rule` varchar(140) NOT NULL,
  `mail_status` tinyint(1) NOT NULL DEFAULT 0,
  `send_contacts` tinyint(1) NOT NULL DEFAULT 0,
  `include_attach` tinyint(1) NOT NULL DEFAULT 0,
  `responded` tinyint(1) NOT NULL DEFAULT 0,
  `responded_at` varchar(20) NOT NULL,
  PRIMARY KEY (`supp_rfq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier_rfq` (supp_rfq_id, rfq_id, supplier_id, selection_rule, mail_status, send_contacts, include_attach, responded, responded_at) VALUES (0, 2, 6, 8, 0, 0, 0, 0);


#
# TABLE STRUCTURE FOR: supplier_segment_map
#

DROP TABLE IF EXISTS `supplier_segment_map`;

CREATE TABLE `supplier_segment_map` (
  `supp_seg_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `segment_json` text NOT NULL,
  PRIMARY KEY (`supp_seg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier_segment_map` (supp_seg_id, supplier_id, segment_json) VALUES (3, 7, {"1":"2","2":"2","3":"1"});
INSERT INTO `supplier_segment_map` (supp_seg_id, supplier_id, segment_json) VALUES (4, 4, {"1":"1","2":"1","3":"1"});
INSERT INTO `supplier_segment_map` (supp_seg_id, supplier_id, segment_json) VALUES (5, 6, {"1":"3","2":"2","3":"0"});
INSERT INTO `supplier_segment_map` (supp_seg_id, supplier_id, segment_json) VALUES (8, 40, {"1":"3","2":"1","3":"0"});


#
# TABLE STRUCTURE FOR: supplier_segments
#

DROP TABLE IF EXISTS `supplier_segments`;

CREATE TABLE `supplier_segments` (
  `segment_id` int(11) NOT NULL,
  `segment_key` varchar(140) NOT NULL,
  `segment_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`segment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier_segments` (segment_id, segment_key, segment_value, created_at, created_by) VALUES (2, Product Quality, {"0":"Not Applicable","1":"Poor","2":"Average","3":"Standard"}, 1646312456, 1);
INSERT INTO `supplier_segments` (segment_id, segment_key, segment_value, created_at, created_by) VALUES (3, Support, {"0":"Not Applicable","1":"Poor","2":"Good","3":"Average","4":"Excellent"}, 1646910701, 1);


#
# TABLE STRUCTURE FOR: supplier_sources
#

DROP TABLE IF EXISTS `supplier_sources`;

CREATE TABLE `supplier_sources` (
  `source_id` int(11) NOT NULL,
  `source_name` varchar(140) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supplier_sources` (source_id, source_name, created_at, created_by) VALUES (1, Email campaign, 1646283489, 1);
INSERT INTO `supplier_sources` (source_id, source_name, created_at, created_by) VALUES (8, General, 1705908706, 1);


#
# TABLE STRUCTURE FOR: suppliers
#

DROP TABLE IF EXISTS `suppliers`;

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `code` varchar(20) NOT NULL,
  `position` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `office_number` varchar(13) NOT NULL,
  `fax_number` varchar(13) NOT NULL,
  `company` varchar(140) NOT NULL,
  `gst` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `website` varchar(255) NOT NULL,
  `payment_terms` text NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `suppliers` (supplier_id, source_id, name, code, position, email, phone, office_number, fax_number, company, gst, address, city, state, country, zipcode, website, payment_terms, description, active, created_at, created_by) VALUES (4, 1, Production Thamizharasi, SO128, web developer, support@qbrainstorm.com, 1234567890, , , qbs, 5754654466689898, madurai, Thiruvallur, tamilnadu, India, 602024, , , , 0, 1704187140, 1);
INSERT INTO `suppliers` (supplier_id, source_id, name, code, position, email, phone, office_number, fax_number, company, gst, address, city, state, country, zipcode, website, payment_terms, description, active, created_at, created_by) VALUES (6, 1, QBS Support, werewr, 434, suppor9t@qbrainstorm.com, 9080780700, werwer, werewr, qbrainstorm, rer, Test Address, chennai, Tamil Nadu, India, 600087, wer, werwer, werwer, 1, 1705909217, 1);


#
# TABLE STRUCTURE FOR: supply_list
#

DROP TABLE IF EXISTS `supply_list`;

CREATE TABLE `supply_list` (
  `supply_list_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supply_qty` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`supply_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `supply_list` (supply_list_id, related_to, related_id, supplier_id, supply_qty, created_at, created_by) VALUES (0, raw_material, 43, 6, 0, 1714647088, 1);
INSERT INTO `supply_list` (supply_list_id, related_to, related_id, supplier_id, supply_qty, created_at, created_by) VALUES (4, raw_material, 17, 4, 0, 1647066656, 1);
INSERT INTO `supply_list` (supply_list_id, related_to, related_id, supplier_id, supply_qty, created_at, created_by) VALUES (19, semi_finished, 6, 40, 0, 1702208413, 1);


#
# TABLE STRUCTURE FOR: tasks
#

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `related_to` varchar(20) NOT NULL,
  `related_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `assignees` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `task_description` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tasks` (task_id, name, status, start_date, due_date, related_to, related_id, priority, assignees, followers, task_description, created_by) VALUES (0, Shoe, 1, 2024-04-27, 2024-04-28, project, 2, 2, 3, 1, demo task, 1);
INSERT INTO `tasks` (task_id, name, status, start_date, due_date, related_to, related_id, priority, assignees, followers, task_description, created_by) VALUES (2, Follow the lead, 0, 2024-01-06, 2024-01-11, lead, 4, 1, 1, 2, Follow the lead, 1);
INSERT INTO `tasks` (task_id, name, status, start_date, due_date, related_to, related_id, priority, assignees, followers, task_description, created_by) VALUES (3, QBS Support, 3, 2024-01-06, 2024-01-24, project, 3, 0, 2, 3, test, 1);
INSERT INTO `tasks` (task_id, name, status, start_date, due_date, related_to, related_id, priority, assignees, followers, task_description, created_by) VALUES (4, Domain C, 1, 2024-01-07, 2024-01-13, ticket, 10, 2, 2, 3, test two, 1);


#
# TABLE STRUCTURE FOR: taxes
#

DROP TABLE IF EXISTS `taxes`;

CREATE TABLE `taxes` (
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(50) NOT NULL,
  `percent` tinyint(4) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `taxes` (tax_id, tax_name, percent, created_at, created_by) VALUES (1, CGST, 9, 1645106126, 1);
INSERT INTO `taxes` (tax_id, tax_name, percent, created_at, created_by) VALUES (2, SGST, 9, 1645158708, 1);
INSERT INTO `taxes` (tax_id, tax_name, percent, created_at, created_by) VALUES (3, IGST, 18, 1645159286, 1);
INSERT INTO `taxes` (tax_id, tax_name, percent, created_at, created_by) VALUES (4, HGST, 20, 1703321477, 1);


#
# TABLE STRUCTURE FOR: team_members
#

DROP TABLE IF EXISTS `team_members`;

CREATE TABLE `team_members` (
  `member_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `team_members` (member_id, team_id, employee_id) VALUES (3, 1, 1);
INSERT INTO `team_members` (member_id, team_id, employee_id) VALUES (4, 1, 2);
INSERT INTO `team_members` (member_id, team_id, employee_id) VALUES (5, 2, 2);


#
# TABLE STRUCTURE FOR: teams
#

DROP TABLE IF EXISTS `teams`;

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `description` text NOT NULL,
  `team_count` smallint(6) NOT NULL,
  `lead_by` int(11) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `teams` (team_id, name, description, team_count, lead_by, created_at, created_by) VALUES (15, test, wsrhht, 5, 2, 1704173024, 1);


#
# TABLE STRUCTURE FOR: tickets
#

DROP TABLE IF EXISTS `tickets`;

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `subject` varchar(140) NOT NULL,
  `priority` tinyint(1) NOT NULL DEFAULT 0,
  `cust_id` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `problem` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`ticket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tickets` (ticket_id, subject, priority, cust_id, assigned_to, problem, status, remarks, created_at, created_by) VALUES (0, Health issue, 1, 38, 2, demo, 0, , 1714199859, 1);
INSERT INTO `tickets` (ticket_id, subject, priority, cust_id, assigned_to, problem, status, remarks, created_at, created_by) VALUES (9, doubt, 0, 6, 2, aezgtbhadn, 2, , 1703842779, 1);
INSERT INTO `tickets` (ticket_id, subject, priority, cust_id, assigned_to, problem, status, remarks, created_at, created_by) VALUES (10, doub, 1, 9, 2, sehtsht, 0, , 1704447163, 1);
INSERT INTO `tickets` (ticket_id, subject, priority, cust_id, assigned_to, problem, status, remarks, created_at, created_by) VALUES (11, Software , 1, 6, 1, Some problems , 0, , 1704720702, 1);
INSERT INTO `tickets` (ticket_id, subject, priority, cust_id, assigned_to, problem, status, remarks, created_at, created_by) VALUES (12, doubt, 1, 45, 3, sfgag, 0, , 1705665339, 1);


#
# TABLE STRUCTURE FOR: transport_type
#

DROP TABLE IF EXISTS `transport_type`;

CREATE TABLE `transport_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(140) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transport_type` (type_id, type_name, created_at, created_by) VALUES (1, Airways, 1647082077, 1);
INSERT INTO `transport_type` (type_id, type_name, created_at, created_by) VALUES (3, Roadways, 1647328672, 1);
INSERT INTO `transport_type` (type_id, type_name, created_at, created_by) VALUES (11, shipway, 1705743195, 1);
INSERT INTO `transport_type` (type_id, type_name, created_at, created_by) VALUES (12, Lorry, 1705743222, 1);
INSERT INTO `transport_type` (type_id, type_name, created_at, created_by) VALUES (14, Bike, 1705743244, 1);


#
# TABLE STRUCTURE FOR: transports
#

DROP TABLE IF EXISTS `transports`;

CREATE TABLE `transports` (
  `transport_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `type_id` int(11) NOT NULL,
  `code` varchar(140) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `delivery_count` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`transport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transports` (transport_id, name, type_id, code, active, status, delivery_count, description, created_at, created_by) VALUES (1, test2, 1, 123, 1, 1, 0, hello, 1647087322, 1);
INSERT INTO `transports` (transport_id, name, type_id, code, active, status, delivery_count, description, created_at, created_by) VALUES (2, test, 3, 124, 1, 1, 0, hellow, 1647087414, 1);
INSERT INTO `transports` (transport_id, name, type_id, code, active, status, delivery_count, description, created_at, created_by) VALUES (6, Leo, 3, 234234, 1, 1, 0, test, 1703409406, 1);
INSERT INTO `transports` (transport_id, name, type_id, code, active, status, delivery_count, description, created_at, created_by) VALUES (7, Q Brainstorm, 3, 6969, 1, 1, 0, test, 1703661598, 1);
INSERT INTO `transports` (transport_id, name, type_id, code, active, status, delivery_count, description, created_at, created_by) VALUES (9, test, 3, c58554, 1, 0, 0, dfhssdh, 1705743126, 1);
INSERT INTO `transports` (transport_id, name, type_id, code, active, status, delivery_count, description, created_at, created_by) VALUES (10, Thamizharasi, 12, b6501, 1, 0, 0, sdyhrthsrh, 1705743269, 1);
INSERT INTO `transports` (transport_id, name, type_id, code, active, status, delivery_count, description, created_at, created_by) VALUES (11, kkkkkk, 1, werewr, 1, 0, 0, wqewqe, 1705743352, 1);


#
# TABLE STRUCTURE FOR: units
#

DROP TABLE IF EXISTS `units`;

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(20) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `units` (unit_id, unit_name, created_at, created_by) VALUES (1, Kg, 1645537313, 1);
INSERT INTO `units` (unit_id, unit_name, created_at, created_by) VALUES (2, Litter, 1702460196, 1);
INSERT INTO `units` (unit_id, unit_name, created_at, created_by) VALUES (5, gram, 1704864231, 1);


#
# TABLE STRUCTURE FOR: warehouses
#

DROP TABLE IF EXISTS `warehouses`;

CREATE TABLE `warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(140) NOT NULL,
  `state` varchar(140) NOT NULL,
  `country` varchar(140) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `has_bins` tinyint(1) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `aisle_count` int(11) NOT NULL,
  `racks_per_aisle` int(11) NOT NULL,
  `shelf_per_rack` int(11) NOT NULL,
  `bins_per_shelf` int(11) NOT NULL,
  PRIMARY KEY (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `warehouses` (warehouse_id, name, address, city, state, country, zipcode, has_bins, description, aisle_count, racks_per_aisle, shelf_per_rack, bins_per_shelf) VALUES (0, Qbrainstorm Software, No.164, First Floor, Arcot Rd, Valasaravakkam, madurai, tamilnadu, India, 602024, 0, dhshfa, 0, 0, 0, 0);
INSERT INTO `warehouses` (warehouse_id, name, address, city, state, country, zipcode, has_bins, description, aisle_count, racks_per_aisle, shelf_per_rack, bins_per_shelf) VALUES (1, LOC-3, No.164, First Floor, Arcot Rd, Valasaravakkam, Chennai, Tamil Nadu, India, 600087, 1, , 12, 10, 5, 4);
INSERT INTO `warehouses` (warehouse_id, name, address, city, state, country, zipcode, has_bins, description, aisle_count, racks_per_aisle, shelf_per_rack, bins_per_shelf) VALUES (2, LOC-2, No.164, First Floor, Arcot Rd, Valasaravakkam, Chennai, Tamil Nadu, India, 600087, 0, , 0, 0, 0, 0);
INSERT INTO `warehouses` (warehouse_id, name, address, city, state, country, zipcode, has_bins, description, aisle_count, racks_per_aisle, shelf_per_rack, bins_per_shelf) VALUES (4, vinitha, chennai, Thiruvallur, tamilnadu, India, 602024, 0, , 0, 0, 0, 0);
INSERT INTO `warehouses` (warehouse_id, name, address, city, state, country, zipcode, has_bins, description, aisle_count, racks_per_aisle, shelf_per_rack, bins_per_shelf) VALUES (6, sample6, chennai, Thiruvallur, tamilnadu, India, 602024, 1, zsvjksv, 45, 45, 55, 55);
INSERT INTO `warehouses` (warehouse_id, name, address, city, state, country, zipcode, has_bins, description, aisle_count, racks_per_aisle, shelf_per_rack, bins_per_shelf) VALUES (8, sadhana, chennai, Thiruvallur, tamilnadu, India, 602024, 1, ftjyguj, 44, 547, 635, 5);
INSERT INTO `warehouses` (warehouse_id, name, address, city, state, country, zipcode, has_bins, description, aisle_count, racks_per_aisle, shelf_per_rack, bins_per_shelf) VALUES (9, harina, chennai, Thiruvallur, tamilnadu, India, 602024, 1, ftjyguj, 44, 547, 635, 5);
INSERT INTO `warehouses` (warehouse_id, name, address, city, state, country, zipcode, has_bins, description, aisle_count, racks_per_aisle, shelf_per_rack, bins_per_shelf) VALUES (11, fathima, chennai, Thiruvallur, tamilnadu, India, 602024, 1, sfffrg, 54, 45, 45, 54);


#
# TABLE STRUCTURE FOR: work_group_equip
#

DROP TABLE IF EXISTS `work_group_equip`;

CREATE TABLE `work_group_equip` (
  `wgroup_equip_id` int(11) NOT NULL,
  `wgroup_id` int(11) NOT NULL,
  `equip_id` int(11) NOT NULL,
  PRIMARY KEY (`wgroup_equip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `work_group_equip` (wgroup_equip_id, wgroup_id, equip_id) VALUES (1, 1, 1);
INSERT INTO `work_group_equip` (wgroup_equip_id, wgroup_id, equip_id) VALUES (2, 2, 1);
INSERT INTO `work_group_equip` (wgroup_equip_id, wgroup_id, equip_id) VALUES (3, 3, 1);
INSERT INTO `work_group_equip` (wgroup_equip_id, wgroup_id, equip_id) VALUES (9, 10, 1);
INSERT INTO `work_group_equip` (wgroup_equip_id, wgroup_id, equip_id) VALUES (10, 11, 1);
INSERT INTO `work_group_equip` (wgroup_equip_id, wgroup_id, equip_id) VALUES (11, 12, 1);
INSERT INTO `work_group_equip` (wgroup_equip_id, wgroup_id, equip_id) VALUES (12, 13, 1);


#
# TABLE STRUCTURE FOR: work_group_items
#

DROP TABLE IF EXISTS `work_group_items`;

CREATE TABLE `work_group_items` (
  `wgroup_item_id` int(11) NOT NULL,
  `wgroup_id` int(11) NOT NULL,
  `related_to` varchar(140) NOT NULL,
  `related_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`wgroup_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `work_group_items` (wgroup_item_id, wgroup_id, related_to, related_id, qty) VALUES (1, 1, raw_material, 2, 5);
INSERT INTO `work_group_items` (wgroup_item_id, wgroup_id, related_to, related_id, qty) VALUES (5, 3, raw_material, 21, 10);
INSERT INTO `work_group_items` (wgroup_item_id, wgroup_id, related_to, related_id, qty) VALUES (6, 10, raw_material, 3, 78);
INSERT INTO `work_group_items` (wgroup_item_id, wgroup_id, related_to, related_id, qty) VALUES (7, 11, raw_material, 20, 59);
INSERT INTO `work_group_items` (wgroup_item_id, wgroup_id, related_to, related_id, qty) VALUES (9, 12, raw_material, 47, 1);
INSERT INTO `work_group_items` (wgroup_item_id, wgroup_id, related_to, related_id, qty) VALUES (10, 2, raw_material, 43, 78);
INSERT INTO `work_group_items` (wgroup_item_id, wgroup_id, related_to, related_id, qty) VALUES (11, 13, raw_material, 53, 1);


#
# TABLE STRUCTURE FOR: work_groups
#

DROP TABLE IF EXISTS `work_groups`;

CREATE TABLE `work_groups` (
  `wgroup_id` int(11) NOT NULL,
  `name` varchar(140) NOT NULL,
  `approx_days` smallint(6) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`wgroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `work_groups` (wgroup_id, name, approx_days, description) VALUES (2, Group 2, 50, hello);
INSERT INTO `work_groups` (wgroup_id, name, approx_days, description) VALUES (3, Group 3, 120, hello);
INSERT INTO `work_groups` (wgroup_id, name, approx_days, description) VALUES (10, QBS Support, 32767, test);


