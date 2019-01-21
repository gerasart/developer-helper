<template>
    <div>
        <button @click="sendData">Create Posts</button>
        <div class="types">
            <div class="title">Post type</div>
            <select v-model="data.current_type">
                <option value="" v-for="type in types" v-bind:value="type">{{type}}</option>
            </select>
        </div>

        <div class="actions">
            <div class="title">Actions</div>
            <select v-model="data.action">
                <option value="" v-for="action in actions" v-bind:value="action.name">{{action.title}}</option>
            </select>
        </div>

        <div class="pages">
            <router-link to="/redirect">redirect</router-link>
        </div>

        <div class="list-data"  v-if="data.action === 'CreatePosts'">
            <textarea v-model="data.posts" placeholder="Test | Тест"></textarea>
        </div>
        <div class="dump">
            <div class="title">Dump</div>
            <pre>{{data}}</pre>
            <pre>{{success}}</pre>
        </div>
    </div>
</template>

<script>
    /* global post_types */
    export default {
        name: 'DemoContent',
        props: ['demo'],
        data() {
            return {
                types: post_types,
                actions: [
                    {
                        name: 'CreatePosts',
                        title: 'Create Posts'
                    },
                    {
                        name: 'DeletePosts',
                        title: 'Delete posts'
                    },
                ],
                data: {
                    action: 'CreatePosts',
                    current_type: 'page',
                    posts: [],

                },
                success: '',
            }
        },
        mounted() {
            this.update();
        },
        methods: {
            update() {
                this.types.push('page', 'post');
            },
            printResult(data) {
                this.success = JSON.stringify(data);
            },
            sendData() {
                const _this_ = this;

                (function ($) {
                    $.post(ajaxurl, _this_.data, (response) => {
                        if (response.success) {
                            _this_.printResult(response.data);
                        }
                    });
                }(jQuery));

            }
        },
    }
</script>

<style lang="scss">
    .list-data textarea {
        width: 100%;
        height: 500px;
    }

    .dump .title {
        margin-top: 40px;
    }

    .dump pre {
        border: 1px solid grey;
        height: 300px;
    }
</style>