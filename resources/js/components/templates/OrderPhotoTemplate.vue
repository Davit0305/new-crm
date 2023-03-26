<template>
    <div class="d-flex" style="width: 630px;">
        <div class="groupbort inline">
            <span class="title">Фото букета</span>
            <CoolLightBox
                :items="before_images"
                :index="before_index"
                @close="before_index = null">
            </CoolLightBox>

            <div class="images-wrapper">
                <div
                    class="image"
                    :title="before_image.title + ' ' +  before_image.description"
                    v-for="(before_image, imageIndex) in before_images"
                    :key="imageIndex"
                    @click="before_index = imageIndex"
                    :style="{ backgroundImage: 'url(' + before_image.src + ')' }"
                ><i v-if="user.id === before_image.user_id || user.admin === 1" @click.stop="removeImage(imageIndex, 'before')" title="Удалить" class="fa fa-times" aria-hidden="true"></i></div>
            </div>
            <button @click="openModal('before')" type="button" class="button ok_button float-start">
                Добавить
            </button>
        </div>

        <div class="groupbort inline"  style="margin-left: 2%;">
            <span class="title">Фото вручения</span>
            <CoolLightBox
                :items="after_images"
                :index="after_index"
                @close="after_index = null">
            </CoolLightBox>

            <div class="images-wrapper">
                <div
                    class="image"
                    :title="after_image.title + ' ' +  after_image.description"
                    v-for="(after_image, imageIndex) in after_images"
                    :key="imageIndex"
                    @click="after_index = imageIndex"
                    :style="{ backgroundImage: 'url(' + after_image.src + ')' }"
                ><i v-if="user.id === after_image.user_id || user.admin === 1" @click.stop="removeImage(imageIndex, 'after')" title="Удалить" class="fa fa-times" aria-hidden="true"></i></div>
            </div>
            <button @click="openModal('after')" type="button" class="button ok_button float-start">
                Добавить
            </button>
        </div>

        <modal :classes="'load-image-modal'" @before-close="beforeClose" :height="'auto'" name="load-image-modal">
            <div class="dialog-content">
                <div class="dialog-c-title">
                    <span>Загрузка изображений</span>
                    <span @click="closeModal" title="Закрыть" id="closeModal">×</span>
                </div>
                <div class="dialog-c-text">
                    <vue-dropzone v-on:vdropzone-queue-complete="completeDropzone" v-on:vdropzone-sending="sendingEvent" v-on:vdropzone-success="uploadSuccess" ref="myVueDropzone" id="customdropzone" :options="dropzoneOptions"></vue-dropzone>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import CoolLightBox from 'vue-cool-lightbox'

    export default {
        name: "OrderPhotoTemplate",
        components: {
            vueDropzone: vue2Dropzone,
            CoolLightBox: CoolLightBox,
        },
        props: {
            o: Object
        },
        data: function () {
            return {
                order: this.o,
                image_type: '',
                after_images: [],
                after_index: null,
                before_images: [],
                before_index: null,
                user: this.$store.getters.GET_USER,
                dropzoneOptions: {
                    url: '/api/order/storeImage',
                    maxFilesize: 100,
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").getAttribute('value')
                    },
                    dictDefaultMessage: "Для загрузки кликните по форме<br> или перетащите файлы в эту форму"
                }
            };
        },
        created() {
            this.order.photos.forEach((entry) => {
                let type = entry.type == 1 ? 'before' : 'after';
                let new_data = {
                    title: entry.user_fio,
                    description: entry.rus_time,
                    user_id: entry.user.id,
                    image_id: entry.id,
                    src: '/storage/images/order_' + type + '/' + entry.file,
                };
                if(entry.type == 1) {
                    this.before_images.push(new_data);
                } else {
                    this.after_images.push(new_data);
                }
            });
        },
        methods: {
            sendingEvent (file, xhr, formData) {
                formData.append('order_id', this.order.number);
                formData.append('type', this.image_type);
            },
            closeModal () {
                this.$modal.hide('load-image-modal');
            },
            uploadSuccess (file, response) {
                let type = response.file.type === 1 ? 'before' : 'after';
                let new_data = {
                    title: response.file.user_fio,
                    description: response.file.rus_time,
                    user_id: response.file.user.id,
                    image_id: response.file.id,
                    src: '/storage/images/order_' + type + '/' + response.file.file,
                };
                this.order.photos.push(response.file);
                if(response.file.type === 1) {
                    this.before_images.push(new_data);
                } else {
                    this.after_images.push(new_data);
                }
            },
            completeDropzone() {
                this.$modal.hide('load-image-modal');
            },
            removeImage(index, type) {
                window.swal.fire({
                    title: 'Вы уверены?',
                    text: "Вы не сможете восстановить это фото!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Отмена',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#ada2a2',
                    confirmButtonText: 'Да, удалить',
                    customClass: {
                        container: 'z-index-max',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let image_id = 0;
                        if(type === 'before') {
                            image_id = this.before_images[index].image_id;
                            this.before_images.splice(index, 1);
                        } else {
                            image_id = this.after_images[index].image_id;
                            this.after_images.splice(index, 1);
                        }
                        for (var i = 0; i < this.order.photos.length; i++) {
                            if (this.order.photos[i].id === image_id) {
                                this.order.photos.splice(i, 1);
                            }
                        }

                        axios.post('/api/order/removeImage', {image_id: image_id})
                            .then( res => {
                                // console.log(res);
                                // window.swal.fire({
                                //     title: 'Успешно!',
                                //     icon: 'success',
                                //     showConfirmButton: false,
                                //     timer: 1000,
                                //     customClass: {
                                //         container: 'z-index-max',
                                //     }
                                // })
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
                    }
                })
            },
            openModal (type) {
                this.image_type = type;
                this.$modal.show('load-image-modal');
            },
            beforeClose (event) {
                this.image_type = '';
            },
            // uploadImages() {
            //     axios.post('/api/order/getTemporaryFiles', {order_id: this.order.number, type: this.image_type})
            //         .then( res => {
            //             if(res.data.status == 'success') {
            //                 if(res.data.files.length > 0) {
            //                     if(this.image_type === 'before') {
            //                         res.data.files.forEach((entry) => {
            //                             let new_data = {
            //                                 title: entry.user_fio,
            //                                 description: entry.rus_time,
            //                                 user_id: entry.user.id,
            //                                 image_id: entry.id,
            //                                 src: '/storage/images/order_before/' + entry.file,
            //                             }
            //                             this.before_images.push(new_data);
            //                         });
            //                     } else {
            //                         res.data.files.forEach((entry) => {
            //                             let new_data = {
            //                                 title: entry.user_fio,
            //                                 description: entry.rus_time,
            //                                 user_id: entry.user.id,
            //                                 image_id: entry.id,
            //                                 src: '/storage/images/order_after/' + entry.file,
            //                             }
            //                             this.after_images.push(new_data);
            //                         });
            //                     }
            //                 }
            //                 this.$modal.hide('load-image-modal');
            //             } else {
            //                 alert('Ошибка загрузки!')
            //             }
            //             console.log(res);
            //         })
            // }
        }
    }
</script>

<style scoped>
    .load-image-modal .dialog-content {
        flex: 1 0 auto;
        width: 100%;
        padding: 15px;
        font-size: 14px;
        text-align: left;
    }
    .load-image-modal .dialog-c-title {
        color: #a5a5a5;
        font-weight: 300;
        padding-bottom: 15px;
        line-height: 1.2;
        margin-bottom: 0;
        font-size: 1.5rem;
    }
    .load-image-modal div {
        box-sizing: border-box;
    }
    .images-wrapper {
        display: flex;
        width: 285px;
        flex-wrap: wrap;
        justify-content: left;
    }
    .images-wrapper .image {
        position: relative;
        cursor: pointer;
        background-position: 50%;
        background-repeat: no-repeat;
        background-size: cover;
        width: 85px;
        height: 85px;
        margin: 5px;
    }
    .images-wrapper .image i {
        position: absolute;
        right: -5px;
        top: -5px;
    }
    #closeModal {
        float: right;
        margin-top: -10px;
        margin-right: -2px;
        cursor: pointer;
    }
    .swal2-containe {
        z-index: 99999999;
    }
</style>
