@extends('layouts.app')
@section('title', 'Fanatech - Inventory')

@prepend('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        .text-center {
            text-align: center;
        }

        .form-control:focus {
            outline: 0;
            box-shadow: none;
        }
    </style>
@endprepend

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Inventory</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Inventory</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body pt-4">
                {{-- btn create --}}
                <a class="btn btn-primary btn-sm mb-4" onClick="addInventroy();" href="javascript:void(0);">Create Inventory</a>

                {{-- table --}}
                <table class="table table-striped table-bordered" id="inventory_datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inventoryModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="javascript:void(0)" id="inventoryForm" name="inventoryForm" method="POST">
                        <input type="hidden" name="id" id="id">

                        {{-- input name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Product Name" maxlength="255" required>
                        </div>

                        {{-- input price --}}
                        <div class="mb-3">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="price" name="price"
                                placeholder="Price, cth: Rp 100.000" maxlength="255" required>
                        </div>

                        {{-- input stock --}}
                        <div class="mb-4">
                            <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" placeholder="Stock"
                                min="0" required>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm" id="btn-save">Submit</button>
                    </form>
                </div>

                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#inventory_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('inventory.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'code',
                        name: 'code',
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                rowCallback: function(row, data, index) {
                    var api = this.api();
                    var start = api.page() * api.page.len();
                    $('td:eq(0)', row).html(start + index + 1);
                },
                order: [
                    [0, 'desc']
                ]
            });
        });

        function addInventroy() {
            $('#inventoryForm').trigger("reset");
            $('#inventoryModal').modal('show');
            $('#inventoryModal #inventoryModalLabel').text('Add Inventory');
            $('#id').val('');
        }

        function editInventory(id) {
            $.ajax({
                type: "PUT",
                url: `{{ route('inventory.edit', '') }}/${id}`,
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    $('#inventoryModal #inventoryModalLabel').text("Edit Inventory");
                    $('#inventoryModal').modal('show');
                    $('#id').val(res.id);
                    $('#code').val(res.code);
                    $('#name').val(res.name);
                    $('#price').val(res.price);
                    $('#stock').val(res.stock);
                }
            });
        }

        function deleteInventory(id){
            if (confirm("Delete Record?") == true) {
                var id = id;
                $.ajax({
                    type:"DELETE",
                    url: `{{ route('inventory.destroy', '') }}/${id}`,
                    data: { id: id },
                    dataType: 'json',
                    success: function(res){
                        var oTable = $('#inventory_datatable').dataTable();
                        oTable.fnDraw(false);
                    }
                });
            }
        }

        $('#inventoryForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('inventory.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#inventoryModal").modal('hide');
                    let oTable = $('#inventory_datatable').dataTable();
                    oTable.fnDraw(false);
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });
    </script>
@endprepend
