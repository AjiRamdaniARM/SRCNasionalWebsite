<x-panel.app>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <div id="loading" style="display: none;" class='flex space-x-2 justify-center items-center bg-white  h-screen '>
        <span class='sr-only'>Loading...</span>
        <div class='h-8 w-8 bg-black rounded-full animate-bounce [animation-delay:-0.3s]'></div>
        <div class='h-8 w-8 bg-black rounded-full animate-bounce [animation-delay:-0.15s]'></div>
        <div class='h-8 w-8 bg-black rounded-full animate-bounce'></div>
    </div>
    <style>
        #loading {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.455);
            color: #fff;
            font-size: 1.5em;
            z-index: 9999;
        }
    </style>
    <div class="flex flex-col mx-3 mt-6 lg:flex-row py-5">
        {{-- == form input team for jadwal == --}}
        <div class="w-full lg:w-1/3 m-1">
            <script>
                function hitungSelisihWaktu() {
                    const waktuAwal = document.getElementById('waktuAwal').value;
                    const waktuAkhir = document.getElementById('waktuAkhir').value;

                    if (waktuAwal && waktuAkhir) {
                        const [jamAwal, menitAwal] = waktuAwal.split(':').map(Number);
                        const [jamAkhir, menitAkhir] = waktuAkhir.split(':').map(Number);

                        const totalMenitAwal = (jamAwal * 60) + menitAwal;
                        const totalMenitAkhir = (jamAkhir * 60) + menitAkhir;

                        let selisihMenit = totalMenitAkhir - totalMenitAwal;

                        // Jika selisih negatif, berarti waktu akhir ada di hari berikutnya
                        if (selisihMenit < 0) {
                            selisihMenit += 24 * 60; // tambahkan 24 jam dalam menit
                        }

                        const hasil = `${Math.floor(selisihMenit / 60)} jam ${selisihMenit % 60} menit`;

                        // Set hasil ke dalam input totalWaktu
                        document.getElementById('totalWaktu').value = hasil;
                    }
                }
                //  == ajax post == //
                $(document).ready(function() {
                    loadPost();
                    $('#postSeleksi').on('submit', function(e) {
                        e.preventDefault();
                        let formData = new FormData(this);
                        $('#loading').show(); // Tampilkan loading
                        $.ajax({
                            url: "{{ route('sesi.jadwal') }}",
                            method: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response); // Cek apakah data masuk ke JSON
                                $('#loading').hide(); // Sembunyikan loading setelah selesai
                                $('#postSeleksi')[0].reset(); // Reset form setelah submit
                                loadPost(); //
                            },
                            error: function(response) {
                                console.log(response);
                                alert('Terjadi kesalahan. Silakan coba lagi.');
                                $('#loading').hide(); // Sembunyikan loading meskipun gagal
                            }
                        });
                    });


                });

                function loadPost() {
                    $.ajax({
                        url: '{{ route('jadwalJD.index') }}',
                        type: 'GET',
                        success: function(response) {
                            let rows = '';
                            $.each(response, function(index, team) {
                                rows += `<tr>
                    <td class="p-2 border border-gray-300">${index + 1}</td> <!-- Nomor urut -->
                    <td>${team.sesi}</td>
                    <td>${team.waktu_awal}</td>
                    <td>${team.waktu_akhir}</td>
                    <td class="p-2 border border-gray-300 text-center">
                        ${team.duration}
                    </td>
                    <td class="p-2 border border-gray-300 text-center">
                        <div class="flex justify-center">
                            <button class="rounded-md hover:bg-red-100 text-red-600 p-2 flex justify-between items-center deleteTeam" data-id="${team.id}">
                                <span>
                                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span> Delete
                            </button>
                        </div>
                    </td>
                </tr>`;
                            });
                            $('#teamTable tbody').html(rows);
                        }
                    });
                }
                $(document).on('click', '.deleteTeam', function() {
                    const teamId = $(this).data('id');

                    $.ajax({
                        url: 'admin/delete/' + teamId, // Menggunakan ID dari team untuk menghapus
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // Jangan lupa untuk menambahkan token CSRF
                        },
                        success: function(response) {
                            console.log(response.success); // Tampilkan pesan sukses di console
                            loadPost(); // Reload the table after deletion
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText); // Jika ada error, tampilkan di console
                        }
                    });
                });
            </script>
            <form class="w-full bg-white shadow-md p-6" id="postSeleksi">
                @csrf
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-sm font-bold mb-2"
                            htmlFor="category_name">Sesi Perlombaan</label>
                        <input
                            class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#1D87C0]"
                            type="text" name="name" placeholder="Cth: Sesi 1" required />
                    </div>
                    <div class="w-full md:w-full px-3 mb-6">
                        <label for="peserta">Waktu Awal</label>
                        <input id="waktuAwal"
                            class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#1D87C0]"
                            type="time" name="waktuAwal" required onchange="hitungSelisihWaktu()" />
                    </div>
                    <div class="w-full px-3 mb-6">
                        <label for="peserta">Waktu Akhir</label>
                        <input id="waktuAkhir"
                            class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#1D87C0]"
                            type="time" name="waktuAkhir" required onchange="hitungSelisihWaktu()" />
                    </div>
                    <div class="w-full px-3 mb-6">
                        <label for="peserta">Total Waktu (dalam menit)</label>
                        <input id="totalWaktu"
                            class="appearance-none block w-full bg-white text-gray-900 font-medium border border-gray-400 rounded-lg py-3 px-3 leading-tight focus:outline-none focus:border-[#1D87C0]"
                            type="text" name="totalWaktu" readonly />
                        <br>
                    </div>

                    <div class="w-full md:w-full px-3 mb-6">
                        <button
                            class="appearance-none block w-full bg-blue-700 text-gray-100 font-bold border border-blue-200 rounded-lg py-3 px-3 leading-tight hover:bg-blue-600 focus:outline-none  focus:border-gray-500">Buat
                            Sesi</button>
                    </div>
                </div>
            </form>
        </div>
        {{-- == form input team for jadwal == --}}

        {{-- == view body table team ==  --}}
        <div class="w-full lg:w-2/3 m-1 bg-white shadow-lg text-lg rounded-sm border border-gray-200">
            <div class="overflow-x-auto rounded-lg p-3">
                <table class="table-auto w-full border-collapse border border-gray-300" id="teamTable">
                    <thead>
                        <tr class="bg-gray-50 text-gray-800 text-sm font-semibold uppercase">
                            <th class="p-2 border border-gray-300">No</th>
                            <th class="p-2 border border-gray-300 text-left">Sesi</th>
                            <th class="p-2 border border-gray-300 text-left">Waktu Awal </th>
                            <th class="p-2 border border-gray-300 text-left">Waktu Akhir</th>
                            <th class="p-2 border border-gray-300 text-center">Durasi</th>
                            <th class="p-2 border border-gray-300 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded here dynamically -->
                    </tbody>
                </table>

            </div>
        </div>
        {{-- == end view body table team == --}}

    </div>
</x-panel.app>
