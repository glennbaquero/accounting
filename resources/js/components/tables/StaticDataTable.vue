<template>
    <div>
        <div class="table-responsive">
            <table :id="dataTableId" class="table table-hover table-striped table-bordered text-center" style="white-space: nowrap">
                <thead>
                    <tr>
                        <th v-for="header in headers">{{ header.text }}</th>
                    </tr>
                </thead>
                <tbody>
                        <slot name="body" :items="current_data"></slot>
                </tbody>
            </table>
        </div>

        <!-- pagination -->
        <div v-if="items.length >= limit">
            <ul class="pagination justify-content-end mt-2">
                <li class="page-item"><a class="page-link" href="javascript:void(0)" @click="prev">Previous</a></li>
                <li class="page-item" v-for="(data,key) in chunk_data" :class="(key+1) === (current_page+1) ? 'active' : ''">
                    <a class="page-link" href="javascript:void(0)" @click="goTo(key)">{{ key+1 }}</a>
                </li>
                <li class="page-item"><a class="page-link" href="javascript:void(0)" @click="nxt">Next</a></li>
              </ul>
        </div>
    </div>
    
</template>
<script>
    export default{
        props: {
            isDefault: {
                default: true,
                type: Boolean
            },

            items: Array,

            headers: Array,
            dataTableId: {
                default: 'dataTable',
                type: String
            },

            limit: {
                type: Number,
                default: 15
            }
        },

        data() {
            return {
                has_fetched: false,
                current_page: 0,
                previous_page: 0,
                next_page: 1,
                current_data: []
            }
        },

        computed: {
            chunk_data() {
                return _.chunk(this.items, this.limit);
            }
        },

        watch: {
            items(val) {
                this.load();
            }
        },

        mounted() {
            this.load();
        }, 

        methods: {
            nxt() {
                var current = this.current_page + 1;
                if(this.chunk_data.length > current) {
                    this.current_page += 1;
                    this.load();
                }
            },
            
            prev() {
                var current = this.current_page - 1;
                if(current >= 0) {
                    this.current_page -= 1;
                    this.load();
                }
            },

            goTo(key) {
                var key = key;
                this.current_page = key;
                this.load();
            },

            load() {
                var chunk_datas = _.chunk(this.items, this.limit);
                this.current_data = chunk_datas[this.current_page];
            },
        }
    }
</script>