-- Crear la base de datos
CREATE DATABASE CosCotoure_db;
USE CosCotoure_db;

-- Tabla de Usuarios
CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    imagen_perfil VARCHAR(255),
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
    talla VARCHAR(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Inventario (
    id_inventario INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    id_talla INT,
    stock INT DEFAULT 0,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_talla) REFERENCES Tallas(id_talla) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de Pedidos
CREATE TABLE Pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    estado ENUM('en proceso','entregado','cancelado') DEFAULT 'en proceso',
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
    precio DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) GENERATED ALWAYS AS (precio * cantidad) STORED,
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
  id_carro INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT,
  id_producto INT,
  precio DECIMAL(10, 2) NOT NULL,
  cantidad varchar(2) NOT NULL DEFAULT '1',
  FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Trigger para garantizar consistencia en caso de cambio en precio o cantidad
DELIMITER $$
CREATE TRIGGER before_update_detallepedido
BEFORE UPDATE ON DetallePedido
FOR EACH ROW
BEGIN
    SET NEW.subtotal = NEW.precio * NEW.cantidad;
END$$
DELIMITER ;