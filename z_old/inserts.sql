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
