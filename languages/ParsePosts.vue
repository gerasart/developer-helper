<template>
    <div>
        <button @click="sendData">Create posts</button>
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

<script src="./ParsePosts.js"></script>
<style src="./ParsePosts.scss" lang="scss"></style>