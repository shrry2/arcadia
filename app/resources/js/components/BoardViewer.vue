<template>
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="container">
                    <button type="button" class="btn btn-success float-right mr-0 mr-lg-4" @click="openNewTaskModal">新規タスク</button>
                    <button
                        type="button"
                        class="btn"
                        :class="{'btn-outline-primary': mode !== 'grid', 'btn-primary': mode === 'grid'}"
                        @click="changeViewMode('grid')"
                    >
                        グリッド表示
                    </button>
                    <button
                        type="button"
                        class="btn"
                        :class="{'btn-outline-primary': mode !== 'list', 'btn-primary': mode === 'list'}"
                        @click="changeViewMode('list')"
                    >
                        リスト表示
                    </button>
                </div>
            </div>
            <div class="alert alert-warning" v-if="errorMessage">
                {{ errorMessage }}
            </div>
            <template v-else>
                <grid-viewer
                    v-if="mode === 'grid'"
                    data="pinnedTasks"
                ></grid-viewer>
                <list-viewer
                    v-if="mode === 'list'"
                    tasks="tasks"
                ></list-viewer>
            </template>
            <new-task-modal :task-store-url="this.taskStoreUrl" @onTaskCreated="handleNewTaskCreated"></new-task-modal>
        </div>
    </div>
</template>

<script>
    import GridViewer from './boardViewer/GridViewer.vue';
    import ListViewer from "./boardViewer/ListViewer";

    import NewTaskModal from "./boardViewer/NewTaskModal";

    export default {
        props: ['dataUrl', 'taskStoreUrl'],
        data: function() {
            return {
                mode: 'grid',
                tasks: [],
                errorMessage: null,
            }
        },
        mounted: function() {
            this.loadTasks();
        },
        components: {
            NewTaskModal,
            GridViewer,
            ListViewer,
        },
        methods: {
            changeViewMode: function(newMode) {
                this.mode = newMode;
            },
            loadTasks: async function() {
                try {
                    const res = await axios.get(this.dataUrl);
                    this.tasks = res.data;
                    this.errorMessage = null;
                } catch (e) {
                    this.errorMessage = e.response.data.message;
                }
            },
            openNewTaskModal: function() {
                $('#newTaskModal').modal('show');
            },
            handleNewTaskCreated: function() {
                $('#newTaskModal').modal('hide');
                this.loadTasks();
            }
        },
        computed: {
            pinnedTasks: function () {
                return this.tasks.filter(function (task) {
                    return task.layout !== null;
                })
            },
        },
    }
</script>

<style scoped lang="scss">
</style>
