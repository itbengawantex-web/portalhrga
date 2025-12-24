<?php
session_start();

$current_page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}
include('includes/header.php');
include('includes/sidebar.php');
include('config/dbcon.php'); 

?>

<main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      <!-- Navbar -->
      <?php
      $page_title = "Tambah Recrutment";
      include('includes/topbar.php')
      ?>

      <div class="w-full px-6 py-6 mx-auto">
        <!-- table 1 -->

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6 class="dark:text-white">Tambah Rekrutmen</h6>
                
              </div>
              <div class="flex-auto px-0  ">
                
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
                      <div class="flex-auto p-6">
                        <div class="flex flex-wrap -mx-3">
                          <div class="px-3">
                              <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                  Tanggal
                                </label>
                                <input type="date" name="tanggal" placeholder="Nama Lengkap" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              </div>
                            </div>
                            <div class="w-full px-3">
                              <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                  Nama
                                </label>
                                <input type="text" name="nama" placeholder="Nama Lengkap" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              </div>
                            </div>

                            <div class="w-full px-3">
                              <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                  Posisi
                                </label>
                                <input type="text" name="posisi" placeholder="Posisi" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              </div>
                            </div>

                            <div class="w-full px-3">
                              <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                  Psikotes
                                </label>
                                <input type="text" name="psikotes" placeholder="Psikotes" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              </div>
                            </div>

                            <div class="w-full px-3">
                              <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                  Interview HR
                                </label>
                                <input type="text" name="interview_hr" placeholder="Interview HR" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              </div>
                            </div>
                            <div class="w-full px-3">
                              <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                  Interview User
                                </label>
                                <input type="text" name="interview_user" placeholder="Interview User" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                              </div>
                            </div>
                            <div class=" px-3">
                              <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                  Status
                                </label>
                                <select name="status" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required>
                                  <option value="">-- Pilih Status --</option>
                                  <option value="Pending">Pending</option>
                                  <option value="Diterima">Diterima</option>
                                  <option value="Ditolak">Ditolek</option>
                                </select>
                              </div>
                            </div>
                            <div class="w-full px-3">
                              <div class="mb-">
                               <button type="submit" name="simpan" class="w-full px-6 py-2  text-sm font-semibold text-white bg-blue-gradient rounded-lg hover:bg-green mr-2">Simpan</button>
                              </div>
                            </div>
                          </div>
                      </div>
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


</script>





    <?php
    include('includes/footer.php');
    ?>
    