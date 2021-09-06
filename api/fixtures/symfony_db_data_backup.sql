CREATE DATABASE IF NOT EXISTS symfony_db DEFAULT CHARACTER SET utf8mb4;
USE symfony_db;


DROP TABLE IF EXISTS user;
CREATE TABLE IF NOT EXISTS `user`
(
    id                   CHAR(36)     NOT NULL PRIMARY KEY,
    name                 VARCHAR(100) NOT NULL,
    email                VARCHAR(100) NOT NULL,
    password             VARCHAR(100)          DEFAULT NULL,
    avatar               VARCHAR(255)          DEFAULT NULL,
    token                VARCHAR(100)          DEFAULT NULL,
    active               TINYINT(1)            DEFAULT 0,
    reset_password_token VARCHAR(100)          DEFAULT NULL,
    created_at           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX IDX_user_email (email),
    CONSTRAINT U_user_email UNIQUE KEY (email)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;


DROP TABLE IF EXISTS user_group;
CREATE TABLE `user_group`
(
    id         CHAR(36)     NOT NULL PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    owner_id   CHAR(36)     NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX IDX_user_group_name (name),
    INDEX IDX_user_group_owner_id (owner_id),
    CONSTRAINT FK_user_group_owner_id FOREIGN KEY (owner_id) REFERENCES `user` (id)
        ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;


DROP TABLE IF EXISTS user_group_user;
CREATE TABLE `user_group_user`
(
    user_id  CHAR(36) NOT NULL,
    group_id CHAR(36) NOT NULL,
    UNIQUE U_user_id_group_id (user_id, group_id),
    CONSTRAINT FK_user_group_user_user_id FOREIGN KEY (user_id) REFERENCES `user` (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_user_group_user_group_id FOREIGN KEY (group_id) REFERENCES `user_group` (id)
        ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;


DROP TABLE IF EXISTS group_request;
CREATE TABLE `group_request`
(
    id          CHAR(36)     NOT NULL PRIMARY KEY,
    group_id    CHAR(36)     NOT NULL,
    user_id     CHAR(36)     NOT NULL,
    token       VARCHAR(100) NOT NULL,
    status      VARCHAR(10)  NOT NULL,
    accepted_at DATETIME DEFAULT NULL,
    INDEX IDX_group_request_group_id (group_id),
    INDEX IDX_group_request_user_id (user_id),
    CONSTRAINT FK_group_request_group_id FOREIGN KEY (group_id) REFERENCES `user_group` (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_group_request_user_id FOREIGN KEY (user_id) REFERENCES `user` (id)
        ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;


DROP TABLE IF EXISTS category;
CREATE TABLE `category`
(
    id         CHAR(36)    NOT NULL PRIMARY KEY,
    name       VARCHAR(50) NOT NULL,
    type       VARCHAR(8)  NOT NULL,
    owner_id   CHAR(36)    NOT NULL,
    group_id   CHAR(36)             DEFAULT NULL,
    created_at DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX IDX_category_owner_id (owner_id),
    INDEX IDX_category_group_id (group_id),
    CONSTRAINT FK_category_owner_id FOREIGN KEY (owner_id) REFERENCES `user` (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_category_group_id FOREIGN KEY (group_id) REFERENCES `user_group` (id)
        ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;


DROP TABLE IF EXISTS movement;
CREATE TABLE `movement`
(
    id          CHAR(36)      NOT NULL PRIMARY KEY,
    category_id CHAR(36)      NOT NULL,
    owner_id    CHAR(36)      NOT NULL,
    group_id    CHAR(36)               DEFAULT NULL,
    amount      DECIMAL(8, 2) NOT NULL,
    file_path   VARCHAR(255)           DEFAULT NULL,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX IDX_movement_category_id (category_id),
    INDEX IDX_movement_owner_id (owner_id),
    INDEX IDX_movement_group_id (group_id),
    CONSTRAINT FK_movement_category_id FOREIGN KEY (category_id) REFERENCES `category` (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_movement_owner_id FOREIGN KEY (owner_id) REFERENCES `user` (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT FK_movement_group_id FOREIGN KEY (group_id) REFERENCES `user_group` (id)
        ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;


DROP TABLE IF EXISTS doctrine_migration_versions;
CREATE TABLE `doctrine_migration_versions`
(
    `version`        varchar(191) COLLATE utf8_unicode_ci NOT NULL,
    `executed_at`    datetime DEFAULT NULL,
    `execution_time` int      DEFAULT NULL,
    PRIMARY KEY (`version`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8_unicode_ci;


INSERT INTO category(id, name, type, owner_id, group_id, created_at, updated_at)
VALUES ('20650174-ca47-4ff0-bb4e-17857ebd9bad', 'Brian Group Expense Category', 'expense',
        '58dab56a-92c6-4f30-86f3-068a8f6d8931', '11350c97-7d47-4f59-813c-53645e94fe03', '2021-09-06 11:50:10',
        '2021-09-06 11:50:10'),
       ('2fe2bfc2-b11a-4fd0-ba3a-34d995f2eba3', 'Brian Income Category', 'income',
        '58dab56a-92c6-4f30-86f3-068a8f6d8931', NULL, '2021-09-06 11:50:10', '2021-09-06 11:50:10'),
       ('6e090175-f6f0-4fa1-bdc8-a0091263dc4a', 'Peter Group Expense Category', 'expense',
        '400abf5b-1918-4f3f-bfb6-73c748de6101', 'ad6fd564-832e-4f05-9e4e-8acb9e1840f4', '2021-09-06 11:50:10',
        '2021-09-06 11:50:10'),
       ('b84b3a8e-98d4-4fee-8390-77a6c4a91d4e', 'Brian Expense Category', 'expense',
        '58dab56a-92c6-4f30-86f3-068a8f6d8931', NULL, '2021-09-06 11:50:10', '2021-09-06 11:50:10'),
       ('c8629fda-f37c-4fad-b7cd-673583821f21', 'Peter Expense Category', 'expense',
        '400abf5b-1918-4f3f-bfb6-73c748de6101', NULL, '2021-09-06 11:50:10', '2021-09-06 11:50:10'),
       ('defc973f-c6ae-4f2e-9d76-76135c6fcd85', 'Brian Group Income Category', 'income',
        '58dab56a-92c6-4f30-86f3-068a8f6d8931', '11350c97-7d47-4f59-813c-53645e94fe03', '2021-09-06 11:50:10',
        '2021-09-06 11:50:10'),
       ('df0e90b5-24c0-4f88-a60e-810479768eb6', 'Peter Group Income Category', 'income',
        '400abf5b-1918-4f3f-bfb6-73c748de6101', 'ad6fd564-832e-4f05-9e4e-8acb9e1840f4', '2021-09-06 11:50:10',
        '2021-09-06 11:50:10'),
       ('e305adea-8087-4f10-b77d-162241c674ec', 'Peter Income Category', 'income',
        '400abf5b-1918-4f3f-bfb6-73c748de6101', NULL, '2021-09-06 11:50:10', '2021-09-06 11:50:10');


INSERT INTO group_request(id, group_id, user_id, token, status, accepted_at)
VALUES ('f21bf4a7-f2b0-4f88-a5f9-b153d84fc6ae', 'ad6fd564-832e-4f05-9e4e-8acb9e1840f4',
        '58dab56a-92c6-4f30-86f3-068a8f6d8931', '234567', 'pending', NULL);

INSERT INTO movement(id, category_id, owner_id, group_id, amount, file_path, created_at, updated_at)
VALUES ('69042641-31ce-4f64-bfd8-e48efe2b9929', 'b84b3a8e-98d4-4fee-8390-77a6c4a91d4e',
        '58dab56a-92c6-4f30-86f3-068a8f6d8931', NULL, 200.00, NULL, '2021-09-06 11:50:10', '2021-09-06 11:50:10'),
       ('c5339cc3-af12-4f2d-81a2-c82a09f9572d', '6e090175-f6f0-4fa1-bdc8-a0091263dc4a',
        '400abf5b-1918-4f3f-bfb6-73c748de6101', 'ad6fd564-832e-4f05-9e4e-8acb9e1840f4', 1000.00, NULL,
        '2021-09-06 11:50:10', '2021-09-06 11:50:10'),
       ('f1f7f38b-e58b-4fca-a504-3ede3ea9b3a9', 'c8629fda-f37c-4fad-b7cd-673583821f21',
        '400abf5b-1918-4f3f-bfb6-73c748de6101', NULL, 100.00, 'example.txt', '2021-09-06 11:50:10',
        '2021-09-06 11:50:10'),
       ('f472506e-48f6-4f55-818d-352277efec95', '20650174-ca47-4ff0-bb4e-17857ebd9bad',
        '58dab56a-92c6-4f30-86f3-068a8f6d8931', '11350c97-7d47-4f59-813c-53645e94fe03', 2000.00, NULL,
        '2021-09-06 11:50:10', '2021-09-06 11:50:10');

INSERT INTO user(id, name, email, password, avatar, token, active, reset_password_token, created_at, updated_at)
VALUES ('400abf5b-1918-4f3f-bfb6-73c748de6101', 'Peter', 'peter@api.com',
        '$argon2i$v=19$m=16,t=2,p=1$cGFzc3dvcmQ$A9HKT/FCm9ft8VCFgT4rVw', NULL,
        '81b7a88e8948c0cbf1244d4880f3e78ff94184aa', 1, '123456', '2021-09-06 11:50:10', '2021-09-06 11:50:10'),
       ('4a3a91dd-0b52-4f95-8da7-2a8d4a0c5c61', 'Roger', 'roger@api.com',
        '$argon2i$v=19$m=16,t=2,p=1$cGFzc3dvcmQ$A9HKT/FCm9ft8VCFgT4rVw', NULL,
        'fa66bd7ab5f97d3a9e1d6e308acf5a5e9e666cdc', 0, NULL, '2021-09-06 11:50:10', '2021-09-06 11:50:10'),
       ('58dab56a-92c6-4f30-86f3-068a8f6d8931', 'Brian', 'brian@api.com',
        '$argon2i$v=19$m=16,t=2,p=1$cGFzc3dvcmQ$A9HKT/FCm9ft8VCFgT4rVw', NULL,
        'd3cd898a0d74b6bb1999674a053222a7ba099ee5', 1, NULL, '2021-09-06 11:50:10', '2021-09-06 11:50:10');

INSERT INTO user_group(id, name, owner_id, created_at, updated_at)
VALUES ('11350c97-7d47-4f59-813c-53645e94fe03', 'Brian Group', '58dab56a-92c6-4f30-86f3-068a8f6d8931',
        '2021-09-06 11:50:10', '2021-09-06 11:50:10'),
       ('ad6fd564-832e-4f05-9e4e-8acb9e1840f4', 'Peter Group', '400abf5b-1918-4f3f-bfb6-73c748de6101',
        '2021-09-06 11:50:10', '2021-09-06 11:50:10');
INSERT INTO user_group_user(user_id, group_id)
VALUES ('58dab56a-92c6-4f30-86f3-068a8f6d8931', '11350c97-7d47-4f59-813c-53645e94fe03'),
       ('400abf5b-1918-4f3f-bfb6-73c748de6101', 'ad6fd564-832e-4f05-9e4e-8acb9e1840f4');