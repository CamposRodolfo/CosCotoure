-- Crear la base de datos
CREATE DATABASE CosCotoure_db;
USE CosCotoure_db;

-- Tabla de Usuarios
CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion1 VARCHAR(255),
    direccion2 VARCHAR(255),
    ciudad VARCHAR(100),
    pais VARCHAR(100),
    codigo_postal VARCHAR(20),
    metodo_pago VARCHAR(50),
    tipo_direccion VARCHAR(50),
    tipo_usuario ENUM('usuario', 'administrador') DEFAULT 'usuario',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Productos
CREATE TABLE Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(255) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    imagen_producto VARCHAR(255),
    creado_por INT,
    FOREIGN KEY (creado_por) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Tallas (relación con Productos)
CREATE TABLE Tallas (
    id_talla INT AUTO_INCREMENT PRIMARY KEY,
    talla VARCHAR(10) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Inventario (
    id_inventario INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    id_talla INT,
    stock INT DEFAULT 0,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE
    FOREIGN KEY (id_talla) REFERENCES Tallas(id_talla) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Pedidos
CREATE TABLE Pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    estado ENUM('en proceso', 'cancelado') DEFAULT 'en proceso',
    total DECIMAL(10, 2) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Detalle de Pedidos (Productos solicitados en cada pedido)
CREATE TABLE DetallePedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT,
    id_talla INT,
    cantidad INT DEFAULT 1,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_talla) REFERENCES Tallas(id_talla) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Sesiones (para login/logout)
CREATE TABLE Sesiones (
    id_sesion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    token VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expiracion TIMESTAMP DEFAULT DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla del Carro de Compras
CREATE TABLE CarroCompras (
  id_carro varchar(20) NOT NULL,
  id_usuario varchar(20) NOT NULL,
  id_producto varchar(20) NOT NULL,
  subtotal varchar(10) NOT NULL,
  cantidad varchar(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Consultas adicionales para funcionalidades
-- Crear Usuarios genericos
INSERT INTO Usuarios (nombre, correo, contrasena)
VALUES 
('Rodolfo Campos', 'rodolfo.campos1@utp.ac.pa', MD5('admin123')),
('Adrina Achurra', 'adriana.achurra@utp.ac.pa', MD5('admin123')),
('Victor Arrocha', 'victor.arrocha1@utp.ac.pa', MD5('admin123')),
('Paola Quiñones', 'paola.quinones@utp.ac.pa', MD5('admin123')),
('Gabriel Ruiz', 'gabriel.ruiz1@utp.ac.pa', MD5('admin123')), 
('Steven Guerra', 'steven.guerra1@utp.ac.pa', MD5('admin123'));

-- Crear un producto con tallas
INSERT INTO Productos (nombre_producto, precio, imagen_producto, creado_por)
VALUES ('Camisa', 19.99, 'camisa.jpg', 1);

INSERT INTO Tallas (id_producto, talla, stock)
VALUES 
(LAST_INSERT_ID(), 'S', 10),
(LAST_INSERT_ID(), 'M', 15),
(LAST_INSERT_ID(), 'L', 5);

-- Crear un pedido
INSERT INTO Pedidos (id_usuario, total)
VALUES (1, 29.98);

INSERT INTO DetallePedido (id_pedido, id_producto, id_talla, cantidad, subtotal)
VALUES (LAST_INSERT_ID(), 1, 2, 2, 29.98);

-- Actualizar el estado de un pedido
UPDATE Pedidos
SET estado = 'cancelado'
WHERE id_pedido = 1;

-- Gestión de perfil (cambiar datos de usuario)
UPDATE Usuarios
SET nombre = 'Nuevo Nombre', telefono = '123456789'
WHERE id_usuario = 1;

-- Autenticación
-- Registro
INSERT INTO Usuarios (nombre, correo, telefono, contrasena)
VALUES ('Usuario Prueba', 'usuario@correo.com', '987654321', MD5('password123'));

-- Login
SELECT * FROM Usuarios
WHERE correo = 'usuario@correo.com' AND contrasena = MD5('password123');

-- Logout
DELETE FROM Sesiones
WHERE id_usuario = 1;
