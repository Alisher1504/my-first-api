<?php

namespace App\Http\Controllers\Api;

use App\Models\blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:250',
            'short_description' => 'required',
            'long_description' => 'required',
            'category_id' => 'required',
            // 'image' => 'required|image|mimes:jpg,bmp,png'
        ]);

        if($validator->fails()) {
            return response([
                'message' => 'Validation errors',
                'errors' => $validator->messages()
            ], 422);
        }

        // $image_name = time(). '.' .$request->image->extension();
        // $request->image->move(public_path('/uploads/blogs_images'), $image_name);

        $blog = blog::create([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            // 'image' => $image_name
        ]);

        $blog->load('user:id,name,email', 'category:id,name');

        return response([
            'message' => 'Blog successfully created',
            'data' => $blog
        ], 200);

    }

    public function list(Request $request) {

        $blog_query = blog::with(['user', 'category']);



        $blog = $blog_query->get();
        return response([
            'message' => 'Blog successfully fatched',
            'data' => $blog
        ], 200);

    }

    public function show($id) {
        $blog = blog::with(['user', 'category'])->where('id', $id)->first();
        if($blog) {
            return response([
                'message' => 'Blog successfully fatched',
                'data' => $blog
            ], 200);
        } else {
            return response([
                'message' => 'No blog found'
            ], 200);
        }
    }

    // public function update(Request $request, $id) {
    //     $blog = blog::with(['user', 'category'])->where('id', $id)->first();
    //     if($blog) {

    //         if($blog->user_id == $request->user()->id) {
    //             $validator = Validator::make($request->all(), [
    //                 'title' => 'required|max:250',
    //                 'short_description' => 'required',
    //                 'long_description' => 'required',
    //                 'category_id' => 'required',
    //                 // 'image' => 'required|image|mimes:jpg,bmp,png'
    //             ]);
        
    //             if($validator->fails()) {
    //                 return response([
    //                     'message' => 'Validation errors',
    //                     'errors' => $validator->messages()
    //                 ], 422);
    //             }
    //         }

    //         $blog = blog::create([
    //             'title' => $request->title,
    //             'short_description' => $request->short_description,
    //             'long_description' => $request->long_description,
    //             'user_id' => $request->user()->id,
    //             'category_id' => $request->category_id,
    //         ]);
    
    //         return response([
    //             'message' => 'Blog successfully created',
    //             'data' => $blog
    //         ], 200);


    //     } else {
    //         return response([
    //             'message' => 'No blog found'
    //         ], 200);
    //     }
    // }

    public function delete($id) {
        return blog::destroy($id);
    }

}
