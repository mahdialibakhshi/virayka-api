<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategoriy;
use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ArticleController extends Controller
{
    public function index()
    {
        $cat='';
        $articles = Article::latest()->paginate(20);
        $categories=ArticleCategoriy::all();
        return view('admin.articles.index', compact('articles','categories','cat'));
    }
    public function index_category_sort($cat)
    {
        $articles = Article::where('category_id',$cat)->latest()->paginate(20);
        $categories=ArticleCategoriy::all();
        return view('admin.articles.index', compact('articles','categories','cat'));
    }

    public function create()
    {
        $categories=ArticleCategoriy::all();
        return view('admin.articles.create',compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:articles,title',
            'description' => 'required',
            'category_id' => 'required|integer',
            'shortDescription' => 'required|max:100',
            'primary_image' => 'required|mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->alias==null){
            $alias=$request->name;
        }else{
            $request->validate([
                'alias' => 'required|unique:articles,alias',
            ]);
            $alias=$request->alias;
        }
        $alias=parent::aliasCreator($alias);
        try {
            DB::beginTransaction();

            $productImageController = new ProductImageController();
            $fileNameImages = $productImageController->ArticlesImageController($request->primary_image);
            $article = Article::create([
                'title' => $request->name,
                'alias' => $alias,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'shortDescription' => $request->shortDescription,
                'image' => $fileNameImages,
            ]);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد مقاله', $ex->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('مقاله مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.articles.index');
    }


    public function edit(Article $article)
    {
        $categories=ArticleCategoriy::all();
        return view('admin.articles.edit', compact('article','categories'));
    }


    public function update(Request $request, Article $article)
    {
        $request->validate([
            'name' => 'required|unique:articles,title,' . $article->id,
            'description' => 'required',
            'category_id' => 'required|integer',
            'shortDescription' => 'required|max:100',
            'primary_image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->alias==null){
            $alias=$request->name;
        }else{
            $request->validate([
                'alias' => 'required|unique:articles,alias,' . $article->id,
            ]);
            $alias=$request->alias;
        }
        $alias=parent::aliasCreator($alias);
        $fileNameImages=$article->image;
        if ($request->primary_image!=null){
            $path=public_path(env('ARTICLES_IMAGES_UPLOAD_PATH').$article->image);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
            $path=public_path(env('ARTICLES_IMAGES_THUMBNAIL_UPLOAD_PATH').$article->image);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
            $productImageController = new ProductImageController();
            $fileNameImages = $productImageController->ArticlesImageController($request->primary_image);
        }

        try {
            DB::beginTransaction();
            $article->update([
                'title' => $request->name,
                'alias' => $alias,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'shortDescription' => $request->shortDescription,
                'image' => $fileNameImages,
            ]);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            alert()->error('مشکل در ویرایش مقاله', $ex->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('مقاله مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.articles.index');
    }

    public function destroy(Request $request)
    {
        $article=Article::find($request->id);
        $path=public_path(env('ARTICLES_IMAGES_UPLOAD_PATH').$article->image);
        if (file_exists($path) and !is_dir($path)){
            unlink($path);
        }
        $path=public_path(env('ARTICLES_IMAGES_THUMBNAIL_UPLOAD_PATH').$article->image);
        if (file_exists($path) and !is_dir($path)){
            unlink($path);
        }
        $article->delete();
        alert()->success('مقاله مورد نظر حذف شد', 'باتشکر');
        return redirect()->back();
    }

    public function categories_index(){
        $categories = ArticleCategoriy::latest()->paginate(20);
        return view('admin.articles.categories.index', compact('categories'));
    }

    public function categories_create()
    {
        return view('admin.articles.categories.create');
    }
    public function categories_store(Request $request)
    {


        $request->validate([
            'title' => 'required|unique:article_categories,title',
        ]);
        if ($request->alias==null){
            $alias=$request->title;
        }else{
            $request->validate([
                'alias' => 'required|unique:article_categories,alias',
            ]);
            $alias=$request->alias;
        }
        $alias=parent::aliasCreator($alias);
        ArticleCategoriy::create([
            'title'=>$request->title,
            'alias'=>$alias,
        ]);

        alert()->success('دسته بندی مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.articles.categories.index');
    }
    public function categories_edit(ArticleCategoriy $category){
        return view('admin.articles.categories.edit',compact('category'));
    }
    public function categories_update(ArticleCategoriy $category,Request $request){
        $request->validate([
            'title' => 'required|unique:article_categories,title,'.$category->id,
        ]);
        if ($request->alias==null){
            $alias=$request->title;
        }else{
            $request->validate([
                'alias' => 'required|unique:article_categories,alias,' . $category->id,
            ]);
            $alias=$request->alias;
        }
        $alias=parent::aliasCreator($alias);
        $category->update([
            'title'=>$request->title,
            'alias'=>$alias,

        ]);

        alert()->success('دسته بندی مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.articles.categories.index');
    }

    public function categories_remove(Request $request){
        $category=ArticleCategoriy::where('id',$request->category_id)->first();
        $articles=Article::where('category_id',$category->id)->get();
        if (sizeof($articles)>0){
            $items=[];
            foreach ($articles as $article){
                $item['name']=$article->title;
                $item['link']=route('admin.articles.edit',['article'=>$article->id]);
                array_push($items,$item);
            }
            $msg='مقالات زیر مربوط به این دسته‌بندی هستند.ابتدا باید مقالات مربوط به این دسته‌بندی را حذف کنید.';
            return response()->json([0,$msg,$items]);
        }
        $category->delete();
        $msg='دسته‌بندی مورد نظر با موفقیت حذف شد';
        return response()->json([1,$msg]);
    }
}
