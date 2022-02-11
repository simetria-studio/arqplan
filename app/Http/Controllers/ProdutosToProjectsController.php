<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProjectToProduct;
use Illuminate\Http\Request;

class ProdutosToProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valor1 = 0;
        $valor2 = 0;

        $product = Product::find($request->product_id);

        if ($product->tipo == 'produto') {
            $valor1 = $product->price;
        } elseif ($product->tipo == 'servico') {
            $valor2 = $product->price;
        }
        // dd($product);

        $dados = ProjectToProduct::create([
            'user_id' => auth()->user()->id,
            'project_id' => $request->input('project_id'),
            'product_id' => $request->input('product_id'),
            'total' => $product->price,
            'quantidade' => $request->input('quantidade'),
            'service' => $valor2,
            'product' => $valor1,
        ]);

        $request->session()->flash('message', 'Adicionado com sucesso!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->input('pj_id');

        $dados = ProjectToProduct::find($id);
        $dados->update([
            'cpe' => $request->input('cpe'),
        ]);

        $request->session()->flash('message', 'CPE Atualizado com sucesso');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dados = ProjectToProduct::find($id);

        $dados->delete();

        return redirect()->back()->with('message', 'Deletado com sucesso!');

    }
}
