<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Employee;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Services\DocumentNumberGenerator;
use Carbon\Carbon;
use Exception;

class BorrowingController
{
    public function index()
    {
        try {
            $borrowings = Borrowing::paginate(10);
            return view('page.borrowing.index', compact('borrowings'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load borrowings. Please try again.');
        }
    }

    public function create()
    {
        try {
            $assets = Asset::select('id', 'asset_id', 'name')->get();
            $employees = Employee::select('id', 'name')->get();
            return view('page.borrowing.create', compact('assets', 'employees'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to prepare borrowing form. Please try again.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate($this->validationRules());

            // Check if asset is available
            $asset = Asset::findOrFail($validatedData['asset_id']);
            if ($asset->status === 'borrowed') {
                throw ValidationException::withMessages([
                    'asset_id' => 'This asset is already borrowed and cannot be borrowed again.'
                ]);
            }

            DB::transaction(function () use ($validatedData) {
                $borrowing = Borrowing::create($validatedData);

                $asset = Asset::findOrFail($validatedData['asset_id']);
                $employee = Employee::findOrFail($validatedData['employee_id']);
                
                $asset->update([
                    'location' => $employee->name,
                    'status' => 'borrowed'
                ]);
            });

            return redirect()->route('borrowings.index')
                ->with('message', 'Borrowing created successfully for asset: ' . $validatedData['asset_id']);
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to create borrowing. Please try again.')
                ->withInput();
        }
    }

    public function show(int $id)
    {
        try {
            $borrowing = Borrowing::findOrFail($id);
            return view('page.borrowing.show', compact('borrowing'));
        } catch (Exception $e) {
            return redirect()->route('borrowings.index')
                ->with('error', 'Borrowing not found or an error occurred.');
        }
    }

    public function edit(int $id)
    {
        try {
            $borrowing = Borrowing::findOrFail($id);
            $assets = Asset::select('id', 'asset_id', 'name')->get();
            $employees = Employee::select('id', 'name')->get();
            return view('page.borrowing.edit', compact('borrowing', 'assets', 'employees'));
        } catch (Exception $e) {
            return redirect()->route('borrowings.index')
                ->with('error', 'Failed to load borrowing edit form.');
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $borrowing = Borrowing::findOrFail($id);

            // Check if already returned
            if ($borrowing->status === 'returned') {
                return redirect()->route('borrowings.index')
                    ->with('message', 'Asset with ID: ' . $borrowing->asset->asset_id . ' has already been returned');
            }

            $validatedData = $request->validate($this->validationRules());

            DB::transaction(function () use ($borrowing, $validatedData) {
                $borrowing->update($validatedData);

                // Update asset status if returned
                if ($validatedData['status'] === 'returned') {
                    $asset = $borrowing->asset;
                    $asset->update([
                        'location' => 'inventory',
                        'status' => 'available'
                    ]);
                }
            });

            return redirect()->route('borrowings.index')
                ->with('message', $borrowing->employee->name . ' has returned asset with asset id: ' . $borrowing->asset->asset_id);
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return back()
                ->with('error', 'Failed to update borrowing. Please try again.')
                ->withInput();
        }
    }

    public function destroy(int $id)
    {
        try {
            $borrowing = Borrowing::findOrFail($id);
            
            DB::transaction(function () use ($borrowing) {
                // Reset asset status if borrowing is deleted
                $asset = $borrowing->asset;
                $asset->update([
                    'location' => 'inventory',
                    'status' => 'available'
                ]);

                $borrowing->delete();
            });

            return redirect()->route('borrowings.index')
                ->with('message', 'Borrowing deleted successfully');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete borrowing. Please try again.');
        }
    }

    public function downloadDocument(int $id)
    {
        try {
            // Load template
            $borrowing = Borrowing::findOrFail($id);

            $templatePath = storage_path('app/templates/asset_template.docx');
            $templateProcessor = new TemplateProcessor($templatePath);
            $generator = new DocumentNumberGenerator();

            // Format current date
            $currentDate = Carbon::now()->locale('id')->isoFormat('D MMMM Y');

            // Get related data
            $asset = $borrowing->asset;
            $employee = $borrowing->employee;

            $documentNumber = $generator->generateNumber();

            // Replace placeholders in the document
            $templateProcessor->setValue('no_dokumen', $documentNumber);
            $templateProcessor->setValue('tanggal', $currentDate);
            
            
            // Pihak Kedua (Employee)
            $templateProcessor->setValue('nama_karyawan', $employee->name);
            $templateProcessor->setValue('jabatan_karyawan', $employee->job_position);
            
            // Asset Details
            $templateProcessor->setValue('jenis_aset', $asset->name);
            $templateProcessor->setValue('no_asset', $asset->asset_id);
            $templateProcessor->setValue('serial_number', $asset->serial_number);

            // Save the document to temporary file
            $fileName = 'Surat_Tanda_Terima_Aset_' . $employee->name . '.docx';
            $tempPath = storage_path('app/temp/' . $fileName);
            $templateProcessor->saveAs($tempPath);

            // Return the file as download
            return response()->download($tempPath, $fileName)->deleteFileAfterSend();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghasilkan dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validationRules(): array
    {
        return [
            'date_of_receipt' => 'required|date|before_or_equal:today',
            'date_of_return' => 'nullable|date|after:date_of_receipt',
            'status' => 'required|in:borrowed,returned,late',
            'asset_id' => 'required|exists:assets,id',
            'employee_id' => 'required|exists:employees,id',
        ];
    }
}