<template>
    <div class="d-flex">
        <div class="groupbort inline">
            <span class="title">Курьер</span>
            <div class="bort">
                <span class="span">Дата доставки</span>
                <date-picker
                    v-model="order.delivery_date"
                    format="DD.MM.YYYY HH:mm"
                    type="datetime"
                    style="width: 100%"
                    lang="ru"
                    value-type="DD.MM.YYYY HH:mm"
                />
            </div>
            <div class="bort">
                <span class="span">Тип срочности</span>
                <div class="typeDriver">
                    <div @click="chaneDeliveryDateType(1)" :class="delivery_date_type === 1 ? 'samoButton selected' : 'samoButton'">Экспресс</div>
                    <div @click="chaneDeliveryDateType(2)" :class="delivery_date_type === 2 ? 'selfAssignButton selected' : 'selfAssignButton'">Ближайшее время</div>
                </div>
            </div>
            <div class="bort">
                <span class="span">Доставляет</span>
                <select :disabled="delivery_type > 0" v-model="order.driver_id" id="driver_select" class="js-example-data-ajax form-control">
                    <option :value="order.driver_id">{{ order.driver }}</option>
                </select>
            </div>
            <div v-if="delivery_type === 0">
                <div v-if="order.type_driver === 3" class="bort">
                    <span class="span">Оплата такси</span>
                    <input placeholder="0.00" v-mask="['####.##', '###.##', '##.##', '#.##']" v-model="order.driver_summ" class="bort_textinput" type="tel">
                </div>
                <div v-if="order.type_driver > 0" class="bort">
                    <span class="span">Тип курьера</span>
                    <div class="typeDriver">
                        <div v-if="order.type_driver < 3" @click="order.type_driver = 1" :class="order.type_driver === 1 ? 'typeDriverVariant shtat selected' : 'typeDriverVariant shtat'">Штатный</div>
                        <div v-if="order.type_driver < 3" @click="order.type_driver = 2" :class="order.type_driver === 2 ? 'typeDriverVariant vneshtat selected' : 'typeDriverVariant vneshtat'">Внештатный</div>
                        <div v-if="order.type_driver === 3" @click="order.type_driver = 3" :class="order.type_driver === 3 ? 'typeDriverVariant vneshtat selected' : 'typeDriverVariant vneshtat'">Такси</div>
                        <div v-if="order.type_driver === 4" @click="order.type_driver = 4" :class="order.type_driver === 4 ? 'typeDriverVariant vneshtat selected' : 'typeDriverVariant vneshtat'">Служба</div>
                        <div v-if="order.type_driver === 5" @click="order.type_driver = 5" :class="order.type_driver === 5 ? 'typeDriverVariant vneshtat selected' : 'typeDriverVariant vneshtat'">Партнёры</div>
                    </div>
                </div>
            </div>
            <div class="bort">
                <span class="span">Тип доставки</span>
                <div class="typeDriver">
                    <div @click="chaneDeliveryType(1)" :class="delivery_type === 1 ? 'samoButton selected' : 'samoButton'">Самовывоз</div>
                    <div @click="chaneDeliveryType(2)" :class="delivery_type === 2 ? 'selfAssignButton selected' : 'selfAssignButton'">Отдать курьерам</div>
                </div>
            </div>
        </div>

        <div class="groupbort inline"  style="margin-left: 2%;">
            <span class="title">Торговая точка</span>
            <div class="bort">
                <span class="span">Откуда</span>
                <select v-model="order.tocka_id" id="point_select" class="js-example-data-ajax form-control">
                    <option v-if="order.point" :value="order.point.id">{{ order.point.full_name }}</option>
                </select>
            </div>
            <div class="bort">
                <span class="span">ФИО продавца</span>
                <select v-model="order.seller_fio" id="seller_select" class="js-example-data-ajax form-control">
                    <option :value="order.seller_fio">{{ order.seller_fio }}</option>
                </select>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        name: "OrderDriverInfoTemplate",
        props: {
            o: Object
        },
        data: function () {
            return {
                order: this.o,
                delivery_type: 0,
                delivery_date_type: 0,
                user: this.$store.getters.GET_USER
            };
        },
        created() {
            if(this.order.show_in_app === 1) {
                this.delivery_type = 2;
            } else if(this.order.samo === 1) {
                this.delivery_type = 1;
            } else {
                this.delivery_type = 0;
            }

            if(this.order.express === 1) {
                this.delivery_date_type = 1;
            } else if(this.order.soon === 1) {
                this.delivery_date_type = 2;
            } else {
                this.delivery_date_type = 0;
            }
        },
        watch: {
            'order.type_driver': function (newVal, oldVal) {
                if(newVal !== 3) {
                    this.order.driver_summ = 0;
                }
            },
        },
        mounted() {
            $('#driver_select').select2(this.driverOption);
            $('#driver_select').on('select2:select', (e) => {
                this.order.driver_id = e.params.data.id;
                this.order.driver = e.params.data.text;
                this.order.type_driver = e.params.data.shtat;
            });
            $('#seller_select').select2(this.sellerOption);
            $('#seller_select').on('select2:select', (e) => {
                this.order.seller_fio = e.params.data.text;
                $('#seller_select').val(e.params.data.text).trigger('change')
            });
            $('#point_select').select2(this.pointOption);
            $('#point_select').on('select2:select', (e) => {
                this.order.tocka_id = e.params.data.id;
                $('#point_select').val(e.params.data.id).trigger('change')
            });
        },
        computed: {
            driverOption: function() {
                return {
                    minimumInputLength: 2,
                    language: this.selectLanguage,
                    templateResult : function (result) {
                        if (result.loading) return result.text;
                        let shift = '<span title="Не на смене" style="color:#ccc;font-size: 18px;float: right;">●</span>'
                        if(result.in_shift === 1) {
                            shift = '<span title="На смене" style="color:#3e9f0d;font-size: 18px;float: right;">●</span>'
                        }
                        var html = '<span>' + result.text + '</span>' + shift;
                        return $(html);
                    },
                    ajax: {
                        url: '/api/order/searchDrivers',
                        headers: {
                            "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").getAttribute('value')
                        },
                        type: "POST",
                        data: function (params) {
                            var query = {
                                search: params.term,
                            }
                            return query;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.worker_surname + ' ' + item.worker_name,
                                        in_shift: item.in_shift,
                                        shtat: item.shtat,
                                        id: item.id
                                    }
                                })
                            };
                        }
                    }
                }
            },
            sellerOption: function() {
                return {
                    minimumInputLength: 2,
                    language: this.selectLanguage,
                    ajax: {
                        url: '/api/order/searchSellers',
                        headers: {
                            "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").getAttribute('value')
                        },
                        type: "POST",
                        data: function (params) {
                            var query = {
                                search: params.term,
                            }
                            return query;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.surname + ' ' + item.name + ' ' + item.middlename,
                                        id: item.surname + ' ' + item.name + ' ' + item.middlename
                                    }
                                })
                            };
                        }
                    }
                }
            },
            pointOption: function() {
                return {
                    minimumInputLength: 2,
                    language: this.selectLanguage,
                    ajax: {
                        url: '/api/order/searchPoints',
                        headers: {
                            "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").getAttribute('value')
                        },
                        type: "POST",
                        data: function (params) {
                            var query = {
                                search: params.term,
                            }
                            return query;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.full_name,
                                        id: item.id
                                    }
                                })
                            };
                        }
                    }
                }
            },
            selectLanguage: function () {
                return {
                    errorLoading: function () {
                        return 'Результат не может быть загружен.';
                    },
                    inputTooShort: function (args) {
                        var remainingChars = args.minimum - args.input.length;
                        var message = 'Введите ' + remainingChars + ' или более символов';
                        return message;
                    },
                    noResults: function () {
                        return 'Ничего не найдено';
                    },
                    searching: function () {
                        return 'Поиск…';
                    }
                }
            }
        },
        methods: {
            searchDrivers() {
                axios.post('/api/order/searchDrivers', {text: 'ff'})
                    .then( res => {
                        console.log(res);
                        for (var i = 0; i < res.data.length; i++) {
                            this.drivers.push({id: res.data[i].id, text: res.data[i].worker_surname})
                        }
                    })
                    .catch(err => {
                        this.ordersLoaded = true;
                        console.log(err.response);
                        window.swal.fire({
                            title: 'Произошла ошибка!',
                            text: err.response.data.message,
                            icon: 'error',
                            customClass: {
                                container: 'z-index-max',
                            }
                        })
                    });
            },
            chaneDeliveryType(type) {
                if(type === 1) {
                    if(this.delivery_type === type) {
                        this.delivery_type = 0;
                        this.order.samo = 0;
                    } else {
                        this.delivery_type = type;
                        this.order.samo = 1;
                    }
                } else {
                    if(this.delivery_type === type) {
                        this.delivery_type = 0;
                        this.order.show_in_app = 0;
                    } else {
                        this.delivery_type = type;
                        this.order.show_in_app = 1;
                    }
                }
                this.order.driver_id = 0;
                $('#driver_select').empty().trigger('change')
                this.order.driver = '';
                this.order.type_driver = 0;
                this.order.driver_summ = 0;
            },
            chaneDeliveryDateType(type) {
                if(type === 1) {
                    if(this.order.express === 1) {
                        this.delivery_date_type = 0;
                        this.order.express = 0;
                        this.order.soon = 0;
                    } else {
                        this.delivery_date_type = 1;
                        this.order.express = 1;
                        this.order.soon = 0;
                    }
                } else {
                    if(this.order.soon === 1) {
                        this.delivery_date_type = 0;
                        this.order.express = 0;
                        this.order.soon = 0;
                    } else {
                        this.delivery_date_type = 2;
                        this.order.express = 0;
                        this.order.soon = 1;
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .samoButton, .selfAssignButton {
        position: relative;
        top: initial;
        left: initial;
        right: initial;
        bottom: initial;
        opacity: initial;
        border: 1px solid #a6b7c7;
        height: initial;
        background: none;
        text-align: center;
        float: left;
        padding: 6px 15px;
        cursor: pointer;
        color: #6c6c6c;
    }
    .samoButton {
        margin-right: 2px;
        border: 1px solid #a6b7c7;
    }
    .samoButton:hover, .selfAssignButton:hover {
        background: #d7e1f3;
    }
    .samoButton.selected, .selfAssignButton.selected {
        background: #88aae6;
        color: white;
    }
    .typeDriver .shtat, .typeDriver .vneshtat {
        border: 1px solid transparent;
        height: 23px;
        width: max-content;
        line-height: 23px;
        text-align: center;
        margin-left: 2px;
        cursor: pointer;
        color: #6c6c6c;
    }
    .typeDriverVariant {
        border: 1px solid #a6b7c7 !important;
        margin-left: 0 !important;
        margin-right: 2px;
        margin-top: 2px;
        width: max-content;
        padding: 0 5px;
        /*display: none;*/
    }
    .typeDriver .shtat.selected, .typeDriver .vneshtat.selected {
        background: #88aae6;
        color: white;
        border: 1px solid #88aae6;
        display: block !important;
    }
    .typeDriver .shtat:hover, .typeDriver .vneshtat:hover {
        background: #d7e1f3;
        border: 1px solid #d7e1f3;
    }
    .typeDriver {
        display: flex;
        width: 100%;
        flex-wrap: wrap;
        justify-content: center;
    }
</style>
