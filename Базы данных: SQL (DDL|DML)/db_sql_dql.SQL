SELECT name
    FROM goods
    WHERE category = 'cars'
    ORDER BY price DESC;
-------
SELECT name FROM goods ORDER BY price LIMIT 1;
------
SELECT DISTINCT name FROM goods WHERE category = 'cars';
-----
SELECT category, COUNT(*) FROM goods GROUP BY category HAVING COUNT(*) > 1;
-----
SELECT goods.name, categories.name AS category_name
    FROM goods INNER JOIN categories ON goods.category_id = categories.id
    WHERE price > 2
    ORDER BY name DESC
    LIMIT 2;
-----
SELECT goods.name
    FROM goods LEFT JOIN categories ON goods.category_id = categories.id
    WHERE categories.id IS NULL;
-----
SELECT name
    FROM goods
    WHERE category_id in (select id from categories where state = 'published');
------



