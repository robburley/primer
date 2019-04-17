<style>
</style>

<template>
    <span>
        <i class="fa" :class="getIcon"></i>
    </span>
</template>

<script>
    export default {
        components: {},
        props: {
            icon: {
                type: String,
                required: true,
            },
        },
        filters: {},
        computed: {
            getIcon() {
                let iconClass = {}

                iconClass[this.icon] = true

                return iconClass
            },
        },
        data() {
            return {
                data: {},
                utilities: {},
            }
        },
        methods: {},
        mounted() {
        },
    }
</script>

