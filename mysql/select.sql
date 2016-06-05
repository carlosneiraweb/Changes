
select u.nick as nick, prov.nombre as provincia, p.fechaPost as fecha, p.titulo as titulo, img.ruta as ruta, p.titulo as titulo, p.comentario as comentario 
from usuario AS u, post AS p, imagenes AS img, provincias AS prov, direccion as dir 
where p.idUsuario = u.idUsuario and p.idPost = 46 and img.post_idPost = 46
and dir.provincias_idprovincias = prov.idprovincias limit 1


