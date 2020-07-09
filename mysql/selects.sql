select * from post;
delete from post where idPost >= 0;


select * from imagenes;
delete from imagenes where post_idPost > 1;

select * from busquedas_pbs_buscadas;
select * from busquedas_pbs_ofrecidas;
delete from busquedas_pbs_buscadas where idPbsBuscada >= 0;
delete from busquedas_pbs_ofrecidas where idPbsOfrecida >= 0;

select * from datos_usuario;
delete from usuario where idDatosUsuario >=0;
select * from direccion;
delete from direccion where idDireccion >= 0;