export default {

    data() {
        return {
            procurement_categories : ['Land','Air','Sea'],
            line_statuses : ['Open Order', 'Recieved', 'Invoiced', 'Canceled'],

            filtered_variants:  [],
        }
    },

    props: {
        products: {
            default: [],
            type: Array
        },

        variants: {
            default: [],
            type: Array
        },
    
        po : {
            default : [],
            type : Object
        },
        lines : {
            default : [],
            type : Array,
        },
        expense_purposes : {
            default : [],
            type : Array,
        },
        departments : {
            default : [],
            type : Array,
        },
        cost_centers : {
            default : [],
            type : Array,
        },
        specifications : {
            default : [],
            type : Array,
        },
        services: Array,
        procurements: Array,
        charges_on_lines: Array,
        discount_on_lines: Array,
    },

    computed: {
        headers() {
            let array = [
                { text: 'Line #', value: 'line_number' },
                { text: 'Item #', value: 'item_number' },
                { text: 'Line Status', value: 'line_status' },
                { text: 'Product', value: 'name' },
                { text: 'Variant', value: 'variant' },
                { text: 'Size', value: 'size' },
                { text: 'Color', value: 'color' },
                { text: 'Quantity', value: 'quantity' },
                { text: 'Unit Price', value: 'unit_price' },
                { text: 'SubTotal', value: 'sub_total' },
                { text: 'COP', value: 'charge_on_purchase' },
                { text: 'Discount', value: 'discount' },
                { text: 'Amount', value: 'amount' },
                { text: 'Action', value: null },
            ];

            return array;
        },

        totalQuantity() {
            var purchase_order_lines = this.purchase_order_lines;
            var total = 0;
            _.each(purchase_order_lines, (line) => {
                total += parseInt(line.quantity);
            })
            return total;
        },

        subTotalAmount() {
            var purchase_order_lines = this.purchase_order_lines;

            var total = 0;
               _.each(purchase_order_lines, (line) => {
                let quantity = line.quantity ? line.quantity : 0;
                let unit_price = line.variant.unit_price ? line.variant.unit_price : 0;
                total += parseFloat(quantity) * parseFloat(unit_price)
            });
            
            return total;
        },

        totalDiscount() {
            var purchase_order_lines = this.purchase_order_lines;
            var total = 0;
            _.each(purchase_order_lines, (line) => {
                total += parseFloat(line.discount);
            })
            return total;
        },

        totalCharges() {
            var purchase_order_lines = this.purchase_order_lines;
            var total = 0;
            _.each(purchase_order_lines, (line) => {
                total += parseFloat(line.charge_on_purchase);
            })
            return total;
        },

        totalSalesTax() {
            var total = 0;
            return total;
        },

        totalRoundOff() {
            var total = 0;
            total = Math.round(this.subTotalAmount);
            return total;
        },

       totalAmount() {
            var total = 0;
            let sale = this.totalSalesTax;
            let charge =  this.totalCharges;
            let total_discount = this.totalDiscount;
            let subtotal = this.subTotalAmount;
            total = (parseFloat(charge) + parseFloat(subtotal)) - (sale + total_discount);
            this.$parent.$parent.$parent.line_total_amount = total;
            this.$parent.$parent.$parent.item.total_sales_vat_exclusive = total;
            return total;
        },

        totalCashDiscount() {
            var lines = this.lines;
            var total = 0;
            _.each(lines, (line) => {
                total += parseInt(line.discount);
            })
            this.po.total_cash_discount = total;
            this.$parent.$parent.$parent.item.less_discount = total;

            return total;        
        },

        disableConfirmButton() {
            if(this.showConfirmButton) {
                return this.item.is_already_confirmed;
            }

            return true;
        },

        disableGenerateInvoiceButton() {
            if(this.showConfirmButton) {
                return this.item.hasExistingInvoice;
            }

            return true;
        },
    },

    methods : {
        computeTotalAmount(item) {
            let amount = parseFloat(item.amount) + parseFloat(item.charge_on_purchase ? item.charge_on_purchase : 0);
            return amount;
        },
        computeSubTotal(item) {
            let amount = parseFloat(item.unit_price ? item.unit_price : item.variant.unit_price) * parseFloat(item.quantity)
            return amount;
        }
    }
}


