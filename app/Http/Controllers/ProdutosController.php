<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Project;
use App\Models\Unidade;
use App\Models\Category;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::where('company_id', Auth::user()->company->id)->get();
        $products = Product::where('user_id', Auth::user()->company->id)->get();

        return view('product.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('user_id', Auth::user()->company->id)->get();
        $unidades = Unidade::where('company_id', Auth::user()->company->id)->get();
        $fornecedores = Provider::get();
        return view('product.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $codigo = rand(200000, 299999);

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(storage_path('/app/public/product_image/'), $imageName);

        $valor = str_replace(['.', ','], ['', '.'], $request->price);

        $dados = Product::create([

            'user_id' => Auth::user()->company->id,
            'code' => $codigo,
            'name' => $request->input('name'),
            'obs' => $request->input('obs'),
            'price' => $valor,
            'fornecedor' => $request->input('fornecedor'),
            'unidade' => $request->input('unidade'),
            'tipo' => $request->input('tipo'),
            'altura' => $request->input('altura'),
            'largura' => $request->input('largura'),
            'peso' => $request->input('peso'),
            'categoria' => $request->input('categoria'),
            'image' => $imageName,
            'comprimento' => $request->input('comprimento'),
            'quantidade' => $request->input('quantidade'),
            'observacao' => $request->input('observacao'),

        ]);
        $request->session()->flash('message', 'Produto criado com sucesso!');

        return redirect()->route('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $unidades = Unidade::where('company_id', Auth::user()->company->id)->get();
        $categories = Category::where('user_id', Auth::user()->company->id)->get();
        $fornecedores = Provider::get();
        return view('product.edit', get_defined_vars());
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
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $valor = str_replace(['.', ','], ['', '.'], $request->price);
        $product->update([
            'name' => $request->input('name'),
            'obs' => $request->input('obs'),
            'price' => $valor,
            'fornecedor' => $request->input('fornecedor'),
            'unidade' => $request->input('unidade'),
            'tipo' => $request->input('tipo'),
            'altura' => $request->input('altura'),
            'largura' => $request->input('largura'),
            'peso' => $request->input('peso'),
            'categoria' => $request->input('categoria'),
            // 'image' => $imageName,
            'comprimento' => $request->input('comprimento'),
            'quantidade' => $request->input('quantidade'),
            'observacao' => $request->input('observacao'),
        ]);

        $request->session()->flash('message', 'Produto alterado com sucesso!');

        return redirect()->route('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dados = Product::find($id);

        $dados->delete();

        return redirect()->back()->with('message', 'Deletado com sucesso!');
    }
}
