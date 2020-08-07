<template>
    <div class="modal" tabindex="-1" role="dialog" id="newTaskModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">新しいタスクを作成</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputTitle" class="sr-only">タイトル</label>
                        <input type="text" v-model="title" class="form-control" id="inputTitle" value="" placeholder="タイトル" autocomplete="off">
                        <div class="alert alert-danger mt-1" v-if="errors.title">
                            <ul class="mb-0">
                                <li v-for="message in errors.title" :key="message">
                                    {{ message }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <quill-editor
                        v-model="note"
                        :options="this.noteEditorOptions"
                    ></quill-editor>
                    <div class="alert alert-danger mt-1" v-if="errors.note">
                        <ul class="mb-0">
                            <li v-for="message in errors.note" :key="message">
                                {{ message }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="alert alert-danger container" v-if="errors.general">
                        <ul class="mb-0">
                            <li v-for="message in errors.general" :key="message">
                                {{ message }}
                            </li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" :disabled="isProcessing">閉じる</button>
                    <button type="button" class="btn btn-primary" @click="createNewTask" :disabled="isProcessing">タスクを新規作成</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import noteEditorOptions from '../../utils/noteEditorOptions';

    export default {
        props: ['taskStoreUrl'],
        data: function() {
            return {
                title: '',
                note: '',
                noteEditorOptions,
                isProcessing: false,
                errors: {},
            };
        },
        methods: {
            createNewTask: async function () {
                this.isProcessing = true;
                this.errors = {};
                let res;
                try {
                    res = await axios.post(this.taskStoreUrl, {
                        title: this.title,
                        note: this.note,
                    });
                } catch (e) {
                    res = e.response;
                    if (res.data.errors) {
                        this.errors = {
                            ...res.data.errors,
                            general: ['入力内容に問題があります。確認して再度実行してください'],
                        };
                    } else {
                        this.resetErrors();
                        this.errors.general = [
                            '何らかのエラーが発生したため、タスクを作成できませんでした。もう一度実行してみるか、入力内容を別の場所に保護したうえでページを再読み込みしてください。',
                        ];
                    }
                } finally {
                    this.isProcessing = false;
                }
                // 入力内容をクリア
                this.title = '';
                this.note = '';
                this.$emit('onTaskCreated');
            },
        },
    }
</script>

<style scoped lang="scss">
</style>
