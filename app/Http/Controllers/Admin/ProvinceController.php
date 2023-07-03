<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{

    public function index()
    {
        $provinces = Province::paginate(40);
        return view('admin.provinces.index', compact('provinces'));
    }


    public function create()
    {
        return view('admin.provinces.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:provinces,name',
        ]);

        Province::create([
            'name' => $request->name,
        ]);

        alert()->success('استان مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.provinces.index');
    }


    public function edit(Province $province)
    {
        return view('admin.provinces.edit', compact('province'));
    }


    public function update(Request $request, Province $province)
    {
        $request->validate([
            'name' => 'required|unique:provinces,name,' . $province->id,
        ]);


        $province->update([
            'name' => $request->name,
        ]);

        alert()->success('استان مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.provinces.index');
    }


    public function province_remove(Request $request)
    {
        $province = Province::where('id', $request->id)->first();
        $province->delete();
        $msg = 'استان با موفقیت حذف شد';
        return response()->json([1, $msg]);
    }

    //
    public function cities_index(Province $province)
    {
        $cities = $province->Cities;
        return view('admin.provinces.cities.index', compact('province', 'cities'));
    }

    public function city_create(Province $province)
    {
        return view('admin.provinces.cities.create', compact('province'));
    }

    public function city_store(Request $request, $province)
    {
        $request->validate([
            'name' => 'required|unique:cities,name',
        ]);

        City::create([
            'name' => $request->name,
            'province_id' => $province,
        ]);

        alert()->success('شهر مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.cities.index', ['province' => $province]);
    }

    public function city_edit(City $city)
    {
        return view('admin.provinces.cities.edit', compact('city'));
    }

    public function city_update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $city->update([
            'name' => $request->name,
        ]);
        alert()->success('شهر مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.cities.index', ['province' => $city->province_id]);
    }

    public function city_remove(Request $request)
    {
        $city = City::where('id', $request->id)->first();
        $city->delete();
        $msg = 'شهر با موفقیت حذف شد';
        return response()->json([1, $msg]);
    }
}
