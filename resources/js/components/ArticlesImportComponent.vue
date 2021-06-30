<template>
    <div class="form-group">
        <input type="file" name="articlesList" id="" class="form-control">
        <button type="button" @click='submit'>Submit</button>
    </div>
</template>

<script>
    
    export default {
        components: {articleForm},
        methods: {

            submit(e) {
                let file = e.target.files[0];

                axios.post('articles/import', file, {
                    headers: {'Content-Type': 'multipart/form-data'}
                })
            }
        }
    }
</script>
