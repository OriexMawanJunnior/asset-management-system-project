<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController
{
    /**
     * Display a listing of the categorys.
     */
    public function index()
    {
       
        try {
            $categories = Category::paginate(10);
            return view('page.categories.index', compact('categories'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load categorys. Please try again.');
        }
        
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('page.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate($this->validationRules());
            
            $category = Category::create($validatedData);
            
            return redirect()->route('categories.index')
                ->with('message', 'category created successfully');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to create category. Please try again.')
                ->withInput();
        }
    }


    /**
     * Show the form for editing the specified category.
     */
    public function edit(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('page.categories.edit', compact('category'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('categories.index')
                ->with('error', 'category not found.');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while preparing category edit.');
        }
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            
            $validatedData = $request->validate($this->validationRules());
            
            $category->update($validatedData);
            
            return redirect()->route('categories.index')
                ->with('message', 'category updated successfully');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('categories.index')
                ->with('error', 'category not found.');
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to update category. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            
            return redirect()->route('categories.index')
                ->with('message', 'category deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('categories.index')
                ->with('error', 'category not found.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete category. Please try again.');
        }
    }

    /**
     * Validation rules for storing and updating categorys.
     */
    private function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:4',
            'remarks' => 'required|string|max:255',
        ];
    }
}
