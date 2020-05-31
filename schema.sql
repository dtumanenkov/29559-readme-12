CREATE DATABASE readme DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE readme;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY, user_date_of_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
user_email  VARCHAR(128) NOT NULL UNIQUE, user_login VARCHAR(128) NOT NULL UNIQUE, user_password CHAR(64) NOT NULL,
user_avatar TEXT);
CREATE TABLE posts(id INT AUTO_INCREMENT PRIMARY KEY,post_date_of_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
post_header TEXT, post_content LONGTEXT, post_author VARCHAR(128), post_image LONGTEXT, post_video LONGTEXT,
post_link LONGTEXT, post_views INT, user_id INT,content_type_id INT);
CREATE TABLE posts_hashtags(post_id INT,hashtag_id INT);
CREATE TABLE comments(id INT AUTO_INCREMENT PRIMARY KEY, comment_date_of_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
comment TEXT, user_id INT, post_id INT);
CREATE TABLE likes(id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, post_id INT);
CREATE TABLE subscriptions(id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, subscription_user_id INT);
CREATE TABLE messages(id INT AUTO_INCREMENT PRIMARY KEY, message_date_of_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
message TEXT, user_id INT, address_user_id INT);
CREATE TABLE hashtags(id INT AUTO_INCREMENT PRIMARY KEY, hashtag TEXT);
CREATE TABLE content_types(id SMALLINT AUTO_INCREMENT PRIMARY KEY, content_name TINYTEXT, content_icon_name TINYTEXT);
CREATE TABLE roles(id SMALLINT AUTO_INCREMENT PRIMARY KEY, roles TINYTEXT);

