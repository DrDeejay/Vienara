UPDATE {db_pref}settings
	SET value = '{cur_version}'
	WHERE id = 'version';

ALTER TABLE {db_pref}pages
	DROP 'show_header';
