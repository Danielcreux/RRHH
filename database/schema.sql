-- database/02_schema.sql
USE rrhh;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS documents;
DROP TABLE IF EXISTS leave_requests;
DROP TABLE IF EXISTS payroll_items;
DROP TABLE IF EXISTS payroll;
DROP TABLE IF EXISTS attendance;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS employees;
DROP TABLE IF EXISTS positions;
DROP TABLE IF EXISTS departments;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE departments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255) NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_departments_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE positions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id INT UNSIGNED NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion VARCHAR(255) NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_positions_dept_nombre (department_id, nombre),
  KEY idx_positions_dept (department_id),
  CONSTRAINT fk_positions_department
    FOREIGN KEY (department_id) REFERENCES departments(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE employees (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  department_id INT UNSIGNED NOT NULL,
  position_id INT UNSIGNED NOT NULL,

  nombre VARCHAR(80) NOT NULL,
  apellidos VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  telefono VARCHAR(30) NULL,
  direccion VARCHAR(255) NULL,
  fecha_nacimiento DATE NULL,

  fecha_ingreso DATE NOT NULL,
  salario_base DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  estado ENUM('activo','baja','suspendido') NOT NULL DEFAULT 'activo',

  contacto_emergencia_nombre VARCHAR(160) NULL,
  contacto_emergencia_telefono VARCHAR(30) NULL,

  foto_path VARCHAR(255) NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_employees_email (email),
  KEY idx_employees_dept (department_id),
  KEY idx_employees_position (position_id),
  KEY idx_employees_estado (estado),

  CONSTRAINT fk_employees_department
    FOREIGN KEY (department_id) REFERENCES departments(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,

  CONSTRAINT fk_employees_position
    FOREIGN KEY (position_id) REFERENCES positions(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NULL,

  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,

  rol ENUM('super_admin','rrhh','empleado') NOT NULL DEFAULT 'empleado',
  activo TINYINT(1) NOT NULL DEFAULT 1,

  last_login_at DATETIME NULL,
  reset_token_hash VARCHAR(255) NULL,
  reset_token_expires_at DATETIME NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_users_email (email),
  KEY idx_users_employee (employee_id),
  KEY idx_users_rol (rol),

  CONSTRAINT fk_users_employee
    FOREIGN KEY (employee_id) REFERENCES employees(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE attendance (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NOT NULL,

  fecha DATE NOT NULL,
  hora_entrada DATETIME NULL,
  hora_salida DATETIME NULL,

  minutos_trabajados INT UNSIGNED NOT NULL DEFAULT 0,
  estado ENUM('a_tiempo','tardanza','ausente','justificado') NOT NULL DEFAULT 'a_tiempo',

  origen_ip VARCHAR(45) NULL,
  ubicacion_lat DECIMAL(10,7) NULL,
  ubicacion_lng DECIMAL(10,7) NULL,

  editado_manual TINYINT(1) NOT NULL DEFAULT 0,
  editado_por_user_id INT UNSIGNED NULL,
  motivo_edicion VARCHAR(255) NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_attendance_employee_fecha (employee_id, fecha),
  KEY idx_attendance_fecha (fecha),
  KEY idx_attendance_estado (estado),
  KEY idx_attendance_editor (editado_por_user_id),

  CONSTRAINT fk_attendance_employee
    FOREIGN KEY (employee_id) REFERENCES employees(id)
    ON UPDATE CASCADE ON DELETE CASCADE,

  CONSTRAINT fk_attendance_editor
    FOREIGN KEY (editado_por_user_id) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE payroll (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NOT NULL,

  periodo_mes TINYINT UNSIGNED NOT NULL,
  periodo_anio SMALLINT UNSIGNED NOT NULL,

  salario_base DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  bonos DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  deducciones DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(10,2) NOT NULL DEFAULT 0.00,

  estado ENUM('draft','approved','paid') NOT NULL DEFAULT 'draft',

  generado_por_user_id INT UNSIGNED NOT NULL,
  aprobado_por_user_id INT UNSIGNED NULL,
  aprobado_en DATETIME NULL,
  pagado_en DATETIME NULL,

  pdf_path VARCHAR(255) NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_payroll_employee_periodo (employee_id, periodo_mes, periodo_anio),
  KEY idx_payroll_periodo (periodo_anio, periodo_mes),
  KEY idx_payroll_estado (estado),
  KEY idx_payroll_generador (generado_por_user_id),

  CONSTRAINT fk_payroll_employee
    FOREIGN KEY (employee_id) REFERENCES employees(id)
    ON UPDATE CASCADE ON DELETE CASCADE,

  CONSTRAINT fk_payroll_generado_por
    FOREIGN KEY (generado_por_user_id) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,

  CONSTRAINT fk_payroll_aprobado_por
    FOREIGN KEY (aprobado_por_user_id) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE payroll_items (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  payroll_id BIGINT UNSIGNED NOT NULL,
  tipo ENUM('bono','deduccion','beneficio','otro') NOT NULL DEFAULT 'otro',
  concepto VARCHAR(120) NOT NULL,
  monto DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_payroll_items_payroll (payroll_id),
  CONSTRAINT fk_payroll_items_payroll
    FOREIGN KEY (payroll_id) REFERENCES payroll(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE leave_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NOT NULL,
  tipo ENUM('vacaciones','permiso','dia_personal') NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  motivo VARCHAR(255) NULL,

  estado ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  aprobado_por_user_id INT UNSIGNED NULL,
  comentario_aprobacion VARCHAR(255) NULL,
  resuelto_en DATETIME NULL,

  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,

  KEY idx_leave_employee (employee_id),
  KEY idx_leave_estado (estado),
  KEY idx_leave_fechas (fecha_inicio, fecha_fin),

  CONSTRAINT fk_leave_employee
    FOREIGN KEY (employee_id) REFERENCES employees(id)
    ON UPDATE CASCADE ON DELETE CASCADE,

  CONSTRAINT fk_leave_aprobador
    FOREIGN KEY (aprobado_por_user_id) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE documents (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NOT NULL,
  nombre VARCHAR(180) NOT NULL,
  tipo VARCHAR(60) NULL,
  path VARCHAR(255) NOT NULL,
  mime VARCHAR(120) NULL,
  tamano_bytes BIGINT UNSIGNED NOT NULL DEFAULT 0,
  subido_por_user_id INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  KEY idx_documents_employee (employee_id),
  KEY idx_documents_uploader (subido_por_user_id),

  CONSTRAINT fk_documents_employee
    FOREIGN KEY (employee_id) REFERENCES employees(id)
    ON UPDATE CASCADE ON DELETE CASCADE,

  CONSTRAINT fk_documents_uploader
    FOREIGN KEY (subido_por_user_id) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE activity_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  accion VARCHAR(80) NOT NULL,
  entidad VARCHAR(80) NULL,
  entidad_id BIGINT UNSIGNED NULL,
  detalle VARCHAR(255) NULL,
  ip VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  KEY idx_logs_user (user_id),
  KEY idx_logs_accion (accion),
  KEY idx_logs_created (created_at),

  CONSTRAINT fk_logs_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS talentia_candidatos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  nombre VARCHAR(150) NOT NULL,
  email VARCHAR(190) NULL,
  telefono VARCHAR(60) NULL,
  edad TINYINT UNSIGNED NULL,
  experiencia_anios TINYINT UNSIGNED NULL,

  skills TEXT NULL,
  idiomas TEXT NULL,
  nivel_ingles VARCHAR(10) NULL,
  puesto_actual VARCHAR(150) NULL,

  archivo_pdf VARCHAR(255) NULL,
  cv_text MEDIUMTEXT NULL,

  estado ENUM('nuevo','en_revision','preseleccionado','contratado','descartado') NOT NULL DEFAULT 'nuevo',

  -- si finalmente se crea empleado, lo enlazas aquí
  empleado_id INT UNSIGNED NULL,

  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  creado_por_user_id INT UNSIGNED NULL,

  KEY idx_talentia_nombre (nombre),
  KEY idx_talentia_email (email),
  KEY idx_talentia_estado (estado),
  KEY idx_talentia_creado_por (creado_por_user_id),
  KEY idx_talentia_empleado (empleado_id),

  CONSTRAINT fk_talentia_creado_por
    FOREIGN KEY (creado_por_user_id) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL,

  CONSTRAINT fk_talentia_empleado
    FOREIGN KEY (empleado_id) REFERENCES employees(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
