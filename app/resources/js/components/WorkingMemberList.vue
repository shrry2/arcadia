<template>
    <div>
        <p class="float-right m-0 py-1">
            <strong>{{ members.length }}名</strong>
        </p>
        <h3>出勤中のスタッフ</h3>
        <div class="card">
            <table class="table table-hover mb-0">
                <thead>
                <tr>
                    <th scope="col">氏名</th>
                    <th scope="col">出勤時刻</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="member in members">
                    <td>{{ member.name }}</td>
                    <td>{{ member.begin_at }}</td>
                    <td class="text-right">
                        <button type="button" class="btn btn-sm btn-success" @click="() => promptFinishWork(member)">退勤</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['apiListUrl', 'apiFinishUrl'],
        data () {
            return {
                members: [],
            };
        },
        methods: {
            loadMemberList: async function () {
                const res = await axios.get(this.apiListUrl);
                this.members = res.data;
            },
            promptFinishWork: async function (user) {
                this.$swal.fire({
                    title: `${user.name}さんの退勤時刻を記録します`,
                    text: 'パスワードを入力して認証してください',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        autocomplete: 'off',
                    },
                    showCancelButton: true,
                    confirmButtonText: '認証',
                    cancelButtonText: 'キャンセル',
                    showLoaderOnConfirm: true,
                    preConfirm: (password) => {
                        return axios.post(this.apiFinishUrl, {
                                user_id: user.id,
                                password,
                            }).then((response) => {
                                return response;
                            })
                            .catch((error) => {
                                if (error.response.data.errors) {
                                    const errors = error.response.data.errors;
                                    if (errors.password) {
                                        this.$swal.showValidationMessage(errors.password[0]);
                                    } else {
                                        this.$swal.showValidationMessage(error.response.data.message);
                                    }
                                } else {
                                    this.$swal.showValidationMessage(error.response.data.message);
                                }
                            })
                    },
                    allowOutsideClick: () => !this.$swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.loadMemberList();
                        this.$swal.fire(
                            'お疲れさまでした!',
                            result.value.data.message,
                            'success'
                        );
                    }
                })
            },
        },
        mounted () {
            this.loadMemberList();
        },
    }
</script>

<style lang="scss" scoped>
    th {
        border-top: 0;
    }
</style>
