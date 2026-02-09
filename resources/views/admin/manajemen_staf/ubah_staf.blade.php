<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ubah Staf</title>
  @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="min-h-screen flex">

  {{-- ================= SIDEBAR ================= --}}
  <aside id="sidebar"
         class="fixed inset-y-0 left-0 z-40 h-screen
                w-[280px] md:w-[280px]
                -translate-x-full md:translate-x-0
                bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white
                border-r border-white/5
                transition-[transform,width] duration-300 ease-out
                overflow-y-auto">

    <div class="h-16 px-5 flex items-center justify-between border-b border-white/10">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-white/10 border border-white/15 grid place-items-center overflow-hidden">
          <img src="{{ asset('images/logo.png') }}" class="h-7 w-7 object-contain" alt="Logo">
        </div>
        <div class="leading-tight">
          <p class="font-semibold tracking-tight">DPM Workshop</p>
        </div>
      </div>

      <button id="btnCloseSidebar" type="button"
              class="md:hidden h-10 w-10 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition grid place-items-center"
              aria-label="Tutup menu">
        <svg class="h-5 w-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <div class="px-5 py-5">
      {{-- Profile --}}
      <div class="flex items-center gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-3">
        <div class="h-10 w-10 rounded-full bg-white/10 border border-white/15"></div>
        <div class="min-w-0">
          <p class="text-sm font-medium truncate">{{ $userName ?? 'User' }}</p>
          <p class="text-[11px] text-white/60">{{ $role ?? 'Admin' }}</p>
        </div>
      </div>

      {{-- Menu --}}
      <nav class="mt-5 space-y-1">
        <a href="#" data-nav
           class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
          <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
            <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V21a1.5 1.5 0 01-1.5 1.5H4.5A1.5 1.5 0 013 21V10.5z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/>
            </svg>
          </span>
          Dashboard
        </a>

        <div class="mt-3">
          <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">BARANG</p>
          <a href="/tampilan_barang" data-nav class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8 4-8-4"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v10"/>
              </svg>
            </span>
            Kelola Barang
          </a>

          <a href="/barang_keluar" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 7h7v7"/>
              </svg>
            </span>
            Barang Keluar
          </a>

          <a href="/barang_masuk" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 10v7h7"/>
              </svg>
            </span>
            Barang Masuk
          </a>
        </div>

        <div class="mt-3">
          <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">RIWAYAT & LAPORAN</p>
          <a href="/riwayat_perubahan_stok" data-nav class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </span>
            Riwayat Perubahan Stok
          </a>

          <a href="/riwayat_transaksi" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v16l-2-1-2 1-2-1-2 1-2-1-2 1V5a2 2 0 012-2z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6M9 12h6M9 16h4"/>
              </svg>
            </span>
            Riwayat Transaksi
          </a>

          <a href="/laporan_penjualan" data-nav class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17v-6"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 17V9"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 17v-3"/>
              </svg>
            </span>
            Laporan Penjualan
          </a>
        </div>

        <div class="mt-3">
          <p class="px-4 pt-3 pb-2 text-[11px] tracking-widest text-white/40">MANAJEMEN</p>

          <a href="/jadwal_kerja" data-nav
             class="nav-item group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </span>
            Kelola Jadwal Kerja
          </a>

          <a href="/tampilan_manajemen_staf" data-nav data-active="true"
             class="nav-item group mt-1 flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm
                    bg-white/12 text-white border border-white/10
                    hover:bg-white/10 hover:text-white transition relative overflow-hidden">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/80 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20c0-2.2-2.7-4-5-4s-5 1.8-5 4"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 20c0-1.7-1.4-3.1-3.3-3.7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7a2.5 2.5 0 01-1.5 2.3"/>
              </svg>
            </span>
            Manajemen Staf
          </a>
        </div>

        <div class="mt-4 pt-4 border-t border-white/10">
          <a href="#"
             class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm text-white/80 hover:bg-white/10 hover:text-white transition">
            <span class="h-8 w-8 rounded-lg bg-white/5 border border-white/10 grid place-items-center">
              <svg class="h-[18px] w-[18px] text-white/70 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21V3a2 2 0 00-2-2h-6"/>
              </svg>
            </span>
            Logout
          </a>
        </div>
      </nav>
    </div>
  </aside>

  <div id="overlay" class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm hidden md:hidden"></div>

  {{-- ================= MAIN ================= --}}
  <main id="main" class="flex-1 min-w-0 relative overflow-hidden md:ml-[280px] transition-[margin] duration-300 ease-out">

    {{-- TOPBAR --}}
    <header class="relative bg-white/75 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
      <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
          <button id="btnSidebar" type="button"
                  class="md:hidden h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                  aria-label="Buka menu">
            <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>

          <div class="min-w-0">
            <h1 class="text-sm font-semibold tracking-tight text-slate-900">Ubah Staf</h1>
            <p class="text-xs text-slate-500">
              Edit data staf. Role tetap <span class="font-semibold">staff</span>.
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <button type="button"
                  class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                  data-tip="Notifikasi">
            <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
            </svg>
          </button>
        </div>
      </div>
    </header>

    {{-- CONTENT --}}
    <section class="relative p-4 sm:p-6">
      <div class="max-w-[920px] mx-auto w-full">

        {{-- ALERTS --}}
        @if (session('success'))
          <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
            <div class="font-semibold">Berhasil</div>
            <div class="text-sm mt-0.5">{{ session('success') }}</div>
          </div>
        @endif

        @if (session('error'))
          <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
            <div class="font-semibold">Gagal</div>
            <div class="text-sm mt-0.5">{{ session('error') }}</div>
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
            <div class="font-semibold">Ada error</div>
            <ul class="list-disc ml-5 text-sm mt-1 space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="rounded-2xl bg-white border border-slate-200 shadow-[0_18px_48px_rgba(2,6,23,0.08)] overflow-hidden">
          <div class="p-5 sm:p-6 border-b border-slate-200">
            <div class="flex items-start justify-between gap-3">
              <div>
                <div class="text-lg font-semibold text-slate-900">Form Ubah Staf</div>
                <div class="text-xs text-slate-500 mt-1">
                  Edit data login dan profil staf. Password opsional (kosongkan jika tidak ingin mengganti).
                </div>
              </div>

              <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border border-slate-200 bg-slate-50 text-slate-700">
                Role: staff
              </span>
            </div>
          </div>
            
          {{-- 
            Asumsi data staff dikirim sebagai $staf
            - $staf->id, $staf->username, $staf->email, $staf->kontak, $staf->catatan
            Sesuaikan route/param sesuai project kamu.
          --}}
          <form action="/ubah_staf/{{ $staf->id ?? '' }}" method="POST" class="p-5 sm:p-6">
                @csrf
                @method('PUT')

                {{-- role dipaksa staff --}}
                <input type="hidden" name="role" value="staff">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-slate-800">Username</label>
                    <input name="username"
                        value="{{ old('username', $staf->username ?? '') }}"
                        maxlength="20" required
                        placeholder="contoh: asep01"
                        class="mt-2 h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                    <p class="mt-2 text-xs text-slate-500">Maks 20 karakter & harus unik.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800">Email</label>
                    <input name="email" type="email"
                        value="{{ old('email', $staf->email ?? '') }}"
                        required
                        placeholder="contoh: asep@gmail.com"
                        class="mt-2 h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                    <p class="mt-2 text-xs text-slate-500">Harus unik.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800">Kontak (No HP)</label>
                    <input name="kontak"
                        value="{{ old('kontak', $staf->kontak ?? '') }}"
                        maxlength="12" required
                        placeholder="contoh: 081234567890"
                        class="mt-2 h-11 w-full rounded-xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                    <p class="mt-2 text-xs text-slate-500">Maks 12 digit (sesuaikan kalau perlu).</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800">Password (opsional)</label>
                    <div class="mt-2 relative">
                    <input id="password" name="password" type="password"
                            placeholder="Kosongkan jika tidak diganti"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-white px-4 pr-12 text-sm outline-none focus:ring-2 focus:ring-slate-200">
                    <button id="btnTogglePw" type="button"
                            class="absolute right-2 top-1/2 -translate-y-1/2 h-9 w-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
                            aria-label="Lihat password">
                        <svg id="iconEye" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">
                    Kalau diisi, password baru akan di-hash di backend.
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-800">Catatan (opsional)</label>
                    <textarea name="catatan" rows="3"
                            placeholder="Contoh: Mekanik / Keuangan / Marketing"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-200">{{ old('catatan', $staf->catatan ?? '') }}</textarea>
                    <p class="mt-2 text-xs text-slate-500">Opsional.</p>
                </div>

                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-2 sm:justify-end">
                <a id="btnCancel" href="/tampilan_manajemen_staf"
                    class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold border border-slate-200 bg-white hover:bg-slate-50 transition">
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex h-11 items-center justify-center rounded-xl px-5 text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-[0_12px_24px_rgba(2,6,23,0.14)]">
                    Update
                </button>
                </div>
          </form>

          <div class="px-5 sm:px-6 py-4 border-t border-slate-200 text-xs text-slate-500">
            © DPM Workshop 2025
          </div>
        </div>

      </div>
    </section>

    <script>
      // mobile sidebar
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('overlay');
      const btnSidebar = document.getElementById('btnSidebar');
      const btnCloseSidebar = document.getElementById('btnCloseSidebar');

      const openSidebar = () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
      };
      const closeSidebar = () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      };

      btnSidebar?.addEventListener('click', openSidebar);
      btnCloseSidebar?.addEventListener('click', closeSidebar);
      overlay?.addEventListener('click', closeSidebar);

      const syncOnResize = () => {
        if (window.innerWidth >= 768) {
          overlay.classList.add('hidden');
          sidebar.classList.remove('-translate-x-full');
          document.body.classList.remove('overflow-hidden');
        } else {
          sidebar.classList.add('-translate-x-full');
        }
      };
      window.addEventListener('resize', syncOnResize);
      syncOnResize();

      // active indicator
      document.querySelectorAll('[data-nav]').forEach(a => {
        if (a.dataset.active === "true") a.classList.add('is-active');
      });

      // toggle password
      const pw = document.getElementById('password');
      const btnTogglePw = document.getElementById('btnTogglePw');
      btnTogglePw?.addEventListener('click', () => {
        if (!pw) return;
        pw.type = pw.type === 'password' ? 'text' : 'password';
      });

      // ===== CONFIRM NATIVE (disesuaikan untuk ubah_staf) =====
      const form = document.querySelector('form[action^="/ubah_staf"]');
      const btnCancel = document.getElementById('btnCancel');

      // snapshot awal buat deteksi perubahan
      const snapshot = () => {
        if (!form) return "";
        const fd = new FormData(form);
        const obj = {};
        fd.forEach((v, k) => obj[k] = String(v));
        return JSON.stringify(obj);
      };

      let snap0 = snapshot();
      const isDirty = () => snap0 !== snapshot();

      // warning kalau refresh/close tab
      window.addEventListener('beforeunload', (e) => {
        if (!isDirty()) return;
        e.preventDefault();
        e.returnValue = '';
      });

      // klik Batal
      btnCancel?.addEventListener('click', (e) => {
        if (!isDirty()) return; // ga ada perubahan → langsung balik

        e.preventDefault();
        const ok = confirm('Perubahan belum disimpan. Yakin mau keluar?');
        if (ok) window.location.href = btnCancel.getAttribute('href');
      });

      // submit update (confirm)
      form?.addEventListener('submit', (e) => {
        const ok = confirm('Simpan perubahan data staf?');
        if (!ok) {
          e.preventDefault();
          return;
        }
        // kalau submit lanjut, anggap sudah "clean"
        snap0 = snapshot();
      });
    </script>

    <style>
      .nav-item{ position: relative; overflow: hidden; }
      .nav-item::before{
        content:"";
        position:absolute;
        left:0; top:10px; bottom:10px;
        width:3px;
        background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,.75), rgba(255,255,255,0));
        opacity:0;
        transform: translateX(-6px);
        transition: .25s ease;
        border-radius: 999px;
      }
      .nav-item.is-active::before{ opacity:.95; transform: translateX(0); }
      #sidebar { -webkit-overflow-scrolling: touch; }
    </style>

  </main>
</div>
</body>
</html>
