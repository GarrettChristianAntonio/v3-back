Respetando el patron de arquitectura 

necesito tener usuarios + sactum en tenants y en central
utiliza migraciones distintas para cada uno.
Respeta hexagonal y ddd arquitecture cuentas con la carpeta usuarios y auth y shared por si la necesitas

usuario de central
users:
nameuser:
password,
date_at
softdelete

+ sanctum con jwt
crear y ejecutar un seeder para crear un usuario con = nameuser:"admin" password:"admin"

endpoints
user index
user show
user post
user put
user destroy
auth login
auth logout


usuario de tenant
users:
name,
lastname,
nameuser,
mail,
password,
at
softdelete

+ sanctum con jwt
crear y ejecutar un seeder para crear un usuario con nameuser:"admin+nombredeltenant" password:"admin+nombredeltenant"

endpoints
index
show
post
put
destroy
auth login
auth logout







