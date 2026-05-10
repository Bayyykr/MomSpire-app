@extends('pengguna.master')

@section('title', 'Screening Buku KIA - MomSpire')

@section('header_title', 'Screening Identitas Buku KIA')
@section('header_subtitle', 'Mohon lengkapi data identitas Buku KIA Anda untuk memulai.')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .step-transition { transition: all 0.3s ease-in-out; }
    </style>
@endpush

@section('content')
<div class="container py-8 max-w-5xl mx-auto" x-data="kiaWizard()" x-cloak>
    <!-- Progress Stepper -->
    <div class="mb-8 px-4">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 w-full h-1 bg-gray-200 -translate-y-1/2 -z-10 rounded-full"></div>
            <div class="absolute left-0 top-1/2 h-1 bg-purple-600 -translate-y-1/2 -z-10 rounded-full transition-all duration-500" :style="`width: ${((step - 1) / 5) * 100}%` "></div>
            
            <template x-for="s in 6" :key="s">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 border-4"
                         :class="step >= s ? 'bg-purple-600 text-white border-purple-100' : 'bg-white text-gray-400 border-gray-100 shadow-sm'">
                        <span x-show="step <= s || step === s" x-text="s"></span>
                        <svg x-show="step > s" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="mt-2 text-[10px] md:text-xs font-bold uppercase tracking-wider text-center" 
                          :class="step >= s ? 'text-purple-700' : 'text-gray-400'"
                          x-text="getStepShortName(s)"></span>
                </div>
            </template>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-purple-100/50 border border-purple-50 overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-6 text-white">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black tracking-tight" x-text="getStepName(step)"></h2>
                    <p class="text-purple-100 text-sm font-medium opacity-90" x-text="getStepDescription(step)"></p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <form @submit.prevent="saveData(true)">
                <!-- Notification Toast (Internal) -->
                <div x-show="toast.show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="mb-8 p-4 rounded-2xl flex items-center gap-3"
                     :class="toast.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100'">
                    <div class="p-2 rounded-full" :class="toast.type === 'success' ? 'bg-emerald-100' : 'bg-rose-100'">
                        <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="font-bold text-sm" x-text="toast.message"></span>
                </div>

                <!-- Step 1: Info Buku & Lokasi -->
                <div x-show="step === 1" x-transition.opacity>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2 bg-purple-50/50 p-6 rounded-2xl border border-purple-100 flex items-start gap-4">
                             <div class="text-purple-600 mt-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                             </div>
                             <p class="text-sm text-purple-800 leading-relaxed font-medium">Data ini dapat ditemukan pada halaman sampul depan atau halaman pertama Buku KIA (Permenkes) Anda.</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Fasilitas Kesehatan</label>
                            <input type="text" x-model="formData.faskes_dikeluarkan" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition-all font-medium" placeholder="Nama Puskesmas/Klinik/RS">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tanggal Dikeluarkan</label>
                            <input type="date" x-model="formData.tanggal_dikeluarkan" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Kabupaten/Kota</label>
                            <input type="text" x-model="formData.kab_kota_dikeluarkan" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition-all font-medium" placeholder="Contoh: Jakarta Selatan">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Provinsi</label>
                            <input type="text" x-model="formData.provinsi_dikeluarkan" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition-all font-medium" placeholder="Contoh: DKI Jakarta">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Identitas Ibu -->
                <div x-show="step === 2" x-transition.opacity>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nama Lengkap Ibu</label>
                            <input type="text" x-model="formData.nama_ibu" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">NIK</label>
                            <input type="text" x-model="formData.nik" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. JKN/BPJS</label>
                            <input type="text" x-model="formData.no_jkn_ibu" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Telepon</label>
                            <input type="text" x-model="formData.telepon_ibu" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Faskes TK 1</label>
                            <input type="text" x-model="formData.faskes_tk1_ibu" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium" placeholder="Puskesmas Domisili">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Faskes Rujukan</label>
                            <input type="text" x-model="formData.faskes_rujukan_ibu" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium" placeholder="Rumah Sakit Rujukan">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tempat Lahir</label>
                            <input type="text" x-model="formData.tempat_lahir" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tanggal Lahir</label>
                            <input type="date" x-model="formData.tanggal_lahir" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Pendidikan</label>
                            <input type="text" x-model="formData.pendidikan" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Pekerjaan</label>
                            <input type="text" x-model="formData.pekerjaan" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Golongan Darah</label>
                            <select x-model="formData.golongan_darah" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                                <option value="">Pilih</option>
                                <template x-for="g in ['A', 'B', 'AB', 'O']">
                                    <option :value="g" x-text="g"></option>
                                </template>
                            </select>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Alamat Rumah</label>
                            <textarea x-model="formData.alamat" rows="2" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Identitas Suami/Keluarga -->
                <div x-show="step === 3" x-transition.opacity>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nama Lengkap Suami</label>
                            <input type="text" x-model="formData.nama_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">NIK Suami</label>
                            <input type="text" x-model="formData.nik_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. JKN/BPJS Suami</label>
                            <input type="text" x-model="formData.no_jkn_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Telepon Suami</label>
                            <input type="text" x-model="formData.telepon_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Pendidikan Suami</label>
                            <input type="text" x-model="formData.pendidikan_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Pekerjaan Suami</label>
                            <input type="text" x-model="formData.pekerjaan_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                         <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Golongan Darah Suami</label>
                            <select x-model="formData.golongan_darah_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                                <option value="">Pilih</option>
                                <template x-for="g in ['A', 'B', 'AB', 'O']">
                                    <option :value="g" x-text="g"></option>
                                </template>
                            </select>
                        </div>
                         <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tempat/Tgl Lahir Suami</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" x-model="formData.tempat_lahir_suami" class="w-full px-3 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium" placeholder="Kota">
                                <input type="date" x-model="formData.tanggal_lahir_suami" class="w-full px-3 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Faskes TK 1 Suami</label>
                            <input type="text" x-model="formData.faskes_tk1_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Faskes Rujukan Suami</label>
                            <input type="text" x-model="formData.faskes_rujukan_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Alamat Rumah Suami</label>
                            <textarea x-model="formData.alamat_rumah_suami" rows="2" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Identitas Anak -->
                <div x-show="step === 4" x-transition.opacity>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nama Lengkap Anak</label>
                            <input type="text" x-model="formData.nama_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">NIK Anak</label>
                            <input type="text" x-model="formData.nik_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. JKN/BPJS Anak</label>
                            <input type="text" x-model="formData.no_jkn_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Anak Ke-</label>
                            <input type="number" x-model="formData.anak_ke" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. Akta Kelahiran</label>
                            <input type="text" x-model="formData.no_akta_kelahiran_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Telepon Anak</label>
                            <input type="text" x-model="formData.telepon_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Faskes TK 1 Anak</label>
                            <input type="text" x-model="formData.faskes_tk1_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Faskes Rujukan Anak</label>
                            <input type="text" x-model="formData.faskes_rujukan_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Golongan Darah Anak</label>
                            <select x-model="formData.golongan_darah_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                                <option value="">Pilih</option>
                                <template x-for="g in ['A', 'B', 'AB', 'O']">
                                    <option :value="g" x-text="g"></option>
                                </template>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tempat Lahir Anak</label>
                            <input type="text" x-model="formData.tempat_lahir_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tanggal Lahir Anak</label>
                            <input type="date" x-model="formData.tanggal_lahir_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Alamat Rumah Anak</label>
                            <textarea x-model="formData.alamat_anak" rows="2" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium" placeholder="Kosongkan jika sama dengan Ibu"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Layanan & Pembiayaan -->
                <div x-show="step === 5" x-transition.opacity>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-bold text-purple-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-8 h-[2px] bg-purple-600"></span> Fasilitas Pelayanan Kesehatan
                            </h4>
                        </div>
                        <div class="md:col-span-2">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Identitas Ibu</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Puskesmas Domisili</label>
                            <input type="text" x-model="formData.puskesmas_domisili" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. Reg Kohort Ibu</label>
                            <input type="text" x-model="formData.no_reg_kohort_ibu" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. Catatan Medik RS</label>
                            <input type="text" x-model="formData.no_catatan_medik_rs" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>

                        <div class="md:col-span-2 mt-2">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Identitas Suami</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Puskesmas Domisili Suami</label>
                            <input type="text" x-model="formData.puskesmas_domisili_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. Catatan Medik RS Suami</label>
                            <input type="text" x-model="formData.no_catatan_medik_rs_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>

                        <div class="md:col-span-2 mt-2">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Identitas Anak</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Puskesmas Domisili Anak</label>
                            <input type="text" x-model="formData.puskesmas_domisili_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. Reg Kohort Bayi</label>
                            <input type="text" x-model="formData.no_reg_kohort_bayi" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. Reg Kohort Balita & Pra-Sekolah</label>
                            <input type="text" x-model="formData.no_reg_kohort_balita" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">No. Catatan Medik RS Anak</label>
                            <input type="text" x-model="formData.no_catatan_medik_rs_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>

                        <div class="md:col-span-2 mt-6">
                            <h4 class="text-sm font-bold text-purple-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-8 h-[2px] bg-purple-600"></span> Pembiayaan Lain (Asuransi)
                            </h4>
                        </div>
                        <!-- Section Asuransi Ibu -->
                        <div class="md:col-span-2">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Asuransi Ibu</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nama Asuransi</label>
                            <input type="text" x-model="formData.asuransi_lain" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nomor Kartu</label>
                            <input type="text" x-model="formData.no_asuransi_lain" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tanggal Berlaku</label>
                            <input type="date" x-model="formData.tanggal_berlaku_asuransi_lain" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>

                        <!-- Section Asuransi Suami -->
                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Asuransi Suami</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nama Asuransi Suami</label>
                            <input type="text" x-model="formData.asuransi_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nomor Kartu Suami</label>
                            <input type="text" x-model="formData.no_asuransi_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tanggal Berlaku Suami</label>
                            <input type="date" x-model="formData.tanggal_berlaku_asuransi_suami" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>

                        <!-- Section Asuransi Anak -->
                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Asuransi Anak</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nama Asuransi Anak</label>
                            <input type="text" x-model="formData.asuransi_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Nomor Kartu Anak</label>
                            <input type="text" x-model="formData.no_asuransi_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">Tanggal Berlaku Anak</label>
                            <input type="date" x-model="formData.tanggal_berlaku_asuransi_anak" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                    </div>
                </div>

                <!-- Step 6: Riwayat Kesehatan & Kehamilan -->
                <div x-show="step === 6" x-transition.opacity>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-bold text-purple-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-8 h-[2px] bg-purple-600"></span> Data Kehamilan Saat Ini
                            </h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">HPHT (Hari Pertama Haid Terakhir)</label>
                            <input type="date" x-model="formData.hpht" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-black text-gray-700 uppercase tracking-wider">HTP (Tafsiran Persalinan)</label>
                            <input type="date" x-model="formData.htp" class="w-full px-4 py-3 bg-gray-50 border-2 border-transparent rounded-xl focus:bg-white focus:border-purple-500 outline-none transition-all font-medium">
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between">
                    <button type="button" 
                            x-show="step > 1" 
                            @click="step--"
                            class="group px-8 py-3 text-gray-500 font-bold hover:text-purple-600 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        KEMBALI
                    </button>
                    <div x-show="step === 1"></div>
                    
                    <div class="flex gap-4">
                        <button type="button" 
                                @click="saveData(false)"
                                :disabled="isSaving"
                                class="px-8 py-3 bg-gray-50 text-gray-600 rounded-2xl hover:bg-gray-100 font-bold transition-all shadow-sm">
                            <span x-show="isSaving">...</span>
                            SIMPAN DRAFT
                        </button>
                        
                        <button type="submit" 
                                :disabled="isSaving"
                                class="px-10 py-3 bg-purple-600 text-white rounded-2xl hover:bg-purple-700 font-bold transition-all flex items-center gap-2 shadow-xl shadow-purple-200">
                            <span x-show="isSaving" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                            <span x-text="step < 6 ? 'LANJUTKAN' : 'SELESAI & SIMPAN'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function kiaWizard() {
        return {
            step: 1,
            isSaving: false,
            toast: {
                show: false,
                message: '',
                type: 'success'
            },
            formData: {
                // Step 1: Core Data
                faskes_dikeluarkan: {!! json_encode($dataKia->faskes_dikeluarkan ?? '') !!},
                tanggal_dikeluarkan: {!! json_encode($dataKia->tanggal_dikeluarkan ?? '') !!},
                kab_kota_dikeluarkan: {!! json_encode($dataKia->kab_kota_dikeluarkan ?? '') !!},
                provinsi_dikeluarkan: {!! json_encode($dataKia->provinsi_dikeluarkan ?? '') !!},
                
                // Step 2: Identitas Ibu
                nama_ibu: {!! json_encode($dataKia->ibu->nama ?? '') !!},
                nik: {!! json_encode($dataKia->ibu->nik ?? '') !!},
                no_jkn_ibu: {!! json_encode($dataKia->ibu->no_jkn ?? '') !!},
                faskes_tk1_ibu: {!! json_encode($dataKia->ibu->faskes_tk1 ?? '') !!},
                faskes_rujukan_ibu: {!! json_encode($dataKia->ibu->faskes_rujukan ?? '') !!},
                tempat_lahir: {!! json_encode($dataKia->ibu->tempat_lahir ?? '') !!},
                tanggal_lahir: {!! json_encode($dataKia->ibu->tanggal_lahir ?? '') !!},
                pendidikan: {!! json_encode($dataKia->ibu->pendidikan ?? '') !!},
                pekerjaan: {!! json_encode($dataKia->ibu->pekerjaan ?? '') !!},
                alamat: {!! json_encode($dataKia->ibu->alamat ?? '') !!},
                telepon_ibu: {!! json_encode($dataKia->ibu->telepon ?? '') !!},
                golongan_darah: {!! json_encode($dataKia->ibu->golongan_darah ?? '') !!},

                // Step 3: Identitas Suami
                nama_suami: {!! json_encode($dataKia->suami->nama ?? '') !!},
                nik_suami: {!! json_encode($dataKia->suami->nik ?? '') !!},
                no_jkn_suami: {!! json_encode($dataKia->suami->no_jkn ?? '') !!},
                faskes_tk1_suami: {!! json_encode($dataKia->suami->faskes_tk1 ?? '') !!},
                faskes_rujukan_suami: {!! json_encode($dataKia->suami->faskes_rujukan ?? '') !!},
                tempat_lahir_suami: {!! json_encode($dataKia->suami->tempat_lahir ?? '') !!},
                tanggal_lahir_suami: {!! json_encode($dataKia->suami->tanggal_lahir ?? '') !!},
                pendidikan_suami: {!! json_encode($dataKia->suami->pendidikan ?? '') !!},
                pekerjaan_suami: {!! json_encode($dataKia->suami->pekerjaan ?? '') !!},
                alamat_rumah_suami: {!! json_encode($dataKia->suami->alamat ?? '') !!},
                telepon_suami: {!! json_encode($dataKia->suami->telepon ?? '') !!},
                golongan_darah_suami: {!! json_encode($dataKia->suami->golongan_darah ?? '') !!},

                // Step 4: Identitas Anak
                nama_anak: {!! json_encode($dataKia->anak->nama ?? '') !!},
                nik_anak: {!! json_encode($dataKia->anak->nik ?? '') !!},
                no_jkn_anak: {!! json_encode($dataKia->anak->no_jkn ?? '') !!},
                telepon_anak: {!! json_encode($dataKia->anak->telepon ?? '') !!},
                alamat_anak: {!! json_encode($dataKia->anak->alamat ?? '') !!},
                faskes_tk1_anak: {!! json_encode($dataKia->anak->faskes_tk1 ?? '') !!},
                faskes_rujukan_anak: {!! json_encode($dataKia->anak->faskes_rujukan ?? '') !!},
                tempat_lahir_anak: {!! json_encode($dataKia->anak->tempat_lahir ?? '') !!},
                tanggal_lahir_anak: {!! json_encode($dataKia->anak->tanggal_lahir ?? '') !!},
                anak_ke: {!! json_encode($dataKia->anak->anak_ke ?? '') !!},
                no_akta_kelahiran_anak: {!! json_encode($dataKia->anak->no_akta_kelahiran ?? '') !!},
                golongan_darah_anak: {!! json_encode($dataKia->anak->golongan_darah ?? '') !!},

                // Step 5: Layanan & Pembiayaan
                puskesmas_domisili: {!! json_encode($dataKia->layanan->puskesmas_domisili ?? '') !!},
                no_reg_kohort_ibu: {!! json_encode($dataKia->layanan->no_reg_kohort_ibu ?? '') !!},
                no_reg_kohort_bayi: {!! json_encode($dataKia->layanan->no_reg_kohort_bayi ?? '') !!},
                no_reg_kohort_balita: {!! json_encode($dataKia->layanan->no_reg_kohort_balita ?? '') !!},
                no_catatan_medik_rs: {!! json_encode($dataKia->layanan->no_catatan_medik_rs ?? '') !!},
                asuransi_lain: {!! json_encode($dataKia->layanan->asuransi_lain ?? '') !!},
                no_asuransi_lain: {!! json_encode($dataKia->layanan->no_asuransi_lain ?? '') !!},
                tanggal_berlaku_asuransi_lain: {!! json_encode($dataKia->layanan->tanggal_berlaku_asuransi_lain ?? '') !!},
                
                // Suami
                asuransi_suami: {!! json_encode($dataKia->layanan->asuransi_suami ?? '') !!},
                no_asuransi_suami: {!! json_encode($dataKia->layanan->no_asuransi_suami ?? '') !!},
                tanggal_berlaku_asuransi_suami: {!! json_encode($dataKia->layanan->tanggal_berlaku_asuransi_suami ?? '') !!},
                puskesmas_domisili_suami: {!! json_encode($dataKia->layanan->puskesmas_domisili_suami ?? '') !!},
                no_catatan_medik_rs_suami: {!! json_encode($dataKia->layanan->no_catatan_medik_rs_suami ?? '') !!},

                // Anak
                asuransi_anak: {!! json_encode($dataKia->layanan->asuransi_anak ?? '') !!},
                no_asuransi_anak: {!! json_encode($dataKia->layanan->no_asuransi_anak ?? '') !!},
                tanggal_berlaku_asuransi_anak: {!! json_encode($dataKia->layanan->tanggal_berlaku_asuransi_anak ?? '') !!},
                puskesmas_domisili_anak: {!! json_encode($dataKia->layanan->puskesmas_domisili_anak ?? '') !!},
                no_catatan_medik_rs_anak: {!! json_encode($dataKia->layanan->no_catatan_medik_rs_anak ?? '') !!},

                // Step 6: Riwayat Kesehatan (Nakes only fields removed)
                hpht: {!! json_encode($dataKia->riwayat->hpht ?? '') !!},
                htp: {!! json_encode($dataKia->riwayat->htp ?? '') !!},
                lingkar_lengan_atas: {!! json_encode($dataKia->riwayat->lingkar_lengan_atas ?? '') !!},
                tinggi_badan: {!! json_encode($dataKia->riwayat->tinggi_badan ?? '') !!},
                trimester_1: {!! json_encode($dataKia->riwayat->trimester_1 ?? '') !!},
                trimester_2: {!! json_encode($dataKia->riwayat->trimester_2 ?? '') !!},
                trimester_3: {!! json_encode($dataKia->riwayat->trimester_3 ?? '') !!}
            },
            
            getStepName(s) {
                return {
                    1: 'Info Buku KIA',
                    2: 'Identitas Ibu',
                    3: 'Identitas Suami/Keluarga',
                    4: 'Identitas Anak',
                    5: 'Layanan & Pembiayaan',
                    6: 'Data Kehamilan'
                }[s];
            },

            getStepShortName(s) {
                return { 1: 'Buku', 2: 'Ibu', 3: 'Suami', 4: 'Anak', 5: 'Layanan', 6: 'Hamil' }[s];
            },

            getStepDescription(s) {
                return {
                    1: 'Data fasilitas kesehatan pengeluar Buku KIA.',
                    2: 'Informasi lengkap mengenai identitas Ibu.',
                    3: 'Informasi lengkap mengenai Suami atau Penanggung Jawab.',
                    4: 'Informasi identitas Anak (jika sudah ada).',
                    5: 'Fasilitas kesehatan domisili dan data asuransi.',
                    6: 'Estimasi HPHT dan HTP kehamilan saat ini.'
                }[s];
            },

            async saveData(advanceStep = true) {
                if (this.isSaving) return;
                this.isSaving = true;
                
                try {
                    const response = await fetch('{{ route('pengguna.kia.wizard.save') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.showToast(advanceStep && this.step < 6 ? 'Data tersimpan, lanjut...' : 'Data berhasil disimpan!', 'success');
                        
                        if (advanceStep && this.step < 6) {
                            this.step++;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        } else if (advanceStep && this.step === 6) {
                            this.showToast('Semua data KIA telah berhasil disimpan!', 'success');
                            setTimeout(() => {
                                window.location.href = '{{ route('pengguna.dashboard') }}';
                            }, 2000);
                        }
                    } else {
                        this.showToast('Gagal menyimpan data.', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    this.showToast('Terjadi kesalahan jaringan.', 'error');
                } finally {
                    this.isSaving = false;
                }
            },
            
            showToast(message, type) {
                this.toast.message = message;
                this.toast.type = type;
                this.toast.show = true;
                setTimeout(() => {
                    this.toast.show = false;
                }, 3000);
            }
        }
    }
</script>
@endpush
@endsection
