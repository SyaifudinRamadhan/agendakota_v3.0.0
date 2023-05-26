<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Category as CatModel;
use Illuminate\Http\Request;

class Category
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        $category= $request->route()->parameter('category');
        $categorys = CatModel::all();
        $categoryIn = false;
        for($i=0; $i<count($categorys); $i++){
            if($category == $categorys[$i]->name){
                $categoryIn = true;
            }
        }
        if($categoryIn = true){
            return $next($request);
        }else{
            abort(403);
        }

    }
}
