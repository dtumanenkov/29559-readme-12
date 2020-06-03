CREATE DATABASE readme
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email  VARCHAR(128) NOT NULL UNIQUE,
    login VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    avatar VARCHAR(256)
    );

CREATE TABLE content_types(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content_name VARCHAR (128) NOT NULL UNIQUE ,
    content_icon_name VARCHAR (128) NOT NULL UNIQUE
    );

CREATE TABLE posts(
    post_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date_of_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    header TEXT,
    content LONGTEXT,
    quote_author VARCHAR(128),
    image LONGTEXT,
    video LONGTEXT,
    link LONGTEXT,
    views INT UNSIGNED DEFAULT 0,
    author_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id),
    content_type_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (content_type_id) REFERENCES content_types(id)
    );

CREATE TABLE hashtags(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hashtag VARCHAR (128) UNIQUE
    );

CREATE TABLE posts_hashtags(
    post_id INT UNSIGNED NOT NULL,
    hashtag_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id,hashtag_id),
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (hashtag_id) REFERENCES hashtags(id)
    );

CREATE TABLE comments(
    comment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    comment TEXT NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    post_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(post_id)
    );

CREATE TABLE likes(
    user_id INT UNSIGNED NOT NULL,
    post_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id,post_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (post_id) REFERENCES posts(post_id)
    );

CREATE TABLE subscriptions(
    user_id INT UNSIGNED NOT NULL,
    subscription_user_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id,subscription_user_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (subscription_user_id) REFERENCES users(id)
    );

CREATE TABLE messages(
    message_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    message_date_of_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    message TEXT NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id),
    recipient_id INT UNSIGNED NOT NULL,
    FOREIGN KEY(recipient_id) REFERENCES users(id)
    );


