<template>
    <div>
        <journal-header
            :type="type"
            :fetch-url="fetchUrl"
            :submit-url="submitUrl"
            :edit-url="editUrl"
            :cost_centers="cost_centers"
            :departments="departments"
            :expense_purposes="expense_purposes"
            invoice-journal-number="journal_number"
            invoice-header-title="Purchase Return Order Journal Number"
            modal-title-update="Update purchase return order journal"
            modal-title-create="New purchase return order journal"
            :createUrl="createUrl"
            :clients="clients"
            :statusUpdateUrl="statusUpdateUrl"
        ></journal-header>
    </div>
</template>
<script>
    import { bus }from 'Root/bus.js';
    import JournalHeader from 'Components/journals/Header.vue';

    export default {
        props: {
            type: String,
            fetchUrl: String,
            submitUrl: String,
            editUrl: String,
            cost_centers: Array,
            departments: Array,
            expense_purposes: Array,
            createUrl: String,
            statusUpdateUrl: String,
            clients: Array,
        },

        components: {
            'journal-header' : JournalHeader
        }
    }
</script>