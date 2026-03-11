<script setup>

	import AppLayout from '@/Layouts/AppLayoutAdmin.vue'
	import { Head, useForm } from '@inertiajs/vue3'

	const props = defineProps({
		user: Object,
		data: Object
	})

	const form = useForm({
		name: props.user.name,
		email: props.user.email,

		person_type: props.data.person_type,
		full_name: props.data.full_name,
		document: props.data.document,
		phone: props.data.phone,

		cnh_file: null,
		instructor_credential: null,
		criminal_record: null,
	})

	const submit = () => {
		form.post(route('admin.users.update', props.user.id))
	}

</script>

<template>

	<Head title="Editar usuário" />

	<AppLayout>

		<h1 class="text-h5 mb-6">
			Editar usuário
		</h1>

		<v-form @submit.prevent="submit">

<!-- CARD 1 - INFORMAÇÕES DA CONTA -->

<v-card class="mb-6" rounded="lg">

	<v-card-title>
		Informações da conta
	</v-card-title>

	<v-card-text>

		<v-text-field
		label="Nome"
		v-model="form.name"
		/>

		<v-text-field
		label="Email"
		v-model="form.email"
		/>

	</v-card-text>

</v-card>


<!-- CARD 2 - DADOS PESSOAIS -->

<v-card class="mb-6" rounded="lg">

	<v-card-title>
		Dados pessoais
	</v-card-title>

	<v-card-text>

		<v-text-field
		label="Nome completo"
		v-model="form.full_name"
		/>

		<v-text-field
		label="Documento"
		v-model="form.document"
		/>

		<v-text-field
		label="Telefone"
		v-model="form.phone"
		/>

	</v-card-text>

</v-card>


<!-- CARD 3 - DOCUMENTOS -->

<v-card class="mb-6" rounded="lg">

	<v-card-title>
		Documentos
	</v-card-title>

	<v-card-text>

		<template v-if="form.person_type === 'cpf'">

			<v-file-input
			label="CNH Definitiva"
			accept=".png,.pdf"
			@change="e => form.cnh_file = e.target.files[0]"
			/>

			<v-file-input
			label="Credencial de Instrutor"
			accept=".png,.pdf"
			@change="e => form.instructor_credential = e.target.files[0]"
			/>

			<v-file-input
			label="Antecedentes Criminais"
			accept=".png,.pdf"
			@change="e => form.criminal_record = e.target.files[0]"
			/>

		</template>

	</v-card-text>

</v-card>


<!-- BOTÕES -->

<div class="d-flex justify-space-between">

	<v-btn
	color="grey"
	:href="route('admin.users.show', user.id)"
	>
	Cancelar
</v-btn>

<v-btn
color="primary"
type="submit"
:loading="form.processing"
>
Salvar alterações
</v-btn>

</div>

</v-form>

</AppLayout>

</template>