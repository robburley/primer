<template>
    <input type="file" name="fileUpload" @change="onFileChange" :class="classes">
</template>

<script>
    export default {
        props: {
            target: {
                type: String,
                required: true,
            },
            action: {
                type: String,
                required: true,
            },
            classes: {
                type: String,
            },
        },
        data() {
            return {
                file: null,
            }
        },
        methods: {
            emitter(event, data) {
                this.$emit(event, data)
            },
            onFileChange(e) {
                let vm = this

                vm.emitter('started')

                let files = e.target.files || e.dataTransfer.files

                if (!files.length) {
                    vm.emitter('error', [['No File???']])
                }

                this.file = files[0]

                let formData = new FormData()

                formData.append('file', this.file)

                axios({
                    method: this.action,
                    url: this.target,
                    data: formData,
                })
                    .then(function (response) {
                        vm.emitter('complete', response.data)
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status === 422) {
                            vm.emitter('error', error.response.data.errors)
                        }
                    })
            },
        },
    }
</script>