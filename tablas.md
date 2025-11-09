-- =========================================
-- Tabla: cfg_role_field
-- Reglas por ROL para cada CAMPO de un recurso (visibilidad, edición, obligatoriedad)
-- =========================================
CREATE TABLE cfg_role_field (
  id BIGSERIAL PRIMARY KEY,
  tenant_id BIGINT NOT NULL,        --solo agrega el comentario aqui...
  role_id BIGINT NOT NULL,
  resource TEXT NOT NULL,            -- p.ej. 'rrhh.profesional' o 'clinica.paciente'
  field_key TEXT NOT NULL,           -- clave del campo (p.ej. 'dni', 'cuit', 'celular')
  visible BOOLEAN DEFAULT TRUE,      -- si el campo se muestra para este rol
  editable BOOLEAN DEFAULT TRUE,     -- si el campo es editable para este rol
  required_on_create BOOLEAN DEFAULT FALSE, -- requerido al crear
  required_on_update BOOLEAN DEFAULT FALSE, -- requerido al actualizar
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE (tenant_id, role_id, resource, field_key)
);

COMMENT ON TABLE cfg_role_field IS 'Reglas por rol/tenant que determinan visibilidad, edición y obligatoriedad de campos por recurso.';
COMMENT ON COLUMN cfg_role_field.id IS 'Identificador interno.';
COMMENT ON COLUMN cfg_role_field.tenant_id IS 'ID del tenant propietario de la configuración.';
COMMENT ON COLUMN cfg_role_field.role_id IS 'ID del rol dentro del tenant al que aplican estas reglas.';
COMMENT ON COLUMN cfg_role_field.resource IS 'Nombre lógico del recurso (p.ej. rrhh.profesional).';
COMMENT ON COLUMN cfg_role_field.field_key IS 'Clave del campo configurado dentro del recurso.';
COMMENT ON COLUMN cfg_role_field.visible IS 'Indica si el campo es visible para este rol.';
COMMENT ON COLUMN cfg_role_field.editable IS 'Indica si el campo es editable para este rol.';
COMMENT ON COLUMN cfg_role_field.required_on_create IS 'Si el campo es obligatorio al crear registros.';
COMMENT ON COLUMN cfg_role_field.required_on_update IS 'Si el campo es obligatorio al actualizar registros.';
COMMENT ON COLUMN cfg_role_field.created_at IS 'Fecha/hora de creación del registro de configuración.';
COMMENT ON COLUMN cfg_role_field.updated_at IS 'Fecha/hora de última actualización del registro de configuración.';
COMMENT ON CONSTRAINT cfg_role_field_tenant_id_role_id_resource_field_key_key ON cfg_role_field
  IS 'Evita duplicados: una sola regla por (tenant, rol, recurso, campo).';

-- =========================================
-- Tabla: permissions
-- Catálogo de permisos (acciones y calificadores) para autorización
-- =========================================
CREATE TABLE permissions (
  id BIGSERIAL PRIMARY KEY,
  service TEXT NOT NULL,                 -- módulo/servicio (p.ej. 'facturacion', 'clinica')
  resource TEXT NOT NULL,                -- recurso dentro del servicio (p.ej. 'factura', 'historia')
  action TEXT NOT NULL,                  -- acción (p.ej. 'ver', 'crear', 'editar', 'eliminar', 'emitir')
  qualifier TEXT NULL,                   -- calificador opcional (p.ej. 'montos', 'propios')
  qualifier_type TEXT NULL CHECK (qualifier_type IN ('campo','alcance')),   -- tipo del calificador: 'campo' (field-level) o 'alcance' (scope de datos)
  title TEXT NOT NULL,                   -- título legible para la UI
  description TEXT NULL,                 -- descripción para la UI
  enabled BOOLEAN DEFAULT TRUE,          -- permite deshabilitar permisos del catálogo sin borrarlos
  created_at TIMESTAMPTZ DEFAULT now(),
  updated_at TIMESTAMPTZ DEFAULT now(),
  UNIQUE (service, resource, action, qualifier) -- evita duplicar el mismo permiso lógico
);

COMMENT ON TABLE permissions IS 'Catálogo de permisos por servicio/recurso/acción, con calificador opcional.';
COMMENT ON COLUMN permissions.id IS 'Identificador interno del permiso.';
COMMENT ON COLUMN permissions.service IS 'Servicio o módulo al que pertenece el permiso (p.ej. facturacion).';
COMMENT ON COLUMN permissions.resource IS 'Recurso dentro del servicio (p.ej. factura).';
COMMENT ON COLUMN permissions.action IS 'Acción autorizable (p.ej. ver, crear, editar, eliminar, emitir).';
COMMENT ON COLUMN permissions.qualifier IS 'Calificador opcional para granularidad extra (p.ej. montos, propios).';
COMMENT ON COLUMN permissions.qualifier_type IS 'Tipo de calificador: campo (visibilidad de campos) o alcance (scope de datos).';
COMMENT ON COLUMN permissions.title IS 'Nombre legible para mostrar en el editor de roles.';
COMMENT ON COLUMN permissions.description IS 'Descripción/ayuda del permiso para la UI.';
COMMENT ON COLUMN permissions.enabled IS 'Bandera para activar/desactivar el permiso en el catálogo.';
COMMENT ON COLUMN permissions.created_at IS 'Fecha/hora de creación del permiso.';
COMMENT ON COLUMN permissions.updated_at IS 'Fecha/hora de última actualización del permiso.';
COMMENT ON CONSTRAINT permissions_service_resource_action_qualifier_key ON permissions
  IS 'Unicidad por (service, resource, action, qualifier).';
