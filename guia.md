- [ ] Nombres de tablas en español (snake_case)
- [ ] Nombres de columnas en español (snake_case)
- [ ] Nombres de clases en español (PascalCase)
- [ ] Nombres de métodos en español (camelCase)
- [ ] Nombres de variables en español (camelCase)
- [ ] Constantes en español (UPPER_SNAKE_CASE)
- [ ] Value Objects siguen el patrón establecido
- [ ] DTOs tienen métodos `desdeArray()` y `aArray()`
- [ ] Servicios usan verbos en infinitivo
- [ ] Mensajes de error y excepciones en español

Estos son los servicios principales y sus contextos internos:

Administración: Usuarios, Roles, Permisos, Configuración, Integraciones, Auth
RRHH: Empleados, Profesionales, Turnos Laborales, Asistencia, Evaluaciones 
Pacientes: Pacientes, Familiares, Obras Sociales, Planes de Tratamiento, Documentación
Agenda: Calendario, Turnos, Recursos, Asignaciones, Notificaciones
Facturación: Facturas, Cobros, Planes, Caja, Configuración Fiscal
Informes: Clínicos, Asistenciales, Administrativos, Exportaciones
Estadísticas: Dashboard, Indicadores RRHH, Pacientes, Económicos
Configuración: Plantillas, Personalización, Backups, Seguridad
Comunicación: Mensajes Internos, Notificaciones, Soporte

librerias y packetes:
sanctum, tenancy

conveciones endpoints:
index
show
post
put
destroy
auth login
auth logout

comandos

php artisan migrate:refresh --> migra todo a 0

php artisan migrate:refresh --database=central --path=database/migrations/Central 
php artisan db:seed --class=CentralUserSeeder 