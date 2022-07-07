SET FOREIGN_KEY_CHECKS=0;
truncate post;
truncate palabras_email;
truncate imagenes;
SET FOREIGN_KEY_CHECKS=1;


CREATE FULLTEXT INDEX palabras_decta ON palabras_email(palabras_detectar);
