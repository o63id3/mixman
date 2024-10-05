<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import ExpenseForm from './Partials/ExpenseForm.vue'
import { Network } from '@/types'
import { useSubmit } from '@/Components/Composables/submit'

defineProps<{
  networks: Array<Network>
}>()

const formSchema = toTypedSchema(
  z.object({
    description: z.string({ message: 'هذا الحقل مطلوب' }),
    network_id: z.string({ message: 'هذا الحقل مطلوب' }),
    amount: z.number({ message: 'هذا الحقل مطلوب' }),
  }),
)

const { handleSubmit, resetForm, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
})

const { submit, loading } = useSubmit(
  route('expenses.store'),
  resetForm,
  setErrors,
  'تم إنشاء المصروف',
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <Head title="Expenses" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit" :loading="loading">
      <ExpenseForm :networks="networks" />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
