export default {
    name: "ParsePosts",
    props: ['demo'],
    data() {
        return {
            types: demo_data.post_types,
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