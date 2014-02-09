<?php
/**
 * SQL installation import
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

defined('SYSPATH') or exit('Install must be loaded from within index.php!');

mysql_query('SET NAMES '.$_POST['DB_CHARSET']);
mysql_query("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");

mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."roles` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."roles_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."access` (
  `id_access` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `access` varchar(100) NOT NULL,
  PRIMARY KEY (`id_access`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS  `".$_POST['TABLE_PREFIX']."users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `seoname` varchar(145) DEFAULT NULL,
  `email` varchar(145) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `id_role` int(10) unsigned DEFAULT '1',
  `id_location` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` datetime DEFAULT NULL,
  `logins` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_ip`  float DEFAULT NULL,
  `user_agent` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `token_created` datetime DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `hybridauth_provider_name` varchar(40) NULL DEFAULT NULL,
  `hybridauth_provider_uid` varchar(245) NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."users_UK_email` (`email`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."users_UK_token` (`token`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."users_UK_seoname` (`seoname`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."users_UK_provider_AND_uid` (`hybridauth_provider_name`,`hybridauth_provider_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS  `".$_POST['TABLE_PREFIX']."categories` (
  `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_category_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`) USING BTREE,
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."categories_IK_seo_name` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."locations` (
  `id_location` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `id_location_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) NULL,
  PRIMARY KEY (`id_location`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."categories_UK_seoname` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."ads` (
  `id_ad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL DEFAULT '0',
  `id_location` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(145) DEFAULT '0',
  `price` decimal(14,3) NOT NULL DEFAULT '0',
  `phone` varchar(30) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `ip_address` float DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` DATETIME  NULL,
  `featured` DATETIME  NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `has_images` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ad`) USING BTREE,
  KEY `".$_POST['TABLE_PREFIX']."ads_IK_id_user` (`id_user`),
  KEY `".$_POST['TABLE_PREFIX']."ads_IK_id_category` (`id_category`),
  KEY `".$_POST['TABLE_PREFIX']."ads_IK_title` (`title`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."ads_UK_seotitle` (`seotitle`),
  KEY `".$_POST['TABLE_PREFIX']."ads_IK_status` (`status`),
  CONSTRAINT `".$_POST['TABLE_PREFIX']."ads_FK_id_user_AT_users` FOREIGN KEY (`id_user`) REFERENCES `".$_POST['TABLE_PREFIX']."users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `".$_POST['TABLE_PREFIX']."ads_FK_id_category_AT_categories` FOREIGN KEY (`id_category`) REFERENCES `".$_POST['TABLE_PREFIX']."categories` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."visits` (
  `id_visit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ad` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `contacted` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` float DEFAULT NULL,
  PRIMARY KEY (`id_visit`),
  KEY `".$_POST['TABLE_PREFIX']."visits_IK_id_user` (`id_user`),
  KEY `".$_POST['TABLE_PREFIX']."visits_IK_id_ad` (`id_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."config` ( 
  `group_name` VARCHAR(128)  NOT NULL, 
  `config_key` VARCHAR(128)  NOT NULL, 
  `config_value` TEXT,
   KEY `".$_POST['TABLE_PREFIX']."config_IK_group_name_AND_config_key` (`group_name`,`config_key`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET']." ;");


mysql_query("CREATE TABLE IF NOT EXISTS  `".$_POST['TABLE_PREFIX']."orders` (
  `id_order` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_ad` int(10) unsigned NULL,
  `id_product` varchar(20) NOT NULL, 
  `paymethod` varchar(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_date` DATETIME  NULL,
  `currency` char(3) NOT NULL,
  `amount` decimal(14,3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`id_order`),
  KEY `".$_POST['TABLE_PREFIX']."orders_IK_id_user` (`id_user`),
  KEY `".$_POST['TABLE_PREFIX']."orders_IK_status` (`status`)
)ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."content` (
  `id_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) NOT NULL DEFAULT 'en_UK',
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` TEXT NULL,
  `from_email` varchar(145) NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('page','email','help') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."subscribers` (
  `id_subscribe` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL DEFAULT '0',
  `id_location` int(10) unsigned NOT NULL DEFAULT '0',
  `min_price` decimal(14,3) NOT NULL DEFAULT '0',
  `max_price` decimal(14,3) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_subscribe`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS  `".$_POST['TABLE_PREFIX']."posts` (
  `id_post` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `title` varchar(245) NOT NULL,
  `seotitle` varchar(245) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_post`) USING BTREE,
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."posts_UK_seotitle` (`seotitle`)
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");

/**
 * add basic content like emails
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."content` (`order`, `title`, `seotitle`, `description`, `from_email`, `type`, `status`) 
    VALUES
(0, 'Change Password [SITE.NAME]', 'auth.remember', 'Hello [USER.NAME],\n\nFollow this link  [URL.QL]\n\nThanks!!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Welcome to [SITE.NAME]!', 'auth.register', 'Welcome [USER.NAME],\n\nWe are really happy that you have joined us! [URL.QL]\n\nRemember your user details:\nEmail: [USER.EMAIL]\nPassword: [USER.PWD]\n\nWe do not have your original password anymore.\n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Hello [USER.NAME]!', 'user.contact', 'You have been contacted regarding your advertisement: \n\n`[AD.NAME]`. \n\n User [EMAIL.SENDER] [EMAIL.FROM], have a message for you: \n\n[EMAIL.BODY]. \n\n Regards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Hello [USER.NAME]!', 'userprofile.contact', 'User [EMAIL.SENDER] [EMAIL.FROM], have a message for you: \n\n[EMAIL.SUBJECT] \n\n[EMAIL.BODY]. \n\n Regards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Hello [USER.NAME]!', 'user.new', 'Welcome to [SITE.NAME]. \n\n We are really happy that you have joined us! , \n\n you can log in with you email : [USER.EMAIL], \n\n with password: [USER.PWD]. Password is generated for you, to change it you can visit this link [URL.PWCH]. \n\n Thank you for trusting us! \n\n Regards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, '[EMAIL.SENDER] wants to contact you!', 'contact.admin', 'Hello Admin,\n\n [EMAIL.SENDER]: [EMAIL.FROM], have a message for you:\n\n [EMAIL.BODY] \n\n Regards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Your advertisement `[AD.NAME]` at [SITE.NAME], has been activated!', 'ads.activated', 'Hello [USER.OWNER],\n\n We want to inform you that your advertisement [URL.QL] has been activated!\n\n Now it can be seen by others. \n\n We hope we did not make you wait for long. \n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Success! Your advertisement `[AD.NAME]` is created on [SITE.NAME]!', 'ads.notify', 'Hello [USER.NAME],\n\nThank you for creating an advertisement at [SITE.NAME]! \n\nYou can edit your advertisement here [URL.QL].\n\n Your ad is still not published, it needs to be validated by an administrator. \n\n We are sorry for any inconvenience. We will review it as soon as possible. \n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Advertisement `[AD.NAME]` is created on [SITE.NAME]!', 'ads.user_check', 'Hello [USER.NAME],\n\n Advertisement is created under your account [USER.NAME]! You can visit this link to see advertisement [URL.AD]\n\n If you are not responsible for creating this advertisement, click a link to contact us [URL.CONTACT].\n\n', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Advertisement `[AD.TITLE]` is created on [SITE.NAME]!', 'ads.subscribers', 'Hello [USER.NAME],\n\n You may be interested in this one!\n\nYou can visit this link to see advertisement [URL.AD]\n\n', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Advertisement `[AD.TITLE]` is created on [SITE.NAME]!', 'ads.to_admin', 'Click here to visit [URL.AD]', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Success! Your advertisement `[AD.NAME]` is created on [SITE.NAME]!', 'ads.confirm', 'Welcome [USER.NAME],\n\nThank you for creating an advertisement at [SITE.NAME]! \n\nPlease click on this link [URL.QL] to confirm it.\n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1);");

/**
 * Content translations
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."content` (`id_content`, `locale`, `order`, `title`, `seotitle`, `description`, `from_email`, `created`, `type`, `status`)
    VALUES
(30, 'ro_RO', 0, 'Anunțul dvs. a fost adăugat cu succes pe [SITE.NAME]!', 'ads.confirm', 'Bun venit, [USER.NAME].\n\nÎți mulțumim pentru adăugarea anunțului dvs. pe [SITE.NAME].\n\nTe rugăm să apeși un click pe acest link [URL.QL], pentru a confirma anunțul.\n\nToate cele bune!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:25:48', 'email', 1),
(31, 'ro_RO', 0, 'Anunțul dvs. a fost adăugat pe [SITE.NAME].', 'ads.user_check', 'Bună [USER.NAME],\n\nAnunțul a fost adăugat în contul dvs. [USER.NAME]. Puteți vizita acest link pentru vizualizarea anunțului [URL.AD].\n\nDacă nu sunteți răspunzător pentru adăugarea acestui anunț, vă rugăm să ne contactați, folosind link-ul următor: [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:27:46', 'email', 1),
(32, 'ro_RO', 0, 'Anunțul dvs. a fost adăugat cu succes pe [SITE.NAME]!', 'ads.notify', 'Bună [USER.NAME],\n\nVă mulțumim că folosii serviciul nostru de anunțuri, [SITE.NAME]\n\nPuteți edita anunțul dvs. aici: [URL.QL].\n\nAnunțul dvs. nu este publicat momentan, deoarece este necesară validarea lui de către un administrator. \nNe cerem scuze pentru eventualele neplăceri. Vom încerca să-l revizuim cât mai repede posibil.\n\nCu stimă.', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:30:59', 'email', 1),
(33, 'ro_RO', 0, '[EMAIL.SENDER] dorește să ia legătura cu dvs.', 'contact.admin', 'Salut Admin,\n\n[EMAIL.SENDER]: [EMAIL.FROM], are un mesaj pentru tine:\n\n[EMAIL.BODY]\n\nCu stimă.', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:32:57', 'email', 1),
(34, 'ro_RO', 0, 'Anunțul dvs. pe [SITE.NAME] a fost activat!', 'ads.activated', 'Salutări [USER.OWNER],\n\nDorim să vă informăm că anunțul dvs. [URL.QL] a fost activat.\nAcum, el poate fi vizualizat de către toată lumea.\n\nSperăm că nu v-am făcut să așteptați prea mult timp.\n\nCu stimă.', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:35:35', 'email', 1),
(35, 'ro_RO', 0, 'Bună [USER.NAME]!', 'user.new', 'Bun venit pe [SITE.NAME].\n\nNe bucurăm că v-ați decis să vă alăturați echipei și serviciilor noastre,\nde acum, vă puteți autentifica folosind adresa dvs. de e-mail: [USER.EMAIL],\nși parola: [USER.PWD]. \n\nAceastă parolă este una generată automat. Dacă doriți să o modificați, vizitați acest link: [URL.PWCH].\n\nVă mulțumim pentru încrederea acordată.\n\nCu stimă.', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:37:40', 'email', 1),
(36, 'ro_RO', 0, 'Bună [USER.NAME]!', 'user.contact', 'Ați fost contactat referitor la anunțul dvs. \n\nUtilizatorul [EMAIL.SENDER] [EMAIL.FROM], are un mesaj pentru dvs.:\n[EMAIL.BODY].\n\nCu stimă.', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:40:46', 'email', 1),
(37, 'ro_RO', 0, 'Modificare parolă [SITE.NAME]', 'auth.remember', 'Salutare [USER.NAME],\n\nVizitează acest link: [URL.QL] \n\nMulțumim!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:41:59', 'email', 1),
(38, 'ro_RO', 0, 'Bun venit pe [SITE.NAME]!', 'auth.register', 'Bun venit [USER.NAME],\n\nNe bucurăm că v-ați decis să vă alăturați echipei și serviciilor noastre! [URL.QL]\n\nIată detaliile contului dvs.:\nE-mail: [USER.EMAIL]\nParolă: [USER.PWD]\n\nDe acum, vă puteți autentifica folosind aceste date. (parola generată automat nu mai este valabilă).\n\nCu stimă!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:44:44', 'email', 1),
(39, 'pl_PL', 0, 'Sukces! Twoje ogłoszenie zostało utworzone na [SITE.NAME]!', 'ads.confirm', 'Witaj [USER.NAME],\n\nDziękujemy za zamieszczenie ogłoszenia na [SITE.NAME]!\n\nKliknij na ten link [URL.QL] aby je zatwierdzić.\n\nMiłego dnia!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:49:48', 'email', 1),
(40, 'pl_PL', 0, 'Ogłoszenie utworzone na [SITE.NAME]!', 'ads.user_check', 'Witaj [USER.NAME],\n\nOgłoszenie zostało utworzone na Twoim koncie [USER.NAME]! Odwiedź ten link, aby zobaczyć ogłoszenie [URL.AD].\n\nJeśli to nie Ty utworzyłeś to ogłoszenie, kliknij na link, aby się z nami skontaktować [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:51:48', 'email', 1),
(41, 'pl_PL', 0, 'Sukces! Twoje ogłoszenie zostało utworzone na [SITE.NAME]!', 'ads.notify', 'Witaj [USER.NAME],\n\nDziękujemy za zamieszczenie ogłoszenia na [SITE.NAME]!\n\nMożesz edytować swoje ogłoszenie tutaj [URL.QL].\n\nTwoje ogłoszenie jest wciąż nieopublikowane, najpierw musi być zatwierdzone przez administratora.\nPrzepraszamy za wszelkie niedogodności. Ogłoszenie zostanie zatwierdzone najszybciej jak to tylko możliwe.\n\nPozdrowienia!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:53:51', 'email', 1),
(42, 'pl_PL', 0, '[EMAIL.SENDER] chce się z Tobą skontaktować!', 'contact.admin', 'Drogi administratorze,\n\n[EMAIL.SENDER]: [EMAIL.FROM], ma dla Ciebie wiadomość:\n\n[EMAIL.BODY] \n\nPozdrowienia!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:55:08', 'email', 1),
(43, 'pl_PL', 0, 'Twoje ogłoszenie na [SITE.NAME] zostało aktywowane!', 'ads.activated', 'Witaj [USER.OWNER],\n\nInformujemy, że Twoje ogłoszenie [URL.QL] zostało aktywowane! Teraz może być widziane przez innych.\n\nMamy nadzieję, że nie musiałeś czekać długo.\n\nPozdrowienia!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:56:23', 'email', 1),
(44, 'pl_PL', 0, 'Witaj [USER.NAME]!', 'user.new', 'Witamy na [SITE.NAME].\n\nBardzo się cieszymy, że do nas dołączyłeś,\nmożesz się zalogować używając swojego emaila: [USER.EMAIL]\ni hasła [USER.PWD]. \n\nHasło zostało wygenerowane automatycznie, aby je zmienić odwiedź link [URL.PWCH].\n\nDziękujemy za zaufanie!\n\nPozdrawiamy!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 10:57:41', 'email', 1),
(45, 'pl_PL', 0, 'Witaj [USER.NAME]!', 'user.contact', 'Dostałeś/aś wiadomość związaną z Twoim ogłoszeniem. \n\nUżytkownik [EMAIL.SENDER] [EMAIL.FROM] ma dla Ciebie wiadomość:\n\n[EMAIL.BODY]. \n\nMiłego dnia!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 11:00:12', 'email', 1),
(46, 'pl_PL', 0, 'Zmień hasło [SITE.NAME]', 'auth.remember', 'Witaj [USER.NAME],\n\nKliknij na ten link [URL.QL]\n\nDziękujemy!!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 11:00:51', 'email', 1),
(47, 'pl_PL', 0, 'Witamy na [SITE.NAME]!', 'auth.register', 'Witamy na [SITE.NAME].\n\nBardzo się cieszymy, że do nas dołączyłeś.\n\nZapamiętaj swoje dane logowania:\nEmail: [USER.EMAIL]\nHasło: [USER.PWD]\n\nNie posiadamy już Twojego poprzedniego hasła.\n\nPozdrowienia!', '".$_POST['ADMIN_EMAIL']."', '2013-07-29 11:03:08', 'email', 1),
(48, 'ru_RU', 0, 'Готово! Ваше объявление размещено на [SITE.NAME]', 'ads.confirm', 'Здравствуйте,  [USER.NAME],\n\nБлагодарим за публикацию объявления на  [SITE.NAME]! \n\nДля подтверждения пройдите по ссылке [URL.QL].\n\nВсего доброго!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:08:43', 'email', 1),
(49, 'ru_RU', 0, 'Объявление опубликовано на  [SITE.NAME]!', 'ads.user_check', 'Здравствуйте, [USER.NAME],\n\nВ вашем аккаунте [USER.NAME] создано новое объявление! Посмотреть его вы можете, пройдя по ссылке [URL.AD]\n\nЕсли вы не создавали никакого объявления, сообщите нам, пройдя по этой ссылке [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:10:30', 'email', 1),
(50, 'ru_RU', 0, 'Поздравляем! Ваше объявление опубликовано на [SITE.NAME]!', 'ads.notify', 'Здравствуйте, [USER.NAME],\n\nБлагодарим за публикацию объявления на  [SITE.NAME]! \n\nРедактировать объявление вы можете здесь [URL.QL].\n\nВаше объявление будет опубликовано после проверки администратором. \nПриносим свои извинения за неудобства. Постараемся исправить как можно быстрее. \n\nВсего доброго!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:11:50', 'email', 1),
(51, 'ru_RU', 0, '[EMAIL.SENDER] прислал сообщение!', 'contact.admin', 'Здравствуйте, Администратор!\n\n[EMAIL.SENDER]: [EMAIL.FROM], прислал для вас сообщение:\n\n[EMAIL.BODY]', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:12:56', 'email', 1),
(52, 'ru_RU', 0, 'Ваше объявление на  [SITE.NAME] опубликовано!', 'ads.activated', 'Здравствуйте, [USER.OWNER],\n\nСообщаем вам, что объявление на [URL.QL] опубликовано! Теперь его смогут увидеть другие. \n\nНадеемся, мы не заставили вас ждать слишком долго.\n\nУдачи!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:14:20', 'email', 1),
(53, 'ru_RU', 0, 'Здравствуйте, [USER.NAME]!', 'user.new', 'Добро пожаловать на  [SITE.NAME]. \n\nРады, что вы к нам присоединились,\nвы можете войти, используя свой e-mail: [USER.EMAIL], \nпароль: [USER.PWD]. \n\nПароль сгенерирован автоматически, изменить его вы можете, пройдя по ссылке [URL.PWCH]. \n\nБлагодарим за доверие!\n\nУдачи!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:15:32', 'email', 1),
(54, 'ru_RU', 0, 'Здравствуйте, [USER.NAME]!', 'user.contact', 'Это сообщение касается вашего объявления. \n\nПользователь [EMAIL.SENDER] [EMAIL.FROM] прислал сообщение: \n\n[EMAIL.BODY]. \n\nУдачи!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:16:48', 'email', 1),
(55, 'ru_RU', 0, 'Изменить пароль на  [SITE.NAME]', 'auth.remember', 'Здравствуйте, [USER.NAME]!\n\nПройдите по этой сылке  [URL.QL]\n\nСпасибо!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:17:41', 'email', 1),
(56, 'ru_RU', 0, 'Добро пожаловать на  [SITE.NAME]!', 'auth.register', 'Добро пожаловать, [USER.NAME],\n\nРады, что вы к нам присоединились на [URL.QL]\n\nЗапомните ваши данные для входа:\nEmail: [USER.EMAIL]\nПароль: [USER.PWD]\n\nМы не сохраяем ваш первоначальный пароль.\n\nУдачи!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:18:35', 'email', 1),
(57, 'cs_CZ', 0, 'Úspěch! Váš inzerát byl vytvořen na [SITE.NAME]!', 'ads.confirm', 'Vítejte [USER.NAME],\n\nDěkujeme za vytvoření inzerátu na [SITE.NAME]! \n\nPro potvrzení prosím klikněte na tento link  [URL.QL]\n\nPřejeme příjemný zbytek dne!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:22:12', 'email', 1),
(58, 'cs_CZ', 0, 'Inzerát je vytvořen na [SITE.NAME]!', 'ads.user_check', 'Dobrý den  [USER.NAME],\n\nInzerát byl vytvořen pod Vaším účtem [USER.NAME]!  Pro prohlédnutí inzerátu můžete kliknout na tento link  [URL.AD]\n\nPokud jste inzerát nevytvořili Vy, prosím kontaktujte nás na [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:23:07', 'email', 1),
(59, 'cs_CZ', 0, 'Úspěch! Váš inzerát byl vytvořen na [SITE.NAME]!', 'ads.notify', 'Dobrý den  [USER.NAME],\n\nDěkujeme za zveřejnění inzerátu na [SITE.NAME]! \n\nVáš inzerát můžete upravit zde  [URL.QL].\n\nVáš inzerát ještě není zveřejněn, potřebuje být schválen administrátorem.\nOmlouváme se za nepříjemností. Shlédneme ho, co nejdříve to bude možné.\n\nPřejeme příjemný zbytek dne!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:24:31', 'email', 1),
(60, 'cs_CZ', 0, '[EMAIL.SENDER] Vás chce kontaktovat!', 'contact.admin', 'Dobrý den Admine,\n\n[EMAIL.SENDER]: [EMAIL.FROM], má pro Vás zprávu:\n\n[EMAIL.BODY] \n\nPřejeme příjemný zbytek dne!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:26:28', 'email', 1),
(61, 'cs_CZ', 0, 'Váš inzerát na [SITE.NAME] byl aktivován!', 'ads.activated', 'Dobrý den [USER.OWNER],\n\nChceme Vás informovat, že Váš inzerát [URL.QL]  byl aktivován!\nNyní si ho mohou prohlížet ostatní.\n\nDoufáme, že jste nečekali dlouho.\n\nPřejeme příjemný zbytek dne!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:27:36', 'email', 1),
(62, 'cs_CZ', 0, 'Dobrý den [USER.NAME]!', 'user.new', 'Vítejte na [SITE.NAME]. \n\nJsme rádi, že jste se k nám připojili, \nmůžete se přihlásit s Vaším emailem:  [USER.EMAIL], \na heslem: [USER.PWD]. \n\nHeslo bylo pro Vás vygenerováno. Pro změnu hesla navštivte tento link [URL.PWCH]. \n\nDěkujeme za důvěru!\n\nPřejeme příjemný zbytek dne!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:28:39', 'email', 1),
(63, 'cs_CZ', 0, 'Dobrý den [USER.NAME]!', 'user.contact', 'Byli jste kontaktování ohledně Vašeho inzerátu, uživatelem [EMAIL.SENDER] \n\n[EMAIL.FROM], má pro Vás tuto zprávu:\n\n[EMAIL.BODY]. \n\nPřejeme příjemný zbytek dne!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:30:08', 'email', 1),
(64, 'cs_CZ', 0, 'Změna hesla [SITE.NAME]', 'auth.remember', 'Dobrý den [USER.NAME],\n\nKlikněte na tento link  [URL.QL]\n\nDěkujeme!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:31:01', 'email', 1),
(65, 'cs_CZ', 0, 'Vítejte na [SITE.NAME]!', 'auth.register', 'Vítejte [USER.NAME],\n\nJsme rádi, že jste se k nám připojili! [URL.QL]\n\nZapamatujte si Vaše přihlašovací údaje:\nEmail: [USER.EMAIL]\nHeslo: [USER.PWD]\n\nJiž nemáme Vaše původní heslo.\n\nPřejeme příjemný zbytek dne!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:32:10', 'email', 1),
(66, 'fr_FR', 0, 'Félicitations! Votre annonce a été créée sur [SITE.NAME]!', 'ads.confirm', 'Bienvenue [USER.NAME],\n\nMerci d''avoir créé une anonnce sur [SITE.NAME]! \n\nVeuillez cliquer sur ce lien [URL.QL] pour la confirmer.\n\nCordialement!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:37:55', 'email', 1),
(67, 'fr_FR', 0, 'L''annonce a été créée sur [SITE.NAME]!', 'ads.user_check', 'Bonjour [USER.NAME],\n\nL''annonce est créée sous le compte [USER.NAME]! Vous pouvez cliquer sur ce lien pour la voir  [URL.AD]\n\nSi vous n''êtes pas à l''origine de cette annonce, cliquez sur ce lien pour nous contacter [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:39:00', 'email', 1),
(68, 'fr_FR', 0, 'Félicitations! Votre annonce a été créée sur [SITE.NAME]!', 'ads.notify', 'Bonjour [USER.NAME],\n\nMerci d''avoir créé une anonnce sur [SITE.NAME]! \n\nVous pouvez modifier votre annonce ici [URL.QL].\n\nVotre annonce est en attente de publication, elle doit être validée par l''administrateur.\n\nNous sommes désolés pour la gène occasionnée. Nous allons l''examiner dès que possible. \n\nCordialement!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:41:07', 'email', 1),
(69, 'fr_FR', 0, '[EMAIL.SENDER] souhaite vous contacter!', 'contact.admin', 'Salut Administrateur,\n\n[EMAIL.SENDER]: [EMAIL.FROM], a un message pour toi:\n\n[EMAIL.BODY] \n\nCordialement!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:43:24', 'email', 1),
(70, 'fr_FR', 0, 'Votre annonce sur [SITE.NAME] a été activée!', 'ads.activated', 'Bonjour [USER.OWNER],\n\nNous vous informons que votre annonce [URL.QL] a été activée et publiée!\nElle est désormais visible par tous. \n\nEn espérant ne pas vous avoir fait attendre trop longtemps.\n\nCordialement!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:45:03', 'email', 1),
(71, 'fr_FR', 0, 'Bonjour [USER.NAME]!', 'user.new', 'Bienvenue sur [SITE.NAME]. \n\nNous sommes ravis de votre participation,\nVous pouvez vous connecter avec votre email: [USER.EMAIL], \nvotre mot de passe: [USER.PWD]. \n\nLe mot de passe est auto-généré, pour le changer cliquez sur ce lien [URL.PWCH]. \n\nMerci de votre confiance! \n\nCordialement!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:46:18', 'email', 1),
(72, 'fr_FR', 0, 'Bonjour [USER.NAME]!', 'user.contact', 'Vous avez été contacté au sujet de votre annonce. \nL''utilisateur [EMAIL.SENDER] [EMAIL.FROM], a un message pour vous:\n[EMAIL.BODY]. \n\nCordialement!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:48:14', 'email', 1),
(73, 'fr_FR', 0, 'Changement de mot de passe [SITE.NAME]', 'auth.remember', 'Bonjour [USER.NAME],\n\nVeuillez suivre ce lien [URL.QL]\n\nMerci!!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:49:56', 'email', 1),
(74, 'fr_FR', 0, 'Bienvenue sur [SITE.NAME]!', 'auth.register', 'Bienvenue [USER.NAME],\n\nNous sommes ravis de votre participation! [URL.QL]\nVoici vos informations d''identification:\nEmail: [USER.EMAIL]\nMot de passe: [USER.PWD]\n\nVotre mot de passe original n''a pas été enregistré, veuillez donc conserver ces informations précieusement!\n\nCordialement!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:51:20', 'email', 1),
(75, 'da_DK', 0, 'Din annonce blev oprettet på [SITE.NAME]!', 'ads.confirm', 'Velkommen [USER.NAME],\n\nTak for din annonce på [SITE.NAME]!\n\nFølge dette link for at bekræfte annoncen.\n\nMed venlig hilsen', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:55:52', 'email', 1),
(76, 'da_DK', 0, 'Annonce oprettet på [SITE.NAME]!', 'ads.user_check', 'Hej [USER.NAME],\n\nAnnoncen er oprettet under din profil [USER.NAME]! Du kan følge dette link for at se din annonce: [URL.AD]\n\nHvis det ikke er dig der har oprettet denne annonce, så kontakt os her: [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:56:56', 'email', 1),
(77, 'da_DK', 0, 'Din annonce blev oprettet på [SITE.NAME]!', 'ads.notify', 'Hej [USER.NAME],\n\nTak for din annonce på [SITE.NAME]! \n\nDu kan redigere din annonce her [URL.QL].\n\nDin annonce er endnu ikke udgivet, da den skal valideres af en administrator. \n\nVi validerer annoncer så hurtigt vi kan, og undskylder på forhånd for ventetiden.\n\nMed venlig hilsen', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:58:35', 'email', 1),
(78, 'da_DK', 0, '[EMAIL.SENDER] vil gerne i kontakt med dig', 'contact.admin', 'Hej Admin,\n\n[EMAIL.SENDER]: [EMAIL.FROM], har skrevet en besked til dig:\n\n[EMAIL.BODY] \n\nMed venlig hilsen', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 08:59:54', 'email', 1),
(79, 'da_DK', 0, 'Din annonce på [SITE.NAME] er blevet aktiveret!', 'ads.activated', 'Hej [USER.OWNER],\n\nDin annonce [URL.QL] er blevet aktiveret,\nden er nu synlig for alle!\n\nHeld og lykke med annoncen.\n\nMed venlig hilsen', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 09:01:26', 'email', 1),
(80, 'da_DK', 0, 'Hej [USER.NAME]!', 'user.new', 'Velkommen til [SITE.NAME]. \nVi er glade for du vil bruge vores websted.\n\nDu kan logge ind med din email: [USER.EMAIL], \nog adgangskoden: [USER.PWD]. \n\nAdgangskoden er dannet automatisk, for at ændre den skal du følge dette link: [URL.PWCH]. \n\nVi håber du får glæde af vores service! \n\nMed venlig hilsen', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 09:03:10', 'email', 1),
(81, 'da_DK', 0, 'Hej [USER.NAME]!', 'user.contact', 'Der er et svar til din annonce. \nBrugeren [EMAIL.SENDER] [EMAIL.FROM], har skrevet følgende besked til dig:\n\n[EMAIL.BODY]. \n\nMed venlig hilsen', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 09:04:57', 'email', 1),
(82, 'da_DK', 0, 'Ændring af adgangskode på [SITE.NAME]', 'auth.remember', 'Hej [USER.NAME],\n\nFølg dette link: [URL.QL]\n\nTak!', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 09:07:13', 'email', 1),
(83, 'da_DK', 0, 'Velkommen til [SITE.NAME]!', 'auth.register', 'Velkommen [USER.NAME],\n\nVi er glade for du vil være med! [URL.QL]\n\nHusk venligst dine log ind oplysninger:\nEmail: [USER.EMAIL]\nAdgangskode: [USER.PWD]\n\nDin oprindelige adgangskode er ikke længere gyldig.\n\nMed venlig hilsen', '".$_POST['ADMIN_EMAIL']."', '2013-07-30 09:08:14', 'email', 1),
(84, 'no_NO', 0, 'Vellykket! Din annonse er opprettet på [SITE.NAME]!', 'ads.confirm', 'Velkommen [USER.NAME],\n\nTakk for at du la inn annonnse på [SITE.NAME]! \n\nVennligst klikk på denne lenken [URL.QL] for å godkjenne den.\n\nHilsen!', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:22:01', 'email', 1),
(85, 'no_NO', 0, 'Annonse er opprettet på [SITE.NAME]!', 'ads.user_check', 'Hallo [USER.NAME],\n\nAnnonse er opprettet for din bruker [USER.NAME]! Du kan klikke på denne lenken for å se annonsen [URL.AD]\n\nHvis du ikke har opprettet denne annonse, klikk på lenken for å kontakte oss [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:22:55', 'email', 1),
(86, 'no_NO', 0, 'Vellykket! Din annonse er opprettet på [SITE.NAME]!', 'ads.notify', 'Hallo [USER.NAME],\n\nTakk for at du la inn annonnse på [SITE.NAME]! \nDu kan editere annonsen her [URL.QL].\n\nDin annonse er fremdeles ikke publisert, den må godkjennes av en administrator. \n\nVi beklager ubeleiligheten. Vi vill se på det så snarest mulig.\n\nHilsen!', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:31:47', 'email', 1),
(87, 'no_NO', 0, '[EMAIL.SENDER] ønsker å kontakte deg!', 'contact.admin', 'Hallo Admin,\n\n[EMAIL.SENDER]: [EMAIL.FROM], har en melding til deg:\n\n[EMAIL.BODY] \n\nHilsen!', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:32:36', 'email', 1),
(88, 'no_NO', 0, 'Din annonse påt [SITE.NAME], har blitt aktivert!', 'ads.activated', 'Hallo [USER.OWNER],\n\nVi vil informere deg om at din annonse [URL.QL] har blitt aktivert!\nDen kan nå ses av andre. \n\nVi håper du ikke måtte vente for lenge. \n\nHilsen!', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:33:29', 'email', 1),
(89, 'no_NO', 0, 'Hallo [USER.NAME]!', 'user.new', 'Velkommen til [SITE.NAME]. \n\nVi setter pris på at du har registrert deg,\ndu kan logge deg inn via din email: [USER.EMAIL], \nmed passord: [USER.PWD]. \n\nPassordet er automatisk generert for deg, for å endre det kan du bruke denne lenken [URL.PWCH]. \n\nTakk for at du stoler på oss! \n\nHilsen!', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:34:46', 'email', 1),
(90, 'no_NO', 0, 'Hallo [USER.NAME]!', 'user.contact', 'Du har blitt kontaktet angående din annonse. \nBruker [EMAIL.SENDER] [EMAIL.FROM], har en beskjed til deg: \n[EMAIL.BODY]. \n\nHilsen!', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:35:54', 'email', 1),
(91, 'no_NO', 0, 'Endre passord [SITE.NAME]', 'auth.remember', 'Hallo [USER.NAME],\n\nKlikk på denne lenken  [URL.QL]\n\nTakk!!', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:37:12', 'email', 1),
(92, 'no_NO', 0, 'Velkommen to [SITE.NAME]!', 'auth.register', 'Velkommen [USER.NAME],\n\nVi setter pris på at du har registrert deg! [URL.QL]\n\nHusk din brukerinformasjon:\nE-post: [USER.EMAIL]\nPassword: [USER.PWD]\n\nVi har ikke ditt opprinnelige passord lenger.\n\nHilsen!', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:38:06', 'email', 1),
(93, 'ca_ES', 0, 'Èxit! El vostre anunci és creat damunt [SITE.NAME]!', 'ads.confirm', 'Benvingut [USER.NAME],\n\nGràcies Per crear un anunci a [SITE.NAME]! \n\nSi us plau clic en aquest enllaç [URL.QL] per confirmar-lo.\n\nConsideracions,', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:46:03', 'email', 1),
(94, 'ca_ES', 0, 'El vostre anunci és creat damunt [SITE.NAME]!', 'ads.user_check', 'Hola [USER.NAME],\n\nL''anunci és creat sota el vostre compte [USER.NAME]! Pots visitar aquest enllaç per veure anunci [URL.AD].\n\nSi no ets responsable per crear aquest anunci, clic un enllaç per contactar-nos [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-07-31 11:55:59', 'email', 1),
(95, 'ca_ES', 0, 'Èxit! El vostre anunci és creat damunt [SITE.NAME]!', 'ads.notify', 'Hola [USER.NAME],\n\nGràcies Per crear un anunci a [SITE.NAME]! \nEt Pot editar el vostre anunci aquí [URL.QL].\n\nEl vostre anunci és encara no publicat, necessita ser validat per un administrador. \nEns sap greu per qualsevol inconveniència. El revisarem al més aviat possible. \n\nConsideracions!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 09:29:57', 'email', 1),
(96, 'ca_ES', 0, '[EMAIL.SENDER] vol contactarte!', 'contact.admin', 'Hola Admin,\n\n[EMAIL.SENDER]: [EMAIL.FROM], té un missatge per tu:\n\n[EMAIL.BODY]\n\nConsideracions!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:17:04', 'email', 1),
(97, 'ca_ES', 0, 'El vostre anunci a [SITE.NAME] ha estat activat!', 'ads.activated', 'Hola [USER.OWNER],\n\nEt volem informar que el vostre anunci [URL.QL] ha estat activat! Ara pugui ser vist per altres. \nEsperem no vam fer esperes per llarg. \n\nConsideracions!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:18:05', 'email', 1),
(98, 'ca_ES', 0, 'Hola [USER.NAME]', 'user.new', 'Benvingut a [SITE.NAME].\n\nSom realment feliços que te''ns ha unit,\net Pot entrar dins amb el vostre correu electrònic: [USER.EMAIL], \nAmb contrasenya: [USER.PWD]. \nLa contrasenya és generada per tu, per canviar-lo pots visitar aquest enllaç [URL.PWCH]. \n\nGràcies Per confiar-nos en! \n\nConsideracions!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 09:45:18', 'email', 1),
(99, 'ca_ES', 0, 'Hola [USER.NAME]!', 'user.contact', 'T''Ha estat contactat pel que fa al vostre anunci. Usuari [EMAIL.SENDER] [EMAIL.FROM], té un missatge per tu: \n[EMAIL.BODY]\n\nConsideracions!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 09:47:07', 'email', 1),
(100, 'ca_ES', 0, 'Canvi contrasenya [SITE.NAME]', 'auth.remember', 'Hola [USER.NAME],\n\nSeguir aquest enllaç [URL.QL]\n\nGràcies!!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 09:48:18', 'email', 1),
(101, 'ca_ES', 0, 'Benvingut a [SITE.NAME]!', 'auth.register', 'Benvinguda [USER.NAME],\n\nSom realment feliços que te''ns ha unit! [URL.QL]\n\nRecordar els vostres detalls d''usuari:\nCorreu electrònic: [USER.EMAIL]\nContrasenya: [USER.PWD]\nNo tenim la vostra contrasenya original més.\n\nConsideracions!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 09:49:40', 'email', 1),
(102, 'in_ID', 0, 'Berhasil! Iklan anda telah dibuat [SITE.NAME]!', 'ads.confirm', 'Selamat datang [USER.NAME],\n\nTerima kasih telah membuat iklan di [SITE.NAME]! \n\nSilahkan Klik link ini [URL.QL] untuk konfirmasi.\n\nRegard!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:04:14', 'email', 1),
(103, 'in_ID', 0, 'Iklan telah dibuat [SITE.NAME]!', 'ads.user_check', 'Halo [USER.NAME],\n\nIklananda telah dibuat dibawah email [USER.NAME]! Anda dapat mengunjungi tutan ini untuk melihat iklan [URL.AD]\n\nJika anda tidak bertanggung jawab membuat iklan ini, klik tautan untuk hubungi kami [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:05:30', 'email', 1),
(104, 'in_ID', 0, 'Berhasil! Iklan anda telah dibuat [SITE.NAME]!', 'ads.notify', 'Halo [USER.NAME],\n\nTerima kasih telah membuat iklan di [SITE.NAME]! \n\nAnda dapar mengedit iklan anda disini [URL.QL].\n\nIklan ada masih belum dipublikasi, harus disahkan oleh administrator.\nKami meminta maaf atas gangguan ini. Kami akan mengadakan peninjauan segera.\n\nRegard!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:07:03', 'email', 1),
(105, 'in_ID', 0, '[EMAIL.SENDER] ingin menghubungi Anda!', 'contact.admin', 'Halo Admin,\n\n[EMAIL.SENDER]: [EMAIL.FROM], Mempunyai pesan untuk anda:\n[EMAIL.BODY] \n\nRegard!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:07:54', 'email', 1),
(106, 'in_ID', 0, 'Iklan ada pada situs [SITE.NAME] telah diaktifan!', 'ads.activated', 'Halo [USER.OWNER],\n\nKami akan menginformasikan bahwa iklan anda [URL.QL] telah diaktifkan!\nSekarang dapat terlihat oleh yang lain.\n\nKami harap tidak membuat anda menunggu lama.\n\nRegard!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:09:34', 'email', 1),
(107, 'in_ID', 0, 'Halo [USER.NAME]!', 'user.new', 'Selamat datang di [SITE.NAME]. \n\nKami sangat senang karena anda telah bergabung dengan kami,\nAnda bisa masuk dengan email anda : [USER.EMAIL], \nDengan taka sandi: [USER.PWD]. \nKata sandi dihasilkan untuk anda, untuk mengubahnya anda bisa klik tautan ini [URL.PWCH].\n\nTerima kasih telah mempercayai kami!\n\nRegard!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:11:12', 'email', 1),
(108, 'in_ID', 0, 'Halo [USER.NAME]!', 'user.contact', 'Anda telah dihubungi mengenai iklan Anda. Pengguna [EMAIL.SENDER] [EMAIL.FROM], mempunyai pesan untuk anda: \n[EMAIL.BODY]. \n\nRegard!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:12:05', 'email', 1),
(109, 'in_ID', 0, 'Ubah kata sandi [SITE.NAME]', 'auth.remember', 'Halo [USER.NAME],\n\nIkutitautan ini [URL.QL]\n\nTerima kasih!!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:13:28', 'email', 1),
(110, 'in_ID', 0, 'Selamat datang di [SITE.NAME]!', 'auth.register', 'Selamat datang [USER.NAME],\n\nKami sangat senang karena anda telah bergabung dengan kami! [URL.QL]\n\nMengingat rincian pengguna Anda:\nEmail: [USER.EMAIL]\nKata sandi: [USER.PWD]\n\nKami tidak mempunyai kata sandi anda yang asli lagi.\n\nRegard!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:14:44', 'email', 1),
(111, 'sk_SK', 0, 'Blahoželáme! Váš inzerát je vytvorený na [SITE.NAME]!', 'ads.confirm', 'Vitajte [USER.NAME],\n\nĎakujeme za pridanie inzerátu na [SITE.NAME]!\n\nProsím kliknite na nasledujúci odkaz [URL.QL] pre potvrdenie.\n\nS pozdravom!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:19:56', 'email', 1),
(112, 'sk_SK', 0, 'Inzerát je vytvorený na [SITE.NAME]!', 'ads.user_check', 'Dobrý deň [USER.NAME],\n\n Inzerát bol vytvorený pod Vaším účtom [USER.NAME]! Pre zobrazenie inzerátu môžete navštíviť túto stránku [URL.AD]\n\nAk ste nevytvorili tento inzerát, kliknite na nasledujúci link aby ste nás kontaktovali  [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:21:41', 'email', 1),
(113, 'sk_SK', 0, 'Blahoželáme! Váš inzerát je vytvorený na [SITE.NAME]!', 'ads.notify', 'Dobrý deň  [USER.NAME],\n\nĎakujeme za zadanie inzerátu na stránke [SITE.NAME]! \nVáš inzerát môžete upraviť tu [URL.QL].\n\nVáš inzerát ešte nie je zverenený, musí ho schváliť administrátor.\nOspravedlňujeme sa za nepríjemnosť. Budeme sa tomu venovať hneď ako to bude možné.\n\nS pozdravom!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:23:18', 'email', 1),
(114, 'sk_SK', 0, '[EMAIL.SENDER] má pre Vás správu', 'contact.admin', 'Dobrý deň Admin,\n\n[EMAIL.SENDER]: [EMAIL.FROM], má pre Vás správu:\n\nS pozdravom!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:27:37', 'email', 1),
(115, 'sk_SK', 0, 'Váš inzerát na [SITE.NAME] bol aktivovaný!', 'ads.activated', 'Dobrý deň [USER.OWNER],\n\nOznamujeme Vám, že Váš inzerát [URL.QL] aktivovaný!\nTeraz to môžu vidieť aj ostatní.\n\nDúfame, že sme Vás nenechali čakať dlho.\n\nS pozdravom !', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:28:44', 'email', 1),
(116, 'sk_SK', 0, 'Dobrý deň [USER.NAME]!', 'user.new', 'Vitajte na stránkach [SITE.NAME].\n\nNaozaj nás teší, že ste sa k nám pripojili.\nmôžete sa prihlásiť Vaším emailom: [USER.EMAIL], \ns heslom: [USER.PWD]. \n\nHeslo je generované pre Vás, zmeniť ho môžete tu [URL.PWCH]. \n\nĎakujeme za dôveru!\n\nS pozdravom!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:30:30', 'email', 1),
(117, 'sk_SK', 0, 'Dobrý deň [USER.NAME]!', 'user.contact', 'Boli ste kontaktovaní vzhľadom na Váš inzerát. \n\nUžívateľ [EMAIL.SENDER] [EMAIL.FROM], má pre Vás správu: \n\n[EMAIL.BODY]. \n\nS pozdravom!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:32:17', 'email', 1),
(118, 'sk_SK', 0, 'Zmena hesla [SITE.NAME]', 'auth.remember', 'Dobrý deň  [USER.NAME],\n\nNásledujte tento odkaz [URL.QL]\n\nĎakujeme!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:34:08', 'email', 1),
(119, 'sk_SK', 0, 'Vitajte na [SITE.NAME]!', 'auth.register', 'Vitajte [USER.NAME],\n\nNaozaj nás teší, že ste sa k nám pripojili! [URL.QL]\nUchovajte si Vaše údaje:\nEmail: [USER.EMAIL]\nHeslo: [USER.PWD]\nUž nemáme vaše pôvodné heslo.\n\nĎakujeme!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 10:35:50', 'email', 1),
(120, 'ml_IN', 0, '[SITE.NAME] - ല്‍ താങ്കളുടെ പരസ്യം സ്വീകരിക്കപ്പെട്ടിരിക്കുന്നു.  അഭിനന്ദനങ്ങള്‍!', 'ads.confirm', 'സ്വാഗതം [USER.NAME],\n\n[SITE.NAME] - ല്‍ ഒരു പരസ്യം പ്രസിദ്ധീകരിക്കുവാന്‍ മുമ്പോട്ടുവന്നതില്‍ താങ്കളോട്‌ നന്ദി അറിയിക്കുന്നു.\n\nദയവായി അത്‌ ഉറപ്പാക്കുവാന്‍ ഈ ലിങ്കില്‍ ക്ലിക്ക് ചെയ്യുക [URL.QL] \n\nഅന്യേഷണങ്ങള്‍ അറിയിക്കുന്നു !', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:21:25', 'email', 1),
(121, 'ml_IN', 0, '[SITE.NAME] - ല്‍  പരസ്യം സ്വീകരിക്കപ്പെട്ടിരിക്കുന്നു!', 'ads.user_check', 'ഹെല്ലോ [USER.NAME],\n\nപ്രിയ [USER.NAME], താങ്കളുടെ അക്കൗണ്ടില്‍ ഈ പരസ്യം ഞങ്ങള്‍ സ്വീകരിച്ചിരിക്കുന്നു. താങ്കളുടെ പരസ്യം കാണുവാന്‍ ഈ ലിങ്കില്‍ ക്ലിക്ക് ചെയ്യുക [URL.AD]\n\nഈ പരസ്യം താങ്കള്‍ തന്നിരിക്കുന്നതല്ല എങ്കില്‍, ദയവായി ഈ ലിങ്കില്‍ ക്ലിക്ക് ചെയ്ത്‌ ഞങ്ങളെ വിവരമറിയിക്കുക [URL.CONTACT].', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:23:21', 'email', 1),
(122, 'ml_IN', 0, '[SITE.NAME] - ല്‍ താങ്കളുടെ പരസ്യം സ്വീകരിക്കപ്പെട്ടിരിക്കുന്നു.  അഭിനന്ദനങ്ങള്‍!', 'ads.notify', 'ഹെല്ലോ [USER.NAME],\n\n[SITE.NAME] - ല്‍ ഒരു പരസ്യം പ്രസിദ്ധീകരിക്കുവാന്‍ മുമ്പോട്ടുവന്നതില്‍ താങ്കളോട്‌ നന്ദി അറിയിക്കുന്നു.\n\nതാങ്കളുടെ പരസ്യത്തില്‍ ഏതെങ്കിലും മാറ്റം വരുത്തുവാന്‍ ഉണ്ടെങ്കില്‍ ഈ ലിങ്ക് ഉപയോഗിക്കുക [URL.QL].\n\nതാങ്കളുടെ പരസ്യം ഇപ്പോഴും പ്രസിദ്ധീകരിക്കപ്പെട്ടില്ല. അത് അഡ്മിനിസ്ട്രേട്ടരുടെ വിലയിരുത്തലിനുകൂടി വിധേയമാവേണ്ടതുണ്ട്.\nതാങ്കള്‍ക്കുണ്ടായ അസൌകര്യങ്ങള്‍ക്ക് ക്ഷമാപണം നടത്തുന്നു.ഏറ്റവും വേഗത്തില്‍ പരിശോധനാ നടപടികള്‍ പൂര്‍ത്തീകരിക്കുന്നതാണ്.\n\nഅന്യേഷണങ്ങള്‍ അറിയിക്കുന്നു !', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:25:33', 'email', 1),
(123, 'ml_IN', 0, '[SITE.NAME] - ല്‍ തന്നിരുന്ന താങ്കളുടെ പരസ്യം ആക്ടിവേറ്റ് ചെയ്യപ്പെട്ടിരിക്കുന്നു. അഭിനന്ദനങ്ങള്‍', 'ads.activated', 'ഹെല്ലോ [USER.OWNER],\n\nതാങ്കളുടെ പരസ്യം [URL.QL] ആക്ടിവേറ്റ് ആയിരിക്കുന്നതായി അറിയിക്കുന്നതില്‍ അതിയായ സന്തോഷമുണ്ട്.\nഇപ്പോള്‍ താങ്കളുടെ പരസ്യം മറ്റുള്ളവര്‍ക്കും കാണുവാന്‍ കഴിയും.\nതാങ്കളെ ഏറെ കാത്തിരുത്തിയില്ലായെന്നു കരുതട്ടെ.\nഅന്യേഷണങ്ങള്‍ അറിയിക്കുന്നു !', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:27:09', 'email', 1),
(124, 'ml_IN', 0, 'ഹെല്ലോ [USER.NAME]!', 'user.new', '[SITE.NAME] - ലേയ്ക്കു സ്വാഗതം!\n\nതാങ്കള്‍ ഞങ്ങളോടൊപ്പം ഒത്തുചേര്‍ന്നത്തില്‍ അതിയായ സന്തോഷം അറിയിക്കുന്നു.\nപ്രിയ [USER.EMAIL], താങ്കളുടെ ഇമെയില്‍ വിലാസം ഉപയോഗിച്ച് താങ്കള്‍ക്കിനി ലോഗിന്‍ ചെയ്യാവുന്നതാണ്.\nതാങ്കളുടെ ഇപ്പോഴത്തെ പാസ്‌വേഡ് : [USER.PWD], സിസ്റ്റം ജനറേറ് ചെയ്തിരിക്കുന്നത് ആണ്. അത് മാറ്റുവാന്‍ ഈ ലിങ്കില്‍ ക്ലിക്ക് ചെയ്യുക [URL.PWCH]. \n\nഞങ്ങളിലര്‍പ്പിച്ച വിശ്വാസത്തിന് നന്ദി!\n\nഅന്യേഷണങ്ങള്‍ അറിയിക്കുന്നു !', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:28:25', 'email', 1),
(125, 'ml_IN', 0, 'ഹെല്ലോ [USER.NAME]!', 'user.contact', 'താങ്കളുടെ പരസ്യത്തിന് ഇതാ ഒരു അന്വേഷണം എത്തിയിരിക്കുന്നു.  [EMAIL.SENDER] [EMAIL.FROM] - ല്‍ നിന്നുമുള്ള പ്രതികരണമാണിത്.\n\n[EMAIL.BODY]\n\nഅന്യേഷണങ്ങള്‍ അറിയിക്കുന്നു !', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:29:59', 'email', 1),
(126, 'ml_IN', 0, 'പാസ്‌വേഡ് മാറ്റം : [SITE.NAME]!', 'auth.remember', 'ഹെല്ലോ [USER.NAME],\n\nഈ ലിങ്ക് പിന്‍തുടരുക [URL.QL]\n\nനന്ദി!', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:31:21', 'email', 1),
(127, 'ml_IN', 0, '[SITE.NAME] - ലേയ്ക്ക് സ്വാഗതം!', 'auth.register', 'സ്വാഗതം [USER.NAME],\n\nപ്രിയ [URL.QL], താങ്കള്‍ ഞങ്ങളോടൊപ്പം ഒത്തുചേര്‍ന്നത്തില്‍ അതിയായ സന്തോഷം അറിയിക്കുന്നു.\n\nതാങ്കളുടെ യൂസര്‍ അക്കൌണ്ട് വിവരങ്ങള്‍ ഓര്‍ത്തിരിക്കുക:\nഇമെയില്‍ : [USER.EMAIL]\nപാസ്‌വേഡ് : [USER.PWD]\n\nതുടര്‍ന്ന്‍ താങ്കളുടെ പാസ്‌വേഡ് ഞങ്ങളോടൊപ്പമല്ല, താങ്കളുടെ നിയന്ത്രണത്തിലാന്നെന്ന്‍ അറിയുക.\n\nഅന്യേഷണങ്ങള്‍ അറിയിക്കുന്നു !', '".$_POST['ADMIN_EMAIL']."', '2013-08-02 11:32:29', 'email', 1),
(128, 'ar', 0, 'تم بنجاح اضافة اعلانك على موقع [SITE.NAME]!', 'ads.confirm', '[right][USER.NAME] مرحبا[/right]\n[right]شكرا  لك على وضع اعلانك على  موقع [SITE.NAME][/right]\n[right]الرجاء الضغط على الرابط  [URL.QL] للتاكيد[/right]\n[right]مع ارق التحيات[/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:07:44', 'email', 1),
(129, 'ar', 0, 'تم ‘نشاء الاعلان  على  [SITE.NAME]!', 'ads.user_check', '[right][USER.NAME] مرحبا [/right]\n[right]تم انشاء اعلان  بواسطة حسابك [USER.NAME]!  اضغط على الرابط للتاكد من الاعلان [URL.AD][/right]\n[right]اذا لم تقم بانشاء هذا الاعلان , الرجاء الضغط على الرابط للتواصل معنا [URL.CONTACT][/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:13:12', 'email', 1),
(130, 'ar', 0, 'تم بنجاح اضافة اعلانك على موقع [SITE.NAME]!', 'ads.notify', '[right]مرحبا[USER.NAME][/right]\n[right]شكرا لك لانشائك اعلان على موقع [SITE.NAME][/right]\n[right]بمكانك التعديل على الاعلان  هنا [URL.QL][/right]\n[right]لم يتم نشر اعلانك حتى تتم الموافقة من الادارة.[/right]\n[right]ونحن نعتذر عن أي إزعاج. وسوف يتم مراجعة في أقرب وقت ممكن.[/right]\n[right]تحياتنا  لك [/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:16:20', 'email', 1),
(131, 'ar', 0, '!يريد الاتصال بك [EMAIL.SENDER]', 'contact.admin', '[right]اهلا مدير [/right]\n[right][EMAIL.SENDER] لديك رساله جديدة من [EMAIL.FROM][/right]\n[right][EMAIL.BODY][/right]\n[right]تحياتنا لك [/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:20:56', 'email', 1),
(132, 'ar', 0, 'تم تفعيل علانك على موقع [SITE.NAME]!', 'ads.activated', '[right]مرحبا [USER.OWNER][/right]\n[right]نود اعلامك بان اعلانك [URL.QL] قد تم تفعيلة ![/right]\n[right]الان يمكن للاخرين مشاهدته[/right]\n[right]نتمنى ألا نكون قد اطلنا انتظارك [/right]\n[right]تحياتنا لك [/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:22:53', 'email', 1),
(133, 'ar', 0, 'مرحبا [USER.NAME],', 'user.new', '[right]مرحبا بك في [SITE.NAME][/right]\n[right]نحن في غاية السعادة بنظمامك الينا .[/right]\n[right]يمكنك تسجيل الدخول بواسطة بريدك الالكتروني [USER.EMAIL][/right]\n[right]وكلمة المرور [USER.PWD].  تم انشاء كلمة المرور عشوائيا , يمكنك زيارة الرابط لتغيير كلمة السر [URL.PWCH][/right]\n[right]شكر لوضع ثقتك بنا ![/right]\n[right]تحياتنا لك![/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:25:05', 'email', 1),
(134, 'ar', 0, 'مرحبا [USER.NAME],', 'user.contact', '[right]تم الاتصال بك  بسبب اعلانك  المستخدم [EMAIL.FROM][EMAIL.SENDER] قام ارسالك  رساله  [/right]\n[right][EMAIL.BODY][/right]\n[right]تحياتنا لك [/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:26:01', 'email', 1),
(135, 'ar', 0, 'تغيير كلمة المرور [SITE.NAME]', 'auth.remember', '[right]مرحبا [USER.NAME][/right]\n[right]الرجاء  الضغط على الرابط التالي [URL.QL][/right]\n[right]وشكرا لك [/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:27:06', 'email', 1),
(136, 'ar', 0, 'مرحبا [SITE.NAME]', 'auth.register', '[right]مرحبا [USER.NAME][/right]\n[right]نحن في غاية السعادة بنظمامك الينا! [URL.QL][/right]\n[right]تفااصيل حسابك [/right]\n[right]البريد الالكتروني: [USER.EMAIL][/right]\n[right]كلمة السر: [USER.PWD][/right]\n[right]لم نعد نملك كلمة السر الاصلية  بعد الان.[/right]\n[right]تحياتنا لك[/right]', '".$_POST['ADMIN_EMAIL']."', '2013-08-05 10:28:13', 'email', 1);");


/**
 * Access
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."roles` (`id_role`, `name`, `description`) VALUES (1, 'user', 'Normal user'), (5, 'translator', 'User + Translations'), (10, 'admin', 'Full access');");
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."access` (`id_access`, `id_role`, `access`) VALUES 
            (1, 10, '*.*'),
            (2, 1, 'profile.*'),(3, 1, 'stats.user'),
            (4, 5, 'translations.*'),(5, 5, 'profile.*'),(6, 5, 'stats.user'),(7, 5, 'content.*');");

/**
 * Create user God/Admin 
 */
$password = hash_hmac('sha256', $_POST['ADMIN_PWD'], $hash_key);
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."users` (`id_user`, `name`, `seoname`, `email`, `password`, `status`, `id_role`) 
VALUES (1, 'admin', 'admin', '".$_POST['ADMIN_EMAIL']."', '$password', 1, 10)");

/**
 * Configs to make the app work
 * @todo refactor to use same coding standard
 * @todo widgets examples? at least at sidebar, rss, text advert, pages, locations...
 *
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."config` (`group_name`, `config_key`, `config_value`) VALUES
('sitemap', 'expires', '43200'),
('sitemap', 'on_post', 1),
('appearance', 'theme', 'default'),
('appearance', 'theme_mobile', ''),
('appearance', 'allow_query_theme', 0),
('i18n', 'charset', 'utf-8'),
('i18n', 'timezone', '".$_POST['TIMEZONE']."'),
('i18n', 'locale', '".$_POST['LANGUAGE']."'),
('i18n', 'allow_query_language', 0),
('payment', 'paypal_currency', 'USD'),
('payment', 'sandbox', 0),
('payment', 'to_featured', 0),
('payment', 'to_top', 0),
('payment', 'featured_days', '5'),
('payment', 'pay_to_go_on_feature', '10'),
('payment', 'pay_to_go_on_top', '5'),
('payment', 'paypal_account', ''),
('payment', 'paypal_seller', '0'),
('general', 'number_format', '%n'),
('general', 'date_format', 'd-m-y'),
('general', 'base_url', '".$_POST['SITE_URL']."'),
('general', 'moderation', 0),
('general', 'maintenance', 0),
('general', 'analytics', ''),
('general', 'translate', ''),
('general', 'feed_elements', '20'),
('general', 'map_elements', '100'),
('general', 'site_name', '".$_POST['SITE_NAME']."'),
('general', 'site_description', ''),
('general', 'advertisements_per_page', '10'),
('general', 'akismet_key', ''),
('general', 'alert_terms', ''),
('general', 'search_by_description', 0),
('general', 'blog', 0),
('general', 'blog_disqus',''),
('general', 'minify', 0),
('general', 'faq', 0),
('general', 'faq_disqus', ''),
('general', 'landing_page', '{\"controller\":\"home\",\"action\":\"index\"}'),
('image', 'allowed_formats', 'jpeg,jpg,png,'),
('image', 'max_image_size', '5'),
('image', 'height', ''),
('image', 'width', '1200'),
('image', 'height_thumb', '200'),
('image', 'width_thumb', '200'),
('image', 'quality', '90'),
('image', 'watermark', '0'),
('image', 'watermark_path', ''),
('image', 'watermark_position', '0'),
('advertisement', 'num_images', '4'),
('advertisement', 'expire_date', '0'),
('advertisement', 'address', 1),
('advertisement', 'phone', 1),
('advertisement', 'upload_file', 0),
('advertisement', 'location', 1),
('advertisement', 'captcha', 1),
('advertisement', 'website', 1),
('advertisement', 'price', 1),
('advertisement', 'contact', 1),
('advertisement', 'tos', ''),
('advertisement', 'disqus', ''),
('advertisement', 'map', 0),
('advertisement', 'map_zoom', 14),
('advertisement', 'center_lon', ''),
('advertisement', 'center_lat', ''),
('advertisement', 'ads_in_home', '0'),
('advertisement', 'banned_words_replacement', 'xxx'),
('advertisement', 'banned_words', ''),
('advertisement', 'fields', ''),
('advertisement', 'parent_category', 0),
('advertisement', 'related', 5),
('email', 'notify_email', '".$_POST['ADMIN_EMAIL']."'),
('email', 'smtp_active', 0),
('email', 'new_ad_notify', 0),
('email', 'smtp_host', ''),
('email', 'smtp_port', ''),
('email', 'smtp_auth', 0),
('email', 'smtp_ssl', 0),
('email', 'smtp_user', ''),
('email', 'smtp_pass', ''),
('social', 'config', '{\"debug_mode\":\"0\",\"providers\":{\"OpenID\":{\"enabled\":\"1\"},\"Yahoo\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}},
\"AOL\":{\"enabled\":\"1\"},\"Google\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}},\"Facebook\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}},
\"Twitter\":{\"enabled\":\"0\",\"keys\":{\"key\":\"\",\"secret\":\"\"}},\"Live\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}},\"MySpace\":{\"enabled\":\"0\",\"keys\":{\"key\":\"\",\"secret\":\"\"}},
\"LinkedIn\":{\"enabled\":\"0\",\"keys\":{\"key\":\"\",\"secret\":\"\"}},\"Foursquare\":{\"enabled\":\"0\",\"keys\":{\"id\":\"\",\"secret\":\"\"}}},\"base_url\":\"\",\"debug_file\":\"\"}');");


//base category
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."categories` 
  (`id_category` ,`name` ,`order` ,`id_category_parent` ,`parent_deep` ,`seoname` ,`description` )
VALUES (1, 'Home category', 0 , 0, 0, 'all', 'root category');");


//base location
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."locations` 
  (`id_location` ,`name` ,`id_location_parent` ,`parent_deep` ,`seoname` ,`description`)
VALUES (1 , 'Home location', 0, 0, 'all', 'root location');");

 

//sample values 
if ( cP('SAMPLE_DB') !== NULL)
{
    //sample catpegories
    mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."categories` (`id_category`, `name`, `order`, `created`, `id_category_parent`, `parent_deep`, `seoname`, `description`, `price`) VALUES
    (2, 'Jobs', 1, '2013-05-01 16:41:04', 1, 0, 'jobs', 'The best place to find work is with our job offers. Also you can ask for work in the ''Need'' section.', 0),
    (3, 'Languages', 2, '2013-05-01 16:41:04', 1, 0, 'languages', 'You want to learn a new language? Or can you teach a language? This is your section!', 0),
    (4, 'Others', 4, '2013-05-01 16:41:04', 1, 0, 'others', 'Whatever you can imagine is in this section.', 0),
    (5, 'Housing', 0, '2013-05-01 16:41:53', 1, 0, 'housing', 'Do you need a place to sleep, or you have something to offer; rooms, shared apartments, houses... etc.\n\nFind your perfect roommate here!', 0),
    (6, 'Market', 3, '2013-05-01 16:41:04', 1, 0, 'market', 'Buy or sell things that you don''t need anymore in the City, you will find someone interested, or maybe you are going to find exactly what you need.', 0),
    (7, 'Full Time', 1, '2009-04-22 17:31:43', 2, 1, 'full-time', 'Are you looking for a fulltime job? Or do you have a fulltime job to offer? Post your Ad here!', 0),
    (8, 'Part Time', 2, '2009-04-22 17:32:15', 2, 1, 'part-time', 'Are you looking for a parttime job? Or do you have a partime job to offer? Post your Ad here!', 0),
    (9, 'Internship', 3, '2009-04-22 17:33:05', 2, 1, 'internship', 'Are you looking for a internship in the City? Or do you have an internship to offer? Post it here!', 0),
    (10, 'Au pair', 4, '2009-06-19 09:26:22', 2, 1, 'au-pair', 'Find or require for a Au Pair service. Here is the best place', 0),
    (11, 'English', 1, '2009-04-22 17:33:52', 3, 1, 'english', 'Do you speak English? Or can you teach it? Do you want to learn? This is your category.', 0),
    (12, 'Spanish', 2, '2009-04-22 17:34:29', 3, 1, 'spanish', 'You want to learn Spanish? Or can you teach Spanish? This is your section!', 0),
    (13, 'Other Languages', 3, '2009-04-22 17:35:34', 3, 1, 'other-languages', 'Are you interested in learning or teaching any other language that is not listed? Post it here!', 0),
    (14, 'Events', 0, '2013-05-01 16:41:11', 4, 1, 'events', 'Upcoming Parties, Cinema, Museums, Parades, Birthdays, Dinners.... Everything!', 0),
    (15, 'Hobbies', 1, '2013-05-01 16:41:11', 4, 1, 'hobbies', 'Share your hobby with someone! Football, running, cinema, music, cinema, party ... Post it here!', 0),
    (16, 'Services', 3, '2009-04-22 17:38:33', 4, 1, 'services', 'Do you need a service? Relocation? Insurance? Doctor? Cleaning? Here you can ask for it or offer services!', 0),
    (17, 'Friendship', 2, '2013-05-01 16:41:17', 1, 1, 'friendship', 'Are you alone in the City? Here you can find new friends! or a new boyfriend/girlfriend ;)', 0),
    (18, 'Apartment', 1, '2009-04-22 17:39:32', 5, 1, 'apartment', 'Apartments, flats, monthly rentals, long terms, for days... this is the section to have your apartment in the City!', 0),
    (19, 'Shared Apartments - Rooms', 2, '2009-05-03 21:53:57', 5, 1, 'shared-apartments-rooms', 'You want to share an apartment? Then you need a room! Ask for rooms or add yours in this section.', 0),
    (20, 'House', 3, '2009-04-22 17:40:50', 5, 1, 'house', 'Rent a house, or offer your house for rent! Here you can find your beach house close to the City!', 0),
    (21, 'TV', 1, '2009-04-22 17:41:39', 6, 1, 'tv', 'TV, Video Games, TFT, Plasma, your old TV, or your new one can find a new owner!', 0),
    (22, 'Audio', 2, '2009-04-22 17:42:13', 6, 1, 'audio', 'HI-FI systems, iPod, MP3 players, MP4, if you don''t use it anymore sell it! If you try to find a second hand one, this is your place!', 0),
    (23, 'Furniture', 3, '2009-04-22 17:43:16', 6, 1, 'furniture', 'Do you need to furnish your home? Or would you like to sell your furniture? Post it here!', 0),
    (24, 'IT', 4, '2009-04-22 17:43:48', 6, 1, 'it', 'You need a computer? Laptop? Or do you have some old components? This is the IT market of the City!', 0),
    (25, 'Other Market', 5, '2009-04-22 17:44:12', 6, 1, 'other-market', 'In this market you can sell everything you want! Or search for it!', 0);
    ");
}


mysql_close();
