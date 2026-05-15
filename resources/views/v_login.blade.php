@extends('theme.wisatawan')

@section('content')
    @include('theme.navbar')

    <section class="hero-slanted" style="min-height: 100vh; display: flex; align-items: center; padding-top: 50px; padding-bottom: 50px;">
        <div class="container px-4" style="position: relative; z-index: 5;">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8 col-sm-10 animate-fade-up">

                    <div class="text-center mb-4">
                        <div class="icon-kesehatan mx-auto mb-3 d-flex align-items-center justify-content-center animasi-jantung" style="width: 80px; height: 80px;">
                            <i class="fas fa-heartbeat fa-2x text-hnb-orange"></i>
                        </div>
                        <h3 class="font-weight-bold text-white teks-judul pb-1">Selamat Datang Kembali</h3>
                        <p class="text-white-50 teks-subjudul" style="font-size: 0.95rem;">WanderMed - Sistem Manajemen Ekosistem Sehat</p>
                    </div>

                    <div class="glass-premier shadow-lg border-0 radius-hnb overflow-hidden" style="border: 1px solid rgba(255,255,255,0.1); border-top: 4px solid var(--hnb-orange); border-radius: 16px;">
                        <div class="card-body p-4 p-md-5">

                            @if(session('error'))
                                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4"
                                     style="border-radius: 10px; background: rgba(231,74,59,0.15); border: 1px solid rgba(231,74,59,0.4); color: #ff6b6b; font-size: 13px;">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert d-flex align-items-center gap-2 mb-4"
                                     style="border-radius: 10px; background: rgba(28,200,138,0.15); border: 1px solid rgba(28,200,138,0.4); color: #1cc88a; font-size: 13px;">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf

                                <div class="form-group mb-4">
                                    <label class="text-white font-weight-bold ml-1 mb-2" style="font-size: 0.9rem; opacity: 0.9;">Email Akun</label>
                                    <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" name="email" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4 @error('email') is-invalid @enderror" placeholder="Masukkan email aktif Anda..." value="{{ old('email') }}" required autocomplete="email" maxlength="100" style="font-size: 1.05rem;">
                                    </div>
                                    @error('email')
                                        <small class="text-danger ml-1 mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group mb-5">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="text-white font-weight-bold ml-1 mb-0" style="font-size: 0.9rem; opacity: 0.9;">Kata Sandi</label>
                                        <a href="#" onclick="forgotPassword(event)" class="text-hnb-orange text-decoration-none hover-orange" style="font-size: 0.85rem;">Lupa Password?</a>
                                    </div>
                                    <div class="input-group" style="border-radius: 12px; overflow: hidden; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1);">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-0 bg-transparent text-white-50 px-3"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="password" id="inputPassword" class="form-control border-0 bg-transparent text-white shadow-none px-2 py-4" placeholder="Ketik kata sandi Anda..." required autocomplete="current-password" maxlength="50" style="font-size: 1.05rem;">
                                        <div class="input-group-append">
                                            <button type="button" id="btnToggle" class="btn border-0 text-white-50 shadow-none px-3" style="background: transparent;">
                                                <i class="fas fa-eye" id="ikonMata"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <small class="text-danger ml-1 mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex flex-column gap-3 mt-2">
                                    <button type="submit" class="btn btn-hnb-orange py-3 font-weight-bold shadow-lg mb-3" style="border-radius: 10px; font-size: 1.1rem;">
                                        Masuk <i class="fas fa-sign-in-alt ml-2"></i>
                                    </button>

                                    <a href="/" class="btn btn-link text-white-50 text-decoration-none text-center hover-orange" style="font-size: 0.9rem;">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-5">
                         <p class="text-white-50 mb-0" style="font-size: 11px; letter-spacing: 1.5px;">
                            DIKEMBANGKAN OLEH <br>
                            <span class="font-weight-bold text-white">HEAR & BUILD STUDIO</span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @include('theme.footer')
@endsection

@push('scripts')
<script>
    async function forgotPassword(e) {
        e.preventDefault();
        
        const { value: formValues } = await Swal.fire({
            title: 'Reset Password via PIN',
            html: `
                <div style="text-align: left; margin-top: 10px;">
                    <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">Email Akun</label>
                    <input id="swal-email" class="swal2-input" placeholder="contoh@gmail.com" style="width: 100%; margin: 0 0 15px 0; box-sizing: border-box;">
                    
                    <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">6-Digit PIN Pemulihan</label>
                    <input id="swal-pin" class="swal2-input" placeholder="Misal: 849201" maxlength="6" style="width: 100%; margin: 0 0 15px 0; box-sizing: border-box;">
                    
                    <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">Password Baru</label>
                    <input id="swal-pass" type="password" class="swal2-input" placeholder="Minimal 8 karakter" style="width: 100%; margin: 0 0 15px 0; box-sizing: border-box;">
                    
                    <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">Konfirmasi Password Baru</label>
                    <input id="swal-pass-conf" type="password" class="swal2-input" placeholder="Ulangi password baru" style="width: 100%; margin: 0; box-sizing: border-box;">
                </div>
            `,
            background: '#111827',
            color: '#e8ecf4',
            showCancelButton: true,
            confirmButtonColor: '#ff7a00',
            cancelButtonColor: '#4b5563',
            confirmButtonText: '<i class="fas fa-check"></i> Reset Password',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const email = document.getElementById('swal-email').value;
                const pin = document.getElementById('swal-pin').value;
                const pass = document.getElementById('swal-pass').value;
                const pass_conf = document.getElementById('swal-pass-conf').value;

                if (!email || !pin || !pass || !pass_conf) {
                    Swal.showValidationMessage('Semua kolom wajib diisi!');
                    return false;
                }

                if (pass.length < 8) {
                    Swal.showValidationMessage('Password baru minimal 8 karakter!');
                    return false;
                }

                if (pass !== pass_conf) {
                    Swal.showValidationMessage('Konfirmasi password tidak cocok!');
                    return false;
                }

                return fetch(`{{ route('password.pin.reset') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        email: email, 
                        recovery_pin: pin,
                        password: pass,
                        password_confirmation: pass_conf
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error(response.statusText)
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(`Gagal menghubungi server: ${error}`)
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        });

        if (formValues) {
            if (formValues.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: formValues.message,
                    icon: 'success',
                    background: '#111827',
                    color: '#e8ecf4',
                    confirmButtonColor: '#1cc88a'
                });
            } else {
                Swal.fire({
                    title: 'Gagal',
                    text: formValues.message || 'Gagal mereset password.',
                    icon: 'error',
                    background: '#111827',
                    color: '#e8ecf4',
                    confirmButtonColor: '#ff7a00'
                });
            }
        }
    }
</script>
@endpush
