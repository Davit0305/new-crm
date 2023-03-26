<template>
    <div>
        <div id="header">
            <div class="login section-to-print">
                <form class="mainform" method="POST">
                    <div id="dataform">
                        <div class="form_date">
                            <date-picker
                                v-model="filter.date_start"
                                format="DD.MM.YYYY HH:mm"
                                type="datetime"
                                lang="ru"
                                value-type="DD.MM.YYYY HH:mm"
                            />
                        </div>
                        <div class="form_date">
                            <date-picker
                                v-model="filter.date_end"
                                format="DD.MM.YYYY HH:mm"
                                type="datetime"
                                lang="ru"
                                value-type="DD.MM.YYYY HH:mm"
                            />
                        </div>
                    </div>
                    <div v-if="issetFilter === true">
                        <div @click="getSearchModal()" id="allsearch" class="active">Расширенный поиск</div>
                        <div @click="clearFilter()" class="allSearchActiveClose">×</div>
                    </div>
                    <div v-else @click="getSearchModal()" id="allsearch">Расширенный поиск</div>
                    <label for="number">Найти заказ</label>
                    <input v-model="filter.search" type="text" id="number" size="10" class="text searchFilter">
                    <button id="ok_button" @click="addOrder()" type="button" class="button ok_button">
                        <span id="ok_button_text">Добавить</span>
                        <img id="ok_button_img" src="/images/processing.gif">
                    </button>
                    <input @click="saveFilter()" type="button" class="button findFilter" value="Найти">
                </form>
            </div>
        </div>

        <div style="text-align:center">
            <a href="javascript:;" @click="getMapModal()">Карта курьеров</a>
        </div>

        <div class="id_user">
            <div v-if="ordersLoaded === false" id="preloader"></div>
            <table v-else id="maintable" border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                <tr>
                    <td style="border:none; font-size:12px; position: relative;" colspan="13">
                        <div style="margin: -15px 0px -15px 0px;">
                            <span class="countOrders">{{ sortedOrders.length }} из {{ orders.length }} заказа</span>
                            <span v-if="pageSize > 1" class="pagelinks">
                                <span>|</span>
                                Страницы:
                                <a v-for="pageNumber in pageSize" @click="currentPage = pageNumber" :class="currentPage == pageNumber ? 'pageLink active' : 'pageLink'">{{ pageNumber }}</a>
                            </span>
                            <span class="pageSize">заказов на странице
                                <select v-model="countPage">
                                    <option :selected="countPage === 50" value="50">50</option>
                                    <option :selected="countPage === 70" value="70">70</option>
                                    <option :selected="countPage === 100" value="100">100</option>
                                </select>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr class="maintr">
                    <td class="tnum" style="min-width:75px"><h3>№</h3></td>
                    <td style="min-width:26px;"><div class="vert">б р е н д</div></td>
                    <td class="tname">
                        <div v-if="this.$cookies.get('orderFilter_soon')">
                            <div class="where_all" @click="changeSoonFilter(false)" id="where_all">Показать все заказы</div>
                            <div class="where_all_text">Показаны заказы до {{soonDate}}</div>
                        </div>
                        <div v-else class="where_all" @click="changeSoonFilter(true)" id="where_all_not">Показать ближайшие доставки</div>
                        <h3>Имя заказчика / Адрес доставки </h3>
                    </td>
                    <td style="min-width:115px;"><h3>Дата и время доставки </h3></td>
                    <td class="tsms"><div class="vert">к о н т а к т ы</div></td>
                    <td class="tstat"><div class="vert">о п л а т а</div></td>
                    <td class="tstat"><div class="vert">с т а т у с</div></td>
                    <td class="tstoch"><div class="vert">ф о т о</div></td>
                    <td class="tstoch smsStatus"><div class="vert">С М С</div></td>
                    <td class="tkur courierColumnTitle multiSet" style="min-width:261px;">
                        <h3>Курьер (Торговая точка)</h3>
                    </td>
<!--                    <td class="tsave"></td>-->
                </tr>

                <tr v-for="(order, index) in sortedOrders" :key="index" class="orderTr" :class="order.status === 1 ? 'zav' : (index === 0 || index % 2 === 0 ? 'one' : 'two')">
                    <td class="history" :class="numberClass(order)" @click="openOrderModal(order.number, 'comment', index)">
                        <input v-if="order.dobavil_api" type="button" class="newAutoOrder" value="Взять">
                        <div v-else style="position:relative">
                            <div v-if="order.comments_count > 0" class="wait">
                                <img style="cursor:pointer;" class="history-icon" src="/icons/wath.png" width="13" height="13">
                                <span class="wait-title">Количество комментариев: {{order.comments_count}}</span>
                            </div>
                            <div v-else class="wait">
                                <img style="cursor:pointer;" class="history-icon" src="/icons/add.png" width="13" height="13">
                                <span class="wait-title">Добавить комментарий</span>
                            </div>
                            <div class="wait">
                                <label class="history-label">{{ order.number }}</label>
                                <span class="wait-title">
                                    <strong>Добавлено:</strong>
                                    <i class="lastModifed">{{order.date_adding}}</i>
                                    <br>Диспетчер: {{order.user_add.fio}}
                                    <span v-if="order.user_edit">
                                        <br><br>
                                        <strong>Последнее изменение:</strong>
                                        <i class="lastModifed">{{order.date_edit}}</i>
                                        <br>Диспетчер: {{order.user_edit.fio}}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="brand" @click="openOrderModal(order.number, 'order', index)">{{ order.brand_short_name }}</td>
                    <td class="client-info">
                        <span v-if="order.polphone != '' || order.phone != ''" class="copyNumberClick">
                            <span class="phoneimg">☎</span>
                            <div class="popupNumber cssall">
                                <div v-if="order.driver_phone" class="call allowCall doNotCloseNumberOnClick todriver">
                                    Курьеру<br>
                                    <span class="phone doNotCloseNumberOnClick">{{order.driver_phone}}</span>
                                </div>
                                <div v-if="order.tochka_phone" class="call allowCall doNotCloseNumberOnClick todriver">
                                    На точку<br>
                                    <span class="phone doNotCloseNumberOnClick">{{order.tochka_phone}}</span>
                                </div>
                            </div>
                        </span>
                        <label class="namelabel" @click="openOrderModal(order.number, 'client', index)">
                            <span class="clientNameLabel">{{ order.name }}</span>
                            <span class="incognitoLabel">{{ order.inkognito == 1 ? ' (Инкогнито) ' : ''}}</span>
                            <span v-if="order.adres != ''">/</span>
                            <span class="addressLabel">{{ order.adres }}</span>
                            <span class="intspan">{{ order.internet }}</span>
                            <span class="imlabel">
                                <div v-if="order.type_name" class="wait">{{ order.type_short_name }}
                                    <span class="wait-title">{{ order.type_name }}</span>
                                </div>
                                <div v-if="order.foto === 1" class="wait">
                                    <img class="fotolabel" src="/icons/fotopol.png">
                                    <span class="wait-title">Необходимо фото получателя</span>
                                </div>
                            </span>
                        </label>
                    </td>
                    <td class="order-date" @click="openOrderModal(order.number, 'driver', index)" :class="dateClass(order)">
                        <div class="datalabel">
                            <span class="dataDateLabel">{{ order.data }}</span>
                            <span class="dataTimeLabel">
                                {{ order.time_zero }}
                                <div class='labvr_sec'>
                                    <div class='wait' v-if="order.soon === 1">
                                        Б<span class="wait-title">Ближайшее время</span>
                                    </div>
                                    <div class='wait' v-if="order.express === 1">
                                        Э<span class="wait-title">Экспресс заказ</span>
                                    </div>
                                </div>
                                <div class='labvr' v-if="order.time_zone !== 0">
                                    <div class='wait'>
                                        +{{order.time_zone}}<span class="wait-title">По местному времени {{order.time_with_zone}}</span>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </td>
                    <td class="tsave" @click="openOrderModal(order.number, 'client', index)">
                        <div class="wait">
                            <img v-if="order.phone" src="/icons/phone.png" width="24" height="24">
                            <img v-else src="/icons/no_phone.png" width="24" height="24">
                            <span class="wait-title">
                                <span class="showClientLabel">
                                    <span class="phoneLabel">Заказчик: {{order.phone ? order.phone : '-'}} / </span>
                                    {{order.sms === 1 ? 'смс' : '-'}}
                                    <span class="phoneLabel"> / {{order.email ? order.email : '-'}}</span>
                                </span>
                                <br>
                                <span class="showPolLabel">
                                    <span class="polNameLabel">Получатель: {{order.polname ? order.polname : '-'}} / </span>
                                    <span class="polPhoneLabel">{{order.polphone ? order.polphone : '-'}}</span>
                                </span>
                            </span>
                        </div>
                    </td>
                    <td class="tsave" @click="openOrderModal(order.number, 'payment', index)">
                        <div class="wait">
                            <img v-if="
                                order.summa == 0 &&
                                order.money == 0 &&
                                order.summa == 0 &&
                                order.istochnik_zakaza == 1 &&
                                order.cheizakaz == 0" src="/icons/no_coins.png" width="24" height="24">
                            <img v-else-if="
                                (order.summa == 0 || order.money == 0 || order.summa == 0 || order.istochnik_zakaza == 1 || order.cheizakaz == 0)
                                || ((order.money == 999 || order.money == 8 || order.money_2 == 999 || order.money_2 == 8) && order.vip == 1)
                                || ((order.money == 501 || order.money_2 == 501) && order.bank == 0)" src="/icons/no_ist_coins.png" width="24" height="24">
                            <img v-else src="/icons/coins.png" width="24" height="24">
                            <div v-if="order.discount_card" class="discount-card-wrap">д</div>
                            <span class="wait-title">
                                <span class="paymentLabel">Способ оплаты: {{order.payment_name}}</span>
                                <span v-if="order.summa" class="sum_monLabel"><br>Всего оплачено: {{order.summa}} руб.</span>
                                <span v-if="order.discount_card" class="sum_monLabel"><br>Дисконтная карта: {{order.discount_card}}</span>
                            </span>
                        </div>
                    </td>
                    <td class="tsave" @click="openOrderModal(order.number, 'order', index)">
                        <div class="wait">
                            <img v-if="order.status === 3" src="/icons/akt.png" width="24" height="24">
                            <img v-else-if="order.status === 1" src="/icons/vyp.png" width="24" height="24">
                            <img v-else src="/icons/otm.png" width="24" height="24">

                            <span v-if="order.status === 3" class="wait-title">Активен</span>
                            <span v-else-if="order.status === 1" class="wait-title">Выполнен</span>
                            <span v-else class="wait-title">Отменен</span>
                        </div>
                    </td>
                    <td class="tsave" @click="openOrderModal(order.number, 'photo', index)">
                        <div class="wait">
                            <img v-if="order.photo_count > 0" src="/icons/camera.png" width="24" height="24">
                            <img v-else src="/icons/no_camera.png" width="24" height="24">
                            <span v-if="order.photo_count > 0" class="wait-title">{{order.photo_count}} фото</span>
                            <span v-else class="wait-title">Фото отсутствует</span>
                        </div>
                    </td>
                    <td class="smsStatus normal" @click="openOrderModal(order.number, 'sms', index)">
                        {{order.sms_count > 0 ? 'sms' : '-'}}
                    </td>
                    <td class="courier" :class="driverClass(order)">
                        <div @click="openOrderModal(order.number, 'driver', index)">
                            <div v-if="order.tocka_id !== 0 || order.driver_id !== 0" class="waits">
                                <img style="cursor:pointer;" class="imgcomm commentexists" src="/icons/wath.png" width="13" height="13">
                                <div class="courier-info">
                                    <div v-if="order.driver_id !== 0" class="driverSpanLabel ">
                                        Выполнял: <span class="typeDriverLabel">{{order.type_driver_name}}</span><br>
                                        Сумма за доставку:  <span class="driverSumLabel">{{order.driver_summ}}</span> руб.<br>
                                        Телефон: <span class="driverPhoneLabel">{{order.driver_phone ? order.driver_phone : '-'}}</span><br>
                                        Автомобиль: <span class="driverAvtoLabel">{{order.driver_avto ? order.driver_avto : '-'}}</span><br>
                                        Гос.номер: <span class="driverStateNumberLabel">{{order.driver_nomer ? order.driver_nomer : '-'}}</span><br><br>
                                    </div>
                                    <div v-if="order.tocka_id !== 0" class="tochkaSpanLabel ">
                                        Торговая точка: <span class="pointNameLabel">{{order.tochka_name ? order.tochka_name : '-'}} {{order.brand_short_name ? '[' + order.brand_short_name + ']' : ''}}</span><br>
                                        Телефон: <span class="pointPhoneLabel">{{order.tochka_phone ? order.tochka_phone : '-'}}</span><br>
                                        Адрес: <span class="pointAddressLabel">{{order.tochka_adres ? order.tochka_adres : '-'}}</span><br>
                                    </div>
                                </div>
                            </div>
                            <label class="driverlabel">
                                <span v-if="order.driver" class="driverNameLabel" :style="order.type_driver === 2 ? 'color:#cd2143' : ''">{{ order.driver }}</span>
                                <span v-if="order.samo === 1" class="driverNameLabel">Самовывоз</span>
                                <marquee v-if="order.show_in_app === 1" class="waitingCourier driverNameLabel " behavior="alternate" scrollamount="2">В ожидании курьера...</marquee>
                                <span v-if="order.tochka_name" class="tochkaLabel" :class="order.show_in_app === 1 ? 'w-100' : ''">({{order.tochka_name ? order.tochka_name : ''}} {{order.brand_short_name ? '[' + order.brand_short_name + ']' : ''}})</span>
                            </label>
                        </div>
                        <span v-if="order.driver_phone || order.tochka_phone" class="copyNumberClick">
                            <span class="phoneimg">☎</span>
                            <div class="popupNumber cssall">
                                <div v-if="order.driver_phone" class="call allowCall doNotCloseNumberOnClick todriver">
                                    Курьеру<br>
                                    <span class="phone doNotCloseNumberOnClick">{{order.driver_phone}}</span>
                                </div>
                                <div v-if="order.tochka_phone" class="call allowCall doNotCloseNumberOnClick todriver">
                                    На точку<br>
                                    <span class="phone doNotCloseNumberOnClick">{{order.tochka_phone}}</span>
                                </div>
                            </div>
                        </span>
                    </td>
<!--                    <td class="tsave">-->
<!--                        <i class="fa fa-print" aria-hidden="true"></i>-->
<!--                    </td>-->
                </tr>
                </tbody>
            </table>
        </div>

        <component @component_method="component_method" :is="used_component" :data="component_data"></component>
    </div>
</template>

<script>
    import OrderSearchModal from "../modals/OrderSearchModal";
    import OrderOnceModal from "../modals/OrderOnceModal";
    import OrderMapModal from "../modals/OrderMapModal";
    export default {
        name: "Orders",
        components: {
            OrderSearchModal,
            OrderMapModal,
            OrderOnceModal
        },
        data: function () {
            return {
                filter: {
                    date_start: this.$cookies.get("orderFilter_date_start") ? this.$cookies.get("orderFilter_date_start") : null,
                    search: this.$cookies.get("orderFilter_search") ? this.$cookies.get("orderFilter_search") : '',
                    date_end: this.$cookies.get("orderFilter_date_end") ? this.$cookies.get("orderFilter_date_end") : null,
                },
                used_component: '',
                component_method: '',
                component_data: '',
                orders: [],
                pageSize: 1,
                countPage: 50,
                ordersLoaded: false,
                issetFilter: false,
                currentPage: 1
            };
        },
        created() {
            this.getOrders();
            this.countPage = this.$cookies.get("countPage") ? this.$cookies.get("countPage") : 50;
            this.currentPage = this.$cookies.get("currentPage") ? this.$cookies.get("currentPage") : 1;
        },
        mounted() {
            this.$root.$on('clear-used-component', () => {
                this.used_component = '';
                this.component_data = '';
                this.component_method = '';
            });
            this.$root.$on('update-order-once', (data) => {
                // console.log(data);
                this.orders[data.index] = data.order;
                //// без этого костыля массив заказов не перерисовывается
                this.orders.push({}); ////
                this.orders.pop(); ///////
                //////////////////////////
            });
            $(document).on('click', '.copyNumberClick', function () {
                $('.popupNumber').hide();
                $(this).find('.popupNumber').show();
            });
            $(document).on('click', function (e) {
                let classArray = ['copyNumberClick'];
                let domelement = e.target;
                let result = false;
                $.each(classArray, function(index, value){
                    if ($(domelement).closest('.'+value).length > 0 ) {
                        result = true;
                    }
                });

                if(result === false) {
                    $('.popupNumber').hide()
                }
            });
        },
        computed:{
            sortedOrders() {
                if(this.ordersLoaded === true) {
                    this.pageSize = Math.ceil(this.orders.length / this.countPage);
                    if(this.currentPage > this.pageSize) {
                        this.currentPage = 1;
                    }
                    return this.orders.filter((row, index) => {
                        let start = (this.currentPage-1)*this.countPage;
                        let end = this.currentPage*this.countPage;
                        if(index >= start && index < end) return true;
                    });
                } else {
                    return this.orders;
                }
            },
            soonDate() {
                let today = new Date();
                today.setHours(today.getHours() + 6);
                let month = (today.getMonth() + 1) < 10 ? '0' + (today.getMonth() + 1) : (today.getMonth() + 1);
                let day = today.getDate() < 10 ? '0' + today.getDate() : today.getDate();
                let hours = today.getHours() < 10 ? '0' + today.getHours() : today.getHours();
                let minutes = today.getMinutes() < 10 ? '0' + today.getMinutes() : today.getMinutes();
                let date = day + '.' + month + '.' + today.getFullYear();
                let time = hours + ':' + minutes;
                return time + ' ' + date;
            }
        },
        watch: {
            'countPage': function (newVal, oldVal) {
                this.$cookies.set("countPage", newVal)
            },
            'currentPage': function (newVal, oldVal) {
                this.$cookies.set("currentPage", newVal)
            }
        },
        methods: {
            getMapModal() {
                this.used_component = 'OrderMapModal';
                this.component_method = '';
                $('.window-overlay').show();
                $('body').css('overflow', 'hidden')
            },
            driverClass(order) {
                let driver_class = '';
                if ( order.driver.length === 0 && order.samo === 0 ) {
                    driver_class = 'vch';
                } else if( order.driver.length > 0 && order.tocka_id === 0 ) {
                    driver_class = 'par';
                } else if( order.samo === 1 && order.tocka_id !== 0 ) {
                    driver_class = 'samo';
                }
                return driver_class;
            },
            numberClass(order) {
                let current_date = new Date();
                current_date.setHours(0,0,0,0);
                let fbr_d = new Date(current_date.getFullYear(), 1, 14);
                let date2 = 0;
                if(order.data) {
                    let order_date = order.data.split('.');
                    date2 = new Date(order_date[2], order_date[1] - 1, order_date[0]).getTime();
                }
                let number_class = ''; // Все обычные дни
                if ( fbr_d === date2 && order.status === 3) {
                    number_class = 'feb'; //  14 feb
                } else if( current_date.getTime() === date2 && order.status === 3) {
                    number_class = 'sei'; //  Текущий день
                } else if( current_date.getTime() > date2 && order.status === 3) {
                    number_class = 'vch'; // Вчерашний день
                }
                return number_class;
            },
            dateClass(order) {
                let date_class = '';
                let current_date = new Date();
                let date2 = 0;
                if(order.data) {
                    let order_date = order.data.split('.');
                    date2 = new Date(order_date[2], order_date[1] - 1, order_date[0]).getTime();
                }
                let tm = current_date.toLocaleTimeString('en-US', { hour12: false, hour: "numeric", minute: "numeric", second: "numeric"});
                current_date.setHours(0,0,0,0)

                if( order.express === 1 ) {
                    date_class = 'exp'; // Экспресс
                } else if(current_date.getTime() === date2 && tm > order.time && order.time !== "" && order.status === 3) {
                    date_class = 'vch'; // Сегодня поздно
                } else if(current_date.getTime() > date2 && order.status === 3) {
                    date_class = 'vch'; // Вчерашний
                } else if(current_date.getTime() === date2 && order.time === "" && order.status === 3) {
                    date_class = 'zav'; // Сегодня без времени
                } else if(current_date.getTime() === date2 && tm < order.time && order.time !== "" && order.status === 3) {
                    date_class = 'sei'; // Сегодня есть время
                }
                return date_class;
            },
            openOrderModal(order_id, tab, index) {
                this.used_component = 'OrderOnceModal';
                this.component_data = {order_id: order_id, tab: tab, index: index};
                $('.window-overlay').show();
                $('body').css('overflow', 'hidden');
            },
            changeSoonFilter(type) {
                if(type) {
                    this.$cookies.set("orderFilter_soon", 1);
                } else {
                    this.$cookies.remove("orderFilter_soon");
                }
                this.getOrders();
            },
            getSearchModal() {
                this.used_component = 'OrderSearchModal';
                this.component_method = (clear) => this.getOrders(clear);
                $('.window-overlay').show();
                $('body').css('overflow', 'hidden')
            },
            addOrder() {
                $('#ok_button_text').hide();
                $('#ok_button_img').show();
                $('#ok_button').attr('disabled', true);
                axios.post('/api/order/addOrder')
                    .then( res => {
                        console.log(res);
                        this.orders.unshift(res.data);
                        this.currentPage = 1;
                        $('#ok_button_text').show();
                        $('#ok_button_img').hide();
                        $('#ok_button').removeAttr('disabled');
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
                    })
            },
            getOrders(clear = false) {
                if(clear === true) {
                    this.clearFilterCookie();
                    this.issetFilter = false;
                    this.filter.date_start = null;
                    this.filter.date_end = null;
                    this.filter.search = '';
                } else {
                    let issetFilter = this.checkFilter();
                    this.filter.date_start = this.$cookies.get("orderFilter_date_start") ? this.$cookies.get("orderFilter_date_start") : null;
                    this.filter.search = this.$cookies.get("orderFilter_search") ? this.$cookies.get("orderFilter_search") : '';
                    this.filter.date_end = this.$cookies.get("orderFilter_date_end") ? this.$cookies.get("orderFilter_date_end") : null;
                    this.issetFilter = issetFilter === 1;
                }
                this.ordersLoaded = false;
                axios.post('/api/order/getOrders')
                    .then( res => {
                        // console.log(res);
                        this.orders = res.data;
                        this.ordersLoaded = true;
                        this.used_component = '';
                        this.component_data = '';
                        this.component_method = '';
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
                    })
            },
            clearFilterCookie() {
                this.$cookies.remove("orderFilter_payment_type");
                this.$cookies.remove("orderFilter_order_source");
                this.$cookies.remove("orderFilter_city");
                this.$cookies.remove("orderFilter_brand");
                this.$cookies.remove("orderFilter_close_disp");
                this.$cookies.remove("orderFilter_add_disp");
                this.$cookies.remove("orderFilter_point");
                this.$cookies.remove("orderFilter_status");
                this.$cookies.remove("orderFilter_driver_type");
                this.$cookies.remove("orderFilter_from_office");
                this.$cookies.remove("orderFilter_date_start");
                this.$cookies.remove("orderFilter_date_end");
                this.$cookies.remove("orderFilter_search");
                this.$cookies.remove("orderFilter_akciya");
                this.$cookies.remove("orderFilter_summa_start");
                this.$cookies.remove("orderFilter_driver_summa_start");
                this.$cookies.remove("orderFilter_summa_end");
                this.$cookies.remove("orderFilter_driver_summa_end");
                this.$cookies.remove("orderFilter_discard_number");
                this.$cookies.remove("orderFilter_discard");
                this.$cookies.remove("orderFilter_express");
                this.$cookies.remove("orderFilter_searchdos");
                this.$cookies.remove("orderFilter_order_type");
                this.$cookies.remove("orderFilter_report_type");
            },
            clearFilter() {
                this.getOrders(true);
                this.$root.$emit('clear-form');
            },
            checkFilter() {
                let payment_type = this.$cookies.get("orderFilter_payment_type");
                let order_source = this.$cookies.get("orderFilter_order_source");
                let city = this.$cookies.get("orderFilter_city");
                let brand = this.$cookies.get("orderFilter_brand");
                let close_disp = this.$cookies.get("orderFilter_close_disp");
                let add_disp = this.$cookies.get("orderFilter_add_disp");
                let point = this.$cookies.get("orderFilter_point");
                let status = this.$cookies.get("orderFilter_status");
                let driver_type = this.$cookies.get("orderFilter_driver_type");
                let from_office = this.$cookies.get("orderFilter_from_office");
                let date_start = this.$cookies.get("orderFilter_date_start");
                let date_end = this.$cookies.get("orderFilter_date_end");
                let search = this.$cookies.get("orderFilter_search");
                let akciya = this.$cookies.get("orderFilter_akciya");
                let summa_start = this.$cookies.get("orderFilter_summa_start");
                let driver_summa_start = this.$cookies.get("orderFilter_driver_summa_start");
                let summa_end = this.$cookies.get("orderFilter_summa_end");
                let driver_summa_end = this.$cookies.get("orderFilter_driver_summa_end");
                let discard_number = this.$cookies.get("orderFilter_discard_number");
                let discard = this.$cookies.get("orderFilter_discard");
                let express = this.$cookies.get("orderFilter_express");
                let searchdos = this.$cookies.get("orderFilter_searchdos");
                let order_type = this.$cookies.get("orderFilter_order_type");
                let report_type = this.$cookies.get("orderFilter_report_type");
                if(
                    payment_type ||
                    order_source ||
                    city ||
                    brand ||
                    close_disp ||
                    add_disp ||
                    point ||
                    status ||
                    akciya ||
                    driver_type ||
                    from_office ||
                    date_start ||
                    date_end ||
                    search ||
                    summa_start ||
                    driver_summa_start ||
                    summa_end ||
                    driver_summa_end ||
                    discard_number ||
                    discard ||
                    express ||
                    searchdos ||
                    order_type ||
                    report_type )
                {
                    return 1;
                } else {
                    return 0;
                }
            },
            saveFilter() {
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
                this.used_component = '';
                this.getOrders();
            },
        }
    }
</script>

<style scoped>
    .newAutoOrder {
        width: 100%;
        height: 20px;
        background: #d6bd0e;
        z-index: 100;
        cursor: pointer;
        border-radius: 5px;
        border: none;
        color: #f3f3f3;
    }
    .form_date {
        height: 25px;
        padding: 2px;
    }
    .mx-datepicker {
        width: 135px;
    }
    .datacheck-input i {
        position: absolute;
        right: 7px;
        top: -1px;
        font-size: 14px;
    }
    .tstat {
        color: #333;
        font-size: 9px;
        width: 24px;
    }
    .tstoch {
        min-width: 24px;
        width: 24px;
    }
    td.smsStatus {
        width: 25px;
        font-size: 9px;
        text-align: center;
        vertical-align: middle;
        position: relative;
        cursor: pointer;
    }
    #dataform {
        float: left;
        margin: -12px -9px;
        display: flex;
    }
    .sel_date_search {
        height: 25px;
        padding: 2px;
        width: 43px;
        margin-right: 4px;
    }
    input[type="text"] {
        border: 1px solid #a6b7c7;
    }
    select {
        border: 1px solid #a6b7c7;
    }
    .tnum {
        width: 80px;
    }
    .tsms {
        width: 24px;
    }
    .where_all {
        position: absolute;
        font-size: 9px;
        text-transform: uppercase;
        background: #D2ECD2;
        border: 1px solid #a6b7c7;
        padding: 4px 5px 2px 5px;
        margin: 16px 10px;
        cursor: pointer;
    }
    .where_all_text {
        color: #333;
        position: absolute;
        margin-top: 43px;
        margin-left: 10px;
        text-transform: uppercase;
    }
    .multiSetCurier {
        width: 100%;
        position: relative;
        top: -15px;
        color: #69737b;
        text-align: center;
        transition: color ease .2s, top ease .2s;
    }
    .tname {
        width: 50%;
    }
</style>
