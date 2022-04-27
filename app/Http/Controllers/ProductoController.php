<?php

namespace App\Http\Controllers;
use App\models\ProductoModel;
use App\models\CategoriaModel;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct(){
        $this->model = new ProductoModel();
    }
    public function index(){
        $productos  = ProductoModel::all();
        $categorias = CategoriaModel::all();
        return view('producto')->with(compact('productos','categorias'));
    }
    public function ver(Request $request){
        $input = $request->all();
        $response['status'] = false;
        try {
             $ver_producto= ProductoModel::find($input['id']);
             if($ver_producto== NULL){
                throw new \Exception("El producto no existe");
            }
            $data['categoria']=$ver_producto->id_categoria;
            $data['nombre']=$ver_producto->nombre;
            $data['precio']=$ver_producto->precio;
            $data['descripcion']=$ver_producto->description;
            $data['foto'] = $ver_producto->foto;
            $response['data'] = $data;
            $response['status'] = true;
        }catch(\Exception $e){
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response); exit;
    }
    public function save(Request $request){
        $input = $request->all();
        $ver_producto = NULL;
        $response['status'] = false;
        try{
            if (trim($input['nombre']) == '') {
                throw new \Exception("Error, el nombre del producto es requerido");
            }
            if (trim($input['categoria']) == '') {
                throw new \Exception("Error, la categoria del producto es requerido");
            }
            if (trim($input['precio']) == '') {
                throw new \Exception("Error, el precio del producto es requerido");
            }
            if (trim($input['descripcion']) == '') {
                throw new \Exception("Error, la descripcion del producto es requerido");
            }
            if (trim($input['producto_id']) != '') {
                $ver_producto = ProductoModel::find($input['producto_id']);
            }
            //Variable que permite reemplazar un archivo
            $replace_file = $ver_producto == NULL ? '' : 'replace';
            $req_file = $ver_producto == NULL ? true : false;
            $respfile = $this->UploadFile($request,'foto','jpg,pnG,mp4','3Mb',$req_file,$replace_file);

            if ($respfile['status'] == false) {
                throw new \Exception($respfile['message']);
            }
            $producto = $ver_producto == NULL ? new ProductoModel() : ProductoModel::find($input['producto_id']);
            $producto->nombre        = $input['nombre'];
            $producto->id_categoria  = $input['categoria'];
            $producto->precio        = $input['precio'];
            $producto->description   = $input['descripcion'];
            $producto->foto          = $ver_producto == NULL ? $respfile['name'] : $ver_producto->foto;
            $producto->save();
            $response['status'] = true;
            $response['message'] = $ver_producto == NULL ? 'El Producto ha sido registrado' : 'El Producto fue editado';
        }catch(\Exception $e){
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response); exit;
    }
    public function delete(Request $request){
        $input = $request->all();
        $response['status'] = false;
        try {
            $producto = ProductoModel::find($input['id']);
            $producto->delete();
            $response['status'] = true;
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }
        echo json_encode($response); exit;
    }
}