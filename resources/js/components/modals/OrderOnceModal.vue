<template>
    <div class="window-overlay">
        <div class="hide_line searchall" id="formallsearch">
            <div @click="hideSearchModal()" id="closeall">×</div>
            <div class="bigtitle">Заказ № {{ order_id }}</div>
            <div id="dynamic-component-demo" class="demo">
                <div class="text-start">
                    <button
                        type="button"
                        v-for="tab in tabs"
                        v-bind:key="tab.key"
                        v-bind:class="['tab-button', { active: currentTab === tab.key }]"
                        v-on:click="currentTab = tab.key"
                    >
                        {{ tab.name }}
                    </button>
                </div>
                <div class="tab">
                    <div v-if="modalLoaded === false" id="preloader"></div>
                    <component v-else v-bind="{o: order}" :is="currentTabComponent"></component>
                    <button @click="printOrder()" type="button" class="button ok_button float-start">
                        Печать
                    </button>
                    <div class="tab-bottons">
                        <button type="button" class="button ok_button danger">
                            Удалить
                        </button>
                        <button @click="saveOrder()" style="margin-left: 10px;" type="button" class="button ok_button">
                            <div id="save_order_button_text">Сохранить</div>
                            <img id="save_order_button_img" src="/images/processing.gif">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import OrderClientInfoTemplate from "../templates/OrderClientInfoTemplate";
    import OrderDriverInfoTemplate from "../templates/OrderDriverInfoTemplate";
    import OrderPaymentInfoTemplate from "../templates/OrderPaymentInfoTemplate";
    import OrderCommentsTemplate from "../templates/OrderCommentsTemplate";
    import OrderPhotoTemplate from "../templates/OrderPhotoTemplate";
    import OrderInfoTemplate from "../templates/OrderInfoTemplate";
    import OrderSmsTemplate from "../templates/OrderSmsTemplate";
    export default {
        name: "OrderOnceModal",
        props: {
            data: Object
        },
        components: {
            OrderClientInfoTemplate,
            OrderPaymentInfoTemplate,
            OrderCommentsTemplate,
            OrderPhotoTemplate,
            OrderInfoTemplate,
            OrderSmsTemplate,
            OrderDriverInfoTemplate
        },
        data: function () {
            return {
                order_id: this.data.order_id,
                order: null,
                modalLoaded: false,
                currentTab: this.data.tab,
                index: this.data.index,
                tabs: [
                    {name: "Заказ", component: "OrderInfoTemplate", key: "order"},
                    {name: "Клиент", component: "OrderClientInfoTemplate", key: "client"},
                    {name: "Оплата", component: "OrderPaymentInfoTemplate", key: "payment"},
                    {name: "Доставка", component: "OrderDriverInfoTemplate", key: "driver"},
                    {name: "СМС", component: "OrderSmsTemplate", key: "sms"},
                    {name: "Фотографии", component: "OrderPhotoTemplate", key: "photo"},
                    {name: "Комментарии", component: "OrderCommentsTemplate", key: "comment"},
                ]
            };
        },
        created() {
            this.getOrder();
        },
        mounted() {
            $('.window-overlay').on('click', (e) => {
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
                    $('body').css('overflow', 'auto');
                    this.$root.$emit('clear-used-component');
                }
            });

            $(document).on('keydown', (e) => {
                if(e.keyCode === 27){
                    e.preventDefault();
                    if($('.window-overlay').is(':visible')) {
                        $('.window-overlay').hide();
                        $('body').css('overflow', 'auto');
                        this.$root.$emit('clear-used-component');
                    }
                }
            });
        },
        computed: {
            currentTabComponent: function() {
                let obj = this.tabs.find(o => o.key === this.currentTab);
                if(this.currentTab === 'photo' || this.currentTab === 'comment') {
                    $('.tab-bottons').hide()
                } else {
                    $('.tab-bottons').show()
                }
                return obj.component;
            }
        },
        methods: {
            printOrder() {
                window.print();
            },
            hideSearchModal() {
                $('.window-overlay').hide();
                $('body').css('overflow', 'auto');
                this.$root.$emit('clear-used-component');
            },
            getOrder() {
                axios.post('/api/order/getOrderOnce/' + this.order_id)
                    .then( res => {
                        // console.log(res);
                        this.order = res.data;
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
                    })
            },
            saveOrder() {
                $('#save_order_button_text').hide();
                $('#save_order_button_img').show();
                axios.post('/api/order/saveOrder', {order: this.order})
                    .then( res => {
                        console.log(res);
                        $('#save_order_button_text').show();
                        $('#save_order_button_img').hide();
                        $('.window-overlay').hide();
                        $('body').css('overflow', 'auto');
                        window.swal.fire({
                            title: 'Успешно!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000,
                            customClass: {
                                container: 'z-index-max',
                            }
                        })
                        this.$root.$emit('update-order-once', {index: this.index, order: res.data});
                        this.$root.$emit('clear-used-component');
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
        }
    }
</script>

<style scoped>
    .tab-bottons {
        display: flex;
        float: right;
    }
    .demo {
        width: 100%;
    }
    .tab-button {
        padding: 6px 10px;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        border: 1px solid #ccc;
        cursor: pointer;
        margin-bottom: -2px;
        margin-right: -1px;
        background: #e0e0e0;
    }
    .tab-button:hover {
        background: #bdbbbb;
    }
    .tab-button.active {
        background: #f3f3f3;
        border-bottom: #f3f3f3;
    }
    .tab {
        border: 1px solid #ccc;
        padding: 20px 10px 0 10px;
    }

    #allformsearch {
        display: flex;
        justify-content: center;
    }
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
        width: max-content;
        min-width: 630px;
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
    #formallsearch input[type=checkbox], #formallsearch input[type=radio] {
        margin: auto;
    }
    .express-check-wrap label {
        margin-left: 5px;
    }
</style>
