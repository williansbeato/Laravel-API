<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Cliente;

class ClienteController extends Controller
{
    public function index()
    {

       $cliente = Cliente::all();
       
       return view('cliente.index', compact(['cliente']));

    }

 
    public function create(){ }

    public function store(Request $request){
        $cliente = new Cliente();
        $cliente -> nome = mb_strtoupper($request->input('nome'),'UTF-8');
        $cliente -> telefone = $request -> input('telefone');
        $cliente -> email = $request -> input('email');
        $cliente -> save();


        return json_encode($cliente);
    }

   
    public function show($id)
    {

        $cliente = Cliente::find($id);

        if(isset($cliente)){
            return json_encode($cliente);
        }

        return response('Cliente não encontrado', 404);

    }

  
    public function edit($id){ }

   
    public function update(Request $request, $id)
    {
        
        $cliente = Cliente::find($id);

        if(isset($cliente)){
            $cliente -> nome = mb_strtoupper($request->input('nome'),'UTF-8');
            $cliente -> telefone = $request -> input('telefone');
            $cliente -> email = $request -> input('email');
            $cliente -> save();     
            return json_encode($cliente);
  
        }

        return response('Cliente não encontrado', 404);

    }

   
    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if(isset($cliente)){
            $cliente -> delete();   
            return response("OK", 200);
        }

        return response('Cliente não encontrado', 404);
    }
}
