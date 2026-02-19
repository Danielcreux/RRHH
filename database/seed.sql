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
