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
            
        ]);

        if($validator->fails()) {
            return response([
                'message' => 'Validation errors',
                'errors' => $validator->messages()
            ], 422);
        }


        $blog = blog::create([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'user_id' => $request->user()->id,
      
        ]);

        $blog->load('user:id,name,email');

        return response([
            'message' => 'Blog successfully created',
            'data' => $blog
        ], 200);

    }

    public function list(Request $request) {

        $blog_query = blog::with(['user']);



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

    
    public function delete($id) {
        return blog::destroy($id);
    }

}
