<?php
session_start();
$page = 'Training';
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
include('includes/header.php');
include('includes/sidebar.php');
include('config/dbcon.php'); 

$filterParams = http_build_query([
    'nama' => $_GET['nama'] ?? '',
    'tanggal_mulai' => $_GET['tanggal_mulai'] ?? '',
    'tanggal_akhir' => $_GET['tanggal_akhir'] ?? ''
]);
/* =======================
   PAGINATION
======================= */
$perPage = 20;
$halaman = isset($_GET['halaman']) ? max((int)$_GET['halaman'], 1) : 1;
$offset  = ($halaman - 1) * $perPage;

/* =======================
   FILTER INPUT (SAFE)
======================= */
$nama          = mysqli_real_escape_string($con, $_GET['nama'] ?? '');
$tanggal_mulai = mysqli_real_escape_string($con, $_GET['tanggal_mulai'] ?? '');
$tanggal_akhir = mysqli_real_escape_string($con, $_GET['tanggal_akhir'] ?? '');

/* =======================
   DEFAULT BULAN BERJALAN
======================= */
if ($tanggal_mulai === '' && $tanggal_akhir === '') {
    $tanggal_mulai = date('Y-m-01');
    $tanggal_akhir = date('Y-m-t');
}

/* =======================
   BUILD WHERE
======================= */
$where = [];
$where[] = "tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";

if ($nama !== '') {
    $where[] = "nama LIKE '%$nama%'";
}

$whereSQL = 'WHERE ' . implode(' AND ', $where);

/* =======================
   TOTAL DATA (COUNT)
======================= */
$totalQuery = mysqli_query(
    $con,
    "SELECT COUNT(*) AS total FROM pelatihan $whereSQL"
);
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$pages = ceil($totalData / $perPage);

/* =======================
   TOTAL DURASI (SUM)
======================= */
$totalDurasiQuery = mysqli_query(
    $con,
    "SELECT COALESCE(SUM(durasi_jam),0) AS total_durasi FROM pelatihan $whereSQL"
);
$totalDurasi = mysqli_fetch_assoc($totalDurasiQuery)['total_durasi'];

/* =======================
   DATA TABEL
======================= */
$query = "
    SELECT id_log, tanggal, nama, departemen, judul_pelatihan,
           pemateri, pretest, posttest, durasi_jam
    FROM pelatihan
    $whereSQL
    ORDER BY tanggal DESC
    LIMIT $perPage OFFSET $offset
";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}


?>


<main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <!-- Navbar -->
      <?php
      $page_title = "Training";
      include('includes/topbar.php')
      ?>

      <div class="w-full px-6 py-6 mx-auto">
        <!-- table 1 -->

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Daftar Training</h6>
                <form method="GET" class="filter-bar">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                    <input type="text"
                          name="nama"
                          placeholder="Nama"
                          class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-40 mr-2">
                    
                    <input type="date"
                          name="tanggal_mulai"
                          class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 mr-2">

                    <input type="date"
                          name="tanggal_akhir"
                          class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 mr-2">

                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-gradient rounded-lg hover:btn-blue-gradient:hover mr-2">
                      Tampilkan
                    </button>
                    
                    <a href="tbhpelatihan.php"
                      class=" ml-auto px-4 py-2 text-sm font-semibold text-white bg-blue-gradient rounded-lg hover:bg-green mr-2">
                      Tambah Log
                    </a>
                  </div>
                  </form>
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

                        <table class="custom-table">
                            <thead>
                                <tr>
                            <th class="center">No</th>
                            <th class="center">Tanggal</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Judul Pelatihan</th>
                            <th>Pemateri</th>
                            <th class="center">N. Pre-test</th>
                            <th class="center">N. Post-test</th>
                            <th class="center">Durasi</th>
                            <th class="center">Aksi</th>
                        </tr>
                            </thead>

                    <tbody>
                    <?php 
                   $no = $offset + 1;
                    while ($row = mysqli_fetch_assoc($result)) { 
                    ?>
                        <tr>
                        <td class="center"><?= $no++; ?></td>
                        <td class="center"><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                        <td><?= $row['nama']; ?></td>
                        <td><?= $row['departemen']; ?></td>
                        <td><?= $row['judul_pelatihan']; ?></td>
                        <td><?= $row['pemateri']; ?></td>
                        <td class="center"><?= $row['pretest']; ?></td>
                        <td class="center"><?= $row['posttest']; ?></td>
                        <td class="center"><?= $row['durasi_jam']; ?></td>
                            <td class="center aksi-btn">
                            <button 
                            class="btn-icon edit"
                            onclick="openEditModal(
                                '<?= $row['id_log']; ?>',
                                '<?= $row['tanggal']; ?>',
                                '<?= htmlspecialchars($row['nama']); ?>',
                                '<?= htmlspecialchars($row['departemen']); ?>',
                                '<?= htmlspecialchars($row['judul_pelatihan']); ?>',
                                '<?= htmlspecialchars($row['pemateri']); ?>',
                                '<?= $row['pretest']; ?>',
                                '<?= $row['posttest']; ?>',
                                '<?= $row['durasi_jam']; ?>'
                            )"
                            >
                            <i class="fas fa-edit"></i>
                            </button>

                                <button 
                                class="btn-icon delete btn-delete"
                                data-url="pelatihan_hapus.php?id_log=<?= $row['id_log']; ?>"
                                >
                                <i class="fas fa-trash-alt"></i>
                                </button>


                            </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align:center;"></th>
                            <th style="text-align:left; color:#000;">Total Durasi Pelatihan</th>
                            <th class="center" style="color:#000;"><?= $totalDurasi; ?> Jam</th>
                            <th></th>
                        </tr>
                    </tfoot>
                        </table>
                        <?php if ($pages > 1) { ?>
                         <!-- Navigasi pagination -->
                            <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <?php
                                $limit = 5; // jumlah nomor halaman yang ditampilkan
                                $start = max(1, $halaman - floor($limit / 2));
                                $end = min($pages, $start + $limit - 1);

                                // Adjust kalau posisi di awal/akhir
                                if ($end - $start + 1 < $limit) {
                                    $start = max(1, $end - $limit + 1);
                                }

                                // Tombol Prev
                                if ($halaman > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?halaman=' . ($halaman - 1) . '&' . $filterParams . '">&laquo;</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
                                }

                                // Tampilkan halaman pertama + ellipsis
                                for ($i = $start; $i <= $end; $i++) {
                                    $active = ($i == $halaman) ? 'active' : '';
                                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="?halaman=' . $i . '&' . $filterParams . '">' . $i . '</a></li>';
                                }

                                // Halaman tengah
                                if ($halaman < $pages) {
                                    echo '<li class="page-item"><a class="page-link" href="?halaman=' . ($halaman + 1) . '&' . $filterParams . '">&raquo;</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
                                }

                                // Tampilkan halaman terakhir + ellipsis
                                if ($end < $pages) {
                                    if ($end < $pages - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="?halaman=' . $pages . '">' . $pages . '</a></li>';
                                }

                                // Tombol Next
                                if ($halaman < $pages) {
                                    echo '<li class="page-item"><a class="page-link" href="?halaman=' . ($halaman + 1) . '">&raquo;</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <?php } ?>
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

function openEditModal(
    id_log,
    tanggal,
    nama,
    departemen,
    judul,
    pemateri,
    pretest,
    posttest,
    durasi
) {
    Swal.fire({
        title: 'Edit Training',
        width: 600,

        html: `
        <form id="editForm">
            <input type="hidden" name="id_log" value="${id_log}">

            <div class="swal-grid">

                <label>Tanggal</label>
                <input type="date" name="tanggal" value="${tanggal}" required>

                <label>Nama</label>
                <input type="text" name="nama" value="${nama}" required>

                <label>Departemen</label>
                <input type="text" name="departemen" value="${departemen}" required>

                <label>Judul Pelatihan</label>
                <input type="text" name="judul_pelatihan" value="${judul}" required>

                <label>Pemateri</label>
                <input type="text" name="pemateri" value="${pemateri}">

                <label>N. Pre-test</label>
                <input type="number" name="pretest" value="${pretest}" required>

                <label>N. Post-test</label>
                <input type="number" name="posttest" value="${posttest}" required>

                <label>Durasi (Jam)</label>
                <input type="number" name="durasi_jam" value="${durasi}" min="0" required>

            </div>
        </form>
        `,

        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        focusConfirm: false,

        preConfirm: () => {
            const form = document.getElementById('editForm');
            if (!form.checkValidity()) {
                Swal.showValidationMessage('Lengkapi semua data wajib');
                return false;
            }
            return new FormData(form);
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('pelatihan_update.php', {
                method: 'POST',
                body: result.value
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            });
        }
    });
}
</script>





    <?php
    include('includes/footer.php');
    ?>
    