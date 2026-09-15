export default {
    methods:{
        copyLinkMultiple(index) {
            let shareableLinkToCopy = document.querySelector('#shareableLink' + `${'-' + index}`)
            shareableLinkToCopy.setAttribute('type', 'text')
            shareableLinkToCopy.select()

            try {
                let successful = document.execCommand('copy'),
                    msg = successful ? this.$t('zoom_meeting_link_copied_successfully') : this.$t('sorry_you_can_not_copy_the_link');

                this.successfullyCopied = true;
                this.$toastr.s(msg);
            } catch (err) {
                this.successfullyCopied = false;
                this.$toastr.s(this.$t('something_went_wrong'));
            }

            // Unselect the range
            shareableLinkToCopy.setAttribute('type', 'hidden')
            window.getSelection().removeAllRanges()
        },

        copyLinkSingle() {
            let shareableLinkToCopy = document.querySelector('#shareableLink')
            shareableLinkToCopy.setAttribute('type', 'text')
            shareableLinkToCopy.select()

            try {
                let successful = document.execCommand('copy'),
                    msg = successful ? this.$t('zoom_meeting_link_copied_successfully') : this.$t('sorry_you_can_not_copy_the_link');

                this.successfullyCopied = true;
                this.$toastr.s(msg);
            } catch (err) {
                this.successfullyCopied = false;
                this.$toastr.s(this.$t('something_went_wrong'));
            }

            // Unselect the range
            shareableLinkToCopy.setAttribute('type', 'hidden')
            window.getSelection().removeAllRanges()
        },

    }
}