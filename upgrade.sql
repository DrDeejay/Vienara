UPDATE {db_pref}settings
	SET value = '{cur_version}'
	WHERE id = 'version';

INSERT INTO {db_pref}settings
	VALUES (
		'reg_comments', '1'
	);

CREATE TABLE IF NOT EXISTS {db_pref}comments (
  `id_comment` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_blog` int(11) NOT NULL,
  `isadmin` int(1) NOT NULL,
  `ip` text NOT NULL,
  `website` text NOT NULL,
  `message` longtext NOT NULL,
  `poster_time` int(11) NOT NULL,
  `username` text NOT NULL
) ENGINE=MyIsam CHARACTER SET utf8 COLLATE 'utf8_bin';
