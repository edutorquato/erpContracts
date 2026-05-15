<script setup>

    import AppLayout from '@/Layouts/AppLayoutAdmin.vue'
    import { Head } from '@inertiajs/vue3'
    import { ref } from 'vue'
    import axios from 'axios'

    import {
        formatCurrency,
        formatDate
    } from '@/Utils/formatters'

    const props = defineProps({
        contracts: Array,
        clients: Array,
        services: Array,
    })

    const contracts = ref([...props.contracts])

    const dialog = ref(false)

    const loading = ref(false)

    const errors = ref({})

    const snackbar = ref(false)

    const snackbarText = ref('')

    const snackbarColor = ref('success')


    const showSnackbar = (
        text,
        color = 'success'
        ) => {

        snackbarText.value = text

        snackbarColor.value = color

        snackbar.value = true
    }

    const search = ref('')



    const form = ref({
        client_id: null,
        start_date: '',
        end_date: '',
        status: 'active',
        items: [],
    })

    const headers = [
        { title: 'Cliente', key: 'client.name' },
        { title: 'Início', key: 'start_date' },
        { title: 'Fim', key: 'end_date' },
        { title: 'Status', key: 'status' },
        { title: 'Total', key: 'total' },
        { title: 'Ações', key: 'actions' },
    ]

    const openCreate = () => {

        form.value = {
            client_id: null,
            start_date: '',
            end_date: '',
            status: 'active',
            items: [],
        }

        dialog.value = true
    }

    const addItem = () => {

        form.value.items.push({
            service_id: null,
            quantity: 1,
            unit_value: 0,
        })
    }

    const removeItem = (index) => {

        form.value.items.splice(index, 1)
    }

    const save = async () => {

        loading.value = true

        errors.value = {}

        try {

            let response

            if (form.value.id) {

                response = await axios.put(
            `/admin/contracts/${form.value.id}`,
            form.value
            )

                const index = contracts.value.findIndex(
                    contract => contract.id == form.value.id
                    )

                contracts.value[index] = response.data.contract

                showSnackbar(
                    'Contrato atualizado com sucesso'
                    )

            } else {

                response = await axios.post(
                    '/admin/contracts',
                    form.value
                    )

                contracts.value.unshift(
                    response.data.contract
                    )

                showSnackbar(
                    'Contrato criado com sucesso'
                    )
            }

            dialog.value = false

        } catch (error) {

            if (error.response?.status === 422) {

                errors.value = error.response.data.errors
            }

            showSnackbar(
                'Erro ao salvar contrato',
                'error'
                )

        } finally {

            loading.value = false
        }
    }

    const edit = (contract) => {

        form.value = {
            id: contract.id,
            client_id: contract.client_id,
            
            start_date: contract.start_date
            ? contract.start_date.split('T')[0]
            : '',

            end_date: contract.end_date
            ? contract.end_date.split('T')[0]
            : '',
            
            status: contract.status,

            items: contract.items.map(item => ({
                service_id: item.service_id,
                quantity: item.quantity,
                unit_value: item.unit_value,
            }))
        }

        dialog.value = true

    }

    const remove = async (contract) => {

        if (!confirm(
            'Deseja remover este contrato?'
            )) return

            try {

                await axios.delete(
            `/admin/contracts/${contract.id}`
            )

                contracts.value = contracts.value.filter(
                    item => item.id !== contract.id
                    )

                showSnackbar(
                    'Contrato removido com sucesso'
                    )

            } catch {

                showSnackbar(
                    'Erro ao remover contrato',
                    'error'
                    )
            }
        }

    </script>

    <template>

        <Head title="Contratos" />

        <AppLayout>

            <div class="d-flex justify-space-between align-center mb-6">

                <h1 class="text-h5 font-weight-medium">
                    Contratos
                </h1>

                <v-btn
                color="primary"
                @click="openCreate"
                >
                Novo Contrato
            </v-btn>

        </div>

        <v-card rounded="lg">

            <v-text-field
            v-model="search"
            label="Buscar contrato"
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            density="comfortable"
            hide-details
            class="ma-4"
            />

            <v-data-table
            :headers="headers"
            :items="contracts"
            :search="search"
            :items-per-page="10"
            >

            <template v-slot:item.status="{ item }">

                <v-chip
                :color="item.status == 'active'
                ? 'success'
                : 'error'"
                size="small"
                >
                {{
                    item.status == 'active'
                    ? 'Ativo'
                    : 'Cancelado'
                }}
            </v-chip>

        </template>

        <template v-slot:item.start_date="{ item }">

            {{ formatDate(item.start_date) }}

        </template>

        <template v-slot:item.end_date="{ item }">

            {{ formatDate(item.end_date) }}

        </template>

        <template v-slot:item.total="{ item }">

            <div class="d-flex flex-column">

                <span>
                    {{ formatCurrency(item.total) }}
                </span>

                <small
                v-if="item.total >= 1000"
                class="text-success"
                >
                Desconto aplicado
            </small>

        </div>

    </template>

    <template v-slot:item.actions="{ item }">

        <v-btn
        size="small"
        color="error"
        variant="tonal"
        @click="remove(item)"
        >
        Excluir
    </v-btn>

    <v-btn
    size="small"
    color="primary"
    variant="tonal"
    @click="edit(item)"
    >
    Editar
</v-btn>

</template>

</v-data-table>

</v-card>

<v-dialog
v-model="dialog"
max-width="1000"
>

<v-card rounded="lg">

    <v-card-title>
        Novo Contrato
    </v-card-title>

    <v-card-text>

        <v-row>

            <v-col cols="12" md="6">

                <v-select
                v-model="form.client_id"
                :items="clients"
                item-title="name"
                item-value="id"
                label="Cliente"
                variant="outlined"
                density="comfortable"
                />

            </v-col>

            <v-col cols="12" md="3">

                <v-text-field
                v-model="form.start_date"
                type="date"
                label="Data Início"
                />

            </v-col>

            <v-col cols="12" md="3">

                <v-text-field
                v-model="form.end_date"
                type="date"
                label="Data Fim"
                />

            </v-col>

            <v-col cols="12">

                <v-select
                v-model="form.status"
                label="Status"
                :items="[
                    {
                        title: 'Ativo',
                        value: 'active'
                    },
                    {
                        title: 'Cancelado',
                        value: 'cancelled'
                    }
                    ]"
                    />

                </v-col>

            </v-row>

            <div class="d-flex justify-space-between align-center mt-8 mb-4">

                <h3 class="text-h6">
                    Itens do Contrato
                </h3>

                <v-btn
                color="primary"
                size="small"
                @click="addItem"
                >
                Adicionar Item
            </v-btn>

        </div>

        <v-table>

            <thead>

                <tr>
                    <th>Serviço</th>
                    <th width="120">Qtd</th>
                    <th width="180">Valor</th>
                    <th width="180">Total</th>
                    <th width="100"></th>
                </tr>

            </thead>

            <tbody>

                <tr
                v-for="(item, index) in form.items"
                :key="index"
                >

                <td>

                    <v-select
                    v-model="item.service_id"
                    :items="services"
                    item-title="name"
                    item-value="id"
                    density="compact"
                    hide-details
                    />

                </td>

                <td>

                    <v-text-field
                    v-model="item.quantity"
                    type="number"
                    density="compact"
                    hide-details
                    />

                </td>

                <td>

                    <v-text-field
                    v-model="item.unit_value"
                    type="number"
                    density="compact"
                    hide-details
                    />

                </td>

                <td>

                    {{
                        formatCurrency(
                            item.quantity * item.unit_value
                            )
                        }}

                    </td>

                    <td>

                        <v-btn
                        icon="mdi-delete"
                        color="error"
                        variant="text"
                        @click="removeItem(index)"
                        />

                    </td>

                </tr>

            </tbody>

        </v-table>

        <div class="text-right mt-6 text-h6">

            Total:
            {{
                formatCurrency(
                    form.items.reduce((total, item) => {
                        return total + (
                            item.quantity * item.unit_value
                            )
                    }, 0)
                    )
                }}

            </div>

        </v-card-text>

        <v-card-actions>

            <v-spacer />

            <v-btn
            variant="text"
            @click="dialog = false"
            >
            Cancelar
        </v-btn>

        <v-btn
        color="primary"
        :loading="loading"
        @click="save"
        >
        Salvar Contrato
    </v-btn>

</v-card-actions>

</v-card>

</v-dialog>


<v-snackbar
v-model="snackbar"
:color="snackbarColor"
timeout="3000"
>

{{ snackbarText }}

</v-snackbar>


</AppLayout>

</template>