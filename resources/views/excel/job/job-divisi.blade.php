 <table class="table table-bordered ">
     <thead>
         <tr>
             <th>Kode</th>
             <th>Status</th>
             <th>Nama Penghadap </th>
             <th>Bank</th>
             <th>Objek</th>
             <th>Jenis Akad</th>
             <th>Tanggal Akad</th>
             <th>Estimasi Selesai Internal</th>
             <th>Estimasi Selesai Eksternal</th>

         </tr>
     </thead>
     <tbody>
         @forelse ($items as $item)
             <tr>
                <th>
                    {{ $item->kode }}
                </th>
                <td style="text-transform: uppercase " class="">
                    @php
                        $statusClass = [
                            'Pra Akad' => 'bg-info',
                            'Akad' => 'bg-success',
                            'Pending' => 'bg-warning',
                            'Batal Akad' => 'bg-danger',
                            'Selesai' => 'bg-primary',
                        ];
                    @endphp
                    <span class="badge {{ $statusClass[$item->status] ?? 'bg-dark' }} text-white p-2">
                        {{ $item->is_pending ? 'Pending' : $item->status }}
                    </span>
                </td>
                <td style="text-transform: uppercase">
                    {{ implode(', ', $item->debitur->pluck('nama')->toArray()) }}
                    {{-- {{ dd($item) }} --}}
                </td>
                <td>
                    @foreach ($item->listBank as $bank)
                        {{ $bank->nama_bank }}
                    @endforeach
                </td>
                <td>
                    {{ $item->objek->pluck('no_sertifikat')->implode(', ') }}
                </td>
                <td>
                    {{ $item->jenisAkad->nama }}
                </td>
                <td>
                    {{ $item->tanggal_akad ? $item->tanggal_akad : $item->tanggal_rencana_akad }}
                </td>
                <td>
                    {{ $item->tanggal_estimasi_selesai }}
                </td>
                <td>
                    {{ $item->tanggal_estimasi_selesai_eksternal }}
                </td>

             </tr>
         @empty
             <tr>
                 <td colspan="8" class="text-center">
                     Data Tidak Tersedia
                 </td>
             </tr>
         @endforelse
     </tbody>
 </table>
