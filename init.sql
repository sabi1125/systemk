CREATE TABLE users (
    id int(11) not null primary key auto_increment,
    fullname varchar(256) not null,
    email varchar(256) not null,
    username varchar(256) not null,
    password varchar(256) not null,
);

CREATE TABLE profiles (
    id int(11) not null primary key auto_increment,
    u_id int(11) not null,
    bio varchar(256),
    profilePic varchar(256),
    foreign key (u_id) references users(id)
);

CREATE TABLE posts (
    id int(11) not null primary key auto_increment,
    u_id int(11) not null,
    post varchar(256) not null,
    image varchar(256),
    foreign key (u_id) REFERENCES users(id)
);

CREATE TABLE followers (
    id int(11) not null primary key auto_increment,
    follower int not null,
    following int not null
)

ALTER TABLE users ADD birthday DATE NOT NULL;