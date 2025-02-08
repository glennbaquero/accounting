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
            invoice-journal-number="inventory_journal_number"
            invoice-header-title="Inventory Journal Number"
            modal-title-update="Update inventory journal"
            modal-title-create="New inventory journal"
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