<script setup>

    import AppLayout from '@/Layouts/AppLayoutAdmin.vue'
    import { Head } from '@inertiajs/vue3'

    defineProps({
        user: Object,
        stats: Object,
    })

</script>

<template>

    <Head title="Dashboard" />
    
    <AppLayout>

        <div class="mb-6">
            <h1 class="text-h5 font-weight-medium">
                Dashboard
            </h1>
        </div>

        <v-card rounded="lg" class="pa-8">
            <div>
                Dashboard
            </div>
        </v-card>

    </AppLayout>

</template>