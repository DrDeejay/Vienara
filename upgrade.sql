UPDATE {db_pref}settings
	SET value = '{cur_version}'
	WHERE id = 'version';

INSERT INTO {db_pref}settings (`id`, `value`)
VALUES (
	'custom_css',
	''
);

UPDATE {db_pref}content 
	SET blog_content = REPLACE(blog_content, '\\', '');

UPDATE {db_pref}pages 
	SET page_body = REPLACE(page_body, '\\', '');
