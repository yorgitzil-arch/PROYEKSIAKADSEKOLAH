<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SppPayment;
use App\Models\Siswa;
use App\Models\SppType;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SchoolProfile;
use App\Models\SchoolSetting;

class SppPaymentController extends Controller
{
    public function index(Request $request)
    {
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        
        $payments = SppPayment::with(['siswa', 'sppType', 'admin', 'tahunAjaran', 'semester'])
            ->orderBy('created_at', 'desc')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->paginate(15);

        return view('admin.spp-payments.index', compact('payments', 'schoolProfile'));
    }

    public function create()
    {
        $siswas = Siswa::with('sppType')->orderBy('name')->get();
        $sppTypes = SppType::orderBy('name')->get();
        $tahunAjarans = TahunAjaran::orderBy('nama')->get();
        $semesters = Semester::orderBy('nama')->get();
        $schoolProfile = SchoolProfile::firstOrCreate([]);

        return view('admin.spp-payments.create', compact('siswas', 'sppTypes', 'tahunAjarans', 'semesters', 'schoolProfile'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'spp_type_id' => 'required|exists:spp_types,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'semester_id' => 'required|exists:semesters,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,belum lunas',
            'notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            SppPayment::create([
                'siswa_id' => $request->siswa_id,
                'admin_id' => $request->status === 'lunas' ? auth()->guard('admin')->id() : null, // Hanya isi admin_id jika status lunas
                'spp_type_id' => $request->spp_type_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                'semester_id' => $request->semester_id,
                'amount' => $request->amount,
                'status' => $request->status,
                'payment_date' => $request->status === 'lunas' ? now() : null, 
                'notes' => $request->notes,
            ]);

            DB::commit();
            return redirect()->route('admin.spp-payments.index')->with('success', 'Pembayaran SPP berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(SppPayment $sppPayment)
    {
        $schoolProfile = SchoolProfile::firstOrCreate([]);
        $sppPayment->load(['siswa.kelas', 'siswa.jurusan', 'sppType', 'admin', 'tahunAjaran', 'semester']);
        return view('admin.spp-payments.show', compact('sppPayment', 'schoolProfile'));
    }

    public function approve(SppPayment $sppPayment)
    {
        $sppPayment->update([
            'status' => 'lunas',
            'payment_date' => now(),
            'admin_id' => auth()->guard('admin')->id(),
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi dan status diubah menjadi Lunas.');
    }

    public function printProof(SppPayment $sppPayment)
    {
        $sppPayment->load(['siswa.kelas', 'siswa.jurusan', 'sppType', 'admin']);
        $schoolSetting = SchoolSetting::first();
        
        $data = [
            'sppPayment' => $sppPayment,
            'schoolSetting' => $schoolSetting,
        ];
        
        $pdf = Pdf::loadView('admin.spp-payments.proof', $data);
        return $pdf->download('bukti-pembayaran-' . $sppPayment->id . '.pdf');
    }

    public function destroy(SppPayment $sppPayment)
    {
        $sppPayment->delete();
        return redirect()->route('admin.spp-payments.index')->with('success', 'Pembayaran berhasil dihapus.');
    }
}