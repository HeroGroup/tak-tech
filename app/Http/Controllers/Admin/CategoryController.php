<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();
            return view('admin.categories', compact('categories'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function store(Request $request)
    {
        try {
            // TODO: sanitize
            // TODO: validation
            Category::create([
                'title' => $request->title,
            ]);

            return back()->with('message', 'new category created successfully.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // TODO: sanitize
            // TODO: validation
            $category = Category::find($id);
            $category->title = $request->title;
            $category->is_active = $request->is_active ? 1 : 0;
            $category->save();

            return back()->with('message', 'category updated successfully.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function destroy($id)
    {
        try {
            Category::destroy($id);
            return $this->success('Category removed successfully.');
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }
}
