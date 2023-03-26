<template>
    <div id="order_payment_info">
        <div class="groupbort inline">
            <span class="title">Информация о заказе</span>
            <div class="bort" style="margin-top:10px;">
                <span class="span">Тип источника / Номер из источнка</span>
                <select v-model="order.type" class="moneyform">
                    <option :value="0">Не определено</option>
                    <option :value="1">Интернет заказ</option>
                    <option :value="2">Золотой номер</option>
                    <option :value="3">Мессенджер</option>
                    <option :value="4">Партнерский исходящий</option>
                    <option :value="5">Партнерский входящий</option>
                    <option :value="6">Корпоративный заказ</option>
                    <option :value="7">Диспетчера (Интернет)</option>
                    <option :value="8">Деливери</option>
                    <option :value="9">Яндекс Маркет</option>
                    <option :value="10">Флорист.ру</option>
                    <option :value="11">Бронибой</option>
                    <option :value="12">Ozon</option>
                    <option :value="13">Мобильное приложение</option>
                </select>
                <input v-model="order.internet" class="moneyform" style="width: 80px;" type="text">
            </div>
            <div class="bortSost">
                <span class="span">Общий состав заказа</span>
                <div class="bortSostB">
                    <div>
                        <input type="text" name="sosBT" value="1" placeholder="шт">
                        <label>Букет</label>
                    </div>
                    <div>
                        <input type="text" name="sosKA" value="" placeholder="шт">
                        <label>Корзина</label>
                    </div>
                    <div>
                        <input type="text" name="sosKY" value="" placeholder="шт">
                        <label>Композиция</label>
                    </div>
                    <div>
                        <input type="text" name="sosIA" value="" placeholder="шт">
                        <label>Игрушка</label>
                    </div>
                </div>
                <div class="bortSostB">
                    <div>
                        <input type="text" name="sosBA" value="" placeholder="шт">
                        <label>Бирка</label>
                    </div>
                    <div>
                        <input type="text" name="sosOA" value="" placeholder="шт">
                        <label>Открытка</label>
                    </div>
                    <div>
                        <input type="text" name="sosKI" value="" placeholder="шт">
                        <label>Конфеты</label>
                    </div>
                    <div>
                        <input type="text" name="sosTE" value="" placeholder="шт">
                        <input type="text" name="sosTEXT" value="" class="bigInp" maxlength="15" placeholder="Указать ещё">
                    </div>
                </div>
                <div style="clear:left"></div>
            </div>
            <div class="bort">
                <span class="span">Текст открытки</span>
                <textarea v-model="order.tekstotkrytki" class="client_textotkrytki" name="tekstotkrytki" placeholder="Не указано"></textarea>
            </div>
            <div class="bort">
                <span class="span">Комментарий покупателя</span>
                <textarea v-model="order.comment" class="client_textotkrytki" name="tekstotkrytki" placeholder="Не указано"></textarea>
            </div>
        </div>

        <div class="groupbort inline" style="margin-left: 2%;">
            <span class="title">Статус</span>
            <div class="bort">
                <span class="span">Бренд</span>
                <div class="typeDriver">
                    <div @click="order.brand_id = 1" :class="order.brand_id === 1 ? 'typeDriverVariant shtat selected' : 'typeDriverVariant shtat'">Букет Столицы</div>
                    <div @click="order.brand_id = 2" :class="order.brand_id === 2 ? 'typeDriverVariant vneshtat selected' : 'typeDriverVariant vneshtat'">Пион</div>
                    <div @click="order.brand_id = 3" :class="order.brand_id === 3 ? 'typeDriverVariant vneshtat selected' : 'typeDriverVariant vneshtat'">Клумба</div>
                    <div @click="order.brand_id = 4" :class="order.brand_id === 4 ? 'typeDriverVariant vneshtat selected' : 'typeDriverVariant vneshtat'">Цветобаза</div>
                    <div @click="order.brand_id = 5" :class="order.brand_id === 5 ? 'typeDriverVariant vneshtat selected' : 'typeDriverVariant vneshtat'">Флора Парк</div>
                </div>
            </div>
            <div class="bort_red">
                <span class="span">Как клиент узнал о компании?</span>
                <select v-model="order.istochnik_zakaza" class="moneyform">
                    <option :value="1">Не определен</option>
                    <option :value="2">Постоянный клиент</option>
                    <option :value="4">Реклама на магазине</option>
                    <option :value="5">Реклама на автомобиле</option>
                    <option :value="6">Друзья, знакомые</option>
                    <option :value="7">Справочники</option>
                    <option :value="8">Партнеры</option>
                    <option :value="9">Сотрудники</option>
                    <option :value="11">Телевидение</option>
                    <option :value="12">Журналы</option>
                    <option :value="15">Звонок с сайта</option>
                    <option :value="16">ДубльГис (2Гис)</option>
                    <option :value="17">Поисковая система «Google»</option>
                    <option :value="18">Поисковая система «Яндекс»</option>
                    <option :value="21">Радио (Европа +)</option>
                    <option :value="22">Рассылка МТС</option>
                    <option :value="24">Реклама в почте «Maйл.ру»</option>
                    <option :value="25">Социальная сеть «Instagram»</option>
                    <option :value="26">Социальная сеть «ВКонтакте»</option>
                    <option :value="33">Работает рядом</option>
                    <option :value="34">Живет рядом</option>
                    <option :value="35">Коммерческое предложение</option>
                </select>
            </div>
            <div class="bort">
                <span class="span">Статус заказа</span>
                <select v-model="order.status" style="width:90px;" class="moneyform">
                    <option :value="3">Активен</option>
                    <option :value="1">Выполнен</option>
                    <option :value="2">Отменен</option>
                </select>
            </div>
            <div class="bort_red">
                <span class="span">Кем принят заказ</span>
                <div class="gorizontal_checkboxes">
                    <input v-model="order.cheizakaz" id="cheizakaz1" name="cheizakaz" type="radio" value="1">
                    <label for="cheizakaz1">Офис</label>
                    <input v-model="order.cheizakaz" id="cheizakaz2" name="cheizakaz" type="radio" value="2">
                    <label for="cheizakaz2">Точка</label>
                </div>
            </div>
            <div class="bort driver_done_container">
                <span class="span">Выполнен курьером</span>
                <label class="driver_done disabled">
                    <input v-model="order.driver_done" style="margin-right: 5px;" type="checkbox" value="1">
                    Да
                </label>
            </div>
            <div v-if="order.status === 2" class="bort_red">
                <span class="span">Причина отмены</span>
                <select v-model="order.cancelreason_id" class="fullLengthSelect_pay">
                    <option :value="1" selected="">Не указано</option>
                    <option :value="2">Передумал</option>
                    <option :value="3">Игрался на сайте</option>
                    <option :value="4">Не устроила замена</option>
                    <option :value="5">Дубль заказа</option>
                    <option :value="6">Мошенник</option>
                    <option :value="7">Другое</option>
                </select>
                <textarea v-model="order.cancelreason_comment" class="fullLengthTextarea_pay" placeholder="Опишите причину отмены"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderInfoTemplate",
        props: {
            o: Object
        },
        data: function () {
            return {
                order: this.o,
            };
        },
    }
</script>

<style scoped>
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
    #order_payment_info {
        display: flex;
    }
    .client_textotkrytki {
        width: 98%;
        min-height: 45px;
    }
    .fullLengthSelect_pay {
        padding: 2px;
        height: 25px;
        width: 164px;
    }
    .fullLengthTextarea_pay {
        width: 98%;
        margin-top: 7px;
        padding: 2px 6px;
        min-height: 45px;
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
</style>
