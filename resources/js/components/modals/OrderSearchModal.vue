<template>
    <div class="window-overlay">
        <div class="hide_line searchall" id="formallsearch">
            <div @click="hideSearchModal()" id="closeall">×</div>
            <div class="bigtitle">Расширенный поиск</div>
            <div v-if="modalLoaded === false" id="preloader"></div>
            <form v-else id="allformsearch">
                <div class="bort" style="float:left;width:45%;">
                    <span class="span">Дата и время доставки</span>
                    <div class="form_date">
                        <div class="form_date_label">с</div>
                        <date-picker
                            v-model="filter.date_start"
                            format="DD.MM.YYYY HH:mm"
                            type="datetime"
                            lang="ru"
                            value-type="DD.MM.YYYY HH:mm"
                            placeholder="Выберите дату и время"
                        />
                    </div>
                    <div class="form_date">
                        <div class="form_date_label">по</div>
                        <date-picker
                            v-model="filter.date_end"
                            format="DD.MM.YYYY HH:mm"
                            type="datetime"
                            lang="ru"
                            value-type="DD.MM.YYYY HH:mm"
                            placeholder="Выберите дату и время"
                        />
                    </div>
                </div>
                <div class="bort" style="float:right;height:40px;width:53%;">
                    <span class="span">Кем принят заказ</span>
                    <div style="margin:5px;">
                        <input id="searchofis" type="checkbox" value="1" v-model="filter.from_office"><label for="searchofis">Офис</label>
                        <input id="searchtoch" type="checkbox" value="2" v-model="filter.from_office"><label for="searchtoch">Точка</label>
                    </div>
                </div>
                <div class="express-check-wrap"><input v-model="filter.express" id="searchexpress" type="checkbox" value="1"><label title="Заказы выполненные с экспресс доставкой" for="searchexpress">Экспресс</label></div>
                <div class="express-check-wrap"><input v-model="filter.discard" id="searchwithdiscard" type="checkbox" value="1"><label for="searchwithdiscard">Дисконтная карта</label></div>
                <div style="clear:both;">&nbsp;</div>

                <div class="bort">
                    <span class="span">Искать в заказе слово</span>
                    <input v-model="filter.search" type="text" class="bortxt" size="10" placeholder="Что ищем?">
                </div>

                <div class="bort" style="margin-top:10px;">
                    <span class="span">Способ оплаты / Сумма / Дисконтная карта</span>
                    <select v-model="filter.payment_type" class="money">
                        <option v-for="payment_type in payment_types" :value="payment_type.number">{{ payment_type.name }}</option>
                    </select>
                    &nbsp;от&nbsp;
                    <input v-model="filter.summa_start" class="summa" type="text" placeholder="500">&nbsp;до&nbsp;
                    <input v-model="filter.summa_end" class="summa" type="text" placeholder="1500">
                    № <input v-model="filter.discard_number" class="discard" type="text" v-mask="'#############'" placeholder="0000000000000">
                </div>

                <div class="bort" style="margin-top:15px;">
                    <span class="span">Добавил диспетчер / Закрыл диспетчер / Как клиент узнал о компании?</span>
                    <select v-model="filter.add_disp" class="moneyform">
                        <option :value="0">Выбрать диспетчера</option>
                        <option v-for="user in users" :value="user.id">{{ user.fio }}</option>
                    </select>
                    <select v-model="filter.close_disp" class="moneyform">
                        <option :value="0">Выбрать диспетчера</option>
                        <option v-for="user in users" :value="user.id">{{ user.fio }}</option>
                        <option :value="'driver'">Закрыт курьером</option>
                    </select>
                    <select v-model="filter.order_source" class="moneyform">
                        <option v-for="order_source in order_sources" :value="order_source.id">{{ order_source.name }}</option>
                    </select>
                </div>

                <div class="bort" style="margin-top:15px;">
                    <span class="span">Город / Бренд / Торговая точка / Статус заказа</span>
                    <select v-model="filter.city" class="status">
                        <option :value="0">Все</option>
                        <option v-for="city in cities" :value="city.id">{{ city.name }}</option>
                    </select>

                    <select v-model="filter.brand" class="status">
                        <option :value="0">Все</option>
                        <option v-for="brand in brands" :value="brand.id">{{ brand.name }}</option>
                    </select>

                    <select v-model="filter.point" class="searchtochka" style="margin-right: 0;">
                        <option :value="0">Все</option>
                        <option v-for="point in points" :value="point.id">{{ point.name + ' [' + point.brand.short_name + ']' }}</option>
                    </select>

                    <select v-model="filter.status" class="status">
                        <option :value="0">Все</option>
                        <option :value="3">Активен</option>
                        <option :value="1">Выполнен</option>
                        <option :value="2">Отменен</option>
                    </select>
                </div>


                <div class="bort" style="margin-top:10px;">
                    <span class="span">Курьер (штатный/внештатный) / Сумма за доставку</span>
                    <select v-model="filter.driver_type" class="money">
                        <option :value="0">Все</option>
                        <option v-for="driver_type in driver_types" :value="driver_type.id">{{ driver_type.name }}</option>
                        <option :value="'free'">Не назначен</option>
                    </select>
                    &nbsp;от&nbsp;
                    <input v-model="filter.driver_summa_start" class="summa" type="text" placeholder="200">&nbsp;до&nbsp;
                    <input v-model="filter.driver_summa_start" class="summa" type="text" placeholder="250">
                </div>

                <div class="bort" style="margin-top:10px;">
                    <span class="span">Тип заказа</span>
                    <div style="float:left">
                        <input id="searchprostoi" v-model="filter.order_type" type="checkbox" value="0"><label for="searchprostoi">Обычный</label><br>
                        <input id="searchcorp" v-model="filter.order_type" type="checkbox" value="6"><label for="searchcorp">Корпоративный</label><br>
                        <input id="searchparteryi" v-model="filter.order_type" type="checkbox" value="4"><label for="searchparteryi">Партнеры исходящий</label><br>
                        <input id="searchparteryv" v-model="filter.order_type" type="checkbox" value="5"><label for="searchparteryv">Партнеры входящий</label><br>
                        <input id="searchakciya" v-model="filter.akciya" type="checkbox" value="1"><label for="searchakciya">Акция</label><br>
                    </div>

                    <div style="float:right">
                        <input id="searchozon" v-model="filter.order_type" type="checkbox" value="13"><label for="searchozon">Ozon</label><br>
                        <input id="searchinternetdis" v-model="filter.order_type" type="checkbox" value="7"><label for="searchinternetdis">Диспетчер</label><br>
                        <input id="searchinternet" v-model="filter.order_type" type="checkbox" value="1"><label for="searchinternet">Интернет-магазин</label><br>
                        <input id="searchmobileapp" v-model="filter.order_type" type="checkbox" value="14"><label for="searchozon">Мобильное приложение</label><br>
                    </div>

                    <div style="float:right;margin-right: 20px;">
                        <input id="searchdelivery" v-model="filter.order_type" type="checkbox" value="8"><label for="searchdelivery">Деливери</label><br>
                        <input id="searchbroniboy" v-model="filter.order_type" type="checkbox" value="11"><label for="searchbroniboy">Бронибой</label><br>
                        <input id="searchfloristru" v-model="filter.order_type" type="checkbox" value="10"><label for="searchfloristru">Флорист.ру</label><br>
                        <input id="searchtea" v-model="filter.order_type" type="checkbox" value="12"><label for="searchtea">TEA.RU</label><br>
                        <input id="searchyandexmarket" v-model="filter.order_type" type="checkbox" value="9"><label for="searchyandexmarket">Яндекс Маркет</label><br>
                    </div>
                    <div style="clear:right"></div>
                </div>

                <div class="bort" style="margin-top:15px;width:230px;float:left">
                    <span class="span">Тип отчета в Excel</span>
                    <div>
                        <input v-model="filter.report_type" id="exelexport1" name="exelexport" type="radio" value="1"><label for="exelexport1">Общий</label><br>
                    </div>
                    <div>
                        <input v-model="filter.report_type" id="exelexport2" name="exelexport" type="radio" value="6"><label for="exelexport2">Поступление заказов</label><br>
                    </div>
                    <div>
                        <input v-model="filter.report_type" id="exelexport3" name="exelexport" type="radio" value="2"><label for="exelexport3">Точки</label><br>
                    </div>
                    <div>
                        <input v-model="filter.report_type" id="exelexport4" name="exelexport" type="radio" value="5"><label for="exelexport4">Оплата</label>
                    </div>
                </div>

                <div class="bort" style="margin-top:15px;width:235px;float:right">
                    <span class="span">Доставка / Самовывоз</span>
                    <div>
                        <input v-model="filter.searchdos" id="searchdos" value="0" name="searchdos" type="checkbox"><label for="searchdos">Доставка</label>
                        <input v-model="filter.searchdos" id="searchsam" value="1" name="searchsam" type="checkbox"><label for="searchsam">Самовывоз</label>
                    </div>
                </div>

                <div class="exelsearch">
                    <img @click="exelExport()" src="/icons/exel.png" width="24" height="24">
                </div>

                <div>
                    <input @click="clearFilter()" type="button" class="buttsearch cancelAllSearch" name="cancelFilter" value="Сбросить">
                    <input @click="saveFilter()" type="button" class="buttsearch submitAllSearch" name="submitAllSearch" value="Найти">
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderSearchModal",
        data: function () {
            return {
                filter: {
                    payment_type: this.$cookies.get("orderFilter_payment_type") ? this.$cookies.get("orderFilter_payment_type") : 0,
                    order_source: this.$cookies.get("orderFilter_order_source") ? this.$cookies.get("orderFilter_order_source") : 1,
                    city: this.$cookies.get("orderFilter_city") ? this.$cookies.get("orderFilter_city") : 0,
                    brand: this.$cookies.get("orderFilter_brand") ? this.$cookies.get("orderFilter_brand") : 0,
                    close_disp: this.$cookies.get("orderFilter_close_disp") ? this.$cookies.get("orderFilter_close_disp") : 0,
                    add_disp: this.$cookies.get("orderFilter_add_disp") ? this.$cookies.get("orderFilter_add_disp") : 0,
                    point: this.$cookies.get("orderFilter_point") ? this.$cookies.get("orderFilter_point") : 0,
                    status: this.$cookies.get("orderFilter_status") ? this.$cookies.get("orderFilter_status") : 3,
                    driver_type: this.$cookies.get("orderFilter_driver_type") ? this.$cookies.get("orderFilter_driver_type") : 0,
                    from_office: this.$cookies.get("orderFilter_from_office") ? this.$cookies.get("orderFilter_from_office") : [],
                    date_start: this.$cookies.get("orderFilter_date_start") ? this.$cookies.get("orderFilter_date_start") : null,
                    search: this.$cookies.get("orderFilter_search") ? this.$cookies.get("orderFilter_search") : '',
                    date_end: this.$cookies.get("orderFilter_date_end") ? this.$cookies.get("orderFilter_date_end") : null,
                    summa_start: this.$cookies.get("orderFilter_summa_start") ? this.$cookies.get("orderFilter_summa_start") : '',
                    driver_summa_start: this.$cookies.get("orderFilter_driver_summa_start") ? this.$cookies.get("orderFilter_driver_summa_start") : '',
                    summa_end: this.$cookies.get("orderFilter_summa_end") ? this.$cookies.get("orderFilter_summa_end") : '',
                    driver_summa_end: this.$cookies.get("orderFilter_driver_summa_end") ? this.$cookies.get("orderFilter_driver_summa_end") : '',
                    discard_number: this.$cookies.get("orderFilter_discard_number") ? this.$cookies.get("orderFilter_discard_number") : '',
                    discard: this.$cookies.get("orderFilter_discard") ? this.$cookies.get("orderFilter_discard") : false,
                    express: this.$cookies.get("orderFilter_express") ? this.$cookies.get("orderFilter_express") : false,
                    akciya: this.$cookies.get("orderFilter_akciya") ? this.$cookies.get("orderFilter_akciya") : false,
                    searchdos: this.$cookies.get("orderFilter_searchdos") ? this.$cookies.get("orderFilter_searchdos") : [],
                    order_type: this.$cookies.get("orderFilter_order_type") ? this.$cookies.get("orderFilter_order_type") : [],
                    report_type: this.$cookies.get("orderFilter_report_type") ? this.$cookies.get("orderFilter_report_type") : 1,
                },
                payment_types: [],
                order_sources: [],
                cities: [],
                points: [],
                driver_types: [],
                users: [],
                brands: [],
                modalLoaded: false,
            };
        },
        created() {
            this.getSearchModal()
        },
        mounted() {
            $('.window-overlay').on('click', function (e) {
                let classArray = ['#formallsearch'];
                let domelement = e.target;
                let result = false;
                $.each(classArray, function(index, value){
                    if ($(domelement).closest(''+value).length > 0 ) {
                        result = true;
                    }
                });

                if(result === false) {
                    $('.window-overlay').hide();
                    $('body').css('overflow', 'auto')
                }
            });

            $(document).on('keydown', function(e){
                if(e.keyCode === 27){
                    e.preventDefault();
                    if($('.window-overlay').is(':visible')) {
                        $('.window-overlay').hide();
                        $('body').css('overflow', 'auto')
                    }
                }
            });
            this.$root.$on('clear-form', () => {
                this.clearFilter()
            });
        },
        methods: {
            hideSearchModal() {
                $('.window-overlay').hide();
                $('body').css('overflow', 'auto')
            },
            getSearchModal() {
                axios.post('/api/order/getOrdersSearchData')
                    .then( res => {
                        // console.log(res);
                        this.payment_types = res.data.payment_types;
                        this.order_sources = res.data.order_sources;
                        this.cities = res.data.cities;
                        this.brands = res.data.brands;
                        this.users = res.data.users;
                        this.points = res.data.points;
                        this.driver_types = res.data.driver_types;
                        this.modalLoaded = true;
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
            saveFilter() {
                if(this.filter.payment_type != 0) {
                    this.$cookies.set("orderFilter_payment_type", this.filter.payment_type);
                } else {
                    this.$cookies.remove("orderFilter_payment_type");
                }
                if(this.filter.order_source != 1) {
                    this.$cookies.set("orderFilter_order_source", this.filter.order_source);
                } else {
                    this.$cookies.remove("orderFilter_order_source");
                }
                if(this.filter.city != 0) {
                    this.$cookies.set("orderFilter_city", this.filter.city);
                } else {
                    this.$cookies.remove("orderFilter_city");
                }
                if(this.filter.brand != 0) {
                    this.$cookies.set("orderFilter_brand", this.filter.brand);
                } else {
                    this.$cookies.remove("orderFilter_brand");
                }
                if(this.filter.close_disp != 0) {
                    this.$cookies.set("orderFilter_close_disp", this.filter.close_disp);
                } else {
                    this.$cookies.remove("orderFilter_close_disp");
                }
                if(this.filter.add_disp != 0) {
                    this.$cookies.set("orderFilter_add_disp", this.filter.add_disp);
                } else {
                    this.$cookies.remove("orderFilter_add_disp");
                }
                if(this.filter.point != 0) {
                    this.$cookies.set("orderFilter_point", this.filter.point);
                } else {
                    this.$cookies.remove("orderFilter_point");
                }
                if(this.filter.status != 3) {
                    this.$cookies.set("orderFilter_status", this.filter.status);
                } else {
                    this.$cookies.remove("orderFilter_status");
                }
                if(this.filter.driver_type != 0) {
                    this.$cookies.set("orderFilter_driver_type", this.filter.driver_type);
                } else {
                    this.$cookies.remove("orderFilter_driver_type");
                }
                if(this.filter.from_office.length > 0) {
                    this.$cookies.set("orderFilter_from_office", JSON.stringify(this.filter.from_office));
                } else {
                    this.$cookies.remove("orderFilter_from_office");
                }
                if(this.filter.date_start != null) {
                    this.$cookies.set("orderFilter_date_start", this.filter.date_start);
                } else {
                    this.$cookies.remove("orderFilter_date_start");
                }
                if(this.filter.date_end != null) {
                    this.$cookies.set("orderFilter_date_end", this.filter.date_end);
                } else {
                    this.$cookies.remove("orderFilter_date_end");
                }
                if(this.filter.search != '') {
                    this.$cookies.set("orderFilter_search", this.filter.search);
                } else {
                    this.$cookies.remove("orderFilter_search");
                }
                if(this.filter.summa_start != '') {
                    this.$cookies.set("orderFilter_summa_start", this.filter.summa_start);
                } else {
                    this.$cookies.remove("orderFilter_summa_start");
                }
                if(this.filter.driver_summa_start != '') {
                    this.$cookies.set("orderFilter_driver_summa_start", this.filter.driver_summa_start);
                } else {
                    this.$cookies.remove("orderFilter_driver_summa_start");
                }
                if(this.filter.summa_end != '') {
                    this.$cookies.set("orderFilter_summa_end", this.filter.summa_end);
                } else {
                    this.$cookies.remove("orderFilter_summa_end");
                }
                if(this.filter.driver_summa_end != '') {
                    this.$cookies.set("orderFilter_driver_summa_end", this.filter.driver_summa_end);
                } else {
                    this.$cookies.remove("orderFilter_driver_summa_end");
                }
                if(this.filter.discard_number != '') {
                    this.$cookies.set("orderFilter_discard_number", this.filter.discard_number);
                } else {
                    this.$cookies.remove("orderFilter_discard_number");
                }
                if(this.filter.discard != false) {
                    this.$cookies.set("orderFilter_discard", this.filter.discard);
                } else {
                    this.$cookies.remove("orderFilter_discard");
                }
                if(this.filter.akciya != false) {
                    this.$cookies.set("orderFilter_akciya", this.filter.akciya);
                } else {
                    this.$cookies.remove("orderFilter_akciya");
                }
                if(this.filter.express != false) {
                    this.$cookies.set("orderFilter_express", this.filter.express);
                } else {
                    this.$cookies.remove("orderFilter_express");
                }
                if(this.filter.searchdos.length > 0) {
                    this.$cookies.set("orderFilter_searchdos", JSON.stringify(this.filter.searchdos));
                } else {
                    this.$cookies.remove("orderFilter_searchdos");
                }
                if(this.filter.order_type.length > 0) {
                    this.$cookies.set("orderFilter_order_type", JSON.stringify(this.filter.order_type));
                } else {
                    this.$cookies.remove("orderFilter_order_type");
                }
                if(this.filter.report_type != 1) {
                    this.$cookies.set("orderFilter_report_type", this.filter.report_type);
                } else {
                    this.$cookies.remove("orderFilter_report_type");
                }

                $('.window-overlay').hide();
                $('body').css('overflow', 'auto');
                this.$emit('component_method');
            },
            clearFilter() {
                this.filter.payment_type = 0;
                this.filter.order_source = 1;
                this.filter.city = 0;
                this.filter.brand = 0;
                this.filter.close_disp = 0;
                this.filter.add_disp = 0;
                this.filter.point = 0;
                this.filter.status = 0;
                this.filter.driver_type = 0;
                this.filter.from_office = [];
                this.filter.date_start = null;
                this.filter.search = '';
                this.filter.date_end = null;
                this.filter.summa_start = '';
                this.filter.driver_summa_start = '';
                this.filter.summa_end = '';
                this.filter.driver_summa_end = '';
                this.filter.discard_number = '';
                this.filter.discard = false;
                this.filter.akciya = false;
                this.filter.express = false;
                this.filter.searchdos = [];
                this.filter.order_type = [];
                this.filter.report_type = 1;
                $('.window-overlay').hide();
                $('body').css('overflow', 'auto');
                this.$emit('component_method', true);
            },
            exelExport() {
                $('.window-overlay').hide();
                $('body').css('overflow', 'auto');
            }
        }
    }
</script>

<style scoped>
    .searchall {
        top: 5%;
        position: absolute;
        margin-left: auto;
        margin-right: auto;
        left: 0;
        right: 0;
        text-align: center;
        background-color: #f3f3f3;
        border: 1px solid #a6b7c7;
        box-shadow: 0 0 50px rgb(50 50 50 / 80%);
        display: inline;
        padding: 15px;
        width: 532px;
    }
    #closeall {
        color: #cd2143;
        cursor: pointer;
        float: right;
        font-size: 20px;
        font-weight: 900;
        margin: -5px;
        text-shadow: 0 1px 0 #fff, 0 -1px 0 rgb(0 0 0 / 20%);
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .bigtitle {
        color: #b4cbdc;
        font-size: 21px;
        font-weight: bold;
        margin-bottom: 15px;
        text-align: center;
        text-shadow: 0 1px 0 #fff, 0 -1px 0 rgb(0 0 0 / 20%);
        text-transform: uppercase;
    }
    .form_date {
        display: flex;
        margin-bottom: 5px;
    }
    .form_date_label {
        width: 10%;
        line-height: 24px;
        text-align: left;
    }
    .hide_line .sel_date_search {
        width: 50px;
    }
    #formallsearch input[type=checkbox], #formallsearch input[type=radio] {
        margin: auto;
    }
    .express-check-wrap {
        margin: 10px 0 0 11px;
        float: left;
        display: flex;
    }
    .express-check-wrap label {
        margin-left: 5px;
    }
    .bortxt {
        font-size: 16px;
        padding: 10px;
        width: 100%;
    }
    .exelsearch {
        cursor: pointer;
        float: left;
        margin: 60px -28px 0 -28px;
    }
    .buttsearch {
        float: right;
        height: 32px;
        margin-top: 10px;
        width: 99px;
    }
    .submitAllSearch {
        width: 99px;
        margin-right: 10px;
    }
    .money {
        float: left;
        height: 25px;
        margin-right: 3px;
        padding: 2px;
        width: 175px;
    }
    .summa {
        height: 25px;
        margin-left: 3px;
        padding: 2px;
        text-align: right;
        width: 50px;
    }
    .discard {
        margin-left: 10px;
        height: 25px;
        padding: 2px;
        text-align: right;
        width: 100px;
    }
    .moneyform {
        height: 25px;
        padding: 2px;
        width: 160px;
    }
    .status {
        height: 25px;
        padding: 2px;
        width: 108px;
    }
    .searchtochka {
        height: 25px;
        margin-right: 3px;
        padding: 2px;
        width: 150px;
    }
</style>
