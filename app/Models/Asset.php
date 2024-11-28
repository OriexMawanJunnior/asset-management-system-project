<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class Asset extends Model
{
    protected $table = 'assets';

    protected const LOCATION_CODE = 'HQR'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset_id',
        'name',
        'merk',
        'color',
        'serial_number',
        'purchase_order_number',
        'purchase_price',
        'quantity',
        'condition',
        'status',
        'remarks', 
        'location',
        'asset_detail_url',
        'qr_code_path',
        'date_of_receipt',
        'number',
        'category_id',  
        'subcategory_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_price' => 'float',
        'date_of_receipt' => 'date',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory(){
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function borrowings(){
        return $this->hasMany(Borrowing::class);
    }

    private static function monthToRoman($month)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
            6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X',
            11 => 'XI', 12 => 'XII'
        ];
        return $romans[(int)$month] ?? '';
    }

    public static function generateAssetId($asset)
    {
        $locationCode = self::LOCATION_CODE;
        $se = 'SE';
        $categoryCode = $asset->category?->code ?? 'NNN';
        $subCategoryCode = $asset->subcategory?->code ?? 'NNN';

        // Handling date_of_receipt null case
        $receiptDate = $asset->date_of_receipt ?? now();
        $parsedDate = Carbon::parse($receiptDate);
        
        // Fix: Ensure we're getting the numeric month and converting it properly
        $receiptMonth = self::monthToRoman($parsedDate->format('n')); // Using 'n' for numeric month without leading zeros
        $receiptMonthWithoutFormat = $parsedDate->format('m');
        $receiptYear = $parsedDate->format('Y');

        // Find last asset in the same category and subcategory
        $lastAsset = self::where('category_id', $asset->category_id)
                        ->where('subcategory_id', $asset->subcategory_id)
                        ->whereMonth('date_of_receipt', $receiptMonthWithoutFormat)
                        ->whereYear('date_of_receipt', $receiptYear)
                        ->orderBy('number', 'desc')
                        ->first();

        // Determine next number in sequence
        $nextNumber = $lastAsset ? $lastAsset->number + 1 : 1;
        $asset->number = $nextNumber;

        // Format asset_id with explicit separator handling
        $sequence = str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        // Fix: Ensure proper formatting with Roman numeral month
        $assetId = "{$locationCode}/{$categoryCode}-{$subCategoryCode}/{$receiptMonth}/{$receiptYear}/{$se}/{$sequence}";

        return $assetId;
    }

    public function generateQrCode()
    {
        // Generate asset detail URL
        $assetUrl = route('assets.show', $this->id);
        $assetId = str_replace('/','_', $this->asset_id);
        // Generate a clean filename without any slashes
        $qrFilename = 'qr_asset_' . $assetId . '.png';
        
        // Create directory if it doesn't exist
        $directoryPath = public_path('qrcodes');
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        // Generate QR code
        $qrImage = QrCode::format('png')
                        ->size(300)
                        ->margin(1)
                        ->backgroundColor(255, 255, 255)
                        ->generate($assetUrl);

        // Save the file with a clean path
        $fullPath = $directoryPath . DIRECTORY_SEPARATOR . $qrFilename;
        file_put_contents($fullPath, $qrImage);
        
        // Store only the filename in the database
        $this->update(['qr_code_path' => $qrFilename]);
        
        return $qrFilename;
    }

    public function getQrCodePath()
    {
        if (empty($this->qr_code_path) || !file_exists(public_path('qrcodes/' . $this->qr_code_path))) {
            return $this->generateQrCode();
        }
        
        return $this->qr_code_path;
    }



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {
            if (empty($asset->asset_id)) {
                $asset->asset_id = self::generateAssetId($asset);
            }
        });
        static::updating(function($asset){
            $categoryChanged = $asset->isDirty('category_id');
            $subcategoryChanged = $asset->isDirty('subcategory_id');

            $newDateOfReceipt = Carbon::parse($asset->getAttribute('date_of_receipt'));
            $oldDateOfReceipt = Carbon::parse($asset->getOriginal('date_of_receipt'));
    
            $dateChanged = $newDateOfReceipt->month !== $oldDateOfReceipt->month || 
                           $newDateOfReceipt->year !== $oldDateOfReceipt->year;
            
            if ($categoryChanged || $subcategoryChanged || $dateChanged) {
                $asset->asset_id = self::generateAssetId($asset);
                if ($asset->qr_code_path && file_exists(public_path($asset->qr_code_path))) {
                    unlink(public_path($asset->qr_code_path));
                }
            }
        });
        static::deleting(function ($asset) {
            // Delete QR code file when asset is deleted
            if ($asset->qr_code_path && file_exists(public_path($asset->qr_code_path))) {
                unlink(public_path($asset->qr_code_path));
            }
        });
    }
}
