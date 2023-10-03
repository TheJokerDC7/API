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
    $idAlumno = Flight::request()->data->id;
    print_r($idAlumno);
    $sql = "delete from alumnos where id=?";
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $idAlumno);
    $sentencia->execute();
    Flight::jsonp(["Alumno eliminado"]);
});

Flight::route('PUT /alumnos', function () {
    //Actualizar datos
    $idAlumno = (Flight::request()->data->id);
    $nombres = (Flight::request()->data->nombres);
    $apellidos = (Flight::request()->data->apellidos);
    $sql = "UPDATE alumnos SET nombres=?, apellidos=? WHERE id=?";
    $sentencia = Flight::db()->prepare($sql);
 
    $sentencia->bindParam(1, $nombres);
    $sentencia->bindParam(2, $apellidos);
    $sentencia->bindParam(3, $idAlumno);
    $sentencia->execute();
    Flight::jsonp(["Alumno editado"]);
});

Flight::start();
