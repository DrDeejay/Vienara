UPDATE {db_pref}settings
	SET value = '{cur_version}'
	WHERE id = 'version';

ALTER TABLE {db_pref}pages
	DROP COLUMN show_header;

INSERT INTO {db_pref}settings
VALUES (
	'form_key',
	'{form_key}'
);
