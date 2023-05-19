@extends('layouts.master')
@section('title', 'Add Invoice')
@section('css')
    <!--Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Invoices</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Edit Invoice</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.update', $invoice->id) }}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        {{-- 1 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label for="inputName" class="control-label">Invoices Num<span class="tx-danger">*</span></label>
                                <input type="text" class="form-control" value="{{$invoice->invoice_number}}" id="inputName" name="invoice_number">
                            </div>
                            <div class="col mb-3">
                                <label>Invoices Date<span class="tx-danger">*</span></label>
                                <input class="form-control fc-datepicker" value="{{$invoice->invoice_date ?? date('Y-m-d')}}" name="invoice_date" placeholder="YYYY-MM-DD" type="text">
                            </div>
                            <div class="col mb-3">
                                <label>Invoices Due Date<span class="tx-danger">*</span></label>
                                <input class="form-control fc-datepicker" value="{{$invoice->due_date}}" name="due_date" placeholder="YYYY-MM-DD" type="text">
                            </div>
                        </div>
                        {{-- 2 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label class="control-label">Department<span class="tx-danger">*</span></label>
                                <select name="department_id" onchange="getProductsOfDepartment(this.value)" class="form-control select2">
                                    @foreach ($departments as $department)
                                        @if($department->id === $invoice->department->id)
                                            <option value="{{$department->id}}" selected> {{$department->name}}</option>
                                        @else
                                            <option value="{{$department->id}}"> {{$department->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Product<span class="tx-danger">*</span></label>
                                <select id="products" name="product_id" class="form-control select2">
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Collection Amount<span class="tx-danger">*</span></label>
                                <input type="text" class="form-control"  value="{{$invoice->collection_amount}}" name="collection_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>
                        </div>
                        {{-- 3 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label class="control-label">Commission Amount<span class="tx-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" value="{{$invoice->commission_amount}}" id="commission_amount" name="commission_amount"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                       commissionCalculations()">
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Discount</label>
                                <input type="text" class="form-control form-control-lg"  value="{{$invoice->discount}}" id="discount" name="discount"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                       commissionCalculations()">
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Added TAX Rate</label>
                                <select onchange="commissionCalculations()" name="tax_rate" id="added_tax_rate" class="form-control select2-no-search">
                                    <option>5%</option>
                                    <option>10%</option>
                                </select>
                            </div>
                        </div>
                        {{-- 4 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label class="control-label">Value Added TAX</label>
                                <input type="text" name="tax_value" class="form-control" id="value_added_tax" value="{{$invoice->tax_value}}" readonly>
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Total Including TAX</label>
                                <input type="text" name="total" class="form-control" id="total_including_tax" value="{{$invoice->total}}" readonly>
                            </div>
                        </div>
                        {{-- 5 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label>Notes</label>
                                <textarea class="form-control" name="note" rows="3">{{$invoice->note}}</textarea>
                            </div>
                        </div>
                        <div class="row row-sm mg-b-20">
                            <div class="col-lg-6">
                                <p class="mg-b-10">Payment Status</p>
                                <select id="invoice_payment_status" name="status" class="form-control select2-no-search">
                                    <option>Paid</option>
                                    <option>Partially Paid</option>
                                    <option>Not Paid</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                                <label>Payment Date</label>
                                <input class="form-control fc-datepicker" value="{{$invoice->payment_date}}" name="payment_date" placeholder="YYYY-MM-DD" type="text">
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Update Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
    @if (session('status'))
        <script>
            window.onload = () => {
                notif({
                    msg: "{{session('status')}}",
                    type: "success"
                });
            }
        </script>
    @endif
    @if (session('error'))
        <script>
            window.onload = () => {
                notif({
                    msg: "{{ session('error') }}",
                    type: "error"
                });
            }
        </script>
    @endif
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal  Form-elements-->
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ asset('assets/js/form-elements.js') }}"></script>
    <!--Internal  Notify js -->
    <script src="{{asset('assets/plugins/notify/js/notifIt.js')}}"></script>
    <script>
        getProductsOfDepartment({{$invoice->department->id}});
        function getProductsOfDepartment(departmentId){
            let url = "{{route('products.show', ':id')}}";
            url = url.replace(':id', departmentId);
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    let products = '';
                    data.forEach(p => {
                        if (p.id === {{$invoice->product_id}}){
                            products += `<option value="${p.id}" selected>${p.name}</option>`
                        }else {
                            products += `<option value="${p.id}">${p.name}</option>`
                        }
                    })
                    document.querySelector('#products').innerHTML = products;
                })
        }

        // Set Current Payment Status To Select
        setSelectedValues();
        function setSelectedValues(){
            document.querySelector('#invoice_payment_status').value = "{{$invoice->status}}";
            document.querySelector('#added_tax_rate').value = "{{$invoice->tax_rate}}";
        }

        function commissionCalculations(){
            let commissionAmount = document.querySelector('#commission_amount').value;
            let discount = document.querySelector('#discount').value;
            let addedTaxRate = parseInt(document.querySelector('#added_tax_rate').value) || 0;
            let valueAddedTax = (commissionAmount - discount) * (addedTaxRate / 100);
            document.querySelector('#value_added_tax').value = valueAddedTax.toFixed(2);
            document.querySelector('#total_including_tax').value = (commissionAmount - discount - valueAddedTax).toFixed(2);
        }
    </script>
@endsection

