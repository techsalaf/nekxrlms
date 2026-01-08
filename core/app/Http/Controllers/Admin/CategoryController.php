<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle  = 'All Categories';
        $categories = Category::searchable(['name'])->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function save(Request $request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'name'        => 'required',
            'description' => 'required',
            'image'       => [$imageValidation, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if ($id) {
            $category     = Category::findOrFail($id);
            $notification = 'Category updated successfully';
        } else {
            $category     = new Category();
            $notification = 'Category added successfully';
        }

        if ($request->hasFile('image')) {
            try {
                $old             = $category->image;
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $category->name        = $request->name;
        $category->description = $request->description;
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Category::changeStatus($id);
    }

    public function feature($id)
    {
        $category           = Category::findOrFail($id);
        $category->featured = !$category->featured;
        $category->save();

        $notification = $category->featured ? 'featured' : 'unfeatured';
        $notify[]     = ['success', "Category $notification successfully"];
        return back()->withNotify($notify);
    }
    public function toggleShowOnBanner($id)
    {
        $category                   = Category::findOrFail($id);
        $category->show_on_banner   = !$category->show_on_banner;
        $category->save();

        $notification = $category->show_on_banner ? 'shown on' : 'hidden from';
        $notify[]     = ['success', "Category $notification banner successfully"];
        return back()->withNotify($notify);
    }
}
