<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Category;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SubcategoryController
{
    /**
     * Display a listing of the subcategorys.
     */
    public function index()
    {
        try {
            $subcategories = Subcategory::paginate(10);
            return view('page.subcategory.index', compact('subcategories'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load subcategorys. Please try again.');
        }
    }

    /**
     * Show the form for creating a new subcategory.
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        return view('page.subcategory.create', compact('categories'));
    }

    /**
     * Store a newly created subcategory in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate($this->validationRules());
            
            $Subcategory = Subcategory::create($validatedData);
            
            return redirect()->route('subcategories.index')
                ->with('message', 'subcategory created successfully');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to create subcategory. Please try again.')
                ->withInput();
        }
    }


    /**
     * Show the form for editing the specified subcategory.
     */
    public function edit(string $id)
    {
        try {
            $subcategory = Subcategory::findOrFail($id);
            $categories = Category::select('id', 'name')->get();
            return view('page.subcategory.edit', compact('subcategory', 'categories'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('subcategories.index')
                ->with('error', 'subcategory not found.');
        } catch (Exception $e) {
            return back()->with('error', 'An error occurred while preparing subcategory edit.');
        }
    }

    /**
     * Update the specified subcategory in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $subcategory = Subcategory::findOrFail($id);
            
            $validatedData = $request->validate($this->validationRules());
            
            $subcategory->update($validatedData);
            
            return redirect()->route('subcategories.index')
                ->with('message', 'subcategory updated successfully');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('subcategories.index')
                ->with('error', 'subcategory not found.');
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to update subcategory. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified subcategory from storage.
     */
    public function destroy(string $id)
    {
        try {
            $subcategory = Subcategory::findOrFail($id);
            $subcategory->delete();
            
            return redirect()->route('subcategories.index')
                ->with('message', 'subcategory deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('subcategories.index')
                ->with('error', 'subcategory not found.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete subcategory. Please try again.');
        }
    }

    /**
     * Validation rules for storing and updating subcategorys.
     */
    private function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:4',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}

