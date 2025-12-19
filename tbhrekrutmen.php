<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
include('includes/header.php');
include('includes/sidebar.php');
include('config/dbcon.php'); 

?>

<main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <!-- Navbar -->
      <?php
      $page_title = "Recruitment";
      include('includes/topbar.php')
      ?>

      <div class="w-full px-6 py-6 mx-auto">
        <!-- table 1 -->

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Daftar Rekrutmen</h6>
                
              </div>
              <div class="flex-auto px-0 pt-0 pb-2">
                
                <div class="p-0 overflow-x-auto">
                    
                  <div class="table-box">
                    
                    <?php if (isset($_SESSION['status'])): ?>
                    <div class="alert alert-<?= $_SESSION['status_type'] ?? 'success'; ?> alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?= $_SESSION['status']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <?php
                    unset($_SESSION['status'], $_SESSION['status_type']);
                    endif;
                    ?>
                    <form action="code.php" method="POST" autocomplete="off">
          <div class="card-body">
            <div class="form-group">
              <label for="tanggal">Tanggal</label>
              <input type="date" name="tanggal" class="form-control" style="max-width: 200px;" required>
            </div>
            <div class="form-group">
              <label for="kode_mesin">Nomor Mesin</label>
              <input type="text" name="kode_mesin" id="kode_mesin" class="form-control" placeholder="Ketik nomor atau nama mesin" autocomplete="off" required>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nomor_wo">Nomor WO</label>
                    <input type="text" name="nomor_wo" class="form-control" placeholder="Masukkan Nomor WO" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="nik_prod">Amano Prod</label>
                    <input type="text" name="nik_prod" class="form-control" placeholder="Masukkan NIK Prod" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nik">Amano MTC</label>
                    <input type="text" name="nik_mekanik" id="nik_mekanik" class="form-control" placeholder="Masukkan NIK" required onkeyup="tampilkanNama()">
                </div>
                <div class="form-group col-md-6">
                    <label>Nama Mekanik</label>
                    <input type="text" id="nama" class="form-control" placeholder="Nama akan muncul otomatis" readonly>
                </div>
                </div>
            <div class="form-group">
              <label for="kriteria">Kriteria Kerusakan</label>
              <input type="text" name="kriteria" id="kriteria" class="form-control" placeholder="Ketik kriteria kerusakan" required />
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="kode_part1">Kode Part 1</label>
                <input type="text" name="kode_part1" class="form-control kode_part" placeholder="Masukkan Kode Part">
              </div>
              <div class="form-group col-md-3">
                <label>Jumlah Part 1</label>
                <input type="number" id="jumlah1" name="jumlah1" class="form-control big-spinner" placeholder="Jumlah Part" min="0" step="1" value="0">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="kode_part2">Kode Part 2</label>
                <input type="text" name="kode_part2" class="form-control kode_part" placeholder="Masukkan Kode Part">
              </div>
              <div class="form-group col-md-3">
                <label>Jumlah Part 2</label>
                <input type="number" id="jumlah2" name="jumlah2" class="form-control big-spinner" placeholder="Jumlah Part" min="0" step="1" value="0">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="kode_part3">Kode Part 3</label>
                <input type="text" name="kode_part3" class="form-control kode_part" placeholder="Masukkan Kode Part">
              </div>
              <div class="form-group col-md-3">
                <label>Jumlah Part 3</label>
                <input type="number" id="jumlah3" name="jumlah3" class="form-control big-spinner" placeholder="Jumlah Part" min="0" step="1" value="0">
              </div>
            </div>

            <div class="form-group">
              <label for="Action MTC/UTY">Action MTC/UTY</label>
              <input type="text" name="tindakan" id="tindakan" class="form-control" placeholder="Ketik Action Mekanik" required />
            </div>
            <input type="hidden" name="kode_downtime" id="kode_kodedowntime" />
            <div class="form-group">
                <label for="jam_mulai">Jam Mulai</label>
                <input type="text" name="jam_mulai" id="jam_mulai" class="form-control" placeholder="HH:MM" maxlength="5" required>
            </div>

            <div class="form-group">
                <label for="jam_selesai">Jam Selesai</label>
                <input type="text" name="jam_selesai" id="jam_selesai" class="form-control" placeholder="HH:MM" maxlength="5" required>
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select name="status" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="Major">Major</option>
                <option value="Minor">Minor</option>
              </select>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="downtime.php" class="btn btn-secondary">Batal</a>
          </div>
        </form>
                        
                        
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        
        
      </div>
    </main>

<script>
  setTimeout(() => {
    $('.alert').alert('close');
  }, 4000);

$('.btn-delete').on('click', function (e) {
    e.preventDefault();

    let url = $(this).data('url');

    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',      // MERAH
        cancelButtonColor: '#6c757d',    // ABU
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
});
</script>





    <?php
    include('includes/footer.php');
    ?>
    