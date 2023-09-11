SET
    FOREIGN_KEY_CHECKS = 0;

DROP
    DATABASE IF EXISTS `ams-db`;
CREATE
    DATABASE `ams-db`;
USE
    `ams-db`;

DROP
    USER IF EXISTS `admin`;
CREATE
    USER `admin` IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON *.* TO `admin` WITH GRANT OPTION;

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article`
(
    article_id  int(11) NOT NULL AUTO_INCREMENT,
    title       varchar(255) DEFAULT NULL,
    content     text,
    url         varchar(255) DEFAULT NULL,
    description varchar(255) DEFAULT NULL,
    PRIMARY KEY (article_id),
    UNIQUE KEY url (url)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci;

INSERT INTO `article`
VALUES ('1', 'Introduction',
        '<p>Welcome to our web page!</p><p>This web is built using <strong>Nette framework</strong>. This is introductory article.</p>',
        'introduction', 'Introductory article.');
INSERT INTO `article`
VALUES ('2', 'Page not found', '<p>Sorry, requested page cannot be found. Check your URL address.</p>', 'error',
        'Page not found.');

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`
(
    user_id  int(11)                         NOT NULL AUTO_INCREMENT,
    username varchar(255)                    NOT NULL,
    email    varchar(255)                    NOT NULL,
    password varchar(60)                     NOT NULL,
    role     enum ('authenticated', 'admin') NOT NULL DEFAULT 'authenticated',
    PRIMARY KEY (user_id),
    UNIQUE KEY `username` (username) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8
  COLLATE = utf8_general_ci;

INSERT INTO `user`
VALUES ('1', 'admin', 'admin@localhost.com', '$2y$10$h8vmMU0yHJ4jFOpfxrZO0eIW3qgnRFXsdi4G9DKzXaHuo9OLPuPJu',
        'admin'); -- password: admin123
INSERT INTO `user`
VALUES ('2', 'test', 'test@localhost.com', '$2y$10$Cv3tdMbkkw.GUY/vrydUGufhpUQg1JSKOdHLKds5DI5EZaeuV/AIm',
        'authenticated'); -- password: testuser