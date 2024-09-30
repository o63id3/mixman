<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'
import CardForm from './Partials/CardForm.vue'

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    price_for_consumer: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    // price_for_seller: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors } = useForm({
  validationSchema: formSchema,
})

const onSubmit = handleSubmit((values) => {
  router.post(route('cards.store'), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إنشاء الكرت' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Cards" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        كرت جديد
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit">
      <CardForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
