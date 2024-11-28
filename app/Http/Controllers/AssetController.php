<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Subcategory;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;


class AssetController
{
    public function index()
    {
        $assets = Asset::paginate(10); 
        return view('page.asset.index', compact('assets'));
    }

    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        $subcategories = Subcategory::select('id', 'name', 'category_id')->get();
        return view('page.asset.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate($this->validationRules());
            $asset = Asset::create($validatedData);
            $asset->generateQrCode();
            return redirect()->route('assets.index')
                ->with('message', 'Asset created successfully');
        } catch (Exception $e){
            throw ValidationException::withMessages([
                'error' => ['Failed to create asset. Please try again']
            ]);
        }
    }

    public function show(int $id)
    {
        try{
            $asset = Asset::FindOrFail($id);
            return view('page.asset.show', compact('asset'));
        } catch (ModelNotFoundException $e){
            throw ValidationException::withMessages([
                'error' => ['Asset not found']
            ]);
        }
    }

    public function edit(int $id)
    {
        try{
            $asset = Asset::FindOrFail($id);
            $categories = Category::select('id', 'name')->get();
            $subcategories = Subcategory::select('id', 'name', 'category_id')->get();
            return view('page.asset.edit', compact('asset', 'categories', 'subcategories'));
        } catch (ModelNotFoundException $e){
            throw ValidationException::withMessages([
                'error' => ['Asset not found']
            ]);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $validatedData = $request->validate($this->validationRules());
            $asset->update($validatedData);
            $asset->generateQrCode();
            return redirect()->route('assets.index')
                ->with('message', 'Asset updated successfully');
        } catch (ModelNotFoundException $e){
            throw ValidationException::withMessages([
                'error' => ['Asset not found']
            ]);
        } catch (Exception $e){
            throw ValidationException::withMessages([
                'error' => ['Failed to update asset. Please try again.']
            ]);
        }
    }

    public function destroy(int $id)
    {
        try {
            $asset = Asset::findOrFail($id);
            
            $asset->delete();
            
            return redirect()->route('assets.index')
                ->with('message', 'Asset deleted successfully');
        } catch (ModelNotFoundException $e) {
            throw ValidationException::withMessages([
                'error' => ['Asset not found.']
            ]);
        } catch (QueryException $e) {
            throw ValidationException::withMessages([
                'error' => [
                    'Failed to delete asset due to database constraints.', 
                    'please delete the borrowing history related to the asset first'
                    ]
            ]);
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'error' => ['Failed to delete asset.']
            ]);
        }
    }

    public function downloadQr($id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $qrPath = $asset->getQrCodePath();
            
            if (!file_exists(public_path('qrcodes/' . $qrPath))) {
                throw ValidationException::withMessages([
                    'error' => ['QR code file not found.']
                ]);
            }
            
            return response()->download(
                public_path('qrcodes/' . $qrPath),
                $qrPath,
                [
                    'Content-Type' => 'image/png',
                    'Content-Disposition' => 'attachment'
                ]
            );
        } catch (ModelNotFoundException $e) {
            throw ValidationException::withMessages([
                'error' => ['Asset not found.']
            ]);
        }
    }

    private function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:50',
            'serial_number' => 'nullable|string|max:100',
            'purchase_order_number' => 'nullable|string|max:100',
            'purchase_price' => 'required|numeric',
            'condition' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'remarks' => 'nullable|string',
            'location' => 'required|string|max:255',
            'qr_code_path' => 'nullable|string|max:255',
            'date_of_receipt' => 'required|date',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'required|integer|exists:subcategories,id',
            'number' => 'nullable|integer'
        ];
    }
}
