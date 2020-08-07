<template>
    <div class="card">
        <div class="card-body">
            <button type="button"
                    class="cardEditButton btn btn-outline-primary float-right rounded"
                    @click="() => editButtonClicked()">
                編集
            </button>
            <h5 class="card-title"><span class="badge badge-secondary">未対応</span> {{ title }}</h5>
            <p class="card-text">{{ description }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['title', 'description', 'editButtonClicked'],
        methods: {
            greet: function (event) {
                console.log(this);
            },
        },
    }
</script>

<style lang="scss" scoped>
    .card {
        height: 100%;
    }
    .card:hover {
        transition: .2s;
        box-shadow: 0 0 6px 3px #efefef;
    }
    .card-body {
        overflow: scroll;
    }
    .cardEditButton {
        display: none;
    }
    .card:hover {
        .cardEditButton {
            display: inline-block;
        }
    }
</style>
