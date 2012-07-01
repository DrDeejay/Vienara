UPDATE {db_pref}settings
	SET value = '{cur_version}'
	WHERE id = 'version';

INSERT INTO {db_pref}settings
VALUES (
	'cutoff',
	'1000'
);
