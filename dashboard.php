<?php
session_start();
 $page = 'dashboard'; 
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
include('includes/header.php');
include('includes/sidebar.php');
include('config/dbcon.php'); 
$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$whereTanggal = "";

// DEFAULT → BULAN INI
if ($tanggal_mulai == '' && $tanggal_akhir == '') {
    $whereTanggal = "
        tanggal >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
        AND tanggal <= LAST_DAY(CURDATE())
    ";
}
// FILTER RANGE
elseif ($tanggal_mulai != '' && $tanggal_akhir != '') {
    $whereTanggal = "
        tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'
    ";
}
elseif ($tanggal_mulai != '') {
    $whereTanggal = "
        tanggal >= '$tanggal_mulai'
    ";
}
elseif ($tanggal_akhir != '') {
    $whereTanggal = "
        tanggal <= '$tanggal_akhir'
    ";
}
// =======================
// CARD 1: TOTAL REKRUTMEN BULAN INI
// =======================
$qRecruit = mysqli_query(
    $con,
    "SELECT COUNT(*) AS total
     FROM rekrutmen
     WHERE $whereTanggal"
);
$totalRecruit = mysqli_fetch_assoc($qRecruit)['total'] ?? 0;


// =======================
// CARD 2: TOTAL REKRUTMEN DITERIMA BULAN INI
// =======================
$qAccepted = mysqli_query(
    $con,
    "SELECT COUNT(*) AS total
     FROM rekrutmen
     WHERE status = 'Diterima'
     AND $whereTanggal"
);
$totalAccepted = mysqli_fetch_assoc($qAccepted)['total'] ?? 0;


// =======================
// CARD 3: TOTAL JAM PELATIHAN BULAN INI
// =======================
$qTrainingHours = mysqli_query(
    $con,
    "SELECT SUM(durasi_jam) AS total
     FROM pelatihan
     WHERE $whereTanggal"
);
$totalTrainingHours = mysqli_fetch_assoc($qTrainingHours)['total'] ?? 0;


// =======================
// CARD 4: TOTAL ORANG IKUT PELATIHAN BULAN INI
// =======================
$qParticipants = mysqli_query(
    $con,
    "SELECT COUNT(DISTINCT nama) AS total
     FROM pelatihan
     WHERE $whereTanggal"
);
$totalParticipants = mysqli_fetch_assoc($qParticipants)['total'] ?? 0;
?>

   

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <!-- Navbar -->
      <?php
      $page_title = "Dashboard";
      include('includes/topbar.php')
      ?>
<!-- <form method="GET" class="filter-bar">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                    <input type="date"
                          name="tanggal_mulai"
                          class=" text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 mr-2">

                    <input type="date"
                          name="tanggal_akhir"
                          class=" text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 mr-2">

                    <button type="submit"
                            class=" text-sm font-semibold text-white bg-red rounded-lg hover:btn-blue-gradient:hover mr-2">
                      Tampilkan
                    </button>
                    </a>
                  </div>
                  </form> -->
      <!-- cards -->
      <div class="w-full px-6 py-6 mx-auto">
        <!-- row 1 -->
        
        <div class="flex flex-wrap -mx-3">
          <!-- card1 -->
          
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Recruitment This Month</p>
                      <h5 class="mb-2 font-bold dark:text-white"><?=$totalRecruit; ?></h5>
                      <p class="mb-0 dark:text-white dark:opacity-60">
                        <span class="text-sm font-bold leading-normal text-red-600"><?php
                            if ($tanggal_mulai || $tanggal_akhir) {
                                echo "Periode: " .
                                    ($tanggal_mulai ? date('d M Y', strtotime($tanggal_mulai)) : '-') .
                                    " s/d " .
                                    ($tanggal_akhir ? date('d M Y', strtotime($tanggal_akhir)) : '-');
                            } else {
                                echo "Periode: Bulan Ini";
                            }
                            ?></span>
                        
                      </p>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                  <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-orange-500 to-yellow-500">
                      <i class="ni leading-none ni ni-single-02 text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card2 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Accepted Candidates</p>
                      <h5 class="mb-2 font-bold dark:text-white"><?= $totalAccepted; ?></h5>
                      <p class="mb-0 dark:text-white dark:opacity-60">
                        <span class="text-sm font-bold leading-normal text-red-600"><?php
                            if ($tanggal_mulai || $tanggal_akhir) {
                                echo "Periode: " .
                                    ($tanggal_mulai ? date('d M Y', strtotime($tanggal_mulai)) : '-') .
                                    " s/d " .
                                    ($tanggal_akhir ? date('d M Y', strtotime($tanggal_akhir)) : '-');
                            } else {
                                echo "Periode: Bulan Ini";
                            }
                            ?></span>
                        
                      </p>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                      <i class="ni leading-none ni ni-check-bold text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card3 -->
          <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Training Participants</p>
                      <h5 class="mb-2 font-bold dark:text-white"><?= $totalParticipants; ?> Orang</h5>
                      <p class="mb-0 dark:text-white dark:opacity-60">
                        <span class="text-sm font-bold leading-normal text-red-600"><?php
                            if ($tanggal_mulai || $tanggal_akhir) {
                                echo "Periode: " .
                                    ($tanggal_mulai ? date('d M Y', strtotime($tanggal_mulai)) : '-') .
                                    " s/d " .
                                    ($tanggal_akhir ? date('d M Y', strtotime($tanggal_akhir)) : '-');
                            } else {
                                echo "Periode: Bulan Ini";
                            }
                            ?></span>
                        
                      </p>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                      <i class="ni leading-none ni ni-single-02 text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- card4 -->
          <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="flex-auto p-4">
                <div class="flex flex-row -mx-3">
                  <div class="flex-none w-2/3 max-w-full px-3">
                    <div>
                      <p class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">Training Hours</p>
                      <h5 class="mb-2 font-bold dark:text-white"><?= $totalTrainingHours; ?> Jam</h5>
                      <p class="mb-0 dark:text-white dark:opacity-60">
                        <span class="text-sm font-bold leading-normal text-red-600"><?php
                            if ($tanggal_mulai || $tanggal_akhir) {
                                echo "Periode: " .
                                    ($tanggal_mulai ? date('d M Y', strtotime($tanggal_mulai)) : '-') .
                                    " s/d " .
                                    ($tanggal_akhir ? date('d M Y', strtotime($tanggal_akhir)) : '-');
                            } else {
                                echo "Periode: Bulan Ini";
                            }
                            ?></span>
                        
                      </p>
                    </div>
                  </div>
                  <div class="px-3 text-right basis-1/3">
                    
                    <div class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-600 to-orange-600">
                      <i class="ni leading-none ni ni-time-alarm text-lg relative top-3.5 text-white"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- cards row 2 -->
        <!-- <div class="flex flex-wrap mt-6 -mx-3">
          <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
            <div class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                <h6 class="capitalize dark:text-white">Sales overview</h6>
                <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
                  <i class="fa fa-arrow-up text-emerald-500"></i>
                  <span class="font-semibold">4% more</span> in 2021
                </p>
              </div>
              <div class="flex-auto p-4">
                <div>
                  <canvas id="chart-line" height="300"></canvas>
                </div>
              </div>
            </div>
          </div> -->
          
          <!-- <div class="w-full max-w-full px-3 mt-0 lg:w-5/12 lg:flex-none">
            <div class="border-black/12.5 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
              <div class="p-4 pb-0 rounded-t-4">
                <h6 class="mb-0 dark:text-white">Categories</h6>
              </div>
              <div class="flex-auto p-4">
                <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                  <li class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-t-lg rounded-xl text-inherit">
                    <div class="flex items-center">
                      <div class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                        <i class="text-white ni ni-mobile-button relative top-0.75 text-xxs"></i>
                      </div>
                      <div class="flex flex-col">
                        <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Devices</h6>
                        <span class="text-xs leading-tight dark:text-white/80">250 in stock, <span class="font-semibold">346+ sold</span></span>
                      </div>
                    </div>
                    <div class="flex">
                      <button class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200" aria-hidden="true"></i></button>
                    </div>
                  </li>
                  <li class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-xl text-inherit">
                    <div class="flex items-center">
                      <div class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                        <i class="text-white ni ni-tag relative top-0.75 text-xxs"></i>
                      </div>
                      <div class="flex flex-col">
                        <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Tickets</h6>
                        <span class="text-xs leading-tight dark:text-white/80">123 closed, <span class="font-semibold">15 open</span></span>
                      </div>
                    </div>
                    <div class="flex">
                      <button class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200" aria-hidden="true"></i></button>
                    </div>
                  </li>
                  <li class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-b-lg rounded-xl text-inherit">
                    <div class="flex items-center">
                      <div class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                        <i class="text-white ni ni-box-2 relative top-0.75 text-xxs"></i>
                      </div>
                      <div class="flex flex-col">
                        <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Error logs</h6>
                        <span class="text-xs leading-tight dark:text-white/80">1 is active, <span class="font-semibold">40 closed</span></span>
                      </div>
                    </div>
                    <div class="flex">
                      <button class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200" aria-hidden="true"></i></button>
                    </div>
                  </li>
                  <li class="relative flex justify-between py-2 pr-4 border-0 rounded-b-lg rounded-xl text-inherit">
                    <div class="flex items-center">
                      <div class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                        <i class="text-white ni ni-satisfied relative top-0.75 text-xxs"></i>
                      </div>
                      <div class="flex flex-col">
                        <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">Happy users</h6>
                        <span class="text-xs leading-tight dark:text-white/80"><span class="font-semibold">+ 430 </span></span>
                      </div>
                    </div>
                    <div class="flex">
                      <button class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200" aria-hidden="true"></i></button>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div> -->
        </div>

        
      </div>
      <!-- end cards -->
    </main>
        <?php
    include('includes/footer.php');
    ?>

    
