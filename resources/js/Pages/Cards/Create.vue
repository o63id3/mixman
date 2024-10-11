<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { useForm } from 'vee-validate'
import { formSchema } from './definitions'
import { useSubmit } from '@/Composables/submit'
import CardForm from './Partials/CardForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'

const { handleSubmit, resetForm, setErrors } = useForm({
  validationSchema: formSchema,
})

const { submit, loading } = useSubmit(route('cards.store'), {
  method: 'post',
  onSuccess: () => {
    toast({ title: 'تم إنشاء الكرت' })
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
        كرت جديد
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit" :loading="loading">
      <CardForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
