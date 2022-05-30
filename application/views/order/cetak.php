<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container-fluid">
      <h3 class="text-center">BOM List</h3>
      <div class="row">
        <div class="col-md-2">Tanggal Order</div>
        <div class="col-md-10">: <?= $order['tgl_order'] ?></div>
        <div class="col-md-2">Nama Pelanggan</div>
        <div class="col-md-10">: <?= $order['nama_pelanggan'] ?> (<?= $order['no_telepon'] ?>)</div>
        <div class="col-md-2">Produk</div>
        <div class="col-md-10">: <?= $order['nama_produk'] ?></div>
        <div class="col-md-2">Nama Marketing</div>
        <div class="col-md-10">: <?= $order['nama'] ?></div>
        <div class="col-md-2">Catatan</div>
        <div class="col-md-10">: <?= $order['catatan'] ?></div>
      </div>
      <br>
      <h6>Jumlah Order :</h6>
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
      <h6>Kebutuhan Kain :</h6>
      <table width="100%" border="1">
        <tr class="text-center">
          <th>Ukuran</th>
          <th>Panjang Kain (m)</th>
          <th>Biaya</th>
        </tr>
        <tr class="text-center">
          <td>S</td>
          <td><?= $order['jumlah_ukuran_s'] * $produk['pnj_kain_s'] ?></td>
          <td>Rp <?= number_format($order['jumlah_ukuran_s'] * $produk['pnj_kain_s'] * $produk['harga_kain'], '2', ',', '.')  ?></td>
        </tr>
        <tr class="text-center">
          <td>M</td>
          <td><?= $order['jumlah_ukuran_m'] * $produk['pnj_kain_m'] ?></td>
          <td>Rp <?= number_format($order['jumlah_ukuran_m'] * $produk['pnj_kain_m'] * $produk['harga_kain'], '2', ',', '.')  ?></td>
        </tr>
        <tr class="text-center">
          <td>L</td>
          <td><?= $order['jumlah_ukuran_l'] * $produk['pnj_kain_l'] ?></td>
          <td>Rp <?= number_format($order['jumlah_ukuran_l'] * $produk['pnj_kain_l'] * $produk['harga_kain'], '2', ',', '.')  ?></td>
        </tr>
        <tr class="text-center">
          <td>XL</td>
          <td><?= $order['jumlah_ukuran_xl'] * $produk['pnj_kain_xl'] ?></td>
          <td>Rp <?= number_format($order['jumlah_ukuran_xl'] * $produk['pnj_kain_xl'] * $produk['harga_kain'], '2', ',', '.')  ?></td>
        </tr>
        <tr class="text-center">
          <td>XXL</td>
          <td><?= $order['jumlah_ukuran_xxl'] * $produk['pnj_kain_xxl'] ?></td>
          <td>Rp <?= number_format($order['jumlah_ukuran_xxl'] * $produk['pnj_kain_xxl'] * $produk['harga_kain'], '2', ',', '.')  ?></td>
        </tr>
        <tr class="text-center">
          <th>Total</th>
          <th><?= $order['jumlah_ukuran_s'] * $produk['pnj_kain_s'] + $order['jumlah_ukuran_m'] * $produk['pnj_kain_m'] + $order['jumlah_ukuran_l'] * $produk['pnj_kain_l'] + $order['jumlah_ukuran_xl'] * $produk['pnj_kain_xl'] + $order['jumlah_ukuran_xxl'] * $produk['pnj_kain_xxl'] ?></th>
          <th>
            Rp <?= number_format(
              $order['jumlah_ukuran_s'] * $produk['pnj_kain_s'] * $produk['harga_kain'] + 
              $order['jumlah_ukuran_xxl'] * $produk['pnj_kain_xxl'] * $produk['harga_kain'] + 
              $order['jumlah_ukuran_l'] * $produk['pnj_kain_l'] * $produk['harga_kain'] + 
              $order['jumlah_ukuran_xl'] * $produk['pnj_kain_xl'] * $produk['harga_kain'] +
              $order['jumlah_ukuran_xxl'] * $produk['pnj_kain_xxl'] * $produk['harga_kain']
            , '2', ',', '.')  ?>
          </th>
        </tr>
      </table>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
      window.print();
    </script>
  </body>
</html>