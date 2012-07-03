UPDATE {db_pref}settings
	SET value = '{cur_version}'
	WHERE id = 'version';
