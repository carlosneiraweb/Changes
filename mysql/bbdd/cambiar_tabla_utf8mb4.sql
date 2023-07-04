 show variables like '%colla%';
 show variables like '%charac%';
 DESCRIBE post;
 SHOW FULL COLUMNS FROM direccion;

show variables like "%innodb_file%";
ALTER DATABASE portal CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_520_ci;

SELECT CONCAT('ALTER TABLE ',TABLE_SCHEMA,'.',TABLE_NAME,
' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  ')
AS alter_sql
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'portal';


ALTER TABLE portal.Errores CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.busquedas_pbs_buscadas CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.busquedas_pbs_ofrecidas CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.comentario_post CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.datos_usuario CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.direccion CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.genero CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.imagenes CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.palabras_email CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.post CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.post_denunciados CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.provincias CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.secciones CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.subsecciones CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.tiempo_cambio CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.usariosmensajespornoactivos CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.usuario CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.usuarios_bloqueados CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;  
ALTER TABLE portal.usuarios_expulsados CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
