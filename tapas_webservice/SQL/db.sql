drop database if exists db_tapas_groupe3;
create database db_tapas_groupe3;
use db_tapas_groupe3;

create table tables(
    id int auto_increment,
    primary key(id)
);

create table categorie(
    id int auto_increment,
    nom varchar(64),
    primary key (id)
);

create table tapas(
    id int auto_increment,
    nom varchar(64) not null,
    description varchar(255),
    prix float not null,
    path_img text not null, 
    primary key(id)
);

create table categorie_tapas(
    categorie_id int not null,
    tapas_id int not null,
    primary key (tapas_id, categorie_id),
    foreign key (categorie_id) references categorie(id) ON DELETE CASCADE,
    foreign key (tapas_id) references tapas(id) ON DELETE CASCADE
);

create table commandes(
    id int auto_increment,
    table_id int not null,
    prix_total float not null,
    datecommande datetime default current_timestamp(),
    isconfirmee boolean default false,
    foreign key (table_id) references tables(id) on delete cascade,
    primary key (id)
);

create table quantite_tapas(
    tapas_id int not null,
    commande_id int not null,
    quantite int not null,
    primary key (tapas_id, commande_id),
    foreign key (commande_id) references commandes(id) on delete cascade,
    foreign key (tapas_id) references tapas(id) on delete cascade
);

create table historiquecommandes(
    id int auto_increment,
    commande_id int not null,
    date_commande datetime default current_timestamp(),
    statut varchar(64),
    primary key (id),
    foreign key (commande_id) references commandes(id) on delete cascade
);

insert into tables(id) values (1), (2), (3);
insert into categorie(nom) values ("Chaud"),("Froid"),("Vegetarien"),("A la viande");
insert into tapas(nom, description, prix, path_img) values ("chikaron", "chorizo, olive", 6, "null"), ("coquinas", "tomate, legumes", 5, "null"), ("lecurieux", "pomme italienne, truffe mefiantes", 5, "null");
insert into tapas(nom, description, prix, path_img) values ("chocolat", "chocolat noir, cerise furieuse", 12, "null");
insert into categorie_tapas(tapas_id, categorie_id) values (1,1),(1,3),(2,2),(2,4),(3,1),(3,3),(4,2),(4,3);
insert into commandes(table_id, isconfirmee, prix_total) value (1,false, 52);
insert into quantite_tapas(tapas_id, commande_id, quantite) values (1,1,2), (2,1,1);
insert into historiquecommandes(commande_id, statut) value (1, "ok");
