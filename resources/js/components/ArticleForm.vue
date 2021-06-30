<template>
   <modal ref="myModal">
        <template #body>
            <form method="post" onsubmit="return false;" @submit.prevent='save'>
                <div class="form-group row">
                    <div class="col-6">
                        <div class="form-material">
                            <label for="">Title</label>
                            <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" v-model="form.title">
                            <div v-if="form.errors.has('title')" v-html="form.errors.get('title')" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-material">
                            <label for="">Description</label>
                            <input type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }" v-model="form.description">
                            <div v-if="form.errors.has('description')" v-html="form.errors.get('description')" />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    
                    <div class="col">
                        <div class="form-material">
                            <label for="">Description</label>
                            <select multiple size="10" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }" v-model="form.categories">
                                <option v-for="category in categories" :key="category.id" :value='category.id'>{{ category.title }}</option>
                            </select>
                            <div v-if="form.errors.has('categories')" v-html="form.errors.get('categories')" />
                        </div>
                    </div>
                </div>
            </form>
        </template>
        <template #footer>
            <div>
                <button type="button" class="btn btn-alt-success" @click="save">Save</button>
                <button type='button' class="btn btn-alt-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </template>
    </modal>
</template>
<script>
    import modal from './ModalComponent.vue';
    export default {
        components:{modal},
        data() {
            return {
                rooms: [],
                editMode: false,
                form: new Form({
                    id:'',
                    name : '',
                    rooms: []
                }),
                categories: [],
                modal: null,
                canHide: true,
            }
        },
        mounted:function()
        {
            this.init();
            this.initListeners()
        },
        methods: {

            createModal()
            {
                this.editMode = false;
                this.initForm();
                this.form.reset();
                this.$refs.myModal.title = 'Create';

                this.$refs.myModal.show()
            },
            editModal(id)
            {
                this.editMode = true;
                
                this.form.reset();
                this.$refs.myModal.title = 'Edit';
                this.$refs.myModal.show()


                this.$refs.myModal.idle(true);

                axios.get(`api/articles/${id}`)
                .then((r1)=>{
                    
                    let data = r1.data;

                    axios.get(`api/articles/${id}/categories`)
                    .then( r2=> data.categories = r2.data)
                    
                    this.initForm(data)
                    this.form.fill(this.form.data());

                    this.$refs.myModal.idle(false);
                    
                })
            },
            hiddenHandler(e)
            {
                this.canHide = false;
            },
            hideHandler(e)
            {
                if(this.canHide)
                {}
                else
                { 
                    e.preventDefault();

                    if(this.validateDirty() == true)
                    {
                        // but form is changed

                            if (confirm('Data unsaved. Dismiss?'))
                            {
                                this.canHide = true;
                                this.$refs.myModal.hide()
                            }
                            else {}
                    }
                    else
                    {
                        // form is unchanged. It's ok, modal can hide

                        this.canHide = true;

                        this.$refs.myModal.hide()
                    }
                }
                // modal wants to hide
            },
            initListeners()
            {
                $(this.$refs.myModal.$el).on('hide.bs.modal', e =>{ this.hideHandler(e) })
                $(this.$refs.myModal.$el).on('hidden.bs.modal', e =>{ this.hiddenHandler(e) })
            },
            init()
            {
                axios.get('/api/articles')
                .then(r=>{this.rooms = r.data})
                
                axios.get('/api/categories')
                .then(r=>{this.categories = r.data})
            },
            initForm(d)
            {
                if(d)
                {
                    let data = {
                        id: d.id,
                        name : d.name,
                        rooms: d.rooms
                    };

                    this.form = new Form(data)
                }
                else
                {
                    this.form = new Form({
                        id:'',
                        name : '',
                        rooms: []
                    })
                }
            },
            save()
            { 
                if(this.editMode)
                {
                    this.update();
                }
                else
                {
                    this.store();
                }
            },
            store()
            {
                this.$refs.myModal.idle(true);
                let data = this.form.data();


                this.form.post('api/articles')
                .then( data =>{
                    this.canHide = true;
                    this.$emit('saved');
                    this.$refs.myModal.hide()
                })
                .catch((err)=>{})
                .then(()=>{this.$refs.myModal.idle(false)});
            },
            update()
            {
                this.$refs.myModal.idle(true);

                this.form.put('api/articles/'+this.form.id)
                .then(() => {
                    // success
                    this.canHide = true;
                    this.$emit('saved');
                    this.$refs.myModal.hide()
                })
                .catch(() => {})
                .then(()=>{this.$refs.myModal.idle(false)});
            },
            validateDirty()
            {
                let oData = this.form.originalData;
                let dirty = false;

                for( const prop in oData)
                {
                    if(this.form.hasOwnProperty(prop))
                    {
                        if(typeof this.form[prop] === 'object')
                        {
                            if(JSON.stringify(this.form[prop]) != JSON.stringify(oData[prop]))
                            {
                                // alert(`${prop} changed`)
                                dirty = true;
                                break;
                            }
                        }
                        else if(this.form[prop] != oData[prop])
                        {
                            // alert(`${prop} changed`)
                            dirty = true;
                            break;
                        }
                    }
                }

                return dirty;
            },
        },
    }
</script>