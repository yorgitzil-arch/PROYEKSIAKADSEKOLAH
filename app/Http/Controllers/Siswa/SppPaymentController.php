<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\SppPayment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Storage;

class SppPaymentController extends Controller
{
    public function index()
    {
        $siswaId = auth()->guard('siswa')->id();
        $schoolProfile = SchoolProfile::firstOrCreate([]);

        $payments = SppPayment::where('siswa_id', $siswaId)
            ->with(['sppType', 'admin', 'tahunAjaran', 'semester'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('siswa.spp-payments.index', compact('payments', 'schoolProfile'));
    }

    public function pay(SppPayment $sppPayment)
    {
        if ($sppPayment->siswa_id !== auth()->guard('siswa')->id() || $sppPayment->status !== 'belum lunas') {
            abort(403, 'Akses Ditolak.');
        }

        $sppPayment->load(['sppType', 'tahunAjaran', 'semester']);
        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('siswa.spp-payments.pay', compact('sppPayment', 'schoolProfile'));
    }

    public function submitPayment(Request $request, SppPayment $sppPayment)
    {
        if ($sppPayment->siswa_id !== auth()->guard('siswa')->id() || $sppPayment->status !== 'belum lunas') {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'proof_of_payment' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $path = $request->file('proof_of_payment')->store('spp_proofs', 'public');

        $sppPayment->update([
            'status' => 'menunggu konfirmasi',
            'proof_path' => $path,
            'payment_date' => now(),
        ]);

        return redirect()->route('siswa.spp-payments.index')->with('success', 'Bukti pembayaran berhasil diunggah. Pembayaran Anda akan segera dikonfirmasi oleh admin.');
    }

    public function show(SppPayment $sppPayment)
    {
        if ($sppPayment->siswa_id !== auth()->guard('siswa')->id()) {
            abort(403, 'Akses Ditolak.');
        }

        $sppPayment->load(['sppType', 'admin', 'tahunAjaran', 'semester']);
        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('siswa.spp-payments.show', compact('sppPayment', 'schoolProfile'));
    }
    
    public function printProof(SppPayment $sppPayment)
    {
        if ($sppPayment->siswa_id !== auth()->guard('siswa')->id()) {
            abort(403, 'Akses Ditolak.');
        }

        $sppPayment->load(['siswa.kelas', 'siswa.jurusan', 'sppType', 'admin']);
        
        $data = [
            'sppPayment' => $sppPayment,
        ];
        
        $pdf = Pdf::loadView('siswa.spp-payments.proof', $data);
        return $pdf->download('bukti-pembayaran-' . $sppPayment->id . '.pdf');
    }
}