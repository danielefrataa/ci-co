<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\DutyOfficer;  // Pastikan model DutyOfficer di-import
use Illuminate\Support\Facades\Log;

class DutyOfficerController extends Controller
{
    public function storeDutyOfficer(Request $request)
    {
        // Validasi input Duty Officer
        Log::info('Masuk ke method storeDutyOfficer');
        Log::info('id_booking: ' . $request->id_booking);
        Log::info('duty_officer_id: ' . $request->duty_officer_id);
        
        $request->validate([
            'id_booking' => 'required|string|exists:absen,id_booking',  // Validasi id_booking
            'duty_officer_id' => 'required|integer|exists:duty_officer,id',
            
        ]);
        try {
            Log::info('Mencari data booking dengan id_booking: ' . $request->id_booking);
            $absen = Absen::where('id_booking', $request->id_booking)->first();
        
            if (!$absen) {
                Log::warning('Booking tidak ditemukan untuk id_booking: ' . $request->id_booking);
                return redirect()->back()->with('gagal', 'Data booking tidak ditemukan.');
            }
            Log::info('Data booking ditemukan: ' . json_encode($absen));
        
            Log::info('Mencari Duty Officer dengan id: ' . $request->duty_officer_id);
            $dutyOfficer = DutyOfficer::find($request->duty_officer_id);
        
            if (!$dutyOfficer) {
                Log::warning('Duty Officer tidak ditemukan untuk id: ' . $request->duty_officer_id);
                return redirect()->back()->with('gagal', 'Duty Officer tidak ditemukan.');
            }
            Log::info('Duty Officer ditemukan: ' . json_encode($dutyOfficer));
        
            Log::info('Mengupdate Duty Officer untuk booking_id: ' . $request->id_booking . ' dengan nama: ' . $dutyOfficer->nama_do);
            $absen->update([
                'duty_officer' => $dutyOfficer->nama_do,
            ]);
        
            Log::info('Duty Officer berhasil disimpan untuk booking_id: ' . $request->id_booking);
            return redirect()->back()->with('success', 'Duty Officer berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Duty Officer: ' . $e->getMessage());
            return redirect()->back()->with('gagal', 'Terjadi kesalahan saat menyimpan Duty Officer.');
        }        
    }
}
