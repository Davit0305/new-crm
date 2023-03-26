<template>
    <div class="window-overlay">
        <div class="hide_line searchall" id="formallsearch">
            <div @click="hideSearchModal()" id="closeall">×</div>
            <div class="bigtitle">Расширенный поиск</div>
            <div v-if="modalLoaded === false" id="preloader"></div>
            <form v-else>
                <div class="bort">
                    <span>Искать по слову</span>
                    <input v-model="filter.search" type="text" class="bortxt" size="10" placeholder="Кого ищем?">
                </div>
                <div class="bort" style="margin-top:15px;">
                    <span>Статус сотрудника</span>
                    <div style="padding:10px;">
                        <div style="float:left;margin-left:5px;">
                            <input v-model="filter.enabled" type="radio" id="enabled_1" name="enabled" value="1" checked="">
                            <label for="enabled_1">Все сотрудники</label>
                        </div>
                        <div style="float:left;margin-left:15px;">
                            <input v-model="filter.enabled" type="radio" id="enabled_2" name="enabled" value="2">
                            <label for="enabled_2">Работающие</label>
                        </div>
                        <div style="float:left;margin-left:15px;">
                            <input v-model="filter.enabled" type="radio" id="enabled_3" name="enabled" value="3">
                            <label for="enabled_3">Уволенные</label>
                        </div>
                        <div style="clear:left"></div>
                    </div>
                </div>
                <div class="bort text-start" style="margin-top:15px;">
                    <span>Должность / Отдел</span>
                    <select v-model="filter.job" class="moneyform" style="width:174px;" name="appointmentSearch">
                        <option :value="0">Выбрать</option>
                        <option v-for="job in jobs" :value="job.bs_id">{{ job.name }}</option>
                    </select>
                    <select v-model="filter.dep" class="moneyform" style="width:174px;margin-left: 5px;" name="departmentSearch">
                        <option :value="0">Выбрать</option>
                        <option v-for="dep in deps" :value="dep.bs_id">{{ dep.name }}</option>
                    </select>
                </div>
                <div style="margin: 15px 0 0 15px;" class="exelsearch">
                    <img v-if="exelLoad === false" @click="exportDrivers()" src="/icons/exel.png" width="24" height="24">
                    <img v-else src="/images/processing.gif" width="24" height="24">
                </div>
                <input @click="clearFilter()" type="button" class="buttsearch cancelAllSearch" name="cancelFilter" value="Сбросить">
                <input @click="saveFilter()" type="button" class="buttsearch submitAllSearch" value="Найти">
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
                    enabled: this.$cookies.get("driverFilter_enabled") ? this.$cookies.get("workerFilter_enabled") : 0,
                    job: this.$cookies.get("driverFilter_job") ? this.$cookies.get("driverFilter") : 0,
                    dep: this.$cookies.get("driverFilter_dep") ? this.$cookies.get("driverFilter_dep") : 0,
                    search: this.$cookies.get("driverFilter_search") ? this.$cookies.get("driverFilter_search") : ''
                },
                deps: [],
                jobs: [],
                modalLoaded: false,
                exelLoad: false,
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
                    $('body').css('overflow', 'auto');
                }
            });

            $(document).on('keydown', function(e){
                if(e.keyCode === 27){
                    e.preventDefault();
                    if($('.window-overlay').is(':visible')) {
                        $('.window-overlay').hide();
                        $('body').css('overflow', 'auto');
                    }
                }
            });

            this.$root.$on('clear-driver-search-form', () => {
                this.clearFilter()
            });
        },
        methods: {
            hideSearchModal() {
                $('.window-overlay').hide();
                $('body').css('overflow', 'auto');
            },
            getSearchModal() {
                axios.post('/api/drivers/get')
                    .then( res => {
                        // console.log(res);
                        this.deps = res.data.deps;
                        this.jobs = res.data.jobs;
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
            exportDrivers() {
                this.exelLoad = true;
                if(this.filter.enabled != 0) {
                    this.$cookies.set("driverFilter_enabled", this.filter.enabled);
                } else {
                    this.$cookies.remove("driverFilter_enabled");
                }
                if(this.filter.search != '') {
                    this.$cookies.set("driverFilter_search", this.filter.search);
                } else {
                    this.$cookies.remove("driverFilter_search");
                }
                if(this.filter.job != 0) {
                    this.$cookies.set("driverFilter", this.filter.job);
                } else {
                    this.$cookies.remove("driverFilter");
                }
                if(this.filter.dep != 0) {
                    this.$cookies.set("driverFilter_dep", this.filter.dep);
                } else {
                    this.$cookies.remove("driverFilter_dep");
                }
                axios.get('/api/drivers/exportDrivers', {responseType: 'blob'})
                    .then( resp => {
                        // console.log(resp);
                        let blob = new Blob([resp.data], { type: "application/vnd.ms-excel" });
                        let link = URL.createObjectURL(blob);
                        let a = document.createElement("a");
                        a.download = "workers.xlsx";
                        a.href = link;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);

                        this.exelLoad = false;
                        this.$cookies.remove("driverFilter_search");
                        this.$cookies.remove("driverFilter_enabled");
                        this.$cookies.remove("driverFilter");
                        this.$cookies.remove("driverFilter_dep");
                        $('.window-overlay').hide();
                        $('body').css('overflow', 'auto');
                    });
            },
            saveFilter() {
                if(this.filter.enabled != 1) {
                    this.$cookies.set("driverFilter_enabled", this.filter.enabled);
                } else {
                    this.$cookies.remove("driverFilter_enabled");
                }
                if(this.filter.search != '') {
                    this.$cookies.set("driverFilter_search", this.filter.search);
                } else {
                    this.$cookies.remove("driverFilter_search");
                }
                if(this.filter.job != 0) {
                    this.$cookies.set("driverFilter", this.filter.job);
                } else {
                    this.$cookies.remove("driverFilter");
                }
                if(this.filter.dep != 0) {
                    this.$cookies.set("driverFilter_dep", this.filter.dep);
                } else {
                    this.$cookies.remove("driverFilter_dep");
                }

                $('.window-overlay').hide();
                $('body').css('overflow', 'auto');
                this.$emit('component_method');
            },
            clearFilter() {
                this.filter.enabled = 0;
                this.filter.job = 0;
                this.filter.dep = 0;
                this.filter.search = '';
                $('.window-overlay').hide();
                $('body').css('overflow', 'auto');
                this.$emit('component_method', true);
            },
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
    #formallsearch input[type=checkbox], #formallsearch input[type=radio] {
        margin: auto;
    }
    .express-check-wrap label {
        margin-left: 5px;
    }
    .submitAllSearch {
        width: 99px;
        margin-right: 10px;
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
    .moneyform {
        height: 25px;
        padding: 2px;
        width: 160px;
    }
</style>
