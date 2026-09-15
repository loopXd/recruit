<template>
    <app-avatars-group
        :avatars-group-class="avatarsGroupClass"
        :name="avatarsGroupName+index"
        :avatars="users"
        :border="true"
        :shadow="true"
    />
</template>

<script>
    export default {
        name: "GroupOfUsers",
        props: {
            value: {},
            rowData: {},
            index: {}
        },
        data(){
            return {
                avatarsGroupClass: 'avatars-group-w-50',
                avatarsGroupName: 'groupOfUsers',
            }
        },
        computed:{
            users(){
                return this.value.map(item => {
                    return {
                        img: item.profile_picture?.full_url,
                        alt: item.first_name,
                        title: item.full_name,
                        subTitle: item.email
                    }
                })
            }
        }
    }
</script>
