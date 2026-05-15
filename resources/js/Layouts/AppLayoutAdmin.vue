<script setup>

    import { ref } from 'vue'
    import { router, usePage } from '@inertiajs/vue3'
    import { computed } from 'vue'

    const drawer = ref(true)
    const logout = () => {
        router.post(route('logout'))
    }

    const page = usePage()
    const user = page.props.auth.user

    const roleLabel = computed(() => {
        if (!user) return ''
            switch (user.level) {
            case 1:
                return 'Admin'
            case 2:
                return 'Partner'
            default:
                return 'Usuário'
            }
        })

    </script>

    <template>

        <v-app>

            <v-navigation-drawer app permanent color="#2A3FA8">

                <v-list class="d-flex flex-column h-100">

                    <v-list-item class="justify-center">
                        <center><h2>ERP CONTRACTS</h2></center>
                    </v-list-item>

                    <div class="mt-2">

                        <!-- DASHBOARD -->
                        <v-list-item :href="route('admin.dashboard')">

                            <template #prepend>
                                <v-icon>mdi-view-dashboard</v-icon>
                            </template>

                            <v-list-item-title class="text-body-2">
                                Dashboard
                            </v-list-item-title>

                        </v-list-item>

                        <!-- CLIENTES -->
                        <v-list-item :href="route('admin.clients')">

                            <template #prepend>
                                <v-icon>mdi-account-group</v-icon>
                            </template>

                            <v-list-item-title class="text-body-2">
                                Clientes
                            </v-list-item-title>

                        </v-list-item>

                        <!-- SERVIÇOS -->
                        <v-list-item :href="route('admin.services')">

                            <template #prepend>
                                <v-icon>mdi-briefcase</v-icon>
                            </template>

                            <v-list-item-title class="text-body-2">
                                Serviços
                            </v-list-item-title>

                        </v-list-item>

                        <!-- CONTRATOS -->
                        <v-list-item :href="route('admin.contracts')">

                            <template #prepend>
                                <v-icon>mdi-file-document-outline</v-icon>
                            </template>

                            <v-list-item-title class="text-body-2">
                                Contratos
                            </v-list-item-title>

                        </v-list-item>

                    </div>

                    <v-spacer />

                    <v-list-item class="px-4 py-3">

                        <template #prepend>
                            <v-avatar color="grey-lighten-1" size="40">
                                <span class="text-subtitle-2 font-weight-bold">
                                    {{ user.name.substring(0,2).toUpperCase() }}
                                </span>
                            </v-avatar>
                        </template>

                        <div class="d-flex flex-column">
                            <v-chip
                            size="x-small"
                            rounded="pill"
                            class="role-badge" style="width: 36%;"
                            >
                            {{ roleLabel }}
                        </v-chip>

                        <v-list-item-title class="text-body-2 font-weight-medium mt-1">
                            {{ user.name }}
                        </v-list-item-title>
                    </div>

                    <template #append>
                        <v-btn
                        icon="mdi-logout"
                        variant="text"
                        size="small"
                        @click="logout"
                        />
                    </template>

                </v-list-item>

            </v-list>

        </v-navigation-drawer>

        <v-main class="bg-grey-lighten-4">
            <v-container class="pa-12">
                <slot />
            </v-container>
        </v-main>

    </v-app>

</template>