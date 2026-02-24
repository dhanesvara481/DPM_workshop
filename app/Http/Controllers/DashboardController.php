<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getTampilanDashboard()
    {
        //View dashboard
        return view('admin.dashboard.tampilan_dashboard', [
        'barangs' => [],
    ]);
    }
    

    // Staff controller
    // Tampilan Dashboard Staff
    public function getTampilanDashboardStaff()
    {
        //View dashboard staff
        return view('staff.dashboard.tampilan_dashboard_staff', [
            'barangs' => [],
    ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
