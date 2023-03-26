<template>
    <div class="window-overlay">
        <div class="hide_line searchall" id="formallsearch">
            <div @click="hideSearchModal()" id="closeall">×</div>
            <div class="bigtitle">Карта курьеров</div>
            <div id="dynamic-component-demo" class="demo">
                <div id="map" style="width: 100%; height: 100%"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderMapModal",
        data: function () {
            return {
                driverMap: {
                    yaMap: null,
                    cars: [],
                    time: [],
                    phones: []
                }
            };
        },
        created() {
            this.initMap();
        },
        methods: {
            hideSearchModal() {
                $('.window-overlay').hide();
                $('body').css('overflow', 'auto');
                this.$root.$emit('clear-used-component');
            },
            initMap() {
                var d = $.Deferred();

                ymaps.ready(() => {
                    this.init();
                    this.driverMap.yaMap.controls.remove('geolocationControl');
                    this.driverMap.yaMap.controls.remove('searchControl');
                    this.driverMap.yaMap.controls.remove('fullscreenControl');
                    d.resolve();
                });

                return d.promise();
            },
            init() {
                this.driverMap.yaMap = new ymaps.Map("map", {
                    center: [55.795051,49.082452],
                    zoom: 11
                });

                // setInterval(function () {
                //     if ( typeof window.driverMap.yaMap !== 'undefined' )
                //     {
                //         for ( key in window.driverMap.time)
                //         {
                //             var time = window.driverMap.time[key];
                //             var current_time = new Date().getTime();
                //             var period = (current_time - time)/1000;
                //             console.log(period)
                //             if(period > 120 && period < 600) {
                //                 var balloonContentBody = window.driverMap.cars[key].properties.get('balloonContentBody');
                //                 window.driverMap.cars[key].options.set('preset', 'islands#grayCircleDotIconWithCaption');
                //                 // получаем объект даты последнего появления в сети
                //                 var driverLastOnlineDate = new Date();
                //                 driverLastOnlineDate.setTime(time);
                //                 // выводим во всплывающим сообщении дату и время последнего появления в сети
                //                 var newBalloonContentBody = balloonContentBody.replace(
                //                     /\<span class='lastSeen'\>(.)*\<\/span\>/g,
                //                     "<span class='lastSeen'><br> Был в сети : " + dateFormat(driverLastOnlineDate) + "</span>"
                //                 );
                //                 window.driverMap.cars[key].properties.set('balloonContentBody', newBalloonContentBody);
                //             } else if(period > 600) {
                //                 window.driverMap.yaMap.geoObjects.remove(window.driverMap.cars[key]);
                //                 delete(window.driverMap.cars[key]);
                //                 delete(window.driverMap.drivers[key]);
                //                 delete(window.driverMap.time[key]);
                //             }
                //         }
                //     }
                // }, 10000)
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
        width: 90%;
        height: 80%;
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
    .demo {
        width: 100%;
        height: 95%;
    }
</style>
