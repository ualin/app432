<template>
    <div>
        <button class="btn" @click="add">Adaugare articole</button>
        <button class="btn" @click="renderList">Listare articole</button>

        <table>
            <thead>
                <tr><th>Id</th><th>Title</th><th>Description</th><th>Categories</th></tr>
            </thead>
            <tbody>
                <tr v-for="article in articlesList" :key="article.id">
                    <td>{{article.id}}</td>
                    <td>{{article.title}}</td>
                    <td>{{article.description}}</td>
                    <td>{{article.categories}}</td>
                </tr>
            </tbody>
        </table>
        <form/>
    </div>
</template>

<script>
    import form from './ArticleForm.vue';
    export default {
        components: {form},
        data(){
            return {
                articlesList: []
            }
        },
        methods: {

            add(e) {
                let file = e.target.files[0];

                axios.post('articles/import', file, {
                    headers: {'Content-Type': 'multipart/form-data'}
                })
            },
            renderList(){
                axios.get('articles')
                .then( r=> {

                    let data = r.data;

                    this.articlesList = data;
                })
            }
        }
    }
</script>
