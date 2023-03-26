<template>
    <div class="commentBlock">
        <div class="commentBlock_scroll" v-slimscroll>
            <div v-for="comment in order.comments" class="bort">
                <span class="span">{{ comment.user.fio }} {{ comment.date_rus }}</span>
                <span v-if="comment.date_edit_rus !== comment.date_rus" class="float-end span" title="Дата редактирования">ред. {{comment.date_edit_rus}}</span>
                <textarea @input="showButton(comment.id)" v-model="comment.text" class="comment" :disabled="user.id == comment.user_id || user.admin == 1 ? false : true "></textarea>
                <div :id="'button_wrap_' + comment.id" class="button-wrap" style="display: none">
                    <button @click="updateComment(comment.id, comment.text)" type="button" class="button ok_button float-end">
                        <div :id="'add_comment_button_text_' + comment.id">Сохранить</div>
                        <img :id="'add_comment_button_img_' + comment.id" src="/images/processing.gif">
                    </button>
                </div>
            </div>
        </div>
        <div class="bort">
            <span class="span">Новый комментарий</span>
            <textarea v-model="user_comment" name="comment" class="comment" placeholder="Укажите комментарий к заказу"></textarea>
            <div v-if="user_comment.trim().length > 0" class="button-wrap">
                <button @click="addNewComment()" type="button" class="button ok_button float-end">
                    <div id="add_comment_button_text">Добавить</div>
                    <img id="add_comment_button_img" src="/images/processing.gif">
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderCommentsTemplate",
        props: {
            o: Object
        },
        data: function () {
            return {
                order: this.o,
                user: this.$store.getters.GET_USER,
                user_comment: ''
            };
        },
        watch: {
            'user_comment': function (newVal, oldVal) {
                this.order.user_new_comment = newVal;
            },
        },
        methods: {
            updateComment(id, text) {
                if(text.trim().length === 0) {
                    alert('Введите текст комментария!');
                    return false;
                }
                $('#add_comment_button_text_' + id).hide();
                $('#add_comment_button_img_' + id).show();
                axios.post('/api/order/updateComment', {id: id, text: text.trim()})
                    .then( res => {
                        // console.log(res);
                        for (var i = 0; i < this.order.comments.length; i++) {
                            if (this.order.comments[i].id === id) {
                                this.order.comments[i].date_edit_rus = res.data.date_edit_rus
                            }
                        }
                        $('#add_comment_button_text_' + id).show();
                        $('#add_comment_button_img_' + id).hide();
                        $('#button_wrap_' + id).hide()
                        window.swal.fire({
                            title: 'Успешно!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000,
                            customClass: {
                                container: 'z-index-max',
                            }
                        })
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
            addNewComment() {
                if(this.user_comment.trim().length === 0) {
                    alert('Введите текст комментария!');
                    return false;
                }
                $('#add_comment_button_text').hide();
                $('#add_comment_button_img').show();
                axios.post('/api/order/addNewComment', {text: this.user_comment.trim(), order_id: this.order.id})
                    .then( res => {
                        // console.log(res);
                        this.order.comments.unshift(res.data);
                        $('#add_comment_button_text').show();
                        $('#add_comment_button_img').hide();
                        this.user_comment = '';
                        window.swal.fire({
                            title: 'Успешно!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000,
                            customClass: {
                                container: 'z-index-max',
                            }
                        })
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
            showButton(id) {
                $('#button_wrap_' + id).show()
            },
        }
    }
</script>

<style scoped>
    .commentBlock {
        margin-bottom: 15px;
    }
    .commentBlock .bort {
        padding: 10px 5px 9px 5px;
        margin-top: 15px;
        text-align: left;
        min-height: 40px;
    }
    .commentBlock .bort textarea {
        width: 100%;
        height: 45px;
        padding: 7px;
    }
    .button-wrap {
        height: 34px;
    }
    .button {
        margin: 5px auto 0;
    }
</style>
