<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\PrivilegioModel;
use Illuminate\Support\Facades\View;


class AdminController extends Controller
{
    public function __construct()
    {
     $this->middleware('auth');//inicio de autenticacion
     $this->middleware(function($request,$next){
         $datos = $request->session()->all(); //CARAGAMOS TODAS LAS SESIONES
         if(isset($datos['user_data'])){ // existe la session user_data?
            $menu_priv = Helper::buildTree($datos['user_data']['menu_priv']);
            $menu_priv_html = Helper::buildTreeHtml($menu_priv);
            View::share('menu_priv_html', $menu_priv_html);

         }
         return $next($request);
     });
    }
    public function dashboard(){
        return view('dashboard');

    }

    public function page($page){
        //echo $page; exit;
       $privilegios = $this->getMethodControllerPriv($page);
       if (count($privilegios)) {
            session()->put('current_page',$page);
            $ruta_privilegios = $this->getRouteAdmin();//obtener la ruta del controlador o controladores del privilegio
            $method = $privilegios['method'];
            return app($ruta_privilegios.$privilegios['controller'])->$method();//cargar otro controlador con su metodo
            //return App\Http\Controllers\Privilegios\UserController->();
            //UserController()->index();
       }else{
           return view('dashboard.404');
       }
       return view('dashboard');

    }
    public function getRouteAdmin(){
        $ruta = 'App\Http\Controllers\Privilegios\.';
        return substr($ruta,0,strlen($ruta)-1);
    }
    public function getMethodControllerPriv($page){
        $privilegios = new PrivilegioModel();
        $data= $privilegios::select(['name','controller','metodo_controller'])->where('url', $page)->get()->toArray();
        $priv = isset($data[0]) ? $data[0]:array(); //isset = si existe //operador ternario
        if (count($priv)) { //existe el privilegio
            View::share('titulo_privilegio', $priv['name']);
            //View()->share('titulo_privilegio', $priv['name']);
        }else{ // no existe el privilegio
            View::share('titulo_privilegio', 'No Existe');
        }
        return $priv;
    }
    //
}
