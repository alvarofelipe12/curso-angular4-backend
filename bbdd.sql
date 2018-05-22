CREATE DATABASE IF NOT EXISTS curso_angular4;
USE curso_angular4;

CREATE TABLE productos(
id int(255) auto_increment NOT NULL,
nombre VARCHAR(255),
descripcion text,
precio VARCHAR(255),
imagen VARCHAR(255),
CONSTRAINT pk_productos PRIMARY KEY(id) 
) ENGINE=InnoDB;