<?php

namespace App\Http\Controllers;

use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        return view('products.index', [
            'products'=>Product::latest()->get()]);
    }

    public function create(){
        return view('products.create');
    }

    public function store(Request $request){
       
        $request->validate([
            'name'=> 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,jpg,gif,png|max:1000',

        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        } else {
            return back()->withErrors(['message' => 'File not uploaded']);
        }
 
        $product = new product;
        $product->image = $imageName;
        $product->name = $request->name;
        $product->description = $request->description;

        $product->save();
 
        return back()->withSuccess("Product Created !!!"); 
    }
    
    public function edit($id){
        $product = Product::where('id', $id)->first();
        return view('products.edit', ['product'=>$product]);
    }

    public function update(Request $request, $id){

        $request->validate([
            'name'=> 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,gif,png|max:1000',

        ]);

        $product = Product::where('id', $id)->first();

        // Check if an image is uploaded
    if($request->hasFile('image')){
        // Generate a new image name
        $imageName = time() . '.' . $request->image->extension();
        
        // Move the image to the public folder
        $request->image->move(public_path('images'), $imageName);
        
        // Update the product's image field
        $product->image = $imageName;
    }

        $product->name = $request->name;
        $product->description = $request->description;

        $product->save();
 
        return back()->withSuccess("Product Updated !!!"); 
    
    }

    public function destroy($id){
        $product = Product::where('id', $id)->first();
        $product->delete();
        return back()->withSuccess("Product Deleted !!!");
    }

    public function show($id){
        $product = Product::where('id', $id)->first();
        return view('products.show', ['product'=>$product]);
    }
}