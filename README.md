# ProyectoFinalAIEdgarHector
Proyecto final de la materia aplicaciones de internet impartida por el profesor Marco

Este proyecto consiste en una página para clases en línea, al menos un prototipo de esta.
Usamos php, css y html, todo lo que vimos durante el curso.
El proyecto funciona con una base de datos local, para la cual usamos MAMP.

IMPORTANTE revisar el puerto al cual se está conectando

Estructura de la base de datos:

CREATE TABLE alumnos (
  alumno_id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(255),
  password VARCHAR(255),
  nombre VARCHAR(255),
  tipo VARCHAR(255) (distingue entre alumno o profesor)
);

CREATE TABLE clases (
  clase_id INT AUTO_INCREMENT PRIMARY KEY,
  profesor_id INT,
  nombre VARCHAR(255),
  descripcion VARCHAR(255),
  FOREIGN KEY (profesor_id) REFERENCES alumnos(alumno_id)
);

CREATE TABLE tareas (
  tarea_id INT AUTO_INCREMENT PRIMARY KEY,
  clase_id INT,
  titulo VARCHAR(255),
  descripcion VARCHAR(255),
  fecha_vencimiento DATE,
  calificacion FLOAT,
  FOREIGN KEY (clase_id) REFERENCES clases(clase_id)
);

CREATE TABLE alumnos_clases (
  alumno_clase_id INT AUTO_INCREMENT PRIMARY KEY,
  alumno_id INT,
  clase_id INT,
  FOREIGN KEY (alumno_id) REFERENCES alumnos(alumno_id),
  FOREIGN KEY (clase_id) REFERENCES clases(clase_id)
);

Hecho por:
Meza Rios Héctor Argel Genezareth.
Rodríguez Vázquez José Edgar.