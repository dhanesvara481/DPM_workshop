{{-- resources/views/admin/invoice/tampilan_invoice.blade.php --}}
@extends('admin.layout.app')

@section('title', 'DPM Workshop - Admin')

@section('content')

{{-- TOPBAR --}}
<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/80 backdrop-blur" data-animate>
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
        <h1 class="text-sm font-semibold tracking-tight text-slate-900">Invoice</h1>
        <p class="text-xs text-slate-500">Buat invoice Barang / Jasa</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <a href="/tampilan_notifikasi"
         class="tip h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center"
         data-tip="Notifikasi" aria-label="Notifikasi">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0"/>
        </svg>
      </a>
      <button type="button"
              class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
        {{ now()->format('d M Y') }}
      </button>
    </div>
  </div>
</header>

<section class="relative p-4 sm:p-6">
  <div class="pointer-events-none absolute inset-0 -z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100"></div>
    <div class="absolute inset-0 opacity-[0.10]"
         style="background-image:
            linear-gradient(to right,rgba(2,6,23,.05) 1px,transparent 1px),
            linear-gradient(to bottom,rgba(2,6,23,.05) 1px,transparent 1px);
            background-size:56px 56px;"></div>
    <div class="absolute -top-48 left-1/2 -translate-x-1/2 h-[680px] w-[680px] rounded-full blur-3xl opacity-10
                bg-gradient-to-tr from-blue-950/25 via-blue-700/10 to-transparent"></div>
  </div>

  <div class="max-w-[1280px] mx-auto w-full space-y-6">

    @if(session('success'))
      <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-900">
        <div class="font-semibold mb-1">Terjadi error:</div>
        <ul class="list-disc pl-5 space-y-1">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- ===================== FORM ===================== --}}
    <form id="formInvoice" method="POST" action="{{ route('invoice.store') }}"
          class="space-y-6" data-animate>
      @csrf

      <div class="rounded-2xl border border-slate-200 bg-white/85 backdrop-blur
                  shadow-[0_16px_44px_rgba(2,6,23,0.08)] overflow-hidden">

        <div class="px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
          <div class="min-w-0">
            <div class="text-sm font-semibold text-slate-900">INVOICE</div>
            <div class="text-xs text-slate-500 mt-0.5">Pilih kategori, input data, lalu simpan.</div>
          </div>
          <button type="button" id="btnReset"
                  class="h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
            Reset
          </button>
        </div>

        <div class="p-5 sm:p-6 space-y-6">

          {{-- Top fields --}}
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="space-y-1">
              <label class="text-xs font-semibold text-slate-700">Nama Pembuat Transaksi</label>
              <input value="{{ auth()->user()->name ?? auth()->user()->username ?? 'User' }}"
                     class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none" readonly />
              <input type="hidden" name="user_id" value="{{ auth()->user()->user_id ?? '' }}">
            </div>

            <div class="space-y-1">
              <label class="text-xs font-semibold text-slate-700">Tanggal</label>
              <input type="date" name="tanggal_invoice"
                     value="{{ old('tanggal_invoice', now()->format('Y-m-d')) }}"
                     class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10" />
            </div>

            <div class="space-y-1">
              <label class="text-xs font-semibold text-slate-700">Kategori Invoice</label>
              <div class="grid grid-cols-2 rounded-xl border border-slate-200 bg-slate-50 p-1">
                <button type="button" data-invtab="barang" class="invtab h-10 rounded-lg text-sm font-semibold transition">Barang</button>
                <button type="button" data-invtab="jasa"   class="invtab h-10 rounded-lg text-sm font-semibold transition">Jasa</button>
              </div>
              <input type="hidden" name="kategori" id="kategori" value="{{ old('kategori','barang') }}">
              <p class="text-[11px] text-slate-500">Jasa: charge servis, plus barang yang memang ditagihkan (opsional).</p>
            </div>
          </div>

          {{-- Customer info --}}
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="space-y-1 lg:col-span-2">
              <label class="text-xs font-semibold text-slate-700">Nama Pelanggan (opsional)</label>
              <input name="nama_pelanggan" value="{{ old('nama_pelanggan') }}"
                     class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                     placeholder="Contoh: Budi" />
            </div>
            <div class="space-y-1">
              <label class="text-xs font-semibold text-slate-700">Kontak Pelanggan (opsional)</label>
              <input name="kontak" value="{{ old('kontak') }}"
                     class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                     placeholder="08xxxxxxxxxx" />
            </div>
          </div>

          {{-- BARANG SECTION --}}
          <div id="sectionBarang" class="space-y-3">
            <div class="flex items-center justify-between gap-3">
              <div>
                <p class="text-sm font-semibold text-slate-900">Barang Yang Dibeli</p>
                <p class="text-xs text-slate-500">Pilih dari barang yang tersedia (stok > 0).</p>
              </div>
              <button type="button" id="btnAddBarang"
                      class="inline-flex items-center gap-2 h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                <span class="text-base">＋</span> Tambah
              </button>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
              <div class="overflow-x-auto">
                <table class="min-w-[1060px] w-full text-sm">
                  <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-xs text-slate-600">
                      <th class="px-4 py-3 text-left font-semibold w-[140px]">Kode</th>
                      <th class="px-4 py-3 text-left font-semibold">Barang</th>
                      <th class="px-4 py-3 text-left font-semibold w-[120px]">Satuan</th>
                      <th class="px-4 py-3 text-left font-semibold w-[160px]">Stok Digunakan</th>
                      <th class="px-4 py-3 text-left font-semibold w-[170px]">Harga Satuan</th>
                      <th class="px-4 py-3 text-left font-semibold w-[170px]">Jumlah</th>
                      <th class="px-3 py-3 w-[64px]"></th>
                    </tr>
                  </thead>
                  <tbody id="tbodyBarang"></tbody>
                </table>
              </div>
            </div>
          </div>

          {{-- JASA SECTION --}}
          <div id="sectionJasa" class="space-y-4 hidden">
            <div>
              <p class="text-sm font-semibold text-slate-900">Detail Pelayanan / Service</p>
              <p class="text-xs text-slate-500">Input biaya service, lalu (opsional) barang yang ditagihkan.</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
              <div class="space-y-1 lg:col-span-2">
                <label class="text-xs font-semibold text-slate-700">Nama Jasa / Service</label>
                <input name="jasa_nama" value="{{ old('jasa_nama') }}"
                       class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                       placeholder="Contoh: Service fan rusak" />
              </div>
              <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-700">Biaya Jasa</label>
                <input type="number" min="0" step="1" name="jasa_biaya" id="jasaBiaya"
                       value="{{ old('jasa_biaya') }}"
                       class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                       placeholder="0" />
              </div>
            </div>

            <div class="space-y-3">
              <div class="flex items-center justify-between gap-3">
                <div>
                  <p class="text-sm font-semibold text-slate-900">Barang Yang Ditagihkan (Opsional)</p>
                  <p class="text-xs text-slate-500">Kalau tidak ditagihkan, tidak perlu diinput.</p>
                </div>
                <button type="button" id="btnAddJasaBarang"
                        class="inline-flex items-center gap-2 h-10 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                  <span class="text-base">＋</span> Tambah
                </button>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden">
                <div class="overflow-x-auto">
                  <table class="min-w-[1060px] w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                      <tr class="text-xs text-slate-600">
                        <th class="px-4 py-3 text-left font-semibold w-[140px]">Kode</th>
                        <th class="px-4 py-3 text-left font-semibold">Barang</th>
                        <th class="px-4 py-3 text-left font-semibold w-[120px]">Satuan</th>
                        <th class="px-4 py-3 text-left font-semibold w-[160px]">Stok Digunakan</th>
                        <th class="px-4 py-3 text-left font-semibold w-[170px]">Harga Satuan</th>
                        <th class="px-4 py-3 text-left font-semibold w-[170px]">Jumlah</th>
                        <th class="px-3 py-3 w-[64px]"></th>
                      </tr>
                    </thead>
                    <tbody id="tbodyJasaBarang"></tbody>
                  </table>
                </div>
              </div>
              <p class="text-[11px] text-slate-500">*Barang yang stok 0 otomatis tidak muncul di pilihan.</p>
            </div>
          </div>

          {{-- Deskripsi + Total --}}
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-1">
              <label class="text-xs font-semibold text-slate-700">Deskripsi</label>
              <textarea name="deskripsi" rows="6"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                        placeholder="Catatan tambahan untuk invoice...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="space-y-3">
              <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-2">
                <div class="flex items-center justify-between text-sm">
                  <span class="text-slate-600">Subtotal Barang</span>
                  <span id="sumBarang" class="font-semibold text-slate-900">0</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                  <span class="text-slate-600">Biaya Jasa</span>
                  <span id="sumJasa" class="font-semibold text-slate-900">0</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                  <span class="text-slate-600">Subtotal Keseluruhan</span>
                  <span id="sumSubtotal" class="font-semibold text-slate-900">0</span>
                </div>

                <div class="border-t border-slate-200 my-1"></div>

                <div class="grid grid-cols-2 gap-2">
                  <div class="space-y-1">
                    <label class="text-[11px] font-semibold text-slate-700">Diskon (Rp)</label>
                    <input type="number" min="0" step="1" id="diskon"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                           placeholder="0" />
                  </div>
                  <div class="space-y-1">
                    <label class="text-[11px] font-semibold text-slate-700">Pajak (%)</label>
                    <input type="number" min="0" step="0.01" id="pajak"
                           class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
                           placeholder="0" />
                  </div>
                </div>

                <div class="border-t border-slate-200 my-1"></div>

                <div class="flex items-center justify-between">
                  <span class="text-sm font-semibold text-slate-900">TOTAL</span>
                  <span id="sumGrand" class="text-xl font-bold text-slate-900">0</span>
                </div>

                <input type="hidden" name="subtotal_barang" id="subtotal_barang" value="0">
                <input type="hidden" name="subtotal_jasa"   id="subtotal_jasa"   value="0">
                <input type="hidden" name="subtotal"        id="subtotal"        value="0">
                <input type="hidden" name="grand_total"     id="grand_total"     value="0">
              </div>

              <div class="grid grid-cols-2 gap-2">
                <a href="/tampilan_dashboard" id="btnBack"
                   class="h-11 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">
                  Batal
                </a>
                <button type="submit" id="btnSave"
                        class="h-11 inline-flex items-center justify-center rounded-xl border border-slate-900 bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold">
                  Simpan
                </button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </form>

    <div data-animate class="text-xs text-slate-400 pt-2">© DPM Workshop 2025</div>
  </div>
</section>

{{-- Toast --}}
<div id="toast" class="fixed bottom-6 right-6 z-50 hidden w-[340px] rounded-2xl border border-slate-200 bg-white/90 backdrop-blur px-4 py-3 shadow-[0_18px_48px_rgba(2,6,23,0.14)]">
  <div class="flex items-start gap-3">
    <div id="toastDot" class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
    <div class="min-w-0">
      <p id="toastTitle" class="text-sm font-semibold text-slate-900">Berhasil</p>
      <p id="toastMsg"   class="text-xs text-slate-600 mt-0.5">Data tersimpan.</p>
    </div>
    <button id="toastClose" class="ml-auto text-slate-500 hover:text-slate-800 transition" type="button">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
</div>

{{-- Confirm Modal --}}
<div id="confirmModal" class="fixed inset-0 z-[999] hidden">
  <div id="cmOverlay" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
  <div class="relative min-h-screen flex items-end sm:items-center justify-center p-3 sm:p-6">
    <div class="w-full max-w-[520px] rounded-2xl bg-white border border-slate-200 shadow-[0_30px_90px_rgba(2,6,23,0.30)] overflow-hidden">
      <div class="px-5 py-4 border-b border-slate-200 flex items-start justify-between gap-3">
        <div class="min-w-0">
          <div id="cmTitle" class="text-lg font-semibold text-slate-900">Konfirmasi</div>
          <div id="cmMsg"   class="text-sm text-slate-600 mt-1">—</div>
        </div>
        <button type="button" id="cmClose"
                class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition grid place-items-center">
          <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
      <div class="p-5">
        <div id="cmNoteWrap" class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600">
          <span id="cmNote">Pastikan data sudah benar.</span>
        </div>
        <div class="mt-4 flex justify-end gap-2">
          <button type="button" id="cmCancel" class="h-10 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-semibold">Batal</button>
          <button type="button" id="cmOk"     class="h-10 px-5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold">Ya</button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  window.BARANGS    = @json($barangs ?? []);
  window.URL_CEK_STOK = "{{ route('invoice.check-stok') }}";
  window.CSRF_TOKEN   = "{{ csrf_token() }}";
</script>
@endpush

@endsection

@push('head')
<style>
  [data-animate]{opacity:0;transform:translateY(14px) scale(.985);filter:blur(3px);transition:opacity .55s ease,transform .55s cubic-bezier(.2,.8,.2,1),filter .55s ease;will-change:opacity,transform,filter;}
  [data-animate].in{opacity:1;transform:translateY(0) scale(1);filter:blur(0);}
  @media(prefers-reduced-motion:reduce){[data-animate]{opacity:1!important;transform:none!important;filter:none!important;transition:none!important;}}
  .invtab{background:transparent;color:rgba(15,23,42,.75);}
  .invtab.is-active{background:#0f172a;color:#fff;box-shadow:0 10px 26px rgba(2,6,23,.18);}
  @keyframes shake{0%{transform:translateX(0)}25%{transform:translateX(-6px)}50%{transform:translateX(6px)}75%{transform:translateX(-4px)}100%{transform:translateX(0)}}
  .shake{animation:shake .28s ease;}
  .tip{position:relative;}
  .tip[data-tip]::after{content:attr(data-tip);position:absolute;right:0;top:calc(100% + 10px);background:rgba(15,23,42,.92);color:rgba(255,255,255,.92);font-size:11px;padding:6px 10px;border-radius:10px;white-space:nowrap;opacity:0;transform:translateY(-4px);pointer-events:none;transition:.15s ease;}
  .tip:hover::after{opacity:1;transform:translateY(0);}
</style>
@endpush

@push('scripts')
<script>
(function(){
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if(reduce) return;
  const items = Array.from(document.querySelectorAll('[data-animate]'));
  items.forEach((el,i) => el.style.transitionDelay = (80 + i*60)+'ms');
  requestAnimationFrame(() => items.forEach(el => el.classList.add('in')));
})();

// ===== TOAST =====
const toastEl=document.getElementById('toast'),toastTitle=document.getElementById('toastTitle'),
      toastMsg=document.getElementById('toastMsg'),toastDot=document.getElementById('toastDot');
let toastTimer=null;
const showToast=(title,msg,type='success')=>{
  if(!toastEl)return;
  toastTitle.textContent=title;
  toastMsg.innerHTML=msg;
  toastDot.className="mt-1 h-2.5 w-2.5 rounded-full "+(type==='success'?"bg-emerald-500":"bg-red-500");
  toastEl.classList.remove('hidden');
  clearTimeout(toastTimer);
  toastTimer=setTimeout(()=>toastEl.classList.add('hidden'),3500);
};
document.getElementById('toastClose')?.addEventListener('click',()=>toastEl.classList.add('hidden'));

// ===== CONFIRM MODAL =====
const cm={
  el:document.getElementById('confirmModal'),overlay:document.getElementById('cmOverlay'),
  title:document.getElementById('cmTitle'),msg:document.getElementById('cmMsg'),
  noteWrap:document.getElementById('cmNoteWrap'),note:document.getElementById('cmNote'),
  ok:document.getElementById('cmOk'),cancel:document.getElementById('cmCancel'),
  close:document.getElementById('cmClose'),_resolver:null,
  open({title='Konfirmasi',message='—',note='',okText='Ya',cancelText='Batal',tone='neutral'}={}){
    if(!this.el)return Promise.resolve(false);
    this.title.textContent=title; this.msg.textContent=message;
    note?(this.noteWrap.classList.remove('hidden'),this.note.textContent=note):this.noteWrap.classList.add('hidden');
    this.ok.textContent=okText; this.cancel.textContent=cancelText;
    if(tone==='danger'){
      this.ok.className="h-10 px-5 rounded-xl bg-rose-600 text-white hover:bg-rose-700 transition text-sm font-semibold";
      this.noteWrap.className="rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-700";
    } else {
      this.ok.className="h-10 px-5 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition text-sm font-semibold";
      this.noteWrap.className="rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600";
    }
    this.el.classList.remove('hidden'); document.body.classList.add('overflow-hidden');
    return new Promise(r=>{this._resolver=r;});
  },
  closeModal(result=false){
    this.el?.classList.add('hidden'); document.body.classList.remove('overflow-hidden');
    if(this._resolver)this._resolver(result); this._resolver=null;
  }
};
cm.overlay?.addEventListener('click',()=>cm.closeModal(false));
cm.close?.addEventListener('click',()=>cm.closeModal(false));
cm.cancel?.addEventListener('click',()=>cm.closeModal(false));
cm.ok?.addEventListener('click',()=>cm.closeModal(true));
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&cm.el&&!cm.el.classList.contains('hidden'))cm.closeModal(false);});

// ===== INVOICE LOGIC =====
const fmtID=n=>(isFinite(n)?n:0).toLocaleString('id-ID');
const barangList=(Array.isArray(window.BARANGS)?window.BARANGS:[]).filter(b=>Number(b?.stok??0)>0);

const form=document.getElementById('formInvoice');
const kategoriEl=document.getElementById('kategori');
const tabs=Array.from(document.querySelectorAll('.invtab'));
const sectionBarang=document.getElementById('sectionBarang');
const sectionJasa=document.getElementById('sectionJasa');
const tbodyBarang=document.getElementById('tbodyBarang');
const tbodyJasaBarang=document.getElementById('tbodyJasaBarang');
const jasaBiaya=document.getElementById('jasaBiaya');
const diskon=document.getElementById('diskon');
const pajak=document.getElementById('pajak');
const sumBarang=document.getElementById('sumBarang');
const sumJasa=document.getElementById('sumJasa');
const sumSubtotal=document.getElementById('sumSubtotal');
const sumGrand=document.getElementById('sumGrand');
const h_sub_barang=document.getElementById('subtotal_barang');
const h_sub_jasa=document.getElementById('subtotal_jasa');
const h_subtotal=document.getElementById('subtotal');
const h_grand=document.getElementById('grand_total');

let isDirty=false;
const markDirty=()=>{isDirty=true;};
form?.querySelectorAll('input,select,textarea').forEach(el=>{
  if(el.closest('#tbodyBarang')||el.closest('#tbodyJasaBarang'))return;
  el.addEventListener('input',markDirty);
  el.addEventListener('change',markDirty);
});

function barangOptionsHTML(){
  return barangList.map(b=>`
    <option value="${b.barang_id}"
      data-kode="${b.kode_barang??''}"
      data-satuan="${b.satuan??'-'}"
      data-harga="${Number(b.harga_jual??0)}"
      data-stok="${Number(b.stok??0)}">
      ${b.nama_barang??'-'}
    </option>`).join('');
}

function setKategori(kat){
  kategoriEl.value=kat;
  tabs.forEach(t=>t.classList.toggle('is-active',t.dataset.invtab===kat));
  sectionBarang.classList.toggle('hidden',kat!=='barang');
  sectionJasa.classList.toggle('hidden',kat!=='jasa');
  recalc();
}
tabs.forEach(t=>t.addEventListener('click',()=>{setKategori(t.dataset.invtab);markDirty();}));

function rowHTML(idx,prefix){
  return `
  <tr class="border-b border-slate-200 last:border-0">
    <td class="px-4 py-3">
      <input name="${prefix}[${idx}][kode]" data-kode
             class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none" readonly/>
    </td>
    <td class="px-4 py-3">
      <select name="${prefix}[${idx}][barang_id]" data-barang-select
              class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10">
        <option value="" selected disabled>Pilih barang</option>
        ${barangOptionsHTML()}
      </select>
    </td>
    <td class="px-4 py-3">
      <input name="${prefix}[${idx}][satuan]" data-satuan
             class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none" readonly/>
    </td>
    <td class="px-4 py-3">
      <input type="number" min="1" step="1" data-qty disabled
             name="${prefix}[${idx}][qty]"
             class="h-10 w-full rounded-xl border border-slate-200 bg-white px-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/10"
             placeholder="0"/>
      <p class="mt-1 text-[11px] text-slate-500 hidden" data-stock-label>Tersedia: <span data-max>0</span></p>
      <p class="mt-1 text-[11px] text-red-500 hidden" data-stok-error></p>
    </td>
    <td class="px-4 py-3">
      <input type="number" min="0" step="1" data-price
             name="${prefix}[${idx}][harga]"
             class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm outline-none" readonly/>
    </td>
    <td class="px-4 py-3">
      <div class="h-10 rounded-xl border border-slate-200 bg-slate-50 px-3 text-sm flex items-center justify-between">
        <span class="text-slate-500">Rp</span>
        <span data-line-total class="font-semibold text-slate-900">0</span>
      </div>
      <input type="hidden" data-line-hidden name="${prefix}[${idx}][total]" value="0"/>
    </td>
    <td class="px-3 py-3 text-right">
      <button type="button" data-remove
              class="h-10 w-10 rounded-xl border border-slate-200 bg-white hover:bg-rose-50 hover:border-rose-200 transition grid place-items-center">
        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.9 13a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6M14 11v6"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16"/>
        </svg>
      </button>
    </td>
  </tr>`;
}

function recalcRow(tr){
  const qty=Number(tr.querySelector('[data-qty]')?.value||0);
  const price=Number(tr.querySelector('[data-price]')?.value||0);
  const total=Math.max(0,qty)*Math.max(0,price);
  tr.querySelector('[data-line-total]').textContent=fmtID(total);
  tr.querySelector('[data-line-hidden]').value=String(total);
}

function handleTableEvents(tbody){
  tbody.addEventListener('change',e=>{
    const sel=e.target.closest('[data-barang-select]');
    if(!sel)return;
    const tr=sel.closest('tr');
    const opt=sel.options[sel.selectedIndex];

    const stok  =Number(opt.getAttribute('data-stok')||0);
    const harga =Number(opt.getAttribute('data-harga')||0);
    const kode  =opt.getAttribute('data-kode')||'';
    const satuan=opt.getAttribute('data-satuan')||'';

    tr.querySelector('input[data-kode]').value   =kode;
    tr.querySelector('input[data-satuan]').value =satuan;

    const priceEl   =tr.querySelector('[data-price]');
    const qtyEl     =tr.querySelector('[data-qty]');
    const maxEl     =tr.querySelector('[data-max]');
    const stockLabel=tr.querySelector('[data-stock-label]');
    const stokError =tr.querySelector('[data-stok-error]');

    if(priceEl) priceEl.value=String(harga);
    if(maxEl)   maxEl.textContent=String(stok);
    if(stockLabel) stockLabel.classList.remove('hidden');
    if(stokError){stokError.textContent='';stokError.classList.add('hidden');}

    if(qtyEl){
      qtyEl.disabled=stok<=0;
      qtyEl.max=String(stok);
      qtyEl.value='';
      qtyEl.classList.remove('border-red-300');
    }
    markDirty(); recalcRow(tr); recalc();
  });

  tbody.addEventListener('input',e=>{
    const qtyInput=e.target.closest('[data-qty]');
    if(!qtyInput||qtyInput.disabled)return;
    const tr=qtyInput.closest('tr');
    const max=Number(qtyInput.max||0);
    let qty=Number(qtyInput.value||0);
    if(max>0&&qty>max)qty=max;
    if(qty<0)qty=0;
    qtyInput.value=qty?String(qty):'';
    const stokError=tr?.querySelector('[data-stok-error]');
    if(stokError){stokError.textContent='';stokError.classList.add('hidden');}
    qtyInput.classList.remove('border-red-300');
    recalcRow(tr); markDirty(); recalc();
  });

  tbody.addEventListener('click',e=>{
    const btn=e.target.closest('[data-remove]');
    if(!btn)return;
    btn.closest('tr')?.remove();
    markDirty(); recalc();
  });
}

let barangIdx=0,jasaBarangIdx=0;
function addBarangRow(){tbodyBarang.insertAdjacentHTML('beforeend',rowHTML(barangIdx++,'barang'));markDirty();}
function addJasaBarangRow(){tbodyJasaBarang.insertAdjacentHTML('beforeend',rowHTML(jasaBarangIdx++,'jasa_barang'));markDirty();}

document.getElementById('btnAddBarang')?.addEventListener('click',addBarangRow);
document.getElementById('btnAddJasaBarang')?.addEventListener('click',addJasaBarangRow);
handleTableEvents(tbodyBarang);
handleTableEvents(tbodyJasaBarang);
;[jasaBiaya,diskon,pajak].forEach(el=>el?.addEventListener('input',()=>{markDirty();recalc();}));

function sumTable(tbody){
  let sum=0;
  tbody.querySelectorAll('[data-line-hidden]').forEach(h=>sum+=Number(h.value||0));
  return sum;
}

function recalc(){
  const kat=kategoriEl.value;
  const barangSum=sumTable(tbodyBarang);
  const jasaBarangSum=sumTable(tbodyJasaBarang);
  const jasa=Number(jasaBiaya?.value||0);
  const subtotalBarangVal=kat==='barang'?barangSum:jasaBarangSum;
  const jasaVal=kat==='jasa'?Math.max(0,jasa):0;
  const subtotalVal=subtotalBarangVal+jasaVal;
  const diskonVal=Math.max(0,Number(diskon?.value||0));
  const pajakPct=Math.max(0,Number(pajak?.value||0));
  const afterDisc=Math.max(0,subtotalVal-diskonVal);
  const pajakVal=Math.round(afterDisc*(pajakPct/100));
  const grand=afterDisc+pajakVal;
  sumBarang.textContent=fmtID(subtotalBarangVal);
  sumJasa.textContent=fmtID(jasaVal);
  sumSubtotal.textContent=fmtID(subtotalVal);
  sumGrand.textContent=fmtID(grand);
  h_sub_barang.value=String(subtotalBarangVal);
  h_sub_jasa.value=String(jasaVal);
  h_subtotal.value=String(subtotalVal);
  h_grand.value=String(grand);
}

function collectItems(tbody){
  const rows=[];
  tbody.querySelectorAll('tr').forEach(tr=>{
    const barangId=tr.querySelector('[data-barang-select]')?.value;
    const qty=Number(tr.querySelector('[data-qty]')?.value||0);
    if(barangId&&qty>0)rows.push({barang_id:barangId,qty,tr});
  });
  return rows;
}

async function cekStokServer(items){
  const payload=items.map(i=>({barang_id:i.barang_id,qty:i.qty}));
  const res=await fetch(window.URL_CEK_STOK,{
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':window.CSRF_TOKEN,'Accept':'application/json'},
    body:JSON.stringify({items:payload}),
  });
  if(!res.ok)throw new Error('Gagal menghubungi server.');
  return res.json();
}

function tandaiBarisProblem(items,errorMessages){
  [...tbodyBarang.querySelectorAll('tr'),...tbodyJasaBarang.querySelectorAll('tr')].forEach(tr=>{
    tr.querySelector('[data-qty]')?.classList.remove('border-red-300');
    const e=tr.querySelector('[data-stok-error]');
    if(e){e.textContent='';e.classList.add('hidden');}
  });
  items.forEach(item=>{
    const sel=item.tr.querySelector('[data-barang-select]');
    const opt=sel?.options[sel.selectedIndex];
    const namaOpt=opt?.text?.split(' (')[0]?.trim()??'';
    const hasError=errorMessages.some(msg=>msg.includes(namaOpt));
    if(hasError){
      item.tr.querySelector('[data-qty]')?.classList.add('border-red-300');
      const e=item.tr.querySelector('[data-stok-error]');
      if(e){e.innerHTML='Stok tidak cukup';e.classList.remove('hidden');}
    }
  });
}

// INIT
setKategori(kategoriEl.value||'barang');
if((kategoriEl.value||'barang')==='barang')addBarangRow();
else addJasaBarangRow();
recalc();

// RESET
document.getElementById('btnReset')?.addEventListener('click',async()=>{
  const ok=await cm.open({title:'Reset form invoice?',message:'Semua data yang sudah kamu input akan dihapus.',
    note:'Tindakan ini tidak bisa dibatalkan.',okText:'Ya, Reset',cancelText:'Batal',tone:'danger'});
  if(!ok)return;
  form.reset();
  tbodyBarang.innerHTML='';tbodyJasaBarang.innerHTML='';
  barangIdx=0;jasaBarangIdx=0;
  setKategori('barang');addBarangRow();
  isDirty=false;recalc();
  showToast('Reset','Form dikosongkan.','success');
});

// KELUAR
document.getElementById('btnBack')?.addEventListener('click',async e=>{
  if(!isDirty)return;
  e.preventDefault();
  const href=e.currentTarget.getAttribute('href')||'/tampilan_dashboard';
  const ok=await cm.open({title:'Keluar dari halaman?',
    message:'Perubahan belum disimpan. Kalau keluar sekarang, data akan hilang.',
    okText:'Ya, Keluar',cancelText:'Tetap di sini',tone:'neutral'});
  if(ok)window.location.href=href;
});

// SUBMIT + CEK STOK
form?.addEventListener('submit',async e=>{
  if(form.dataset.confirmed==='1')return;
  e.preventDefault();

  const kategori=kategoriEl.value;

  // ---- Validasi kategori BARANG ----
  if(kategori==='barang'){
    const rows=tbodyBarang.querySelectorAll('tr');
    if(!rows.length){
      showToast('Gagal','Tambahkan minimal 1 item barang.','error'); return;
    }
    let valid=true;
    rows.forEach(tr=>{
      const sel=tr.querySelector('[data-barang-select]');
      const qty=tr.querySelector('[data-qty]');
      if(!sel?.value||!qty?.value||Number(qty.value)<=0) valid=false;
    });
    if(!valid){
      showToast('Gagal','Pastikan semua item barang sudah dipilih dan qty diisi.','error'); return;
    }
  }

  // ---- Validasi kategori JASA ----
  if(kategori==='jasa'&&Number(jasaBiaya?.value||0)<=0){
    jasaBiaya?.classList.add('border-red-300','shake');
    setTimeout(()=>jasaBiaya?.classList.remove('shake'),300);
    showToast('Gagal','Biaya jasa wajib diisi untuk kategori Jasa.','error'); return;
  }

  // ---- CEK STOK (hanya jika ada item barang) ----
  const activetbody=kategori==='barang'?tbodyBarang:tbodyJasaBarang;
  const activeItems=collectItems(activetbody);
  if(activeItems.length>0){
    const btnSave=document.getElementById('btnSave');
    const oriText=btnSave?.textContent??'Simpan';
    try{
      if(btnSave){btnSave.disabled=true;btnSave.textContent='Mengecek stok...';}
      const result=await cekStokServer(activeItems);
      if(!result.ok){
        tandaiBarisProblem(activeItems,result.errors);
        showToast('Stok tidak cukup',result.errors.join('<br>'),'error');
        if(btnSave){btnSave.disabled=false;btnSave.textContent=oriText;}
        return;
      }
      if(btnSave){btnSave.disabled=false;btnSave.textContent=oriText;}
    }catch(err){
      if(btnSave){btnSave.disabled=false;btnSave.textContent=oriText;}
      showToast('Error','Gagal mengecek stok. Coba lagi.','error'); return;
    }
  }

  // ---- KONFIRMASI ----
  const ok=await cm.open({title:'Simpan invoice?',
    message:'Invoice akan disimpan sesuai data yang kamu input.',
    note:'Pastikan item dan jumlah sudah benar.',
    okText:'Ya, Simpan',cancelText:'Batal',tone:'neutral'});
  if(!ok)return;

  form.dataset.confirmed='1';
  isDirty=false;
  form.submit();
});
</script>
@endpush