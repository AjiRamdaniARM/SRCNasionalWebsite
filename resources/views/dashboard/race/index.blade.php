<x-panel.app>

    {{-- === modal pay seleksi 2 === --}}
    <dialog id="formulir">
        <form method="POST" >
            @csrf
            <h2 class="poppins-bold">Formulir Google </h2>
            <div style="border: 2px solid #0080ff" class="block bg-white shadow-md rounded-2xl px-10 py-3 ">
                <h1 class="poppins-semibold text-start">Untuk Form Pendaftaran <span>
                    </span></h1>
            </div>
            <div class="block p-5 bg-blue-300 rounded-2xl relative mt-4" >
                <h6 class=" text-2xl text-blue-800 font-bold">Link Google Form</h6>
               <input type="text" style="border-radius: 15px" name="formGoogle" id="formGoogle">
            </div>
            <button type="submit"
                class="block bg-blue-400 relative mt-4 rounded-xl w-full py-3 text-white font-bold hover:bg-blue-300 ">
                Create Form
            </button>
        </form>
        <button onclick="window.formulir.close();" aria-label="close" class="x">❌</button>
    </dialog>

    <style>
          dialog {
            padding: 1rem 3rem;
            background: white;
            max-width: 390px;
            padding-top: 2rem;
            border-radius: 20px;
            border: 0;
            box-shadow: 0 5px 30px 0 rgba(0, 0, 0, 0.1);
            -webkit-animation: fadeIn 1s ease both;
            animation: fadeIn 1s ease both;
        }

        dialog::-webkit-backdrop {
            -webkit-animation: fadeIn 1s ease both;
            animation: fadeIn 1s ease both;
            background: rgba(255, 255, 255, 0.4);
            z-index: 2;
            -webkit-backdrop-filter: blur(20px);
            backdrop-filter: blur(20px);
        }

        dialog::backdrop {
            -webkit-animation: fadeIn 1s ease both;
            animation: fadeIn 1s ease both;
            background: rgba(255, 255, 255, 0.4);
            z-index: 2;
            -webkit-backdrop-filter: blur(20px);
            backdrop-filter: blur(20px);
        }

        dialog .x {
            filter: grayscale(1);
            border: none;
            background: none;
            position: absolute;
            top: 15px;
            right: 10px;
            transition: ease filter, transform 0.3s;
            cursor: pointer;
            transform-origin: center;
        }

        dialog .x:hover {
            filter: grayscale(0);
            transform: scale(1.1);
        }

        dialog h2 {
            font-weight: 600;
            font-size: 2rem;
            padding-bottom: 1rem;
        }

        dialog p {
            font-size: 1rem;
            line-height: 1.3rem;
            padding: 0.5rem 0;
        }

        dialog p a:visited {
            color: rgb(var(--vs-primary));
        }

        button.primary {
            display: inline-block;
            font-size: 0.8rem;
            color: #fff !important;
            background: rgb(var(--vs-primary)/100%);
            padding: 13px 25px;
            border-radius: 17px;
            transition: background-color 0.1s ease;
            box-sizing: border-box;
            transition: all 0.25s ease;
            border: 0;
            cursor: pointer;
            box-shadow: 0 10px 20px -10px rgb(var(--vs-primary)/50%);
        }

        button.primary:hover {
            box-shadow: 0 20px 20px -10px rgb(var(--vs-primary)/50%);
            transform: translateY(-5px);
        }

        @-webkit-keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
    {{-- === akhir  modal pay seleksi 2 === --}}

<div class="container py-10 flex justify-between items-center">
        <div class="grup-tools">
            <h1 class="poppins-bold text-2xl lg:text-4xl text-[#34364A]">Perlombaan Manage</h1>
            <p class="rela">manage admin</p>
        </div>
        <div class="block
        ">
        <button onclick="window.formulir.showModal();" class="btn btn-primary mb-3">Form Google</button>
        <a href="{{ route('race.create') }}" class="btn btn-primary mb-3">Create</a>
    </div>
    </div>
    
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                {{-- table --}}
                <table class="table datatables" id="dataTable">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="text-capitalize">No</th>
                            <th class="text-capitalize">Name</th>
                            <th class="text-capitalize">Point</th>
                            <th class="text-capitalize">Session</th>
                            <th class="text-capitalize">Price</th>
                            <th class="text-capitalize">Image</th>
                            <th class="text-capitalize">Team</th>
                            <th class="text-capitalize">Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>

    @push('extra-script')
    <script>
        $(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('race.index') !!}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'point', name: 'point' },
                    { data: 'session', name: 'session' },
                    { data: 'price', name: 'price' },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'team', name: 'team', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#editCategory').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                $("#formEditCategory").attr('action', $(location).attr('href')+'/'+button.data('id'));
                $("#nameEdit").val(button.data('name'));
            });
        });
    </script>
    @endpush
</x-panel.app>
