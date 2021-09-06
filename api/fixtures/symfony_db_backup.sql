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