<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use RuntimeException;
use Illuminate\Support\Facades\Cache;

class DocumentNumberGenerator
{
    private string $counterKey;
    private string $lockKey;
    
    public function __construct()
    {
        $this->counterKey = 'document:counter';
        $this->lockKey = 'document:counter:lock';
    }
    
    public function generateNumber(): string
    {
        $lock = Cache::lock($this->lockKey, 10);
        
        try {
            $lock->block(5);
            
            // Gunakan Redis untuk counter
            $counter = Redis::incr($this->counterKey);
            
            // Format counter dengan leading zeros
            $formattedCounter = str_pad($counter, 4, '0', STR_PAD_LEFT);
            
            // Ambil informasi bulan dan tahun
            $now = Carbon::now();
            $romanMonth = $this->numberToRoman($now->format('n'));
            $year = $now->format('Y');
            
            // Buat nomor dokumen
            $documentNumber = "{$formattedCounter}/SPTTA/PPT-HRGA/{$romanMonth}/{$year}";
            
            return $documentNumber;
            
        } catch (\Exception $e) {
            throw new RuntimeException('Failed to generate document number: ' . $e->getMessage());
        } finally {
            optional($lock)->release();
        }
    }
    
    private function numberToRoman(int $number): string
    {
        return match($number) {
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
            default => throw new RuntimeException('Invalid month number'),
        };
    }
    
    public function resetCounter(): void
    {
        $lock = Cache::lock($this->lockKey, 10);
        
        try {
            $lock->block(5);
            Redis::set($this->counterKey, 0);
        } catch (\Exception $e) {
            throw new RuntimeException('Failed to reset counter: ' . $e->getMessage());
        } finally {
            optional($lock)->release();
        }
    }
}