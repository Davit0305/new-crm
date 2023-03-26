<template>
    <div id="allformsearch">
        <div class="groupbort inline">
            <span class="title">Заказчик</span>
            <div class="bort">
                <span class="span">Имя</span>
                <input v-model="order.name" class="bort_textinput" type="text">
            </div>
            <div class="bort">
                <span class="span">Телефон</span>
                <input v-model="order.phone" class="phoneIn ui-autocomplete-input" type="text" autocomplete="off">
                <div class="insms">
                    <input v-model="order.sms" id="sms" name="sms" type="checkbox" value="1">
                </div>
                <div class="lasms">
                    <label for="sms">SMS</label>
                </div>
                <div class="country_block">
                    <input v-model="order.client_country" type="radio" id="ctr0" value="0"><label for="ctr0">Россия</label>
                    <input v-model="order.client_country" type="radio" id="ctr1" value="1"><label for="ctr1">Другая</label>
                </div>
            </div>
            <div class="bort">
                <span class="span">Email</span>
                <input v-model="order.email" class="email" type="text">
            </div>
            <div class="bort">
                <span class="span">Пол</span>
                <div class="gorizontal_checkboxes">
                    <input v-model="order.client_gender" type="radio" id="cgen1" value="1"><label for="cgen1">Мужчина</label>
                    <input v-model="order.client_gender" type="radio" id="cgen0" value="0"><label for="cgen0">Женщина</label>
                </div>
            </div>
            <div v-if="order.phone.length == 11" class="bort hashtags">
                <span class="span">Социальный портрет</span>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="1" id="hashtag_1">
                <label for="hashtag_1">Экономный</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="2" id="hashtag_2">
                <label for="hashtag_2">Щедрый</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="3" id="hashtag_3">
                <label for="hashtag_3">Умничает</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="4" id="hashtag_4">
                <label for="hashtag_4">Буйный</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="5" id="hashtag_5">
                <label for="hashtag_5">Дерзкий</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="6" id="hashtag_6">
                <label for="hashtag_6">Вежлив</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="7" id="hashtag_7">
                <label for="hashtag_7">Странный</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="8" id="hashtag_8">
                <label for="hashtag_8">Заторможен</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="9" id="hashtag_9">
                <label for="hashtag_9">Занудливый</label>
                <input v-model="order.clientrate.hashtags" type="checkbox" name="clientrate[hashtag]" value="10" id="hashtag_10">
                <label for="hashtag_10">Мошенник</label>
            </div>
            <div v-if="order.phone.length == 11" class="bort">
                <span class="span">Ваше описание</span>
                <textarea  v-model="order.clientrate.comment"></textarea>
            </div>
            <div class="bort statuses">
                <span class="span">Статус</span>
                <div class="gorizontal_checkboxes">
                    <input v-model="order.client_status_vip" type="checkbox" id="vip"><label for="vip">VIP</label>
                    <input v-model="order.client_status_corp" type="checkbox" id="corp"><label for="corp">Корпоративный</label>
                    <input v-model="order.client_status_partner" type="checkbox" id="partner"><label for="partner">Партнёр</label>
                </div>
            </div>
        </div>

        <div class="groupbort inline" style="margin-left: 2%;">
            <span class="title">Получатель</span>
            <div class="bort">
                <span class="span">Имя</span>
                <input v-model="order.polname" class="bort_textinput" type="text">
            </div>
            <div class="bort">
                <span class="span">Телефон</span>
                <input v-model="order.polphone" class="phone" type="text">
            </div>
            <div class="bort">
                <span class="span">Город</span>
                <input v-model="order.otkuda" class="bort_textinput" type="text">
            </div>
            <div class="bort">
                <span class="span">Адрес</span>
                <input v-model="order.adres" class="bort_textinput" type="text">
            </div>
            <div class="bort">
                <span class="span">Пол</span>
                <div class="gorizontal_checkboxes">
                    <input v-model="order.recipient_gender" type="radio" name="recipient[gender]" id="rgen1" value="1"><label for="rgen1">Мужчина</label>
                    <input v-model="order.recipient_gender" type="radio" name="recipient[gender]" id="rgen0" value="0"><label for="rgen0">Женщина</label>
                </div>
            </div>
            <div class="bort">
                <span class="span">Родство</span>
                <select v-model="order.degree_id">
                    <option :value="1">Не указано</option>
                    <option :value="2">Мама</option>
                    <option :value="3">Супруга</option>
                    <option :value="4">Девушка</option>
                    <option :value="5">Сестра</option>
                    <option :value="6">Бабушка</option>
                    <option :value="7">Дочь</option>
                    <option :value="8">Племянница</option>
                    <option :value="9">Тётя</option>
                    <option :value="10">Друг</option>
                    <option :value="11">Коллега</option>
                    <option :value="12">Отец</option>
                    <option :value="13">Брат</option>
                    <option :value="14">Сын</option>
                    <option :value="15">Дед</option>
                    <option :value="16">Дядя</option>
                </select>
            </div>
            <div class="bort">
                <span class="span">Событие</span>
                <select v-model="order.giftevent_id">
                    <option :value="1">Не указано</option>
                    <option :value="2">Встреча</option>
                    <option :value="3">День рождения</option>
                    <option :value="4">Юбилей</option>
                    <option :value="5">Праздник календаря</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderClientInfoTemplate",
        props: {
            o: Object
        },
        data: function () {
            return {
                order: this.o,
            };
        },
        created() {
            this.order.clientrate = this.order.clientrate ? this.order.clientrate : {hashtags : [], comment: ''};
        },
    }
</script>

<style scoped>
    .inline {
        display: inline-block;
    }
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
    .phoneIn {
        float: left;
        height: 25px;
        padding: 2px;
        text-align: center;
        width: 100px;
    }
    .insms {
        float: left;
        margin-left: 5px;
        margin-top: 6px;
    }
    .lasms {
        float: left;
        margin-left: 0px;
        margin-top: 7px;
        font-size: 9px;
    }
    .country_block {
        display: inline-block;
        margin-left: 1px;
        width: 74px;
    }
    .country_block label {
        float: none;
    }
    .nameprod, .email {
        height: 19px;
        margin-right: 5px;
        padding: 2px;
        width: 238px;
    }
    .hashtags input[type=checkbox]:checked + label {
        background-image: none;
        outline: 0;
        -webkit-box-shadow: inset 0 2px 4px rgb(0 0 0 / 15%), 0 1px 2px rgb(0 0 0 / 5%);
        -moz-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
        box-shadow: inset 0 2px 4px rgb(0 0 0 / 15%), 0 1px 2px rgb(0 0 0 / 5%);
        background-color: #e0e0e0;
    }
    .hashtags input[type=checkbox] {
        display: none;
    }
    .hashtags input[type=checkbox] + label {
        display: inline-block;
        margin: -2px;
        padding: 0px 6px;
        margin-bottom: 0;
        font-size: 10px;
        line-height: 20px;
        color: #333;
        text-align: center;
        text-shadow: 0 1px 1px rgb(255 255 255 / 75%);
        vertical-align: middle;
        cursor: pointer;
        background-color: #f5f5f5;
        background-image: -moz-linear-gradient(top,#fff,#e6e6e6);
        background-image: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));
        background-image: -webkit-linear-gradient(top,#fff,#e6e6e6);
        background-image: -o-linear-gradient(top,#fff,#e6e6e6);
        background-image: linear-gradient(to bottom,#fff,#e6e6e6);
        background-repeat: repeat-x;
        border: 1px solid #ccc;
        border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
        border-bottom-color: #b3b3b3;
        filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
        -webkit-box-shadow: inset 0 1px 0 rgb(255 255 255 / 20%), 0 1px 2px rgb(0 0 0 / 5%);
        -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
        box-shadow: inset 0 1px 0 rgb(255 255 255 / 20%), 0 1px 2px rgb(0 0 0 / 5%);
    }
    .hashtags label {
        float: none;
    }
    .groupbort .bort textarea {
        width: 235px;
        padding-left: 10px;
    }
    .groupbort .bort select {
        width: 124px;
        height: 24px;
    }
</style>
