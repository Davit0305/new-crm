<template>
    <div id="order_payment_info">
        <div class="groupbort inline">
            <span class="title">Оплата</span>
            <div class="bort">
                <span class="span">Срезка / Сопутка / Упаковка</span>
                <input v-model="order.cvety" class="sum" maxlength="9" type="text" name="cvety">
                <input v-model="order.tovar" class="sum" maxlength="9" type="text" name="tovar">
                <input v-model="order.upakovka" class="sum" maxlength="9" type="text" name="upakovka">
            </div>
            <div class="bort" style="margin-top:15px">
                <span class="span">Прочие / Наценка  / Доставка</span>
                <input v-model="order.prochee" class="sum" maxlength="9" type="text" name="prochee">
                <input v-model="order.nacenka" class="sum" maxlength="9" type="text" name="nacenka">
                <input v-model="order.dostavka" class="sum" maxlength="9" type="text" name="dostavka">
            </div>
            <div v-if="showVip === true" class="bort" style="margin-top: 15px;">
                <span class="span">Основание для скидки</span>
                <span style="margin-left: 183px;margin-top: 3px;position: absolute;font-size: 18px;">{{ nacenkaProc }}</span>
                <select v-model="order.vip" class="vipform" name="vip">
                    <option :value="1">Не указано</option>
                    <option :value="2">Сеньковский Е.В.</option>
                    <option :value="3">Сеньковская Л.Б.</option>
                    <option :value="6">Дисконтная карта</option>
                    <option :value="7">Скидка сайта</option>
                </select>
            </div>
            <div v-if="order.vip === 6" class="bort discount-card red" style="margin-top: 15px;">
                <span class="span">Дисконтная карта</span>
                <input v-model="order.discount_card" class="nameprod" type="text" name="discount_card">
            </div>
            <div style="clear:both"></div>
            <div class="bort" style="margin-top:15px">
                <span class="span">Способ оплаты</span>
                <select v-model="order.money" class="moneyform" name="money">
                    <option :value="0"> Не определен</option>
                    <option :value="500">Наличными в салоне</option>
                    <option :value="501">Терминал в салоне</option>
                    <option :value="509">Курьеру банковской картой</option>
                    <option :value="507">Курьеру наличными</option>
                    <option :value="106" selected="">Сбербанк через интернет</option>
                    <option :value="504">Мобильная коммерция</option>
                    <option :value="107">Uniteller</option>
                    <option :value="15">Uniteller (QIWI)</option>
                    <option :value="16">Uniteller (Yandex)</option>
                    <option :value="17">Uniteller (Web-money)</option>
                    <option :value="505">Расчетный счет (Корп. клиент)</option>
                    <option :value="999">100% возврат от Е.В.</option>
                    <option :value="8">В долг (VIP клиент)</option>
                    <option :value="7">Взаимозачет</option>
                    <option :value="6">В счет заработной платы</option>
                    <option :value="18">Расчетный счет (Партнеры)</option>
                    <option :value="9">Деливери Клаб</option>
                    <option :value="11">Робокасса (ПИОН)</option>
                    <option :value="10">Яндекс Маркет</option>
                    <option :value="12">Флорист.Ру</option>
                    <option :value="14">Сертификат (выигрыш в конкурсе)</option>
                </select>
                <input v-model="sumNom" disabled class="sum" maxlength="9" type="text" style="float:right" name="sum_mon">

                <div class="hide" style="display: block;">
                    <select v-model="order.money_2" style="margin-top:5px;" class="moneyform" name="money_2">
                        <option :value="0" selected=""> Не определен</option>
                        <option :value="500">Наличными в салоне</option>
                        <option :value="501">Терминал в салоне</option>
                        <option :value="509">Курьеру банковской картой</option>
                        <option :value="507">Курьеру наличными</option>
                        <option :value="106" disabled="disabled">Сбербанк через интернет</option>
                        <option :value="504">Мобильная коммерция</option>
                        <option :value="107">Uniteller</option>
                        <option :value="15">Uniteller (QIWI)</option>
                        <option :value="16">Uniteller (Yandex)</option>
                        <option :value="17">Uniteller (Web-money)</option>
                        <option :value="505">Расчетный счет (Корп. клиент)</option>
                        <option :value="999">100% возврат от Е.В.</option>
                        <option :value="8">В долг (VIP клиент)</option>
                        <option :value="7">Взаимозачет</option>
                        <option :value="6">В счет заработной платы</option>
                        <option :value="18">Расчетный счет (Партнеры)</option>
                        <option :value="9">Деливери Клаб</option>
                        <option :value="11">Робокасса (ПИОН)</option>
                        <option :value="10">Яндекс Маркет</option>
                        <option :value="12">Флорист.Ру</option>
                        <option :value="14">Сертификат (выигрыш в конкурсе)</option>
                    </select>
                    <input v-model="order.sum_mon_2" class="sum" maxlength="9" type="text" style="float:right;margin-top:5px;" name="sum_mon_2">
                </div>
            </div>
            <div class="bort" style="margin-top:15px;">
                <span class="span">Через какой банк?</span>
                <select v-model="order.bank" class="moneyform" name="bank">
                    <option :value="0">Не указано</option>
                    <option :value="2">Кредит Европа банк</option>
                    <option :value="4">Сбербанк</option>
                </select>
            </div>

            <div style="float:right">
                <div style="font-weight:700;margin-top:10px">
                    Итого: <span>{{ endSum }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderPaymentInfoTemplate",
        props: {
            o: Object
        },
        data: function () {
            return {
                order: this.o,
                showVip: false
            };
        },
        watch: {
            'order.nacenka': function (newVal, oldVal) {
                if(parseFloat(newVal) < 0) {
                    this.showVip = true;
                } else {
                    this.showVip = false;
                    this.order.vip = 1;
                }
            },
            'order.vip': function (newVal, oldVal) {
                if(newVal != 6) {
                    this.order.discount_card = '';
                }
            },
        },
        mounted() {
            if(parseFloat(this.order.nacenka) < 0) {
                this.showVip = true;
            } else {
                this.showVip = false;
                this.order.vip = 1;
            }
        },
        computed: {
            sumNom: function() {
                let sum_nom = (
                    parseFloat(this.order.cvety) +
                    parseFloat(this.order.tovar) +
                    parseFloat(this.order.upakovka) +
                    parseFloat(this.order.prochee) +
                    parseFloat(this.order.nacenka) +
                    parseFloat(this.order.dostavka)
                    ) - parseFloat(this.order.sum_mon_2);
                this.order.sum_mon = sum_nom.toFixed(2);
                return this.order.sum_mon;
            },
            endSum: function() {
                let end_sum = parseFloat(this.sumNom) + parseFloat(this.order.sum_mon_2);
                this.order.summa = end_sum.toFixed(2);
                return this.order.summa;
            },
            nacenkaProc: function () {
                let nacenka = (
                    parseFloat(this.order.nacenka) /
                    (
                        parseFloat(this.order.cvety) +
                        parseFloat(this.order.prochee) +
                        parseFloat(this.order.tovar) +
                        parseFloat(this.order.upakovka)
                    ) * 100 ).toFixed(0) + '%';
                return nacenka;
            }
        },
    }
</script>

<style scoped>
    .groupbort {
        border: 1px solid #a6b7c7;
        padding: 8px 12px 21px 12px;
        margin-bottom: 15px;
        vertical-align: top;
        width: 49%;
    }
    .groupbort .bort, .groupbort .bort_red {
        padding: 10px 5px 9px 5px;
        margin-top: 15px;
        width: 256px;
        text-align: left;
    }
    .groupbort input {
        border: 1px solid transparent;
    }
    .groupbort .title {
        background: #f3f3f3;
        float: left;
        margin-top: -17px;
        padding: 1px;
        font-size: 14px;
    }
    .groupbort .bort_textinput {
        height: 19px;
        margin-right: 5px;
        padding: 2px;
        width: 245px;
    }



    .sum {
        height: 19px;
        margin-left: 4px;
        padding: 2px;
        text-align: right;
        width: 70px;
    }
    #order_payment_info {
        justify-content: center;
        display: flex;
    }
    #order_payment_info input {
        border: 1px solid transparent;
    }
    .nameprod {
        height: 19px;
        margin-right: 5px;
        padding: 2px;
        width: 238px;
    }
    .bortSost {
        border: 1px solid #a6b7c7;
        padding: 10px 5px 5px 5px;
        margin-top: 10px;
    }
    .bortSost span {
        background: #f3f3f3;
        float: left;
        margin-top: -20px;
        padding: 1px;
    }
    .bortSost .bortSostB {
        float: left;
        width: 122px;
        margin-bottom: -5px;
    }
    .bortSost .bortSostB div {
        height: 25px;
        text-align: left;
    }
    .bortSost .bortSostB input[type="text"] {
        float: left;
        width: 20px;
        text-align: center;
        padding: 3px 1px;
        margin: -3px 5px 0px 0px;
        font-size: 10px;
    }
    .bortSost .bortSostB label {
        padding: 0px;
    }
    .bortSost .bortSostB input.bigInp {
        width: 87px;
        text-align: left;
        font-size: 10px;
        padding: 3px;
        margin: -3px -2px;
    }
    .moneyform {
        height: 25px;
        padding: 2px;
        width: 160px;
    }
    .bank_red, .bort_red {
        border: 1px solid #ff0000;
        padding: 10px 5px 5px 5px;
        text-align: left;
    }
    .bank_red span, .bort_red span {
        background: #f3f3f3;
        color: #ff0000;
        float: left;
        margin-top: -20px;
        padding: 1px;
    }
    .bank_red label, .bort_red label {
        margin: 0px 5px 0px 0px;
        display: block;
        float: left;
        padding: 0px;
        line-height: 14px;
    }
    .bank_red input[type="radio"], .bort_red input[type="radio"] {
        float: left;
    }
    .status {
        height: 25px;
        padding: 2px;
        width: 108px;
    }
    .driver_done_container {
        clear: both;
        text-align: center;
        height: 25px;
    }
    .driver_done_container .driver_done.disabled {
        cursor: default;
        color: lightgray;
    }
    .driver_done_container .driver_done {
        width: 140px;
        display: inline;
        cursor: pointer;
    }
</style>
