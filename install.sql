CREATE TABLE IF NOT EXISTS {db_pref}content (
  `id_blog` int(11) NOT NULL AUTO_INCREMENT,
  `blog_title` text NOT NULL,
  `blog_content` longtext NOT NULL,
  `post_date` int(11) NOT NULL,
  `published` int(1) NOT NULL,
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
('title', 'Vienara'), 
('blogsperpage', '10'),
('order', 'DESC'),
('top_button', '1'),
('timezone', 'America/New_York'),
('enable_extra_title', '0'),
('extra_title', ''),
('language', 'english_usa'),
('notice', 'Welcome to Vienara!'),
('width', '90%');
