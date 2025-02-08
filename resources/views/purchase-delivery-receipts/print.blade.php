<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
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
                    <h2>Vendor Invoice</h2>
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
                    <h6>VI Number :</h6>
                    <h6>VI Date :</h6>
                    <h6>Vendor ID :</h6>
                </div>
                <div class="col-sm-3">
                    <h6>{{ $vi->purchase_order_number }}</h6>
                    <h6>{{ \Carbon\Carbon::parse($vi->purchase_order->purchase_order_date)->format('d/m/Y') }}</h6>
                    <h6>{{ $vi->vendor_invoice_number }}</h6>
                    <h6>{{ \Carbon\Carbon::parse($vi->created_at)->format('d/m/Y') }}</h6>
                    <h6>{{ $vendor->vendor_account }}</h6>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">Sales From:</div>
                <div class="col-md-4"></div>
                <div class="col-md-4">Ship To:</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class='row'>
                        <div class="col-md-12"> {{ $vendor->company }}</div>
                        <div class="col-md-12"> {{ $vendor->fullname }}</div>
                        <div class="col-md-12"> {{ $vendor->address }}</div>
                    </div>       
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class='row'>
                        <div class="col-md-12"> {{ $vi->purchase_order->delivery_contact }}</div>
                        <div class="col-md-12"> {{ $vi->purchase_order->delivery_address }}</div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row text-center">
                <div class="col-md-4"><b>Due Date</b></div>
            </div>
            <hr>
            <div class="row text-center">
                <div class="col-md-4">{{  \Carbon\Carbon::parse($vi->payment_due_date)->format('d/m/Y') }}</div>
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
                        @foreach ($vi_lines as $line)
                        <tr>
                            <th scope="row">{{ $line->product->name }}</th>
                            <td>{{ $line->variant->name }}</td>
                            <td>{{ $line->variant->size }}</td>
                            <td>{{ $line->variant->color }}</td>
                            <td>{{ number_format($line->quantity, 2, '.', ',') }}</td>
                            <td>{{ number_format($line->unit_price, 2, '.', ',') }}</td>
                            <td>{{ number_format($line->amount + $line->charges_on_sales, 2, '.', ',') }}</td>
                            <td>{{ number_format($line->charges_on_sales, 2, '.', ',') }}</td>
                            <td>{{ number_format($line->discount, 2, '.', ',') }}</td>
                            <td>{{ number_format($line->amount, 2, '.', ',') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row mt-2">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-4">
                </div>
                <div class="col-sm-3">
                    <h6>Total Charge on Sales</h6>
                    <h6>Total Discount</h6>
                    <h6>Subtotal</h6>
                    <hr>
                    <h6>Grand Total</h6>
                </div>
                <div class="col-sm-1">
                    <h6>+ {{ $vi->renderCOP() }}</h6>
                    <h6>- {{ $vi->renderTotalDiscount() }}</h6>
                    <h6>+ {{ $vi->renderSubtotal() }}</h6>
                    <hr>
                    <h6>{{ $vi->renderTotalAmount() }}</h6>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    window.onload = function() { window.print(); }
</script>
</html>