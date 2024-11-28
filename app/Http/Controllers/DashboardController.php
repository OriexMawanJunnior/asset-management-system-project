<?php

namespace App\Http\Controllers;

use App\Models\Asset;

class DashboardController
{
    public function showDashboard(){
        $hrgaAsset = Asset::where('location', 'HRGA')->count();
        $nonhrgaAsset = Asset::where('location','!=', 'HRGA')->count();
        $totalAsset = Asset::count();

        return view('page.dashboard', compact('hrgaAsset', 'nonhrgaAsset', 'totalAsset'));
    }
}
