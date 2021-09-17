-- 5.6

-- 1. Отримання останніх N опублікованих за датою записів/постів для першої сторінки, враховуючи їх стан.
-- Мають включати всі необхідні поля: пост (ID, назва, alias, дата публікації), категорія (ID, назва, alias),
-- автор (ID, імʼя), фото (імʼя файла та alt-текст)
SELECT
    p.id,
    p.title,
    p.alias,
    p.description,
    p.publish_date,
    p.category_id,
    pc.title        AS category_title,
    pc.alias        AS category_alias,
    p.author_id,
    u.firstname     AS author_firstname,
    u.lastname      AS author_lastname,
    pc2.filename    AS cover_filename,
    pc2.alt         AS cover_alt
FROM
    posts AS p
LEFT JOIN
    posts_categories AS pc ON ( p.category_id = pc.id )
LEFT JOIN
    users AS u ON ( p.author_id = u.id )
LEFT JOIN
    posts_covers AS pc2 ON ( p.cover_id = pc2.id )
INNER JOIN
    posts_statuses AS ps2 ON ( ps2.name = 'active' AND p.status_id = ps2.id )
WHERE
    p.status_id = ps2.id
    AND
    p.publish_date <= now()
ORDER BY
    p.publish_date DESC
LIMIT
    9;


-- 2. Отримання наступних N опублікованих за датою записів/постів, враховуючи їх стан,
-- отриманих через кнопку “Завантажити ще”. Передбачити пейджинг, оскільки може бути декілька сторінок
-- (поля ті ж, що для п.1)
SELECT
    p.id,
    p.title,
    p.alias,
    p.description,
    p.publish_date,
    p.category_id,
    pc.title        AS category_title,
    pc.alias        AS category_alias,
    p.author_id,
    u.firstname     AS author_firstname,
    u.lastname      AS author_lastname,
    pc2.filename    AS cover_filename,
    pc2.alt         AS cover_alt
FROM
    posts AS p
LEFT JOIN
    posts_categories AS pc ON ( p.category_id = pc.id )
LEFT JOIN
    users AS u ON ( p.author_id = u.id )
LEFT JOIN
    posts_covers AS pc2 ON ( p.cover_id = pc2.id )
INNER JOIN
    posts_statuses AS ps2 ON ( ps2.name = 'active' AND p.status_id = ps2.id )
WHERE
    p.status_id = ps2.id
    AND
    p.publish_date <= now()
ORDER BY
    p.publish_date DESC
LIMIT
    9
OFFSET
    9;          -- =( ($page - 1) * 9 )


-- 3. Отримання пунктів головного меню (таблиця website_menu) враховуючи їх порядок у полі order
SELECT
    `title`,
    `href`
FROM
    website_menu
ORDER BY
    `order` ASC;


-- 4. Отримання списку категорій та кількості постів/записів у кожній з них
SELECT
    pc.id,
    pc.title,
    pc.alias,
    (
        SELECT
            count(p.id)
        FROM
            posts p
        WHERE
            p.category_id = pc.id
            AND
            p.status_id = ( SELECT ps.id FROM posts_statuses ps WHERE ps.name = 'active' LIMIT 1 )
    ) AS posts_count
FROM
    posts_categories pc
ORDER BY
    pc.title ASC;


-- 5. Отримання всієї інформації для одного запису/поста за його ID, враховуючи стан та дату публікації.
SELECT
    p.id,
    p.title,
    p.alias,
    p.description,
    p.publish_date,
    p.category_id,
    p.content,
    pc.title        AS category_title,
    pc.alias        AS category_alias,
    p.author_id,
    u.firstname     AS author_firstname,
    u.lastname      AS author_lastname,
    pc2.filename    AS cover_filename,
    pc2.alt         AS cover_alt
FROM
    posts AS p
LEFT JOIN
    posts_categories AS pc ON ( p.category_id = pc.id )
LEFT JOIN
    users AS u ON ( p.author_id = u.id )
LEFT JOIN
    posts_covers AS pc2 ON ( p.cover_id = pc2.id )
INNER JOIN
    posts_statuses AS ps2 ON ( ps2.name = 'active' AND p.status_id = ps2.id )
WHERE
    p.status_id = ps2.id
    AND
    p.publish_date <= now()
    AND
    p.id = 1                    -- <POST_ID>
LIMIT
    1;


-- 6. Отримання всіх активних коментарів для одного запису/поста за його ID
SELECT
    pc.id,
    pc.author_id,
    u.firstname     AS author_firstname,
    u.lastname      AS author_lastname,
    pc.publish_date,
    pc.comment
FROM
    posts_comments AS pc
LEFT JOIN
    users AS u ON ( pc.author_id = u.id )
WHERE
    pc.post_id = 1              -- <POST_ID>
    AND
    pc.status_id = ( SELECT cs.id FROM comments_statuses AS cs WHERE name = 'active' LIMIT 1 )
ORDER BY
    pc.publish_date ASC;