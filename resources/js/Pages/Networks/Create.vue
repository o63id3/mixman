<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import NetworkForm from './Partials/NetworkForm.vue'
import { useSubmit } from '@/Composables/submit'

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    internet_price_per_week: z.any().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors } = useForm({
  validationSchema: formSchema,
})

const { submit, loading } = useSubmit(
  route('networks.store'),
  resetForm,
  setErrors,
  'تم إنشاء الشبكة',
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <Head title="Admins" />

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
