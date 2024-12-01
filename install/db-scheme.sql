CREATE TABLE IF NOT EXISTS director
(
    ID int not null auto_increment,
    NAME varchar(500) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE movie
(
    ID int not null auto_increment,
    TITLE varchar(500) not null,
    ORIGINAL_TITLE varchar(500) not null,
    DESCRIPTION text not null,
    DURATION int not null,
    AGE_RESTRICTION int not null,
    RELEASE_DATE YEAR,
    RATING float,
    DIRECTOR_ID int,

    PRIMARY KEY (ID),
    FOREIGN KEY FK_MOVIE_DIRECTOR (DIRECTOR_ID)
        REFERENCES director(ID)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
);

CREATE TABLE actor
(
    ID int not null auto_increment,
    NAME varchar(500) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE movie_actor
(
    MOVIE_ID int not null,
    ACTOR_ID int not null,
    PRIMARY KEY (MOVIE_ID, ACTOR_ID),
    FOREIGN KEY FK_MA_MOVIE (MOVIE_ID)
        REFERENCES movie(ID)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    FOREIGN KEY FK_MA_ACTOR (ACTOR_ID)
        REFERENCES actor(ID)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
);

CREATE TABLE genre
(
    ID int not null auto_increment,
    CODE varchar(500) not null,
    NAME varchar(500) not null,
    PRIMARY KEY (ID)
);

CREATE TABLE movie_genre
(
    MOVIE_ID int not null,
    GENRE_ID int not null,
    PRIMARY KEY (MOVIE_ID, GENRE_ID),
    FOREIGN KEY FK_MG_MOVIE (MOVIE_ID)
        REFERENCES movie(ID)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    FOREIGN KEY FK_MG_GENRE (GENRE_ID)
        REFERENCES genre(ID)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
);
