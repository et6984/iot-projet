/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de cr�ation :  10/05/2025 14:09:05                      */
/*==============================================================*/


drop table if exists CAPTEUR;

drop table if exists DEPARTEMENT;

drop table if exists SALLE;

drop table if exists TYPE_CAPTEUR;

drop table if exists TYPE_SALLE;

drop table if exists TYPE_USER;

drop table if exists USER;

/*==============================================================*/
/* Table : CAPTEUR                                              */
/*==============================================================*/
create table CAPTEUR
(
   ID_CAPTEUR           int not null auto_increment,
   ID_SALLE             int,
   TYPE_CAPTEUR         char(2),
   MESURE               float,
   DATE_HEURE           datetime,
   primary key (ID_CAPTEUR)
);

/*==============================================================*/
/* Table : DEPARTEMENT                                          */
/*==============================================================*/
create table DEPARTEMENT
(
   ID_NOM_DEPARTEMENT   char(2) not null,
   ID_DEPARTEMENT       int,
   NOM_DEPARTEMENT      varchar(50),
   primary key (ID_NOM_DEPARTEMENT)
);

/*==============================================================*/
/* Table : SALLE                                                */
/*==============================================================*/
create table SALLE
(
   ID_SALLE             int not null auto_increment,
   NOM_SALLE            varchar(50),
   TYPE_SALLE           char(2),
   ID_NOM_DEPARTEMENT       char(2),
   primary key (ID_SALLE)
);

/*==============================================================*/
/* Table : TYPE_CAPTEUR                                         */
/*==============================================================*/
create table TYPE_CAPTEUR
(
   TYPE_CAPTEUR         char(2) not null,
   LIBELLE_TYPE_CAPTEUR varchar(50),
   primary key (TYPE_CAPTEUR)
);

/*==============================================================*/
/* Table : TYPE_SALLE                                           */
/*==============================================================*/
create table TYPE_SALLE
(
   TYPE_SALLE           char(2) not null,
   LIBELLE_TYPE_SALLE   varchar(50),
   primary key (TYPE_SALLE)
);

/*==============================================================*/
/* Table : TYPE_USER                                            */
/*==============================================================*/
create table TYPE_USER
(
   TYPE_USER            char(2) not null,
   LIBELLE_TYPE_USER    varchar(50),
   primary key (TYPE_USER)
);

/*==============================================================*/
/* Table : USER                                                 */
/*==============================================================*/
create table USER
(
   ID_USER              int not null auto_increment,
   TYPE_USER            char(2),
   ID_NOM_DEPARTEMENT   char(2),
   LOGIN_USER           varchar(50),
   NOM_USER             varchar(50),
   PRENOM_USER          varchar(50),
   PASSWORD_USER      varchar(150),
   primary key (ID_USER)
);

alter table CAPTEUR add constraint FK_ASSOCIATION_1 foreign key (TYPE_CAPTEUR)
      references TYPE_CAPTEUR (TYPE_CAPTEUR) on delete restrict on update restrict;

alter table CAPTEUR add constraint FK_ASSOCIATION_2 foreign key (ID_SALLE)
      references SALLE (ID_SALLE) on delete restrict on update restrict;

alter table SALLE add constraint FK_ASSOCIATION_3 foreign key (TYPE_SALLE)
      references TYPE_SALLE (TYPE_SALLE) on delete restrict on update restrict;

alter table SALLE add constraint FK_ASSOCIATION_4 foreign key (ID_NOM_DEPARTEMENT)
      references DEPARTEMENT (ID_NOM_DEPARTEMENT) on delete restrict on update restrict;

alter table USER add constraint FK_ASSOCIATION_5 foreign key (ID_NOM_DEPARTEMENT)
      references DEPARTEMENT (ID_NOM_DEPARTEMENT) on delete restrict on update restrict;

alter table USER add constraint FK_ASSOCIATION_6 foreign key (TYPE_USER)
      references TYPE_USER (TYPE_USER) on delete restrict on update restrict;

INSERT INTO `type_user`(`TYPE_USER`, `LIBELLE_TYPE_USER`) 
VALUES ('A','administrateur'),
       ('S','superviseur');

INSERT INTO `type_salle`(`TYPE_SALLE`, `LIBELLE_TYPE_SALLE`) 
VALUES ('S','serveur'),
       ('L','laboratoire');

INSERT INTO `type_capteur`(`TYPE_CAPTEUR`, `LIBELLE_TYPE_CAPTEUR`) 
VALUES ('T','temperature'),
       ('H','humidité');

INSERT INTO `departement`(`ID_NOM_DEPARTEMENT`, `NOM_DEPARTEMENT`)
VALUES ('I','informatique'),
       ('S','science'),
       ('G','global');

INSERT INTO `salle`(`TYPE_SALLE`, `NOM_SALLE`, `ID_NOM_DEPARTEMENT`) 
VALUES ('S', 'serveur', 'I'),
       ('L', 'laboratoire', 'I'),
       ('S', 'serveur', 'S'),
       ('L', 'laboratoire', 'S');

INSERT INTO `user`(`TYPE_USER`, `ID_NOM_DEPARTEMENT`, `LOGIN_USER`, `NOM_USER`, `PRENOM_USER`, 'PASSWORD_USER')
VALUES ('A', 'G', 't.test', 'test','test', '$argon2i$v=19$m=65536,t=4,p=1$WHo1ckpLSkd6TWI5MG9ueg$zXPNsV0V7TlelYjk84YXSg24E92emyojobTJSQ8PwP0');