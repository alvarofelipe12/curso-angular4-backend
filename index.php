<?php

require_once 'vendor/autoload.php';
$app = new \Slim\Slim();
$db = new mysqli('localhost','root','', 'curso_angular4');

//configuracion de cabeceras
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

$app->get("/pruebas", function() use($app,$db){
    echo "Hola mundo desde Slim PHP";
    var_dump($db);
});
//listar todos los productos
$app->get('/productos', function() use($db,$app){
    $sql = 'SELECT * FROM productos ORDER BY id DESC;';
    $query = $db->query($sql);
    //var_dump ($query->fetch_all());//reemplaza el bucle all en lugar de assoc
    $productos= array();
    while($producto=$query->fetch_assoc()){
        $productos[] = $producto;
    }
    $result=array(
        'status'=>'success',
        'code'=>200,
        'data'=>$productos
    );
    echo json_encode($result);
});
//devolver un solo producto
$app->get('/productos/:id', function($id) use($db,$app){
    $sql='SELECT * FROM productos WHERE id='.$id;
    $query=$db->query($sql);
    $result=array(
        'status' => 'error',
        'code'=> 404,
        'message'=> 'producto no disponible'
    );
    if($query->num_rows == 1){
        $producto=$query->fetch_assoc();
        $result=array(
            'status' => 'success',
            'code'=> 200,
            'data'=> $producto
        );
    }
    echo json_encode($result);
});
//eliminar un producto
$app->get('/delete-producto/:id', function($id) use($db,$app){
    $sql='DELETE FROM productos WHERE id='.$id;
    $query=$db->query($sql);
    
    if($query){
        $result=array(
            'status' => 'success',
            'code'=> 200,
            'message'=> 'se ha eliminado correctamente el producto'
        );
    }else{
        $result=array(
            'status' => 'error',
            'code'=> 404,
            'message'=> 'el producto NO ha sido eliminado'
        );
    }
    echo json_encode($result);
});


//actualizar un producto

$app->post('/update-producto/:id', function($id) use($app,$db){
    $json=$app->request->post('json');
    $data=json_decode($json,true);
    $sql="UPDATE productos SET nombre='{$data["nombre"]}', descripcion='{$data["descripcion"]}', ";
    if(isset($data['imagen'])){
        $sql.="imagen='{$data["imagen"]}', ";
    }
    $sql.=" precio='{$data["precio"]}' WHERE id={$id}";
    $query=$db->query($sql);
    if($query){
        $result=array(
            'status' => 'success',
            'code'=> 200,
            'message'=> 'se ha actualizado el producto'
        );
    }else{
        $result=array(
            'status' => 'error',
            'code'=> 404,
            'message'=> 'el producto NO ha sido actualizado'
        );
    }
    echo json_encode($result);
});

//subir una imagen a un producto

$app->post('/upload-file', function() use($db,$app){
    $result=array(
        'status' => 'error',
        'code'=> 404,
        'message'=> 'la imagen NO ha sido subida'
    );
    if(isset($_FILES['uploads'])){
        
        $piramideUploader=new PiramideUploader();
        $upload = $piramideUploader->upload('image',"uploads", "uploads", array('image/jpeg','image/png','image/gif'));
        $file=$piramideUploader->getInfoFile();
        $fileName=$file['complete_name'];
        if(isset($upload) && $upload['uploaded']==false){
            $result=array(
                'status' => 'error',
                'code'=> 404,
                'message'=> 'la imagen NO ha sido subida'
            );
        }else{
            $result=array(
                'status' => 'success',
                'code'=> 200,
                'message'=> 'la imagen ha sido subida correctamente',
                'filename'=> $fileName
            );
        };
    }
    echo json_encode($result);
});

//guardar productos
$app->post('/productos', function() use($app,$db){
    $json = $app->request->post('json');
    $data = json_decode($json, true);
    if(!isset($data['nombre'])){
        $data['nombre']=null;
    }
    if(!isset($data['descripcion'])){
        $data['descripcion']=null;
    }
    if(!isset($data['precio'])){
        $data['precio']=null;
    }
    if(!isset($data['imagen'])){
        $data['imagen']=null;
    }

    $query = "INSERT INTO productos VALUES(NULL,".
    "'{$data['nombre']}',".
    "'{$data['descripcion']}',".
    "'{$data['precio']}',".
    "'{$data['imagen']}'".
    ");";
    $insert = $db->query($query);
    //var_dump($query);
    $result = array(
        'status' => 'error',
        'code' => 404,
        'message' => 'producto no creado'
    );
    if($insert){
        $result = array(
            'status' => 'success',
            'code' => 200,
            'message' => 'producto creado correctamente'
        );
    }
    echo json_encode($result);
});
$app->run();