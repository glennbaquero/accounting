
export default {
    data() {
        return {
            offset_disabled : true,
            main_account_disabled : true,
        }
    },

    watch : {

        'item.voucher_type'(value) {
            if(value === 'Debit') {
                this.main_account_disabled = false;
                this.disableOffSets();
            }else if(value === 'Credit'){
                this.offset_disabled = false;
                this.disableMainAccounts();
            }else {
                this.disableOffSets();
                this.disableMainAccounts();
            }
        },
    },

    methods : {
        disableOffSets() {
            this.offset_disabled = true;
            this.item.credit_amount = 0;
            this.clearOffsets();
        },
        disableMainAccounts() {
            this.main_account_disabled = true;
            this.item.debit_amount = 0;
            this.clearMainAccounts();
        },
        clearMainAccounts() {
            this.$refs['main_account'].clearSelection();
            this.$refs['account_type'].clearSelection();
        },
        clearOffsets() {
            this.item.offset_company_accounts = null;
            this.$refs['offset_account_type'].clearSelection();
            this.$refs['offset_account'].clearSelection();
            this.item.offset_transaction_text = null;
        },

        keyGenerator() {
            return Math.random() + Math.random().toString(36).substring(7);;
        }
    }
}