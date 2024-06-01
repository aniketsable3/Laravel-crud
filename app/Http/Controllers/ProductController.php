<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        return view("products.index",['products'=>Product::get()]);
    }

    public function create(){
        return view("products.create");
    }

    public function store(Request $request){
        // Validate the incoming request
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check if the request has an image file
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('products'), $imageName);

            // Proceed with further logic, like saving the product details to the database
            // For demonstration, just return the image name

            $product = new Product;
            $product->image = $imageName;
            $product->name = $request->name;
            $product->description = $request->description;


            $product->save();

            return back()->withSuccess('Product Created Successfully');




        }


    }
    public function edit($id){
        $product = Product::where('id',$id)->first();
        return view('products.edit',['product'=>$product]);
    }
    public function update( Request $request, $id){
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);


        $product = Product::where('id',$id)->first();

        if(isset($request->image)){

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }

        // Check if the request has an image file


        // Proceed with further logic, like saving the product details to the database
        // For demonstration, just return the image name


        $product->name = $request->name;
        $product->description = $request->description;


        $product->save();

        return back()->withSuccess('Product Updated Successfully');







    }

    public function destory($id){

        $product = Product::where('id',$id)->first();
        $product->delete();
        return back()->withSuccess('Product Deleted Successfully');

    }
}
