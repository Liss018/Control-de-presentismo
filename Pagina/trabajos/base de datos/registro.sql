-- Eliminar la base de datos si ya existe
DROP DATABASE IF EXISTS registro;

-- Crear la base de datos
CREATE DATABASE registro;
USE registro;

-- Tabla Año
CREATE TABLE Año(
    id_ano int PRIMARY KEY AUTO_INCREMENT,
    descripcion varchar(100) NOT NULL -- Descripción del año
);
-- Insertar datos en la tabla Año
INSERT INTO Año (id_ano, descripcion)
VALUES 
(1, '1er Año'),
(2, '2do Año'),
(3, '3er Año'),
(4, '4to Año'),
(5, '5to Año'),
(6, '6to Año');

-- Tabla division
CREATE TABLE division (
    id INT PRIMARY KEY AUTO_INCREMENT,
    año INT NOT NULL,
    Nr INT NOT NULL,
    turno ENUM('mañana', 'tarde') NOT NULL,
    FOREIGN KEY (año) REFERENCES Año(id_ano)
);

-- Inserciones en la tabla division
INSERT INTO division (id, año, Nr, turno) VALUES
(1, 1, 1, 'mañana'),
(2, 1, 2, 'mañana'),
(3, 1, 3, 'mañana'),
(4, 1, 4, 'mañana'),
(5, 1, 5, 'mañana'),
(6, 1, 6, 'mañana'),
(7, 1, 7, 'mañana'),
(8, 1, 8, 'mañana'),
(9, 1, 9, 'mañana'),
(10, 1, 10, 'mañana'),
(11, 1, 11, 'mañana'),
(12, 1, 12, 'mañana'),
(13, 2, 1, 'mañana'),
(14, 2, 2, 'mañana'),
(15, 2, 3, 'mañana'),
(16, 2, 4, 'mañana'),
(17, 2, 5, 'mañana'),
(18, 2, 6, 'tarde'),
(19, 2, 7, 'tarde'),
(20, 2, 8, 'tarde'),
(21, 2, 9, 'tarde'),
(22, 2, 10, 'tarde'),
(23, 2, 11, 'tarde'),
(24, 2, 12, 'mañana'),
(25, 3, 1, 'mañana'), -- Computacion
(26, 3, 7, 'tarde'),
(27, 4, 1, 'mañana'),
(28, 4, 2, 'tarde'),
(29, 5, 1, 'mañana'),
(30, 5, 2, 'tarde'),
(31, 6, 2, 'mañana'),
(32, 6, 1, 'tarde'),
(33, 3, 9, 'mañana'), -- Quimica
(34, 3, 5, 'tarde'),
(35, 4, 1, 'mañana'),
(36, 4, 2, 'tarde'),
(37, 5, 1, 'mañana'),
(38, 5, 2, 'tarde'),
(39, 6, 1, 'mañana'),
(40, 6, 2, 'tarde'),
(41, 3, 3, 'mañana'), -- Construcciones
(42, 3, 6, 'tarde'),
(43, 4, 2, 'tarde'),
(44, 5, 1, 'mañana'),
(45, 6, 1, 'mañana'),
(46, 6, 2, 'mañana'),
(47, 3, 8, 'tarde'), -- Electronica
(48, 4, 2, 'mañana'),
(49, 4, 1, 'tarde'),
(50, 5, 2, 'mañana'),
(51, 5, 1, 'tarde'),
(52, 6, 1, 'tarde');

-- Inserciones en la tabla division lo que falta
INSERT INTO division (id, año, Nr, turno) VALUES
(53,3,2,'mañana'), --Mecanica
(54,3,10,'tarde'),
(55,4,1,'mañana'),
(56,4,2,'mañana'),
(57,5,1,'tarde'),
(58,6,1,'tarde'),
(59,3,4,'mañana'),--Electricidad
(60,4,1,'mañana'),
(61,5,1,'mañana'),
(62,6,1,'mañana');

-- Tabla especialidades
CREATE TABLE especialidades (
    id_espe INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('Computacion', 'Mecanica', 'Electricidad', 'Química', 'Construcciones', 'Electrónica', 'Ninguna') NOT NULL,
    id_ano INT NOT NULL,
    id_divi INT NOT NULL,
    FOREIGN KEY (id_divi) REFERENCES division(id),
    FOREIGN KEY (id_ano) REFERENCES Año(id_ano)
);

-- Inserciones en la tabla especialidades
INSERT INTO especialidades (tipo, id_ano, id_divi) VALUES
('Computacion', 3, 25),
('Computacion', 3, 26),
('Computacion', 4, 27),
('Computacion', 4, 28),
('Computacion', 5, 29),
('Computacion', 5, 30),
('Computacion', 6, 31),
('Computacion', 6, 32),
('Quimica', 3, 33),
('Quimica', 3, 34),
('Quimica', 4, 35),
('Quimica', 4, 36),
('Quimica', 5, 37),
('Quimica', 5, 38),
('Quimica', 6, 39),
('Quimica', 6, 40),
('Construcciones', 3, 41),
('Construcciones', 3, 42),
('Construcciones', 4, 43),
('Construcciones', 5, 44),
('Construcciones', 6, 45),
('Construcciones', 6, 46),
('Electronica', 3, 47),
('Electronica', 4, 48),
('Electronica', 4, 49),
('Electronica', 5, 50),
('Electronica', 5, 51),
('Electronica', 6, 52);

--insertar lo resto 
INSERT INTO especialidades (tipo, id_ano, id_divi) VALUES
('Mecanica',3,53),
('Mecanica',3,54),
('Mecanica',4,55),
('Mecanica',4,56),
('Mecanica',5,57),
('Mecanica',6,58),
('Electricidad',3,59),
('Electricidad',4,60),
('Electricidad',5,61),
('Electricidad',6,62);


-- Tabla verificacion
CREATE TABLE verificacion(
    codigo int PRIMARY KEY AUTO_INCREMENT,
    ip varchar(45) NOT NULL -- Dirección IP como varchar
);


-- Tabla registro de alumnos
CREATE TABLE registro_alum (
    id_alum INT PRIMARY KEY AUTO_INCREMENT,
    dni INT NOT NULL UNIQUE, -- DNI único para cada alumno
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono INT NOT NULL,
    id_ano INT NOT NULL,
    id_divi INT NOT NULL,
    id_espe INT ,
    turno ENUM('mañana', 'tarde') NOT NULL, -- Aquí guardamos el turno
    codigo INT NOT NULL,
    FOREIGN KEY (id_espe) REFERENCES especialidades(id_espe), -- Relación con especialidades
    FOREIGN KEY (id_divi) REFERENCES division(id), -- Relación con división
    FOREIGN KEY (id_ano) REFERENCES Año(id_ano) -- Relación con Año
);

-- Tabla registro de administradores
CREATE TABLE registro_admi (
    id_admi INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    dni VARCHAR(8) NOT NULL UNIQUE, -- DNI único para cada administrador
    correo VARCHAR(100) NOT NULL UNIQUE, -- Correo único para cada administrador
    contraseña VARCHAR(250) NOT NULL
);
CREATE TABLE asistencia (
    id_asistencia INT PRIMARY KEY AUTO_INCREMENT,
    id_alum INT NOT NULL,
    fecha DATE NOT NULL,
    asistencia ENUM('Presente', 'Tarde', 'Ausente', 'Ausente_con_Permanencia') NOT NULL, 
    observaciones VARCHAR(255), -- Opcional: para justificar la ausencia
    FOREIGN KEY (id_alum) REFERENCES registro_alum(id_alum) -- Relación con la tabla registro_alum
);
