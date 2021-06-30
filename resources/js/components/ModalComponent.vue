<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-top" style="display: none;" aria-hidden="true">
        <div :class="`modal-dialog modal-dialog-centered ${ size }`" role="document">
            <div class="modal-content js-draggable-modal shadow shadow-lg">
                <div class="modal-header bg-gd-sea border-white-op-t">
                    <h3 class="modal-title">
                        <slot name="title"></slot>
                        {{ title }}
                    </h3>
                    <div class="block-options">
                        <slot name="block-options"></slot>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    export default {
        props: {
            size: {type: String, default: ''},
        },
        data() {
            return {
                title: '',
                modal: '',
            }
        },
        mounted: function(){
            
            this.initModal();
            this.initListeners()
        },
        methods: {

            initModal()
            {
                this.modal = $(this.$el).modal({show:false})
            },
            hide(){

                this.modal.modal('hide')
            },
            idle(b)
            {
            },
            initListeners()
            {
            },
            show()
            {
                this.modal.modal('show')
            },
        }
    }
</script>