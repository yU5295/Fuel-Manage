@extends('layouts.master')

@section('content')
    @php
        $role = Auth::user()->role->slug;
    @endphp
    <div class="content-header row align-items-center m-0">
        <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
            <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
                <li class="breadcrumb-item"><a href="#"><i class="hvr-buzz-out fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Vehicles</li>
            </ol>
        </nav>
        <div class="col-sm-8 header-title p-0">
            <div class="media">
                <div class="header-icon text-success mr-3"><i class="hvr-buzz-out fas fa-car"></i></div>
                <div class="media-body">
                    <h1 class="font-weight-bold">Vehicles</h1>
                    <small>Vehicle Management</small>
                </div>
            </div>
        </div>
    </div> 
    <div class="body-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body card-fill">
                    <div class="">
                        <button type="button" class="btn btn-success btn-sm float-right mg-b-5" id="btn-add"><i class="fas fa-plus mr-1"></i>Add New</button>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-bordered table-hover mb-5">
                            <thead class="">
                                <tr class="bg-blue">
                                    <th style="width:40px;">#</th>
                                    <th>Plate</th>
                                    <th>Model</th>
                                    <th>Brand</th>
                                    <th>Type Of Vehicle</th>
                                    <th>Type Of Fuel</th>
                                    <th>Capacity</th>
                                    <th>Driver</th>
                                    <th>Unloading Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                @foreach ($data as $item)
                                    @php
                                        $unloading = $item->unloadings()->sum('amount');
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td class="plate">{{$item->plate}}</td>
                                        <td class="model">{{$item->model}}</td>
                                        <td class="brand">{{$item->brand}}</td>
                                        <td class="type" data-id="{{$item->vehicle_type_id}}">@if($item->type->name){{$item->type->name}}@endif</td>
                                        <td class="fuel" data-id="{{$item->fuel_id}}">@if($item->fuel){{$item->fuel->name}}@endif</td>
                                        <td class="capacity">{{$item->capacity}}</td>
                                        <td class="driver" data-id="{{$item->driver_id}}">@isset($item->driver){{$item->driver->name}}@endif</td>
                                        <td class="unloading">{{number_format($unloading)}}</td>
                                        <td class="py-1">
                                            <a href="#" class="btn btn-sm btn-primary btn-icon mr-1 btn-edit" data-id="{{$item->id}}" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></a>
                                            <a href="{{route('vehicle.delete', $item->id)}}" class="btn btn-sm btn-danger btn-icon" data-id="{{$item->id}}" onclick="return window.confirm('Are you sure?')" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->  
    <div class="modal fade" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Vehicle</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('vehicle.create')}}" id="create_form" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Plate</label>
                            <input class="form-control plate" type="text" name="plate" placeholder="Plate" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Model</label>
                            <input class="form-control model" type="text" name="model" placeholder="Model" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Brand</label>
                            <input class="form-control brand" type="text" name="brand" placeholder="Brand" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Type Of Vehicle</label>
                            <select name="type" id="" class="form-control type" required>
                                <option value="" hidden>Type Of Vehicle</option>
                                @foreach ($vehicle_types as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Type Of Fuel</label>
                            <select name="fuel" id="" class="form-control fuel" required>
                                <option value="" hidden>Type Of Fuel</option>
                                @foreach ($fuels as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Capacity</label>
                            <input class="form-control capacity" type="number" min="0" name="capacity" placeholder="Capacity" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Driver</label>
                            <select name="driver" id="" class="form-control driver">
                                <option value="" hidden>Select Driver</option>
                                @foreach ($drivers as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>    
                    <div class="modal-footer">
                        <button type="submit" id="btn_create" class="btn btn-primary btn-submit"><i class="fas fa-check-circle mr-1"></i>&nbsp;Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-1"></i>&nbsp;Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Vehicle</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <form action="{{route('vehicle.edit')}}" id="edit_form" method="post">
                    @csrf
                    <input type="hidden" name="id" class="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Plate</label>
                            <input class="form-control plate" type="text" name="plate" placeholder="Plate" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Brand</label>
                            <input class="form-control brand" type="text" name="brand" placeholder="Brand" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Model</label>
                            <input class="form-control model" type="text" name="model" placeholder="Model" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Type Of Vehicle</label>
                            <select name="type" id="" class="form-control type" required>
                                <option value="" hidden>Type Of Vehicle</option>
                                @foreach ($vehicle_types as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Type Of Fuel</label>
                            <select name="fuel" id="" class="form-control fuel" required>
                                <option value="" hidden>Type Of Fuel</option>
                                @foreach ($fuels as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Capacity</label>
                            <input class="form-control capacity" type="number" min="0" name="capacity" placeholder="Capacity" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Driver</label>
                            <select name="driver" id="" class="form-control driver">
                                <option value="" hidden>Select Driver</option>
                                @foreach ($drivers as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>  
                    <div class="modal-footer">
                        <button type="submit" id="btn_update" class="btn btn-primary btn-submit"><i class="fa fa-check mg-r-10"></i>&nbsp;Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mg-r-10"></i>&nbsp;Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
<script>
    $(document).ready(function () {
        
        $("#btn-add").click(function(){
            $("#create_form input.form-control").val('');
            $("#create_form .invalid-feedback strong").text('');
            $("#addModal").modal();
        });

        $(".btn-edit").click(function(){
            let id = $(this).data("id");
            let plate = $(this).parents('tr').find(".plate").text().trim();
            let model = $(this).parents('tr').find(".model").text().trim();
            let brand = $(this).parents('tr').find(".brand").text().trim();
            let capacity = $(this).parents('tr').find(".capacity").text().trim();
            let fuel = $(this).parents('tr').find(".fuel").data('id');
            let type = $(this).parents('tr').find(".type").data('id');
            let driver = $(this).parents('tr').find(".driver").data('id');
            $("#edit_form input.form-control").val('');
            $("#editModal .id").val(id);
            $("#editModal .plate").val(plate);
            $("#editModal .model").val(model);
            $("#editModal .brand").val(brand);
            $("#editModal .capacity").val(capacity);
            $("#editModal .fuel").val(fuel);
            $("#editModal .type").val(type);
            $("#editModal .driver").val(driver);
            $("#editModal").modal();
        });

    });
</script>
@endsection

