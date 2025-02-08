<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $po->purchase_order_return_number }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
    <body>
        <div class="container-fluid">
            <div class="row text-center mt-3">
                <div class="col-md-12">
                    <h2>Purchase Order</h2>
                </div>
            </div>
            <hr>
        </div>
        <div class="container">
            <div class="row mt-2">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-3">
                </div>
                <div class="col-sm-2">
                    <h6>PO Number :</h6>
                    <h6>PO Date :</h6>
                    <h6>Vendor ID :</h6>
                </div>
                <div class="col-sm-3">
                    <h6>{{ $po->purchase_order_return_number }}</h6>
                    <h6>{{ \Carbon\Carbon::parse($po->purchase_order_return_date)->format('d/m/Y') }}</h6>
                    <h6>{{ $vendor->vendor_account }}</h6>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">Purchase From:</div>
                <div class="col-md-4"></div>
                <div class="col-md-4">Ship To:</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class='row'>
                        <div class="col-md-12"> {{ $vendor->company_name }}</div>
                        <div class="col-md-12"> {{ $vendor->fullname }}</div>
                        <div class="col-md-12"> {{ $vendor->address }}</div>
                    </div>       
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class='row'>
                        <div class="col-md-12"> {{ $po->delivery_contact }}</div>
                        <div class="col-md-12"> {{ $po->delivery_address }}</div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row text-center">
                <div class="col-md-4"><b>Mode of Delivery</b></div>
                <div class="col-md-4"><b>Payment Term</b></div>
                <div class="col-md-4"><b>Due Date</b></div>
            </div>
            <hr>
            <div class="row text-center">
                <div class="col-md-4">{{ $po->mode_of_delivery_type ?  $po->mode_of_delivery_type : '---'}}</div>
                <div class="col-md-4">{{ $po->terms_of_payment_detail ? $po->terms_of_payment_detail->terms_of_payment : '---' }}</div>
                <div class="col-md-4">{{  \Carbon\Carbon::parse($po->due_date)->format('d/m/Y') }}</div>
            </div>
            <hr>

            <div class="row">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Variant</th>
                            <th scope="col">Size</th>
                            <th scope="col">Color</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">C.O.P</th>
                            <th scope="col">Discount</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($po_lines as $line)
                        <tr>
                            <th scope="row">{{ $line->product->name }}</th>
                            <td>{{ $line->variant->name }}</td>
                            <td>{{ $line->variant->size }}</td>
                            <td>{{ $line->variant->color }}</td>
                            <td>{{ $line->quantity }}</td>
                            <td>{{ $line->unit_price }}</td>
                            <td>{{ $line->amount + $line->charge_on_purchase }}</td>
                            <td>{{ $line->charge_on_purchase }}</td>
                            <td>{{ $line->discount }}</td>
                            <td>{{ $line->amount }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row mt-2">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-3">
                </div>
                <div class="col-sm-3">
                    <h6>Total Charge on Purchase</h6>
                    <h6>Total Discount</h6>
                    <h6>Subtotal</h6>
                    <hr>
                    <h6>Grand Total</h6>
                </div>
                <div class="col-sm-2">
                    <h6>+ {{ $po->renderCOP() }}</h6>
                    <h6>- {{ $po->renderTotalDiscount() }}</h6>
                    <h6>+ {{ $po->renderSubtotal() }}</h6>
                    <hr>
                    <h6>{{ $po->renderTotalAmount() }}</h6>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    window.onload = function() { window.print(); }
</script>
</html>
