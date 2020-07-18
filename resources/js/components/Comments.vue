<template>
    <div>
        <new-comment :endpoint="endpoint" @created="add"></new-comment>

        <ul v-if="items.length > 0" class="list-group">
            <div v-for="(comment, index) in items">
                <comment :attributes="comment"></comment>
                <hr>
            </div>
        </ul>

        <div v-else class="empty-block">暂时没有任何人发表评论~</div>
    </div>

</template>

<script>
    import Comment from './Comment';
    import NewComment from "./NewComment";

    export default {
        props: ['data', 'subject'],

        components: { Comment, NewComment },

        data() {
            return {
                items:this.data,
                endpoint:this.subject.commentEndpoint
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },
        },

        methods: {
            add(comment) {
                // 将添加的 comment 放到最前面
                this.items.unshift(comment);

                // 发送 addComment 事件
                this.$emit('addComment');
            }
        }
    }
</script>
