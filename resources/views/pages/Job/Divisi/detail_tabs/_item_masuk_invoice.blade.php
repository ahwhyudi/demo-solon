 {{-- {{ dd($formOrderTanpaInvoice) }} --}}
 @if (count($formOrderDenganInvoice))
     <div class="card mt-4">
         <div class="card-status-top bg-blue"></div>
         <div class="card-body">
             <div class="card-title">
                 Item Masuk Invoice
             </div>
         </div>
         <div class="table-responsive">
             <table class="table table-striped">
                 <thead>
                     <tr>
                         <th>
                             No
                         </th>
                         <th>
                             Nama
                         </th>
                         <th>
                             Harga Modal
                         </th>
                         <th>
                             Harga Jual
                         </th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach ($formOrderDenganInvoice as $index => $item)
                         <tr>
                             <td>
                                 {{ $index + 1 }}
                             </td>
                             <td>
                                 {{ $item->nama }}
                             </td>
                             <td>
                                 Rp. {{ number_format($item->harga_modal) }}
                                 @if ($item->kategori === 'pajak')
                                     <div class="text-secondary">
                                         <small>
                                             Estimasi Pajak
                                         </small>
                                     </div>
                                 @endif
                             </td>
                             <td>
                                 Rp. {{ number_format($item->harga_jual) }}
                                 @if ($item->kategori === 'pajak')
                                     <div class="text-secondary">
                                         <small>
                                             Realisasi Pajak
                                         </small>
                                     </div>
                                 @endif
                             </td>
                         </tr>
                     @endforeach
                 </tbody>
             </table>
         </div>
     </div>
 @endif
