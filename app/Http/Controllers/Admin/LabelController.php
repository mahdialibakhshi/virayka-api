<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Product;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::latest()->paginate(20);
        return view('admin.labels.index', compact('labels'));
    }

    public function create()
    {
        return view('admin.labels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:labels,name',
            'color' => 'required',
        ]);


        Label::create([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        alert()->success('برچسب مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.labels.index');
    }

    public function edit(Label $label)
    {
        return view('admin.labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        $request->validate([
            'name' => 'required|unique:labels,name,'.$label->id,
            'color' => 'required',
        ]);

        $label->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        alert()->success('برچسب مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.labels.index');
    }

    public function remove(Request $request){
        $label_id=$request->label_id;
        $label=Label::findOrFail($label_id);
        $products=Product::where('label',$label_id)->get();
        if (sizeof($products)){
            $msg='کالاهای زیر دارای این برچسب هستند.ابتدا باید برچسب مورد نظر را از کالاهای زیر را حذف کنید.';
            $items=[];
            foreach ($products as $product){
                $item['name']=$product->name;
                $item['link']=route('admin.products.edit',['product'=>$product->id]);
                array_push($items,$item);
            }
            return response()->json([0,$msg,$items]);
        }
        $label->delete();
        $msg='برچسب با موفقیت حذف شد';
        return response()->json([1,$msg]);
    }


}
