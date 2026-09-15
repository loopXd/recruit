<template></template>

<script>
import { FormMixin } from "../../../mixins/form/FormMixin";
import { LANG } from "../../../../app/Config/ApiUrl";

export default {
    name: "LanguageDropdown",
    mixins: [FormMixin],
    props: {
        selectedLanguage: {
            type: String,
            required: true
        },
        data: {
            type: Array,
            required: true
        }
    },
    methods: {
        setLocale(lang) {
            this.axiosPost({
                url: LANG,
                data: {
                    lang: lang,
                },
            }).then(() => {
                window.location.reload();
            });
        },
    },
}
</script>
