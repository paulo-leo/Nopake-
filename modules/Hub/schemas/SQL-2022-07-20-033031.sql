CREATE TABLE IF NOT EXISTS ec_adverts(id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), name VARCHAR(80) NOT NULL, description VARCHAR(250) NOT NULL, url VARCHAR(200) NOT NULL, clicks FLOAT(10,2) NOT NULL, stars INTEGER(1) NOT NULL, status INTEGER(1) NOT NULL DEFAULT 5) ENGINE=innodb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE IF NOT EXISTS ec_categories(id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), name VARCHAR(10) NOT NULL, status INTEGER(1) NOT NULL) ENGINE=innodb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE IF NOT EXISTS ec_categories_items(id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), advert_id BIGINT(20) UNSIGNED NOT NULL,CONSTRAINT fk_advert_id FOREIGN KEY(advert_id) REFERENCES ec_adverts(id) , categoria_id BIGINT(20) UNSIGNED NOT NULL,CONSTRAINT fk_categoria_id FOREIGN KEY(categoria_id) REFERENCES ec_categories(id)  ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=innodb DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;