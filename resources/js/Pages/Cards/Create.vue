<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import CardForm from './Partials/CardForm.vue'
import { useSubmit } from '@/Composables/submit'
import { toast } from '@/Components/ui/toast'

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    price_for_consumer: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    notes: z.string().optional(),
  }),
)

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
  <Head title="Cards" />

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
