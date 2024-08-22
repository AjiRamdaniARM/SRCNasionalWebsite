<x-panel.app>
    {{-- === akhir style dan script internal === --}}
    <div id="team" class="section relative pt-20 pb-8 md:pt-16">
        <div class="container mx-auto px-1 lg:px-4">
            <header class="text-start mx-auto mb-12">
                <h2 class="text-7xl text-center bebas-neue-regular mb-2 font-bold text-gray-800 ">
                    Table Participants Perlombaan
                </h2>
                <div class="text-center">
                    {{ $race->name }}
                </div>
                <br>
                <div class="grup-import-data flex" style="justify-content: center ; items-align:center; gap:5px">
                    <button onclick="window.location.href='{{ route('export.excel', ['id' => $race->id]) }}'"
                        class="bg-green-500 text-white px-3 py-1 rounded">Excel</button>
                    <button onclick="window.location.href='{{ route('export.pdf', ['id' => $race->id]) }}'"
                        class="bg-red-500 text-white px-3 py-1 rounded">Pdf</button>
                </div>
            </header>
            {{-- === data peserta perlombaan === --}}
            <div id="participantsContainer" class=" -mx-4 justify-center">
                <div class="card shadow">
                    <div class="card-body">
                        <!-- Wrapper untuk tabel dengan scroll horizontal -->
                        <div class="overflow-x-auto">
                            <table class="table datatables min-w-full" id="dataTable">
                                <thead class="bg-blue-500 text-white">
                                    <tr>
                                        <th class="text-capitalize">No</th>
                                        <th class="text-capitalize">Sekolah</th>
                                        <th class="text-capitalize">Alamat</th>
                                        <th class="text-capitalize">Peserta</th>
                                        <th class="text-capitalize">Sesi</th>
                                        <th class="text-capitalize">Duration</th>
                                        <th class="text-capitalize">Waktu Awal</th>
                                        <th class="text-capitalize">Waktu Akhir</th>
                                        <th class="text-capitalize">Sesi Save</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- === akhir data peserta perlombaan === --}}
        </div>
    </div>
    @push('extra-script')
        <script>
            $(function() {
                var table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('table.participants', ['id' => $race->id]) !!}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'community',
                            name: 'community'
                        },
                        {
                            data: 'maps',
                            name: 'maps'
                        },
                        {
                            data: 'peserta',
                            name: 'peserta'
                        },
                        {
                            data: 'sesis',
                            name: 'sesis'
                        },
                        {
                            data: 'duration',
                            name: 'duration'
                        },
                        {
                            data: 'waktu_awal',
                            name: 'waktu_awal'
                        },
                        {
                            data: 'waktu_akhir',
                            name: 'waktu_akhir'
                        },
                        {
                            data: 'sesi',
                            name: 'sesi',
                            orderable: false,
                            searchable: false
                        },

                    ]
                });

                $(document).on('change', '.selectSesi', function() {
                    var pesertaId = $(this).data('id_peserta'); // Mengambil data-id_peserta dari select
                    var sesiId = $(this).val();
                    console.log("Peserta ID:", pesertaId); // Debug
                    console.log("Sesi ID:", sesiId); // Debug
                    if (sesiId) {
                        $.ajax({
                            url: '{{ route('update.sesi') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                peserta_id: pesertaId,
                                sesi_id: sesiId
                            },
                            success: function(response) {
                                console.log('Response dari server:', response);
                                table.draw(false); // Refresh data tabel tanpa mereset pagination
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    } else {
                        console.log('error');
                    }
                });

            });
        </script>
    @endpush
</x-panel.app>
