<template>
    <div class="row">
        <div class="container">
            <grid-layout
                :layout.sync="layout"
                :col-num="8"
                :row-height="150"
                :is-draggable="true"
                :is-resizable="true"
                :is-mirrored="false"
                :vertical-compact="true"
                :margin="[10, 10]"
                :use-css-transforms="true"
                :responsive="true"
                :auto-size="true"
                :cols="{ lg: 3, md: 3, sm: 3, xs: 2, xxs: 1 }"
                @layout-updated="updateLayout"
            >
                <grid-item v-for="item in layout"
                           :x="item.x"
                           :y="item.y"
                           :w="item.w"
                           :h="item.h"
                           :i="item.i"
                           :key="item.i">
                    <card :title="item.i" :description="item.i" :editButtonClicked="openModal"></card>
                </grid-item>
            </grid-layout>
            <div class="modal" tabindex="-1" role="dialog" id="cardDetailModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Modal body text goes here.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueGridLayout from 'vue-grid-layout';

    import Card from './Card.vue';

    const testLayout = [
        {"x":0,"y":0,"w":1,"h":1,"i":"0"},
        {"x":1,"y":0,"w":1,"h":1,"i":"1"},
        {"x":2,"y":0,"w":1,"h":1,"i":"2"},
        {"x":0,"y":1,"w":1,"h":1,"i":"3"},
        {"x":1,"y":1,"w":1,"h":1,"i":"4"},
        {"x":2,"y":1,"w":1,"h":1,"i":"5"},
        {"x":0,"y":2,"w":1,"h":1,"i":"6"},
        {"x":1,"y":2,"w":1,"h":1,"i":"7"},
        {"x":2,"y":2,"w":1,"h":1,"i":"8"},
        {"x":0,"y":3,"w":1,"h":1,"i":"9"},
        {"x":1,"y":3,"w":1,"h":1,"i":"10"},
        {"x":2,"y":3,"w":1,"h":1,"i":"11"},
    ];

    const initialData = window.dashboardInitalData;

    export default {
        data: function() {
            return {
                layout: testLayout,
            }
        },
        mounted: function() {
        },
        components: {
            GridLayout: VueGridLayout.GridLayout,
            GridItem: VueGridLayout.GridItem,
            Card,
        },
        methods: {
            openModal: function(data) {
                $('#cardDetailModal').modal('show');
            },
            updateLayout: function() {
                console.log('layout updated');
            },
        },
    }
</script>

<style scoped lang="scss">
    .row {
        overflow-x: scroll;
        padding: 10px 0;
    }
    .container {
        min-width: 1140px;
        margin: -10px;
    }
    .date-select {
        #list-today {
            background: #fff;
            color: #000;
        }
    }
    .operator-select {
        margin-bottom: 1rem;
    }
</style>
