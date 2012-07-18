CREATE TABLE IF NOT EXISTS {db_pref}content (
  `id_blog` int(11) NOT NULL AUTO_INCREMENT,
  `blog_title` text NOT NULL,
  `blog_content` longtext NOT NULL,
  `post_date` int(11) NOT NULL,
  `published` int(1) NOT NULL,
  `is_status` int(1) NOT NULL,
  PRIMARY KEY (`id_blog`),
  UNIQUE KEY `id_blog` (`id_blog`)
) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_bin;

INSERT INTO {db_pref}content (`blog_title`, `blog_content`, `post_date`, `published`) VALUES
('Welcome to Vienara!', 'Welcome to Vienara!\r\n\r\nThanks for using our software. If you have any questions, feel free to ask them at our community forums. We hope you enjoy using our software.\r\n\r\nThanks!\r\nThe Vienara development team', '{cur_time}', 1);

CREATE TABLE IF NOT EXISTS {db_pref}settings (
  `id` text NOT NULL,
  `value` longtext NOT NULL
) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_bin;

INSERT INTO {db_pref}settings (`id`, `value`) VALUES
('password', '{user_password}'),
('title', 'My Blog'), 
('blogsperpage', '10'),
('order', 'DESC'),
('enable_search', '1'),
('top_button', '1'),
('timezone', 'America/Los_Angeles'),
('enable_extra_title', '0'),
('extra_title', ''),
('language', 'english_usa'),
('notice', 'Welcome to Vienara!'),
('enable_custom_copyright', '0'),
('custom_copyright', ''),
('copyright_link_to', ''),
('blog_url', '{blogurl}'),
('enable_comments', '0'),
('custom_css', ''),
('avatar', 'images/no_ava.png'),
('enable_likes', '0'),
('quick_status', '0'),
('menu_icons', '1'),
('css_cache_version', 'vienara10'),
('ignore_disabled_ext', '0'),
('ext_enable', '1'),
('version', '{cur_version}'),
('reg_comments', '1'),
('width', '90');

CREATE TABLE {db_pref}menu (
  `id_tab` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tab_position` int NOT NULL,
  `tab_link` text NOT NULL,
  `tab_label` tinytext NOT NULL
) ENGINE=MyIsam CHARACTER SET utf8 COLLATE 'utf8_bin';

CREATE TABLE {db_pref}pages (
  `id_page` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `page_title` text NOT NULL,
  `page_body` longtext NOT NULL,
  `is_php` tinyint NOT NULL
) ENGINE=MyIsam CHARACTER SET utf8 COLLATE 'utf8_bin';

CREATE TABLE {db_pref}comments (
  `id_comment` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_blog` int(11) NOT NULL,
  `isadmin` int(1) NOT NULL,
  `ip` text NOT NULL,
  `website` text NOT NULL,
  `message` longtext NOT NULL,
  `poster_time` int(11) NOT NULL,
  `username` text NOT NULL
) ENGINE=MyIsam CHARACTER SET utf8 COLLATE 'utf8_bin';

CREATE TABLE IF NOT EXISTS `{db_pref}guestbook` (
  `id_comment` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip_adress` text NOT NULL,
  `message` longtext NOT NULL,
  `time` int(11) NOT NULL,
  `isadmin` tinyint NOT NULL,
  `website` mediumtext NOT NULL,
  `username` text NOT NULL
) ENGINE=MyIsam CHARACTER SET utf8 COLLATE 'utf8_bin';
