<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { useForm } from 'vee-validate'
import { formSchema } from './definitions'
import { useSubmit } from '@/Composables/submit'
import ExpenseForm from './Partials/ExpenseForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'

import { Network } from '@/types'

defineProps<{
  networks: Array<Network>
}>()

const { handleSubmit, resetForm, setErrors } = useForm({
  validationSchema: formSchema,
})

const { submit, loading } = useSubmit(route('expenses.store'), {
  method: 'post',
  onSuccess: () => {
    toast({ title: 'تم إنشاء المصروف' })
    resetForm()
  },
  onError: (errors) => setErrors(errors),
})
const onSubmit = handleSubmit(submit)
</script>

<template>
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
