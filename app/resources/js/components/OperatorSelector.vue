<template>
    <div class="form-inline">
        <div class="input-group w-100">
            <div class="input-group-prepend">
                <label class="input-group-text" for="operatorSelector">担当者</label>
            </div>
            <select class="custom-select" id="operatorSelector" v-model="operatorId" @change="switchOperator">
                <option disabled value="">選択してください</option>
                <option v-for="operator in operators" :value="operator.id">{{ operator.name }}</option>
            </select>
        </div>
    </div>
</template>

<script>
    import isStorageAvailable from '../utils/isStorageAvailable';

    export default {
        props: ['apiOperatorListUrl'],
        data () {
            return {
                operatorId: '',
                operators: [],
            };
        },
        methods: {
            findOperator: function(operatorId) {
                return this.operators.find((operator) => operator.id === operatorId);
            },
            updateOperator: function(newOperatorId) {
                this.operatorId = newOperatorId;
                axios.defaults.headers.common['X-Operator-Id'] = this.operatorId;
                if(isStorageAvailable('sessionStorage')) {
                    sessionStorage.setItem('currentOperator', JSON.stringify(this.operator));
                }
            },
            initSelector: async function () {
                // 担当者リストを読み込み
                const res = await axios.get(this.apiOperatorListUrl);
                this.operators = res.data;
                // セッションストレージを読み取り
                if(isStorageAvailable('sessionStorage')) {
                    let currentOperator = sessionStorage.getItem('currentOperator');
                    try {
                        currentOperator = JSON.parse(currentOperator);
                        if (!currentOperator.id) {
                            sessionStorage.removeItem('currentOperator');
                            return;
                        }
                        const latestOperatorData = this.findOperator(currentOperator.id);
                        if (!latestOperatorData) {
                            sessionStorage.removeItem('currentOperator');
                            return;
                        }
                        this.updateOperator(latestOperatorData.id);
                    } catch (e) {
                        sessionStorage.removeItem('currentOperator');
                    }
                }
            },
            switchOperator: function(e) {
                this.updateOperator(Number(e.target.value));
            },
        },
        mounted () {
            this.initSelector();
        },
        computed: {
            operator: function() {
                return this.findOperator(this.operatorId);
            },
        },
    }
</script>

<style lang="scss" scoped>
</style>
