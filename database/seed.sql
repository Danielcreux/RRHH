-- database/03_seed_base.sql
USE rrhh;
SET NAMES utf8mb4;

INSERT INTO departments (nombre, descripcion) VALUES
('Recursos Humanos','Gestión de personas y cultura'),
('IT','Tecnología y soporte'),
('Finanzas','Contabilidad y tesorería'),
('Operaciones','Operativa diaria'),
('Ventas','Comercial y cuentas');

INSERT INTO positions (department_id, nombre) VALUES
(1,'Técnico RRHH'),
(1,'Responsable RRHH'),
(2,'Desarrollador PHP'),
(2,'Soporte IT'),
(3,'Contable'),
(4,'Operario'),
(5,'Ejecutivo de ventas');

INSERT INTO employees
(department_id, position_id, nombre, apellidos, email, telefono, direccion, fecha_nacimiento, fecha_ingreso, salario_base, estado,
 contacto_emergencia_nombre, contacto_emergencia_telefono, foto_path)
VALUES
(1,2,'Daniel','Creux','daniel.creux@empresa.com','600100100','C/ Empresa 1','1990-01-10','2020-01-01',4200,'activo','Contacto Daniel','699000001',NULL),
(1,1,'Lucía','García Pérez','lucia.garcia@empresa.com','600111222','C/ Mayor 1','1992-04-12','2023-01-10',2200,'activo','Ana García','600999888',NULL),
(2,3,'Carlos','Martín Ruiz','carlos.martin@empresa.com','600333444','Av. Sol 22','1990-09-30','2022-06-01',3000,'activo','María Ruiz','600777666',NULL),
(3,5,'Elena','Santos López','elena.santos@empresa.com','600555666','C/ Luna 8','1988-02-20','2021-03-15',2800,'activo','Juan Santos','600123123',NULL),
(4,6,'Diego','Navarro Gil','diego.navarro@empresa.com','600777888','C/ Río 14','1995-11-05','2024-02-01',1900,'activo','Laura Gil','600321321',NULL),
(5,7,'Marta','Vega Torres','marta.vega@empresa.com','600888999','Plaza Centro 3','1993-07-18','2020-09-01',2400,'activo','Pedro Vega','600222111',NULL),
(2,4,'Sergio','Ibáñez Mora','sergio.ibanez@empresa.com','611000111','C/ Norte 9','1991-01-25','2023-09-11',2100,'activo','Clara Mora','611999888',NULL),
(4,6,'Paula','Ortega Díaz','paula.ortega@empresa.com','622000111','C/ Sur 2','1987-05-09','2019-04-01',3500,'activo','Luis Ortega','622999888',NULL),
(5,7,'Iván','Rojas Cano','ivan.rojas@empresa.com','633000111','Av. Mar 7','1996-08-14','2024-07-01',1850,'activo','Sara Cano','633999888',NULL),
(3,5,'Nuria','Campos Silva','nuria.campos@empresa.com','644000111','C/ Arte 6','1994-12-02','2022-11-21',2300,'activo','Hugo Silva','644999888',NULL);

-- Solicitudes ejemplo (sin aprobador todavía: luego seed.php las resuelve)
INSERT INTO leave_requests (employee_id, tipo, fecha_inicio, fecha_fin, motivo, estado)
VALUES
(2,'vacaciones','2026-02-10','2026-02-14','Viaje familiar','approved'),
(3,'permiso','2026-02-17','2026-02-17','Cita médica','pending'),
(5,'dia_personal','2026-02-05','2026-02-05','Asuntos personales','rejected');

-- Nóminas ejemplo (sin generado_por todavía: luego seed.php actualiza con el admin)
INSERT INTO payroll (employee_id, periodo_mes, periodo_anio, salario_base, bonos, deducciones, total, estado, generado_por_user_id)
VALUES
(2,1,2026,2200,150,80,2270,'approved', 1),
(3,1,2026,3000,200,120,3080,'paid', 1),
(4,1,2026,2800,0,100,2700,'draft', 1);

-- Fichajes (ejemplo)
INSERT INTO attendance (employee_id, fecha, hora_entrada, hora_salida, minutos_trabajados, estado, origen_ip)
VALUES
(2,'2026-02-03','2026-02-03 09:01:00','2026-02-03 17:05:00',484,'tardanza','127.0.0.1'),
(2,'2026-02-04','2026-02-04 09:00:00','2026-02-04 17:00:00',480,'a_tiempo','127.0.0.1'),
(3,'2026-02-03','2026-02-03 08:59:00','2026-02-03 17:02:00',483,'a_tiempo','127.0.0.1');

-- Asistencias para empleados 2-10 durante febrero 2026
INSERT INTO attendance (employee_id, fecha, hora_entrada, hora_salida, minutos_trabajados, estado, origen_ip) VALUES
-- Empleado 2 (Lucía García)
(2, '2026-02-01', '2026-02-01 09:00:00', '2026-02-01 17:00:00', 480, 'a_tiempo', '192.168.1.10'),
(2, '2026-02-02', '2026-02-02 08:55:00', '2026-02-02 17:05:00', 490, 'a_tiempo', '192.168.1.10'),
(2, '2026-02-03', '2026-02-03 09:10:00', '2026-02-03 17:00:00', 470, 'tardanza', '192.168.1.10'),
(2, '2026-02-04', '2026-02-04 09:00:00', '2026-02-04 17:00:00', 480, 'a_tiempo', '192.168.1.10'),
(2, '2026-02-05', '2026-02-05 09:00:00', '2026-02-05 17:00:00', 480, 'a_tiempo', '192.168.1.10'),
(2, '2026-02-06', '2026-02-06 09:00:00', '2026-02-06 17:00:00', 480, 'a_tiempo', '192.168.1.10'),
(2, '2026-02-07', NULL, NULL, 0, 'ausente', NULL),
(2, '2026-02-08', '2026-02-08 09:00:00', '2026-02-08 17:00:00', 480, 'a_tiempo', '192.168.1.10'),
-- Empleado 3 (Carlos Martín)
(3, '2026-02-01', '2026-02-01 08:59:00', '2026-02-01 17:02:00', 483, 'a_tiempo', '192.168.1.11'),
(3, '2026-02-02', '2026-02-02 09:00:00', '2026-02-02 17:00:00', 480, 'a_tiempo', '192.168.1.11'),
(3, '2026-02-03', '2026-02-03 09:15:00', '2026-02-03 17:00:00', 465, 'tardanza', '192.168.1.11'),
(3, '2026-02-04', '2026-02-04 09:00:00', '2026-02-04 17:00:00', 480, 'a_tiempo', '192.168.1.11'),
(3, '2026-02-05', '2026-02-05 09:00:00', '2026-02-05 17:00:00', 480, 'a_tiempo', '192.168.1.11'),
(3, '2026-02-06', '2026-02-06 09:00:00', '2026-02-06 17:00:00', 480, 'a_tiempo', '192.168.1.11'),
(3, '2026-02-07', NULL, NULL, 0, 'justificado', NULL),
-- Empleado 4 (Elena Santos)
(4, '2026-02-01', '2026-02-01 09:00:00', '2026-02-01 17:00:00', 480, 'a_tiempo', '192.168.1.12'),
(4, '2026-02-02', '2026-02-02 09:00:00', '2026-02-02 17:00:00', 480, 'a_tiempo', '192.168.1.12'),
(4, '2026-02-03', '2026-02-03 08:50:00', '2026-02-03 17:10:00', 500, 'a_tiempo', '192.168.1.12'),
(4, '2026-02-04', '2026-02-04 09:05:00', '2026-02-04 17:00:00', 475, 'tardanza', '192.168.1.12'),
(4, '2026-02-05', '2026-02-05 09:00:00', '2026-02-05 17:00:00', 480, 'a_tiempo', '192.168.1.12'),
-- Empleado 5 (Diego Navarro)
(5, '2026-02-01', '2026-02-01 09:00:00', '2026-02-01 17:00:00', 480, 'a_tiempo', '192.168.1.13'),
(5, '2026-02-02', '2026-02-02 09:00:00', '2026-02-02 17:00:00', 480, 'a_tiempo', '192.168.1.13'),
(5, '2026-02-03', '2026-02-03 09:00:00', '2026-02-03 17:00:00', 480, 'a_tiempo', '192.168.1.13'),
(5, '2026-02-04', '2026-02-04 09:00:00', '2026-02-04 17:00:00', 480, 'a_tiempo', '192.168.1.13'),
(5, '2026-02-05', '2026-02-05 09:00:00', '2026-02-05 17:00:00', 480, 'a_tiempo', '192.168.1.13'),
-- Empleado 6 (Marta Vega)
(6, '2026-02-01', '2026-02-01 09:00:00', '2026-02-01 17:00:00', 480, 'a_tiempo', '192.168.1.14'),
(6, '2026-02-02', '2026-02-02 09:00:00', '2026-02-02 17:00:00', 480, 'a_tiempo', '192.168.1.14'),
(6, '2026-02-03', '2026-02-03 09:20:00', '2026-02-03 17:00:00', 460, 'tardanza', '192.168.1.14'),
(6, '2026-02-04', '2026-02-04 09:00:00', '2026-02-04 17:00:00', 480, 'a_tiempo', '192.168.1.14'),
-- Empleado 7 (Sergio Ibáñez)
(7, '2026-02-01', '2026-02-01 09:00:00', '2026-02-01 17:00:00', 480, 'a_tiempo', '192.168.1.15'),
(7, '2026-02-02', '2026-02-02 09:00:00', '2026-02-02 17:00:00', 480, 'a_tiempo', '192.168.1.15'),
(7, '2026-02-03', '2026-02-03 09:00:00', '2026-02-03 17:00:00', 480, 'a_tiempo', '192.168.1.15'),
-- Empleado 8 (Paula Ortega)
(8, '2026-02-01', '2026-02-01 09:00:00', '2026-02-01 17:00:00', 480, 'a_tiempo', '192.168.1.16'),
(8, '2026-02-02', '2026-02-02 09:00:00', '2026-02-02 17:00:00', 480, 'a_tiempo', '192.168.1.16'),
-- Empleado 9 (Iván Rojas)
(9, '2026-02-01', '2026-02-01 09:00:00', '2026-02-01 17:00:00', 480, 'a_tiempo', '192.168.1.17'),
-- Empleado 10 (Nuria Campos)
(10, '2026-02-01', '2026-02-01 09:00:00', '2026-02-01 17:00:00', 480, 'a_tiempo', '192.168.1.18');

-- Nóminas para empleados 2-10 en enero y febrero 2026
INSERT INTO payroll (employee_id, periodo_mes, periodo_anio, salario_base, bonos, deducciones, total, estado, generado_por_user_id) VALUES
-- Enero 2026 (completar)
(2, 1, 2026, 2200, 150, 80, 2270, 'approved', 1),
(3, 1, 2026, 3000, 200, 120, 3080, 'paid', 1),
(4, 1, 2026, 2800, 0, 100, 2700, 'approved', 1),
(5, 1, 2026, 1900, 50, 30, 1920, 'approved', 1),
(6, 1, 2026, 2400, 100, 60, 2440, 'draft', 1),
(7, 1, 2026, 2100, 80, 40, 2140, 'approved', 1),
(8, 1, 2026, 3500, 200, 150, 3550, 'paid', 1),
(9, 1, 2026, 1850, 20, 10, 1860, 'approved', 1),
(10,1, 2026, 2300, 120, 70, 2350, 'draft', 1),

-- Febrero 2026
(2, 2, 2026, 2200, 150, 80, 2270, 'draft', 1),
(3, 2, 2026, 3000, 200, 120, 3080, 'draft', 1),
(4, 2, 2026, 2800, 100, 100, 2800, 'draft', 1),
(5, 2, 2026, 1900, 50, 30, 1920, 'draft', 1),
(6, 2, 2026, 2400, 100, 60, 2440, 'draft', 1),
(7, 2, 2026, 2100, 80, 40, 2140, 'draft', 1),
(8, 2, 2026, 3500, 200, 150, 3550, 'draft', 1),
(9, 2, 2026, 1850, 20, 10, 1860, 'draft', 1),
(10,2, 2026, 2300, 120, 70, 2350, 'draft', 1);

-- Solicitudes adicionales
INSERT INTO leave_requests (employee_id, tipo, fecha_inicio, fecha_fin, motivo, estado, aprobado_por_user_id, comentario_aprobacion, resuelto_en) VALUES
(4, 'vacaciones', '2026-02-20', '2026-02-25', 'Viaje familiar', 'approved', 1, 'Aprobado', NOW()),
(5, 'permiso', '2026-02-15', '2026-02-15', 'Cita médica', 'approved', 1, 'OK', NOW()),
(6, 'dia_personal', '2026-02-10', '2026-02-10', 'Asuntos personales', 'rejected', 1, 'No procede', NOW()),
(7, 'vacaciones', '2026-03-01', '2026-03-05', 'Descanso', 'pending', NULL, NULL, NULL),
(8, 'permiso', '2026-02-18', '2026-02-18', 'Trámites', 'pending', NULL, NULL, NULL),
(9, 'vacaciones', '2026-02-22', '2026-02-24', 'Asuntos familiares', 'approved', 1, 'Concedido', NOW()),
(10,'dia_personal', '2026-02-14', '2026-02-14', 'Día personal', 'pending', NULL, NULL, NULL);

-- Candidatos de ejemplo (con campos opcionales)
INSERT INTO talentia_candidatos (nombre, email, telefono, edad, experiencia_anios, skills, idiomas, nivel_ingles, puesto_actual, archivo_pdf, cv_text, estado, creado_por_user_id) VALUES
('Ana Belén Martín', 'ana.martin@email.com', '600111222', 32, 8, 'PHP, JavaScript, MySQL, Laravel, Vue', 'Inglés, Francés', 'B2', 'Desarrolladora Full Stack', NULL, 'Experiencia en desarrollo web...', 'nuevo', 1),
('Carlos López', 'carlos.lopez@email.com', '600222333', 28, 5, 'Python, Django, PostgreSQL, Docker', 'Inglés', 'C1', 'Backend Developer', NULL, 'Desarrollador backend con experiencia en microservicios...', 'en_revision', 1),
('Elena Ruiz', 'elena.ruiz@email.com', '600333444', 35, 12, 'Project Management, Agile, Scrum, Jira', 'Inglés, Alemán', 'C1', 'Project Manager', NULL, 'Gestión de proyectos tecnológicos...', 'preseleccionado', 1),
('David Gómez', 'david.gomez@email.com', '600444555', 41, 15, 'Java, Spring, Hibernate, Oracle', 'Inglés', 'B2', 'Arquitecto de Software', NULL, 'Arquitecto con más de 15 años de experiencia...', 'contratado', 1),
('Laura Sánchez', 'laura.sanchez@email.com', '600555666', 26, 3, 'React, Node.js, MongoDB', 'Inglés', 'B1', 'Desarrolladora Frontend', NULL, 'Desarrolladora frontend con experiencia en React...', 'descartado', 1),
('Mario Jiménez', 'mario.jimenez@email.com', '600666777', 38, 10, 'DevOps, AWS, Kubernetes, Terraform', 'Inglés', 'C2', 'DevOps Engineer', NULL, 'Ingeniero DevOps certificado AWS...', 'nuevo', 1),
('Patricia Díaz', 'patricia.diaz@email.com', '600777888', 30, 6, 'Marketing Digital, SEO, SEM, Analytics', 'Inglés', 'B2', 'Especialista Marketing', NULL, 'Experta en marketing digital y análisis de datos...', 'en_revision', 1);
