 @if (count($formOrderTanpaInvoice))
     <div class="card mt-4">
         <div class="card-status-top bg-black"></div>
         <div class="card-body">
             <div class="card-title">
                 Item Tidak Masuk Invoice
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
                     @foreach ($formOrderTanpaInvoice as $index => $item)
                         <tr>
                             <td>
                                 {{ $index + 1 }}
                             </td>
                             <td>
                                 {{ $item->nama }}
                             </td>
                             <td>
                                 Rp. {{ number_format($item->harga_modal) }}
                             </td>
                             <td>
                                 Rp. {{ number_format($item->harga_jual) }}
                             </td>
                         </tr>
                     @endforeach
                 </tbody>
             </table>
         </div>
     </div>
 @endif
