<template>
    <div class="form-group">
        <div class="input-group" :class="small ? 'input-group-sm' : ''">
            <div class="input-group-prepend">
                <button type="button" @click="clear" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <input v-model="value" type="text" :name="name" placeholder="Search here..." aria-label="Search here..." class="form-control">
            <div class="input-group-append">
                <button type="button" @click="search" class="btn btn-secondary">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    methods: {
        search() {
            this.$emit('search', this.value);
        },
        clear() {
            this.value = null;
            this.search();
        }
    },

    data() {
        return {
            value: null,
        }
    },

    props: {
        small: {
            default: false,
            type: Boolean,
        },

        name: String,
    },
}
</script>