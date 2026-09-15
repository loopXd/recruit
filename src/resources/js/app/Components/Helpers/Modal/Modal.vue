<template>
    <app-modal
        :modal-id="modalId"
        :modal-size="modalSize"
        :modal-backdrop="modalBackdrop"
        modal-alignment="top"
        @close-modal="closeModal"
        :modal-scroll="modalScroll"
        :modal-body-class="modalBodyClass">
        <template v-if="!hideHeader" slot="header">
            <h5 class="modal-title text-capitalize">{{ title }}</h5>
            <button type="button"
                    class="close outline-none"
                    data-dismiss="modal"
                    aria-label="Close"
                    @click.prevent="closeModal">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <slot name="body"></slot>
        </template>

        <template v-if="!hideFooter" slot="footer">
            <div :class="{'loading-opacity': preloader}">
                <button
                    v-if="!hideCancelButton"
                    type="button"
                    class="btn btn-secondary mr-2"
                    data-dismiss="modal"
                    @click.prevent="closeModal">
                    {{ cancelButtonText }}
                </button>
                <button
                    v-if="!hideSubmitButton"
                    type="button"
                    class="btn btn-primary"
                    @click.prevent="submit">
                    {{ submitButtonText }}
                </button>
            </div>
        </template>
    </app-modal>
</template>
<script>
export default {
    name: 'Modal',
    props: {
        title: String,
        modalId: String,
        preloader: {
            type: Boolean,
            default: false
        },
        hideHeader: {
            default: false
        },
        hideFooter: {
            default: false
        },
        modalScroll: {
            type: Boolean,
            default: true,
        },
        modalBodyClass: {},
        modalSize: {
            default: 'default'
        },
        modalBackdrop: {
            type: Boolean,
            default: true
        },
        hideSubmitButton: {
            type: Boolean,
            default: false
        },
        hideCancelButton: {
            type: Boolean,
            default: false
        },
        submitButtonText: {
            default: function () {
                return this.$t('save');
            }
        },
        cancelButtonText: {
            default: function () {
                return this.$t('cancel');
            }
        }
    },
    methods: {
        closeModal() {
            this.$emit('closeModal');
        },
        submit() {
            this.$emit('submit');
        }
    }
}
</script>
