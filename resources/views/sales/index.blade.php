@extends('layouts.app')
@section('title', 'Fanatech - Sales')

@prepend('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        .text-center {
            text-align: center;
        }

        .form-control:focus,
        .form-select:focus {
            outline: 0;
            box-shadow: none;
        }
    </style>
@endprepend

@section('breadcrumb')
    <div class="pagetitle">
        <h1>Sales</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Sales</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body pt-4">
                {{-- btn create --}}
                <a class="btn btn-primary btn-sm mb-4" onClick="addSales();" href="javascript:void(0);">Create Sales</a>

                {{-- table --}}
                <table class="table table-striped table-bordered" id="sales_datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Number</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="salesModal" tabindex="-1" aria-labelledby="salesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="salesModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="javascript:void(0)" id="salesForm" name="salesForm" method="POST">
                        <input type="hidden" name="id" id="id">

                        {{-- input inventory --}}
                        <div class="mb-3">
                            <label for="inventories_id" class="form-label">Item <span class="text-danger">*</span></label>
                            <select name="inventories_id[]" id="inventories_id" class="form-select" multiple multiple aria-label="Multiple select example" required>
                                @foreach ($inventories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- input qty --}}
                        <div class="mb-3">
                            <label for="qty" class="form-label">Qty <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="qty" name="qty" min="1" required>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#sales_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'user',
                        name: 'user',
                    },
                    {
                        data: 'number',
                        name: 'number'
                    },
                    {
                        data: 'date',
                        name: 'date'
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

        function addSales() {
            $('#salesForm').trigger("reset");
            $('#salesModal').modal('show');
            $('#salesModal #salesModalLabel').text('Add Sales');
            $('#id').val('');
        }

        function editSales(id) {
            $.ajax({
                type: "PUT",
                url: `{{ route('inventory.update', '') }}/${id}`,
                data: {id: id},
                dataType: 'json',
                success: function(res) {
                    $('#salesModal #salesModalLabel').text("Edit Inventory");
                    $('#salesModal').modal('show');
                    $('#id').val(res.id);
                    $('#code').val(res.code);
                    $('#name').val(res.name);
                    $('#price').val(res.price);
                    $('#stock').val(res.stock);
                }
            });
        }

        function deleteSales(id){
            if (confirm("Delete Record?") == true) {
                var id = id;
                $.ajax({
                    type:"DELETE",
                    url: `{{ route('sales.destroy', '') }}/${id}`,
                    data: { id: id },
                    dataType: 'json',
                    success: function(res){
                        var oTable = $('#sales_datatable').dataTable();
                        oTable.fnDraw(false);
                    }
                });
            }
        }

        $('#salesForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('sales.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#salesModal").modal('hide');
                    let oTable = $('#sales_datatable').dataTable();
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
