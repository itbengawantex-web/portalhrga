<?php
session_start();
$page = 'rekrutmen';
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
include('includes/header.php');
include('includes/sidebar.php');
include('config/dbcon.php'); 

$perPage = 20; // jumlah data per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman = ($halaman < 1) ? 1 : $halaman;

$offset = ($halaman - 1) * $perPage;
$nama          = $_GET['nama'] ?? '';
$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
$posisi        = $_GET['posisi'] ?? '';
$status        = $_GET['status'] ?? '';

$where = [];

// filter tanggal
if ($tanggal_mulai != '' && $tanggal_akhir != '') {
    $where[] = "tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
} elseif ($tanggal_mulai != '') {
    $where[] = "tanggal >= '$tanggal_mulai'";
} elseif ($tanggal_akhir != '') {
    $where[] = "tanggal <= '$tanggal_akhir'";
}

// filter nama (INI FIX)
if ($nama != '') {
    $where[] = "nama_rek LIKE '%$nama%'";
}

// filter posisi
if ($posisi != '') {
    $where[] = "posisi LIKE '%$posisi%'";
}

// filter status
if ($status != '') {
    $where[] = "status = '$status'";
}

$whereSQL = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$totalQuery = mysqli_query(
    $con,
    "SELECT COUNT(*) AS total FROM rekrutmen $whereSQL"
);

$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$pages = ceil($totalData / $perPage);


$query = "SELECT * FROM rekrutmen
          $whereSQL
          ORDER BY tanggal DESC
          LIMIT $perPage OFFSET $offset";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($con));
}

?>
<link rel="stylesheet" href="assets/css/custom.css">
<style>




</style>

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
                <form method="GET" class="filter-bar">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                    <input type="text"
                          name="nama"
                          placeholder="Nama"
                          class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-40 mr-2">
                    <select name="status"
                            class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 w-40 mr-2">
                      <option value="">-- Status --</option>
                      <option value="DITERIMA">DITERIMA</option>
                      <option value="PENDING">PENDING</option>
                      <option value="DITOLAK">DITOLAK</option>
                    </select>
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
                    
                    <a href="tbhrekrutmen.php"
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
                            <th>Posisi</th>
                            <th class="center">Psikotes</th>
                            <th class="center">Interview HR</th>
                            <th class="center">Interview User</th>
                            <th class="center">Status</th>
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
                        <td><?= $row['nama_rek']; ?></td>
                        <td><?= $row['posisi']; ?></td>
                        <td class="center"><?= $row['psikotes']; ?></td>
                        <td class="center"><?= $row['interview_hr']; ?></td>
                        <td class="center"><?= $row['interview_user']; ?></td>
                        <td class="center">
                            <?php
                            $status = strtolower($row['status']);

                            if ($status == 'diterima') {
                                echo '<span class="status-label status-diterima">Diterima</span>';
                            } elseif ($status == 'ditolak') {
                                echo '<span class="status-label status-ditolak">Ditolak</span>';
                            } else {
                                echo '<span class="status-label status-pending">Pending</span>';
                            }
                            ?>
                            </td>
                            <td class="center aksi-btn">
                            <button 
                                class="btn-icon edit"
                                onclick="openEditModal(
                                    '<?= $row['rek_no']; ?>',
                                    '<?= $row['tanggal']; ?>',
                                    '<?= $row['nama_rek']; ?>',
                                    '<?= $row['posisi']; ?>',
                                    '<?= $row['psikotes']; ?>',
                                    '<?= $row['interview_hr']; ?>',
                                    '<?= $row['interview_user']; ?>',
                                    '<?= $row['status']; ?>'
                                )"
                            >
                                <i class="fas fa-edit"></i>
                            </button>

                                <button 
                                class="btn-icon delete btn-delete"
                                data-url="rekruitmen_hapus.php?rek_no=<?= $row['rek_no']; ?>"
                                >
                                <i class="fas fa-trash-alt"></i>
                                </button>


                            </td>
                    </tr>
                    <?php } ?>
                    </tbody>
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
                                    echo '<li class="page-item"><a class="page-link" href="?halaman=' . ($halaman - 1) . '">&laquo;</a></li>';
                                } else {
                                    echo '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
                                }

                                // Tampilkan halaman pertama + ellipsis
                                if ($start > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?halaman=1">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                // Halaman tengah
                                for ($i = $start; $i <= $end; $i++) {
                                    $active = ($i == $halaman) ? 'active' : '';
                                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="?halaman=' . $i . '">' . $i . '</a></li>';
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
    rek_no,
    tanggal,
    nama,
    posisi,
    psikotes,
    interview_hr,
    interview_user,
    status
) {
    Swal.fire({
        title: 'Edit Rekrutmen',
        width: 600,

        /* 👉 TARUH html DI SINI */
        html: `
        <form id="editForm">
            <input type="hidden" name="rek_no" value="${rek_no}">

            <div class="swal-grid">

                <label>Tanggal</label>
                <input type="date" name="tanggal" value="${tanggal}" required>

                <label>Nama</label>
                <input type="text" name="nama_rek" value="${nama}" required>

                <label>Posisi</label>
                <input type="text" name="posisi" value="${posisi}" required>

                <label>Psikotes</label>
                <input type="number" name="psikotes" value="${psikotes}">

                <label>Interview HR</label>
                <input type="number" name="interview_hr" value="${interview_hr}">

                <label>Interview User</label>
                <input type="number" name="interview_user" value="${interview_user}">

                <label>Status</label>
                <select name="status" required>
                    <option value="PENDING" ${status=='PENDING'?'selected':''}>PENDING</option>
                    <option value="DITERIMA" ${status=='DITERIMA'?'selected':''}>DITERIMA</option>
                    <option value="DITOLAK" ${status=='DITOLAK'?'selected':''}>DITOLAK</option>
                </select>

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
            fetch('rekrutmen_update.php', {
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
    