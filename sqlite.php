<?php
$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$connection->exec("CREATE TABLE IF NOT EXISTS users (
    uuid TEXT NOT NULL CONSTRAINT uuid_primary_key PRIMARY KEY,
    username TEXT NOT NUll CONSTRAINT username_unique_key UNIQUE,
    password TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL
)");

$connection->exec("CREATE TABLE IF NOT EXISTS comments (
    uuid TEXT NOT NULL CONSTRAINT uuid_primary_key PRIMARY KEY,
    authoruuid TEXT NOT NUll,
    postuuid TEXT NOT NUll,
    comment TEXT NOT NULL
)");

$connection->exec("CREATE TABLE IF NOT EXISTS posts (
    uuid TEXT NOT NULL CONSTRAINT uuid_primary_key PRIMARY KEY,
    authoruuid TEXT NOT NUll,
    title TEXT NOT NUll,
    posttext TEXT NOT NULL
)");

$connection->exec("CREATE TABLE IF NOT EXISTS likes (
    uuid TEXT NOT NULL CONSTRAINT uuid_primary_key PRIMARY KEY,
    useruuid TEXT NOT NUll,
    postuuid TEXT NOT NUll
)");

$connection->exec("CREATE TABLE IF NOT EXISTS tokens (
    token TEXT NOT NULL CONSTRAINT token_primary_key PRIMARY KEY,
    user_uuid TEXT NOT NULL,
    expires_on TEXT NOT NULL
)");
