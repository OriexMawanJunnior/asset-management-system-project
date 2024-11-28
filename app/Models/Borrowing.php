<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $table = 'borrowings'; 

    protected $fillable = [
        'date_of_receipt',
        'date_of_return',
        'status',
        'asset_id',
        'employee_id',
    ];

    protected $casts = [
        'date_of_receipt' => 'date',
        'date_of_return' => 'date',
    ];

    // Definisi status yang valid
    const STATUS_BORROWED = 'borrowed';
    const STATUS_RETURNED = 'returned';
    const STATUS_LATE = 'late';

    // Array status untuk validasi yang lebih mudah
    public const STATUSES = [
        self::STATUS_BORROWED,
        self::STATUS_RETURNED,
        self::STATUS_LATE,
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function booted()
    {
        static::updating(function ($borrowing) {
            // Update asset status ketika status berubah ke returned
            if ($borrowing->isDirty('status') && $borrowing->status === self::STATUS_RETURNED) {
                $borrowing->markAssetAsAvailable();
            }

            // Cek dan update status late jika kondisi dipenuhi
            $borrowing->checkIfLate();
        });
    }

    /**
     * Mark asset as available and update location.
     */
    protected function markAssetAsAvailable()
    {
        $this->asset()->update([
            'location' => 'HRGA',
            'status' => 'available'
        ]);
    }

    /**
     * Check if the borrowing is late based on the return date.
     */
    protected function checkIfLate()
    {
        if ($this->status === self::STATUS_BORROWED &&
            $this->date_of_return &&
            Carbon::now()->greaterThan($this->date_of_return)) {
            $this->status = self::STATUS_LATE;
        }
    }
}
