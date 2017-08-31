<?php
/**
 * Created by PhpStorm.
 * User: cared
 * Date: 10/08/2017
 * Time: 07:43 PM
 */

namespace App;


class Cart{
    public $productos = null;
    public $cantidadProductos = 0;
    public $total = 0;

    public static function withCart($oldCarrito){
        $instance = new self();
        if($oldCarrito){
            $instance->productos = $oldCarrito->productos;
            $instance->cantidadProductos = $oldCarrito->cantidadProductos;
            $instance->total = $oldCarrito->total;
        }
    }
    public function __construct(){
            $this->productos = null;
            $this->cantidadProductos =0;
            $this->total = 0;
    }

    public function add($item, $id,$cantidad){
        $producto =['cantidad' => 0,'total'=>0,  'item' => $item];
        if($this->productos){
            if(array_key_exists($id, $this->productos)){
                $producto = $this->productos[$id];
            }
        }
        $producto['cantidad']+=$cantidad;
        $producto['total'] = $item['precio'] * $producto['cantidad'];
        $this->productos[$id] = $producto;
        $this->cantidadProductos+=$cantidad;
        $this->total += $producto['total'];
    }

    public function remove($id){
        if($this->productos){
            if(array_key_exists($id,$this->productos)){
                $this->cantidadProductos -= $this->productos[$id]['cantidad'];
                $this->total -= $this->productos[$id]['total'];
                unset($this->productos[$id]);
            }
        }
    }

    public function removePartial($id, $cantidad){
        if($this->productos){
            if(array_key_exists($id,$this->productos)){
                if(($this->productos[$id]['cantidad'] - $cantidad ) == 0 || $cantidad == 0){
                    $this->remove($id);
                }else {
                    if ($cantidad <= $this->productos[$id]['cantidad']) {
                        $this->cantidadProductos -= $cantidad;
                        $this->productos[$id]['cantidad'] -= $cantidad;
                        $this->productos[$id]['total'] = $this->productos[$id]['item']['precio'] * $this->productos[$id]['cantidad'];
                        $this->total -= $this->productos[$id]['item']['precio'] * $cantidad;
                        if ( $this->cantidadProductos == 0){
                            unset($this->productos[$id]);
                        }
                    } else {
                        unset($this->productos[$id]);
                    }
                }
            }
        }
    }

    public function setCantidad($id, $cantidad){
        $this->cantidadProductos -= $this->productos[$id]['cantidad'];
        $this->productos[$id]['cantidad'] = $cantidad;
        $this->cantidadProductos += $this->productos[$id]['cantidad'];
        $this->total -= $this->productos[$id]['total'];
        $this->productos[$id]['total'] = $this->productos[$id]['cantidad'] * $this->productos[$id]['item']->precio1;
        $this->total += $this->productos[$id]['total'];
    }

}