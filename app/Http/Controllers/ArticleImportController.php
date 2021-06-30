<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleImportController extends Controller
{
    public function index(){

        return view('articlesImport');
    }
}
