<?php

require 'flight/Flight.php';

//base de datos
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=api', 'root', ''));

Flight::route('GET /alumnos', function () {
    //presentar
    $sentencia = Flight::db()->prepare("SELECT * FROM `alumnos`");
    $sentencia->execute();
    $datos = $sentencia->fetchAll();
    Flight::json($datos);
});

Flight::route('POST /alumnos', function () {
    //recepcion yenviar datos por medio de metodos de posto
    $nombres = (Flight::request()->data->nombres);
    $apellidos = (Flight::request()->data->apellidos);
    $sql = "insert into alumnos (nombres, apellidos) value (?,?)";
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $nombres);
    $sentencia->bindParam(2, $apellidos);
    $sentencia->execute();

    Flight::jsonp(["Alumno agregado"]);
});

Flight::route('DELETE /alumnos', function () {
    // Asumamos que el ID del alumno se pasa a través de un parámetro POST llamado 'id'
    $id = Flight::request()->data->id;
    print_r($id);
    $sql = "delete from alumnos where id=?";
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $id);
    $sentencia->execute();
    Flight::jsonp(["Alumno eliminado"]);
});

Flight::route('PUT /alumnos', function () {
    //Actualizar datos
    $id = (Flight::request()->data->id);
    $nombres = (Flight::request()->data->nombres);
    $apellidos = (Flight::request()->data->apellidos);
    $sql = "UPDATE alumnos SET nombres=?, apellidos=? WHERE id=?";
    $sentencia = Flight::db()->prepare($sql);
 
    $sentencia->bindParam(1, $nombres);
    $sentencia->bindParam(2, $apellidos);
    $sentencia->bindParam(3, $id);

    if($sentencia->execute()) {
        if($sentencia->rowCount() > 0) {
            Flight::json(["message" => "Alumno editado con éxito."]);
        } else {
            Flight::json(["error" => "No se encontró alumno con ese ID o no hubo cambios en los datos."], 404); // Not Found
        }
    } else {
        Flight::json(["error" => "Error al editar el alumno. Inténtalo de nuevo."], 500); // Internal Server Error
    }
});

Flight::route('GET /alumnos/@id', function ($id) {
    // Buscar un registro específico en la base de datos basado en el ID
    $sql = "SELECT * FROM alumnos WHERE id=?";
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $id);
    $sentencia->execute();
    $datos = $sentencia->fetchAll();
    
    if ($datos) {
        Flight::json($datos);
    } else {
        Flight::json(["error" => "No se encontró alumno con ese ID"], 404); // Not Found
    }
});


Flight::start();
