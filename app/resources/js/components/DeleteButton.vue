<template>
    <button class="btn btn-danger" @click="confirmDeletion">
        {{ deleteButtonText }}
    </button>
</template>

<script>
    export default {
        props: {
            formElementId: String,
            deleteButtonText: {
                type: String,
                default: '削除',
            },
        },
        methods: {
            confirmDeletion: function (e) {
                e.preventDefault();
                this.$swal.fire({
                    title: '削除の確認',
                    text: "この操作を取り消すことはできません。削除対象に関連するすべてのデータも同時に失われます。本当に実行してよろしいですか?",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'キャンセル',
                    confirmButtonText: '削除を実行',
                    confirmButtonColor: '#d33',
                }).then((result) => {
                    if (result.value) {
                        document.getElementById(this.formElementId).submit();
                    }
                })
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>
