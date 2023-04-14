<template>
    <div class="adminBlocks">
        <div class="menu">
            <ul class="head-name">
                <li class="active">
                    <router-link to="/settings" title="Настройки системы">
                        <a href="javascript:void(0)">Тарифная сетка курьеров</a>
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <router-link to="/admin" title="Переместить заказы в архив">
                        <a href="javascript:void(0)">Администрирование</a>
                    </router-link>
                </li>
                <li class="devide">·</li>
                <li>Тарифная сетка курьеров</li>
            </ul>
        </div>
        <div class="body">

            <form id="courier_price">

                <div id="container">
                    <div class="bort" style="margin-top:10px;">
                        <span>Минимальное расстояние</span>
                            до<input id="min_km" v-model="min_distance" data-type="min_distance" class="distance" type="number" name="min_distance"
                                 placeholder="км.">
                        сумма<input class="distance_summa" type="number" name="min_km_price" v-model="min_price"
                                    placeholder="руб.">
                    </div>
                    <div id="items_container" v-for="(item, index) in items" v-if="index >= 3">
                        <div class="bort" data-id="19" style="margin-top:10px;">
                            <span>Промежуточное расстояние</span>
                            от<input class="distance at" :class="{ 'error': hasError }" v-on:click="checkDistance($event,'min_km',item, index)" data-type="at_km" type="number" name="at[]" v-model="item.min_distance"
                                     placeholder="км.">
                            до<input class="distance before" :class="{ 'error': hasError }" v-on:click="checkDistance($event,'max_km',item, index)" data-type="before_km" type="number" name="before[]"
                                     v-model="item.max_distance" placeholder="км.">
                            сумма<input class="distance_summa" :class="{ 'error': hasError }"  type="number" name="price[]" v-model="item.price"
                                        placeholder="руб.">
                            <a class="remove" @click="removeItem(item)">Удалить</a>
                        </div>
                    </div>
                    <div class="bort" style="margin-top:10px;">
                        <span>Максимальное расстояние</span>
                        от<input id="max_km" data-type="max_distance" class="distance" type="number" name="max_distance" v-model="max_distance"
                                 placeholder="км.">
                        сумма<input class="distance_summa" type="number" name="max_km_price" v-model="max_price"
                                    placeholder="руб.">
                        цена за 1 км<input class="distance_summa" type="number" name="max_km_once" v-model="once_km"
                                           placeholder="руб.">
                    </div>
                </div>
                <div style="display: inline-block;">
                    <button type="button" id="add_new_item" @click="addNewItem()" class="button">Добавить пункт</button>
                </div>
                <div class="actionLine">
                    <input type="button" name="submit" @click="saveCourierPrices(items)" value="Сохранить"
                           class="greenButton">
                </div>

            </form>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            min_distance: 0,
            max_distance: 0,
            max_price: 0,
            min_price: 0,
            items:{
                min_distance: 0,
                max_distance: 0,
                price: 0,
            },
            nextId: 1,
            once_km:'',
            hasError: false, // Set the initial state to false
        };
    },
    created() {
        this.getCourierPrices();
    },

    methods: {
        checkDistance(event,type,item,index) {

            let prevIndex = this.items.findIndex(i => i === item) - 1;
            let all_count = this.items.length;
            console.log(this.items[prevIndex],'  ',prevIndex,'  ',all_count )
            if (prevIndex >= 0) {
                let prevItem = prevIndex;
            }
            let min_km = this.min_distance;
            let max_km = this.max_distance;
            var all_km = [];
            var result = true;

            if(type == 'min_km') {
                if(max_km != 0 && max_km <= item.min_distance) {
                    result = false;
                }
                if(index < all_count && this.items[prevIndex]['type'] == 'interval' &&
                    item.min_distance != this.items[prevIndex].max_distance){
                    result = false;
                }
                if(index < all_count && this.items[prevIndex]['type'] !== 'interval' &&
                    item.min_distance != min_km){
                    result = false;
                }

            } else if(type == 'max_km') {
                if(min_km != 0 && min_km >= item.max_distance) {
                    result = false;
                }
                if(index + 1 < all_count) {
                    if (index + 1 < all_count && this.items[index + 1]['type'] == 'interval' &&
                        item.max_distance != this.items[index + 1].min_distance) {
                        result = false;
                    }

                }else{
                    if (item.max_distance != max_km) {
                        result = false;
                        console.log(item.max_distance,max_km);
                    }
                }
            } else {
                if(type == 'min_km') {
                    if(min_km !== 0 && min_km > item.min_distance) {
                        result = false;
                    }
                    if(max_km !== 0 && max_km <= item.min_distance) {
                        result = false;
                    }
                    if(index < all_count && this.items[prevIndex]['type'] == 'interval' &&
                        item.min_distance < this.items[prevIndex].max_distance){
                        result = false;
                    }
                } else {
                    if(min_km != 0 && min_km >=  item.min_distance) {
                        result = false;
                    }
                    if(max_km != 0 && max_km <  item.max_distance) {
                        result = false;
                    }
                }
            }
            if(!result) {
                event.target.classList.toggle('error', true);
            } else {
                event.target.classList.toggle('error', false);
            }
        },
        addNewItem() {
            this.items.push({
                type: 'interval',
                min_distance: '',
                max_distance: '',
                price: '',

            });
            this.id++;
        },
        removeItem(item) {
            const index = this.items.indexOf(item);
            if (index > -1) {
                this.items.splice(index, 1);
            }
        },
        saveCourierPrices(items) {
            let error = false;
            // Loop through all input fields
            let inputs = document.querySelectorAll('input.error');
            if (inputs.length > 0) {
                window.swal.fire({
                    title: 'Произошла ошибка!',
                    text: '',
                    icon: 'error',
                    customClass: {
                        container: 'z-index-max',
                    },
                });
                return;
            }

            axios.post('/api/couriers/save',
                {
                    items: items,
                    min_distance: this.min_distance,
                    max_distance: this.max_distance,
                    max_price: this.max_price,
                    min_price: this.min_price,
                    once_km: this.once_km,
                }
            )
                .then(res => {
                    console.log(res);
                    window.swal.fire({
                        title: 'Успешно Сохранено!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000,
                        customClass: {
                            container: 'z-index-max'
                        }
                    });

                })
                .catch(err => {
                    window.swal.fire({
                        title: 'Произошла ошибка!',
                        // text: err.response.data.message,
                        icon: 'error',
                        customClass: {
                            container: 'z-index-max',
                        }
                    })
                })
        },

        getCourierPrices() {
            axios.get('/api/couriers')
                .then(res => {
                    console.log(res);
                    let data = res.data;
                    this.items = data;
                    for (let i = 0; i < data.length; i++) {
                        if (data[i]['type'] == 'max_km') {
                            this.max_distance = data[i]['max_distance'];
                            this.max_price = data[i]['price'];
                        } else if (data[i]['type'] == 'min_km') {
                            this.min_distance = data[i]['min_distance'];
                            this.min_price = data[i]['price'];
                        } else if (data[i]['type'] == 'once_km') {
                            this.once_km = data[i]['price'];
                        }
                    }

                })
                .catch(err => {
                    window.swal.fire({
                        title: 'Произошла ошибка!',
                        // text: err.response.data.message,
                        icon: 'error',
                        customClass: {
                            container: 'z-index-max',
                        }
                    })
                })
        },
    }
};
</script>

<style scoped>
.menu ul li.active {
    background: #eff5fd;
}

.menu {
    width: 100%;
    background: #e3ecf7;
}

.head-name {
    display: flex;
    justify-content: center;
}

ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
}

.menu ul li {
    display: inline-block;
    padding: 0 15px;
}

.menu ul li a {
    line-height: 50px;
    display: inline-block;
    font-size: 14px;
    text-decoration: none;
}

.menu ul li.active a {
    color: #689ab7;
}

body .cont {
    margin: 0 !important;
    width: 100% !important;
    padding: 0 !important;
    display: block !important;
    max-width: 100% !important;
}

.breadcrumbs {
    width: 100%;
}

.breadcrumbs ul {
    max-width: 900px;
    margin: 0 auto;
    padding: 15px 0;
    list-style: none;
}

.breadcrumbs ul li {
    display: inline-block;
}

.breadcrumbs ul li.devide {
    margin: 0 10px;
}

.actionLine {
    text-align: center;
    padding: 20px 0;
}

.greenButton {
    display: inline-block;
    padding: 0 20px;
    line-height: 32px;
    border: none;
    background: #58c174;
    color: #fff;
    text-decoration: none;
    cursor: pointer;
    transition: background ease .2s;
}

.body {
    padding-top: 20px;
    max-width: 900px;
}

#add_new_item {
    width: 112px;
    border-radius: unset;
    border: none;
    background-color: #88d1eb;
    color: #fafafa;
    font: 11px/14px Verdana, Arial, sans-serif;
}

.bort {
    border: 1px solid #a6b7c7;
    padding: 10px 5px 5px 5px;
}

.distance {
    height: 19px;
    margin-left: 3px;
    padding: 2px;
    text-align: center;
    width: 50px;
}

.distance_summa {
    height: 19px;
    margin-left: 3px;
    padding: 2px;
    text-align: center;
    width: 50px;
}
.distance.error{
    border: 1px solid #f50d0d !important;
}
.remove {
    text-decoration: none;
    float: right;
    margin-top: 5px;
    margin-right: 10px;
    cursor: pointer;
}

.bort span {
    background: #f3f3f3;
    float: left;
    margin-top: -20px;
    padding: 1px;
}
</style>
