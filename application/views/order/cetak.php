<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <title>Cetak Order</title>
    <style>
      .break {
        page-break-after: always; 
      }
    </style>
  </head>
  <body>
    <div class="container-fluid">
      <div class="mt-2">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h3 class="text-center mb-4">Laporan Order</h3>
                <br>
                <table width="100%" border='0'>
                  <tr>
                    <td width="20%">Tanggal Order</td>
                    <td>: <?= $order['tgl_order'] ?></td>
                  </tr>
                  <tr>
                    <td width="20%">Nama Pelanggan</td>
                    <td>: <?= $order['nama_pelanggan'] ?> (<?= $order['no_telepon'] ?>)</td>
                  </tr>
                  <tr>
                    <td width="20%">Produk</td>
                    <td>: <?= $order['nama_produk'] ?></td>
                  </tr>
                  <tr>
                    <td width="20%">Nama Marketing</td>
                    <td>: <?= $order['nama'] ?></td>
                  </tr>
                  <tr>
                    <td width="20%">Catatan</td>
                    <td>: <?= $order['catatan'] ?></td>
                  </tr>
                </table>
                <br>
                <h4>Jumlah Order :</h4>
                <table width="100%" border="1">
                  <tr class="text-center">
                    <th>Ukuran</th>
                    <th>Jumlah</th>
                  </tr>
                  <tr class="text-center">
                    <td>S</td>
                    <td><?= $order['jumlah_ukuran_s'] ?></td>
                  </tr>
                  <tr class="text-center">
                    <td>M</td>
                    <td><?= $order['jumlah_ukuran_m'] ?></td>
                  </tr>
                  <tr class="text-center">
                    <td>L</td>
                    <td><?= $order['jumlah_ukuran_l'] ?></td>
                  </tr>
                  <tr class="text-center">
                    <td>XL</td>
                    <td><?= $order['jumlah_ukuran_xl'] ?></td>
                  </tr>
                  <tr class="text-center">
                    <td>XXL</td>
                    <td><?= $order['jumlah_ukuran_xxl'] ?></td>
                  </tr>
                </table>

                <br>
                <h4>Pegawai Cutting :</h4>
                <table width="100%" border="1">
                  <tr class="text-center">
                    <th width="3%">No</th>
                    <th width="30%">Nama Pegawai</th>
                    <th width="7%">Jumlah</th>
                    <th width="15%">Upah</th>
                    <th width="15%">Total</th>
                    <th width="15%">Kasbon</th>
                    <th width="15%">Total Upah</th>
                  </tr>
                  <?php
                    $no = 1;
                    foreach ($pegawai_cutting as $key) {
                        $upah = $key['jumlah']*$key['harga'];
                        $total_upah = $upah - $key['kasbon'];
                      ?>
                      <tr>
                        <td width="3%" class="text-center"><?= $no++ ?></td>
                        <td width="30%"><?= $key['nama'] ?></td>
                        <td class="text-center"><?= $key['jumlah'] ?></td>
                        <td class="text-right">Rp <?= number_format($key['harga'], 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($upah, 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($key['kasbon'], 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($total_upah, 2, '.', ',') ?></td>
                      </tr>
                    <?php } ?>
                </table>

                <br>
                <h4>Pegawai Jahit :</h4>
                <table width="100%" border="1">
                  <tr class="text-center">
                    <th width="3%">No</th>
                    <th width="30%">Nama Pegawai</th>
                    <th width="7%">Jumlah</th>
                    <th width="15%">Upah</th>
                    <th width="15%">Total</th>
                    <th width="15%">Kasbon</th>
                    <th width="15%">Total Upah</th>
                  </tr>
                  <?php
                    $no = 1;
                    foreach ($pegawai_jahit as $key) {
                        $upah = $key['jumlah']*$key['harga'];
                        $total_upah = $upah - $key['kasbon'];
                      ?>
                      <tr>
                        <td width="3%" class="text-center"><?= $no++ ?></td>
                        <td width="30%"><?= $key['nama'] ?></td>
                        <td class="text-center"><?= $key['jumlah'] ?></td>
                        <td class="text-right">Rp <?= number_format($key['harga'], 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($upah, 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($key['kasbon'], 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($total_upah, 2, '.', ',') ?></td>
                      </tr>
                    <?php } ?>
                </table>

                <br>
                <h4>Pegawai QC / Packaging :</h4>
                <table width="100%" border="1">
                  <tr class="text-center">
                    <th width="3%">No</th>
                    <th width="30%">Nama Pegawai</th>
                    <th width="7%">Jumlah</th>
                    <th width="15%">Upah</th>
                    <th width="15%">Total</th>
                    <th width="15%">Kasbon</th>
                    <th width="15%">Total Upah</th>
                  </tr>
                  <?php
                    $no = 1;
                    foreach ($pegawai_qc as $key) {
                        $upah = $key['jumlah']*$key['harga'];
                        $total_upah = $upah - $key['kasbon'];
                      ?>
                      <tr>
                        <td width="3%" class="text-center"><?= $no++ ?></td>
                        <td width="30%"><?= $key['nama'] ?></td>
                        <td class="text-center"><?= $key['jumlah'] ?></td>
                        <td class="text-right">Rp <?= number_format($key['harga'], 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($upah, 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($key['kasbon'], 2, '.', ',') ?></td>
                        <td class="text-right">Rp <?= number_format($total_upah, 2, '.', ',') ?></td>
                      </tr>
                    <?php } ?>
                </table>
                
              </div>
            </div>
          </div>
        </div>
      </div>
      

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script>
      //window.print();
    </script>
  </body>
</html>