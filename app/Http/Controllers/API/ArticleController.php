<?php

namespace App\Http\Controllers\API;

use App\Article;
use App\Http\Controllers\ArticleImportController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HelpersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index(Request $request){

        return DB::select('SELECT * FROM articles');
    }

    public function store(Request $request){

        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        return DB::table('articles')->insert(['name'=>$data['title'], 'description'=>$data['description']]);
    }

    public function import(Request $request){

        $data = $request->validate([
            'articlesList' => 'required|file|mimes:csv',
        ]);
        $file = $_FILES['articlesList'];
        $csvArray = HelpersController::csvToArray($file);

        foreach ($csvArray as $k => $v) {
            
            $data = [
                'title' => $v[0],
                'description' => $v[1],
                'categories' => $v[2]
            ];

            $validator = Validator::make($data, [
                'title' => 'required',
                'description' => 'required',
                'categories' => 'required'
           ]);

           if(!$validator->fails()){

                $articleId = DB::table('articles')->insertGetId(['name'=>$data['title'], 'description'=>$data['description']]);

                $categories = explode(',',$data['categories']);

                foreach ($categories as $key => $categoryId) {
                    
                    try {
                        
                        DB::insert("INSERT INTO articles_categories(article_id, category_id) VALUES($articleId,$categoryId)");
                    } 
                    catch (\Throwable $th) {
                        //throw $th;
                    }
                }
           }
        }
    }

    public function export(Request $request){

        return DB::select('SELECT a.*, GROUP_CONCAT(c.title SEPARATOR ", ") as categories, COUNT(c.id) as categoriesCount FROM articles as a
            LEFT JOIN articles_categories as ac ON a.id = ac.article_id AND ac.deleted = 0
            LEFT JOIN categories as c ON ac.category_id = c.id AND c.deleted = 0
            GROUP BY a.id HAVING categoriesCount > 1');
    }
}
