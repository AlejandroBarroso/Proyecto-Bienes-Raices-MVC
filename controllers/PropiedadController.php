<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController {

    public static function index(Router $router) {

        $propiedades = Propiedad::all();
        // Muestra mensaje condicional *creado/actualizado/eliminado correcamente*
        $resultado = $_GET['resultado'] ?? null;

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado
       ]); 
    }

    public static function crear(Router $router) {  

        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();  //Arreglo de mensajes de errores
      
    //Ejecuta el codigo despues que el usuario envia el formulario
    if($_SERVER["REQUEST_METHOD"] === 'POST') {
        // Crea una nueva instancia
        $propiedad = new Propiedad($_POST['propiedad']);
        //          **subida de archivos **
        // generar un nombre unico para las imagenes ( para que no se sobreescriban)
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
        // Setear la imagen
        // Raliaza un resize a la imagen con INTERVENTION
        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $image = Image::make($_FILES ['propiedad']['tmp_name']['imagen'])->fit(800, 600);
            $propiedad->setImagen($nombreImagen);
        }
        //Validar  
        $errores = $propiedad->validar();
        //Revisa si el array este vacio...
        if (empty($errores)) {
        // Si esta vacio, crea la carpeta para subir imagenes
        if (!is_dir(CARPETA_IMAGENES)) {
           mkdir(CARPETA_IMAGENES);
        }
        //Guarda la imagen en el servidor
        $image->save(CARPETA_IMAGENES . $nombreImagen);
        // Guarda en la DB
        $propiedad->guardar();
    }
}

       $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
       ]);
    }

    public static function actualizar(Router $router) {
        $id = validarORedireccionar('/admin');
        $propiedad = Propiedad::find($id);
        $vendedores = Vendedor::all();
        $errores = Propiedad::getErrores();
        // Metodo Post para actualizar (ejecuta el codigo despues que el usuario envia el formulario) 
    if ($_SERVER["REQUEST_METHOD"] === 'POST') {
        // Asignar los atributos
        $args = $_POST['propiedad'];
        $propiedad->sincronizar($args);
        // Validacion
        $errores = $propiedad->validar();
        // Subida de archivos
        // generar un nombre unico para las imagenes ( para que no se sobreescriban)
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $image = Image::make($_FILES ['propiedad']['tmp_name']['imagen'])->fit(800, 600);
            $propiedad->setImagen($nombreImagen);
        }
        //revisar que el array este vacio
        if(empty($errores)) {
        if($_FILES['propiedad']['tmp_name']['imagen']) {
            // almacena imagen
            $image->save(CARPETA_IMAGENES . $nombreImagen); 
        }
        $propiedad->guardar();
            // Guarda en la base de datos
            $resultado = $propiedad->guardar();

            if($resultado) {
                header('location: /propiedades');
            }
    }
}

        $router->render('/propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }

    public static function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validar id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);
        
            if ($id) {
                $tipo = $_POST['tipo'];
                if(validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }
            }
        }

    }

}