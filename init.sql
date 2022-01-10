CREATE TABLE users (
    id int(11) not null primary key auto_increment,
    fullname varchar(256) not null,
    email varchar(256) not null,
    username varchar(256) not null,
    password varchar(256) not null
);

CREATE TABLE profiles (
    id int(11) not null primary key auto_increment,
    u_id int(11) not null,
    bio varchar(256),
    profilePic varchar(256),
    foreign key (u_id) references users(id)
);