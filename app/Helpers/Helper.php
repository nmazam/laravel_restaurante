<?php
namespace App\Helpers;
class Helper{

    public static function buildTree(array $lista, $parent_id=0){
        $build = array(); // este array contiene todos los elementos con sus hijos
        foreach($lista as $elemento){
            if($elemento['parent']== $parent_id){ // estoy dentro del elemento padre
                /**Encontrar todos los hijos del padre con recursividad**/
                $children = Helper::buildTree($lista,$elemento['id']); //$parent_id = al id del elemento
                if ($children) { // si encuentra un hijo
                    $elemento ['children']= $children;
                }
                $build []= $elemento; // la variable build contiene todos los elementos con sus hijos
                // [] es igual a un contador desde 0 y aumenta de 1 en 1
        }
     }
    return $build;
      }
      public static function buildTreeHtml(array $lista,$parent_id=0 ){
        $html='';
        foreach($lista as $key =>$item){
            if ($parent_id == 0) {
                if(0<$item['Count']){//si el privilegio padre tiene hijos
                    $html.= '<li class="nav-item">';
                    $html.=   '<a href="" class="">';
                    $html.=     '<i class="nav-icon fas fa-search"></i>';
                    $html.=     '<p>'.$item["label"].'<i class="fas fa-angle-left right"></i></p>';
                    $html.=   '</a>';
                    $html.=   '<ul class="nav nav-treeview">';
                }else{
                    $html.= '<li class="nav-item">';
                    $html.=   '<a href="" class="">';
                    $html.=     '<i class="nav-icon fas fa-search"></i>';
                    $html.=     '<p>'.$item["label"].'</p>';
                    $html.=   '</a>';

                }
            }
            if(isset($item["children"])){
                $html.= Helper::buildTreeHtml($item["children"],$item["id"]);
                $html.= '</ul>';
                $html.= '</li>';

            }
        }
        return $html;

      }
      public static function objectToArray($object){
          if(is_object($object) || is_array($object)){
              $data = (array)$object;     //conversion forzada de objeto array
            foreach($data as $key => $item){
                $item = Helper::objectToArray($item);

            }
            return $data;
          }
          else{
              return $object;
          }
      }
}