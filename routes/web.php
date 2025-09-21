<?php

use App\Http\Controllers\Admin\GradeManagementController;
use App\Http\Controllers\Admin\GuruAssignmentController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\SiswaManagementController;
use App\Http\Controllers\Admin\StudentDataController;
use App\Http\Controllers\Guru\GradeController;
use App\Http\Controllers\Guru\ProfileController as GuruProfileController;
use App\Http\Controllers\Siswa\MyGradesController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;
use App\Http\Controllers\Siswa\DataDiriController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\SchoolProfileController;
use App\Http\Controllers\Admin\AdmissionInfoController;
use App\Http\Controllers\Admin\JurusanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\GuruManagementController;
use App\Http\Controllers\Guru\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Guru\TeachingMaterialController;
use App\Http\Controllers\Guru\AppreciationController;
use App\Http\Controllers\Guru\StudentAnnouncementController;
use App\Http\Controllers\Siswa\JadwalPelajaranController;
use App\Http\Controllers\Guru\AssignmentGivenController;
use App\Http\Controllers\Siswa\AssignmentSubmissionController;
use App\Http\Controllers\Admin\AppreciationManagementController;
use App\Http\Controllers\Admin\AdminStudentAnnouncementController;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminSppController;
use App\Http\Controllers\Admin\SppTypeController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Siswa\SppPaymentController;

use App\Http\Controllers\Guru\GuruLessonScheduleController;

use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\WaliKelas\RaportController;




Route::get('/', function () {
    return redirect()->route('admin.login');
});


Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.post');
});

Route::middleware('guest:guru')->group(function () {
    Route::get('/guru/login', [LoginController::class, 'showGuruLoginForm'])->name('guru.login');
    Route::post('/guru/login', [LoginController::class, 'guruLogin'])->name('guru.login.post');
});

Route::middleware('guest:siswa')->group(function () {
    Route::get('/siswa/login', [LoginController::class, 'showSiswaLoginForm'])->name('siswa.login');
    Route::post('/siswa/login', [LoginController::class, 'siswaLogin'])->name('siswa.login.post');
});

Route::get('/siswa/register', function() {
    return view('auth.siswa-register');
})->name('siswa.register');


Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
    Route::resource('admin-management', AdminManagementController::class)->except(['show', 'edit', 'update']);
    Route::resource('guru-management', GuruManagementController::class)->parameters(['guru-management' => 'guru',]);
    Route::resource('mata-pelajaran', MataPelajaranController::class)->parameters(['mata-pelajaran' => 'mata_pelajaran',]);
    Route::resource('kelas', KelasController::class);
    Route::resource('jurusans', JurusanController::class);
    Route::resource('siswa-management', SiswaManagementController::class);
    Route::resource('student-data', StudentDataController::class)->parameters(['student-data' => 'siswa']);
    Route::get('student-data/{siswa}/download/{documentType}', [StudentDataController::class, 'downloadDocument'])->name('student-data.download-document');
    Route::get('guru-assignments/get-mata-pelajaran-by-kelompok', [GuruAssignmentController::class, 'getMataPelajaranByKelompok'])->name('guru-assignments.getMataPelajaranByKelompok');
    Route::resource('guru-assignments', GuruAssignmentController::class)->parameters(['guru-assignments' => 'assignment',]);
    Route::resource('grade-management', GradeManagementController::class)->only(['index']);

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/delete-picture', [ProfileController::class, 'deleteProfilePicture'])->name('profile.delete-picture');

    Route::resource('announcements', AnnouncementController::class);
    Route::get('/school-profile', [SchoolProfileController::class, 'index'])->name('school-profile.index');
    Route::post('/school-profile', [SchoolProfileController::class, 'storeUpdate'])->name('school-profile.store-update');
    Route::post('/school-profile/delete-logo', [SchoolProfileController::class, 'deleteLogo'])->name('school-profile.deleteLogo');
    Route::post('/school-profile/delete-banner', [SchoolProfileController::class, 'deleteBanner'])->name('school-profile.deleteBanner');
    Route::post('school-profile/reset', [SchoolProfileController::class, 'resetProfile'])->name('school-profile.resetProfile');

    Route::resource('spp-payments', App\Http\Controllers\Admin\SppPaymentController::class);
    Route::post('spp-payments/{sppPayment}/approve', [App\Http\Controllers\Admin\SppPaymentController::class, 'approve'])->name('spp-payments.approve');
    Route::get('spp-payments/{sppPayment}/print', [App\Http\Controllers\Admin\SppPaymentController::class, 'printProof'])->name('spp-payments.print');

    Route::resource('spp-types', App\Http\Controllers\Admin\SppTypeManagementController::class);
    Route::resource('awards', AwardController::class);
    Route::resource('carousel', CarouselController::class);
    Route::resource('public-teachers', PublicTeacherController::class);
    Route::get('/admission-info', [AdmissionInfoController::class, 'index'])->name('admission-info.index');
    Route::post('/admission-info', [AdmissionInfoController::class, 'storeUpdate'])->name('admission-info.store-update');
    Route::put('/admission-info', [AdmissionInfoController::class, 'storeUpdate'])->name('admission-info.update');
    Route::get('/contact-info', [ContactInfoController::class, 'index'])->name('contact-info.index');
    Route::post('/contact-info', [ContactInfoController::class, 'storeUpdate'])->name('contact-info.store-update');
    Route::put('/contact-info', [ContactInfoController::class, 'storeUpdate'])->name('contact-info.update');
    Route::get('/footer', [FooterController::class, 'index'])->name('footer.index');
    Route::post('/footer', [FooterController::class, 'storeUpdate'])->name('footer.store-update');
    Route::put('/footer', [FooterController::class, 'storeUpdate'])->name('footer.update');
    Route::get('appreciation-management/create', [AppreciationManagementController::class, 'create'])->name('appreciation-management.create');
    Route::post('appreciation-management', [AppreciationManagementController::class, 'store'])->name('appreciation-management.store');
    Route::get('appreciation-management', [AppreciationManagementController::class, 'index'])->name('appreciation-management.index');
    Route::resource('admin-student-announcements', AdminStudentAnnouncementController::class);
    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::post('contact-messages/{contact_message}/toggle-read', [ContactMessageController::class, 'toggleReadStatus'])->name('contact-messages.toggle-read');
    Route::resource('menus', MenuController::class);
    Route::resource('facilities', FacilityController::class);

    Route::resource('news', NewsController::class);

    Route::resource('video-activities', VideoActivityController::class);
    Route::resource('home-statistics', HomeStatisticController::class)->parameters([
        'home-statistics' => 'homeStatisticId'
    ]);

    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
    Route::get('/', [App\Http\Controllers\Admin\SchoolSettingController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Admin\SchoolSettingController::class, 'store'])->name('store');
});

    Route::resource('tahun-ajaran', TahunAjaranController::class);
    Route::post('tahun-ajaran/{tahunAjaran}/toggle-active', [TahunAjaranController::class, 'toggleActive'])->name('tahun-ajaran.toggle-active');

    Route::resource('semester', SemesterController::class);
    Route::post('semester/{semester}/toggle-active', [SemesterController::class, 'toggleActive'])->name('semester.toggle-active');

    Route::get('kelas/get-by-jurusan', [KelasController::class, 'getByJurusan'])->name('kelas.getByJurusan');

});

// Rute untuk Guru (dilindungi oleh middleware 'auth:guru')
Route::middleware(['auth:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'guruDashboard'])->name('dashboard');
    Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('assignments/{assignment}/input-nilai', [GradeController::class, 'index'])->name('assignments.inputNilai'); 
    Route::get('grades/{assignment}/siswa/{siswa}/create', [GradeController::class, 'create'])->name('grades.create');
    Route::post('grades/{assignment}/siswa/{siswa}/store-akademik', [GradeController::class, 'storeAkademik'])->name('grades.storeAkademik');
    Route::post('grades/{assignment}/siswa/{siswa}/store-keterampilan', [GradeController::class, 'storeKeterampilan'])->name('grades.storeKeterampilan');
    Route::post('grades/{assignment}/siswa/{siswa}/store-sikap', [GradeController::class, 'storeSikap'])->name('grades.storeSikap');
    Route::delete('grades/akademik/{nilaiAkademik}', [GradeController::class, 'destroyAkademik'])->name('grades.destroyAkademik');
    Route::delete('grades/keterampilan/{nilaiKeterampilan}', [GradeController::class, 'destroyKeterampilan'])->name('grades.destroyKeterampilan');
    Route::delete('grades/sikap/{nilaiSikap}', [GradeController::class, 'destroySikap'])->name('grades.destroySikap');
    Route::get('assignments/{assignment}/lesson-schedules', [GuruLessonScheduleController::class, 'index'])->name('assignments.lesson_schedules.index');
    Route::get('assignments/{assignment}/lesson-schedules/create', [GuruLessonScheduleController::class, 'create'])->name('assignments.lesson_schedules.create');
    Route::post('assignments/{assignment}/lesson-schedules', [GuruLessonScheduleController::class, 'store'])->name('assignments.lesson_schedules.store');
    Route::get('assignments/{assignment}/lesson-schedules/{lesson_schedule}/fill-attendance', [GuruLessonScheduleController::class, 'show'])->name('assignments.lesson_schedules.fill_attendance');
    Route::post('assignments/{assignment}/lesson-schedules/{lesson_schedule}/store-attendance', [GuruLessonScheduleController::class, 'storeAttendance'])->name('assignments.lesson_schedules.store_attendance');
    Route::delete('attendances/{attendance}', [GuruLessonScheduleController::class, 'destroyAttendance'])->name('lesson_schedules.destroy_attendance');
    Route::get('assignments/{assignment}/attendance-summary', [GuruLessonScheduleController::class, 'attendance_summary'])->name('assignments.lesson_schedules.attendance_summary');
    Route::delete('assignments/{assignment}/lesson-schedules/{lesson_schedule}', [GuruLessonScheduleController::class, 'destroy'])->name('assignments.lesson_schedules.destroy');
    Route::prefix('wali-kelas')->name('wali-kelas.')->group(function () {
        Route::get('raports', [RaportController::class, 'index'])->name('raports.index');
        Route::get('raports/{siswa}', [RaportController::class, 'show'])->name('raports.show');
        Route::post('raports/{siswa}/presensi', [RaportController::class, 'storePresensi'])->name('raports.storePresensi');
        Route::post('raports/{siswa}/catatan', [RaportController::class, 'storeCatatan'])->name('raports.storeCatatan');
        Route::post('raports/{siswa}/rekap-nilai', [RaportController::class, 'rekapNilai'])->name('raports.rekapNilai');
        Route::post('raports/{siswa}/generate', [RaportController::class, 'generateRaport'])->name('raports.generateRaport');
        Route::post('raports/{siswa}/finalisasi', [RaportController::class, 'finalisasiRaport'])->name('raports.finalisasiRaport');
        Route::get('raports/{siswa}/print', [RaportController::class, 'printRaport'])->name('raports.print');
        Route::post('raports/{siswa}/kepala-sekolah', [RaportController::class, 'storeKepalaSekolah'])->name('raports.storeKepalaSekolah');
        Route::post('raports/{siswa}/ekstrakurikuler', [RaportController::class, 'storeEkstrakurikuler'])->name('raports.storeEkstrakurikuler');
        Route::delete('raports/ekstrakurikuler/{ekstrakurikuler}/delete', [RaportController::class, 'destroyEkstrakurikuler'])->name('raports.destroyEkstrakurikuler');
    });
    Route::resource('teaching-materials', TeachingMaterialController::class);
    Route::get('teaching-materials/{teaching_material}/download', [TeachingMaterialController::class, 'download'])->name('teaching-materials.download');
    Route::get('/appreciations', [AppreciationController::class, 'index'])->name('appreciations.index');
    Route::resource('student-announcements', StudentAnnouncementController::class);
    Route::resource('assignments-given', AssignmentGivenController::class);
    Route::get('assignments-given/{assignments_given}/download', [AssignmentGivenController::class, 'downloadFile'])->name('assignments-given.download-file');
    Route::get('assignments-given/submissions/{submission}', [AssignmentGivenController::class, 'showSubmission'])->name('assignments-given.show-submission');
    Route::put('assignments-given/submissions/{submission}/grade', [AssignmentGivenController::class, 'gradeSubmission'])->name('assignments-given.grade-submission');
    Route::get('assignments-given/submissions/{submission}/download-file', [AssignmentGivenController::class, 'downloadSubmissionFile'])->name('assignments-given.download-submission-file');
    Route::get('/assignments', [TeacherAssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/assignments/{assignment}/confirm', [TeacherAssignmentController::class, 'confirm'])->name('assignments.confirm');
    Route::get('/profile', [App\Http\Controllers\Guru\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\Guru\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/delete-picture', [App\Http\Controllers\Guru\ProfileController::class, 'deleteProfilePicture'])->name('profile.delete-picture');
});


// Rute untuk Siswa (dilindungi oleh middleware 'auth:siswa')
Route::middleware(['auth:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'siswaDashboard'])->name('dashboard');
    Route::get('/my-grades', [MyGradesController::class, 'index'])->name('my-grades.index');
    Route::get('/profile', [SiswaProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [SiswaProfileController::class, 'update'])->name('profile.update');
    Route::get('data-diri/edit', [DataDiriController::class, 'edit'])->name('data-diri.edit');
    Route::resource('data-diri', DataDiriController::class)->only(['index', 'update'])->parameters(['data-diri' => 'siswa']);
    Route::get('/jadwal-pelajaran', [JadwalPelajaranController::class, 'index'])->name('jadwal-pelajaran.index');
    Route::get('/jadwal-pelajaran/download-material/{teachingMaterial}', [JadwalPelajaranController::class, 'downloadMaterial'])->name('jadwal-pelajaran.download-material');
    Route::get('/jadwal-pelajaran/attendance-history', [JadwalPelajaranController::class, 'showAttendanceHistory'])->name('jadwal-pelajaran.attendance-history');
    Route::get('assignments-submissions', [AssignmentSubmissionController::class, 'index'])->name('assignments-submissions.index');
    Route::get('assignments-submissions/{assignmentGiven}/submit', [AssignmentSubmissionController::class, 'create'])->name('assignments-submissions.create');
    Route::post('assignments-submissions/{assignmentGiven}/submit', [AssignmentSubmissionController::class, 'store'])->name('assignments-submissions.store');
    Route::get('assignments-submissions/{submission}', [AssignmentSubmissionController::class, 'show'])->name('assignments-submissions.show');
    Route::get('assignments-submissions/{submission}/download', [AssignmentSubmissionController::class, 'downloadFile'])->name('assignments-submissions.download-file');
    
    Route::get('spp-payments', [App\Http\Controllers\Siswa\SppPaymentController::class, 'index'])->name('spp-payments.index');
    Route::get('spp-payments/{sppPayment}', [App\Http\Controllers\Siswa\SppPaymentController::class, 'show'])->name('spp-payments.show');
    Route::get('spp-payments/{sppPayment}/pay', [App\Http\Controllers\Siswa\SppPaymentController::class, 'pay'])->name('spp-payments.pay');
    Route::post('spp-payments/{sppPayment}/submit', [App\Http\Controllers\Siswa\SppPaymentController::class, 'submitPayment'])->name('spp-payments.submit');
    Route::get('spp-payments/{sppPayment}/print', [App\Http\Controllers\Siswa\SppPaymentController::class, 'printProof'])->name('spp-payments.print');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');