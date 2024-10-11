<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { useForm } from 'vee-validate'
import { formSchema } from './definitions'
import { useSubmit } from '@/Composables/submit'
import NetworkForm from './Partials/NetworkForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'

const { handleSubmit, resetForm, setErrors } = useForm({
  validationSchema: formSchema,
})

const { submit, loading } = useSubmit(route('networks.store'), {
  method: 'post',
  onSuccess: () => {
    toast({ title: 'تم إنشاء الشبكة' })
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
        شبكة جديدة
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit" :loading="loading">
      <NetworkForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
